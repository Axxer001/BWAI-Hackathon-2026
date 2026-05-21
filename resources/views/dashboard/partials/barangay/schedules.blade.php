@extends('dashboard.layout')

@section('title', 'Route Schedules')

@section('content')
    {{-- Leaflet for the View Route modal map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Route Schedules</h1> 
        <p class="text-sm text-slate-500">Manage and monitor daily garbage collection routes for your Barangay.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-bold flex items-center gap-2 animate-slideUp">
            <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── FORM SECTION ── --}}
    <div id="add-schedule" class="bg-white rounded-2xl border border-slate-200 shadow-sm animate-slideUp mb-8 overflow-visible" style="animation-delay: 0.1s;">

        {{-- Form Header --}}
        <div class="p-6 border-b border-slate-100 bg-slate-50 rounded-t-2xl flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7l6-3 5.447 2.724A1 1 0 0121 7.618v10.764a1 1 0 01-1.447.894L15 17l-6 3z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-800 text-lg">Create Route Schedule</h3>
                <p class="text-sm text-slate-500">Configure timings, assign vehicles and collector, and define the collection checkpoints.</p>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="p-6 md:p-8">
            <form method="POST" action="{{ route('dashboard.schedules.store') }}">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                    {{-- ── LEFT COLUMN: Timing, Frequency, Assets ── --}}
                    <div class="space-y-6">
                        <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px]">1</span>
                            Schedule Timing
                        </h4>

                        {{-- Route Name --}}
                        <div>
                            <label class="block">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Route / Schedule Name</span>
                                <input type="text" name="name" placeholder="e.g., Calarian Morning Commercial Run"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-amber-300 focus:bg-white transition-all" />
                            </label>
                        </div>

                        {{-- Frequency --}}
                        <div>
                            <label class="block">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Collection Frequency <span class="text-red-500">*</span></span>
                                <select name="frequency" id="frequencySelect" required
                                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-amber-300 focus:bg-white transition-all cursor-pointer">
                                    <option value="daily">Every Day</option>
                                    <option value="weekly" selected>Specific Days</option>
                                    <option value="bi-weekly">Every 2 Weeks</option>
                                    <option value="monthly">Every Month</option>
                                </select>
                            </label>
                        </div>

                        {{-- Days of Week --}}
                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3 block">Select Days <span class="text-red-500">*</span></span>
                            <div class="flex flex-wrap gap-2.5" id="days-container">
                                @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                                    <label class="day-label flex items-center gap-2 cursor-pointer bg-slate-50 border border-slate-200 px-3.5 py-2.5 rounded-xl transition-all has-[:checked]:bg-amber-50 has-[:checked]:border-amber-300 has-[:checked]:ring-1 has-[:checked]:ring-amber-300">
                                        <input type="checkbox" name="days_of_week[]" value="{{ $day }}" class="day-checkbox w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500">
                                        <span class="text-sm font-bold text-slate-700 capitalize">{{ substr($day, 0, 3) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Time --}}
                        <div>
                            <label class="block">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Collection Time <span class="text-red-500">*</span></span>
                                <input type="time" name="collection_time" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-amber-300 focus:bg-white transition-all" />
                            </label>
                        </div>


                    </div>

                    {{-- ── RIGHT COLUMN: Assets & Checkpoints ── --}}
                    <div class="space-y-6">
                        <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px]">2</span>
                            Assigned Assets & Checkpoints
                        </h4>

                        {{-- Truck --}}
                        <div>
                            <label class="block">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Assign Truck <span class="text-red-500">*</span></span>
                                <select name="truck_id" required
                                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-amber-300 focus:bg-white transition-all cursor-pointer">
                                    <option value="">-- Select Truck --</option>
                                    @foreach($trucks as $truck)
                                        <option value="{{ $truck->id }}">{{ $truck->plate_number }} &mdash; {{ $truck->capacity_tons }}t capacity</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        {{-- Collector --}}
                        <div>
                            <label class="block">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">Assign Collector <span class="text-red-500">*</span></span>
                                <select name="collector_id" required
                                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-2 focus:ring-amber-300 focus:bg-white transition-all cursor-pointer">
                                    <option value="">-- Select Collector --</option>
                                    @foreach($collectors as $collector)
                                        <option value="{{ $collector->id }}">{{ $collector->full_name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 block">
                                Collection Points <span class="text-red-500">*</span>
                            </span>

                            @if($collectionPoints->isEmpty())
                                <div class="p-3 rounded-xl bg-rose-50 border border-rose-100 text-sm text-rose-600 font-medium">
                                    No active collection points found.
                                </div>
                            @else
                                @php
                                    $grouped = $collectionPoints->groupBy(fn($p) => $p->barangay->name ?? 'General Zone');
                                @endphp

                                <div id="checkpoint-hidden-inputs"></div>

                                <div class="relative" id="checkpoint-dropdown-wrapper">
                                    <button type="button" id="checkpoint-trigger"
                                        class="w-full flex items-center justify-between px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-500 outline-none focus:ring-2 focus:ring-amber-300 focus:bg-white transition-all cursor-pointer hover:border-slate-300">
                                        <span id="checkpoint-trigger-label">-- Select Collection Points --</span>
                                        <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2 transition-transform" id="checkpoint-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div id="checkpoint-panel"
                                        class="hidden absolute z-50 mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden"
                                        style="max-height: 320px;">

                                        {{-- Search --}}
                                        <div class="p-2.5 border-b border-slate-100 bg-slate-50">
                                            <div class="relative">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                                                </svg>
                                                <input type="text" id="checkpoint-search" placeholder="Search checkpoints…"
                                                    class="w-full pl-8 pr-3 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-amber-300 transition-all placeholder-slate-400"/>
                                            </div>
                                        </div>

                                        {{-- List --}}
                                        <div class="overflow-y-auto" style="max-height: 240px;" id="checkpoint-list">
                                            @foreach($grouped as $groupName => $points)
                                                <div class="checkpoint-group" data-group="{{ strtolower($groupName) }}">
                                                    <div class="flex items-center gap-2 px-4 pt-3 pb-1.5">
                                                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">{{ $groupName }}</span>
                                                        <div class="flex-1 h-px bg-slate-100"></div>
                                                        <button type="button"
                                                            class="select-group-btn text-[10px] font-bold text-amber-600 hover:text-amber-700 transition-colors"
                                                            data-group-id="{{ Str::slug($groupName) }}">Select all</button>
                                                    </div>
                                                    @foreach($points as $point)
                                                        <label class="checkpoint-item flex items-start gap-3 px-4 py-2.5 cursor-pointer hover:bg-amber-50 transition-colors group"
                                                            data-name="{{ strtolower($point->name) }}"
                                                            data-address="{{ strtolower($point->address ?? '') }}"
                                                            data-group="{{ Str::slug($groupName) }}">
                                                            <input type="checkbox"
                                                                class="checkpoint-checkbox mt-0.5 w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-400 shrink-0"
                                                                value="{{ $point->id }}"
                                                                data-label="{{ $point->name }}"
                                                                data-group="{{ Str::slug($groupName) }}">
                                                            <div class="min-w-0">
                                                                <p class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 leading-tight">{{ $point->name }}</p>
                                                                @if($point->address)
                                                                    <p class="text-xs text-slate-400 mt-0.5 truncate">{{ \Illuminate\Support\Str::limit($point->address, 45) }}</p>
                                                                @endif
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Footer --}}
                                        <div class="border-t border-slate-100 bg-slate-50 px-4 py-2 flex items-center justify-between">
                                            <span class="text-xs text-slate-400" id="checkpoint-count-label">0 selected</span>
                                            <button type="button" id="clear-checkpoints"
                                                class="text-xs font-bold text-rose-500 hover:text-rose-700 transition-colors">Clear all</button>
                                        </div>
                                    </div>
                                </div>

                                <div id="checkpoint-tags" class="flex flex-wrap gap-1.5 mt-2.5 empty:hidden"></div>
                                <p id="checkpoint-error" class="hidden mt-1.5 text-xs text-rose-500 font-medium">Please select at least one checkpoint.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit"
                        class="w-full md:w-auto px-10 py-3.5 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Save & Activate Route
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── TABLE SECTION ── --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm animate-slideUp overflow-hidden" style="animation-delay: 0.2s;">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h3 class="font-bold text-slate-800">Weekly Route Masterlist</h3>
                <span class="text-xs font-semibold bg-white border border-slate-200 text-slate-500 px-3 py-1 rounded-lg shadow-sm">
                    {{ $schedules->count() }} Schedules
                </span>
            </div>

            {{-- Bulk Delete Action Button --}}
            <div id="bulk-delete-action" class="hidden animate-slideUp">
                <button type="submit" form="bulk-delete-form" onclick="return confirm('Are you sure you want to delete all selected schedules? This cannot be undone.');" 
                    class="text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-800 px-4 py-2 rounded-lg border border-rose-200 shadow-sm transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Selected (<span id="bulk-count">0</span>)
                </button>
            </div>
        </div>

        <div class="p-0 overflow-x-auto">
            <form id="bulk-delete-form" method="POST" action="{{ route('dashboard.schedules.bulk-delete') }}">
                @csrf
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 text-[10px] text-slate-400 uppercase font-bold tracking-wider">
                            <th class="px-6 py-4 w-12 text-center">
                                <input type="checkbox" id="select-all-schedules" class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500 cursor-pointer">
                            </th>
                            <th class="px-6 py-4">Route Name / Timing</th>
                            <th class="px-6 py-4">Assigned Assets</th>
                            <th class="px-6 py-4">Checkpoints</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                        @forelse($schedules as $sched)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $sched->id }}" class="schedule-checkbox w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500 cursor-pointer">
                                </td>
                                <td class="px-6 py-4">
                                    @if($sched->name)
                                        <p class="font-extrabold text-slate-900 text-sm tracking-tight mb-1">{{ $sched->name }}</p>
                                    @endif
                                    <p class="font-bold text-slate-800 capitalize text-xs flex items-center gap-1.5">
                                        @if(!$sched->name)
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        @endif
                                        {{ $sched->day_of_week === 'everyday' ? 'Every Day' : $sched->day_of_week }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5 font-medium">
                                        {{ date('h:i A', strtotime($sched->collection_time)) }}
                                        &middot; {{ ucfirst(str_replace('-', ' ', $sched->frequency)) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                            {{ $sched->truck->plate_number ?? 'No truck' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            {{ $sched->collector->full_name ?? 'No collector' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg border border-amber-200 shadow-sm">
                                        {{ $sched->collectionPoints->count() }} Points
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($sched->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase tracking-wider">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider">Paused</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                            onclick="openRouteModal(this)"
                                            data-name="{{ $sched->name ?? ($sched->day_of_week === 'everyday' ? 'Every Day' : ucfirst($sched->day_of_week)) }}"
                                            data-day="{{ $sched->day_of_week === 'everyday' ? 'Every Day' : ucfirst($sched->day_of_week) }}"
                                            data-time="{{ date('h:i A', strtotime($sched->collection_time)) }}"
                                            data-frequency="{{ ucfirst(str_replace('-', ' ', $sched->frequency)) }}"
                                            data-truck="{{ $sched->truck->plate_number ?? 'Unassigned' }}"
                                            data-collector="{{ $sched->collector->full_name ?? 'Unassigned' }}"
                                            data-points='@json($sched->collectionPoints)'
                                            class="text-xs text-blue-600 hover:text-blue-800 font-bold bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors border border-blue-200 shadow-sm">
                                            🗺️ View Route
                                        </button>
                                        <form method="POST" action="{{ route('dashboard.schedules.toggle', $sched->id) }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-slate-600 hover:text-slate-800 font-bold bg-white hover:bg-slate-100 px-3 py-1.5 rounded-lg transition-colors border border-slate-200 shadow-sm">
                                                {{ $sched->is_active ? 'Pause' : 'Resume' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.schedules.delete', $sched->id) }}" onsubmit="return confirm('Delete this schedule?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-bold bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors border border-rose-200 shadow-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                        <p class="text-slate-500 text-sm font-semibold">No schedules defined yet.</p>
                                        <p class="text-slate-400 text-xs mt-1">Create your first route using the form above.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    {{-- ── JavaScript ── --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Frequency → auto-check days
            const freqSelect    = document.getElementById('frequencySelect');
            const dayCheckboxes = document.querySelectorAll('.day-checkbox');
            const dayLabels     = document.querySelectorAll('.day-label');

            if (freqSelect) {
                freqSelect.addEventListener('change', function (e) {
                    if (e.target.value === 'daily') {
                        dayCheckboxes.forEach(cb => cb.checked = true);
                        dayLabels.forEach(l => l.classList.add('opacity-60', 'pointer-events-none'));
                    } else {
                        dayCheckboxes.forEach(cb => cb.checked = false);
                        dayLabels.forEach(l => l.classList.remove('opacity-60', 'pointer-events-none'));
                    }
                });
            }

            // Checkpoint multi-select dropdown
            const trigger       = document.getElementById('checkpoint-trigger');
            const panel         = document.getElementById('checkpoint-panel');
            const chevron       = document.getElementById('checkpoint-chevron');
            const searchInput   = document.getElementById('checkpoint-search');
            const tagsContainer = document.getElementById('checkpoint-tags');
            const hiddenInputs  = document.getElementById('checkpoint-hidden-inputs');
            const countLabel    = document.getElementById('checkpoint-count-label');
            const triggerLabel  = document.getElementById('checkpoint-trigger-label');
            const errorMsg      = document.getElementById('checkpoint-error');
            const clearBtn      = document.getElementById('clear-checkpoints');
            const wrapper       = document.getElementById('checkpoint-dropdown-wrapper');

            if (!trigger) return;

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                const isOpen = !panel.classList.contains('hidden');
                panel.classList.toggle('hidden', isOpen);
                chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
                if (!isOpen && searchInput) searchInput.focus();
            });

            document.addEventListener('click', function (e) {
                if (!wrapper.contains(e.target)) {
                    panel.classList.add('hidden');
                    chevron.style.transform = '';
                }
            });

            panel.addEventListener('click', e => e.stopPropagation());

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const q = this.value.trim().toLowerCase();
                    document.querySelectorAll('.checkpoint-group').forEach(group => {
                        let any = false;
                        group.querySelectorAll('.checkpoint-item').forEach(item => {
                            const match = !q || item.dataset.name.includes(q) || item.dataset.address.includes(q);
                            item.style.display = match ? '' : 'none';
                            if (match) any = true;
                        });
                        group.style.display = any ? '' : 'none';
                    });
                });
            }

            function syncSelections() {
                const checked = document.querySelectorAll('.checkpoint-checkbox:checked');

                hiddenInputs.innerHTML = '';
                checked.forEach(cb => {
                    const inp = document.createElement('input');
                    inp.type  = 'hidden';
                    inp.name  = 'collection_points[]';
                    inp.value = cb.value;
                    hiddenInputs.appendChild(inp);
                });

                tagsContainer.innerHTML = '';
                checked.forEach(cb => {
                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold rounded-lg';
                    tag.innerHTML = `${cb.dataset.label}
                        <button type="button" data-val="${cb.value}" class="remove-tag ml-0.5 text-amber-400 hover:text-rose-500 transition-colors leading-none">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>`;
                    tagsContainer.appendChild(tag);
                });

                const n = checked.length;
                countLabel.textContent  = `${n} selected`;
                triggerLabel.textContent = n === 0 ? '-- Select Collection Points --' : `${n} checkpoint${n > 1 ? 's' : ''} selected`;
                triggerLabel.classList.toggle('text-slate-700', n > 0);
                triggerLabel.classList.toggle('text-slate-500', n === 0);
                if (n > 0 && errorMsg) errorMsg.classList.add('hidden');
            }

            document.querySelectorAll('.checkpoint-checkbox').forEach(cb => cb.addEventListener('change', syncSelections));

            tagsContainer.addEventListener('click', function (e) {
                const btn = e.target.closest('.remove-tag');
                if (!btn) return;
                const cb = document.querySelector(`.checkpoint-checkbox[value="${btn.dataset.val}"]`);
                if (cb) { cb.checked = false; syncSelections(); }
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function () {
                    document.querySelectorAll('.checkpoint-checkbox').forEach(cb => cb.checked = false);
                    syncSelections();
                });
            }

            document.querySelectorAll('.select-group-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const boxes      = document.querySelectorAll(`.checkpoint-checkbox[data-group="${this.dataset.groupId}"]`);
                    const allChecked = [...boxes].every(cb => cb.checked);
                    boxes.forEach(cb => cb.checked = !allChecked);
                    this.textContent = allChecked ? 'Select all' : 'Deselect all';
                    syncSelections();
                });
            });

            const form = trigger.closest('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    if (!document.querySelectorAll('.checkpoint-checkbox:checked').length) {
                        e.preventDefault();
                        if (errorMsg) errorMsg.classList.remove('hidden');
                        panel.classList.remove('hidden');
                        chevron.style.transform = 'rotate(180deg)';
                        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                });
            }

            // ── Bulk Delete Logic ──
            const selectAllCheck = document.getElementById('select-all-schedules');
            const schedCheckboxes = document.querySelectorAll('.schedule-checkbox');
            const bulkActionDiv = document.getElementById('bulk-delete-action');
            const bulkCountSpan = document.getElementById('bulk-count');

            function syncBulkDeleteUI() {
                if (!selectAllCheck) return;
                const checkedCount = document.querySelectorAll('.schedule-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkActionDiv.classList.remove('hidden');
                    bulkCountSpan.textContent = checkedCount;
                } else {
                    bulkActionDiv.classList.add('hidden');
                }
                
                // Keep select all state updated
                selectAllCheck.checked = checkedCount === schedCheckboxes.length && schedCheckboxes.length > 0;
                selectAllCheck.indeterminate = checkedCount > 0 && checkedCount < schedCheckboxes.length;
            }

            if (selectAllCheck) {
                selectAllCheck.addEventListener('change', function () {
                    const isChecked = this.checked;
                    schedCheckboxes.forEach(cb => {
                        cb.checked = isChecked;
                    });
                    syncBulkDeleteUI();
                });
            }

            schedCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    syncBulkDeleteUI();
                });
            });

            syncSelections();
        });
    </script>

    {{-- ── VIEW ROUTE MODAL ── --}}
    <div id="route-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center px-4">
        {{-- Backdrop --}}
        <div id="route-modal-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm cursor-pointer transition-opacity duration-300 opacity-0" onclick="closeRouteModal()"></div>

        {{-- Panel --}}
        <div id="route-modal-dialog" class="relative z-10 bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full p-8 scale-95 opacity-0 duration-300">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Route Preview</p>
                    <h3 id="modal-title" class="font-bold text-slate-900 text-lg">Loading...</h3>
                    <div class="flex flex-wrap gap-3 mt-2" id="modal-meta">
                        {{-- filled by JS --}}
                    </div>
                </div>
                <button onclick="closeRouteModal()" class="ml-4 p-2 rounded-xl hover:bg-slate-100 transition-colors text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Map --}}
            <div id="route-map" style="height: 400px; z-index: 1;"></div>

            {{-- Checkpoint List --}}
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 max-h-40 overflow-y-auto">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Checkpoint Order</p>
                <ol id="modal-checkpoint-list" class="flex flex-wrap gap-2">
                    {{-- filled by JS --}}
                </ol>
            </div>
        </div>
    </div>

    <script>
        let routeMapInstance = null;

        function openRouteModal(btn) {
            const name      = btn.dataset.name;
            const day       = btn.dataset.day;
            const time      = btn.dataset.time;
            const freq      = btn.dataset.frequency;
            const truck     = btn.dataset.truck;
            const collector = btn.dataset.collector;
            const points    = JSON.parse(btn.dataset.points || '[]');

            // Title
            document.getElementById('modal-title').textContent = name || (day + ' Route');

            // Meta badges
            document.getElementById('modal-meta').innerHTML = `
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-sm">
                    🗓️ ${day} &middot; ${time}
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-sm">
                    🔁 ${freq}
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-sm">
                    🚚 ${truck}
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-lg shadow-sm">
                    👤 ${collector}
                </span>
            `;

            // Checkpoint list
            const ol = document.getElementById('modal-checkpoint-list');
            ol.innerHTML = points.length === 0
                ? '<li class="text-xs text-slate-400">No checkpoints assigned.</li>'
                : points.map((p, i) => `
                    <li class="inline-flex items-center gap-1.5 text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-800 px-2.5 py-1 rounded-lg">
                        <span class="w-4 h-4 rounded-full bg-amber-400 text-white flex items-center justify-center text-[9px] font-extrabold shrink-0">${i+1}</span>
                        ${p.name}
                    </li>`).join('');

            // Show modal
            document.getElementById('route-modal').classList.remove('hidden');
            document.getElementById('route-modal').classList.add('flex');
            // animate dialog appearance
            const dialog = document.getElementById('route-modal-dialog');
            dialog.classList.remove('scale-95', 'opacity-0');
            dialog.classList.add('scale-100', 'opacity-100');
            document.body.style.overflow = 'hidden';

            // Build map after a tick so the container is visible
            setTimeout(() => buildRouteMap(points), 80);
        }

        function buildRouteMap(points) {
            // Destroy previous instance
            if (routeMapInstance) {
                routeMapInstance.remove();
                routeMapInstance = null;
            }

            const center = points.length > 0
                ? [parseFloat(points[0].lat), parseFloat(points[0].lng)]
                : [6.9214, 122.0790];

            routeMapInstance = L.map('route-map').setView(center, 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(routeMapInstance);

            if (points.length === 0) return;

            const latlngs = [];

            points.forEach((p, i) => {
                const lat = parseFloat(p.lat);
                const lng = parseFloat(p.lng);
                latlngs.push([lat, lng]);

                // Numbered div icon
                const icon = L.divIcon({
                    className: '',
                    html: `<div style="
                        width:28px;height:28px;border-radius:50%;
                        background:#f59e0b;color:#fff;
                        display:flex;align-items:center;justify-content:center;
                        font-size:11px;font-weight:800;
                        border:2.5px solid #fff;
                        box-shadow:0 2px 8px rgba(0,0,0,0.25);
                    ">${i + 1}</div>`,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14]
                });

                L.marker([lat, lng], { icon })
                    .addTo(routeMapInstance)
                    .bindPopup(`<strong>#${i+1}: ${p.name}</strong>${p.address ? '<br><small style="color:#6b7280">' + p.address + '</small>' : ''}`);
            });

            // Route polyline
            L.polyline(latlngs, {
                color: '#3b82f6',
                weight: 4,
                opacity: 0.75,
                dashArray: '8, 10'
            }).addTo(routeMapInstance);

            // Fit bounds
            routeMapInstance.fitBounds(L.polyline(latlngs).getBounds(), { padding: [40, 40] });
        }

        function closeRouteModal() {
            document.getElementById('route-modal').classList.add('hidden');
            document.getElementById('route-modal').classList.remove('flex');
            // reset dialog animation
            const dialog = document.getElementById('route-modal-dialog');
            dialog.classList.remove('scale-100', 'opacity-100');
            dialog.classList.add('scale-95', 'opacity-0');
            document.body.style.overflow = '';
            if (routeMapInstance) {
                routeMapInstance.remove();
                routeMapInstance = null;
            }
        }

        // Close on Escape
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeRouteModal();
        });
    </script>
@endsection