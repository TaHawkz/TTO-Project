@extends('layouts.portal')

@section('page-title', 'New Disclosure')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{
    inventors: [{ name: '', email: '', department: '', designation: '' }],
    addInventor() { this.inventors.push({ name: '', email: '', department: '', designation: '' }) },
    removeInventor(i) { if (this.inventors.length > 1) this.inventors.splice(i, 1) }
}">
    <form method="POST" action="{{ route('portal.disclosures.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800 text-base">Basic Information</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('title') border-red-400 @enderror">
                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Abstract <span class="text-red-500">*</span></label>
                <textarea name="abstract" rows="3" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('abstract') }}</textarea>
                @error('abstract')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Technical Field <span class="text-red-500">*</span></label>
                    <input type="text" name="technical_field" value="{{ old('technical_field') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('technical_field')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry Sector <span class="text-red-500">*</span></label>
                    <input type="text" name="industry_sector" value="{{ old('industry_sector') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('industry_sector')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Technical Details --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800 text-base">Technical Details</h2>

            @foreach(['description' => 'Full Description *', 'problem_solved' => 'Problem Solved *', 'novel_features' => 'Novel Features *', 'potential_applications' => 'Potential Applications *'] as $field => $label)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                <textarea name="{{ $field }}" rows="3" {{ str_ends_with($label,'*') ? 'required' : '' }}
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old($field) }}</textarea>
                @error($field)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            @endforeach

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Existing Alternatives</label>
                <textarea name="existing_alternatives" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('existing_alternatives') }}</textarea>
            </div>
        </div>

        {{-- Funding --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800 text-base">Funding Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Funding Source</label>
                    <input type="text" name="funding_source" value="{{ old('funding_source') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Reference</label>
                    <input type="text" name="project_reference" value="{{ old('project_reference') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sponsor Information</label>
                <textarea name="sponsor_info" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('sponsor_info') }}</textarea>
            </div>
        </div>

        {{-- Inventors --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 text-base">Inventors <span class="text-xs text-gray-400 font-normal ml-1">(first listed is primary)</span></h2>
                <button type="button" @click="addInventor()"
                        class="text-sm text-teal-700 hover:underline">+ Add Inventor</button>
            </div>

            <template x-for="(inv, i) in inventors" :key="i">
                <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide" x-text="i === 0 ? 'Primary Inventor' : 'Co-Inventor ' + i"></span>
                        <button type="button" @click="removeInventor(i)" x-show="inventors.length > 1"
                                class="text-xs text-red-500 hover:underline">Remove</button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Name *</label>
                            <input type="text" :name="'inventors[' + i + '][name]'" x-model="inv.name" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Email *</label>
                            <input type="email" :name="'inventors[' + i + '][email]'" x-model="inv.email" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Department</label>
                            <input type="text" :name="'inventors[' + i + '][department]'" x-model="inv.department"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Designation</label>
                            <input type="text" :name="'inventors[' + i + '][designation]'" x-model="inv.designation"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>
                </div>
            </template>

            @error('inventors')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            @error('inventors.0.name')<p class="text-xs text-red-600">Primary inventor name is required.</p>@enderror
        </div>

        {{-- Documents --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-3">
            <h2 class="font-semibold text-gray-800 text-base">Supporting Documents</h2>
            <p class="text-xs text-gray-500">PDF, Word, or image files up to 10 MB each.</p>
            <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                   class="block text-sm text-gray-600">
            @error('documents.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-6 py-2.5 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Save as Draft
            </button>
            <a href="{{ route('portal.disclosures.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
