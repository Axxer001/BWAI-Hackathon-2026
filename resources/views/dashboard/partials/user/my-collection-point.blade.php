@extends('dashboard.layout')

@section('title', 'My Collection Point')

@section('content')

    {{-- Page Header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-poppins text-2xl font-bold text-slate-900 mb-1">My Collection Point</h1>
        <p class="text-sm text-slate-500">View your assigned GPS drop-off location and track live collection status.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ── MAP CARD ── --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col animate-slideUp" style="animation-delay: 0.1s;">

            {{-- Map header --}}
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Camino Nuevo Drop-off (Zone 2)</h3>
                        <p class="text-xs text-slate-500">Zamboanga City, 7000</p>
                    </div>
                </div>
                <a href="https://www.openstreetmap.org/?mlat=6.912&mlon=122.075#map=15/6.912/122.075" target="_blank"
                   class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-100 hover:border-blue-200 px-3 py-1.5 rounded-lg transition-all">
                    Get Directions ↗
                </a>
            </div>

            {{-- Map embed --}}
            <div class="w-full flex-1 min-h-[400px] relative">
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0"
                    scrolling="no"
                    marginheight="0"
                    marginwidth="0"
                    src="https://www.openstreetmap.org/export/embed.html?bbox=121.9500%2C6.8500%2C122.1500%2C6.9500&layer=mapnik&marker=6.9214%2C122.0790"
                    class="absolute inset-0 w-full h-full border-0 block">
                </iframe>
            </div>
        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div class="lg:col-span-1 flex flex-col gap-5 animate-slideUp" style="animation-delay: 0.2s;">

            {{-- Live Route Status --}}
            <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden">
                <div class="absolute -bottom-8 -right-8 w-40 h-40 rounded-full bg-white/[.03]"></div>
                <div class="absolute -top-6 -left-6 w-28 h-28 rounded-full bg-white/[.03]"></div>

                <div class="relative z-10">

                    {{-- Live pill --}}
                    <div class="flex items-center gap-2 mb-5">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-400"></span>
                        </span>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Live Route Status</span>
                    </div>

                    {{-- Main stat --}}
                    <p class="text-slate-400 text-xs mb-1">Distance to your point</p>
                    <div class="flex items-end gap-2 mb-1">
                        <span class="text-5xl font-extrabold tracking-tight text-white leading-none">2</span>
                        <span class="text-blue-400 font-semibold text-base mb-1">stops away</span>
                    </div>
                    <p class="text-slate-400 text-sm mb-5">
                        Estimated arrival: <span class="text-white font-semibold">8:45 AM (~15 mins)</span>
                    </p>

                    <div class="w-full h-px bg-white/[.08] mb-5"></div>

                    {{-- Progress --}}
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-slate-400 text-[11px] font-semibold uppercase tracking-wider">Route Progress</p>
                        <p class="text-white text-[11px] font-bold">75%</p>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2 mb-4">
                        <div class="bg-blue-400 h-2 rounded-full" style="width: 75%"></div>
                    </div>

                    {{-- Stop segments --}}
                    <div class="flex items-center gap-1.5">
                        <span class="flex-1 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="flex-1 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="flex-1 h-1.5 rounded-full bg-blue-400"></span>
                        <span class="flex-1 h-1.5 rounded-full bg-white/20"></span>
                        <span class="flex-1 h-1.5 rounded-full bg-white/20"></span>
                    </div>
                    <div class="flex justify-between mt-1.5">
                        <p class="text-[10px] text-blue-400 font-medium">3 stops done</p>
                        <p class="text-[10px] text-slate-500 font-medium">2 remaining</p>
                    </div>

                </div>
            </div>

            {{-- Today's Requirements --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex-1 flex flex-col">
                <h3 class="text-sm font-bold text-slate-900 mb-5">Today's Requirements</h3>

                <div class="space-y-5 flex-1">

                    {{-- Scheduled window --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0 border border-green-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium mb-0.5">Scheduled Window</p>
                            <p class="text-sm font-bold text-slate-800">8:00 AM – 10:00 AM</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Place bins out 15 mins before start.</p>
                        </div>
                    </div>

                    <div class="w-full h-px bg-slate-100"></div>

                    {{-- Accepted waste --}}
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0 border border-slate-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium mb-2">Accepted Waste Today</p>
                            <div class="flex flex-wrap gap-1.5">
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-lg border border-green-200">Biodegradable</span>
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-lg border border-blue-200">Plastic</span>
                            </div>
                            <p class="text-[11px] text-red-500 mt-2.5 flex items-center gap-1 font-medium">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                No Residual Waste today
                            </p>
                        </div>
                    </div>

                </div>

                <button class="w-full mt-6 bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 hover:border-slate-300 font-semibold py-2.5 rounded-xl text-sm transition-all flex justify-center items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    View City Ordinance Guidelines
                </button>
            </div>

        </div>
    </div>

@endsection