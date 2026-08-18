<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Disclosure;
use App\Models\Patent;
use App\Models\RevenueDistribution;
use App\Models\RevenueRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RevenueController extends Controller
{
    public function index(Request $request): View
    {
        $records = RevenueRecord::with('disclosure', 'patent', 'recorder', 'distributions')
            ->latest('received_date')
            ->paginate(15);

        $totalNet            = RevenueRecord::sum('net_amount');
        $pendingDistributions = RevenueDistribution::where('payment_status', 'pending')->sum('amount');

        return view('portal.revenue.index', compact('records', 'totalNet', 'pendingDistributions'));
    }

    public function show(RevenueRecord $revenue): View
    {
        $revenue->load('distributions.recipientUser', 'agreement', 'disclosure', 'patent');
        return view('portal.revenue.show', compact('revenue'));
    }

    public function create(): View
    {
        $disclosures = Disclosure::all(['id', 'title', 'disclosure_id']);
        $patents     = Patent::all(['id', 'title', 'patent_number']);
        $agreements  = Agreement::where('status', 'signed')->get(['id', 'title']);

        return view('portal.revenue.create', compact('disclosures', 'patents', 'agreements'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'source_type'                         => 'required|in:licensing,royalty,milestone,other',
            'agreement_id'                        => 'nullable|exists:agreements,id',
            'disclosure_id'                       => 'nullable|exists:disclosures,id',
            'patent_id'                           => 'nullable|exists:patents,id',
            'gross_amount'                        => 'required|numeric|min:0',
            'deductions'                          => 'required|numeric|min:0',
            'received_date'                       => 'required|date',
            'currency'                            => 'required|string|max:10',
            'notes'                               => 'nullable|string',
            'distributions'                       => 'required|array|min:1',
            'distributions.*.recipient_type'      => 'required|in:inventor,department,university',
            'distributions.*.recipient_name'      => 'required|string|max:255',
            'distributions.*.recipient_user_id'   => 'nullable|exists:users,id',
            'distributions.*.percentage'          => 'required|numeric|min:0|max:100',
        ]);

        $total = collect($validated['distributions'])->sum('percentage');
        if (abs($total - 100) > 0.01) {
            return back()
                ->withInput()
                ->withErrors(['distributions' => 'Distribution percentages must sum to 100%.']);
        }

        $net  = $validated['gross_amount'] - $validated['deductions'];
        $data = collect($validated)->except(['distributions'])->all();

        $record = RevenueRecord::create([
            ...$data,
            'net_amount'  => $net,
            'recorded_by' => Auth::id(),
        ]);

        foreach ($validated['distributions'] as $dist) {
            $record->distributions()->create([
                ...$dist,
                'amount' => round($net * ($dist['percentage'] / 100), 2),
            ]);
        }

        return redirect()->route('portal.revenue.show', $record)
            ->with('success', 'Revenue recorded.');
    }

    public function markPaid(Request $request, RevenueRecord $revenue, RevenueDistribution $distribution): RedirectResponse
    {
        $request->validate([
            'paid_at' => 'required|date',
        ]);

        $distribution->update([
            'payment_status' => 'paid',
            'paid_at'        => $request->paid_at,
        ]);

        return back()->with('success', 'Marked as paid.');
    }
}
