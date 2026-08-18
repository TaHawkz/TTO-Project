@extends('layouts.portal')

@section('page-title', $disclosure->title)

@section('header-actions')
@if($disclosure->status === 'draft' && Auth::id() === $disclosure->submitted_by)
<a href="{{ route('portal.disclosures.edit', $disclosure) }}"
   class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
    Edit Draft
</a>
<form method="POST" action="{{ route('portal.disclosures.submit', $disclosure) }}" class="inline">
    @csrf
    <button type="submit"
            onclick="return confirm('Submit this disclosure for TTO review?')"
            class="ml-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
        Submit to TTO
    </button>
</form>
@endif
@endsection

@section('content')
<div class="max-w-4xl space-y-6 mx-auto">

    {{-- Status badge + ID --}}
    <div class="flex flex-wrap items-center gap-3">
        @if($disclosure->disclosure_id)
        <span class="text-sm font-mono text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">{{ $disclosure->disclosure_id }}</span>
        @endif
        <span class="text-sm px-3 py-1 rounded-full font-medium bg-blue-100 text-blue-700">{{ $disclosure->status_label }}</span>
        @if($disclosure->submitted_at)
        <span class="text-xs text-gray-400">Submitted {{ $disclosure->submitted_at->format('M d, Y') }}</span>
        @endif
    </div>

    {{-- Main info --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
        <h2 class="font-semibold text-gray-800">Disclosure Details</h2>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <dt class="text-gray-500 font-medium">Submitted By</dt>
                <dd class="mt-0.5 text-gray-800">{{ $disclosure->submitter->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 font-medium">Technical Field</dt>
                <dd class="mt-0.5 text-gray-800">{{ $disclosure->technical_field }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 font-medium">Industry Sector</dt>
                <dd class="mt-0.5 text-gray-800">{{ $disclosure->industry_sector }}</dd>
            </div>
            @if($disclosure->assignee)
            <div>
                <dt class="text-gray-500 font-medium">Assigned To</dt>
                <dd class="mt-0.5 text-gray-800">{{ $disclosure->assignee->name }}</dd>
            </div>
            @endif
        </dl>

        <div>
            <h3 class="text-sm font-medium text-gray-500 mb-1">Abstract</h3>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $disclosure->abstract }}</p>
        </div>

        @foreach(['description' => 'Full Description', 'problem_solved' => 'Problem Solved', 'novel_features' => 'Novel Features', 'potential_applications' => 'Potential Applications', 'existing_alternatives' => 'Existing Alternatives'] as $field => $label)
        @if($disclosure->$field)
        <div>
            <h3 class="text-sm font-medium text-gray-500 mb-1">{{ $label }}</h3>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $disclosure->$field }}</p>
        </div>
        @endif
        @endforeach

        @if($disclosure->funding_source || $disclosure->sponsor_info || $disclosure->project_reference)
        <div class="border-t border-gray-100 pt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Funding</h3>
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                @if($disclosure->funding_source)
                <div><dt class="text-gray-500">Source</dt><dd>{{ $disclosure->funding_source }}</dd></div>
                @endif
                @if($disclosure->project_reference)
                <div><dt class="text-gray-500">Reference</dt><dd>{{ $disclosure->project_reference }}</dd></div>
                @endif
            </dl>
            @if($disclosure->sponsor_info)
            <p class="mt-2 text-sm text-gray-700">{{ $disclosure->sponsor_info }}</p>
            @endif
        </div>
        @endif
    </div>

    {{-- Inventors --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Inventors</h2>
        <div class="space-y-3">
            @foreach($disclosure->inventors as $inv)
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr($inv->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800">
                        {{ $inv->name }}
                        @if($inv->is_primary) <span class="ml-1 text-xs text-teal-700 font-medium">(Primary)</span> @endif
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $inv->email }}</p>
                    @if($inv->department || $inv->designation)
                    <p class="text-xs text-gray-500">{{ implode(' · ', array_filter([$inv->designation, $inv->department])) }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Documents --}}
    @if($disclosure->documents->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Documents</h2>
        <ul class="space-y-2">
            @foreach($disclosure->documents as $doc)
            <li class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="flex-1 text-sm text-gray-700 truncate">{{ $doc->original_name }}</span>
                <span class="text-xs text-gray-400">{{ number_format($doc->file_size / 1024, 1) }} KB</span>
                <a href="{{ route('portal.documents.download', $doc) }}"
                   class="text-xs text-teal-700 hover:underline font-medium flex-shrink-0">Download</a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- TTO Action Panel (staff only) --}}
    @if(Auth::user()->canReviewDisclosures() && $disclosure->status !== 'draft')
    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-800">TTO Actions</h2>

        {{-- Status update --}}
        <form method="POST" action="{{ route('portal.disclosures.status', $disclosure) }}" class="space-y-3">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach(['submitted','under_review','ownership_determined','patentability_assessed','committee_review','approved','rejected','patent_filing','commercializing'] as $s)
                        <option value="{{ $s }}" @selected($disclosure->status === $s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reviewer Notes</label>
                <textarea name="reviewer_notes" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ $disclosure->reviewer_notes }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rejection Reason (if rejecting)</label>
                <textarea name="rejection_reason" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ $disclosure->rejection_reason }}</textarea>
            </div>
            <button type="submit" class="px-5 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Update Status
            </button>
        </form>

        @if(Auth::user()->hasRole('tto_officer', 'director', 'system_admin') && $reviewers->isNotEmpty())
        {{-- Assign --}}
        <form method="POST" action="{{ route('portal.disclosures.assign', $disclosure) }}" class="border-t border-gray-100 pt-4 flex gap-3 items-end">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                <select name="assigned_to" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">— Select reviewer/officer —</option>
                    @foreach($reviewers as $r)
                    <option value="{{ $r->id }}" @selected($disclosure->assigned_to === $r->id)>
                        {{ $r->name }} ({{ $r->role_label }})
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2 border border-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Assign
            </button>
        </form>
        @endif
    </div>
    @endif

    {{-- Reviewer notes display (if set) --}}
    @if($disclosure->reviewer_notes && !Auth::user()->canReviewDisclosures())
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
        <p class="font-medium mb-1">Reviewer Notes</p>
        <p>{{ $disclosure->reviewer_notes }}</p>
    </div>
    @endif

    @if($disclosure->rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-800">
        <p class="font-medium mb-1">Rejection Reason</p>
        <p>{{ $disclosure->rejection_reason }}</p>
    </div>
    @endif

</div>
@endsection
