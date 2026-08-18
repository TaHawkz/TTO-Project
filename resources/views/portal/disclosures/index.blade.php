@extends('layouts.portal')

@section('page-title', 'Disclosures')

@section('header-actions')
<a href="{{ route('portal.disclosures.create') }}"
   class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    + New Disclosure
</a>
@endsection

@section('content')
<div class="bg-white rounded-xl border border-gray-200">

    {{-- Filters --}}
    <div class="px-5 py-4 border-b border-gray-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search title…"
                   class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">All statuses</option>
                @foreach(['draft','submitted','under_review','ownership_determined','patentability_assessed','committee_review','approved','rejected','patent_filing','commercializing'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-sm rounded-lg transition-colors">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('portal.disclosures.index') }}" class="px-4 py-1.5 text-sm text-gray-500 hover:underline self-center">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">ID</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Title</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Submitted By</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-left px-5 py-3 font-medium text-gray-600">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($disclosures as $d)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $d->disclosure_id ?? '—' }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800 max-w-xs truncate">{{ $d->title }}</td>
                    <td class="px-5 py-3 text-gray-600 whitespace-nowrap">{{ $d->submitter->name }}</td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-blue-100 text-blue-700">
                            {{ $d->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $d->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('portal.disclosures.show', $d) }}" class="text-teal-700 hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No disclosures found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($disclosures->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $disclosures->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
