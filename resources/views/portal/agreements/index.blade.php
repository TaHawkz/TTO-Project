@extends('layouts.portal')

@section('page-title', 'Agreements')

@section('header-actions')
<a href="{{ route('portal.agreements.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    + New Agreement
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="type" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">All types</option>
                @foreach(['assignment','nda_cda','revenue_sharing','sponsored_research','licensing','other'] as $t)
                <option value="{{ $t }}" @selected(request('type')===$t)>{{ ucwords(str_replace('_',' ',$t)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-sm rounded-lg">Filter</button>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Type</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Signed</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Expiry</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($agreements as $ag)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $ag->title }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $ag->type_label }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $ag->status === 'signed' ? 'bg-green-100 text-green-700' :
                               ($ag->status === 'expired' ? 'bg-gray-100 text-gray-600' :
                               ($ag->status === 'terminated' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) }}">
                            {{ ucfirst($ag->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $ag->signed_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $ag->expiry_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('portal.agreements.show', $ag) }}" class="text-teal-700 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No agreements found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($agreements->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $agreements->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
