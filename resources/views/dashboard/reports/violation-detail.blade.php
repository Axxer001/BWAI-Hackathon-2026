@extends('dashboard.layout')

@section('title', 'Violation Report Details')

@section('content')
    <div class="mb-8 animate-slideUp">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-poppins text-2xl font-bold text-slate-900 mb-1">Violation Report Details</h1>
                <p class="text-sm text-slate-500">Full details for the illegal dumping report you submitted.</p>
            </div>
            <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-orange-600 hover:text-orange-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Report Page
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 animate-slideUp">
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $report->address }}</h2>
                    <p class="text-xs text-slate-500 mt-1">Filed {{ $report->created_at->format('F j, Y \a\t h:i A') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border {{ $report->status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : ($report->status === 'resolved' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200') }}">
                    {{ ucfirst($report->status) }}
                </span>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-3xl bg-slate-50 border border-slate-100 p-5">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Barangay</h3>
                        <p class="text-sm text-slate-700">{{ $report->barangay->name ?? 'Unspecified' }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 border border-slate-100 p-5">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">Coordinates</h3>
                        <p class="text-sm text-slate-700">{{ $report->latitude }}, {{ $report->longitude }}</p>
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-50 border border-slate-100 p-6">
                    <h3 class="text-sm font-bold text-slate-900 mb-3">Reported Description</h3>
                    @php
                        $descParts = explode('[AI Verification]', $report->description);
                        $userDesc = trim($descParts[0]);
                        $aiDesc = isset($descParts[1]) ? trim($descParts[1]) : null;
                    @endphp
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $userDesc }}</p>
                </div>

                @if($aiDesc)
                    <div class="rounded-3xl bg-white border border-slate-200 p-6">
                        <h3 class="text-sm font-bold text-slate-900 mb-3">AI Verification Summary</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $aiDesc }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="text-sm font-bold text-slate-900">Evidence Photo</h3>
                <p class="text-xs text-slate-500 mt-1">Uploaded by you</p>
            </div>
            <div class="p-6">
                <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                    <img src="{{ asset($report->photo_url) }}" alt="Violation evidence" class="w-full h-full object-cover min-h-[300px]">
                </div>
            </div>
        </div>
    </div>
@endsection
