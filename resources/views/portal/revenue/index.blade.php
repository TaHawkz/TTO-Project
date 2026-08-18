@extends('layouts.portal')

@section('page-title', 'Revenue Records')

@section('header-actions')
<a href="{{ route('portal.revenue.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    + Record Revenue
</a>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Net Revenue</p>
            <p class="mt-2 text-2xl font-bold text-green-700">{{ number_format($totalNet, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Distributions</p>
            <p class="mt-2 text-2xl font-bold text-yellow-600">{{ number_format($pendingDistributions, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Records</p>
            <p class="mt-2 text-2xl font-bold text-gray-800">{{ $records->total() }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Source</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Type</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-600">Gross</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-600">Net</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Currency</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Received</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-700">
                            {{ $r->disclosure?->disclosure_id ?? $r->patent?->patent_number ?? $r->agreement?->title ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ ucwords(str_replace('_',' ',$r->source_type)) }}</td>
                        <td class="px-5 py-3 text-right text-gray-700">{{ number_format($r->gross_amount, 2) }}</td>
                        <td class="px-5 py-3 text-right font-medium text-green-700">{{ number_format($r->net_amount, 2) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $r->currency }}</td>
                        <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $r->received_date->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('portal.revenue.show', $r) }}" class="text-teal-700 hover:underline text-xs font-medium">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No revenue records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $records->links() }}</div>
        @endif
    </div>
</div>
@endsection
