<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Patent;
use App\Models\PatentDeadline;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PatentDeadlineController extends Controller
{
    public function store(Request $request, Patent $patent): RedirectResponse
    {
        $validated = $request->validate([
            'deadline_type' => 'required|string|max:255',
            'due_date'      => 'required|date',
            'notes'         => 'nullable|string',
        ]);

        $patent->deadlines()->create($validated);

        return back()->with('success', 'Deadline added.');
    }

    public function update(Request $request, Patent $patent, PatentDeadline $deadline): RedirectResponse
    {
        $validated = $request->validate([
            'is_completed' => 'required|boolean',
            'completed_at' => 'nullable|date',
            'notes'        => 'nullable|string',
        ]);

        $deadline->update($validated);

        return back()->with('success', 'Deadline updated.');
    }

    public function destroy(Patent $patent, PatentDeadline $deadline): RedirectResponse
    {
        $deadline->delete();

        return back()->with('success', 'Deadline removed.');
    }
}
