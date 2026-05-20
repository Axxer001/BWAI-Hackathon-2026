@extends('dashboard.layout')

@section('title', 'City-Wide Analytics')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">City-Wide Analytics</h1>
        <p class="text-sm text-slate-500">Aggregated environmental impact and operational efficiency metrics.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-slideUp" style="animation-delay: 0.1s;">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-sm font-semibold text-slate-500 mb-1">Total Waste Diverted</p>
            <div class="flex items-end gap-2">
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">4,250</h2>
                <span class="text-slate-500 font-medium mb-1">kg</span>
            </div>
            <p class="text-xs text-green-600 font-bold mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                12% this month
            </p>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-sm font-semibold text-slate-500 mb-1">Eco-Points Distributed</p>
            <div class="flex items-end gap-2">
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">185.2k</h2>
                <span class="text-slate-500 font-medium mb-1">pts</span>
            </div>
            <p class="text-xs text-green-600 font-bold mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                Active economy
            </p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-sm font-semibold text-slate-500 mb-1">Open Violations</p>
            <div class="flex items-end gap-2">
                <h2 class="text-4xl font-extrabold text-red-600 tracking-tight">24</h2>
                <span class="text-slate-500 font-medium mb-1">reports</span>
            </div>
            <p class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Requires attention
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-80 flex items-center justify-center animate-slideUp" style="animation-delay: 0.2s;">
        <p class="text-slate-400 font-medium">Monthly Segregation Trends Chart Space</p>
    </div>
@endsection