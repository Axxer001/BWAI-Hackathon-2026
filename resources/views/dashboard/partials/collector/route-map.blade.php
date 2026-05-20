@extends('dashboard.layout')

@section('title', 'Assigned Route Map')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Assigned Route Map</h1>
        <p class="text-sm text-slate-500">Live navigation display showing collection checkpoints throughout your active coverage loop.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span> Current Target: Camino Nuevo Sector
            </span>
            <span class="text-xs text-slate-400 font-medium">Map syncs every 10 seconds</span>
        </div>
        <div class="w-full h-[500px] bg-slate-200">
            <iframe width="100%" height="100%" frameborder="0" scrolling="no" src="https://www.openstreetmap.org/export/embed.html?bbox=122.065%2C6.905%2C122.085%2C6.920&amp;layer=mapnik&amp;marker=6.912%2C122.075" style="filter: contrast(1.15) saturate(1.15);"></iframe>
        </div>
    </div>
@endsection