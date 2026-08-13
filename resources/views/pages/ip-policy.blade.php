@extends('layouts.app')
@section('title', 'IP Policy')
@section('description', 'NSU\'s Intellectual Property Policy — ownership rules, disclosure obligations, revenue sharing, and governance.')

@section('content')

<section class="relative hero-clip overflow-hidden" style="min-height:280px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">Governance</p>
        <h1 class="text-4xl font-bold mb-3">IP Policy &amp; Governance</h1>
        <p class="text-white/80 text-lg max-w-2xl">North South University's Intellectual Property Policy governing the ownership, protection, and commercialization of university-created innovations.</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">

        {{-- TOC --}}
        <div class="lg:col-span-1">
            <div class="sticky top-28 bg-tto-teal-light rounded-xl p-5">
                <p class="font-semibold text-tto-teal-900 text-sm mb-3 uppercase tracking-wider">Contents</p>
                <ul class="space-y-2 text-sm">
                    <li><a href="#section-1" class="text-gray-600 hover:text-tto-teal transition-colors">1. Policy Overview</a></li>
                    <li><a href="#section-2" class="text-gray-600 hover:text-tto-teal transition-colors">2. Governance Documents</a></li>
                </ul>
            </div>
        </div>

        {{-- Policy content --}}
        <div class="lg:col-span-3 prose prose-gray max-w-none">
            <div id="section-1" class="mb-10">
                <h2 class="text-xl font-bold text-tto-teal-900 mb-4">1. Policy Overview</h2>
                <div class="bg-tto-teal-light border border-tto-teal rounded-xl p-6 text-center">
                    <svg class="w-10 h-10 mx-auto mb-3 text-tto-teal opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h3 class="font-semibold text-tto-teal-900 text-lg mb-2">Full IP Policy Coming Soon</h3>
                    <p class="text-gray-600 text-sm leading-relaxed max-w-lg mx-auto">The official NSU Intellectual Property Policy — covering ownership rules, disclosure obligations, revenue sharing, and governance — will be published here by the TTO office.</p>
                    <p class="text-gray-600 text-sm leading-relaxed max-w-lg mx-auto mt-3">In the meantime, please contact the TTO directly for a copy of the current policy document.</p>
                    <a href="{{ route('contact') }}" class="mt-5 inline-block btn-primary text-sm">Contact TTO Office</a>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mt-6">
                    <p class="text-amber-800 text-sm font-medium">Important: All NSU faculty, staff, and students are required to disclose any potentially patentable invention to the TTO <strong>before</strong> making any public disclosure (publications, conferences, presentations). Contact the TTO office for guidance on the disclosure process.</p>
                </div>
            </div>
            <div id="section-2" class="mb-10">
                <h2 class="text-xl font-bold text-tto-teal-900 mb-4">2. Governance Documents</h2>
                <div class="space-y-3">
                    @foreach(['NSU Intellectual Property Policy (Full Text)', 'Technology Transfer Operating Procedures', 'Revenue Sharing Agreement Template', 'Conflict of Interest Disclosure Form'] as $doc)
                    <a href="{{ asset('docs/placeholder.pdf') }}" class="download-card" target="_blank">
                        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $doc }}</p>
                            <p class="text-xs text-gray-500">PDF — Click to download</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
