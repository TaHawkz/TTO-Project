@extends('layouts.portal')

@section('page-title', 'Commercialization Report')

@section('header-actions')
<a href="{{ route('portal.reports.export', 'commercialization') }}"
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
                    <span class="text-gray-700">{{ ucwords(str_replace('_', ' ', $status)) }}</span>
                    <span class="font-semibold text-gray-800">{{ $count }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">By Type</p>
            <ul class="mt-3 space-y-2">
                @foreach($byType as $type => $count)
                <li class="flex items-center justify-between text-sm">
                    <span class="text-gray-700">{{ ucwords(str_replace('_', ' ', $type)) }}</span>
                    <span class="font-semibold text-gray-800">{{ $count }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">All Records ({{ $records->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Type</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Partner</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($records as $r)
                    <tr>
                        <td class="px-5 py-3">
                            <a href="{{ route('portal.commercialization.show', $r) }}" class="text-teal-700 hover:underline">{{ $r->title }}</a>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ ucwords(str_replace('_',' ',$r->type)) }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ ucwords(str_replace('_',' ',$r->status)) }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $r->partner_name ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">No records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
