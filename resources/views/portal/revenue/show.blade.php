@extends('layouts.portal')

@section('page-title', 'Revenue Record')

@section('content')
<div class="max-w-3xl space-y-6 mx-auto">

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Revenue Details</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div><dt class="text-gray-500">Source Type</dt><dd class="mt-0.5 font-medium">{{ ucfirst($revenue->source_type) }}</dd></div>
            <div><dt class="text-gray-500">Received Date</dt><dd class="mt-0.5">{{ $revenue->received_date->format('M d, Y') }}</dd></div>
            <div><dt class="text-gray-500">Currency</dt><dd class="mt-0.5">{{ $revenue->currency }}</dd></div>
            <div><dt class="text-gray-500">Gross Amount</dt><dd class="mt-0.5">{{ number_format($revenue->gross_amount, 2) }}</dd></div>
            <div><dt class="text-gray-500">Deductions</dt><dd class="mt-0.5">{{ number_format($revenue->deductions, 2) }}</dd></div>
            <div><dt class="text-gray-500">Net Amount</dt><dd class="mt-0.5 text-green-700 font-bold text-base">{{ number_format($revenue->net_amount, 2) }}</dd></div>
            <div><dt class="text-gray-500">Recorded By</dt><dd class="mt-0.5">{{ $revenue->recorder->name }}</dd></div>
            @if($revenue->disclosure)
            <div><dt class="text-gray-500">Disclosure</dt>
                <dd class="mt-0.5"><a href="{{ route('portal.disclosures.show', $revenue->disclosure) }}" class="text-teal-700 hover:underline">{{ $revenue->disclosure->disclosure_id }}</a></dd>
            </div>
            @endif
            @if($revenue->patent)
            <div><dt class="text-gray-500">Patent</dt>
                <dd class="mt-0.5"><a href="{{ route('portal.patents.show', $revenue->patent) }}" class="text-teal-700 hover:underline">{{ $revenue->patent->patent_number ?? $revenue->patent->title }}</a></dd>
            </div>
            @endif
        </dl>
        @if($revenue->notes)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-500 font-medium mb-1">Notes</p>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $revenue->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Distributions --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Revenue Distributions</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-4 py-2 font-medium text-gray-600">Recipient</th>
                        <th class="text-left px-4 py-2 font-medium text-gray-600">Type</th>
                        <th class="text-right px-4 py-2 font-medium text-gray-600">%</th>
                        <th class="text-right px-4 py-2 font-medium text-gray-600">Amount</th>
                        <th class="text-left px-4 py-2 font-medium text-gray-600">Status</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($revenue->distributions as $dist)
                    <tr>
                        <td class="px-4 py-3 text-gray-800">{{ $dist->recipient_name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ ucfirst($dist->recipient_type) }}</td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ $dist->percentage }}%</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-800">{{ number_format($dist->amount, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($dist->payment_status === 'paid')
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">
                                Paid {{ $dist->paid_at?->format('M d, Y') }}
                            </span>
                            @else
                            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 font-medium">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if($dist->payment_status === 'pending')
                            <form method="POST" action="{{ route('portal.revenue.distributions.mark-paid', [$revenue, $dist]) }}" class="inline flex items-center gap-2">
                                @csrf
                                <input type="date" name="paid_at" value="{{ now()->format('Y-m-d') }}" required
                                       class="border border-gray-200 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-teal-500">
                                <button type="submit" class="text-xs text-teal-700 hover:underline font-medium">Mark Paid</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <a href="{{ route('portal.revenue.index') }}" class="text-sm text-teal-700 hover:underline">← Back to Revenue</a>
    </div>
</div>
@endsection
