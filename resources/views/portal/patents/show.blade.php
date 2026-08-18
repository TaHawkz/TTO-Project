@extends('layouts.portal')

@section('page-title', $patent->title)

@section('header-actions')
<a href="{{ route('portal.patents.edit', $patent) }}"
   class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
    Edit
</a>
@endsection

@section('content')
<div class="max-w-4xl space-y-6 mx-auto">

    {{-- Status + dates --}}
    <div class="flex flex-wrap gap-3 items-center">
        <span class="text-sm px-3 py-1 rounded-full font-medium
            {{ $patent->status === 'granted' ? 'bg-green-100 text-green-700' :
               ($patent->status === 'filed' ? 'bg-blue-100 text-blue-700' :
               ($patent->status === 'expired' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-100 text-yellow-700')) }}">
            {{ $patent->status_label }}
        </span>
        <span class="text-sm text-gray-500">{{ $patent->jurisdiction }}</span>
        @if($patent->patent_number)
        <span class="text-sm font-mono text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">{{ $patent->patent_number }}</span>
        @endif
        @if($patent->is_expiring_soon)
        <span class="text-sm px-3 py-1 rounded-full bg-orange-100 text-orange-700 font-medium">Expiring Soon</span>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Patent Details</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
            <div><dt class="text-gray-500">Applicant</dt><dd class="mt-0.5 font-medium text-gray-800">{{ $patent->applicant }}</dd></div>
            <div><dt class="text-gray-500">Filing Date</dt><dd class="mt-0.5">{{ $patent->filing_date?->format('M d, Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Grant Date</dt><dd class="mt-0.5">{{ $patent->grant_date?->format('M d, Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Publication Date</dt><dd class="mt-0.5">{{ $patent->publication_date?->format('M d, Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Expiry Date</dt><dd class="mt-0.5 {{ $patent->is_expiring_soon ? 'text-orange-600 font-medium' : '' }}">{{ $patent->expiry_date?->format('M d, Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Managed By</dt><dd class="mt-0.5">{{ $patent->manager->name }}</dd></div>
            @if($patent->attorney_firm)
            <div><dt class="text-gray-500">Attorney Firm</dt><dd class="mt-0.5">{{ $patent->attorney_firm }}</dd></div>
            <div><dt class="text-gray-500">Attorney Contact</dt><dd class="mt-0.5">{{ $patent->attorney_contact }}</dd></div>
            @endif
            @if($patent->disclosure)
            <div class="sm:col-span-3"><dt class="text-gray-500">Linked Disclosure</dt>
                <dd class="mt-0.5"><a href="{{ route('portal.disclosures.show', $patent->disclosure) }}" class="text-teal-700 hover:underline">{{ $patent->disclosure->disclosure_id }} — {{ $patent->disclosure->title }}</a></dd>
            </div>
            @endif
        </dl>
        @if($patent->notes)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500 font-medium mb-1">Notes</p>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $patent->notes }}</p>
        </div>
        @endif
    </div>

    {{-- Deadlines --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-gray-800">Deadlines</h2>
        </div>

        @if($patent->deadlines->isNotEmpty())
        <div class="space-y-2 mb-4">
            @foreach($patent->deadlines->sortBy('due_date') as $dl)
            <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg {{ $dl->is_completed ? 'opacity-50' : '' }}">
                <form method="POST" action="{{ route('portal.patents.deadlines.update', [$patent, $dl]) }}" class="flex-shrink-0">
                    @csrf @method('PATCH')
                    <input type="hidden" name="is_completed" value="{{ $dl->is_completed ? '0' : '1' }}">
                    <input type="hidden" name="completed_at" value="{{ $dl->is_completed ? '' : now()->format('Y-m-d') }}">
                    <button type="submit" class="w-5 h-5 border-2 rounded {{ $dl->is_completed ? 'bg-teal-600 border-teal-600' : 'border-gray-300' }} flex items-center justify-center">
                        @if($dl->is_completed)
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @endif
                    </button>
                </form>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate {{ $dl->is_completed ? 'line-through' : '' }}">{{ $dl->deadline_type }}</p>
                    @if($dl->notes)<p class="text-xs text-gray-500">{{ $dl->notes }}</p>@endif
                </div>
                <span class="text-xs flex-shrink-0 {{ $dl->is_overdue ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                    {{ $dl->due_date->format('M d, Y') }}
                </span>
                <form method="POST" action="{{ route('portal.patents.deadlines.destroy', [$patent, $dl]) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-gray-400 hover:text-red-500">×</button>
                </form>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Add deadline --}}
        <form method="POST" action="{{ route('portal.patents.deadlines.store', $patent) }}"
              class="border border-gray-200 rounded-lg p-4 space-y-3 bg-gray-50">
            @csrf
            <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">Add Deadline</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <input type="text" name="deadline_type" placeholder="e.g. Annual Renewal Fee" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <input type="date" name="due_date" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="sm:col-span-2">
                    <input type="text" name="notes" placeholder="Notes (optional)"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                        Add
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Linked Agreements --}}
    @if($patent->agreements->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-3">Linked Agreements</h2>
        <ul class="space-y-2">
            @foreach($patent->agreements as $ag)
            <li class="flex items-center justify-between p-3 border border-gray-100 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $ag->title }}</p>
                    <p class="text-xs text-gray-500">{{ ucwords(str_replace('_',' ',$ag->type)) }} · {{ $ag->status }}</p>
                </div>
                <a href="{{ route('portal.agreements.show', $ag) }}" class="text-xs text-teal-700 hover:underline">View</a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

</div>
@endsection
