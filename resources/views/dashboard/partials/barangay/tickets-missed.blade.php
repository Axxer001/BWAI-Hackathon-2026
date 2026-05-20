@extends('dashboard.layout')

@section('title', 'Missed Pickups Desk')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Missed Pickups Desk</h1>
        <p class="text-sm text-slate-500">Review and resolve reports from residents regarding uncollected waste.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-slideUp" style="animation-delay: 0.1s;">
        <div class="bg-white p-6 rounded-2xl border border-amber-200 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
            <div class="flex justify-between items-start mb-4">
                <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold uppercase rounded border border-amber-200">Action Required</span>
                <span class="text-xs font-medium text-slate-400">10 mins ago</span>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Household Waste Not Collected</h3>
            <p class="text-xs text-slate-600 mb-4">Reporter: Maria Santos (Purok 3)<br>Bin was placed outside at 7:30 AM.</p>
            <div class="flex gap-2">
                <button class="flex-1 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold py-2 rounded-lg transition-colors border border-amber-200">Dispatch Truck</button>
                <button class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-bold py-2 rounded-lg transition-colors border border-slate-200">Mark Invalid</button>
            </div>
        </div>
    </div>
@endsection