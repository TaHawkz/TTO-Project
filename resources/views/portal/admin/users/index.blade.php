@extends('layouts.portal')

@section('page-title', 'User Management')

@section('content')
<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email…"
                   class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <select name="role" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">All roles</option>
                @foreach(['student','faculty','staff','reviewer','tto_officer','legal_officer','director','system_admin'] as $r)
                <option value="{{ $r }}" @selected(request('role')===$r)>{{ ucwords(str_replace('_',' ',$r)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-sm rounded-lg">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Name</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Email</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Role</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Department</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $u)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $u->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $u->email }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-blue-100 text-blue-700">
                            {{ $u->role_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $u->department ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @if($u->is_active)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">Active</span>
                        @else
                        <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-medium">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('portal.admin.users.edit', $u) }}" class="text-teal-700 hover:underline text-xs font-medium">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $users->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
