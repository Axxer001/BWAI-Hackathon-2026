@extends('dashboard.layout')

@section('title', 'Platform Settings')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Platform Settings</h1>
        <p class="text-sm text-slate-500">Configure global application parameters, AI thresholds, and ordinance enforcement rules.</p>
    </div>

    <div class="max-w-3xl space-y-6 animate-slideUp" style="animation-delay: 0.1s;">
        
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="text-lg font-bold text-slate-900 mb-6">Global Configurations</h3>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Maintenance Mode</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Suspend non-admin logins for system updates.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-600"></div>
                    </label>
                </div>

                <hr class="border-slate-100">

                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Strict AI Validation</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Require >85% confidence score on Waste Scanner for points.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                <button class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
@endsection