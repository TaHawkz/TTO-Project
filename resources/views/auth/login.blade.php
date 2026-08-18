<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TTMS Portal — NSU TTO</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/TTO_logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }

        .tto-panel {
            background: linear-gradient(155deg, #0A4F51 0%, #0D9EA0 60%, #0A7F82 100%);
        }
        .tto-panel-pattern {
            background-image: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.06) 0%, transparent 50%),
                              radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 0%, transparent 40%),
                              radial-gradient(circle at 50% 50%, rgba(245,158,11,0.05) 0%, transparent 60%);
        }
        .feature-chip {
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.15);
            backdrop-filter: blur(4px);
        }
        .tab-btn {
            position: relative;
            padding: 0.875rem 0;
            font-size: 0.875rem;
            font-weight: 600;
            flex: 1;
            transition: color 0.2s;
            color: #6b7280;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: #0D9EA0;
            transform: scaleX(0);
            transition: transform 0.2s;
        }
        .tab-btn.active { color: #0A4F51; }
        .tab-btn.active::after { transform: scaleX(1); }

        .tto-input {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 0.625rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            font-family: 'Inter', system-ui, sans-serif;
            color: #111827;
        }
        .tto-input:focus {
            border-color: #0D9EA0;
            box-shadow: 0 0 0 3px rgba(13,158,160,0.12);
        }
        .tto-btn-primary {
            width: 100%;
            background: #0D9EA0;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.75rem;
            border-radius: 0.625rem;
            border: none;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            font-family: 'Inter', system-ui, sans-serif;
            letter-spacing: 0.01em;
        }
        .tto-btn-primary:hover { background: #0A7F82; }
        .tto-btn-primary:active { transform: scale(0.99); }

        .input-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.375rem;
        }
        .form-group { margin-bottom: 1rem; }
        .error-msg { font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem; }
        .error-banner {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.8125rem;
            color: #dc2626;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex" style="background:#0A4F51;">

<div class="flex w-full min-h-screen"
     x-data="{ tab: '{{ (($errors->any() && old('_form') === 'register') || session('register')) ? 'register' : 'login' }}' }">

    {{-- ── Left branding panel (hidden on mobile) ── --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 tto-panel tto-panel-pattern flex-col justify-between p-12 relative overflow-hidden">

        {{-- Decorative rings --}}
        <div style="position:absolute;width:400px;height:400px;border-radius:50%;border:1px solid rgba(255,255,255,0.07);top:-80px;right:-120px;pointer-events:none;"></div>
        <div style="position:absolute;width:260px;height:260px;border-radius:50%;border:1px solid rgba(255,255,255,0.05);bottom:60px;left:-80px;pointer-events:none;"></div>

        {{-- Logo + name --}}
        <div>
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/img/TTO_logo.png') }}" alt="NSU TTO" class="h-24 w-auto rounded-xl bg-white/10 p-2 mx-auto">
            </a>
            <div style="margin-top:2.5rem;">
                <span style="display:inline-block;background:rgba(245,158,11,0.2);border:1px solid rgba(245,158,11,0.4);color:#F59E0B;font-size:0.7rem;font-weight:700;letter-spacing:0.12em;padding:0.25rem 0.75rem;border-radius:999px;text-transform:uppercase;">TTMS Portal</span>
                <h1 style="color:white;font-size:2rem;font-weight:800;line-height:1.2;margin-top:1rem;">Technology Transfer<br>Management System</h1>
                <p style="color:rgba(255,255,255,0.65);font-size:0.9rem;line-height:1.6;margin-top:0.75rem;">North South University's internal platform for managing intellectual property, patent portfolios, and commercialization pipelines.</p>
            </div>
        </div>

        {{-- Feature chips --}}
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            @foreach([
                ['📄', 'Invention Disclosure Management'],
                ['⚖️', 'Patent Filing & Deadlines'],
                ['🤝', 'Licensing & Agreements'],
                ['📊', 'Revenue Tracking & Reports'],
            ] as [$icon, $label])
            <div class="feature-chip" style="display:flex;align-items:center;gap:0.75rem;padding:0.625rem 1rem;border-radius:0.75rem;">
                <span style="font-size:1rem;">{{ $icon }}</span>
                <span style="color:rgba(255,255,255,0.85);font-size:0.8125rem;font-weight:500;">{{ $label }}</span>
            </div>
            @endforeach

            <p style="color:rgba(255,255,255,0.35);font-size:0.75rem;margin-top:0.5rem;">
                HEAT Sub-Project PIN-15012 · School of Engineering &amp; Physical Sciences
            </p>
        </div>
    </div>

    {{-- ── Right form panel ── --}}
    <div class="flex-1 flex flex-col items-center justify-center p-6 sm:p-10 bg-gray-50 min-h-screen">

        {{-- Mobile logo --}}
        <div class="lg:hidden text-center mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/img/TTO_logo.png') }}" alt="NSU TTO" style="height:4rem;width:auto;margin:0 auto;border-radius:0.75rem;background:rgba(13,158,160,0.12);padding:0.5rem;">
            </a>
        </div>

        <div class="w-full" style="max-width:420px;">

            <div style="margin-bottom:1.75rem;">
                <h2 style="font-size:1.5rem;font-weight:800;color:#0A4F51;line-height:1.2;">TTMS Portal</h2>
                <p style="font-size:0.875rem;color:#6b7280;margin-top:0.25rem;">Sign in to manage NSU intellectual property</p>
            </div>

            {{-- Card --}}
            <div style="background:white;border-radius:1rem;box-shadow:0 4px 24px rgba(10,79,81,0.10),0 1px 4px rgba(0,0,0,0.06);overflow:hidden;">

                {{-- Tabs --}}
                <div style="display:flex;border-bottom:1.5px solid #f3f4f6;background:white;">
                    <button class="tab-btn" :class="{ active: tab==='login' }" @click="tab='login'">Sign In</button>
                    <button class="tab-btn" :class="{ active: tab==='register' }" @click="tab='register'">Register</button>
                </div>

                {{-- ── Login form ── --}}
                <div x-show="tab==='login'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="padding:1.75rem;">

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    @if($errors->any() && old('_form') === 'login')
                    <div class="error-banner">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <input type="hidden" name="_form" value="login">

                        <div class="form-group">
                            <label class="input-label">Phone / Email</label>
                            <input type="text" name="identifier" value="{{ old('identifier') }}"
                                   class="tto-input" required autofocus autocomplete="username"
                                   placeholder="e.g. 01711000001 or admin@tto.edu">
                            @error('identifier')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.375rem;">
                                <label class="input-label" style="margin-bottom:0;">Password</label>
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="font-size:0.75rem;color:#0D9EA0;text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Forgot password?</a>
                                @endif
                            </div>
                            <input type="password" name="password" class="tto-input" required autocomplete="current-password" placeholder="••••••••">
                            @error('password')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>

                        <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.8125rem;color:#4b5563;cursor:pointer;margin-bottom:1.25rem;">
                            <input type="checkbox" name="remember" style="width:1rem;height:1rem;accent-color:#0D9EA0;cursor:pointer;">
                            Keep me signed in
                        </label>

                        <button type="submit" class="tto-btn-primary">Sign In to Portal</button>
                    </form>

                    <p style="text-align:center;font-size:0.8125rem;color:#6b7280;margin-top:1.25rem;">
                        New to the portal?
                        <button @click="tab='register'" style="color:#0D9EA0;font-weight:600;background:none;border:none;cursor:pointer;padding:0;font-family:inherit;font-size:inherit;">Register here</button>
                    </p>
                </div>

                {{-- ── Register form ── --}}
                <div x-show="tab==='register'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="padding:1.75rem;display:none;">

                    @if($errors->any() && old('_form') === 'register')
                    <div class="error-banner">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="_form" value="register">

                        <div class="form-group">
                            <label class="input-label">Full Name <span style="color:#dc2626;">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="tto-input" required autocomplete="name" placeholder="e.g. Dr. Ahmed Hossain">
                            @error('name')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                            <div class="form-group">
                                <label class="input-label">Email <span style="color:#dc2626;">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="tto-input" required autocomplete="email" placeholder="you@northsouth.edu">
                                @error('email')<p class="error-msg">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label class="input-label">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="tto-input" placeholder="01711000000">
                                @error('phone')<p class="error-msg">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                            <div class="form-group">
                                <label class="input-label">Department</label>
                                <input type="text" name="department" value="{{ old('department') }}" class="tto-input" placeholder="e.g. CSE">
                            </div>
                            <div class="form-group">
                                <label class="input-label">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation') }}" class="tto-input" placeholder="e.g. Professor">
                            </div>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                            <div class="form-group">
                                <label class="input-label">Password <span style="color:#dc2626;">*</span></label>
                                <input type="password" name="password" class="tto-input" required autocomplete="new-password" placeholder="••••••••">
                                @error('password')<p class="error-msg">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label class="input-label">Confirm Password <span style="color:#dc2626;">*</span></label>
                                <input type="password" name="password_confirmation" class="tto-input" required autocomplete="new-password" placeholder="••••••••">
                            </div>
                        </div>

                        <div style="background:#f0fdfa;border:1px solid #99f6e4;border-radius:0.5rem;padding:0.625rem 0.875rem;font-size:0.775rem;color:#0f766e;margin-bottom:1rem;line-height:1.5;">
                            Your account defaults to the <strong>Student</strong> role. A TTO administrator can update it after registration.
                        </div>

                        <button type="submit" class="tto-btn-primary">Create Account</button>
                    </form>

                    <p style="text-align:center;font-size:0.8125rem;color:#6b7280;margin-top:1.25rem;">
                        Already have an account?
                        <button @click="tab='login'" style="color:#0D9EA0;font-weight:600;background:none;border:none;cursor:pointer;padding:0;font-family:inherit;font-size:inherit;">Sign in</button>
                    </p>
                </div>
            </div>

            <p style="text-align:center;font-size:0.75rem;color:#9ca3af;margin-top:1.5rem;">
                <a href="{{ route('home') }}" style="color:#9ca3af;text-decoration:none;" onmouseover="this.style.color='#0D9EA0'" onmouseout="this.style.color='#9ca3af'">← Back to TTO Website</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
