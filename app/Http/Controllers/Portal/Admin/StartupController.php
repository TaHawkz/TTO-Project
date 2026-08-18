<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Startup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StartupController extends Controller
{
    public function index(): View
    {
        $startups = Startup::latest()->paginate(20);

        return view('portal.admin.startups.index', compact('startups'));
    }

    public function create(): View
    {
        return view('portal.admin.startups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'founders'           => 'nullable|array',
            'founders.*'         => 'string|max:255',
            'faculty_advisor'    => 'nullable|string|max:255',
            'technology_used'    => 'nullable|string|max:255',
            'incorporation_date' => 'nullable|date',
            'funding_status'     => 'nullable|string|max:255',
            'funding_amount'     => 'nullable|string|max:255',
            'website'            => 'nullable|url|max:255',
            'description'        => 'nullable|string',
            'industry_sector'    => 'nullable|string|max:255',
            'is_published'       => 'required|boolean',
        ]);

        Startup::create($validated);

        return redirect()->route('portal.admin.startups.index')
            ->with('success', 'Startup created.');
    }

    public function edit(Startup $startup): View
    {
        return view('portal.admin.startups.edit', compact('startup'));
    }

    public function update(Request $request, Startup $startup): RedirectResponse
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'founders'           => 'nullable|array',
            'founders.*'         => 'string|max:255',
            'faculty_advisor'    => 'nullable|string|max:255',
            'technology_used'    => 'nullable|string|max:255',
            'incorporation_date' => 'nullable|date',
            'funding_status'     => 'nullable|string|max:255',
            'funding_amount'     => 'nullable|string|max:255',
            'website'            => 'nullable|url|max:255',
            'description'        => 'nullable|string',
            'industry_sector'    => 'nullable|string|max:255',
            'is_published'       => 'required|boolean',
        ]);

        $startup->update($validated);

        return redirect()->route('portal.admin.startups.index')
            ->with('success', 'Startup updated.');
    }

    public function destroy(Startup $startup): RedirectResponse
    {
        $startup->update(['is_published' => false]);

        return redirect()->route('portal.admin.startups.index')
            ->with('success', 'Startup unpublished.');
    }
}
