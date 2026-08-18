<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Disclosure;
use App\Models\IpAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $assignments = IpAssignment::with('disclosure', 'determinedBy')
            ->latest()
            ->paginate(15);

        return view('portal.assignments.index', compact('assignments'));
    }

    public function show(IpAssignment $assignment): View
    {
        $assignment->load('disclosure.inventors', 'determinedBy');
        return view('portal.assignments.show', compact('assignment'));
    }

    public function create(): View
    {
        $disclosures = Disclosure::whereDoesntHave('assignment')
            ->whereNotIn('status', ['draft'])
            ->get(['id', 'title', 'disclosure_id']);

        return view('portal.assignments.create', compact('disclosures'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'disclosure_id'      => 'required|exists:disclosures,id|unique:ip_assignments,disclosure_id',
            'outcome'            => 'required|in:university,inventor,joint,sponsored_research',
            'determination_date' => 'required|date',
            'notes'              => 'nullable|string',
        ]);

        IpAssignment::create([...$validated, 'determined_by' => Auth::id()]);

        Disclosure::find($request->disclosure_id)
            ->update(['status' => 'ownership_determined']);

        return redirect()->route('portal.assignments.index')
            ->with('success', 'Ownership determination recorded.');
    }
}
