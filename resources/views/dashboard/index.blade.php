@extends('dashboard.layout')

@section('title', 'Overview Dashboard')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2">Welcome back, {{ Auth::user()->full_name ?? 'Resident' }}!</h1>
        <p class="text-sm text-slate-500">Here is an overview of your local waste management services today.</p>
    </div>

    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm animate-slideUp" style="animation-delay: 0.1s;">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Authentication Successful</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-4">You are securely logged into the LimpioZambo platform. Use the sidebar on the left to navigate to your Eco-Points, view the collection map, or use the AI Waste Scanner.</p>
                
                <a href="/dashboard/points" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 px-4 rounded-lg transition-colors">
                    View My Eco-Points
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>
@endsection