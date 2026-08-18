@extends('layouts.portal')

@section('page-title', 'Edit Draft')

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('portal.disclosures.update', $disclosure) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800 text-base">Basic Information</h2>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $disclosure->title) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Abstract <span class="text-red-500">*</span></label>
                <textarea name="abstract" rows="3" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('abstract', $disclosure->abstract) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Technical Field <span class="text-red-500">*</span></label>
                    <input type="text" name="technical_field" value="{{ old('technical_field', $disclosure->technical_field) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Industry Sector <span class="text-red-500">*</span></label>
                    <input type="text" name="industry_sector" value="{{ old('industry_sector', $disclosure->industry_sector) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800 text-base">Technical Details</h2>
            @foreach(['description' => 'Full Description *', 'problem_solved' => 'Problem Solved *', 'novel_features' => 'Novel Features *', 'potential_applications' => 'Potential Applications *', 'existing_alternatives' => 'Existing Alternatives'] as $field => $label)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                <textarea name="{{ $field }}" rows="3" {{ str_ends_with($label,'*') ? 'required' : '' }}
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old($field, $disclosure->$field) }}</textarea>
            </div>
            @endforeach
        </div>

        {{-- Inventors (re-enter to replace) --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4"
             x-data="{
                inventors: {{ json_encode($disclosure->inventors->map(fn($i) => ['name'=>$i->name,'email'=>$i->email,'department'=>$i->department,'designation'=>$i->designation])) }},
                addInventor() { this.inventors.push({ name:'',email:'',department:'',designation:'' }) },
                removeInventor(i) { if (this.inventors.length > 1) this.inventors.splice(i,1) }
             }">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 text-base">Inventors</h2>
                <button type="button" @click="addInventor()" class="text-sm text-teal-700 hover:underline">+ Add</button>
            </div>
            <template x-for="(inv, i) in inventors" :key="i">
                <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-medium text-gray-500" x-text="i === 0 ? 'Primary' : 'Co-Inventor ' + i"></span>
                        <button type="button" @click="removeInventor(i)" x-show="inventors.length > 1" class="text-xs text-red-500 hover:underline">Remove</button>
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
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-3">
            <h2 class="font-semibold text-gray-800 text-base">Add More Documents</h2>
            <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                   class="block text-sm text-gray-600">
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-6 py-2.5 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Save Changes
            </button>
            <a href="{{ route('portal.disclosures.show', $disclosure) }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
