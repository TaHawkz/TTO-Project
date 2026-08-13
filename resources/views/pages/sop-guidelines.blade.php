@extends('layouts.app')
@section('title', 'SOP & Guidelines')
@section('description', 'Standard Operating Procedures and inventor guidelines for technology transfer at NSU.')

@section('content')

<section class="relative hero-clip overflow-hidden" style="min-height:280px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">Procedures</p>
        <h1 class="text-4xl font-bold mb-3">SOP &amp; Guidelines</h1>
        <p class="text-white/80 text-lg max-w-2xl">Standard Operating Procedures, inventor guidelines, and commercialization process flowcharts for the NSU technology transfer lifecycle.</p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

    {{-- Process Steps --}}
    <div class="mb-14">
        <p class="section-eyebrow justify-center fade-up">Step by Step</p>
        <h2 class="section-title text-center fade-up">Disclosure-to-<span>Commercialization</span> Process</h2>
        <p class="section-subtitle text-center fade-up">From research innovation to market impact</p>
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-0 sm:gap-0 overflow-x-auto mt-8 pb-4">
            @php $steps = [
                ['n'=>1,'label'=>'Disclose','sub'=>'Submit disclosure form'],
                ['n'=>2,'label'=>'TTO Review','sub'=>'4–8 weeks'],
                ['n'=>3,'label'=>'Ownership','sub'=>'Determination'],
                ['n'=>4,'label'=>'Patentability','sub'=>'Assessment'],
                ['n'=>5,'label'=>'IP Committee','sub'=>'Review & decision'],
                ['n'=>6,'label'=>'Filing','sub'=>'Patent application'],
                ['n'=>7,'label'=>'Commercialize','sub'=>'License or startup'],
            ] @endphp
            @foreach($steps as $step)
            <div class="flex flex-col sm:flex-row items-center flex-shrink-0">
                <div class="process-step flex-shrink-0 min-w-[90px]">
                    <div class="process-step-number">{{ $step['n'] }}</div>
                    <p class="text-xs font-semibold text-tto-teal-900 leading-tight">{{ $step['label'] }}</p>
                    <p class="text-xs text-gray-500">{{ $step['sub'] }}</p>
                </div>
                @if(!$loop->last)
                <div class="hidden sm:block process-connector mx-2"></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- SOPs --}}
    @php $sections = [
        ['title'=>'Inventor Guidelines', 'items'=>[
            ['q'=>'Step 1: Identify a Potentially Patentable Invention', 'a'=>'An invention is potentially patentable if it is novel, non-obvious, and useful. This includes new processes, machines, manufactured items, compositions of matter, or improvements thereof. Software, algorithms, and biological materials may also qualify. If you are unsure, contact the TTO for a preliminary evaluation before investing time in a formal disclosure.'],
            ['q'=>'Step 2: Record Your Invention', 'a'=>'Maintain a dated laboratory notebook or electronic equivalent documenting your invention\'s conception and development. Record the date of conception, the problem you were solving, your technical approach, experimental results, and the names of all contributors. This documentation is critical evidence in establishing inventorship and priority.'],
            ['q'=>'Step 3: Disclose Before Publishing', 'a'=>'Submit your Invention Disclosure Form to the TTO at least 6 weeks before any public disclosure. Public disclosure includes journal articles, conference presentations (oral or poster), patent applications, grant applications, or even informal presentations to third parties. Filing the disclosure form does not commit the university to filing a patent — it begins the evaluation process.'],
            ['q'=>'Step 4: Work with the TTO', 'a'=>'After submission you will receive a unique Disclosure ID (DISC-YYYY-NNNN). A TTO Officer will contact you within 5 business days to begin the Ownership Determination and Patentability Evaluation. You will be asked to review the assessment and provide input on prior art, commercial applications, and potential industry contacts.'],
        ]],
        ['title'=>'Commercialization Guidelines', 'items'=>[
            ['q'=>'Licensing vs. Startup: Choosing the Right Path', 'a'=>'Licensing is typically faster to revenue and lower risk — a third party commercializes the technology and pays royalties to NSU and the inventors. Forming a startup is appropriate when no suitable licensee exists, when the inventor wants to maintain more control, or when the market opportunity is very large but requires significant development before industry adoption. The TTO can help you evaluate both paths.'],
            ['q'=>'Industry Engagement Protocol', 'a'=>'All industry contacts regarding NSU technology should be coordinated through the TTO. Do not enter into informal agreements, share confidential technical details, or accept compensation from companies interested in your technology without first involving the TTO. This protects your legal interests and ensures the university\'s IP rights are preserved.'],
            ['q'=>'Confidential Disclosure Agreements (CDAs)', 'a'=>'Before sharing detailed technical information with any external party, a Confidential Disclosure Agreement (CDA/NDA) must be in place. The TTO can prepare and execute a CDA within 3–5 business days of receiving the request. Do not share materials with external parties without a signed CDA.'],
        ]],
    ] @endphp

    @foreach($sections as $section)
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-4 pb-2 border-b-2 border-tto-teal-light">
                <div class="w-1.5 h-6 rounded-full bg-gradient-to-b from-tto-teal to-tto-amber flex-shrink-0"></div>
                <h2 class="text-xl font-bold text-tto-teal-900">{{ $section['title'] }}</h2>
            </div>
        <div class="space-y-3">
            @foreach($section['items'] as $item)
            <details class="faq-item bg-white rounded-xl border border-gray-200">
                <summary>
                    {{ $item['q'] }}
                    <svg class="faq-icon w-5 h-5 text-tto-teal flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-5 pb-4 pt-1 text-sm text-gray-600 leading-relaxed">{{ $item['a'] }}</div>
            </details>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="bg-tto-teal-light rounded-2xl p-8 text-center">
        <h3 class="font-bold text-tto-teal-900 text-lg mb-2">Ready to Disclose Your Invention?</h3>
        <p class="text-gray-600 text-sm mb-5">Sign in to the portal to access the online Invention Disclosure Form.</p>
        <a href="{{ route('disclosure') }}" class="btn-primary">Go to Disclosure Page</a>
    </div>
</section>

@endsection
