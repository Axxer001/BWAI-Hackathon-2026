@extends('dashboard.layout')

@section('title', 'Violation Reports')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Violation Reports</h1>
        <p class="text-sm text-slate-500">Investigate user-submitted reports of illegal dumping and improper segregation.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Date Reported</th>
                        <th class="px-6 py-4 font-semibold">Violation Type</th>
                        <th class="px-6 py-4 font-semibold">Location</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr class="hover:bg-red-50/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">Today, 9:15 AM</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">Illegal Dumping</p>
                            <button class="text-[11px] text-blue-600 font-medium hover:underline flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                View Photo Evidence
                            </button>
                        </td>
                        <td class="px-6 py-4 text-slate-600">Empty lot near Zone 3 Basketball Court</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-red-50 text-red-700 text-xs font-bold border border-red-200 uppercase">Pending Review</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">Take Action</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection