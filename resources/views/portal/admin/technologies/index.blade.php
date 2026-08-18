@extends('layouts.portal')

@section('page-title', 'Technologies')

@section('header-actions')
<a href="{{ route('portal.admin.technologies.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    + New Technology
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Sector</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Stage</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Licensing</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Published</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($technologies as $t)
                <tr class="hover:bg-gray-50 {{ !$t->is_published ? 'opacity-60' : '' }}">
                    <td class="px-5 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $t->title }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $t->industry_sector }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $t->stage_label }}</td>
                    <td class="px-5 py-3">
                        @if($t->licensing_available)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">Yes</span>
                        @else
                        <span class="text-xs text-gray-400">No</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        @if($t->is_published)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-teal-100 text-teal-700 font-medium">Published</span>
                        @else
                        <span class="text-xs text-gray-400">Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right flex items-center justify-end gap-3">
                        <a href="{{ route('portal.admin.technologies.edit', $t) }}" class="text-teal-700 hover:underline text-xs font-medium">Edit</a>
                        @if($t->is_published)
                        <form method="POST" action="{{ route('portal.admin.technologies.destroy', $t) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Unpublish this technology?')"
                                    class="text-xs text-red-500 hover:underline">Unpublish</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No technologies yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($technologies->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $technologies->links() }}</div>
    @endif
</div>
@endsection
