@extends('layouts.portal')

@section('page-title', 'Revenue Report')

@section('header-actions')
<a href="{{ route('portal.reports.export', 'revenue') }}"
   class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
    Export CSV
</a>
@endsection

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Gross</p>
            <p class="mt-2 text-2xl font-bold text-gray-800">{{ number_format($totalGross, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Net</p>
            <p class="mt-2 text-2xl font-bold text-green-700">{{ number_format($totalNet, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Distributions</p>
            <p class="mt-2 text-2xl font-bold text-yellow-600">{{ number_format($pending, 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Net Revenue by Source Type</p>
        <ul class="space-y-2">
            @foreach($byType as $type => $total)
            <li class="flex items-center justify-between text-sm">
                <span class="text-gray-700">{{ ucfirst($type) }}</span>
                <span class="font-semibold text-gray-800">{{ number_format($total, 2) }}</span>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">All Revenue Records</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Type</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-600">Gross</th>
                        <th class="text-right px-5 py-3 font-medium text-gray-600">Net</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Currency</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                        <th class="text-center px-5 py-3 font-medium text-gray-600">Distributions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $r)
                    <tr>
                        <td class="px-5 py-3 text-gray-700">{{ ucfirst($r->source_type) }}</td>
                        <td class="px-5 py-3 text-right text-gray-600">{{ number_format($r->gross_amount, 2) }}</td>
                        <td class="px-5 py-3 text-right font-medium text-green-700">{{ number_format($r->net_amount, 2) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $r->currency }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $r->received_date->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-center text-gray-500">
                            {{ $r->distributions->where('payment_status','paid')->count() }} / {{ $r->distributions->count() }} paid
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
