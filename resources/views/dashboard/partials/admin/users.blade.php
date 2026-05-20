@extends('dashboard.layout')

@section('title', 'System Users')

@section('content')
    <div class="flex justify-between items-end mb-8 animate-slideUp">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">System Users</h1>
            <p class="text-sm text-slate-500">Manage all registered residents, collectors, and barangay administrators across the platform.</p>
        </div>
        <div class="flex gap-3">
            <div class="relative">
                <input type="text" placeholder="Search users..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-purple-500 outline-none w-64 shadow-sm">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors">
                + Add User
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Full Name</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold">Barangay</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">James Benedict Rojas</p>
                            <p class="text-xs text-slate-500">rojasjamesbenedict@gmail.com</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-md border border-green-200">Resident</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-600">Camino Nuevo</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-green-500"></div> Active</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs">Manage</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">Carlos Mendoza</p>
                            <p class="text-xs text-slate-500">carlos.m@limpiozambo.gov</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-md border border-blue-200">Collector</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-600">City Wide (Fleet)</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-green-500"></div> Active</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs">Manage</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection