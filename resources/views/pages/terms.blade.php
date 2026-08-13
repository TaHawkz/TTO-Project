@extends('layouts.app')
@section('title', 'Terms & Conditions')
@section('description', 'Terms and conditions governing use of the NSU Technology Transfer Office website and TTMS portal.')

@section('content')

<section class="relative hero-clip overflow-hidden" style="min-height:220px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">Legal</p>
        <h1 class="text-4xl font-bold mb-2">Terms &amp; Conditions</h1>
        <p class="text-white/70 text-sm">Last updated: August 2026</p>
    </div>
</section>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="prose prose-gray max-w-none">

        @php $sections = [
            ['title'=>'1. Acceptance of Terms', 'body'=>'By accessing and using the NSU Technology Transfer Office website and the Technology Transfer Management System (TTMS) portal, you accept and agree to be bound by these Terms and Conditions and NSU\'s Privacy Policy. If you do not agree to these terms, please do not use this website.'],
            ['title'=>'2. Intended Users', 'body'=>'This website and the TTMS portal are intended for use by members of the North South University community (faculty, students, staff) and authorised external parties such as industry partners. Use is restricted to lawful purposes consistent with NSU\'s policies and applicable laws of Bangladesh.'],
            ['title'=>'3. Intellectual Property', 'body'=>'All content on this website — including text, graphics, logos, icons, images, and software — is the property of North South University or its content suppliers and is protected under applicable copyright and intellectual property laws. Unauthorised reproduction, distribution, or modification of any content is prohibited. Technologies listed in the Technology Portfolio are proprietary to NSU and subject to the terms of specific licensing agreements.'],
            ['title'=>'4. Accuracy of Information', 'body'=>'NSU TTO endeavours to keep the information on this website accurate and up to date. However, we make no warranties, express or implied, as to the completeness, accuracy, reliability, or suitability of any information. Technology descriptions, development stages, and availability are subject to change without notice. Always contact the TTO directly to confirm current status of any technology or service.'],
            ['title'=>'5. External Links', 'body'=>'This website may contain links to external websites for convenience. NSU TTO does not endorse or take responsibility for the content or practices of any external sites. Clicking an external link is at your own risk.'],
            ['title'=>'6. Confidentiality', 'body'=>'Information submitted through this website (contact forms, industry inquiry forms) will be treated as confidential by NSU TTO and used only for the purpose for which it was submitted. We will not share your information with third parties without your consent, except where required by law or NSU policy.'],
            ['title'=>'7. Limitation of Liability', 'body'=>'To the fullest extent permitted by law, NSU TTO and North South University shall not be liable for any direct, indirect, incidental, or consequential damages arising from your use of, or inability to use, this website or any content on it.'],
            ['title'=>'8. Amendments', 'body'=>'NSU TTO reserves the right to amend these Terms and Conditions at any time. Continued use of the website after any changes constitutes acceptance of the revised terms. The effective date at the top of this page will be updated accordingly.'],
            ['title'=>'9. Governing Law', 'body'=>'These Terms and Conditions are governed by and construed in accordance with the laws of Bangladesh. Any disputes arising from the use of this website shall be subject to the exclusive jurisdiction of the courts of Bangladesh.'],
            ['title'=>'10. Contact', 'body'=>'If you have questions about these Terms and Conditions, please contact the NSU Technology Transfer Office at tto@northsouth.edu.'],
        ] @endphp

        @foreach($sections as $section)
        <div class="mb-8">
            <h2 class="text-lg font-bold text-tto-teal-900 mb-2">{{ $section['title'] }}</h2>
            <p class="text-gray-600 leading-relaxed text-sm">{{ $section['body'] }}</p>
        </div>
        @endforeach

    </div>
    <div class="mt-10 text-center">
        <a href="{{ route('contact') }}" class="btn-outline">Contact TTO</a>
    </div>
</section>

@endsection
