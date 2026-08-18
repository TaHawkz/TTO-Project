<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NSU TTO') — Technology Transfer Office</title>
    <meta name="description" content="@yield('description', 'North South University Technology Transfer Office — protecting and commercializing NSU intellectual property for societal impact.')">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/TTO_logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Responsive utility classes not yet in build --}}
    <style>
        @media (min-width: 640px) {
            .sm\:text-3xl { font-size: 1.875rem; line-height: 2.25rem; }
            .sm\:text-4xl { font-size: 2.25rem; line-height: 2.5rem; }
            .sm\:py-14 { padding-top: 3.5rem; padding-bottom: 3.5rem; }
            .sm\:py-16 { padding-top: 4rem; padding-bottom: 4rem; }
            .sm\:py-20 { padding-top: 5rem; padding-bottom: 5rem; }
            .sm\:py-28 { padding-top: 7rem; padding-bottom: 7rem; }
            .sm\:p-8 { padding: 2rem; }
            .sm\:gap-0 { gap: 0; }
        }
        @media (min-width: 1024px) {
            .lg\:sticky { position: sticky; }
            .lg\:grid-cols-6 { grid-template-columns: repeat(6, minmax(0, 1fr)); }
            .lg\:col-start-2 { grid-column-start: 2; }
            .lg\:col-start-4 { grid-column-start: 4; }
        }
    </style>
</head>
<body class="antialiased">

    {{-- Preloader --}}
    <div id="tto-preloader">
        <div class="preloader-inner">
            <img src="{{ asset('assets/img/TTO_logo_shortened.png') }}" alt="NSU TTO" class="preloader-logo">
            <div class="preloader-ring"></div>
            <div class="preloader-ring-outer"></div>
        </div>
    </div>

    {{-- Topbar ticker --}}
    <div class="tto-topbar">
        <div class="tto-topbar-track">
            {{-- Duplicated for seamless loop --}}
            @foreach([1,2] as $_)
            <span class="tto-topbar-item"><span>NSU Technology Transfer Office</span> — Accepting Invention Disclosures for 2026</span>
            <span class="tto-topbar-sep">·</span>
            <span class="tto-topbar-item">HEAT Sub-Project <span>PIN-15012</span> · School of Engineering &amp; Physical Sciences</span>
            <span class="tto-topbar-sep">·</span>
            <span class="tto-topbar-item">Contact: <span>office.tto@northsouth.edu</span></span>
            <span class="tto-topbar-sep">·</span>
            <span class="tto-topbar-item">Office Hours: <span>Sun–Thu, 9 AM – 5 PM BST</span></span>
            <span class="tto-topbar-sep">·</span>
            @endforeach
        </div>
    </div>

    {{-- Navigation --}}
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm" x-data="{ mobileOpen: false, aboutOpen: false, resourcesOpen: false }">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-24">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex-shrink-0">
                    <img src="{{ asset('assets/img/TTO_logo.png') }}" alt="NSU Technology Transfer Office" class="h-16 w-auto">
                </a>

                {{-- Desktop nav --}}
                <div class="hidden lg:flex items-center gap-7">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>

                    {{-- About dropdown --}}
                    <div class="relative" @mouseenter="aboutOpen=true" @mouseleave="aboutOpen=false">
                        <button class="nav-link flex items-center gap-1">
                            About
                            <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="aboutOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 top-full mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50" style="display:none;">
                            <a href="{{ route('about') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tto-teal-light hover:text-tto-teal-dark font-medium rounded-lg mx-1">About TTO</a>
                            <a href="{{ route('faq') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tto-teal-light hover:text-tto-teal-dark font-medium rounded-lg mx-1">FAQ</a>
                            <a href="{{ route('contact') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tto-teal-light hover:text-tto-teal-dark font-medium rounded-lg mx-1">Contact Us</a>
                            <a href="{{ route('terms') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tto-teal-light hover:text-tto-teal-dark font-medium rounded-lg mx-1">Terms of Use</a>
                        </div>
                    </div>

                    {{-- Resources dropdown --}}
                    <div class="relative" @mouseenter="resourcesOpen=true" @mouseleave="resourcesOpen=false">
                        <button class="nav-link flex items-center gap-1">
                            Resources
                            <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="resourcesOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50" style="display:none;">
                            <a href="{{ route('ip-policy') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tto-teal-light hover:text-tto-teal-dark font-medium rounded-lg mx-1">IP Policy</a>
                            <a href="{{ route('sop-guidelines') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tto-teal-light hover:text-tto-teal-dark font-medium rounded-lg mx-1">SOP &amp; Guidelines</a>
                            <a href="{{ route('forms-templates') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-tto-teal-light hover:text-tto-teal-dark font-medium rounded-lg mx-1">Forms &amp; Templates</a>
                        </div>
                    </div>

                    <a href="{{ route('disclosure') }}" class="nav-link {{ request()->routeIs('disclosure') ? 'active' : '' }}">Invention Disclosure</a>
                    <a href="{{ route('technology-portfolio') }}" class="nav-link {{ request()->routeIs('technology-portfolio') ? 'active' : '' }}">Technology Portfolio</a>
                    <a href="{{ route('industry-collaboration') }}" class="nav-link {{ request()->routeIs('industry-collaboration') ? 'active' : '' }}">Industry</a>
                    <a href="{{ route('startups') }}" class="nav-link {{ request()->routeIs('startups') ? 'active' : '' }}">Startups</a>
                </div>

                {{-- Portal button + hamburger --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:inline-block btn-amber text-sm py-2 px-5">Internal Portal</a>
                    <button @click="mobileOpen=!mobileOpen" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100" aria-label="Toggle menu">
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="lg:hidden border-t border-gray-100 py-4 space-y-1" style="display:none;">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">Home</a>
                <a href="{{ route('about') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">About TTO</a>
                <a href="{{ route('terms') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">Terms of Use</a>
                <a href="{{ route('ip-policy') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">IP Policy</a>
                <a href="{{ route('sop-guidelines') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">SOP &amp; Guidelines</a>
                <a href="{{ route('forms-templates') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">Forms &amp; Templates</a>
                <a href="{{ route('disclosure') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">Invention Disclosure</a>
                <a href="{{ route('technology-portfolio') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">Technology Portfolio</a>
                <a href="{{ route('industry-collaboration') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">Industry Collaboration</a>
                <a href="{{ route('startups') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">Startups &amp; Innovation</a>
                <a href="{{ route('faq') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">FAQ</a>
                <a href="{{ route('contact') }}" class="block px-4 py-2 text-sm font-medium text-gray-800 hover:text-tto-teal rounded-lg hover:bg-tto-teal-light">Contact Us</a>
                <div class="pt-2 px-4">
                    <a href="{{ route('login') }}" class="btn-amber block text-center text-sm py-2">Internal Portal</a>
                </div>
            </div>
        </nav>
    </header>

    {{-- Flash message --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="alert-success flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    {{-- Page content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-tto-teal-900 text-white pt-14 pb-8 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

                {{-- Brand --}}
                <div class="lg:col-span-1">
                    <img src="{{ asset('assets/img/TTO_logo.png') }}" alt="NSU TTO" class="h-16 w-auto mb-4 rounded-xl p-1.5 bg-white/10">
                    <p class="text-sm text-white/70 leading-relaxed">
                        The Technology Transfer Office of North South University protects and commercializes NSU's intellectual property for societal and economic impact.
                    </p>
                </div>

                {{-- Resources --}}
                <div>
                    <h4 class="font-semibold text-sm uppercase tracking-wider text-tto-amber mb-4">Resources</h4>
                    <ul class="space-y-2 text-sm text-white/70">
                        <li><a href="{{ route('ip-policy') }}" class="hover:text-white transition-colors">IP Policy</a></li>
                        <li><a href="{{ route('sop-guidelines') }}" class="hover:text-white transition-colors">SOP &amp; Guidelines</a></li>
                        <li><a href="{{ route('forms-templates') }}" class="hover:text-white transition-colors">Forms &amp; Templates</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Use</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="font-semibold text-sm uppercase tracking-wider text-tto-amber mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-white/70">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-tto-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Plot-15, Block-B, Bashundhara, Dhaka-1229, Bangladesh
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 text-tto-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +880-2-55668200
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 text-tto-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            office.tto@northsouth.edu
                        </li>
                    </ul>
                </div>

                {{-- Quick links --}}
                <div>
                    <h4 class="font-semibold text-sm uppercase tracking-wider text-tto-amber mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-white/70">
                        <li><a href="{{ route('disclosure') }}" class="hover:text-white transition-colors">Submit a Disclosure</a></li>
                        <li><a href="{{ route('technology-portfolio') }}" class="hover:text-white transition-colors">Technology Portfolio</a></li>
                        <li><a href="{{ route('industry-collaboration') }}" class="hover:text-white transition-colors">Industry Collaboration</a></li>
                        <li><a href="{{ route('startups') }}" class="hover:text-white transition-colors">Startups &amp; Innovation</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact TTO</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-white/50">
                <p>&copy; {{ date('Y') }} Technology Transfer Office, North South University. All rights reserved.</p>
                <p>HEAT Sub-Project PIN-15012 | School of Engineering &amp; Physical Sciences</p>
            </div>
        </div>
    </footer>

</body>
</html>
