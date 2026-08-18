<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechnologyController extends Controller
{
    public function index(): View
    {
        $technologies = Technology::orderBy('sort_order')->orderBy('title')->paginate(20);

        return view('portal.admin.technologies.index', compact('technologies'));
    }

    public function create(): View
    {
        return view('portal.admin.technologies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'industry_sector'     => 'required|string|max:255',
            'development_stage'   => 'required|in:early_stage,filed,granted,licensed',
            'benefits'            => 'nullable|array',
            'benefits.*'          => 'string|max:255',
            'licensing_available' => 'required|boolean',
            'contact_email'       => 'nullable|email|max:255',
            'is_published'        => 'required|boolean',
            'sort_order'          => 'nullable|integer|min:0',
        ]);

        Technology::create($validated);

        return redirect()->route('portal.admin.technologies.index')
            ->with('success', 'Technology created.');
    }

    public function edit(Technology $technology): View
    {
        return view('portal.admin.technologies.edit', compact('technology'));
    }

    public function update(Request $request, Technology $technology): RedirectResponse
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'industry_sector'     => 'required|string|max:255',
            'development_stage'   => 'required|in:early_stage,filed,granted,licensed',
            'benefits'            => 'nullable|array',
            'benefits.*'          => 'string|max:255',
            'licensing_available' => 'required|boolean',
            'contact_email'       => 'nullable|email|max:255',
            'is_published'        => 'required|boolean',
            'sort_order'          => 'nullable|integer|min:0',
        ]);

        $technology->update($validated);

        return redirect()->route('portal.admin.technologies.index')
            ->with('success', 'Technology updated.');
    }

    public function destroy(Technology $technology): RedirectResponse
    {
        $technology->update(['is_published' => false]);

        return redirect()->route('portal.admin.technologies.index')
            ->with('success', 'Technology unpublished.');
    }
}
