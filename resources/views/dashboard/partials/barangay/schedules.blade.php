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

    @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="add-schedule"
        class="bg-white rounded-2xl border border-slate-200 shadow-sm animate-slideUp mb-8 overflow-hidden"
        style="animation-delay: 0.15s;">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h3 class="font-bold text-slate-800">Add New Route Schedule</h3>
            <p class="text-sm text-slate-500 mt-1">Create a schedule for the barangay collection route.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('dashboard.schedules.store') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf

                <label class="block">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Day of week</span>
                    <select name="day_of_week" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition-all">
                        <option value="">Choose a day</option>
                        <option value="monday" {{ old('day_of_week') == 'monday' ? 'selected' : '' }}>Monday</option>
                        <option value="tuesday" {{ old('day_of_week') == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                        <option value="wednesday" {{ old('day_of_week') == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                        <option value="thursday" {{ old('day_of_week') == 'thursday' ? 'selected' : '' }}>Thursday</option>
                        <option value="friday" {{ old('day_of_week') == 'friday' ? 'selected' : '' }}>Friday</option>
                        <option value="saturday" {{ old('day_of_week') == 'saturday' ? 'selected' : '' }}>Saturday</option>
                        <option value="sunday" {{ old('day_of_week') == 'sunday' ? 'selected' : '' }}>Sunday</option>
                    </select>
                </label>

                <label class="block">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Collection time</span>
                    <input type="time" name="collection_time" value="{{ old('collection_time') }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition-all" />
                </label>

                <label class="block">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Frequency</span>
                    <select name="frequency" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition-all">
                        <option value="">Choose frequency</option>
                        <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="bi-weekly" {{ old('frequency') == 'bi-weekly' ? 'selected' : '' }}>Bi-weekly</option>
                    </select>
                </label>

                <button type="submit"
                    class="w-full px-4 py-3 bg-amber-500 text-white rounded-xl text-sm font-bold hover:bg-amber-600 transition-all">
                    Save Schedule
                </button>
            </form>
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

        <div class="p-0 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wide">
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
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No schedules
                                defined yet for this Barangay.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection