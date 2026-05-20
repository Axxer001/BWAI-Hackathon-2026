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
                    fontFamily: { poppins: ['Poppins', 'sans-serif'] },
                    animation: {
                        'slideUp': 'slideUp 0.6s ease 0.2s both',
                    },
                    keyframes: {
                        slideUp: { from: { opacity: '0', transform: 'translateY(20px)' }, to: { opacity: '1', transform: 'none' } },
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', system-ui, sans-serif; }
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity .7s ease, transform .7s ease; }
        .reveal.active { opacity: 1; transform: none; }
        .reveal.delay-1 { transition-delay: .1s; }
        .reveal.delay-2 { transition-delay: .2s; }
        
        /* Flow connector line */
        .flow-line::before {
            content: ''; position: absolute; top: 40px; left: calc(12.5% + 20px); right: calc(12.5% + 20px);
            height: 2px; background: #bbf7d0; z-index: 0;
        }
        @media (max-width: 768px) { .flow-line::before { display: none; } }
    </style>
</head>
<body class="bg-white text-slate-700 antialiased overflow-x-hidden">

<header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6 h-[72px] flex items-center justify-between">
        <a href="#" class="flex items-center font-poppins text-xl font-extrabold text-slate-900 no-underline">
            <div class="w-8 h-8 bg-green-600 mr-2 rounded-[10px] flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-600/30">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                    <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                </svg>
            </div>
            Limpio<span class="text-green-600">Zambo</span>
        </a>

        <nav class="hidden md:flex items-center gap-8">
            <a href="#how-it-works" class="text-sm font-medium text-slate-500 hover:text-green-600 transition-colors">How it Works</a>
            <a href="#features" class="text-sm font-medium text-slate-500 hover:text-green-600 transition-colors">Features</a>
            <a href="#roles" class="text-sm font-medium text-slate-500 hover:text-green-600 transition-colors">Ecosystem</a>
        </nav>

        <div class="flex gap-3">
            <a href="{{ route('auth.login') }}" class="text-sm font-semibold text-slate-700 border border-slate-200 px-4 py-2 rounded-lg hover:border-green-500 hover:text-green-700 transition-all">Log In</a>
            <a href="{{ route('auth.register') }}" class="text-sm font-semibold text-white bg-green-600 px-4 py-2 rounded-lg hover:bg-green-700 hover:-translate-y-0.5 transition-all shadow-sm">Register</a>
        </div>
    </div>
</header>

{{-- HERO SECTION --}}
<section class="grid md:grid-cols-2 gap-16 items-center max-w-7xl mx-auto px-6 pt-32 pb-20">
    <div class="relative z-10 animate-slideUp">
        <h1 class="text-[clamp(2.6rem,4.5vw,4rem)] font-extrabold leading-[1.1] tracking-tight text-slate-900 mb-5">
            Garbage collection,<br><em class="not-italic text-green-600">finally connected.</em>
        </h1>
        <p class="text-lg text-slate-500 max-w-[480px] leading-[1.7] mb-9">
            LimpioZambo digitizes City Ordinance No. 500 — giving residents real-time collection alerts, drivers a live route tracker, and barangays full oversight.
        </p>
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('auth.register') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-base font-bold text-white bg-green-600 hover:bg-green-700 hover:-translate-y-0.5 shadow-md transition-all">
                Join Your Barangay →
            </a>
            <a href="#features" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl text-base font-bold text-slate-700 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-all">
                Explore Features
            </a>
        </div>
    </div>
    <div id="hero-slideshow" class="relative h-[500px] animate-slideUp rounded-3xl overflow-hidden shadow-2xl">
        
        <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-100 z-10">
            <img src="{{ asset('images/garbage_route_notif.png') }}" class="w-full h-full object-cover block" alt="Waste Collection" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
            <div class="absolute bottom-6 left-6 text-white">
                <span class="px-3 py-1 bg-green-500 text-[10px] font-bold uppercase tracking-wider rounded-full mb-2 inline-block shadow-sm">Live Active Route</span>
                <h3 class="text-xl font-bold tracking-tight">Camino Nuevo Collection</h3>
                <p class="text-sm text-white/80 mt-0.5">Zone 2 · Arriving in 5 mins</p>
            </div>
        </div>

        <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0 z-0">
            <img src="{{ asset('images/garbage_scan.png') }}" class="w-full h-full object-cover block" alt="AI Segregation" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
            <div class="absolute bottom-6 left-6 text-white">
                <span class="px-3 py-1 bg-purple-500 text-[10px] font-bold uppercase tracking-wider rounded-full mb-2 inline-block shadow-sm">AI Segregation</span>
                <h3 class="text-xl font-bold tracking-tight">Smart Waste Classification</h3>
                <p class="text-sm text-white/80 mt-0.5">Scan before you drop</p>
            </div>
        </div>

        <div class="slide absolute inset-0 transition-opacity duration-1000 opacity-0 z-0">
            <img src="{{ asset('images/gps.jpg') }}" class="w-full h-full object-cover block" alt="GPS Tracking" />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
            <div class="absolute bottom-6 left-6 text-white">
                <span class="px-3 py-1 bg-blue-500 text-[10px] font-bold uppercase tracking-wider rounded-full mb-2 inline-block shadow-sm">Verified Collection</span>
                <h3 class="text-xl font-bold tracking-tight">GPS Marked Pickups</h3>
                <p class="text-sm text-white/80 mt-0.5">100% City Ordinance Compliance</p>
            </div>
        </div>

    </div>
</section>

{{-- QUICK STATS --}}
<div class="border-y border-slate-100 bg-slate-50/50">
    <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-center reveal">
        <div>
            <div class="text-3xl font-extrabold text-green-600 mb-1">98</div>
            <div class="text-xs text-slate-500 font-medium uppercase tracking-wide">Barangays Supported</div>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-green-600 mb-1">Real-Time</div>
            <div class="text-xs text-slate-500 font-medium uppercase tracking-wide">GPS Truck Tracking</div>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-green-600 mb-1">AI</div>
            <div class="text-xs text-slate-500 font-medium uppercase tracking-wide">Waste Classification</div>
        </div>
        <div>
            <div class="text-3xl font-extrabold text-green-600 mb-1">100%</div>
            <div class="text-xs text-slate-500 font-medium uppercase tracking-wide">Ordinance Compliant</div>
        </div>
    </div>
</div>

{{-- HOW IT WORKS --}}
<section id="how-it-works" class="py-24 px-6">
    <div class="max-w-5xl mx-auto">
        <div class="reveal text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">How it flows</h2>
            <p class="text-slate-500 mt-3">A seamless cycle from the barangay hall to your front door.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative flow-line">
            <div class="text-center relative z-10 reveal">
                <div class="w-20 h-20 rounded-full bg-green-50 border-[3px] border-green-200 flex items-center justify-center text-2xl mx-auto mb-4">📅</div>
                <h3 class="font-bold text-slate-900 mb-2">1. Schedule Set</h3>
                <p class="text-sm text-slate-500">Barangays configure collection days and GPS drop-off points.</p>
            </div>
            <div class="text-center relative z-10 reveal delay-1">
                <div class="w-20 h-20 rounded-full bg-green-50 border-[3px] border-green-200 flex items-center justify-center text-2xl mx-auto mb-4">📱</div>
                <h3 class="font-bold text-slate-900 mb-2">2. AI Scanning</h3>
                <p class="text-sm text-slate-500">Residents scan their garbage to ensure proper segregation.</p>
            </div>
            <div class="text-center relative z-10 reveal delay-2">
                <div class="w-20 h-20 rounded-full bg-blue-50 border-[3px] border-blue-200 flex items-center justify-center text-2xl mx-auto mb-4">🚛</div>
                <h3 class="font-bold text-slate-900 mb-2">3. Active Route</h3>
                <p class="text-sm text-slate-500">Collectors start the run. Residents get alerted 15 mins prior.</p>
            </div>
            <div class="text-center relative z-10 reveal delay-3">
                <div class="w-20 h-20 rounded-full bg-purple-50 border-[3px] border-purple-200 flex items-center justify-center text-2xl mx-auto mb-4">📊</div>
                <h3 class="font-bold text-slate-900 mb-2">4. Analytics Logged</h3>
                <p class="text-sm text-slate-500">Completed points and delays are instantly sent to city dashboards.</p>
            </div>
        </div>
    </div>
</section>

{{-- CORE FEATURES --}}
<section id="features" class="py-24 px-6 bg-slate-900 text-white rounded-[40px] max-w-[96%] mx-auto my-10">
    <div class="max-w-6xl mx-auto">
        <div class="reveal mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-4">Everything the system was missing.</h2>
            <p class="text-white/60 text-lg">Four integrated tools to modernize Zamboanga's waste management.</p>
        </div>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 reveal hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center text-2xl mb-6">🔔</div>
                <h3 class="text-xl font-bold mb-3">Real-Time Alerts & Delays</h3>
                <p class="text-white/60 leading-relaxed">No more waiting outside guessing. Get SMS and email alerts when the truck is approaching your specific GPS zone, or if a truck breaks down and is delayed.</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 reveal delay-1 hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center text-2xl mb-6">🤖</div>
                <h3 class="text-xl font-bold mb-3">AI Waste Classifier</h3>
                <p class="text-white/60 leading-relaxed">Not sure which bin it goes in? Snap a photo in the app. Our AI instantly identifies the waste type and tells you if it's for today's collection schedule.</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 reveal hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center text-2xl mb-6">📍</div>
                <h3 class="text-xl font-bold mb-3">Live Fleet Tracking</h3>
                <p class="text-white/60 leading-relaxed">Collectors use a simplified interface to start their route. They snap photo proof at every checkpoint, creating an undeniable trail of completed pickups.</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-3xl p-8 reveal delay-1 hover:bg-white/10 transition-colors">
                <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center text-2xl mb-6">📈</div>
                <h3 class="text-xl font-bold mb-3">Centralized Dashboards</h3>
                <p class="text-white/60 leading-relaxed">Barangay officials and City Admin (OCENR) get a bird's-eye view of completion rates, reported violations, and eco-points distributed across the city.</p>
            </div>
        </div>
    </div>
</section>

{{-- ROLES / ECOSYSTEM --}}
<section id="roles" class="py-24 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="reveal text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Built for everyone</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 text-center reveal">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">🏠</div>
                <h3 class="font-bold text-slate-900">Residents</h3>
                <p class="text-sm text-slate-500 mt-2">Get alerts, scan waste, and earn Eco-Points.</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 text-center reveal delay-1">
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">🚛</div>
                <h3 class="font-bold text-slate-900">Collectors</h3>
                <p class="text-sm text-slate-500 mt-2">Log GPS checkpoints and report delays easily.</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 text-center reveal delay-2">
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">🏛️</div>
                <h3 class="font-bold text-slate-900">Barangays</h3>
                <p class="text-sm text-slate-500 mt-2">Manage schedules and monitor daily compliance.</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 text-center reveal delay-3">
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">🏙️</div>
                <h3 class="font-bold text-slate-900">City Admin</h3>
                <p class="text-sm text-slate-500 mt-2">City-wide analytics and ordinance oversight.</p>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-slate-900 border-t border-white/10 pt-16 pb-8 px-6 text-white/60">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <div class="font-poppins text-xl font-extrabold text-white mb-1">Limpio<span class="text-green-500">Zambo</span></div>
            <p class="text-sm">Smart Waste Management for Zamboanga City.</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="px-3 py-1 bg-white/10 rounded-full text-xs font-bold text-white/80">City Ordinance No. 500 Compliant</span>
            <span class="px-3 py-1 bg-green-500/20 border border-green-500/30 text-green-400 rounded-full text-xs font-bold">🏆 BWAI Hackathon 2026</span>
        </div>
    </div>
</footer>

<script>
    // Simple Scroll Reveal Observer
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // 3-Picture Fade Slideshow Logic
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('#hero-slideshow .slide');
        let currentSlide = 0;

        if (slides.length > 0) {
            setInterval(() => {
                // 1. Fade out the current slide and push it to the back
                slides[currentSlide].classList.remove('opacity-100', 'z-10');
                slides[currentSlide].classList.add('opacity-0', 'z-0');
                
                // 2. Move to the next slide (loops back to 0 after the 3rd picture)
                currentSlide = (currentSlide + 1) % slides.length;
                
                // 3. Bring the next slide to the front and fade it in
                slides[currentSlide].classList.remove('opacity-0', 'z-0');
                slides[currentSlide].classList.add('opacity-100', 'z-10');
            }, 4500); // Fades to the next picture every 4.5 seconds
        }
    });
</script>
</body>
</html>