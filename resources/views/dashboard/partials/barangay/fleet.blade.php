@extends('dashboard.layout')

@section('title', 'Track Fleet & Trucks')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Track Fleet & Trucks</h1>
        <p class="text-sm text-slate-500">Monitor active garbage collection vehicles within your jurisdiction in real-time.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <span class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                    </span>
                    Live GPS Tracking Active
                </span>
            </div>
            <div class="w-full h-[450px] bg-slate-200 relative">
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
                    src="https://www.openstreetmap.org/export/embed.html?bbox=122.065%2C6.905%2C122.085%2C6.920&amp;layer=mapnik" 
                    class="block" style="filter: contrast(1.1) saturate(1.2);">
                </iframe>
            </div>
        </div>

        <div class="lg:col-span-1 flex flex-col gap-4 animate-slideUp" style="animation-delay: 0.2s;">
            <h3 class="text-sm font-bold text-slate-900 px-1">Active Vehicles</h3>
            
            <div class="bg-white p-5 rounded-2xl border border-blue-200 shadow-sm shadow-blue-100 flex flex-col gap-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-slate-900">Truck A-102</h4>
                        <p class="text-xs text-slate-500">Driver: Carlos Mendoza</p>
                    </div>
                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-md">In Transit</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: 60%"></div>
                </div>
                <p class="text-[11px] text-slate-500 text-right">Route 60% Complete</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-slate-900">Truck B-405</h4>
                        <p class="text-xs text-slate-500">Driver: Pending Assignment</p>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded-md">Idle (Depot)</span>
                </div>
            </div>
        </div>
    </div>
@endsection