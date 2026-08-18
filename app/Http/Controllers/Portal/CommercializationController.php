<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Commercialization;
use App\Models\Disclosure;
use App\Models\Patent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CommercializationController extends Controller
{
    public function index(Request $request): View
    {
        $records = Commercialization::with('patent', 'disclosure', 'manager')
            ->when($request->filled('type'),   fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('portal.commercialization.index', compact('records'));
    }

    public function create(): View
    {
        $patents     = Patent::whereIn('status', ['filed', 'granted'])->get(['id', 'title', 'patent_number']);
        $disclosures = Disclosure::whereIn('status', ['approved', 'commercializing'])->get(['id', 'title', 'disclosure_id']);

        return view('portal.commercialization.create', compact('patents', 'disclosures'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patent_id'       => 'nullable|exists:patents,id',
            'disclosure_id'   => 'nullable|exists:disclosures,id',
            'title'           => 'required|string|max:500',
            'type'            => 'required|in:licensing,startup,joint_development,direct_sale',
            'status'          => 'required|in:evaluation,industry_engagement,negotiation,agreement_executed,active,closed',
            'partner_name'    => 'nullable|string|max:255',
            'partner_contact' => 'nullable|string|max:255',
            'partner_email'   => 'nullable|email|max:255',
            'description'     => 'nullable|string',
            'notes'           => 'nullable|string',
        ]);

        Commercialization::create([...$validated, 'managed_by' => Auth::id()]);

        return redirect()->route('portal.commercialization.index')
            ->with('success', 'Record created.');
    }

    public function show(Commercialization $commercialization): View
    {
        $commercialization->load('patent', 'disclosure', 'manager');
        return view('portal.commercialization.show', compact('commercialization'));
    }

    public function edit(Commercialization $commercialization): View
    {
        $patents     = Patent::whereIn('status', ['filed', 'granted'])->get(['id', 'title', 'patent_number']);
        $disclosures = Disclosure::whereIn('status', ['approved', 'commercializing'])->get(['id', 'title', 'disclosure_id']);

        return view('portal.commercialization.edit', compact('commercialization', 'patents', 'disclosures'));
    }

    public function update(Request $request, Commercialization $commercialization): RedirectResponse
    {
        $validated = $request->validate([
            'patent_id'       => 'nullable|exists:patents,id',
            'disclosure_id'   => 'nullable|exists:disclosures,id',
            'title'           => 'required|string|max:500',
            'type'            => 'required|in:licensing,startup,joint_development,direct_sale',
            'status'          => 'required|in:evaluation,industry_engagement,negotiation,agreement_executed,active,closed',
            'partner_name'    => 'nullable|string|max:255',
            'partner_contact' => 'nullable|string|max:255',
            'partner_email'   => 'nullable|email|max:255',
            'description'     => 'nullable|string',
            'notes'           => 'nullable|string',
        ]);

        $commercialization->update($validated);

        return redirect()->route('portal.commercialization.show', $commercialization)
            ->with('success', 'Record updated.');
    }
}
