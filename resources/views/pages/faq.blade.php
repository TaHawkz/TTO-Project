@extends('layouts.app')
@section('title', 'FAQ')
@section('description', 'Frequently asked questions about patents, IP ownership, commercialization, and TTO services at NSU.')

@section('content')

{{-- Hero --}}
<section class="relative hero-clip overflow-hidden" style="min-height:280px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">Help</p>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3">Frequently Asked Questions</h1>
        <p class="text-white/80 text-lg max-w-2xl">Answers to common questions about patents, IP ownership, commercialization, and using the TTO portal.</p>
    </div>
</section>

<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

    @php $categories = [
        ['title'=>'Patents &amp; Ownership', 'questions'=>[
            ['q'=>'Who owns the IP I create at NSU?', 'a'=>'Under NSU\'s IP Policy, intellectual property created using university resources, facilities, or funding is generally owned by the university. However, if the invention was created entirely on the inventor\'s own time without using university resources, the inventor may retain ownership. Sponsored research agreements may also define ownership differently — the TTO will review each case individually through the Ownership Determination workflow.'],
            ['q'=>'What qualifies for a patent?', 'a'=>'An invention must be novel (new), non-obvious (not obvious to someone skilled in the field), and useful (has a practical application). The TTO performs a prior art search and patentability assessment before recommending patent filing. Software, business methods, and algorithms may qualify under certain conditions — speak to the TTO for guidance.'],
            ['q'=>'How long does the patent process take?', 'a'=>'From disclosure submission to a granted patent typically takes 3–5 years. The TTO Review and Ownership Determination stages take 4–8 weeks. Patent drafting and filing takes 2–6 months. Patent prosecution (examination at the patent office) usually takes 1–3 years depending on the jurisdiction.'],
        ]],
        ['title'=>'Commercialization &amp; Licensing', 'questions'=>[
            ['q'=>'How does licensing work?', 'a'=>'Once a patent is filed or granted, the TTO actively seeks industry partners interested in commercializing the technology. Licensing can be exclusive (one company gets rights) or non-exclusive (multiple parties). The TTO negotiates the terms and manages the licensing agreement. Revenue from licensing is distributed to inventors, departments, and the university according to the official revenue sharing policy — contact the TTO for details.'],
            ['q'=>'What is the revenue sharing formula?', 'a'=>'NSU\'s revenue sharing policy defines how net royalty and licensing income is distributed among inventors, departments, and the university. The specific distribution percentages are defined in the official NSU IP Policy document. Please contact the TTO office or download the IP Policy from the IP Policy page for the current formula.'],
            ['q'=>'Can I start a company based on my research?', 'a'=>'Yes — the TTO actively supports spin-out startups. You\'ll first need to disclose the underlying IP, which will go through the Ownership Determination process. Once ownership is established, the TTO can help structure a license to the startup, connect you to incubation resources, and assist with early-stage funding opportunities. Contact the TTO for details on currently available support programs.'],
        ]],
        ['title'=>'TTO Services', 'questions'=>[
            ['q'=>'What does the TTO charge for its services?', 'a'=>'The TTO does not charge inventors directly for its core services — disclosure evaluation, patent filing, and commercialization support are funded by the university. However, external patent filing costs (attorney fees, patent office fees) are covered by the university and recouped from future licensing revenue if successful.'],
            ['q'=>'Can I publish my research before filing a patent?', 'a'=>'You must disclose your invention to the TTO before publishing. Public disclosure (a journal article, conference presentation, or even a poster) starts a 12-month grace period in some jurisdictions (like the US) but may bar patent protection in others (like Europe). The TTO strongly recommends submitting a disclosure at least 6 weeks before any planned publication.'],
        ]],
        ['title'=>'Using the Portal', 'questions'=>[
            ['q'=>'Who can use the TTMS portal?', 'a'=>'The portal is accessible to all NSU faculty, students, and staff with institutional email addresses. Eight user roles are supported: Student, Faculty, Staff, Reviewer, TTO Officer, Legal Officer, Director, and System Administrator. Some roles require admin approval after registration.'],
            ['q'=>'How long does it take to get a disclosure ID?', 'a'=>'A unique Disclosure ID (format: DISC-YYYY-NNNN) is generated automatically the moment you submit your disclosure. If you save as draft, the ID is assigned upon final submission. You will also receive an email confirmation with the ID within minutes of submission.'],
        ]],
    ] @endphp

    @foreach($categories as $cat)
    <div class="mb-10 fade-up">
        <div class="flex items-center gap-3 mb-4 pb-2 border-b-2 border-tto-teal-light">
            <div class="w-1.5 h-6 rounded-full bg-gradient-to-b from-tto-teal to-tto-amber flex-shrink-0"></div>
            <h2 class="text-xl font-bold text-tto-teal-900">{!! $cat['title'] !!}</h2>
        </div>
        <div class="space-y-3">
            @foreach($cat['questions'] as $faq)
            <details class="faq-item bg-white rounded-xl border border-gray-200 group">
                <summary>
                    {{ $faq['q'] }}
                    <svg class="faq-icon w-5 h-5 text-tto-teal flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </summary>
                <div class="px-5 pb-4 pt-1 text-sm text-gray-600 leading-relaxed">{{ $faq['a'] }}</div>
            </details>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="bg-tto-teal-light rounded-2xl p-8 text-center mt-12 fade-up">
        <h3 class="font-bold text-tto-teal-900 text-lg mb-2">Still have questions?</h3>
        <p class="text-gray-600 text-sm mb-5">Our team is happy to help. Reach out directly or submit a contact form.</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('contact') }}" class="btn-primary text-sm">Contact Us</a>
            <a href="mailto:tto@northsouth.edu" class="btn-outline bg-tto-teal text-white text-sm">tto@northsouth.edu</a>
        </div>
    </div>
</section>

@endsection
