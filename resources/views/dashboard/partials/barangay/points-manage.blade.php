@extends('dashboard.layout')

@section('title', 'Collection Points')

@section('content')
    <div class="flex justify-between items-end mb-8 animate-slideUp">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Collection Points</h1>
            <p class="text-sm text-slate-500">Manage designated waste drop-off zones and monitor reported capacities.</p>
        </div>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors">
            + Add New Point
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Zone / Location</th>
                        <th class="px-6 py-4 font-semibold">Assigned Households</th>
                        <th class="px-6 py-4 font-semibold">Current Est. Capacity</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">Camino Nuevo Drop-off</p>
                            <p class="text-xs text-slate-500">Zone 2, Main Street</p>
                        </td>
                        <td class="px-6 py-4">145 / 200 Max</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-200">
                                <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div> 85% Full
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs">Edit</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">Purok 4 Junction</p>
                            <p class="text-xs text-slate-500">Zone 4, Near Plaza</p>
                        </td>
                        <td class="px-6 py-4">80 / 150 Max</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-green-50 text-green-700 text-xs font-semibold border border-green-200">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> 30% Full
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs">Edit</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection