@extends('layouts.app')
@section('title', 'Industry Inquiry')
@section('description', 'Submit an industry inquiry to NSU TTO for sponsored research, technology licensing, consultancy, or joint development partnerships.')

@section('content')

<section class="relative hero-clip overflow-hidden" style="min-height:260px;">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1400&q=80" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(10,79,81,0.92) 0%,rgba(13,158,160,0.70) 100%);"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-white">
        <p class="text-tto-amber font-semibold text-sm uppercase tracking-widest mb-3">For Industry</p>
        <h1 class="text-4xl font-bold mb-3">Industry Inquiry Form</h1>
        <p class="text-white/80 text-lg max-w-2xl">Tell us about your organisation and what you are looking for. We'll match you with the right NSU researchers or technologies.</p>
    </div>
</section>

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">

    <form action="{{ route('industry-inquiry.store') }}" method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="form-label">Company / Organisation Name *</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-input @error('company_name') border-red-400 @enderror" placeholder="Acme Technologies Ltd." required>
                @error('company_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Your Name *</label>
                <input type="text" name="contact_name" value="{{ old('contact_name') }}" class="form-input @error('contact_name') border-red-400 @enderror" placeholder="Jane Doe" required>
                @error('contact_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="form-label">Business Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-input @error('email') border-red-400 @enderror" placeholder="jane@acme.com" required>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+880 1XXX-XXXXXX">
            </div>
        </div>

        <div>
            <label class="form-label">Type of Collaboration *</label>
            <select name="inquiry_type" class="form-input @error('inquiry_type') border-red-400 @enderror" required>
                <option value="">— Select one —</option>
                <option value="sponsored_research"  {{ old('inquiry_type')=='sponsored_research'  ? 'selected':'' }}>Sponsored Research</option>
                <option value="licensing"           {{ old('inquiry_type')=='licensing'           ? 'selected':'' }}>Technology Licensing</option>
                <option value="consultancy"         {{ old('inquiry_type')=='consultancy'         ? 'selected':'' }}>Faculty Consultancy</option>
                <option value="joint_development"   {{ old('inquiry_type')=='joint_development'   ? 'selected':'' }}>Joint Development</option>
                <option value="tech_scouting"       {{ old('inquiry_type')=='tech_scouting'       ? 'selected':'' }}>Technology Scouting</option>
            </select>
            @error('inquiry_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label">Message / Details *</label>
            <textarea name="message" rows="6" class="form-input @error('message') border-red-400 @enderror" placeholder="Describe your area of interest, the problem you're trying to solve, and any relevant details about your organisation or timeline..." required>{{ old('message') }}</textarea>
            @error('message')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <p class="text-xs text-gray-500">By submitting this form you agree that NSU TTO may contact you about this enquiry. No information will be shared with third parties without your consent.</p>

        <button type="submit" class="btn-primary w-full py-3 font-bold text-center">Submit Inquiry</button>
    </form>

    <div class="mt-8 text-center text-sm text-gray-500">
        Prefer to speak first? Email us at <a href="mailto:industry.tto@northsouth.edu" class="text-tto-teal hover:underline">industry.tto@northsouth.edu</a>.
    </div>
</section>

@endsection
