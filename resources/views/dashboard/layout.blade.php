<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · LimpioZambo</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        poppins: ['Poppins', 'sans-serif']
                    },
                    keyframes: {
                        slideUp: { from: { opacity: '0', transform: 'translateY(20px)' }, to: { opacity: '1', transform: 'none' } },
                    },
                    animation: {
                        slideUp: 'slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) both',
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
        
        /* Custom Scrollbar for sidebar and main content */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden antialiased font-poppins">

    {{-- ═══════════ SIDEBAR ═══════════ --}}
    <aside class="w-[280px] bg-slate-900 flex flex-col flex-shrink-0 shadow-2xl z-20 relative font-poppins">
        
        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/20 to-green-900/10 pointer-events-none"></div>

        <div class="relative h-[76px] flex items-center px-6 border-b border-white/10">
            <a href="/" class="flex items-center gap-2 text-xl font-extrabold text-white no-underline tracking-tight">
                <div class="w-8 h-8 bg-green-600 rounded-[10px] flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-600/30">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none">
                        <path d="M10 3L13 8.5H17.5L13.8 11.5L15.5 17L10 13.8L4.5 17L6.2 11.5L2.5 8.5H7L10 3Z" fill="white"/>
                    </svg>
                </div>
                Limpio<span class="text-green-500">Zambo</span>
            </a>
        </div>
        
        <div class="relative p-5 mx-4 mt-6 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm">
            <div class="text-sm font-bold text-white truncate mb-1">
                {{ Auth::user()->full_name ?? 'Guest User' }}
            </div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-green-500/20 border border-green-500/30 text-[10px] font-bold text-green-400 uppercase tracking-wide">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                {{ Auth::user()->role ?? 'Resident' }}
            </div>
        </div>

        <nav class="relative flex-1 overflow-y-auto mt-2 pb-6">
            <ul class="flex flex-col space-y-1">
                <div class="px-6 py-3 text-[10px] font-bold tracking-[0.1em] text-slate-500 uppercase">
                    Platform Tools
                </div>

                @if(Auth::check())
                    @if(strtolower(Auth::user()->role) === 'user' || strtolower(Auth::user()->role) === 'resident')
                        @include('dashboard.nav-user')
                    @elseif(strtolower(Auth::user()->role) === 'collector')
                        @include('dashboard.nav-collector')
                    @elseif(strtolower(Auth::user()->role) === 'barangay')
                        @include('dashboard.nav-barangay')
                    @elseif(strtolower(Auth::user()->role) === 'admin')
                        @include('dashboard.nav-admin')
                    @endif
                @else
                    @include('dashboard.nav-user')
                @endif
            </ul>
        </nav>
    </aside>

    {{-- ═══════════ MAIN CONTENT AREA ═══════════ --}}
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative bg-slate-50/50 font-poppins">
        
        <header class="h-[76px] bg-white border-b border-slate-200 flex items-center justify-end px-8 flex-shrink-0 z-10">
            <div class="flex items-center gap-6">
                <button class="relative text-slate-400 hover:text-slate-600 transition-colors">
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </button>
                
                <div class="h-8 w-px bg-slate-200"></div>

                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-600 flex items-center gap-2 transition-colors">
                        Sign Out
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8 animate-slideUp">
            <div class="max-w-6xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>