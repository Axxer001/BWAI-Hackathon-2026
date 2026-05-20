@extends('dashboard.layout')

@section('title', 'Collection History')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Collection History Log</h1>
        <p class="text-sm text-slate-500">Review historical log sheets containing successfully verified pickup checkpoints verified during your operational periods.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Timestamp</th>
                        <th class="px-6 py-4 font-semibold">Checkpoint Location</th>
                        <th class="px-6 py-4 font-semibold">Households Served</th>
                        <th class="px-6 py-4 font-semibold text-right">Status State</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($logs as $log)
                        @php
                            $householdsCount = \App\Models\UserPointAssignment::where('garbage_point_id', $log->garbage_point_id)->count();
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-500 font-medium">
                                {{ $log->collected_at ? $log->collected_at->format('M d, h:i A') : $log->updated_at->format('M d, h:i A') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900">{{ $log->garbagePoint->name }}</span>
                                <span class="block text-xs text-slate-400 mt-0.5">{{ $log->garbagePoint->address }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $householdsCount }} {{ Str::plural('House', $householdsCount) }}</td>
                            <td class="px-6 py-4 text-right">
                                @if($log->status === 'collected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-xs uppercase">Collected</span>
                                @elseif($log->status === 'skipped')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-rose-50 border border-rose-200 text-rose-700 font-bold text-xs uppercase">Skipped</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-500 font-bold text-xs uppercase">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium">
                                No checkpoint collection logs recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection