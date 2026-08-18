@extends('layouts.portal')

@section('page-title', 'Reports')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

    @foreach([
        ['route' => 'portal.reports.disclosures', 'export' => 'disclosures', 'title' => 'Disclosures Report', 'desc' => 'Status breakdown, sector distribution, monthly trends.', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['route' => 'portal.reports.patents', 'export' => 'patents', 'title' => 'Patents Report', 'desc' => 'Portfolio status, jurisdictions, expiring patents.', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['route' => 'portal.reports.revenue', 'export' => 'revenue', 'title' => 'Revenue Report', 'desc' => 'Total received, pending distributions, by source type.', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['route' => 'portal.reports.commercialization', 'export' => 'commercialization', 'title' => 'Commercialization Report', 'desc' => 'Active deals, pipeline status, by type.', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
    ] as $card)
    <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col gap-4">
        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-gray-800">{{ $card['title'] }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ $card['desc'] }}</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route($card['route']) }}"
               class="px-3 py-1.5 bg-teal-700 text-white text-xs font-medium rounded-lg hover:bg-teal-800 transition-colors">
                View Report
            </a>
            <a href="{{ route('portal.reports.export', $card['export']) }}"
               class="px-3 py-1.5 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                CSV Export
            </a>
        </div>
    </div>
    @endforeach

</div>
@endsection
