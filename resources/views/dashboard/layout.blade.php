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
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden antialiased font-poppins relative">

    {{-- ═══════════ MOBILE OVERLAY ═══════════ --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden md:hidden opacity-0 transition-opacity duration-300"></div>

    {{-- ═══════════ SIDEBAR ═══════════ --}}
    <aside id="sidebar" class="w-[280px] bg-slate-900 flex flex-col flex-shrink-0 shadow-2xl z-50 fixed inset-y-0 left-0 md:relative transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out font-poppins">
        
        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/20 to-green-900/10 pointer-events-none"></div>

        <div class="relative h-[76px] flex items-center justify-between px-6 border-b border-white/10">
            <a href="/" class="flex items-center text-xl font-extrabold text-white no-underline tracking-tight">
                <div class="w-8 h-8 bg-green-600 mr-2 rounded-[10px] flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-600/30">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                    </svg>
                </div>
                Limpio<span class="text-green-500">Zambo</span>
            </a>
            
            <button id="close-sidebar" class="md:hidden text-white/70 hover:text-white transition-colors p-1">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
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
        
        <header class="h-[76px] bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 flex-shrink-0 z-10">
            
            <div class="flex items-center gap-4">
                <button id="open-sidebar" class="md:hidden p-2 -ml-2 text-slate-500 hover:text-green-600 transition-colors rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                @if(Auth::check() && strtolower(Auth::user()->role) === 'barangay')
                    <div class="flex flex-col justify-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none ">This is</span>
                        <span class="text-lg md:text-xl font-extrabold text-slate-900 leading-none">{{ Auth::user()->barangay->name ?? 'Barangay' }} Dashboard</span>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4 md:gap-6">
                <button class="relative text-slate-400 hover:text-slate-600 transition-colors">
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </button>
                
                <div class="h-8 w-px bg-slate-200"></div>

                <form id="logout-form" method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="button" id="logout-btn" class="text-sm font-semibold text-slate-500 hover:text-red-600 flex items-center gap-2 transition-colors">
                        <span class="hidden sm:inline">Sign Out</span>
                        <svg class="w-5 h-5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 animate-slideUp">
            <div class="max-w-6xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- ═══════════ LOGOUT CONFIRMATION MODAL ═══════════ --}}
    <div id="logout-modal" class="fixed inset-0 z-[100] hidden">
        <div id="logout-backdrop" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity duration-300 opacity-0 cursor-pointer"></div>
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0 pointer-events-none">
            <div id="logout-dialog" class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300 pointer-events-auto">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-extrabold text-slate-900">Sign out</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 font-medium">Are you sure you want to end your session? You will need to log back in to access the dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-slate-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 gap-3">
                    <button type="button" id="confirm-logout-btn" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm transition-colors">
                        Yes, sign me out
                    </button>
                    <button type="button" id="cancel-logout-btn" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-2 bg-white text-base font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:w-auto sm:text-sm transition-colors">
                        Cancel
                    </button>
                </div>
                
            </div>
        </div>
    </div>

    {{-- ═══════════ SCRIPT LOGIC ═══════════ --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Sidebar Logic ---
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openBtn = document.getElementById('open-sidebar');
            const closeBtn = document.getElementById('close-sidebar');

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            };

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            };

            openBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);

            // --- Logout Modal Logic ---
            const logoutBtn = document.getElementById('logout-btn');
            const logoutModal = document.getElementById('logout-modal');
            const logoutBackdrop = document.getElementById('logout-backdrop');
            const logoutDialog = document.getElementById('logout-dialog');
            const cancelLogoutBtn = document.getElementById('cancel-logout-btn');
            const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
            const logoutForm = document.getElementById('logout-form');

            const openLogoutModal = () => {
                logoutModal.classList.remove('hidden');
                // Trigger reflow to ensure animations play out
                void logoutModal.offsetWidth;
                
                logoutBackdrop.classList.remove('opacity-0');
                
                logoutDialog.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                logoutDialog.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            };

            const closeLogoutModal = () => {
                logoutBackdrop.classList.add('opacity-0');
                
                logoutDialog.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
                logoutDialog.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                
                setTimeout(() => {
                    logoutModal.classList.add('hidden');
                }, 300); // Matches the duration-300 class
            };

            logoutBtn.addEventListener('click', openLogoutModal);
            cancelLogoutBtn.addEventListener('click', closeLogoutModal);
            logoutBackdrop.addEventListener('click', closeLogoutModal); // Click outside to cancel

            // Actually submit the form when confirmed
            confirmLogoutBtn.addEventListener('click', () => {
                logoutForm.submit();
            });
        });
    </script>
</body>
</html>