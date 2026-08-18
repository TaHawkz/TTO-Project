@extends('layouts.portal')

@section('page-title', 'Record IP Ownership')

@section('content')
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('portal.assignments.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Ownership Determination</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Disclosure <span class="text-red-500">*</span></label>
                <select name="disclosure_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Select disclosure…</option>
                    @foreach($disclosures as $d)
                    <option value="{{ $d->id }}" @selected(old('disclosure_id')==$d->id)>
                        {{ $d->disclosure_id ?? 'Draft' }} — {{ Str::limit($d->title, 70) }}
                    </option>
                    @endforeach
                </select>
                @error('disclosure_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Outcome <span class="text-red-500">*</span></label>
                    <select name="outcome" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="university" @selected(old('outcome')==='university')>University</option>
                        <option value="inventor" @selected(old('outcome')==='inventor')>Inventor</option>
                        <option value="joint" @selected(old('outcome')==='joint')>Joint Ownership</option>
                        <option value="sponsored_research" @selected(old('outcome')==='sponsored_research')>Sponsored Research</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Determination Date <span class="text-red-500">*</span></label>
                    <input type="date" name="determination_date" value="{{ old('determination_date', now()->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Record Determination
            </button>
            <a href="{{ route('portal.assignments.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
