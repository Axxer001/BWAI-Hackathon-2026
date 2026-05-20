@extends('dashboard.layout')

@section('title', 'Barangay Dashboard')

@section('content')

    {{-- Welcome header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Barangay Admin Panel</h1>
        <p class="text-sm text-slate-500 font-medium">Monitoring and scheduling dashboard for Barangay {{ auth()->user()->barangay->name ?? 'Zamboanga' }}.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-slideUp" style="animation-delay: 0.1s;">
        
        {{-- Active Trucks --}}
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/15 relative overflow-hidden">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full bg-white/[.08]"></div>
            <div class="relative z-10">
                <p class="text-white/70 text-xs font-bold uppercase tracking-wider mb-2">Active Fleet Trucks</p>
                <div class="flex items-end justify-between">
                    <span class="text-4xl font-extrabold leading-none">{{ $activeTrucksCount }}</span>
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/60 text-[10px] font-medium mt-4">Assigned to collector schedules</p>
            </div>
        </div>

        {{-- Points Collected Today --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg shadow-emerald-500/15 relative overflow-hidden">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full bg-white/[.08]"></div>
            <div class="relative z-10">
                <p class="text-white/70 text-xs font-bold uppercase tracking-wider mb-2">Points Awarded Today</p>
                <div class="flex items-end justify-between">
                    <span class="text-4xl font-extrabold leading-none">{{ $totalPointsToday }} <span class="text-sm font-semibold">pts</span></span>
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/60 text-[10px] font-medium mt-4">Earned from resident waste scans</p>
            </div>
        </div>

        {{-- Unresolved Reports --}}
        <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg shadow-rose-500/15 relative overflow-hidden">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full bg-white/[.08]"></div>
            <div class="relative z-10">
                <p class="text-white/70 text-xs font-bold uppercase tracking-wider mb-2">Unresolved Tickets</p>
                <div class="flex items-end justify-between">
                    <span class="text-4xl font-extrabold leading-none">{{ $unresolvedReportsCount }}</span>
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-white/60 text-[10px] font-medium mt-4">{{ $unresolvedMissedCount }} Missed Pickups · {{ $unresolvedViolationsCount }} Violations</p>
            </div>
        </div>

    </div>

    {{-- Main sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left & Mid: Operations --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            {{-- Quick Links Cards --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Operations Control Center</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    {{-- Scheduling --}}
                    <a href="{{ route('dashboard.schedules') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-amber-300 hover:bg-amber-50/20 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 mb-0.5">Scheduling & Calendar</p>
                            <p class="text-[11px] text-slate-400 font-medium">Set collection routines</p>
                        </div>
                    </a>

                    {{-- Fleet --}}
                    <a href="{{ route('dashboard.fleet') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-blue-300 hover:bg-blue-50/20 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 mb-0.5">Fleet & Collectors</p>
                            <p class="text-[11px] text-slate-400 font-medium">Manage trucks and collector shifts</p>
                        </div>
                    </a>

                    {{-- Map Manager --}}
                    <a href="{{ route('dashboard.points-manage') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-green-300 hover:bg-green-50/20 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 mb-0.5">Map Manager</p>
                            <p class="text-[11px] text-slate-400 font-medium">Add or adjust garbage zones</p>
                        </div>
                    </a>

                    {{-- Missed Pickups --}}
                    <a href="{{ route('dashboard.tickets-missed') }}" class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-rose-300 hover:bg-rose-50/20 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 mb-0.5">Resident Tickets</p>
                            <p class="text-[11px] text-slate-400 font-medium">Solve reported missed collections</p>
                        </div>
                    </a>

                </div>
            </div>

            {{-- Collection points list --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900">Garbage Point Capacities</h3>
                    <a href="{{ route('dashboard.points-manage') }}" class="text-xs text-blue-600 hover:underline font-bold">Manage Points</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Location</th>
                                <th class="px-6 py-3">Coordinates</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                            @forelse($collectionPoints as $cp)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $cp->name }}</td>
                                    <td class="px-6 py-4 text-xs font-mono">{{ round($cp->latitude, 4) }}, {{ round($cp->longitude, 4) }}</td>
                                    <td class="px-6 py-4">
                                        @if($cp->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-200">Active</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold border border-slate-200">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-xs font-semibold">No collection points defined yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Right: Activity & Live Feed --}}
        <div class="lg:col-span-1 flex flex-col gap-6">
            
            {{-- Scan Logs today --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900">Recent AI Scans</h3>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Live</span>
                </div>
                <div class="divide-y divide-slate-100 max-h-[360px] overflow-y-auto">
                    @forelse($recentScans as $scan)
                        <div class="p-4 hover:bg-slate-50 transition-colors flex gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center flex-shrink-0">
                                @if($scan->image_url)
                                    <img src="{{ $scan->image_url }}" class="w-full h-full object-cover rounded-lg" alt="">
                                @else
                                    📸
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 truncate mb-0.5">{{ $scan->ai_classification }}</p>
                                <p class="text-[10px] text-slate-400 truncate mb-1">by {{ $scan->user->full_name ?? 'Resident' }}</p>
                                <span class="text-[9px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded">{{ $scan->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-400 text-xs font-semibold">No waste scans recorded yet.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

@endsection
