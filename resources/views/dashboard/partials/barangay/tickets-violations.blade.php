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
                <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Report Date</th>
                        <th class="px-6 py-4">Violation Details</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                {{ $report->created_at->format('M d, Y h:i A') }}
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $report->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-800 italic mb-2">"{{ $report->description ?? 'No notes' }}"</p>
                                @if($report->photo_url)
                                    <a href="{{ $report->photo_url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 hover:underline">
                                        📸 View Photo Evidence
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <p class="text-slate-700 font-bold mb-0.5">Coordinates</p>
                                <p class="font-mono text-slate-400 text-[10px]">{{ round($report->latitude, 5) }}, {{ round($report->longitude, 5) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($report->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold uppercase rounded-md">Pending</span>
                                @elseif($report->status === 'investigating')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold uppercase rounded-md">Investigating</span>
                                @elseif($report->status === 'resolved')
                                    <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase rounded-md">Resolved</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 bg-slate-50 text-slate-500 border border-slate-200 text-[10px] font-bold uppercase rounded-md capitalize">{{ $report->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-1.5">
                                    <form method="POST" action="{{ route('dashboard.tickets-violations.action', $report->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="resolved">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors">Resolve</button>
                                    </form>
                                    <form method="POST" action="{{ route('dashboard.tickets-violations.action', $report->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="investigating">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors">Investigate</button>
                                    </form>
                                    <form method="POST" action="{{ route('dashboard.tickets-violations.action', $report->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="dismissed">
                                        <button type="submit" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-2.5 py-1 rounded-lg text-[10px] font-bold transition-colors">Dismiss</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No violation reports logged for this Barangay.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection