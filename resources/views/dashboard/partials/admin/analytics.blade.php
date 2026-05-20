@extends('dashboard.layout')

@section('title', 'Analytics & Reports')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 animate-slideUp">
        <div>
            <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">📈 Analytics & Reports</h1>
            <p class="text-sm text-slate-500 font-medium">Compare barangay performance, collection efficiency, and download detailed reports.</p>
        </div>
        <a href="{{ route('admin.analytics.download') }}"
            class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Download CSV
        </a>
    </div>

    {{-- Top-level city stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-slideUp" style="animation-delay: 0.05s;">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">System Eco-Points</p>
            <div class="flex items-baseline gap-1.5">
                <span class="text-4xl font-extrabold text-emerald-600 tracking-tight">{{ number_format($totalEcoPoints) }}</span>
                <span class="text-xs text-slate-400 font-bold">pts</span>
            </div>
            <p class="text-[10px] text-emerald-600 font-bold mt-2">Circulating across all users</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Total Waste Processed</p>
            <div class="flex items-baseline gap-1.5">
                <span class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalWaste, 1) }}</span>
                <span class="text-xs text-slate-400 font-bold">Tons</span>
            </div>
            <p class="text-[10px] text-slate-400 font-bold mt-2">Estimated from session reports</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Open Violations</p>
            <div class="flex items-baseline gap-1.5">
                <span class="text-4xl font-extrabold text-red-600 tracking-tight">{{ $totalViolations }}</span>
                <span class="text-xs text-slate-400 font-bold">reports</span>
            </div>
            <p class="text-[10px] text-red-500 font-bold mt-2">Pending barangay action</p>
        </div>
    </div>

    {{-- Per-barangay performance table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Barangay Efficiency Comparison</h3>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $barangays->count() }} Barangays</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Barangay</th>
                        <th class="px-6 py-4">Completed Sessions</th>
                        <th class="px-6 py-4">Waste Collected</th>
                        <th class="px-6 py-4">Eco-Points Distributed</th>
                        <th class="px-6 py-4">Avg Completion Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($barangays as $barangay)
                        @php
                            $rate = $barangay->avg_completion ?? 0;
                            $barColor = $rate >= 80 ? 'bg-emerald-500' : ($rate >= 50 ? 'bg-amber-500' : 'bg-red-400');
                            $textColor = $rate >= 80 ? 'text-emerald-700' : ($rate >= 50 ? 'text-amber-700' : 'text-red-600');
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $barangay->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $barangay->district }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold">
                                {{ number_format($barangay->completed_count) }}
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ number_format($barangay->waste_collected, 2) }} Tons
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-emerald-700">
                                {{ number_format($barangay->points_distributed) }} pts
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-slate-100 rounded-full h-1.5 overflow-hidden w-20">
                                        <div class="{{ $barColor }} h-full rounded-full" style="width: {{ $rate }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-extrabold {{ $textColor }} whitespace-nowrap">{{ $rate }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No collection report data available yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection