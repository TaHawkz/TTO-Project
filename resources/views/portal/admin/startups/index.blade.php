@extends('layouts.portal')

@section('page-title', 'Startups')

@section('header-actions')
<a href="{{ route('portal.admin.startups.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    + New Startup
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Name</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Sector</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Incorporated</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Funding</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Published</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($startups as $s)
                <tr class="hover:bg-gray-50 {{ !$s->is_published ? 'opacity-60' : '' }}">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $s->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $s->industry_sector ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $s->incorporation_date?->format('M Y') ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $s->funding_status ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @if($s->is_published)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-teal-100 text-teal-700 font-medium">Published</span>
                        @else
                        <span class="text-xs text-gray-400">Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right flex items-center justify-end gap-3">
                        <a href="{{ route('portal.admin.startups.edit', $s) }}" class="text-teal-700 hover:underline text-xs font-medium">Edit</a>
                        @if($s->is_published)
                        <form method="POST" action="{{ route('portal.admin.startups.destroy', $s) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Unpublish this startup?')"
                                    class="text-xs text-red-500 hover:underline">Unpublish</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No startups yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($startups->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $startups->links() }}</div>
    @endif
</div>
@endsection
