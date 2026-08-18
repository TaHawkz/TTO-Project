<?php

namespace App\Http\Controllers\Portal\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('search'), fn ($q) => $q
                ->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
            )
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('portal.admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('portal.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role'        => 'required|in:student,faculty,staff,reviewer,tto_officer,legal_officer,director,system_admin',
            'department'  => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'is_active'   => 'required|boolean',
        ]);

        $user->update($validated);

        return redirect()->route('portal.admin.users.index')
            ->with('success', "User {$user->name} updated.");
    }
}
