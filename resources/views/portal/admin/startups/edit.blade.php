@extends('layouts.portal')

@section('page-title', 'Edit Startup')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ founders: {{ json_encode(old('founders', $startup->founders ?? [''])) }} }">
    <form method="POST" action="{{ route('portal.admin.startups.update', $startup) }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Startup Details</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $startup->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry Sector</label>
                    <input type="text" name="industry_sector" value="{{ old('industry_sector', $startup->industry_sector) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Incorporation Date</label>
                    <input type="date" name="incorporation_date" value="{{ old('incorporation_date', $startup->incorporation_date?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Faculty Advisor</label>
                    <input type="text" name="faculty_advisor" value="{{ old('faculty_advisor', $startup->faculty_advisor) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Technology Used</label>
                    <input type="text" name="technology_used" value="{{ old('technology_used', $startup->technology_used) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Funding Status</label>
                    <input type="text" name="funding_status" value="{{ old('funding_status', $startup->funding_status) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Funding Amount</label>
                    <input type="text" name="funding_amount" value="{{ old('funding_amount', $startup->funding_amount) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website', $startup->website) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700">Founders</label>
                    <button type="button" @click="founders.push('')" class="text-sm text-teal-700 hover:underline">+ Add</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(f, i) in founders" :key="i">
                        <div class="flex gap-2">
                            <input type="text" :name="'founders[' + i + ']'" x-model="founders[i]"
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <button type="button" @click="if(founders.length > 1) founders.splice(i,1)" class="px-2 text-gray-400 hover:text-red-500">×</button>
                        </div>
                    </template>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('description', $startup->description) }}</textarea>
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $startup->is_published))
                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    Published
                </label>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Save Changes
            </button>
            <a href="{{ route('portal.admin.startups.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
