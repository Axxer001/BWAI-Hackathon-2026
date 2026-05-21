@extends('dashboard.layout')

@section('title', 'Start Active Shift')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Active Shift Manager</h1>
        <p class="text-sm text-slate-500">Initialize your physical run session to stream real-time tracking data out to your assigned barangay zone.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-semibold flex items-center gap-2 max-w-2xl animate-slideUp">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl bg-white rounded-2xl border border-slate-200 shadow-sm p-8 animate-slideUp" style="animation-delay: 0.1s;">

        @if($activeSession)
            {{-- ── SHIFT IS RUNNING ── --}}
            <div class="flex items-center gap-4 mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-emerald-900">Shift is Currently Active</h3>
                    <p class="text-xs text-emerald-700 font-medium">
                        Started at {{ $activeSession->started_at ? \Carbon\Carbon::parse($activeSession->started_at)->format('h:i A') : now()->format('h:i A') }}
                    </p>
                </div>
            </div>

        @else
            {{-- ── PRE-START INFO BANNER ── --}}
            <div class="flex items-center gap-4 mb-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-900">Vehicle Check</h3>
                    <p class="text-xs text-blue-700 font-medium">Ensure your mobile GPS device is securely mounted to your dashboard configuration before hitting start.</p>
                </div>
            </div>
        @endif

        {{-- ── SCHEDULE DETAILS CARD (shown in both states) ── --}}
        @if($todaySchedule)
            <div class="bg-slate-50 rounded-xl border border-slate-100 p-5 mb-6 space-y-3">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest pb-1">Today's Route Details</p>

                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Route Boundary</span>
                    <span class="text-sm font-bold text-slate-800">{{ auth()->user()->barangay->name ?? 'Zone' }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Scheduled Time</span>
                    <span class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($todaySchedule->collection_time)->format('h:i A') }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Frequency</span>
                    <span class="text-sm font-bold text-slate-800">{{ ucfirst(str_replace('-', ' ', $todaySchedule->frequency)) }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Assigned Truck</span>
                    <span class="text-sm font-bold text-slate-800">
                        {{ $todaySchedule->truck->plate_number ?? 'Unassigned' }}
                        @if($todaySchedule->truck)
                            <span class="text-xs text-slate-400 font-normal ml-1">({{ $todaySchedule->truck->capacity_tons }}t)</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Collector</span>
                    <span class="text-sm font-bold text-slate-800">{{ $todaySchedule->collector->full_name ?? 'Unassigned' }}</span>
                </div>
                <div class="flex justify-between items-start pt-0.5">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Checkpoints</span>
                    <div class="flex flex-wrap gap-1.5 justify-end max-w-xs">
                        @forelse($todaySchedule->collectionPoints as $point)
                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold rounded-lg">
                                {{ $point->name }}
                            </span>
                        @empty
                            <span class="text-sm font-bold text-slate-500 italic">No checkpoints</span>
                        @endforelse
                    </div>
                </div>
            </div>
        @else
            <div class="p-4 mb-6 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm font-semibold flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                No schedule assigned for today. Please contact your administrator.
            </div>
        @endif

        {{-- ── ACTIONS ── --}}
        @if($activeSession)
            <div class="space-y-4">
                <a href="{{ route('dashboard.route-map') }}"
                    class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-center tracking-wide shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    GO TO ASSIGNED ROUTE MAP
                </a>
                <form action="{{ route('dashboard.complete-route', $activeSession->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold tracking-wide shadow-lg shadow-rose-600/20 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        END ACTIVE SHIFT
                    </button>
                </form>
            </div>
        @else
            <form action="{{ route('dashboard.start-session') }}" method="POST">
                @csrf
                <button type="submit" @unless($todaySchedule) disabled @endunless
                    class="w-full py-4 rounded-xl font-bold tracking-wide transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2
                        {{ $todaySchedule
                            ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/30'
                            : 'bg-slate-200 text-slate-400 cursor-not-allowed' }}">
                    <span class="w-2 h-2 rounded-full {{ $todaySchedule ? 'bg-white animate-ping' : 'bg-slate-400' }}"></span>
                    START COLLECTION SESSION
                </button>
            </form>
        @endif
    </div>
@endsection