@extends('dashboard.layout')

@section('title', 'Violation Reports')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">⚖️ Resident Violation Reports</h1>
        <p class="text-sm text-slate-500 font-medium">Investigate and take action on user-reported illegal waste dumping or segregation violations.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold animate-slideUp">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Report Info</th>
                        <th class="px-6 py-4">Violation Details</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            
                            {{-- Report Date & User --}}
                            <td class="px-6 py-5 whitespace-nowrap align-top">
                                <p class="text-xs font-bold text-slate-800">{{ $report->created_at->format('M d, Y') }}</p>
                                <p class="text-[10px] text-slate-400 mb-3">{{ $report->created_at->format('h:i A') }}</p>
                                
                                <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Reported By</p>
                                <p class="text-xs font-semibold text-indigo-600">{{ $report->reporter->full_name ?? 'Resident' }}</p>
                            </td>

                            {{-- Description & AI Verification --}}
                            <td class="px-6 py-5 align-top max-w-xs">
                                @php
                                    // Split the description to separate user notes from AI analysis
                                    $descParts = explode('[AI Verification]', $report->description);
                                    $userDesc = trim($descParts[0]);
                                    $aiDesc = isset($descParts[1]) ? trim($descParts[1]) : null;
                                @endphp
                                
                                <p class="text-xs text-slate-700 leading-relaxed mb-3 italic">"{{ $userDesc ?: 'No additional notes provided.' }}"</p>
                                
                                @if($aiDesc)
                                    <div class="p-3 bg-indigo-50/50 border border-indigo-100 rounded-lg flex items-start gap-2.5 mb-3">
                                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                                        </svg>
                                        <div>
                                            <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mb-0.5">AI Analysis</p>
                                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $aiDesc }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if($report->photo_url)
                                    <a href="{{ $report->photo_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[10px] font-bold uppercase rounded-lg transition-colors border border-slate-200 shadow-sm">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        View Evidence
                                    </a>
                                @endif
                            </td>

                            {{-- Location & Address --}}
                            <td class="px-6 py-5 align-top">
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-rose-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800 leading-snug mb-1">{{ $report->address ?? 'Address not specified' }}</p>
                                        <p class="font-mono text-slate-400 text-[10px] tracking-tight">{{ round($report->latitude, 5) }}, {{ round($report->longitude, 5) }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-5 whitespace-nowrap align-top">
                                @php
                                    $statusLabel = match($report->status) {
                                        'pending' => 'Pending',
                                        'under_review' => 'Investigating',
                                        'investigating' => 'Investigating',
                                        'fined' => 'Resolved',
                                        'resolved' => 'Resolved',
                                        'dismissed' => 'Dismissed',
                                        default => ucfirst($report->status),
                                    };

                                    $statusClass = match($report->status) {
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'under_review', 'investigating' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'fined', 'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'dismissed' => 'bg-slate-50 text-slate-500 border-slate-200',
                                        default => 'bg-slate-50 text-slate-500 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 {{ $statusClass }} text-[10px] font-bold uppercase rounded-md tracking-wider">{{ $statusLabel }}</span>
                            </td>

                            {{-- Actions Column --}}
                            <td class="px-6 py-5 text-right align-top">
                                <div class="flex flex-col gap-2 min-w-[110px]">
                                    
                                    @if(!in_array($report->status, ['fined', 'resolved']))
                                    <form method="POST" action="{{ route('dashboard.tickets-violations.action', $report->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="fined">
                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-colors shadow-sm text-center">
                                            Mark Resolved
                                        </button>
                                    </form>
                                    @endif

                                    @if(!in_array($report->status, ['under_review', 'investigating']))
                                    <form method="POST" action="{{ route('dashboard.tickets-violations.action', $report->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="under_review">
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-colors shadow-sm text-center">
                                            Investigate
                                        </button>
                                    </form>
                                    @endif

                                    @if($report->status !== 'dismissed')
                                    <form method="POST" action="{{ route('dashboard.tickets-violations.action', $report->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="dismissed">
                                        <button type="submit" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-[10px] font-bold transition-colors border border-slate-200 text-center">
                                            Dismiss
                                        </button>
                                    </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-700 mb-1">No violations reported</h3>
                                <p class="text-xs text-slate-500">There are no resident violation reports logged for this Barangay yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection