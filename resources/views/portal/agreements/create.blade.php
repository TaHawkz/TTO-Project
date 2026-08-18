@extends('layouts.portal')

@section('page-title', 'New Agreement')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ parties: [''] }">
    <form method="POST" action="{{ route('portal.agreements.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Agreement Details</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach(['assignment','nda_cda','revenue_sharing','sponsored_research','licensing','other'] as $t)
                        <option value="{{ $t }}" @selected(old('type')===$t)>{{ ucwords(str_replace('_',' ',$t)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach(['draft','under_review','signed','expired','terminated'] as $s)
                        <option value="{{ $s }}" @selected(old('status','draft')===$s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Signed Date</label>
                    <input type="date" name="signed_date" value="{{ old('signed_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Linked Disclosure</label>
                    <select name="disclosure_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">None</option>
                        @foreach($disclosures as $d)
                        <option value="{{ $d->id }}" @selected(old('disclosure_id')==$d->id)>{{ $d->disclosure_id }} — {{ Str::limit($d->title, 50) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Linked Patent</label>
                    <select name="patent_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">None</option>
                        @foreach($patents as $p)
                        <option value="{{ $p->id }}" @selected(old('patent_id')==$p->id)>{{ $p->patent_number ?? 'Draft' }} — {{ Str::limit($p->title, 50) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Parties --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Parties <span class="text-red-500">*</span></label>
                    <button type="button" @click="parties.push('')" class="text-sm text-teal-700 hover:underline">+ Add Party</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(party, i) in parties" :key="i">
                        <div class="flex gap-2">
                            <input type="text" :name="'parties[' + i + ']'" x-model="parties[i]" required
                                   placeholder="Organisation or individual name"
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <button type="button" @click="if(parties.length > 1) parties.splice(i, 1)"
                                    class="px-2 text-gray-400 hover:text-red-500">×</button>
                        </div>
                    </template>
                </div>
                @error('parties')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Signed Document (PDF/Word, max 20 MB)</label>
                <input type="file" name="document" accept=".pdf,.doc,.docx"
                       class="block text-sm text-gray-600">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Create Agreement
            </button>
            <a href="{{ route('portal.agreements.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
