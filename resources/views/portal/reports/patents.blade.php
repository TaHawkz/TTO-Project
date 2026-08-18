@extends('layouts.portal')

@section('page-title', 'Patents Report')

@section('header-actions')
<a href="{{ route('portal.reports.export', 'patents') }}"
   class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
    Export CSV
</a>
@endsection

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">By Status</p>
            <ul class="mt-3 space-y-2">
                @foreach($byStatus as $status => $count)
                <li class="flex items-center justify-between text-sm">
                    <span class="text-gray-700">{{ ucfirst($status) }}</span>
                    <span class="font-semibold text-gray-800">{{ $count }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">By Jurisdiction</p>
            <ul class="mt-3 space-y-2">
                @foreach($byJurisdiction as $j => $count)
                <li class="flex items-center justify-between text-sm">
                    <span class="text-gray-700">{{ $j }}</span>
                    <span class="font-semibold text-gray-800">{{ $count }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    @if($expiringSoon->isNotEmpty())
    <div class="bg-white rounded-xl border border-orange-200 p-5">
        <h2 class="font-semibold text-orange-700 mb-3">Expiring Within 90 Days ({{ $expiringSoon->count() }})</h2>
        <ul class="space-y-2">
            @foreach($expiringSoon as $p)
            <li class="flex items-center justify-between text-sm">
                <a href="{{ route('portal.patents.show', $p) }}" class="text-teal-700 hover:underline">{{ $p->title }}</a>
                <span class="text-orange-600 font-medium">{{ $p->expiry_date->format('M d, Y') }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
@endsection
