@extends('dashboard.layout')

@section('title', 'Barangay Directory')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Barangay Directory</h1>
        <p class="text-sm text-slate-500">Oversee local government units, their active collection fleets, and overall compliance scores.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-slideUp" style="animation-delay: 0.1s;">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-lg">CN</div>
                <span class="px-2.5 py-1 bg-green-50 text-green-700 text-[10px] font-bold uppercase rounded border border-green-200">Compliant</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Camino Nuevo</h3>
            <p class="text-xs text-slate-500 mb-4">Admin: Brgy. Capt. Santos</p>
            
            <div class="space-y-2 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Registered Residents</span>
                    <span class="font-semibold text-slate-700">1,245</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Active Trucks</span>
                    <span class="font-semibold text-slate-700">3</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Collection Points</span>
                    <span class="font-semibold text-slate-700">8</span>
                </div>
            </div>
            
            <button class="w-full bg-slate-50 hover:bg-slate-100 text-purple-700 text-sm font-bold py-2.5 rounded-xl transition-colors border border-slate-200">
                View Dashboard
            </button>
        </div>

    </div>
@endsection