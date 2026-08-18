@extends('layouts.portal')

@section('page-title', 'Commercialization')

@section('header-actions')
<a href="{{ route('portal.commercialization.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    + New Record
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="type" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">All types</option>
                @foreach(['licensing','startup','joint_development','direct_sale'] as $t)
                <option value="{{ $t }}" @selected(request('type')===$t)>{{ ucwords(str_replace('_',' ',$t)) }}</option>
                @endforeach
            </select>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">All statuses</option>
                @foreach(['evaluation','industry_engagement','negotiation','agreement_executed','active','closed'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
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
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Partner</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Manager</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($records as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $r->title }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $r->type_label }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $r->status === 'active' ? 'bg-green-100 text-green-700' :
                               ($r->status === 'closed' ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-700') }}">
                            {{ $r->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $r->partner_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $r->manager->name }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('portal.commercialization.show', $r) }}" class="text-teal-700 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No commercialization records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $records->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
