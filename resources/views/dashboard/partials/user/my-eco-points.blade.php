@extends('dashboard.layout')

@section('title', 'My Eco-Points')

@section('content')

    {{-- Page Header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-poppins text-2xl font-bold text-slate-900 mb-1">My Eco-Points</h1>
        <p class="text-sm text-slate-500">Track your rewards for maintaining a clean and green Zamboanga.</p>
    </div>

    {{-- Top Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 animate-slideUp" style="animation-delay: 0.1s;">

        {{-- Balance Card --}}
        <div class="lg:col-span-1 bg-slate-900 rounded-2xl p-7 text-white relative overflow-hidden flex flex-col justify-between min-h-[260px]">
            {{-- Decorative circles --}}
            <div class="absolute -bottom-10 -right-10 w-48 h-48 rounded-full bg-white/[.04]"></div>
            <div class="absolute -top-10 -left-10 w-36 h-36 rounded-full bg-white/[.04]"></div>
            <div class="absolute top-1/2 right-6 w-20 h-20 rounded-full bg-green-500/10"></div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-slate-400 text-[11px] font-semibold uppercase tracking-widest">Available Balance</p>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-green-500/15 text-green-400 border border-green-500/20">Active</span>
                </div>

                <div class="mb-1 flex items-end gap-2">
                    <span class="text-[3.5rem] font-extrabold tracking-tight leading-none text-white">1,250</span>
                    <span class="text-green-400 font-semibold text-base mb-1">pts</span>
                </div>
                <p class="text-slate-500 text-xs mb-7">eco-points balance</p>

                <div class="w-full h-px bg-white/[.08] mb-5"></div>

                <div class="flex items-center justify-between mb-7">
                    <div>
                        <p class="text-slate-500 text-[11px] mb-0.5">Earned this month</p>
                        <p class="text-green-400 font-bold text-sm">+30 pts</p>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div class="text-right">
                        <p class="text-slate-500 text-[11px] mb-0.5">Total redeemed</p>
                        <p class="text-slate-300 font-bold text-sm">100 pts</p>
                    </div>
                </div>
            </div>

            <button class="relative z-10 w-full bg-white text-slate-900 hover:bg-slate-100 font-bold py-3 rounded-xl text-sm transition-all hover:-translate-y-0.5">
                Redeem Rewards →
            </button>
        </div>

        {{-- How to Earn --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 flex flex-col">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-slate-900">How to Earn Points</h3>
                <span class="text-[11px] text-slate-400 font-medium">3 ways available</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 flex-1">
                {{-- Scan --}}
                <div class="flex flex-col p-5 bg-slate-50 rounded-xl border border-slate-100 hover:border-purple-200 hover:bg-purple-50/40 transition-all group">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mb-4 group-hover:bg-purple-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        </svg>
                    </div>
                    <div class="mt-auto">
                        <div class="text-[11px] font-semibold text-purple-600 mb-1">+5 Points per scan</div>
                        <div class="text-sm font-bold text-slate-800 mb-1">Scan Waste with AI</div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Identify and classify your garbage before placing it at the collection point.</p>
                    </div>
                </div>

                {{-- Collection --}}
                <div class="flex flex-col p-5 bg-slate-50 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/40 transition-all group">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="mt-auto">
                        <div class="text-[11px] font-semibold text-blue-600 mb-1">+10 Points per collection</div>
                        <div class="text-sm font-bold text-slate-800 mb-1">Verified Collection</div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Earn when a collector successfully logs your GPS point as collected.</p>
                    </div>
                </div>

                {{-- Report --}}
                <div class="flex flex-col p-5 bg-slate-50 rounded-xl border border-slate-100 hover:border-amber-200 hover:bg-amber-50/40 transition-all group">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-4 group-hover:bg-amber-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-auto">
                        <div class="text-[11px] font-semibold text-amber-600 mb-1">+15 Points per report</div>
                        <div class="text-sm font-bold text-slate-800 mb-1">Report Violations</div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">Submit a valid photo report of a garbage violation in your barangay.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="mt-5 bg-white rounded-2xl border border-slate-200 animate-slideUp" style="animation-delay: 0.2s;">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Recent Transactions</h3>
                <p class="text-xs text-slate-400 mt-0.5">Your latest point activity</p>
            </div>
            <button class="text-xs font-semibold text-green-600 hover:text-green-700 border border-green-200 hover:border-green-400 px-3 py-1.5 rounded-lg transition-all">
                View All
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3.5 text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">Today, 8:45 AM</td>
                        <td class="px-6 py-4 font-medium text-sm">AI Waste Scan — Plastic Bottle</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-100">
                                AI Scan
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">+5</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">Yesterday, 7:10 AM</td>
                        <td class="px-6 py-4 font-medium text-sm">Successful Segregated Collection Logged</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                Collection
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">+10</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">May 15, 2026</td>
                        <td class="px-6 py-4 font-medium text-sm">Valid Violation Report Submitted</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                                Report
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">+15</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400">May 12, 2026</td>
                        <td class="px-6 py-4 font-medium text-sm">Redeemed: Barangay Environmental Certificate</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-red-50 text-red-700 border border-red-100">
                                Redemption
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-red-500">-100</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Empty footer --}}
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">Showing 4 of 4 transactions</p>
            <div class="flex items-center gap-1.5">
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <p class="text-xs text-slate-500 font-medium">Points update in real time</p>
            </div>
        </div>
    </div>

@endsection