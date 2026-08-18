<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Agreement;
use App\Models\Disclosure;
use App\Models\Patent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AgreementController extends Controller
{
    public function index(Request $request): View
    {
        $agreements = Agreement::with('disclosure', 'patent', 'manager')
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('portal.agreements.index', compact('agreements'));
    }

    public function create(): View
    {
        $disclosures = Disclosure::whereIn('status', ['approved', 'commercializing'])
            ->get(['id', 'title', 'disclosure_id']);
        $patents = Patent::all(['id', 'title', 'patent_number']);

        return view('portal.agreements.create', compact('disclosures', 'patents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:500',
            'type'         => 'required|in:assignment,nda_cda,revenue_sharing,sponsored_research,licensing,other',
            'disclosure_id' => 'nullable|exists:disclosures,id',
            'patent_id'    => 'nullable|exists:patents,id',
            'parties'      => 'required|array|min:1',
            'parties.*'    => 'required|string|max:255',
            'signed_date'  => 'nullable|date',
            'expiry_date'  => 'nullable|date',
            'status'       => 'required|in:draft,under_review,signed,expired,terminated',
            'document'     => 'nullable|file|mimes:pdf,doc,docx|max:20480',
        ]);

        $data = collect($validated)->except(['document'])->all();
        $data['managed_by'] = Auth::id();

        if ($request->hasFile('document')) {
            $data['document_path'] = $request->file('document')->store('agreements', 'private');
        }

        Agreement::create($data);

        return redirect()->route('portal.agreements.index')
            ->with('success', 'Agreement created.');
    }

    public function show(Agreement $agreement): View
    {
        $agreement->load('disclosure', 'patent', 'manager');
        return view('portal.agreements.show', compact('agreement'));
    }

    public function edit(Agreement $agreement): View
    {
        $disclosures = Disclosure::whereIn('status', ['approved', 'commercializing'])
            ->get(['id', 'title', 'disclosure_id']);
        $patents = Patent::all(['id', 'title', 'patent_number']);

        return view('portal.agreements.edit', compact('agreement', 'disclosures', 'patents'));
    }

    public function update(Request $request, Agreement $agreement): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:500',
            'type'         => 'required|in:assignment,nda_cda,revenue_sharing,sponsored_research,licensing,other',
            'disclosure_id' => 'nullable|exists:disclosures,id',
            'patent_id'    => 'nullable|exists:patents,id',
            'parties'      => 'required|array|min:1',
            'parties.*'    => 'required|string|max:255',
            'signed_date'  => 'nullable|date',
            'expiry_date'  => 'nullable|date',
            'status'       => 'required|in:draft,under_review,signed,expired,terminated',
            'document'     => 'nullable|file|mimes:pdf,doc,docx|max:20480',
        ]);

        $data = collect($validated)->except(['document'])->all();

        if ($request->hasFile('document')) {
            if ($agreement->document_path) {
                Storage::disk('private')->delete($agreement->document_path);
            }
            $data['document_path'] = $request->file('document')->store('agreements', 'private');
        }

        $agreement->update($data);

        return redirect()->route('portal.agreements.show', $agreement)
            ->with('success', 'Agreement updated.');
    }

    public function download(Agreement $agreement): StreamedResponse
    {
        abort_unless(
            $agreement->document_path && Storage::disk('private')->exists($agreement->document_path),
            404
        );

        return Storage::disk('private')->download($agreement->document_path);
    }
}
