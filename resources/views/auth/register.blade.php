<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register · LimpioZambo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] },
                    keyframes: {
                        slideUp: { from: { opacity: '0', transform: 'translateY(24px)' }, to: { opacity: '1', transform: 'none' } },
                    },
                    animation: {
                        slideUp: 'slideUp .65s cubic-bezier(.16,1,.3,1) both',
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .wave-svg   { animation: waveMove 8s ease-in-out infinite; }
        .wave-svg-2 { animation: waveMove 11s ease-in-out infinite reverse; }
        @keyframes waveMove { 0%,100%{transform:translateX(0)} 50%{transform:translateX(-40px)} }
        @keyframes floatDot { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }
        .dot-1{animation:floatDot 4s ease-in-out infinite}
        .dot-2{animation:floatDot 6s ease-in-out infinite 1s}
        .dot-3{animation:floatDot 5s ease-in-out infinite 2s}
        .dot-4{animation:floatDot 7s ease-in-out infinite .5s}
        .grid-lines {
            background-image:
                linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            -webkit-appearance: none;
            appearance: none;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4 py-8">

    <div class="w-full max-w-[980px] bg-white rounded-3xl shadow-[0_24px_60px_rgba(0,0,0,.12)] overflow-hidden flex animate-slideUp">

        {{-- ═══════════ LEFT PANEL ═══════════ --}}
        <div class="hidden md:flex relative w-[38%] flex-shrink-0 flex-col justify-between overflow-hidden"
             style="background: linear-gradient(145deg, #16a34a 0%, #15803d 40%, #166534 100%);">

            {{-- Grid overlay --}}
            <div class="absolute inset-0 grid-lines"></div>

            {{-- Animated wave shapes --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <svg class="wave-svg absolute -bottom-6 -left-10 w-[140%] opacity-30" viewBox="0 0 600 200" fill="none">
                    <path d="M0 100 C100 40, 200 160, 300 100 C400 40, 500 160, 600 100 L600 200 L0 200 Z" fill="white"/>
                </svg>
                <svg class="wave-svg-2 absolute -bottom-2 -left-10 w-[140%] opacity-20" viewBox="0 0 600 200" fill="none">
                    <path d="M0 120 C80 60, 180 180, 300 120 C420 60, 520 180, 600 120 L600 200 L0 200 Z" fill="white"/>
                </svg>
                <svg class="wave-svg absolute -top-6 -right-10 w-[140%] rotate-180 opacity-15" viewBox="0 0 600 200" fill="none">
                    <path d="M0 100 C100 40, 200 160, 300 100 C400 40, 500 160, 600 100 L600 200 L0 200 Z" fill="white"/>
                </svg>
            </div>

            {{-- Floating dots --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="dot-1 absolute top-[16%] left-[18%] w-12 h-12 rounded-full border-2 border-white/30 bg-white/10"></div>
                <div class="dot-2 absolute top-[10%] right-[16%] w-6 h-6 rounded-full bg-white/20"></div>
                <div class="dot-3 absolute top-[50%] left-[12%] w-8 h-8 rounded-full border border-white/25 bg-white/10"></div>
                <div class="dot-4 absolute bottom-[22%] right-[20%] w-5 h-5 rounded-full bg-white/25"></div>
                <div class="dot-1 absolute top-[35%] right-[8%] w-10 h-10 rounded-full border border-white/20 bg-white/5"></div>
                <div class="dot-3 absolute bottom-[38%] left-[30%] w-4 h-4 rounded-full bg-white/20"></div>
            </div>

            {{-- Top: Logo --}}
            <div class="relative z-10 p-8 pt-9">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/20 border border-white/30 rounded-lg flex items-center justify-center">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path d="M10 3L13 8.5H17.5L13.8 11.5L15.5 17L10 13.8L4.5 17L6.2 11.5L2.5 8.5H7L10 3Z" fill="white"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-sm tracking-tight">LimpioZambo</span>
                </div>
            </div>

            {{-- Bottom: Text --}}
            <div class="relative z-10 px-8 pb-12">
                <p class="text-white/70 text-[11px] font-medium uppercase tracking-widest mb-3">Join the platform</p>
                <h2 class="text-white font-extrabold text-[1.9rem] leading-tight mb-4">
                    Be part of a<br>cleaner Zambo.
                </h2>
                <div class="w-10 h-1 bg-white/40 rounded-full mb-5"></div>
                <p class="text-white/65 text-xs leading-relaxed max-w-[220px]">
                    Register to receive real-time collection alerts, scan your waste with AI, and earn Eco-Points for your barangay.
                </p>

                {{-- Step indicators --}}
                <div class="flex flex-col gap-2.5 mt-8">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 border border-white/30 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">1</div>
                        <span class="text-white/70 text-[11px]">Create your account</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 border border-white/30 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">2</div>
                        <span class="text-white/70 text-[11px]">Select your barangay</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-white/20 border border-white/30 flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">3</div>
                        <span class="text-white/70 text-[11px]">Get notified before collection</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════ RIGHT PANEL ═══════════ --}}
        <div class="flex-1 flex flex-col justify-center px-10 py-10">

            {{-- Back --}}
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-green-700 transition-colors mb-6 self-start">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Back to Home
            </a>

            <h1 class="text-2xl font-bold text-slate-900 mb-1">Create Account</h1>
            <p class="text-sm text-slate-400 mb-7 leading-relaxed">Join the community to track and manage waste collection in your barangay.</p>

            @php
                $field = 'w-full pl-5 pr-4 py-3 bg-slate-50 border-0 rounded-lg text-sm text-slate-700 placeholder-slate-400 outline-none focus:ring-2 focus:ring-green-400/40 focus:bg-white transition-all';
                $fieldErr = 'ring-2 ring-red-300';
            @endphp

            <form method="POST" action="{{ route('auth.register') }}">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4">

                    {{-- Full Name --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Full Name</label>
                        <div class="relative">
                            <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg {{ $errors->has('full_name') ? 'bg-red-400' : 'bg-green-500' }}"></span>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required autofocus
                                   placeholder="Juan Dela Cruz"
                                   class="{{ $field }} {{ $errors->has('full_name') ? $fieldErr : '' }}">
                        </div>
                        @error('full_name') <p class="mt-1 text-xs text-red-500 font-medium pl-5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg {{ $errors->has('email') ? 'bg-red-400' : 'bg-green-500' }}"></span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="juan@example.com"
                                   class="{{ $field }} {{ $errors->has('email') ? $fieldErr : '' }}">
                        </div>
                        @error('email') <p class="mt-1 text-xs text-red-500 font-medium pl-5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Phone Number</label>
                        <div class="relative">
                            <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg {{ $errors->has('phone') ? 'bg-red-400' : 'bg-green-500' }}"></span>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   placeholder="0917 123 4567"
                                   class="{{ $field }} {{ $errors->has('phone') ? $fieldErr : '' }}">
                        </div>
                        @error('phone') <p class="mt-1 text-xs text-red-500 font-medium pl-5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Barangay --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Select Your Barangay</label>
                        <div class="relative">
                            <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg {{ $errors->has('barangay_id') ? 'bg-red-400' : 'bg-green-500' }}"></span>
                            <select name="barangay_id" required
                                    class="{{ $field }} pr-10 cursor-pointer {{ $errors->has('barangay_id') ? $fieldErr : '' }}">
                                <option value="" disabled {{ old('barangay_id') ? '' : 'selected' }}>Choose a barangay...</option>
                                @foreach($barangays as $barangay)
                                    <option value="{{ $barangay->id }}" {{ old('barangay_id') == $barangay->id ? 'selected' : '' }}>
                                        {{ $barangay->name }} (District {{ $barangay->district }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('barangay_id') <p class="mt-1 text-xs text-red-500 font-medium pl-5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Password</label>
                        <div class="relative">
                            <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg {{ $errors->has('password') ? 'bg-red-400' : 'bg-green-500' }}"></span>
                            <input type="password" name="password" required placeholder="Create a password"
                                   class="{{ $field }} {{ $errors->has('password') ? $fieldErr : '' }}">
                        </div>
                        @error('password') <p class="mt-1 text-xs text-red-500 font-medium pl-5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wide">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-green-500"></span>
                            <input type="password" name="password_confirmation" required placeholder="Confirm password"
                                   class="{{ $field }}">
                        </div>
                    </div>

                </div>

                <input type="hidden" name="role" value="user">

                {{-- Terms note --}}
                <p class="text-[11px] text-slate-400 mt-5 leading-relaxed">
                    By registering, you agree to follow <span class="font-semibold text-slate-500">City Ordinance No. 500</span> on proper waste segregation and collection schedules.
                </p>

                <button type="submit"
                        class="w-full mt-5 py-3.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-bold
                               tracking-wide shadow-[0_4px_14px_rgba(22,163,74,.35)]
                               hover:shadow-[0_6px_20px_rgba(22,163,74,.45)]
                               hover:-translate-y-0.5 transition-all duration-200">
                    CREATE ACCOUNT
                </button>
            </form>

            <p class="text-center text-xs text-slate-400 mt-5">
                Already have an account?
                <a href="{{ route('auth.login') }}" class="font-semibold text-green-600 hover:text-green-800 transition-colors">Sign in here</a>
            </p>

        </div>
    </div>

</body>
</html>