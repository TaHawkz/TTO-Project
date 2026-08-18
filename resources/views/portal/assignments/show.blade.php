@extends('layouts.portal')

@section('page-title', 'Ownership Determination')

@section('content')
<div class="max-w-3xl space-y-6 mx-auto">

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Assignment Details</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div>
                <dt class="text-gray-500">Outcome</dt>
                <dd class="mt-0.5">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-teal-100 text-teal-700">
                        {{ $assignment->outcome_label }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Determination Date</dt>
                <dd class="mt-0.5 text-gray-800">{{ $assignment->determination_date?->format('M d, Y') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Determined By</dt>
                <dd class="mt-0.5 text-gray-800">{{ $assignment->determinedBy->name }}</dd>
            </div>
        </dl>
        @if($assignment->notes)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500 font-medium mb-1">Notes</p>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $assignment->notes }}</p>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Disclosure</h2>
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="font-medium text-gray-800">{{ $assignment->disclosure->title }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $assignment->disclosure->disclosure_id }}</p>
            </div>
            <a href="{{ route('portal.disclosures.show', $assignment->disclosure) }}" class="text-sm text-teal-700 hover:underline flex-shrink-0">View Disclosure</a>
        </div>

        @if($assignment->disclosure->inventors->isNotEmpty())
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm font-medium text-gray-600 mb-2">Inventors</p>
            <ul class="space-y-1">
                @foreach($assignment->disclosure->inventors as $inv)
                <li class="text-sm text-gray-700">{{ $inv->name }} — {{ $inv->email }}
                    @if($inv->is_primary) <span class="text-xs text-teal-600">(Primary)</span> @endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div>
        <a href="{{ route('portal.assignments.index') }}" class="text-sm text-teal-700 hover:underline">← Back to Assignments</a>
    </div>
</div>
@endsection
