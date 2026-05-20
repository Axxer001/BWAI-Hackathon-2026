@extends('dashboard.layout')

@section('title', 'City-Wide Overview')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">🌍 Zamboanga City Overview</h1>
        <p class="text-sm text-slate-500 font-medium">Global operations control panel and ecological points ledger system.</p>
    </div>

    {{-- Global stats grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slideUp" style="animation-delay: 0.05s;">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Waste Collected</p>
            <div class="flex items-baseline gap-1">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalWaste, 1) }}</h2>
                <span class="text-xs font-bold text-slate-400">Tons</span>
            </div>
            <div class="w-full bg-slate-100 h-1 mt-4 rounded-full overflow-hidden">
                <div class="bg-indigo-600 h-full rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">System Eco-Points</p>
            <div class="flex items-baseline gap-1">
                <h2 class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ number_format($totalEcoPoints) }}</h2>
                <span class="text-xs font-bold text-emerald-400">pts</span>
            </div>
            <div class="w-full bg-slate-100 h-1 mt-4 rounded-full overflow-hidden">
                <div class="bg-emerald-600 h-full rounded-full" style="width: 85%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Onboarded Barangays</p>
            <div class="flex items-baseline gap-1">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalBarangays }}</h2>
                <span class="text-xs font-bold text-slate-400">active LGUs</span>
            </div>
            <div class="w-full bg-slate-100 h-1 mt-4 rounded-full overflow-hidden">
                <div class="bg-purple-600 h-full rounded-full" style="width: 90%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Active Fleet Trucks</p>
            <div class="flex items-baseline gap-1">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalTrucks }}</h2>
                <span class="text-xs font-bold text-slate-400">vehicles</span>
            </div>
            <div class="w-full bg-slate-100 h-1 mt-4 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full" style="width: 65%"></div>
            </div>
        </div>
    </div>

    {{-- Barangay Performance Comparison list --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-sm font-bold text-slate-900">Barangay Infrastructure Summary</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Barangay Name</th>
                        <th class="px-6 py-4">District</th>
                        <th class="px-6 py-4">Active Fleet</th>
                        <th class="px-6 py-4">Collection Points</th>
                        <th class="px-6 py-4">Registered Admins</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($barangays as $barangay)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-slate-950">{{ $barangay->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $barangay->district }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold">
                                {{ $barangay->trucks_count }} trucks
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold">
                                {{ $barangay->collection_points_count }} zones
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $barangay->users_count }} users
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs font-semibold">No onboarded barangays yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
