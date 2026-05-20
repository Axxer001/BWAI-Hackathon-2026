@extends('dashboard.layout')

@section('title', 'My Collection Point')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">My Collection Point</h1>
        <p class="text-sm text-slate-500">View your assigned GPS drop-off location and track live collection status.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col animate-slideUp" style="animation-delay: 0.1s;">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Camino Nuevo Drop-off (Zone 2)</h3>
                        <p class="text-xs text-slate-500">Zamboanga City, 7000</p>
                    </div>
                </div>
                <button class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 hover:border-blue-200">
                    Get Directions
                </button>
            </div>
            
            <div class="w-full h-[400px] bg-slate-200 relative">
                <iframe 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    src="https://www.openstreetmap.org/export/embed.html?bbox=122.065%2C6.905%2C122.085%2C6.920&amp;layer=mapnik&amp;marker=6.912%2C122.075" 
                    class="block"
                    style="filter: contrast(1.1) saturate(1.2);">
                </iframe>
                
                <div class="absolute inset-0 pointer-events-none shadow-[inset_0_0_20px_rgba(0,0,0,0.05)]"></div>
            </div>
        </div>

        <div class="lg:col-span-1 flex flex-col gap-6 animate-slideUp" style="animation-delay: 0.2s;">
            
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-lg shadow-blue-600/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4"/></svg>
                </div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-100"></span>
                        </span>
                        <span class="text-blue-100 text-xs font-bold uppercase tracking-widest">Live Route Status</span>
                    </div>
                    
                    <h2 class="text-2xl font-extrabold tracking-tight mb-1">Truck is 2 stops away</h2>
                    <p class="text-blue-200 text-sm mb-6">Estimated arrival: <span class="text-white font-semibold">15 mins (8:45 AM)</span></p>
                    
                    <div class="w-full bg-blue-900/40 rounded-full h-2 mb-2">
                        <div class="bg-blue-300 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                    <p class="text-[11px] text-blue-200 text-right">Route is 75% complete</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex-1 flex flex-col">
                <h3 class="text-sm font-bold text-slate-900 mb-5">Today's Requirements</h3>
                
                <div class="space-y-4 flex-1">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0 mt-0.5 border border-green-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Scheduled Window</p>
                            <p class="text-sm font-bold text-slate-800">8:00 AM - 10:00 AM</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Please place bins out 15 mins prior.</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 mt-0.5 border border-slate-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium">Accepted Waste Today</p>
                            <div class="mt-1.5 flex flex-wrap gap-2">
                                <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-md border border-green-200">Biodegradable</span>
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-md border border-blue-200">Recyclable (Plastic)</span>
                            </div>
                            <p class="text-[11px] text-red-500 mt-2 flex items-center gap-1 font-medium">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                No Residual Waste today
                            </p>
                        </div>
                    </div>
                </div>
                
                <button class="w-full mt-6 bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 hover:border-slate-300 font-semibold py-2.5 rounded-xl text-sm transition-all shadow-sm flex justify-center items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    View City Ordinance Guidelines
                </button>
            </div>
            
        </div>
    </div>
@endsection