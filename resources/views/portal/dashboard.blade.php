@extends('layouts.portal')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Stats grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @if(isset($stats['total_disclosures']))
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Disclosures</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_disclosures'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Review</p>
            <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $stats['pending_review'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Active Patents</p>
            <p class="mt-2 text-3xl font-bold text-teal-700">{{ $stats['active_patents'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue (Net)</p>
            <p class="mt-2 text-3xl font-bold text-green-700">{{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
        @else
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">My Disclosures</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['my_disclosures'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Draft Disclosures</p>
            <p class="mt-2 text-3xl font-bold text-gray-400">{{ $stats['my_drafts'] }}</p>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Recent disclosures --}}
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Recent Disclosures</h2>
                <a href="{{ route('portal.disclosures.index') }}" class="text-sm text-teal-700 hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentDisclosures as $d)
                <a href="{{ route('portal.disclosures.show', $d) }}" class="block px-5 py-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $d->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $d->submitter->name }}
                                @if($d->disclosure_id) · {{ $d->disclosure_id }} @endif
                            </p>
                        </div>
                        <span class="flex-shrink-0 text-xs px-2 py-0.5 rounded-full font-medium
                                     {{ $d->status_badge_class === 'draft' ? 'bg-gray-100 text-gray-600' :
                                        ($d->status_badge_class === 'submitted' ? 'bg-blue-100 text-blue-700' :
                                        ($d->status_badge_class === 'approved' ? 'bg-green-100 text-green-700' :
                                        'bg-yellow-100 text-yellow-700')) }}">
                            {{ $d->status_label }}
                        </span>
                    </div>
                </a>
                @empty
                <p class="px-5 py-6 text-sm text-gray-400 text-center">No disclosures yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Upcoming patent deadlines (TTO staff only) --}}
        @if($upcomingDeadlines->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Upcoming Patent Deadlines</h2>
                <a href="{{ route('portal.patents.index') }}" class="text-sm text-teal-700 hover:underline">View patents</a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($upcomingDeadlines as $dl)
                <a href="{{ route('portal.patents.show', $dl->patent) }}" class="block px-5 py-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $dl->deadline_type }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $dl->patent->title }}</p>
                        </div>
                        <span class="flex-shrink-0 text-xs font-medium {{ $dl->is_overdue ? 'text-red-600' : 'text-orange-600' }}">
                            {{ $dl->due_date->format('M d, Y') }}
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @elseif($user->isTTOStaff())
        <div class="bg-white rounded-xl border border-gray-200 flex items-center justify-center p-10">
            <p class="text-sm text-gray-400">No patent deadlines in the next 90 days.</p>
        </div>
        @endif

    </div>

    {{-- Quick actions --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="font-semibold text-gray-800 mb-3">Quick Actions</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('portal.disclosures.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                + New Disclosure
            </a>
            @if($user->canManagePatents())
            <a href="{{ route('portal.patents.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                + New Patent
            </a>
            <a href="{{ route('portal.agreements.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                + New Agreement
            </a>
            @endif
        </div>
    </div>

</div>
@endsection
