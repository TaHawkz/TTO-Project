@extends('layouts.app')
@section('title', 'Technology Portfolio')
@section('description', 'Explore NSU\'s portfolio of patented and licensable technologies ready for commercialization.')

@section('content')

{{-- Hero --}}
<section class="relative hero-clip overflow-hidden" style="min-height:300px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">NSU Innovations</p>
        <h1 class="text-4xl font-bold mb-3">Technology Portfolio</h1>
        <p class="text-white/80 text-lg max-w-2xl">Search and explore our portfolio of patent-protected and early-stage technologies available for licensing and collaboration.</p>
    </div>
</section>

{{-- Filter & Search --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{
    search: '',
    sector: '',
    stage: '',
    get filtered() {
        return this.$refs.cards ? Array.from(this.$refs.cards.querySelectorAll('[data-tech]')).filter(el => {
            const title = el.dataset.title.toLowerCase();
            const sec = el.dataset.sector.toLowerCase();
            const stg = el.dataset.stage;
            const matchSearch = !this.search || title.includes(this.search.toLowerCase()) || sec.includes(this.search.toLowerCase());
            const matchSector = !this.sector || sec.includes(this.sector.toLowerCase());
            const matchStage = !this.stage || stg === this.stage;
            return matchSearch && matchSector && matchStage;
        }) : [];
    }
}">

    <div class="flex flex-col sm:flex-row gap-3 mb-8">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model="search" type="text" placeholder="Search technologies..." class="form-input pl-10">
        </div>
        <select x-model="sector" class="form-input sm:w-52">
            <option value="">All Sectors</option>
            <option>Water & Sanitation</option>
            <option>Agriculture Technology</option>
            <option>Textile & Materials</option>
            <option>Renewable Energy / AgriTech</option>
        </select>
        <select x-model="stage" class="form-input sm:w-44">
            <option value="">All Stages</option>
            <option value="early_stage">Early Stage</option>
            <option value="filed">Patent Filed</option>
            <option value="granted">Patent Granted</option>
            <option value="licensed">Licensed</option>
        </select>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-ref="cards">
        @foreach($technologies as $tech)
        <div data-tech data-title="{{ strtolower($tech->title) }}" data-sector="{{ strtolower($tech->industry_sector) }}" data-stage="{{ $tech->development_stage }}"
             x-show="!search && !sector && !stage ? true : ((!search || '{{ strtolower($tech->title) }}'.includes(search.toLowerCase()) || '{{ strtolower($tech->industry_sector) }}'.includes(search.toLowerCase())) && (!sector || '{{ strtolower($tech->industry_sector) }}'.includes(sector.toLowerCase())) && (!stage || stage === '{{ $tech->development_stage }}'))"
             class="bg-white rounded-xl border border-gray-200 p-6 flex flex-col card-hover"
             x-data="{ modalOpen: false }">

            <div class="flex items-start justify-between gap-3 mb-3">
                <h3 class="font-semibold text-gray-900 leading-snug text-base">{{ $tech->title }}</h3>
                <span class="badge-stage {{ $tech->stage_badge_class }} flex-shrink-0">{{ $tech->stage_label }}</span>
            </div>
            <span class="badge-sector self-start mb-4">{{ $tech->industry_sector }}</span>
            <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">{{ $tech->description }}</p>

            <div class="mb-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Key Benefits</p>
                <ul class="space-y-1">
                    @foreach($tech->benefits as $benefit)
                    <li class="flex items-start gap-2 text-sm text-gray-700">
                        <svg class="w-4 h-4 text-tto-teal flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $benefit }}
                    </li>
                    @endforeach
                </ul>
            </div>

            @if($tech->licensing_available)
            <div class="flex items-center gap-2 text-sm text-tto-teal font-medium mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Exclusive licensing available — contact {{ $tech->contact_email }}
            </div>
            @endif

            <button @click="modalOpen=true" class="btn-primary text-sm self-start mt-auto">Express Interest</button>

            {{-- Modal --}}
            <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display:none;">
                <div class="absolute inset-0 bg-black/50" @click="modalOpen=false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full z-10">
                    <h3 class="font-bold text-lg text-gray-900 mb-1">Express Interest</h3>
                    <p class="text-sm text-gray-500 mb-5">{{ $tech->title }}</p>
                    <form action="{{ route('industry-inquiry.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="inquiry_type" value="licensing">
                        <input type="hidden" name="message" value="Expressing interest in: {{ $tech->title }}">
                        <div class="space-y-4">
                            <div>
                                <label class="form-label">Company Name *</label>
                                <input type="text" name="company_name" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Contact Name *</label>
                                <input type="text" name="contact_name" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-input">
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="submit" class="btn-primary flex-1 text-center">Submit Interest</button>
                            <button type="button" @click="modalOpen=false" class="flex-1 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 font-medium">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <p class="text-sm text-gray-400 mt-6">Showing {{ count($technologies) }} published technologies</p>
</section>

{{-- CTA --}}
<section class="bg-tto-teal py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white fade-up">
        <h2 class="text-2xl font-bold mb-3">Looking for Something Specific?</h2>
        <p class="text-white/80 mb-6">Tell us about your technology needs and our team will identify relevant NSU innovations and potential collaboration pathways.</p>
        <a href="{{ route('industry-inquiry') }}" class="bg-white text-tto-teal font-bold py-3 px-8 rounded-full hover:bg-gray-50 transition-colors text-sm shadow-lg">Submit a Collaboration Inquiry</a>
    </div>
</section>

@endsection
