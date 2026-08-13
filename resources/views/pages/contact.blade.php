@extends('layouts.app')
@section('title', 'Contact Us')
@section('description', 'Get in touch with NSU\'s Technology Transfer Office — contact form, office address, and appointment requests.')

@section('content')

{{-- Hero --}}
<section class="relative hero-clip overflow-hidden" style="min-height:260px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">Get in Touch</p>
        <h1 class="text-4xl font-bold mb-3">Contact TTO</h1>
        <p class="text-white/80 text-lg max-w-2xl">We're here to help with your IP questions, disclosure guidance, or partnership inquiries. Reach out and we'll respond within 2 business days.</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

        {{-- Form --}}
        <div class="lg:col-span-3">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Send a Message</h2>
            <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input @error('name') border-red-400 @enderror" placeholder="Dr. Jane Smith" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror" placeholder="jane@northsouth.edu" required>
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+880 1XXX-XXXXXX">
                    </div>
                    <div>
                        <label class="form-label">Subject *</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="form-input @error('subject') border-red-400 @enderror" placeholder="Invention disclosure enquiry" required>
                        @error('subject')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="form-label">Message *</label>
                    <textarea name="message" rows="6" class="form-input @error('message') border-red-400 @enderror" placeholder="Please describe your enquiry in detail..." required>{{ old('message') }}</textarea>
                    @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full text-center py-3 font-bold">Send Message</button>
            </form>
        </div>

        {{-- Office Info --}}
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Office Information</h2>
            <div class="space-y-5">
                <div class="flex gap-4 p-4 bg-tto-teal-light rounded-xl">
                    <div class="w-10 h-10 rounded-lg bg-tto-teal flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-tto-teal-900 text-sm mb-1">Office Address</p>
                        <p class="text-sm text-gray-600 leading-relaxed">Plot-15, Block-B, Bashundhara<br>Dhaka-1229, Bangladesh</p>
                    </div>
                </div>
                <div class="flex gap-4 p-4 bg-tto-teal-light rounded-xl">
                    <div class="w-10 h-10 rounded-lg bg-tto-teal flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-tto-teal-900 text-sm mb-1">PABX</p>
                        <p class="text-sm text-gray-600">+880-2-55668200</p>
                    </div>
                </div>
                <div class="flex gap-4 p-4 bg-tto-teal-light rounded-xl">
                    <div class="w-10 h-10 rounded-lg bg-tto-teal flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-tto-teal-900 text-sm mb-1">Email</p>
                        <a href="mailto:tto@northsouth.edu" class="text-sm text-tto-teal hover:underline">tto@northsouth.edu</a><br>
                        <a href="mailto:industry.tto@northsouth.edu" class="text-sm text-tto-teal hover:underline">industry.tto@northsouth.edu</a>
                    </div>
                </div>
                <div class="flex gap-4 p-4 bg-tto-teal-light rounded-xl">
                    <div class="w-10 h-10 rounded-lg bg-tto-teal flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-tto-teal-900 text-sm mb-1">Office Hours</p>
                        <p class="text-sm text-gray-600">Sunday – Thursday<br>9:00 AM – 5:00 PM BST</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
