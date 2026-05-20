<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LimpioZambo · Smart Waste Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        green: {
                            50:  '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0',
                            400: '#4ade80', 500: '#22c55e', 600: '#16a34a',
                            700: '#15803d', 800: '#166534',
                        },
                        blue: {
                            50:  '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e3a8a',
                        },
                        slate: {
                            50:  '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
                            300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b',
                            700: '#334155', 900: '#0f172a',
                        },
                    },
                    animation: {
                        'blink':      'blink 1.2s ease-in-out infinite',
                        'slideUp':    'slideUp 0.6s ease 1s both',
                        'scrollLeft': 'scrollLeft 35s linear infinite',
                        'float':      'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        blink:      { '0%,100%': { opacity: '1' }, '50%': { opacity: '.4' } },
                        slideUp:    { from: { opacity: '0', transform: 'translateY(12px)' }, to: { opacity: '1', transform: 'none' } },
                        scrollLeft: { from: { transform: 'translateX(0)' }, to: { transform: 'translateX(-50%)' } },
                        float:      { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-8px)' } },
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', system-ui, sans-serif; }

        /* Reveal animation */
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity .7s ease, transform .7s ease; }
        .reveal.delay-1 { transition-delay: .1s; }
        .reveal.delay-2 { transition-delay: .2s; }
        .reveal.delay-3 { transition-delay: .3s; }
        .reveal.delay-4 { transition-delay: .4s; }
        .reveal.active  { opacity: 1; transform: none; }

        /* Photo crossfade */
        #mainImg, #sideImg { transition: opacity .35s; }

        /* Gallery scroll */
        @keyframes scrollLeft {
            from { transform: translateX(0); }
            to   { transform: translateX(-50%); }
        }
        .gallery-track {
            display: flex;
            gap: 1.25rem;
            width: max-content;
            animation: scrollLeft 35s linear infinite;
        }
        .gallery-track:hover { animation-play-state: paused; }

        /* Prob card top bar */
        .prob-red::before   { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:#f87171; border-radius:3px 3px 0 0; }
        .prob-amber::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:#fbbf24; border-radius:3px 3px 0 0; }
        .prob-green::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:#22c55e; border-radius:3px 3px 0 0; }

        /* Features dark grid dividers */
        .feat-grid-wrap { background: rgba(255,255,255,0.08); }

        /* Flow connector line */
        .flow-line::before {
            content: '';
            position: absolute;
            top: 40px;
            left: calc(12.5% + 20px);
            right: calc(12.5% + 20px);
            height: 2px;
            background: #bbf7d0;
            z-index: 0;
        }

        /* Route dot pulse */
        .dot-active { animation: blink 1.2s ease-in-out infinite; }
        @keyframes blink { 0%,100% { opacity:1 } 50% { opacity:.4 } }

        /* ph-dot transition width */
        .ph-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,.5); cursor: pointer; transition: all .3s; }
        .ph-dot.active { background: white; width: 22px; border-radius: 100px; }
    </style>
</head>
<body class="bg-white text-slate-700 antialiased overflow-x-hidden font-poppins">

<header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 h-[72px] flex items-center justify-between">
        <a href="#" class="flex items-center gap-2 font-poppins text-xl font-extrabold text-slate-900 no-underline">
            <div class="w-9 h-9 bg-green-600 rounded-[10px] flex items-center justify-center flex-shrink-0">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M10 3L13 8.5H17.5L13.8 11.5L15.5 17L10 13.8L4.5 17L6.2 11.5L2.5 8.5H7L10 3Z" fill="white"/>
                </svg>
            </div>
            Limpio<span class="text-green-600">Zambo</span>
        </a>

        <nav class="hidden md:flex items-center gap-8">
            <a href="#problem"  class="text-sm font-medium text-slate-500 hover:text-green-600 transition-colors">Context</a>
            <a href="#features" class="text-sm font-medium text-slate-500 hover:text-green-600 transition-colors">Features</a>
            <a href="#ecosystem" class="text-sm font-medium text-slate-500 hover:text-green-600 transition-colors">Roles</a>
            <a href="#ai"       class="text-sm font-medium text-slate-500 hover:text-green-600 transition-colors">AI</a>
            <a href="#delay"    class="text-sm font-medium text-slate-500 hover:text-green-600 transition-colors">Delay Handling</a>
        </nav>

        <div class="flex gap-3">
            <a href="{{ route('auth.login') }}"
               class="text-sm font-semibold text-slate-700 border border-slate-200 px-4 py-2 rounded-lg hover:border-green-500 hover:text-green-700 transition-all">
                Log In
            </a>
            <a href="{{ route('auth.register') }}"
               class="text-sm font-semibold text-white bg-green-600 px-4 py-2 rounded-lg hover:bg-green-700 hover:-translate-y-0.5 transition-all">
                Register
            </a>
        </div>
    </div>
</header>

<section class="min-h-screen grid md:grid-cols-2 gap-16 items-center max-w-7xl mx-auto px-6 pt-28 pb-12">

    <div class="relative z-10 reveal">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-[11px] font-bold tracking-widest uppercase bg-green-50 text-green-700 border border-green-200 mb-6">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
            Zamboanga City, Philippines
        </div>

        <h1 class="font-poppins text-[clamp(2.6rem,4.5vw,4rem)] font-extrabold leading-[1.1] tracking-tight text-slate-900 mb-5">
            Garbage collection,<br>
            <em class="not-italic text-green-600">finally connected.</em>
        </h1>

        <p class="text-[1.05rem] text-slate-500 max-w-[480px] leading-[1.8] mb-9">
            LimpioZambo digitizes the city's existing solid waste ordinance — giving residents real-time notifications, collectors a live route tracker, and barangays full analytics over every collection session.
        </p>

        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('auth.register') }}"
            class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-base font-semibold text-white bg-green-600 hover:bg-green-700 hover:-translate-y-0.5 hover:shadow-[0_8px_20px_rgba(22,163,74,.25)] transition-all">
                Join Your Barangay →
            </a>
            <a href="#features"
               class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-base font-semibold text-slate-700 border border-slate-300 hover:border-green-500 hover:text-green-700 transition-all">
                Explore Features
            </a>
        </div>

        <div class="mt-8 flex items-center gap-3 text-xs text-slate-400 flex-wrap">
            <span class="flex items-center gap-1.5">✓ Aligned with City Ordinance No. 500</span>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <span class="flex items-center gap-1.5">✓ SMS &amp; Email alerts</span>
            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
            <span class="flex items-center gap-1.5">✓ AI waste scanner</span>
        </div>
    </div>

    <div class="relative h-[560px] reveal delay-2">

        <div class="absolute top-4 -left-3 z-20 bg-white border border-slate-200 rounded-xl px-4 py-2.5 shadow-lg flex items-start gap-2.5 max-w-[210px] animate-slideUp">
            <div class="w-7 h-7 rounded-lg bg-green-50 flex items-center justify-center text-sm flex-shrink-0">📍</div>
            <div class="text-[11px] leading-[1.4]">
                <strong class="block text-slate-900 text-[12px] mb-0.5">Truck arriving soon</strong>
                <span class="text-slate-500">GPS Point 3 — Camino Nuevo in ~5 min</span>
            </div>
        </div>

        <div class="absolute top-0 right-0 w-[72%] h-[75%] z-[3] rounded-2xl overflow-hidden shadow-[0_16px_40px_rgba(0,0,0,.12)] transition-all duration-[900ms]">
            <img id="mainImg" src="https://picsum.photos/seed/waste1/700/500" alt="Collection" class="w-full h-full object-cover block" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-5 text-white text-xs font-medium tracking-wide">
                <strong id="mainCaption" class="block text-[15px] font-bold mb-0.5">Barangay collection in progress</strong>
                <span id="mainSub">Zamboanga City · Zone 2</span>
            </div>
            <div id="photoDots" class="absolute top-4 right-4 z-10 flex gap-1.5"></div>
        </div>

        <div class="absolute bottom-0 left-0 w-[48%] h-[52%] z-[4] rounded-2xl overflow-hidden shadow-[0_16px_40px_rgba(0,0,0,.12)]">
            <img id="sideImg" src="https://picsum.photos/seed/side1/500/400" alt="Bins" class="w-full h-full object-cover block" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-4 text-white text-[11px] font-semibold">
                <strong id="sideCaption">Segregated bins ready</strong>
            </div>
        </div>

        <div class="absolute bottom-7 right-3 z-10 bg-white/95 backdrop-blur-sm border border-slate-200 rounded-2xl p-4 w-[200px] shadow-[0_8px_24px_rgba(0,0,0,.10)]">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Tuesday Route · Live</div>
            <div class="flex items-center gap-2.5 mb-2 text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500 flex-shrink-0"></span>
                <span class="flex-1 text-slate-700 font-medium text-xs">Sta. Maria</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-50 text-green-700">Done</span>
            </div>
            <div class="flex items-center gap-2.5 mb-2 text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500 flex-shrink-0"></span>
                <span class="flex-1 text-slate-700 font-medium text-xs">Tetuan</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-50 text-green-700">Done</span>
            </div>
            <div class="flex items-center gap-2.5 mb-2 text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 flex-shrink-0 dot-active"></span>
                <span class="flex-1 text-slate-700 font-medium text-xs">Camino Nuevo</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700">Now</span>
            </div>
            <div class="flex items-center gap-2.5 text-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-300 flex-shrink-0"></span>
                <span class="flex-1 text-slate-700 font-medium text-xs">Canelar</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-400">Next</span>
            </div>
        </div>
    </div>
</section>

<div class="border-t border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8 text-center reveal">
        <div>
            <div class="font-poppins text-[2.4rem] font-extrabold text-green-600 leading-none mb-1.5 stat-num" data-target="98" data-suffix="%">0</div>
            <div class="text-xs text-slate-500 font-medium">Barangays in Zamboanga City</div>
        </div>
        <div>
            <div class="font-poppins text-[2.4rem] font-extrabold text-green-600 leading-none mb-1.5 stat-num" data-target="58" data-suffix="%">0</div>
            <div class="text-xs text-slate-500 font-medium">Non-biodegradable waste share</div>
        </div>
        <div>
            <div class="font-poppins text-[2.4rem] font-extrabold text-green-600 leading-none mb-1.5 stat-num" data-target="30" data-suffix="T/day">0</div>
            <div class="text-xs text-slate-500 font-medium">Biodegradable processed daily</div>
        </div>
        <div>
            <div class="font-poppins text-[2.4rem] font-extrabold text-green-600 leading-none mb-1.5 stat-num" data-target="4" data-suffix=" roles">0</div>
            <div class="text-xs text-slate-500 font-medium">Coordinated in one platform</div>
        </div>
    </div>
</div>

<div class="overflow-hidden py-14 bg-slate-50 border-t border-b border-slate-200">
    <div id="galleryTrack" class="gallery-track"></div>
</div>

<section class="py-24 px-6" id="problem">
    <div class="max-w-5xl mx-auto">
        <div class="reveal">
            <p class="text-[11px] font-bold tracking-[.08em] uppercase text-green-600 mb-3">Why We Built This</p>
            <h2 class="font-poppins text-[clamp(1.8rem,3vw,2.6rem)] font-extrabold text-slate-900 leading-tight tracking-tight mb-4">
                Addressing real gaps in<br>Zamboanga's waste system
            </h2>
            <p class="text-base text-slate-500 max-w-xl leading-[1.75] mb-12">
                Zamboanga City already has City Ordinance No. 500 and EO KHYM 062-2025 mandating scheduled, segregated collection. LimpioZambo doesn't replace that — it makes it finally work.
            </p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="relative bg-white border border-slate-200 rounded-2xl p-7 overflow-hidden prob-red reveal delay-1">
                <div class="text-2xl mb-4">📋</div>
                <h3 class="text-sm font-bold text-slate-900 mb-2">Ordinance Defines the Schedule</h3>
                <p class="text-[13.5px] text-slate-500 leading-[1.65]">City Ordinance No. 500 already mandates time-and-type schedules per barangay. Residents must set out garbage 15 minutes before 8 AM — but no one gets notified in real time.</p>
                <span class="inline-flex items-center gap-1 mt-4 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-200">City Ordinance No. 500</span>
            </div>
            <div class="relative bg-white border border-slate-200 rounded-2xl p-7 overflow-hidden prob-amber reveal delay-2">
                <div class="text-2xl mb-4">🚛</div>
                <h3 class="text-sm font-bold text-slate-900 mb-2">Trucks Break Down, People Don't Know</h3>
                <p class="text-[13.5px] text-slate-500 leading-[1.65]">A documented, recurring issue in Zamboanga City: defective trucks delay or skip entire routes. Residents wait, garbage piles up, and violations get issued — even when residents did everything right.</p>
                <span class="inline-flex items-center gap-1 mt-4 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-red-50 text-red-700 border border-red-200">Documented failure mode</span>
            </div>
            <div class="relative bg-white border border-slate-200 rounded-2xl p-7 overflow-hidden prob-green reveal delay-3">
                <div class="text-2xl mb-4">✅</div>
                <h3 class="text-sm font-bold text-slate-900 mb-2">We Add an Intelligence Layer</h3>
                <p class="text-[13.5px] text-slate-500 leading-[1.65]">LimpioZambo sits on top of the existing fixed schedule — pushing real-time notifications, logging delays with auto-escalation, giving barangays live dashboards, and adding AI-assisted waste classification.</p>
                <span class="inline-flex items-center gap-1 mt-4 text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-200">Our innovation</span>
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-900 py-24 px-6 relative overflow-hidden" id="features">
    <div class="absolute inset-0 pointer-events-none"
         style="background: radial-gradient(ellipse at 20% 50%, rgba(34,197,94,.07) 0%, transparent 60%), radial-gradient(ellipse at 80% 50%, rgba(59,130,246,.05) 0%, transparent 60%)">
    </div>
    <div class="max-w-5xl mx-auto relative z-10">
        <div class="reveal">
            <p class="text-[11px] font-bold tracking-[.08em] uppercase text-green-400 mb-3">Core Features</p>
            <h2 class="font-poppins text-[clamp(1.8rem,3vw,2.6rem)] font-extrabold text-white leading-tight tracking-tight mb-4">
                Everything the city's system<br>was missing
            </h2>
            <p class="text-base text-white/50 max-w-xl leading-[1.75] mb-14">
                Six integrated capabilities that turn a static ordinance into a living, responsive system.
            </p>
        </div>

        <div class="reveal grid grid-cols-1 md:grid-cols-3 rounded-2xl overflow-hidden" style="background:rgba(255,255,255,.08); gap:1px;">
            <div class="bg-slate-900 p-10 hover:bg-white/[.04] transition-colors">
                <div class="font-poppins text-[2rem] font-extrabold text-white/[.08] leading-none mb-6">01</div>
                <div class="text-base font-bold text-white mb-2">Real-Time Notifications</div>
                <div class="text-[13.5px] text-white/50 leading-[1.65]">SMS and email alerts fire automatically when a truck approaches a GPS collection point — giving residents the required 15-minute heads-up.</div>
                <span class="inline-block mt-4 text-[11px] font-bold px-2 py-0.5 rounded-full bg-green-500/15 text-green-400">SMS · Email</span>
            </div>
            <div class="bg-slate-900 p-10 hover:bg-white/[.04] transition-colors">
                <div class="font-poppins text-[2rem] font-extrabold text-white/[.08] leading-none mb-6">02</div>
                <div class="text-base font-bold text-white mb-2">GPS Collection Points</div>
                <div class="text-[13.5px] text-white/50 leading-[1.65]">Households register to their nearest collection point. When the collector marks a point complete, the next point's residents are notified automatically.</div>
                <span class="inline-block mt-4 text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-500/15 text-blue-300">Live Map</span>
            </div>
            <div class="bg-slate-900 p-10 hover:bg-white/[.04] transition-colors">
                <div class="font-poppins text-[2rem] font-extrabold text-white/[.08] leading-none mb-6">03</div>
                <div class="text-base font-bold text-white mb-2">Collector Route Sessions</div>
                <div class="text-[13.5px] text-white/50 leading-[1.65]">Drivers start a session and follow the barangay-configured route. Each point is marked done with a timestamped GPS photo for accountability.</div>
                <span class="inline-block mt-4 text-[11px] font-bold px-2 py-0.5 rounded-full bg-blue-500/15 text-blue-300">Fleet Tracker</span>
            </div>
            <div class="bg-slate-900 p-10 hover:bg-white/[.04] transition-colors">
                <div class="font-poppins text-[2rem] font-extrabold text-white/[.08] leading-none mb-6">04</div>
                <div class="text-base font-bold text-white mb-2">Delay Escalation Engine</div>
                <div class="text-[13.5px] text-white/50 leading-[1.65]">When a truck doesn't reach a point within the scheduled window, the barangay officer is alerted and a rescheduling notice goes out to residents.</div>
                <span class="inline-block mt-4 text-[11px] font-bold px-2 py-0.5 rounded-full bg-purple-500/15 text-purple-300">Auto-escalation</span>
            </div>
            <div class="bg-slate-900 p-10 hover:bg-white/[.04] transition-colors">
                <div class="font-poppins text-[2rem] font-extrabold text-white/[.08] leading-none mb-6">05</div>
                <div class="text-base font-bold text-white mb-2">Barangay Analytics</div>
                <div class="text-[13.5px] text-white/50 leading-[1.65]">Completion rates, delay trends, waste type volumes, and collector performance — all in one dashboard, aligned to the city's MRF reporting requirements.</div>
                <span class="inline-block mt-4 text-[11px] font-bold px-2 py-0.5 rounded-full bg-green-500/15 text-green-400">Dashboard</span>
            </div>
            <div class="bg-slate-900 p-10 hover:bg-white/[.04] transition-colors">
                <div class="font-poppins text-[2rem] font-extrabold text-white/[.08] leading-none mb-6">06</div>
                <div class="text-base font-bold text-white mb-2">AI Waste Classifier</div>
                <div class="text-[13.5px] text-white/50 leading-[1.65]">Residents upload a photo of their garbage and get instant advice on proper segregation — plastic? biodegradable? hazardous? — before placing it at the collection point.</div>
                <span class="inline-block mt-4 text-[11px] font-bold px-2 py-0.5 rounded-full bg-purple-500/15 text-purple-300">Powered by AI</span>
            </div>
        </div>
    </div>
</section>

<section class="py-24 px-6 bg-green-50">
    <div class="max-w-5xl mx-auto">
        <div class="reveal text-center">
            <p class="text-[11px] font-bold tracking-[.08em] uppercase text-green-600 mb-3">The Flow</p>
            <h2 class="font-poppins text-[clamp(1.8rem,3vw,2.6rem)] font-extrabold text-slate-900 leading-tight tracking-tight">From schedule to collection</h2>
        </div>

        <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-6 relative flow-line">
            <div class="flow-step text-center relative z-10 reveal delay-1">
                <div class="w-20 h-20 rounded-full bg-green-50 border-[3px] border-green-200 flex items-center justify-center text-2xl mx-auto mb-5">📅</div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Barangay Sets Schedule</h3>
                <p class="text-xs text-slate-500 leading-[1.6]">Officials input the city-mandated collection schedule and configure GPS collection points per zone.</p>
            </div>
            <div class="flow-step text-center relative z-10 reveal delay-2">
                <div class="w-20 h-20 rounded-full bg-green-50 border-[3px] border-green-200 flex items-center justify-center text-2xl mx-auto mb-5">📍</div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Residents Register</h3>
                <p class="text-xs text-slate-500 leading-[1.6]">Households sign up to their nearest collection point and receive their waste type schedule.</p>
            </div>
            <div class="flow-step text-center relative z-10 reveal delay-3">
                <div class="w-20 h-20 rounded-full bg-blue-50 border-[3px] border-blue-200 flex items-center justify-center text-2xl mx-auto mb-5">🚛</div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Collector Starts Session</h3>
                <p class="text-xs text-slate-500 leading-[1.6]">The driver activates a route. Residents at the first point are notified. The system tracks progress in real time.</p>
            </div>
            <div class="flow-step text-center relative z-10 reveal delay-4">
                <div class="w-20 h-20 rounded-full bg-blue-50 border-[3px] border-blue-200 flex items-center justify-center text-2xl mx-auto mb-5">✔️</div>
                <h3 class="text-sm font-bold text-slate-900 mb-1.5">Points Logged &amp; Chained</h3>
                <p class="text-xs text-slate-500 leading-[1.6]">Each completed point triggers a notification to the next. Dashboards update instantly with collection data.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-24 px-6" id="ai">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-20 items-center">
        <div class="reveal">
            <div class="inline-flex items-center gap-2 text-[11px] font-bold tracking-widest uppercase text-purple-700 bg-purple-50 border border-purple-200 rounded-full px-4 py-1.5 mb-5">✦ AI-Powered</div>
            <h2 class="font-poppins text-[clamp(1.8rem,3vw,2.6rem)] font-extrabold text-slate-900 leading-tight tracking-tight mb-4">
                Snap a photo.<br>Know where it goes.
            </h2>
            <p class="text-base text-slate-500 leading-[1.75] mb-2">
                Before putting garbage out at the collection point, residents capture an image and ask the AI exactly how to prepare and segregate it — aligned to the city's color-coded bin system.
            </p>
            <div class="flex flex-col gap-5 mt-8">
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-green-50 border-2 border-green-200 text-green-700 text-xs font-extrabold flex items-center justify-center flex-shrink-0 mt-0.5">1</div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Capture a photo of your waste</h4>
                        <p class="text-xs text-slate-500 leading-[1.6]">Tap the scanner in the app, point your camera, and upload. Works with plastic, cardboard, food waste, hazardous items, and more.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-green-50 border-2 border-green-200 text-green-700 text-xs font-extrabold flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">AI classifies and advises</h4>
                        <p class="text-xs text-slate-500 leading-[1.6]">The model returns waste type, the correct bin color, today's scheduled collection day, and any prep steps (rinse, flatten, separate).</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-full bg-green-50 border-2 border-green-200 text-green-700 text-xs font-extrabold flex items-center justify-center flex-shrink-0 mt-0.5">3</div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Earn Eco-Points for compliance</h4>
                        <p class="text-xs text-slate-500 leading-[1.6]">Each verified scan adds points to your account. Points feed into barangay-level compliance reports sent to OCENR.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="reveal delay-2 rounded-2xl overflow-hidden bg-slate-900 border border-white/[.08]">
            <div class="flex items-center gap-3 px-5 py-3.5 bg-white/[.05] border-b border-white/[.07]">
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <span class="text-xs text-white/60 font-medium">LimpioZambo AI Assistant · Online</span>
            </div>
            <div class="p-5 flex flex-col gap-4">
                <div class="self-end flex items-center gap-2 bg-white/[.06] border border-white/[.08] rounded-xl px-3.5 py-2 text-[11px] text-white/50">
                    📷 &nbsp;plastic_bottle.jpg &nbsp;·&nbsp; uploading…
                </div>
                <div class="self-end max-w-[88%] bg-blue-600 text-white/90 text-xs leading-[1.55] px-4 py-3 rounded-xl rounded-br-[4px]">
                    What bin does this go in and when is it collected?
                </div>
                <div class="self-start max-w-[88%] bg-white/[.07] text-white/80 text-xs leading-[1.55] px-4 py-3 rounded-xl rounded-bl-[4px] border border-white/[.08]">
                    <strong class="text-green-400">Plastic bottle — recyclable.</strong><br><br>
                    Place it in the <strong class="text-green-400">blue bin</strong> (non-biodegradable). Your next scheduled plastic collection is <strong class="text-green-400">Monday and Wednesday</strong>, per EO KHYM 062-2025.<br><br>
                    Tip: rinse the bottle and remove the cap — caps go in a separate recyclables bag. You'll earn <strong class="text-green-400">+5 Eco-Points</strong> once the collector scans this point. ♻️
                </div>
                <div class="self-end max-w-[88%] bg-blue-600 text-white/90 text-xs leading-[1.55] px-4 py-3 rounded-xl rounded-br-[4px]">
                    What about the greasy pizza box?
                </div>
                <div class="self-start max-w-[88%] bg-white/[.07] text-white/80 text-xs leading-[1.55] px-4 py-3 rounded-xl rounded-bl-[4px] border border-white/[.08]">
                    Greasy cardboard is <strong class="text-green-400">not recyclable</strong> — the oil contaminates the paper stream. Tear off any clean portions for the blue bin, and place the oily parts in the <strong class="text-green-400">grey residual bin</strong>. Collected daily 6–8 PM.
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-24 px-6 bg-slate-50 border-t border-slate-200" id="ecosystem">
    <div class="max-w-5xl mx-auto">
        <div class="reveal">
            <p class="text-[11px] font-bold tracking-[.08em] uppercase text-green-600 mb-3">The Ecosystem</p>
            <h2 class="font-poppins text-[clamp(1.8rem,3vw,2.6rem)] font-extrabold text-slate-900 leading-tight tracking-tight mb-4">Four roles. One system.</h2>
            <p class="text-base text-slate-500 max-w-xl leading-[1.75] mb-14">Every stakeholder in Zamboanga City's waste chain has a tailored experience — from the household to city hall.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 reveal delay-1">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-2xl mb-5">🏠</div>
                <div class="text-[11px] font-extrabold tracking-[.07em] uppercase text-green-700 mb-1">Resident</div>
                <h3 class="text-sm font-bold text-slate-900 mb-4">Household User</h3>
                <ul class="flex flex-col gap-1.5">
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-green-600 font-bold flex-shrink-0">›</span>Register to a GPS collection point</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-green-600 font-bold flex-shrink-0">›</span>Get notified 15 min before truck arrives</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-green-600 font-bold flex-shrink-0">›</span>Scan waste with AI for guidance</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-green-600 font-bold flex-shrink-0">›</span>Report uncollected garbage</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1"><span class="text-green-600 font-bold flex-shrink-0">›</span>Earn Eco-Points for compliance</li>
                </ul>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 reveal delay-2">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-2xl mb-5">🚛</div>
                <div class="text-[11px] font-extrabold tracking-[.07em] uppercase text-blue-700 mb-1">Collector</div>
                <h3 class="text-sm font-bold text-slate-900 mb-4">Truck Driver / Crew</h3>
                <ul class="flex flex-col gap-1.5">
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-blue-600 font-bold flex-shrink-0">›</span>Start and end a route session</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-blue-600 font-bold flex-shrink-0">›</span>Mark each GPS point as collected</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-blue-600 font-bold flex-shrink-0">›</span>Upload GPS photo proof per stop</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-blue-600 font-bold flex-shrink-0">›</span>Log "Truck Full" to notify dispatch</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1"><span class="text-blue-600 font-bold flex-shrink-0">›</span>Report delays with reason codes</li>
                </ul>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 reveal delay-3">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-2xl mb-5">🏛️</div>
                <div class="text-[11px] font-extrabold tracking-[.07em] uppercase text-amber-800 mb-1">Barangay</div>
                <h3 class="text-sm font-bold text-slate-900 mb-4">Local Government Unit</h3>
                <ul class="flex flex-col gap-1.5">
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-amber-700 font-bold flex-shrink-0">›</span>Set and manage collection schedules</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-amber-700 font-bold flex-shrink-0">›</span>Assign collectors and GPS points</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-amber-700 font-bold flex-shrink-0">›</span>Monitor live route sessions</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-amber-700 font-bold flex-shrink-0">›</span>View analytics dashboard</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1"><span class="text-amber-700 font-bold flex-shrink-0">›</span>Handle delay escalation alerts</li>
                </ul>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6 reveal delay-4">
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-2xl mb-5">🏙️</div>
                <div class="text-[11px] font-extrabold tracking-[.07em] uppercase text-purple-800 mb-1">City Admin</div>
                <h3 class="text-sm font-bold text-slate-900 mb-4">OCENR / City Central</h3>
                <ul class="flex flex-col gap-1.5">
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-purple-700 font-bold flex-shrink-0">›</span>Manage all barangay accounts</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-purple-700 font-bold flex-shrink-0">›</span>City-wide analytics and reports</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-purple-700 font-bold flex-shrink-0">›</span>Configure ordinance parameters</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1 border-b border-black/[.05]"><span class="text-purple-700 font-bold flex-shrink-0">›</span>Oversee MRF data pipeline</li>
                    <li class="text-xs text-slate-700 flex gap-1.5 items-start py-1"><span class="text-purple-700 font-bold flex-shrink-0">›</span>Audit logs and compliance records</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-24 px-6 border-t border-slate-200" id="delay">
    <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-20 items-center">
        <div class="reveal bg-white border border-slate-200 rounded-[18px] overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,.06)]">
            <div class="flex gap-3 items-start bg-amber-50 border-b border-amber-200 px-5 py-4">
                <span class="text-[11px] font-extrabold px-2.5 py-0.5 rounded-full bg-amber-200 text-amber-900 flex-shrink-0 mt-0.5">⚠ Delay</span>
                <div>
                    <h4 class="text-sm font-bold text-amber-900">Truck not yet at GPS Point 4 — Canelar</h4>
                    <p class="text-xs text-amber-700 mt-0.5">Scheduled: 8:00 AM · Now: 8:47 AM · Delay: 47 minutes</p>
                </div>
            </div>
            <div class="p-5 flex flex-col gap-3">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex gap-3 items-start">
                    <div class="text-base flex-shrink-0 mt-0.5">📲</div>
                    <div>
                        <h4 class="text-[13px] font-bold text-slate-900 mb-0.5">Residents at Point 4 notified</h4>
                        <p class="text-xs text-slate-500 leading-[1.5]">SMS sent: "Collection delayed. Estimated arrival: 9:15 AM. No need to wait outside — we'll alert you again."</p>
                    </div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex gap-3 items-start">
                    <div class="text-base flex-shrink-0 mt-0.5">🏛️</div>
                    <div>
                        <h4 class="text-[13px] font-bold text-slate-900 mb-0.5">Barangay officer escalated</h4>
                        <p class="text-xs text-slate-500 leading-[1.5]">Dashboard flagged. Officer can confirm rescheduled time or dispatch a replacement unit.</p>
                    </div>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex gap-3 items-start">
                    <div class="text-base flex-shrink-0 mt-0.5">📊</div>
                    <div>
                        <h4 class="text-[13px] font-bold text-slate-900 mb-0.5">Delay logged to analytics</h4>
                        <p class="text-xs text-slate-500 leading-[1.5]">Event recorded with reason code, driver ID, and weather tag. Feeds into the monthly performance report.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="reveal delay-2">
            <p class="text-[11px] font-bold tracking-[.08em] uppercase text-green-600 mb-3">Delay Handling</p>
            <h3 class="font-poppins text-[1.6rem] font-extrabold text-slate-900 leading-tight tracking-tight mb-4">
                When trucks can't follow the schedule, we handle it gracefully.
            </h3>
            <p class="text-sm text-slate-500 leading-[1.75] mb-5">
                Defective trucks are a documented, recurring challenge in Zamboanga City. Rather than leaving residents guessing, LimpioZambo detects delays automatically and takes action.
            </p>
            <ul class="flex flex-col gap-4">
                <li class="flex gap-4 items-start text-sm text-slate-700">
                    <span class="min-w-[26px] h-[26px] rounded-full bg-green-50 border border-green-200 text-green-700 text-[11px] font-extrabold flex items-center justify-center flex-shrink-0">1</span>
                    If a truck hasn't reached a point within the grace period, the system flags the delay.
                </li>
                <li class="flex gap-4 items-start text-sm text-slate-700">
                    <span class="min-w-[26px] h-[26px] rounded-full bg-green-50 border border-green-200 text-green-700 text-[11px] font-extrabold flex items-center justify-center flex-shrink-0">2</span>
                    Residents receive an updated ETA via SMS — no need to stand outside waiting.
                </li>
                <li class="flex gap-4 items-start text-sm text-slate-700">
                    <span class="min-w-[26px] h-[26px] rounded-full bg-green-50 border border-green-200 text-green-700 text-[11px] font-extrabold flex items-center justify-center flex-shrink-0">3</span>
                    The barangay officer is notified and can confirm a new time or request a replacement.
                </li>
                <li class="flex gap-4 items-start text-sm text-slate-700">
                    <span class="min-w-[26px] h-[26px] rounded-full bg-green-50 border border-green-200 text-green-700 text-[11px] font-extrabold flex items-center justify-center flex-shrink-0">4</span>
                    Every delay is logged with a reason code and timestamp — feeding into monthly city compliance reports.
                </li>
            </ul>
        </div>
    </div>
</section>

<div class="bg-blue-800 py-16 px-6">
    <div class="max-w-5xl mx-auto flex gap-10 items-start">
        <div class="w-16 h-16 flex-shrink-0 bg-white/10 border border-white/15 rounded-2xl flex items-center justify-center text-3xl">📜</div>
        <div>
            <h3 class="font-poppins text-lg font-extrabold text-white mb-2">Built on Zamboanga City's Legal Framework</h3>
            <p class="text-sm text-white/65 leading-[1.7] mb-4">LimpioZambo is designed in full compliance with the city's existing solid waste management laws. We digitize and enforce what's already mandated — we don't reinvent it.</p>
            <div class="flex flex-wrap gap-2">
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-white/10 text-white/85 border border-white/15">City Ordinance No. 500 — Sanitary Code</span>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-white/10 text-white/85 border border-white/15">EO KHYM 062-2025 — Segregation at Source</span>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-white/10 text-white/85 border border-white/15">R.A. 9003 — Ecological Solid Waste Act</span>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-white/10 text-white/85 border border-white/15">OCENR Solid Waste Management Division</span>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-white/10 text-white/85 border border-white/15">Barangay-level collection under 2016 Ordinance</span>
            </div>
        </div>
    </div>
</div>

<footer class="bg-slate-900 text-white/60 pt-16 pb-8 px-6">
    <div class="max-w-5xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 mb-12">
            <div class="col-span-2 md:col-span-1">
                <div class="font-poppins text-xl font-extrabold text-white mb-2">Limpio<span class="text-green-400">Zambo</span></div>
                <p class="text-xs text-white/40 leading-[1.65]">Empowering Zamboanga City with smart, data-driven waste management — aligned with city ordinances and built for real operational conditions.</p>
            </div>
            <div>
                <h4 class="text-[11px] font-bold uppercase tracking-widest text-white/40 mb-4">Residents</h4>
                <ul class="flex flex-col gap-2.5">
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">AI Waste Scanner</a></li>
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">Collection Schedule</a></li>
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">Report an Issue</a></li>
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">Eco-Points</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-[11px] font-bold uppercase tracking-widest text-white/40 mb-4">Collectors</h4>
                <ul class="flex flex-col gap-2.5">
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">Route Sessions</a></li>
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">GPS Marking</a></li>
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">Delay Logging</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-[11px] font-bold uppercase tracking-widest text-white/40 mb-4">Admin</h4>
                <ul class="flex flex-col gap-2.5">
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">Barangay Dashboard</a></li>
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">Fleet Management</a></li>
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">City Analytics</a></li>
                    <li><a href="#" class="text-sm text-white/50 hover:text-green-400 transition-colors">Ordinance Config</a></li>
                </ul>
            </div>
        </div>
        <div class="pt-6 border-t border-white/[.08] flex justify-between items-center flex-wrap gap-4 text-xs">
            <span>&copy; <script>document.write(new Date().getFullYear())</script> LimpioZambo · Zamboanga City</span>
            <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/15 text-green-400 border border-green-500/25 font-bold">🏆 Hackathon Submission 2026</span>
        </div>
    </div>
</footer>

<script>
// Sticky header
window.addEventListener('scroll', () => {
    const h = document.getElementById('header');
    if (window.scrollY > 50) {
        h.classList.add('bg-white/95', 'backdrop-blur-md', 'border-b', 'border-slate-200', 'shadow-sm');
    } else {
        h.classList.remove('bg-white/95', 'backdrop-blur-md', 'border-b', 'border-slate-200', 'shadow-sm');
    }
});

// Scroll reveal
const revealObs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

// Animated counters
const statEls = document.querySelectorAll('.stat-num');
let counted = false;
const countObs = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting && !counted) {
        counted = true;
        statEls.forEach(el => {
            const target = +el.dataset.target, suffix = el.dataset.suffix || '';
            const step = target / (1800 / 16);
            let cur = 0;
            const tick = () => {
                cur += step;
                if (cur < target) { el.textContent = Math.ceil(cur) + suffix; requestAnimationFrame(tick); }
                else el.textContent = target + suffix;
            };
            tick();
        });
    }
}, { threshold: 0.5 });
const statsEl = document.querySelector('.stat-num');
if (statsEl) countObs.observe(statsEl.closest('div').parentElement);

// Hero photo slideshow
const photos = [
    { main: { src: 'https://picsum.photos/seed/waste1/700/500', cap: 'Barangay collection in progress', sub: 'Zamboanga City · Zone 2' },
      side: { src: 'https://picsum.photos/seed/side1/500/400', cap: 'Segregated bins ready' } },
    { main: { src: 'https://picsum.photos/seed/waste2/700/500', cap: 'Collector marking GPS point', sub: 'Route Session Active' },
      side: { src: 'https://picsum.photos/seed/side2/500/400', cap: 'Community waste drive' } },
    { main: { src: 'https://picsum.photos/seed/waste3/700/500', cap: 'Color-coded waste segregation', sub: 'City Ordinance No. 500 compliant' },
      side: { src: 'https://picsum.photos/seed/side3/500/400', cap: 'MRF facility processing' } },
    { main: { src: 'https://picsum.photos/seed/waste4/700/500', cap: 'Resident app notification', sub: 'SMS alert before collection' },
      side: { src: 'https://picsum.photos/seed/side4/500/400', cap: 'Green community initiative' } },
];
let photoIdx = 0;
const mainImg = document.getElementById('mainImg');
const sideImg = document.getElementById('sideImg');
const mainCap = document.getElementById('mainCaption');
const mainSub = document.getElementById('mainSub');
const sideCap = document.getElementById('sideCaption');
const dotsEl  = document.getElementById('photoDots');

photos.forEach((_, i) => {
    const d = document.createElement('div');
    d.className = 'ph-dot' + (i === 0 ? ' active' : '');
    d.addEventListener('click', () => goToPhoto(i));
    dotsEl.appendChild(d);
});

function goToPhoto(idx) {
    photoIdx = idx;
    const p = photos[idx];
    mainImg.style.opacity = '0';
    sideImg.style.opacity = '0';
    setTimeout(() => {
        mainImg.src = p.main.src; mainImg.style.opacity = '1';
        sideImg.src = p.side.src; sideImg.style.opacity = '1';
        mainCap.textContent = p.main.cap;
        mainSub.textContent = p.main.sub || '';
        sideCap.textContent = p.side.cap;
        document.querySelectorAll('.ph-dot').forEach((d, i) => d.classList.toggle('active', i === idx));
    }, 300);
}
setInterval(() => goToPhoto((photoIdx + 1) % photos.length), 4000);

// Gallery strip
const galleryPhotos = [
    { src: 'https://picsum.photos/seed/g1/400/260', label: 'Collection in progress' },
    { src: 'https://picsum.photos/seed/g2/400/260', label: 'GPS point logged' },
    { src: 'https://picsum.photos/seed/g3/400/260', label: 'Segregated bins' },
    { src: 'https://picsum.photos/seed/g4/400/260', label: 'MRF processing' },
    { src: 'https://picsum.photos/seed/g5/400/260', label: 'Green community' },
    { src: 'https://picsum.photos/seed/g6/400/260', label: 'Color-coded waste' },
    { src: 'https://picsum.photos/seed/g7/400/260', label: 'Community drive' },
    { src: 'https://picsum.photos/seed/g8/400/260', label: 'App notification' },
];
const track = document.getElementById('galleryTrack');
[...galleryPhotos, ...galleryPhotos].forEach(p => {
    const div = document.createElement('div');
    div.className = 'w-[260px] h-[170px] rounded-2xl overflow-hidden flex-shrink-0 relative';
    div.innerHTML = `
        <img src="${p.src}" alt="${p.label}" loading="lazy" class="w-full h-full object-cover block hover:scale-105 transition-transform duration-300">
        <div class="absolute bottom-0 left-0 right-0 px-3.5 py-2.5 bg-gradient-to-t from-black/55 to-transparent text-white text-[11px] font-semibold">${p.label}</div>
    `;
    track.appendChild(div);
});
</script>
</body>
</html>