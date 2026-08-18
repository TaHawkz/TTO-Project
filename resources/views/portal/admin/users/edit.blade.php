@extends('layouts.portal')

@section('page-title', 'Edit User — ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('portal.admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <div class="pb-3 border-b border-gray-100">
                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 mt-1">Registered {{ $user->created_at->format('M d, Y') }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @foreach(['student','faculty','staff','reviewer','tto_officer','legal_officer','director','system_admin'] as $r)
                    <option value="{{ $r }}" @selected(old('role',$user->role)===$r)>{{ ucwords(str_replace('_',' ',$r)) }}</option>
                    @endforeach
                </select>
                @error('role')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <input type="text" name="department" value="{{ old('department', $user->department) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Designation</label>
                <input type="text" name="designation" value="{{ old('designation', $user->designation) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       @checked(old('is_active', $user->is_active))
                       class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                <label for="is_active" class="text-sm text-gray-700">Account is active</label>
            </div>

            @if(Auth::id() === $user->id && !$user->is_active)
            <p class="text-xs text-red-600">Warning: deactivating your own account will lock you out.</p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Save Changes
            </button>
            <a href="{{ route('portal.admin.users.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
