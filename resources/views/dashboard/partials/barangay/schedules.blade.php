@extends('dashboard.layout')

@section('title', 'Route Schedules')

    @section('content')
        <div class="mb-8 animate-slideUp">
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Route Schedules</h1>
            <p class="text-sm text-slate-500">Manage and monitor daily garbage collection routes for your Barangay.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 animate-slideUp" style="animation-delay: 0.1s;">
            <div class="bg-white p-6 rounded-2xl border border-amber-200 shadow-sm shadow-amber-100 flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wide">Active Routes Today</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ $activeRoutes }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-blue-200 shadow-sm shadow-blue-100 flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wide">Assigned Personnel</p>
                    <h3 class="text-3xl font-black text-slate-900">{{ $assignedPersonnel }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm animate-slideUp overflow-hidden"
            style="animation-delay: 0.2s;">
            <div class="p-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Weekly Schedule</h3>
                <button
                    class="px-4 py-2 bg-amber-500 text-white text-xs font-bold rounded-lg shadow-md shadow-amber-500/30 hover:bg-amber-600 transition-colors">
                    + New Schedule
                </button>
            </div>

            <div class="p-0">
                @if(isset($schedules) && $schedules->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($schedules as $schedule)
                            <div
                                class="p-5 hover:bg-slate-50 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-12 h-12 rounded-lg bg-amber-50 border border-amber-100 flex flex-col items-center justify-center flex-shrink-0">
                                        <span
                                            class="text-[10px] font-bold text-amber-500 uppercase">{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('M') }}</span>
                                        <span
                                            class="text-lg font-black text-amber-700 leading-none">{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d') }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-lg">{{ $schedule->zone_name ?? 'Collection Zone' }}
                                        </h4>
                                        <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-3">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $schedule->collector->name ?? 'Unassigned Driver' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                                {{ $schedule->truck->name ?? $schedule->truck_name ?? 'No Truck Assigned' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="px-3 py-1 text-[11px] font-bold uppercase rounded-full {{ $schedule->status === 'completed' ? 'bg-green-100 text-green-700' : ($schedule->status === 'active' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $schedule->status ?? 'Pending' }}
                                    </span>
                                    <button
                                        class="p-2 text-slate-400 hover:text-amber-600 transition-colors rounded-lg hover:bg-amber-50">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 mb-1">No Schedules Found</h3>
                        <p class="text-sm text-slate-500">There are no collection schedules set for this week.</p>
                    </div>
                @endif
            </div>
        </div>
    @endsection <th class="px-6 py-3">Day</th>
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
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-200">Active</span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold border border-slate-200">Paused</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right flex justify-end gap-2">
                    <form method="POST" action="{{ route('dashboard.schedules.toggle', $sched->id) }}">
                        @csrf
                        <button type="submit"
                            class="text-xs text-slate-600 hover:text-slate-800 font-bold bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-lg transition-colors">
                            {{ $sched->is_active ? 'Pause' : 'Resume' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('dashboard.schedules.delete', $sched->id) }}"
                        onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-xs text-rose-600 hover:text-rose-800 font-bold bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-lg transition-colors">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No schedules defined yet for
                    this Barangay.</td>
            </tr>
        @endforelse
    </tbody>
    </table>
    </div>
    </div>

    </div>

    </div>
@endsection