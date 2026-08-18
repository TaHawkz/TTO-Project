@extends('layouts.portal')

@section('page-title', 'Record Revenue')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{
    distributions: [{ recipient_type: 'university', recipient_name: '', recipient_user_id: '', percentage: 100 }],
    addDist() { this.distributions.push({ recipient_type: 'university', recipient_name: '', recipient_user_id: '', percentage: 0 }) },
    removeDist(i) { if (this.distributions.length > 1) this.distributions.splice(i, 1) },
    totalPct() { return this.distributions.reduce((s, d) => s + Number(d.percentage || 0), 0) }
}">
    <form method="POST" action="{{ route('portal.revenue.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Revenue Details</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source Type <span class="text-red-500">*</span></label>
                    <select name="source_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach(['licensing','royalty','milestone','other'] as $t)
                        <option value="{{ $t }}" @selected(old('source_type')===$t)>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency <span class="text-red-500">*</span></label>
                    <input type="text" name="currency" value="{{ old('currency','BDT') }}" required maxlength="10"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gross Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="gross_amount" value="{{ old('gross_amount','0') }}" required min="0" step="0.01"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('gross_amount')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deductions <span class="text-red-500">*</span></label>
                    <input type="number" name="deductions" value="{{ old('deductions','0') }}" required min="0" step="0.01"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Received Date <span class="text-red-500">*</span></label>
                    <input type="date" name="received_date" value="{{ old('received_date', now()->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Linked Agreement</label>
                    <select name="agreement_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">None</option>
                        @foreach($agreements as $ag)
                        <option value="{{ $ag->id }}" @selected(old('agreement_id')==$ag->id)>{{ Str::limit($ag->title, 50) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Linked Disclosure</label>
                    <select name="disclosure_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">None</option>
                        @foreach($disclosures as $d)
                        <option value="{{ $d->id }}" @selected(old('disclosure_id')==$d->id)>{{ $d->disclosure_id }} — {{ Str::limit($d->title, 40) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Linked Patent</label>
                    <select name="patent_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">None</option>
                        @foreach($patents as $p)
                        <option value="{{ $p->id }}" @selected(old('patent_id')==$p->id)>{{ $p->patent_number ?? 'Draft' }} — {{ Str::limit($p->title, 40) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Distributions --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-gray-800">Revenue Distribution</h2>
                <button type="button" @click="addDist()" class="text-sm text-teal-700 hover:underline">+ Add Row</button>
            </div>
            <p class="text-xs text-gray-500">Percentages must sum to 100%.</p>

            @error('distributions')<p class="text-xs text-red-600">{{ $message }}</p>@enderror

            <div class="space-y-3">
                <template x-for="(dist, i) in distributions" :key="i">
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-start border border-gray-100 rounded-lg p-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Recipient Type</label>
                            <select :name="'distributions[' + i + '][recipient_type]'" x-model="dist.recipient_type"
                                    class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="inventor">Inventor</option>
                                <option value="department">Department</option>
                                <option value="university">University</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Recipient Name</label>
                            <input type="text" :name="'distributions[' + i + '][recipient_name]'" x-model="dist.recipient_name" required
                                   class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">% Share</label>
                            <input type="number" :name="'distributions[' + i + '][percentage]'" x-model="dist.percentage"
                                   min="0" max="100" step="0.01" required
                                   class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div class="flex items-end">
                            <button type="button" @click="removeDist(i)" x-show="distributions.length > 1"
                                    class="w-full px-3 py-1.5 text-xs text-red-500 hover:bg-red-50 border border-red-200 rounded-lg transition-colors">
                                Remove
                            </button>
                        </div>
                        <input type="hidden" :name="'distributions[' + i + '][recipient_user_id]'" x-model="dist.recipient_user_id">
                    </div>
                </template>
            </div>

            <div class="flex items-center gap-2 text-sm"
                 :class="Math.abs(totalPct() - 100) < 0.01 ? 'text-green-700' : 'text-red-600'">
                <span>Total: <strong x-text="totalPct().toFixed(2)"></strong>%</span>
                <span x-show="Math.abs(totalPct() - 100) >= 0.01">(must equal 100%)</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 bg-teal-700 text-white text-sm font-medium rounded-lg hover:bg-teal-800 transition-colors">
                Record Revenue
            </button>
            <a href="{{ route('portal.revenue.index') }}" class="text-sm text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection
