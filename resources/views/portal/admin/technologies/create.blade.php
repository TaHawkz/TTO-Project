@extends('layouts.portal')

@section('page-title', 'New Technology')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ benefits: [''] }">
    <form method="POST" action="{{ route('portal.admin.technologies.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Technology Details</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry Sector <span class="text-red-500">*</span></label>
                    <input type="text" name="industry_sector" value="{{ old('industry_sector') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Development Stage <span class="text-red-500">*</span></label>
                    <select name="development_stage" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="early_stage" @selected(old('development_stage')==='early_stage')>Early Stage</option>
                        <option value="filed" @selected(old('development_stage')==='filed')>Patent Filed</option>
                        <option value="granted" @selected(old('development_stage')==='granted')>Patent Granted</option>
                        <option value="licensed" @selected(old('development_stage')==='licensed')>Licensed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('description') }}</textarea>
            </div>

            {{-- Benefits --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700">Key Benefits</label>
                    <button type="button" @click="benefits.push('')" class="text-sm text-teal-700 hover:underline">+ Add</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(b, i) in benefits" :key="i">
                        <div class="flex gap-2">
                            <input type="text" :name="'benefits[' + i + ']'" x-model="benefits[i]"
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <button type="button" @click="if(benefits.length > 1) benefits.splice(i,1)" class="px-2 text-gray-400 hover:text-red-500">×</button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="licensing_available" value="0">
                    <input type="checkbox" name="licensing_available" value="1" @checked(old('licensing_available'))
                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    Licensing Available
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true))
                           class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    Publish immediately
                </label>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Create Technology
            </button>
            <a href="{{ route('portal.admin.technologies.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
