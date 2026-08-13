@extends('layouts.app')
@section('title', 'Invention Disclosure')
@section('description', 'Learn how to disclose your invention to the NSU Technology Transfer Office and begin the patent evaluation process.')

@section('content')

<section class="relative hero-clip overflow-hidden" style="min-height:280px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1576086213369-97a306d36557?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">For Inventors</p>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3">Invention Disclosure</h1>
        <p class="text-white/80 text-lg max-w-2xl">The invention disclosure is the first step in protecting your innovation. Use the TTMS portal to submit your disclosure online, or contact the TTO for assistance.</p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

    {{-- Why disclose --}}
    <div class="mb-14 text-center">
        <h2 class="section-title">Why File an Invention Disclosure?</h2>
        <p class="section-subtitle">Filing a disclosure triggers several important protections for your innovation.</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-8">
            @php $reasons = [
                ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title'=>'IP Protection', 'desc'=>'Starts the process to file a patent application before any public disclosure, preserving rights in over 150 countries.'],
                ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title'=>'Revenue Potential', 'desc'=>'Inventors receive 40% of net licensing royalties. Filing your disclosure is the essential first step.'],
                ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title'=>'TTO Support', 'desc'=>'Gain access to TTO\'s commercialization team, industry network, and licensing expertise.'],
            ] @endphp
            @foreach($reasons as $r)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center card-hover">
                <div class="w-12 h-12 rounded-xl bg-tto-teal-light flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-tto-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $r['icon'] }}"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $r['title'] }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $r['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- What to expect --}}
    <div class="mb-14">
        <h2 class="text-xl font-bold text-tto-teal-900 mb-6">What the Disclosure Form Covers</h2>
        <div class="space-y-4">
            @php $sections = [
                ['n'=>'01', 'title'=>'Invention Summary', 'desc'=>'A concise lay-language description of what the invention is, what problem it solves, and how it differs from existing approaches. This section is used for initial TTO review and for contacting potential industry partners.'],
                ['n'=>'02', 'title'=>'Inventor Information', 'desc'=>'Full names, departments, NSU ID numbers, and percentage contributions of all inventors. Co-inventors must each sign the completed disclosure.'],
                ['n'=>'03', 'title'=>'Technical Description', 'desc'=>'A detailed technical description of the invention — how it works, preferred embodiments, and any experimental data or proof-of-concept results. Attach diagrams, schematics, and lab notebooks as needed.'],
                ['n'=>'04', 'title'=>'Prior Art Knowledge', 'desc'=>'Any published literature, patents, or products you are aware of that are similar to your invention. This helps the TTO\'s patentability evaluation and the patent attorney drafting the application.'],
                ['n'=>'05', 'title'=>'Funding & Resources', 'desc'=>'A list of all grants, contracts, or sponsored research agreements that funded any part of the work leading to this invention. This determines whether US government (Bayh-Dole) or sponsor IP rights apply.'],
                ['n'=>'06', 'title'=>'Publication & Disclosure Plans', 'desc'=>'Any planned publication dates, conference presentation dates, or student thesis defenses. Provide at least 6 weeks notice so the TTO can file a provisional patent application before public disclosure.'],
            ] @endphp
            @foreach($sections as $sec)
            <div class="flex gap-4 bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="text-2xl font-black text-tto-teal-light/80 flex-shrink-0 leading-none pt-0.5">{{ $sec['n'] }}</div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $sec['title'] }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $sec['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Timeline --}}
    <div class="mb-14 bg-tto-teal-light rounded-2xl p-8">
        <h2 class="text-xl font-bold text-tto-teal-900 mb-6 text-center">What Happens After You Submit</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php $timeline = [
                ['time'=>'Day 1', 'event'=>'Disclosure ID assigned (DISC-YYYY-NNNN) + email confirmation'],
                ['time'=>'5 days', 'event'=>'TTO Officer contacts you to schedule a first meeting'],
                ['time'=>'4–8 wks', 'event'=>'Ownership Determination + Patentability Assessment complete'],
                ['time'=>'IP Cmte', 'event'=>'Decision: file patent, publish open access, or release to inventor'],
            ] @endphp
            @foreach($timeline as $t)
            <div class="text-center">
                <div class="inline-block bg-tto-teal text-white text-xs font-bold px-3 py-1 rounded-full mb-2">{{ $t['time'] }}</div>
                <p class="text-xs text-gray-700 leading-relaxed">{{ $t['event'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- CTA --}}
    <div class="text-center">
        <a href="/portal" class="btn-primary text-lg px-8 py-3 inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            Sign In to Submit a Disclosure
        </a>
        <p class="text-sm text-gray-500 mt-3">Portal access requires an NSU institutional email account.</p>
        <p class="text-sm text-gray-500 mt-1">Prefer paper? <a href="{{ route('forms-templates') }}" class="text-tto-teal hover:underline">Download the PDF form</a> and email it to <a href="mailto:tto@northsouth.edu" class="text-tto-teal hover:underline">tto@northsouth.edu</a>.</p>
    </div>

</section>
@endsection
