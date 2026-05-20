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
            <div class="flex items-center gap-4 mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-emerald-900">Shift is Currently Active</h3>
                    <p class="text-xs text-emerald-700 font-medium">You have an ongoing shift started at {{ $activeSession->started_at->format('h:i A') }}.</p>
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('dashboard.route-map') }}" class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-center tracking-wide shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                    GO TO ASSIGNED ROUTE MAP
                </a>

                <form action="{{ route('dashboard.complete-route', $activeSession->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold tracking-wide shadow-lg shadow-rose-600/20 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        END ACTIVE SHIFT
                    </button>
                </form>
            </div>
        @else
            <div class="flex items-center gap-4 mb-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-blue-900">Vehicle Check</h3>
                    <p class="text-xs text-blue-700 font-medium">Ensure your mobile GPS device is securely mounted to your dashboard configuration before hitting start.</p>
                </div>
            </div>

            <form action="{{ route('dashboard.start-session') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Select Plate Number</label>
                    <select name="plate_number" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all text-slate-700 font-semibold">
                        <option value="IX-7701">IX-7701 (Truck A-102)</option>
                        <option value="IX-4482">IX-4482 (Truck B-405)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Assigned Target Boundary</label>
                    <input type="text" value="{{ $boundaryName }} Sector Boundary" disabled class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 font-medium outline-none">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold tracking-wide shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-0.5 flex justify-center items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                        START COLLECTION SESSION
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection