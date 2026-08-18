@extends('layouts.portal')

@section('page-title', 'IP Ownership Assignments')

@section('header-actions')
<a href="{{ route('portal.assignments.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    + New Assignment
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Disclosure</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Outcome</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Determined By</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assignments as $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800 truncate max-w-xs">{{ $a->disclosure->title }}</p>
                        <p class="text-xs text-gray-400">{{ $a->disclosure->disclosure_id }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-teal-100 text-teal-700">
                            {{ $a->outcome_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $a->determinedBy->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $a->determination_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('portal.assignments.show', $a) }}" class="text-teal-700 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">No assignments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assignments->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $assignments->links() }}</div>
    @endif
</div>
@endsection
