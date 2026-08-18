@extends('layouts.portal')

@section('page-title', $commercialization->title)

@section('header-actions')
<a href="{{ route('portal.commercialization.edit', $commercialization) }}"
   class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
    Edit
</a>
@endsection

@section('content')
<div class="max-w-3xl space-y-6 mx-auto">

    <div class="flex flex-wrap gap-3 items-center">
        <span class="text-sm px-3 py-1 rounded-full font-medium
            {{ $commercialization->status === 'active' ? 'bg-green-100 text-green-700' :
               ($commercialization->status === 'closed' ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-700') }}">
            {{ $commercialization->status_label }}
        </span>
        <span class="text-sm text-gray-500">{{ $commercialization->type_label }}</span>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Details</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
            <div><dt class="text-gray-500">Manager</dt><dd class="mt-0.5 text-gray-800">{{ $commercialization->manager->name }}</dd></div>
            @if($commercialization->partner_name)
            <div><dt class="text-gray-500">Partner</dt><dd class="mt-0.5 text-gray-800">{{ $commercialization->partner_name }}</dd></div>
            <div><dt class="text-gray-500">Contact</dt><dd class="mt-0.5">{{ $commercialization->partner_contact ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Partner Email</dt><dd class="mt-0.5">{{ $commercialization->partner_email ?? '—' }}</dd></div>
            @endif
            @if($commercialization->patent)
            <div><dt class="text-gray-500">Patent</dt>
                <dd class="mt-0.5"><a href="{{ route('portal.patents.show', $commercialization->patent) }}" class="text-teal-700 hover:underline">{{ $commercialization->patent->title }}</a></dd>
            </div>
            @endif
            @if($commercialization->disclosure)
            <div><dt class="text-gray-500">Disclosure</dt>
                <dd class="mt-0.5"><a href="{{ route('portal.disclosures.show', $commercialization->disclosure) }}" class="text-teal-700 hover:underline">{{ $commercialization->disclosure->disclosure_id }}</a></dd>
            </div>
            @endif
        </dl>
        @if($commercialization->description)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500 font-medium mb-1">Description</p>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $commercialization->description }}</p>
        </div>
        @endif
        @if($commercialization->notes)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-500 font-medium mb-1">Notes</p>
            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $commercialization->notes }}</p>
        </div>
        @endif
    </div>

    <div>
        <a href="{{ route('portal.commercialization.index') }}" class="text-sm text-teal-700 hover:underline">← Back to Commercialization</a>
    </div>
</div>
@endsection
