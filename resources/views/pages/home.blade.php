@extends('layouts.app')
@section('title', 'Home')
@section('description', 'NSU TTO — Bridging innovation and industry. Explore our technology portfolio, learn about invention disclosure, and connect with North South University\'s Technology Transfer Office.')

@section('content')

{{-- Hero --}}
<section class="relative hero-clip overflow-hidden" style="min-height: 580px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1532094349884-543559c21f85?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(10,79,81,0.93) 0%, rgba(13,158,160,0.72) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 flex flex-col lg:flex-row items-center gap-12">
        <div class="flex-1 text-white">
            <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-4">North South University · Technology Transfer Office</p>
            <h1 class="text-4xl lg:text-5xl font-bold leading-tight mb-5">Bridging Innovation<br>and Industry</h1>
            <p class="text-lg text-white/80 mb-8 max-w-xl leading-relaxed">
                We protect, commercialize, and advance NSU's intellectual property — connecting groundbreaking university research with industry partners, entrepreneurs, and society.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('disclosure') }}" class="btn-amber text-sm font-bold py-3 px-6">Submit a Disclosure</a>
                <a href="{{ route('technology-portfolio') }}" class="btn-outline text-sm font-bold py-3 px-6">Explore Technology Portfolio</a>
            </div>
        </div>
        <div class="flex-shrink-0 w-full lg:w-auto">
            <div class="glass-card rounded-2xl p-6 grid grid-cols-2 gap-5 min-w-[260px]">
                @php $services = [
                    ['label'=>'Invention Disclosure', 'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['label'=>'Patent Filing', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['label'=>'Tech Licensing', 'icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ['label'=>'Startup Support', 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
                ] @endphp
                @foreach($services as $svc)
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-tto-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $svc['icon'] }}"/></svg>
                    </div>
                    <div class="text-xs text-white/90 font-medium leading-tight">{{ $svc['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Featured Technologies --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-12 fade-up">
        <p class="section-eyebrow justify-center">From Our Labs</p>
        <h2 class="section-title">Featured <span>Technologies</span></h2>
        <p class="section-subtitle">Innovations from NSU's research labs ready for commercialization</p>
    </div>
    @php $featuredTechs = \App\Models\Technology::published()->take(4)->get(); @endphp
    @if($featuredTechs->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($featuredTechs as $i => $tech)
        <div class="bg-white rounded-xl border border-gray-200 p-6 card-hover flex flex-col fade-up delay-{{ $i + 1 }}">
            <div class="flex items-start justify-between gap-3 mb-3">
                <h3 class="font-semibold text-gray-900 leading-snug">{{ $tech->title }}</h3>
                <span class="badge-stage {{ $tech->stage_badge_class }} flex-shrink-0">{{ $tech->stage_label }}</span>
            </div>
            <span class="badge-sector self-start mb-3">{{ $tech->industry_sector }}</span>
            <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">{{ Str::limit($tech->description, 140) }}</p>
            <ul class="space-y-1 mb-5">
                @foreach(array_slice($tech->benefits, 0, 2) as $benefit)
                <li class="flex items-start gap-2 text-sm text-gray-700">
                    <svg class="w-4 h-4 text-tto-teal flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $benefit }}
                </li>
                @endforeach
            </ul>
            <a href="{{ route('technology-portfolio') }}" class="text-tto-teal font-semibold text-sm hover:text-tto-teal-dark flex items-center gap-1">
                View Details
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-16 text-gray-400 fade-up">
        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
        <p class="font-medium text-gray-500">Technology listings coming soon</p>
        <p class="text-sm mt-1">The TTO will publish available technologies here.</p>
    </div>
    @endif
    <div class="text-center mt-10 fade-up">
        <a href="{{ route('technology-portfolio') }}" class="btn-primary">View Full Portfolio</a>
    </div>
</section>

{{-- Latest Updates --}}
<section class="bg-tto-teal-light py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 fade-up">
            <p class="section-eyebrow justify-center">What's New</p>
            <h2 class="section-title">Latest <span>Updates</span></h2>
            <p class="section-subtitle">News, announcements, and innovation highlights from NSU TTO</p>
        </div>
        <div class="text-center py-14 fade-up">
            <svg class="w-12 h-12 mx-auto mb-4 text-tto-teal opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <p class="font-semibold text-tto-teal-900">News and announcements</p>
            <p class="text-sm text-gray-500 mt-1">Updates from the TTO will appear here.</p>
            <a href="{{ route('contact') }}" class="mt-5 inline-block btn-primary text-sm">Contact TTO for Updates</a>
        </div>
    </div>
</section>

{{-- CTA Band --}}
<section class="bg-tto-teal py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white fade-up">
        <p class="text-tto-teal-light text-sm font-semibold uppercase tracking-widest mb-3">Ready to Start?</p>
        <h2 class="text-3xl lg:text-4xl font-bold mb-4">Have a Groundbreaking Discovery?</h2>
        <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto leading-relaxed">
            Innovation is only impactful when it reaches the people who need it most. Let us help you protect your idea and bring it to the world.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('disclosure') }}" class="bg-white text-tto-teal font-bold py-3 px-8 rounded-full hover:bg-gray-50 transition-colors text-sm shadow-lg">Start Your Disclosure</a>
            <a href="{{ route('contact') }}" class="btn-outline text-sm font-bold py-3 px-8">Talk to TTO</a>
        </div>
    </div>
</section>

@endsection
