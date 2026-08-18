@extends('layouts.portal')

@section('page-title', $agreement->title)

@section('header-actions')
<a href="{{ route('portal.agreements.edit', $agreement) }}"
   class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
    Edit
</a>
@if($agreement->document_path)
<a href="{{ route('portal.agreements.download', $agreement) }}"
   class="ml-2 px-4 py-2 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
    Download Document
</a>
@endif
@endsection

@section('content')
<div class="max-w-3xl space-y-6 mx-auto">

    <div class="flex flex-wrap gap-3 items-center">
        <span class="text-sm px-3 py-1 rounded-full font-medium
            {{ $agreement->status === 'signed' ? 'bg-green-100 text-green-700' :
               ($agreement->status === 'terminated' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
            {{ ucfirst($agreement->status) }}
        </span>
        <span class="text-sm text-gray-500">{{ $agreement->type_label }}</span>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Agreement Details</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div><dt class="text-gray-500">Managed By</dt><dd class="mt-0.5 text-gray-800">{{ $agreement->manager->name }}</dd></div>
            <div><dt class="text-gray-500">Signed Date</dt><dd class="mt-0.5">{{ $agreement->signed_date?->format('M d, Y') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Expiry Date</dt><dd class="mt-0.5">{{ $agreement->expiry_date?->format('M d, Y') ?? '—' }}</dd></div>
            @if($agreement->disclosure)
            <div><dt class="text-gray-500">Linked Disclosure</dt>
                <dd class="mt-0.5"><a href="{{ route('portal.disclosures.show', $agreement->disclosure) }}" class="text-teal-700 hover:underline">{{ $agreement->disclosure->disclosure_id }}</a></dd>
            </div>
            @endif
            @if($agreement->patent)
            <div><dt class="text-gray-500">Linked Patent</dt>
                <dd class="mt-0.5"><a href="{{ route('portal.patents.show', $agreement->patent) }}" class="text-teal-700 hover:underline">{{ $agreement->patent->patent_number ?? $agreement->patent->title }}</a></dd>
            </div>
            @endif
        </dl>

        @if($agreement->parties)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm font-medium text-gray-600 mb-2">Parties</p>
            <ul class="space-y-1">
                @foreach($agreement->parties as $party)
                <li class="text-sm text-gray-700">{{ $party }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div>
        <a href="{{ route('portal.agreements.index') }}" class="text-sm text-teal-700 hover:underline">← Back to Agreements</a>
    </div>
</div>
@endsection
