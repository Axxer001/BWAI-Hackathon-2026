@extends('dashboard.layout')

@section('title', 'Collection History')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Collection History Log</h1>
        <p class="text-sm text-slate-500">Review historical log sheets containing successfully verified pickup checkpoints verified during your operational periods.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Timestamp</th>
                        <th class="px-6 py-4 font-semibold">Checkpoint Location</th>
                        <th class="px-6 py-4 font-semibold">Households Served</th>
                        <th class="px-6 py-4 font-semibold text-right">Status State</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-500 font-medium">Today, 10:14 AM</td>
                        <td class="px-6 py-4 font-bold text-slate-900">Camino Nuevo Drop-off (Zone 2)</td>
                        <td class="px-6 py-4 font-medium">42 Houses</td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-green-50 border border-green-200 text-green-700 font-bold text-xs uppercase">Complete</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-500 font-medium">Today, 09:30 AM</td>
                        <td class="px-6 py-4 font-bold text-slate-900">Purok 4 Junction Hub Point</td>
                        <td class="px-6 py-4 font-medium">18 Houses</td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-green-50 border border-green-200 text-green-700 font-bold text-xs uppercase">Complete</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection