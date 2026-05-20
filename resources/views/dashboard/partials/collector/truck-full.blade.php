@extends('dashboard.layout')

@section('title', 'Log Truck Full Event')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Log Truck Full Event</h1>
        <p class="text-sm text-slate-500">Instantly flag when your vehicle cargo reaches maximum weight limit layout constraints.</p>
    </div>

    <div class="max-w-xl bg-white rounded-2xl border border-slate-200 shadow-sm p-6 animate-slideUp" style="animation-delay: 0.1s;">
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex gap-3 text-amber-800">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <h4 class="text-sm font-bold">Important Notice</h4>
                <p class="text-xs font-medium mt-0.5">Submitting this event will temporarily clear your markers from residents' maps and reroute you toward the city landfill operations terminal.</p>
            </div>
        </div>

        <form class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Estimated Waste Volume Load</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="p-4 rounded-xl border-2 border-amber-500 bg-amber-50/50 text-amber-800 font-bold text-sm text-center">100% Solid Full</button>
                    <button type="button" class="p-4 rounded-xl border border-slate-200 hover:border-slate-300 text-slate-700 font-semibold text-sm text-center bg-slate-50">Overflowing Catch</button>
                </div>
            </div>

            <div class="pt-4">
                <button type="button" class="w-full py-3.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold tracking-wide shadow-md shadow-amber-500/20 transition-colors">
                    BROADCAST TRUCK CAPACITY STATUS
                </button>
            </div>
        </form>
    </div>
@endsection