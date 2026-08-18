<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Disclosure;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DisclosureController extends Controller
{
    public function index(Request $request): View
    {
        $user  = Auth::user();
        $query = Disclosure::query()->forUser($user)->with('submitter', 'inventors');

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $disclosures = $query->latest()->paginate(15)->withQueryString();

        return view('portal.disclosures.index', compact('disclosures'));
    }

    public function create(): View
    {
        return view('portal.disclosures.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'                    => 'required|string|max:500',
            'abstract'                 => 'required|string',
            'description'              => 'required|string',
            'technical_field'          => 'required|string|max:255',
            'problem_solved'           => 'required|string',
            'novel_features'           => 'required|string',
            'potential_applications'   => 'required|string',
            'industry_sector'          => 'required|string|max:255',
            'existing_alternatives'    => 'nullable|string',
            'funding_source'           => 'nullable|string|max:255',
            'sponsor_info'             => 'nullable|string',
            'project_reference'        => 'nullable|string|max:255',
            'inventors'                => 'required|array|min:1',
            'inventors.*.name'         => 'required|string|max:255',
            'inventors.*.email'        => 'required|email',
            'inventors.*.department'   => 'nullable|string|max:255',
            'inventors.*.designation'  => 'nullable|string|max:255',
            'documents'                => 'nullable|array',
            'documents.*'              => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        $data = collect($validated)->except(['inventors', 'documents'])->all();

        $disclosure = Disclosure::create([
            ...$data,
            'submitted_by' => Auth::id(),
            'status'       => 'draft',
        ]);

        foreach ($request->inventors as $i => $inv) {
            $disclosure->inventors()->create([
                ...$inv,
                'is_primary' => ($i === 0),
            ]);
        }

        foreach ($request->file('documents', []) as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs("disclosures/{$disclosure->id}", $filename, 'private');
            $disclosure->documents()->create([
                'uploaded_by'   => Auth::id(),
                'filename'      => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $file->getMimeType(),
                'file_size'     => $file->getSize(),
                'path'          => $path,
            ]);
        }

        return redirect()->route('portal.disclosures.show', $disclosure)
            ->with('success', 'Disclosure saved as draft.');
    }

    public function show(Disclosure $disclosure): View
    {
        $this->authorizeView($disclosure);
        $disclosure->load('submitter', 'inventors', 'documents.uploader', 'patent', 'assignment', 'assignee');

        $reviewers = Auth::user()->canReviewDisclosures()
            ? User::whereIn('role', ['reviewer', 'tto_officer', 'legal_officer', 'director', 'system_admin'])
                ->where('is_active', true)
                ->get(['id', 'name', 'role'])
            : collect();

        return view('portal.disclosures.show', compact('disclosure', 'reviewers'));
    }

    public function edit(Disclosure $disclosure): View
    {
        abort_unless(
            Auth::id() === $disclosure->submitted_by && $disclosure->status === 'draft',
            403
        );
        return view('portal.disclosures.edit', compact('disclosure'));
    }

    public function update(Request $request, Disclosure $disclosure): RedirectResponse
    {
        abort_unless(
            Auth::id() === $disclosure->submitted_by && $disclosure->status === 'draft',
            403
        );

        $validated = $request->validate([
            'title'                    => 'required|string|max:500',
            'abstract'                 => 'required|string',
            'description'              => 'required|string',
            'technical_field'          => 'required|string|max:255',
            'problem_solved'           => 'required|string',
            'novel_features'           => 'required|string',
            'potential_applications'   => 'required|string',
            'industry_sector'          => 'required|string|max:255',
            'existing_alternatives'    => 'nullable|string',
            'funding_source'           => 'nullable|string|max:255',
            'sponsor_info'             => 'nullable|string',
            'project_reference'        => 'nullable|string|max:255',
            'inventors'                => 'required|array|min:1',
            'inventors.*.name'         => 'required|string|max:255',
            'inventors.*.email'        => 'required|email',
            'inventors.*.department'   => 'nullable|string|max:255',
            'inventors.*.designation'  => 'nullable|string|max:255',
        ]);

        $data = collect($validated)->except(['inventors'])->all();
        $disclosure->update($data);

        $disclosure->inventors()->delete();
        foreach ($request->inventors as $i => $inv) {
            $disclosure->inventors()->create([
                ...$inv,
                'is_primary' => ($i === 0),
            ]);
        }

        return redirect()->route('portal.disclosures.show', $disclosure)
            ->with('success', 'Draft updated.');
    }

    public function submit(Request $request, Disclosure $disclosure): RedirectResponse
    {
        abort_unless(
            Auth::id() === $disclosure->submitted_by && $disclosure->status === 'draft',
            403
        );

        DB::transaction(function () use ($disclosure) {
            $disclosure->update([
                'status'         => 'submitted',
                'disclosure_id'  => Disclosure::generateDisclosureId(),
                'submitted_at'   => now(),
            ]);
        });

        return redirect()->route('portal.disclosures.show', $disclosure)
            ->with('success', "Disclosure submitted. Your ID is {$disclosure->disclosure_id}.");
    }

    public function updateStatus(Request $request, Disclosure $disclosure): RedirectResponse
    {
        $request->validate([
            'status'           => 'required|in:draft,submitted,under_review,ownership_determined,patentability_assessed,committee_review,approved,rejected,patent_filing,commercializing',
            'reviewer_notes'   => 'nullable|string',
            'rejection_reason' => 'nullable|string',
        ]);

        $disclosure->update($request->only('status', 'reviewer_notes', 'rejection_reason'));

        return back()->with('success', 'Status updated.');
    }

    public function assign(Request $request, Disclosure $disclosure): RedirectResponse
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $disclosure->update(['assigned_to' => $request->assigned_to]);

        return back()->with('success', 'Assignee updated.');
    }

    private function authorizeView(Disclosure $disclosure): void
    {
        $user = Auth::user();
        abort_unless(
            $user->canReviewDisclosures() || $disclosure->submitted_by === $user->id,
            403
        );
    }
}
