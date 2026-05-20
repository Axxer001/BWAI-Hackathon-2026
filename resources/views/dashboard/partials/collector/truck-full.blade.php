@extends('dashboard.layout')

@section('title', 'Log Truck Full Event')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Log Truck Full Event</h1>
        <p class="text-sm text-slate-500">Instantly flag when your vehicle cargo reaches maximum weight limit layout constraints.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-semibold flex items-center gap-2 max-w-xl animate-slideUp">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-xl bg-white rounded-2xl border border-slate-200 shadow-sm p-6 animate-slideUp" style="animation-delay: 0.1s;">
        @if(!$activeSession)
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h4 class="text-base font-bold text-slate-800">No Active Shift Session Found</h4>
                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">You must start an active collection session before broadcasting truck capacity events.</p>
                <a href="{{ route('dashboard.active-session') }}" class="inline-block mt-5 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs tracking-wider transition-colors">
                    START SHIFT
                </a>
            </div>
        @else
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex gap-3 text-amber-800">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <h4 class="text-sm font-bold">Important Notice</h4>
                    <p class="text-xs font-medium mt-0.5">Submitting this event will temporarily clear your markers from residents' maps and reroute you toward the city landfill operations terminal.</p>
                </div>
            </div>

            <form action="{{ route('dashboard.log-truck-full') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="session_id" value="{{ $activeSession->id }}">

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Current Collection Point Location</label>
                    <select name="collection_point_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-500/40 focus:bg-white transition-all text-slate-700 font-semibold" required>
                        <option value="" disabled selected>Select point location...</option>
                        @foreach($collectionPoints as $cp)
                            <option value="{{ $cp->id }}">{{ $cp->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Estimated Waste Volume Load</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex flex-col items-center p-4 rounded-xl border-2 border-slate-200 cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition-all font-bold text-sm text-center relative select-none">
                            <input type="radio" name="estimated_load" value="100%" class="sr-only peer" required>
                            <span class="text-slate-700 peer-checked:text-amber-800">100% Solid Full</span>
                            <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-amber-500 bg-amber-500/5 -z-10 transition-colors"></div>
                        </label>
                        <label class="flex flex-col items-center p-4 rounded-xl border-2 border-slate-200 cursor-pointer bg-slate-50/50 hover:bg-slate-50 transition-all font-bold text-sm text-center relative select-none">
                            <input type="radio" name="estimated_load" value="Overflowing" class="sr-only peer" required>
                            <span class="text-slate-700 peer-checked:text-amber-800">Overflowing Catch</span>
                            <div class="absolute inset-0 rounded-xl border-2 border-transparent peer-checked:border-amber-500 bg-amber-500/5 -z-10 transition-colors"></div>
                        </label>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold tracking-wide shadow-md shadow-amber-500/20 transition-colors">
                        BROADCAST TRUCK CAPACITY STATUS
                    </button>
                </div>
            </form>
        @endif
    </div>

    <style>
        /* Highlight styled radio buttons visually when checked */
        input[type="radio"]:checked + span {
            color: #b45309 !important; /* amber-700 */
        }
        input[type="radio"]:checked ~ div {
            border-color: #f59e0b !important; /* amber-500 */
            background-color: rgb(254 243 199 / 0.5) !important;
        }
    </style>
@endsection