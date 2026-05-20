@extends('dashboard.layout')

@section('title', 'Missed Pickups Desk')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">🚨 Missed Pickups Desk</h1>
        <p class="text-sm text-slate-500 font-medium">Review and resolve reports from residents regarding uncollected waste in Barangay.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold animate-slideUp">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-slideUp" style="animation-delay: 0.1s;">
        @forelse($reports as $report)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative flex flex-col justify-between">
                
                {{-- Status indicators --}}
                <div class="p-5 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        @if($report->status === 'pending')
                            <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase rounded-md tracking-wider">Pending</span>
                        @elseif($report->status === 'resolved')
                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase rounded-md tracking-wider">Resolved</span>
                        @else
                            <span class="px-2.5 py-0.5 bg-slate-50 text-slate-500 border border-slate-200 text-[10px] font-bold uppercase rounded-md tracking-wider capitalize">{{ $report->status }}</span>
                        @endif
                        <span class="text-[10px] font-semibold text-slate-400">{{ $report->created_at->diffForHumans() }}</span>
                    </div>

                    <h3 class="font-bold text-slate-800 text-sm mb-1">Missed Collection Point</h3>
                    <p class="text-xs text-slate-500 mb-4 font-medium">Location: <span class="text-slate-700 font-bold">{{ $report->collectionPoint->name ?? 'Unknown Point' }}</span></p>

                    @if($report->photo_url)
                        <div class="w-full h-32 rounded-lg bg-slate-100 mb-4 overflow-hidden border border-slate-100">
                            <img src="{{ $report->photo_url }}" class="w-full h-full object-cover" alt="Evidence">
                        </div>
                    @endif

                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 text-xs text-slate-600 mb-4 font-medium italic">
                        "{{ $report->notes ?? 'No additional description provided.' }}"
                    </div>

                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Reported By</p>
                    <p class="text-xs font-semibold text-slate-700 mb-1">{{ $report->reporter->full_name ?? 'Resident' }}</p>
                    <p class="text-[10px] text-slate-400 font-mono">{{ $report->reporter->email ?? '' }}</p>
                </div>

                @if($report->status === 'pending')
                    <div class="border-t border-slate-100 p-4 bg-slate-50/50 flex gap-2">
                        <form method="POST" action="{{ route('dashboard.tickets-missed.action', $report->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 rounded-xl transition-colors shadow-sm">
                                Resolve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.tickets-missed.action', $report->id) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="invalid">
                            <button type="submit" class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold py-2 rounded-xl transition-colors">
                                Mark Invalid
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                <p class="text-slate-400 text-sm font-semibold">No missed pickup reports logged for this Barangay.</p>
            </div>
        @endforelse
    </div>
@endsection