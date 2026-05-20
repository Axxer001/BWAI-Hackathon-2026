@extends('dashboard.layout')

@section('title', 'Route Schedules')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">📅 Collection Schedules</h1>
        <p class="text-sm text-slate-500 font-medium">Create and adjust weekly waste collection schedules for Barangay residents.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold animate-slideUp">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-slideUp" style="animation-delay: 0.1s;">
        
        {{-- Left: Add Schedule Form --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Add Weekly Schedule</h3>
                
                <form method="POST" action="{{ route('dashboard.schedules.store') }}" class="flex flex-col gap-4">
                    @csrf
                    
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Day of the Week</label>
                        <select name="day_of_week" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 text-xs font-medium bg-slate-50">
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Collection Time</label>
                        <input type="time" name="collection_time" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 text-xs font-medium bg-slate-50">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Frequency</label>
                        <select name="frequency" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 text-xs font-medium bg-slate-50">
                            <option value="weekly">Weekly</option>
                            <option value="bi-weekly">Bi-Weekly</option>
                            <option value="daily">Daily</option>
                        </select>
                    </div>

                    <button type="submit" class="mt-2 w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                        Create Schedule
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Calendar Grid & Active Schedules --}}
        <div class="lg:col-span-2 flex flex-col gap-6">
            
            {{-- Weekly Overview Grid --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Weekly Schedule Matrix</h3>
                
                <div class="grid grid-cols-7 gap-2">
                    @php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    @endphp
                    @foreach($days as $day)
                        @php
                            $dayScheds = $schedules->filter(fn($s) => $s->day_of_week === $day);
                        @endphp
                        <div class="flex flex-col items-center p-2 rounded-xl {{ $dayScheds->count() > 0 ? 'bg-indigo-50/50 border border-indigo-100' : 'bg-slate-50/50 border border-slate-100' }}">
                            <span class="text-[10px] font-bold uppercase tracking-wider {{ $dayScheds->count() > 0 ? 'text-indigo-600' : 'text-slate-400' }}">{{ substr($day, 0, 3) }}</span>
                            <span class="text-xs font-extrabold mt-1 {{ $dayScheds->count() > 0 ? 'text-indigo-800' : 'text-slate-300' }}">{{ $dayScheds->count() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Table List of Schedules --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900">Current active routines</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Day</th>
                                <th class="px-6 py-3">Time</th>
                                <th class="px-6 py-3">Frequency</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                            @forelse($schedules as $sched)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800 capitalize">{{ $sched->day_of_week }}</td>
                                    <td class="px-6 py-4 text-xs">{{ date('h:i A', strtotime($sched->collection_time)) }}</td>
                                    <td class="px-6 py-4 text-xs capitalize">{{ $sched->frequency }}</td>
                                    <td class="px-6 py-4">
                                        @if($sched->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-200">Active</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold border border-slate-200">Paused</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                                        <form method="POST" action="{{ route('dashboard.schedules.toggle', $sched->id) }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-slate-600 hover:text-slate-800 font-bold bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-lg transition-colors">
                                                {{ $sched->is_active ? 'Pause' : 'Resume' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.schedules.delete', $sched->id) }}" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-lg transition-colors">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No schedules defined yet for this Barangay.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection