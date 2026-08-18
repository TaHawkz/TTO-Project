@extends('layouts.portal')

@section('page-title', 'Patent Portfolio')

@section('header-actions')
<a href="{{ route('portal.patents.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    + New Patent
</a>
@endsection

@section('content')
<div class="space-y-4">

    @if($expiringCount > 0)
    <div class="bg-orange-50 border border-orange-200 rounded-xl px-5 py-3 text-sm text-orange-800 flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <strong>{{ $expiringCount }} patent{{ $expiringCount > 1 ? 's' : '' }}</strong> expiring within 90 days.
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
            <form method="GET" class="flex flex-wrap gap-3">
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">All statuses</option>
                    @foreach(['draft','filed','published','examination','granted','expired','abandoned'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <input type="text" name="jurisdiction" value="{{ request('jurisdiction') }}" placeholder="Jurisdiction (e.g. BD)"
                       class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm w-40 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <button type="submit" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-sm rounded-lg">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Patent No.</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Jurisdiction</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Filing Date</th>
                        <th class="text-left px-5 py-3 font-medium text-gray-600">Expiry</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($patents as $p)
                    <tr class="hover:bg-gray-50 {{ $p->is_expiring_soon ? 'bg-orange-50' : '' }}">
                        <td class="px-5 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $p->title }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $p->patent_number ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $p->status === 'granted' ? 'bg-green-100 text-green-700' :
                                   ($p->status === 'filed' ? 'bg-blue-100 text-blue-700' :
                                   ($p->status === 'expired' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ $p->status_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $p->jurisdiction }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $p->filing_date?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500 {{ $p->is_expiring_soon ? 'text-orange-600 font-medium' : '' }}">
                            {{ $p->expiry_date?->format('M d, Y') ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('portal.patents.show', $p) }}" class="text-teal-700 hover:underline text-xs font-medium">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No patents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patents->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $patents->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
