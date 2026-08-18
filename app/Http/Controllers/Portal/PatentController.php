<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Disclosure;
use App\Models\Patent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatentController extends Controller
{
    public function index(Request $request): View
    {
        $patents = Patent::with('disclosure', 'manager')
            ->when($request->filled('status'),       fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('jurisdiction'), fn ($q) => $q->where('jurisdiction', $request->jurisdiction))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $expiringCount = Patent::where('status', 'granted')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->count();

        return view('portal.patents.index', compact('patents', 'expiringCount'));
    }

    public function create(): View
    {
        $disclosures = Disclosure::where('status', 'approved')
            ->whereDoesntHave('patent')
            ->get(['id', 'title', 'disclosure_id']);

        return view('portal.patents.create', compact('disclosures'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'disclosure_id'    => 'nullable|exists:disclosures,id',
            'title'            => 'required|string|max:500',
            'patent_number'    => 'nullable|string|max:100',
            'status'           => 'required|in:draft,filed,published,examination,granted,expired,abandoned',
            'jurisdiction'     => 'required|string|max:10',
            'filing_date'      => 'nullable|date',
            'publication_date' => 'nullable|date',
            'grant_date'       => 'nullable|date',
            'expiry_date'      => 'nullable|date',
            'applicant'        => 'required|string|max:255',
            'attorney_firm'    => 'nullable|string|max:255',
            'attorney_contact' => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
        ]);

        Patent::create([...$validated, 'managed_by' => Auth::id()]);

        return redirect()->route('portal.patents.index')
            ->with('success', 'Patent record created.');
    }

    public function show(Patent $patent): View
    {
        $patent->load('disclosure', 'manager', 'deadlines', 'agreements', 'commercialization');
        return view('portal.patents.show', compact('patent'));
    }

    public function edit(Patent $patent): View
    {
        $disclosures = Disclosure::where('status', 'approved')->get(['id', 'title', 'disclosure_id']);
        return view('portal.patents.edit', compact('patent', 'disclosures'));
    }

    public function update(Request $request, Patent $patent): RedirectResponse
    {
        $validated = $request->validate([
            'disclosure_id'    => 'nullable|exists:disclosures,id',
            'title'            => 'required|string|max:500',
            'patent_number'    => 'nullable|string|max:100',
            'status'           => 'required|in:draft,filed,published,examination,granted,expired,abandoned',
            'jurisdiction'     => 'required|string|max:10',
            'filing_date'      => 'nullable|date',
            'publication_date' => 'nullable|date',
            'grant_date'       => 'nullable|date',
            'expiry_date'      => 'nullable|date',
            'applicant'        => 'required|string|max:255',
            'attorney_firm'    => 'nullable|string|max:255',
            'attorney_contact' => 'nullable|string|max:255',
            'notes'            => 'nullable|string',
        ]);

        $patent->update($validated);

        return redirect()->route('portal.patents.show', $patent)
            ->with('success', 'Patent updated.');
    }
}
