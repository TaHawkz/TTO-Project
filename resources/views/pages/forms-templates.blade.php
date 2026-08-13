@extends('layouts.app')
@section('title', 'Forms & Templates')
@section('description', 'Download NSU TTO forms and agreement templates including invention disclosure forms, NDAs, and licensing agreements.')

@section('content')

<section class="relative hero-clip overflow-hidden" style="min-height:280px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1568667256549-094345857637?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">Downloads</p>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3">Forms &amp; Templates</h1>
        <p class="text-white/80 text-lg max-w-2xl">Download official TTO forms and agreement templates. Contact the TTO if you need a customized version or have questions about any document.</p>
    </div>
</section>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

    @php $categories = [
        ['title'=>'Inventor Forms', 'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'docs'=>[
            ['name'=>'Invention Disclosure Form', 'desc'=>'Submit a new invention disclosure. Complete all sections for fastest processing.', 'size'=>'PDF, 4 pages'],
            ['name'=>'Co-Inventor Declaration', 'desc'=>'Required when an invention has multiple inventors. Each co-inventor must sign.', 'size'=>'PDF, 2 pages'],
            ['name'=>'Prior Art Disclosure Statement', 'desc'=>'Identify known prior art relevant to your invention to assist the patentability assessment.', 'size'=>'PDF, 3 pages'],
        ]],
        ['title'=>'Agreement Templates', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'docs'=>[
            ['name'=>'Confidential Disclosure Agreement (CDA/NDA)', 'desc'=>'Standard mutual NDA for sharing confidential information with industry partners before a licensing discussion.', 'size'=>'DOCX, 6 pages'],
            ['name'=>'IP Assignment Agreement', 'desc'=>'Used when IP ownership is being transferred from inventor(s) to the university or to a third party.', 'size'=>'DOCX, 8 pages'],
            ['name'=>'Revenue Sharing Agreement', 'desc'=>'Documents the distribution of royalty income among inventors, department, and university.', 'size'=>'DOCX, 5 pages'],
            ['name'=>'Sponsored Research Agreement Template', 'desc'=>'Base template for industry-funded research projects at NSU. Requires TTO and legal review before execution.', 'size'=>'DOCX, 12 pages'],
            ['name'=>'Material Transfer Agreement (MTA)', 'desc'=>'Governs the transfer of biological or physical research materials between NSU and external parties.', 'size'=>'DOCX, 7 pages'],
            ['name'=>'Waiver/Release Form', 'desc'=>'Used when the university releases IP rights back to the inventor or when an inventor waives rights to the university.', 'size'=>'PDF, 3 pages'],
        ]],
    ] @endphp

    @foreach($categories as $cat)
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-9 h-9 rounded-lg bg-tto-teal flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cat['icon'] }}"/></svg>
            </div>
            <h2 class="text-xl font-bold text-tto-teal-900">{{ $cat['title'] }}</h2>
        </div>
        <div class="space-y-3">
            @foreach($cat['docs'] as $doc)
            <a href="{{ asset('docs/placeholder.pdf') }}" class="download-card" target="_blank">
                <div class="w-11 h-11 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 text-sm">{{ $doc['name'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $doc['desc'] }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-400">{{ $doc['size'] }}</p>
                    <svg class="w-5 h-5 text-tto-teal mt-1 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="bg-tto-teal-light rounded-2xl p-7 flex flex-col sm:flex-row items-center gap-5">
        <div class="w-12 h-12 rounded-xl bg-tto-teal flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1 text-center sm:text-left">
            <p class="font-semibold text-tto-teal-900">Need a customized form or template?</p>
            <p class="text-sm text-gray-600 mt-0.5">Contact the TTO directly — we can adapt any template for your specific situation.</p>
        </div>
        <a href="{{ route('contact') }}" class="btn-primary flex-shrink-0 text-sm">Contact TTO</a>
    </div>
</section>

@endsection
