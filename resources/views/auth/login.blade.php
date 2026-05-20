<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · LimpioZambo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] },
                    keyframes: {
                        slideUp:  { from: { opacity: '0', transform: 'translateY(24px)' }, to: { opacity: '1', transform: 'none' } },
                        waveMove:{ '0%,100%': { transform: 'translateX(0)' }, '50%': { transform: 'translateX(-40px)' } },
                        floatDot:{ '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-12px)' } },
                    },
                    animation: {
                        slideUp:  'slideUp .65s cubic-bezier(.16,1,.3,1) both',
                        waveMove: 'waveMove 8s ease-in-out infinite',
                        waveMove2:'waveMove 10s ease-in-out infinite reverse',
                        floatDot: 'floatDot 4s ease-in-out infinite',
                        floatDot2:'floatDot 6s ease-in-out infinite 1s',
                        floatDot3:'floatDot 5s ease-in-out infinite 2s',
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
        .wave-svg { animation: waveMove 8s ease-in-out infinite; }
        .wave-svg-2 { animation: waveMove 10s ease-in-out infinite reverse; }
        @keyframes waveMove { 0%,100%{transform:translateX(0)} 50%{transform:translateX(-40px)} }
        @keyframes floatDot  { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }
        .dot-1{animation:floatDot 4s ease-in-out infinite}
        .dot-2{animation:floatDot 6s ease-in-out infinite 1s}
        .dot-3{animation:floatDot 5s ease-in-out infinite 2s}
        .dot-4{animation:floatDot 7s ease-in-out infinite .5s}
        /* grid lines */
        .grid-lines {
            background-image:
                linear-gradient(rgba(255,255,255,.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.08) 1px, transparent 1px);
            background-size: 48px 48px;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

    <div class="w-full max-w-[920px] bg-white rounded-3xl shadow-[0_24px_60px_rgba(0,0,0,.12)] overflow-hidden flex min-h-[580px] animate-slideUp">

        {{-- ═══════════ LEFT PANEL ═══════════ --}}
        <div class="hidden md:flex relative w-[45%] flex-shrink-0 flex-col justify-between overflow-hidden"
             style="background: linear-gradient(145deg, #16a34a 0%, #15803d 40%, #166534 100%);">

            {{-- Grid overlay --}}
            <div class="absolute inset-0 grid-lines"></div>

            {{-- Animated wave shapes --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <svg class="wave-svg absolute -bottom-6 -left-10 w-[130%] opacity-30" viewBox="0 0 600 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 100 C100 40, 200 160, 300 100 C400 40, 500 160, 600 100 L600 200 L0 200 Z" fill="white"/>
                </svg>
                <svg class="wave-svg-2 absolute -bottom-2 -left-10 w-[130%] opacity-20" viewBox="0 0 600 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 120 C80 60, 180 180, 300 120 C420 60, 520 180, 600 120 L600 200 L0 200 Z" fill="white"/>
                </svg>
                {{-- Top wave --}}
                <svg class="wave-svg absolute -top-6 -right-10 w-[130%] rotate-180 opacity-20" viewBox="0 0 600 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 100 C100 40, 200 160, 300 100 C400 40, 500 160, 600 100 L600 200 L0 200 Z" fill="white"/>
                </svg>
            </div>

            {{-- Floating dots --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="dot-1 absolute top-[18%] left-[20%] w-12 h-12 rounded-full border-2 border-white/30 bg-white/10"></div>
                <div class="dot-2 absolute top-[12%] right-[18%] w-6 h-6 rounded-full bg-white/20"></div>
                <div class="dot-3 absolute bottom-[28%] left-[14%] w-8 h-8 rounded-full border border-white/25 bg-white/10"></div>
                <div class="dot-4 absolute bottom-[18%] right-[22%] w-5 h-5 rounded-full bg-white/25"></div>
                <div class="dot-1 absolute top-[45%] right-[10%] w-10 h-10 rounded-full border border-white/20 bg-white/5"></div>
            </div>

            {{-- Content --}}
            <div class="relative z-10 p-10 pt-10">
                {{-- Logo --}}
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white/20 border border-white/30 rounded-lg flex items-center justify-center">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                            <path d="M10 3L13 8.5H17.5L13.8 11.5L15.5 17L10 13.8L4.5 17L6.2 11.5L2.5 8.5H7L10 3Z" fill="white"/>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-base tracking-tight">LimpioZambo</span>
                </div>
            </div>

            <div class="relative z-10 px-10 pb-14">
                <p class="text-white/70 text-xs font-medium uppercase tracking-widest mb-3">Welcome back</p>
                <h2 class="text-white font-extrabold text-[2.1rem] leading-tight mb-4">
                    Cleaner city<br>starts here.
                </h2>
                <div class="w-10 h-1 bg-white/40 rounded-full mb-5"></div>
                <p class="text-white/65 text-sm leading-relaxed max-w-[240px]">
                    Zamboanga City's smart waste collection platform — keeping every barangay informed and on schedule.
                </p>
            </div>
        </div>

        {{-- ═══════════ RIGHT PANEL ═══════════ --}}
        <div class="flex-1 flex flex-col justify-center px-10 py-12">

            {{-- Back --}}
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-green-700 transition-colors mb-8 self-start">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Back to Home
            </a>

            <h1 class="text-2xl font-bold text-slate-900 mb-1">Login Account</h1>
            <p class="text-sm text-slate-400 mb-8 leading-relaxed">Sign in to your account to access your barangay collection schedule and notifications.</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <div class="relative">
                        <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg {{ $errors->has('email') ? 'bg-red-400' : 'bg-green-500' }}"></span>
                        <input
                            type="email" id="email" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="Email ID"
                            class="w-full pl-5 pr-4 py-3.5 bg-slate-50 border-0 rounded-lg text-sm text-slate-700 placeholder-slate-400 outline-none focus:ring-2 focus:ring-green-400/40 focus:bg-white transition-all {{ $errors->has('email') ? 'ring-2 ring-red-300' : '' }}"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 font-medium pl-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="relative">
                        <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg {{ $errors->has('password') ? 'bg-red-400' : 'bg-green-500' }}"></span>
                        <input
                            type="password" id="password" name="password"
                            required autocomplete="current-password"
                            placeholder="Password"
                            class="w-full pl-5 pr-4 py-3.5 bg-slate-50 border-0 rounded-lg text-sm text-slate-700 placeholder-slate-400 outline-none focus:ring-2 focus:ring-green-400/40 focus:bg-white transition-all {{ $errors->has('password') ? 'ring-2 ring-red-300' : '' }}"
                        >
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 font-medium pl-5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-green-600 focus:ring-green-500 cursor-pointer accent-green-600">
                        <span class="text-xs text-slate-500 font-medium">Keep me signed in</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs font-semibold text-green-600 hover:text-green-800 transition-colors">
                            Already a member?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-bold
                                   tracking-wide shadow-[0_4px_14px_rgba(22,163,74,.35)]
                                   hover:shadow-[0_6px_20px_rgba(22,163,74,.45)]
                                   hover:-translate-y-0.5 transition-all duration-200">
                        SIGN IN
                    </button>
                </div>
            </form>

            <p class="text-center text-xs text-slate-400 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-800 transition-colors">Register here</a>
            </p>

        </div>
    </div>

</body>
</html>