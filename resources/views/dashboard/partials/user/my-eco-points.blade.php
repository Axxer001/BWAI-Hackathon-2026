@extends('dashboard.layout')

@section('title', 'My Eco-Points')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2">My Eco-Points</h1>
        <p class="text-sm text-slate-500">Track your rewards for maintaining a clean and green Zamboanga.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-slideUp" style="animation-delay: 0.1s;">
        
        <div class="lg:col-span-1 bg-gradient-to-br from-green-600 to-green-800 rounded-2xl p-6 text-white shadow-lg shadow-green-600/20 relative overflow-hidden">
            <svg class="absolute -bottom-6 -right-6 w-32 h-32 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            
            <div class="relative z-10">
                <p class="text-green-100 text-sm font-semibold uppercase tracking-wider mb-2">Available Balance</p>
                <div class="flex items-end gap-2 mb-6">
                    <span class="text-5xl font-extrabold tracking-tight">1,250</span>
                    <span class="text-green-200 font-medium mb-1.5">pts</span>
                </div>
                
                <button class="w-full bg-white text-green-700 hover:bg-green-50 font-bold py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                    Redeem Rewards
                </button>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col justify-center">
            <h3 class="text-sm font-bold text-slate-900 mb-4">How to Earn More</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mb-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mb-1">+5 Points</div>
                    <div class="text-sm font-bold text-slate-800">Scan Waste with AI</div>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mb-1">+10 Points</div>
                    <div class="text-sm font-bold text-slate-800">Verified Collection</div>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="text-xs font-semibold text-slate-500 mb-1">+15 Points</div>
                    <div class="text-sm font-bold text-slate-800">Report Violations</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm animate-slideUp" style="animation-delay: 0.2s;">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Recent Transactions</h3>
            <button class="text-sm font-medium text-green-600 hover:text-green-700">View All</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Description</th>
                        <th class="px-6 py-4 font-semibold text-right">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">Today, 8:45 AM</td>
                        <td class="px-6 py-4 font-medium">AI Waste Scan - Plastic Bottle</td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">+5</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">Yesterday, 7:10 AM</td>
                        <td class="px-6 py-4 font-medium">Successful Segregated Collection Logged</td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">+10</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">May 15, 2026</td>
                        <td class="px-6 py-4 font-medium">Valid Violation Report Submitted</td>
                        <td class="px-6 py-4 text-right font-bold text-green-600">+15</td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">May 12, 2026</td>
                        <td class="px-6 py-4 font-medium">Redeemed: Barangay Environmental Certificate</td>
                        <td class="px-6 py-4 text-right font-bold text-red-500">-100</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection