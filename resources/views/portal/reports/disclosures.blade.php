@extends('layouts.portal')

@section('page-title', 'Disclosures Report')

@section('header-actions')
<a href="{{ route('portal.reports.export', 'disclosures') }}"
   class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
    Export CSV
</a>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">By Status</p>
            <ul class="mt-3 space-y-2">
                @foreach($byStatus as $status => $count)
                <li class="flex items-center justify-between text-sm">
                    <span class="text-gray-700">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                    <span class="font-semibold text-gray-800">{{ $count }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">By Industry Sector</p>
            <ul class="mt-3 space-y-2">
                @foreach($bySector as $sector => $count)
                <li class="flex items-center justify-between text-sm">
                    <span class="text-gray-700 truncate max-w-[160px]">{{ $sector ?: 'Unknown' }}</span>
                    <span class="font-semibold text-gray-800">{{ $count }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly ({{ now()->year }})</p>
            <ul class="mt-3 space-y-2">
                @foreach(range(1, 12) as $m)
                @if(isset($monthly[$m]))
                <li class="flex items-center justify-between text-sm">
                    <span class="text-gray-700">{{ now()->setMonth($m)->format('M') }}</span>
                    <span class="font-semibold text-gray-800">{{ $monthly[$m] }}</span>
                </li>
                @endif
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">All Disclosures ({{ $list->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">ID</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Sector</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Submitted By</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Submitted At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($list as $d)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $d->disclosure_id ?? '—' }}</td>
                        <td class="px-5 py-3 max-w-xs">
                            <a href="{{ route('portal.disclosures.show', $d) }}" class="text-teal-700 hover:underline truncate block">{{ $d->title }}</a>
                        </td>
                        <td class="px-5 py-3">{{ $d->status_label }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $d->industry_sector }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $d->submitter->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $d->submitted_at?->format('M d, Y') ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No disclosures.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
