@extends('layouts.app')
@section('title', 'Startups & Innovation')
@section('description', 'NSU TTO spin-out startups and entrepreneurship support programs — incubation, seed funding, and mentorship for NSU-based ventures.')

@section('content')

<section class="relative hero-clip overflow-hidden" style="min-height:300px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-20 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">Entrepreneurship</p>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">Startups &amp; Innovation</h1>
        <p class="text-white/80 text-xl max-w-2xl">NSU's Technology Transfer Office supports researchers and students in building technology ventures based on NSU's intellectual property.</p>
    </div>
</section>

{{-- Startup cards from DB --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <p class="section-eyebrow justify-center fade-up">Spin-outs</p>
    <h2 class="section-title text-center fade-up">NSU <span>Spin-out Companies</span></h2>
    <p class="section-subtitle text-center fade-up">Technology ventures founded by NSU researchers and graduates</p>

    @if($startups->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
        @foreach($startups as $startup)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden card-hover flex flex-col">
            <div class="h-2 bg-tto-teal"></div>
            <div class="p-7 flex-1 flex flex-col">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $startup->name }}</h3>
                        @if($startup->industry_sector)
                        <span class="badge-sector text-xs mt-1 inline-block">{{ $startup->industry_sector }}</span>
                        @endif
                    </div>
                    @if($startup->funding_status)
                    <span class="bg-tto-teal-light text-tto-teal-900 text-xs font-semibold px-3 py-1 rounded-full flex-shrink-0">{{ $startup->funding_status }}</span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 leading-relaxed mb-5 flex-1">{{ $startup->description }}</p>
                <div class="border-t border-gray-100 pt-4 space-y-2 text-sm text-gray-600">
                    @if(count($startup->founders))
                    <div class="flex flex-wrap gap-2">
                        <span class="font-medium text-gray-700 flex-shrink-0">Founders:</span>
                        <span class="break-words min-w-0">{{ implode(', ', $startup->founders) }}</span>
                    </div>
                    @endif
                    @if($startup->faculty_advisor)
                    <div class="flex flex-wrap gap-2">
                        <span class="font-medium text-gray-700 flex-shrink-0">Faculty Advisor:</span>
                        <span class="break-words min-w-0">{{ $startup->faculty_advisor }}</span>
                    </div>
                    @endif
                    @if($startup->technology_used)
                    <div class="flex flex-wrap gap-2">
                        <span class="font-medium text-gray-700 flex-shrink-0">Core Technology:</span>
                        <span class="break-words min-w-0">{{ $startup->technology_used }}</span>
                    </div>
                    @endif
                    @if($startup->incorporation_date)
                    <div class="flex flex-wrap gap-2">
                        <span class="font-medium text-gray-700 flex-shrink-0">Incorporated:</span>
                        <span class="break-words min-w-0">{{ $startup->incorporation_date->format('F Y') }}</span>
                    </div>
                    @endif
                </div>
                @if($startup->website)
                <a href="{{ $startup->website }}" class="btn-outline-teal text-sm text-center mt-5" target="_blank">Visit Website</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        <p>Startup profiles coming soon.</p>
    </div>
    @endif
</section>

{{-- Support Programs --}}
<section class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="section-eyebrow justify-center fade-up">Ecosystem</p>
        <h2 class="section-title text-center fade-up">Startup <span>Support Programs</span></h2>
        <p class="section-subtitle text-center fade-up">Resources available to NSU researchers and students building ventures</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
            @php $programs = [
                ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title'=>'Seed Funding Support', 'desc'=>'The TTO connects early-stage ventures based on NSU IP with potential funding sources. Contact the TTO for details on current funding programs.'],
                ['icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'title'=>'Incubation Support', 'desc'=>'Co-working space, meeting rooms, and lab access are available to registered NSU spin-outs. Contact the TTO office for availability.'],
                ['icon'=>'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'title'=>'Mentor Network', 'desc'=>'The TTO facilitates connections with industry mentors, entrepreneurs, and corporate innovation contacts to support NSU-based ventures.'],
                ['icon'=>'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'title'=>'IP Licensing Support', 'desc'=>'The TTO structures IP licenses to spin-outs on favorable terms. Revenue sharing and royalty arrangements are defined case-by-case with TTO guidance.'],
            ] @endphp
            @foreach($programs as $prog)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center card-hover">
                <div class="w-12 h-12 rounded-xl bg-tto-teal-light flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-tto-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $prog['icon'] }}"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $prog['title'] }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $prog['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 text-center">
    <div class="max-w-xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-3">Have a startup idea?</h2>
        <p class="text-gray-600 mb-6">Talk to the TTO. We'll help you evaluate your IP, structure the venture, and connect you to funding.</p>
        <a href="{{ route('contact') }}" class="btn-primary">Talk to the TTO</a>
    </div>
</section>

@endsection
