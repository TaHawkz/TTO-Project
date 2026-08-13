@extends('layouts.app')
@section('title', 'Industry Collaboration')
@section('description', 'Partner with NSU\'s Technology Transfer Office for sponsored research, licensing, consultancy, and joint development.')

@section('content')

<section class="relative hero-clip overflow-hidden" style="min-height:300px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">For Industry</p>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Partner with NSU Research</h1>
        <p class="text-white/80 text-xl max-w-2xl leading-relaxed">Access cutting-edge research, world-class expertise, and emerging technologies from North South University's research community.</p>
        <a href="{{ route('industry-inquiry') }}" class="btn-amber mt-6 inline-block">Submit an Inquiry</a>
    </div>
</section>

{{-- Engagement Pathways --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <p class="section-eyebrow justify-center fade-up">Partner With Us</p>
    <h2 class="section-title text-center fade-up">Engagement <span>Pathways</span></h2>
    <p class="section-subtitle text-center fade-up">Five ways to collaborate with NSU's research community</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
        @php $pathways = [
            [
                'icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
                'title'=>'Sponsored Research',
                'tag'=>'Most Common',
                'tag_color'=>'bg-tto-amber text-white',
                'desc'=>'Commission NSU researchers to investigate specific technical challenges or R&D questions relevant to your business. You define the problem; our researchers design and execute the work.',
                'benefits'=>['IP rights typically shared or assigned to sponsor', 'Access to NSU labs and equipment', 'Flexible project scoping and timelines', 'Co-publication rights available'],
            ],
            [
                'icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                'title'=>'Technology Licensing',
                'tag'=>'Revenue-sharing',
                'tag_color'=>'bg-blue-100 text-blue-700',
                'desc'=>'License a patented or patent-pending NSU technology for commercial use. Licensing can be exclusive, non-exclusive, or field-of-use exclusive depending on your market needs.',
                'benefits'=>['Exclusive and non-exclusive options', 'Milestone-based payment structures', 'Sub-licensing rights negotiable', 'Technical support from inventors'],
            ],
            [
                'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                'title'=>'Faculty Consultancy',
                'tag'=>'Expert advice',
                'tag_color'=>'bg-green-100 text-green-700',
                'desc'=>'Engage NSU faculty as technical consultants on a time-limited basis. Ideal for due diligence, expert testimony, product review, or knowledge transfer.',
                'benefits'=>['Targeted domain expertise', 'Short-term, flexible engagements', 'No overhead on routine consulting', 'NDA protection standard'],
            ],
            [
                'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                'title'=>'Joint Development',
                'tag'=>'Co-innovation',
                'tag_color'=>'bg-purple-100 text-purple-700',
                'desc'=>'Co-develop new products or technologies with NSU researchers, combining your industry resources with NSU\'s research capabilities. IP ownership is negotiated based on contributions.',
                'benefits'=>['Shared development costs', 'Joint IP ownership by default', 'Access to student talent pipeline', 'Co-branding opportunities'],
            ],
            [
                'icon'=>'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
                'title'=>'Technology Scouting',
                'tag'=>'Market intelligence',
                'tag_color'=>'bg-orange-100 text-orange-700',
                'desc'=>'Let the TTO identify emerging technologies across NSU\'s research portfolio that match your innovation roadmap. Receive a curated technology brief with no upfront commitment.',
                'benefits'=>['Curated technology portfolio briefing', 'No upfront obligation', 'Quarterly update service available', 'Benchmarked against global IP landscape'],
            ],
        ] @endphp
        @foreach($pathways as $p)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 card-hover flex flex-col">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-tto-teal-light flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-tto-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $p['icon'] }}"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ $p['title'] }}</h3>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $p['tag_color'] }}">{{ $p['tag'] }}</span>
                </div>
            </div>
            <p class="text-sm text-gray-600 leading-relaxed mb-4 flex-1">{{ $p['desc'] }}</p>
            <ul class="space-y-1 mb-5">
                @foreach($p['benefits'] as $b)
                <li class="flex items-start gap-2 text-xs text-gray-600">
                    <svg class="w-3.5 h-3.5 text-tto-teal flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    {{ $b }}
                </li>
                @endforeach
            </ul>
            <a href="{{ route('industry-inquiry') }}" class="btn-outline text-sm text-center">Submit Inquiry</a>
        </div>
        @endforeach
    </div>
</section>

{{-- Partnership Process --}}
<section class="bg-gray-50 py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="section-eyebrow justify-center fade-up">The Process</p>
        <h2 class="section-title text-center fade-up">How the <span>Partnership Process</span> Works</h2>
        <p class="section-subtitle text-center fade-up">From initial inquiry to active collaboration in four steps</p>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mt-10">
            @php $process = [
                ['n'=>1,'title'=>'Submit Inquiry','desc'=>'Fill out the industry inquiry form. Tell us about your company and what you\'re looking for.'],
                ['n'=>2,'title'=>'TTO Review','desc'=>'The TTO reviews your inquiry and matches it to relevant faculty, research groups, or available technologies.'],
                ['n'=>3,'title'=>'Initial Meeting','desc'=>'We facilitate an introductory meeting between you and the relevant NSU researchers or TTO team.'],
                ['n'=>4,'title'=>'Agreement','desc'=>'If there\'s a mutual fit, the TTO drafts the appropriate agreement (NDA, Sponsored Research, License, etc.).'],
            ] @endphp
            @foreach($process as $step)
            <div class="text-center">
                <div class="w-14 h-14 rounded-2xl bg-tto-teal text-white text-xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">{{ $step['n'] }}</div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-tto-teal-900 py-16 text-center text-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-4">Ready to Explore a Partnership?</h2>
        <p class="text-white/75 mb-8">Submit an inquiry and a TTO representative will respond within 3 business days.</p>
        <a href="{{ route('industry-inquiry') }}" class="btn-amber text-lg px-8 py-3 fade-up">Submit Industry Inquiry</a>
    </div>
</section>

@endsection
