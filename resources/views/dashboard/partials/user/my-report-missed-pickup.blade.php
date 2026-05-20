@extends('dashboard.layout')

@section('title', 'Report Missed Pickup')

@section('content')

    {{-- Header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-poppins text-2xl font-bold text-slate-900 mb-1">Report Missed Pickup</h1>
        <p class="text-sm text-slate-500">Was your garbage not collected on schedule? Submit a report so your barangay can take action.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- ── LEFT: Report Form ── --}}
        <div class="lg:col-span-3 flex flex-col gap-5">

            {{-- Form Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-red-100 text-red-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Missed Pickup Report</h3>
                        <p class="text-xs text-slate-400">Fill in the details below</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Collection Point --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Collection Point <span class="text-red-400">*</span>
                        </label>
                        <select class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all"
                                style="appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 1rem center;background-size:1em;">
                            <option value="">— Select your collection point —</option>
                            <option value="1">Camino Nuevo Drop-off (Zone 2)</option>
                            <option value="2">Canelar Drop-off (Zone 1)</option>
                            <option value="3">Tetuan Drop-off (Zone 1)</option>
                        </select>
                    </div>

                    {{-- Date of missed pickup --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Date of Missed Pickup <span class="text-red-400">*</span>
                        </label>
                        <input type="date"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all">
                    </div>

                    {{-- Scheduled time --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Scheduled Collection Time
                        </label>
                        <input type="time"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Your area's scheduled time per City Ordinance No. 500</p>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Additional Notes
                        </label>
                        <textarea rows="3" placeholder="e.g. Garbage has been sitting out since 7 AM. Truck did not pass by."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all resize-none"></textarea>
                    </div>

                    {{-- Photo upload --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Photo Evidence <span class="text-slate-300 font-normal normal-case">(optional but recommended)</span>
                        </label>
                        <div id="photoDropZone"
                             class="relative border-2 border-dashed border-slate-200 rounded-xl p-6 text-center cursor-pointer hover:border-red-400 hover:bg-red-50/20 transition-all group">
                            <input type="file" id="photoInput" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <div id="photoPreviewWrap" class="hidden">
                                <img id="photoPreviewImg" src="" alt="Preview"
                                     class="mx-auto max-h-36 rounded-xl object-contain mb-2">
                                <p id="photoPreviewName" class="text-xs text-slate-500 font-medium"></p>
                                <p class="text-xs text-slate-400 mt-0.5">Click to change</p>
                            </div>

                            <div id="photoDropDefault">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-red-100 flex items-center justify-center mx-auto mb-2 transition-colors">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-600">Attach a photo of the uncollected garbage</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG up to 10MB</p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="button"
                            class="w-full py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-bold transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 shadow-sm shadow-red-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Submit Report
                    </button>

                </div>
            </div>

        </div>

        {{-- ── RIGHT: Info + History ── --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            {{-- What happens next --}}
            <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
                <div class="absolute -bottom-6 -right-6 w-32 h-32 rounded-full bg-white/[.03]"></div>
                <div class="relative z-10">
                    <p class="text-slate-400 text-[11px] font-semibold uppercase tracking-widest mb-5">What Happens Next</p>

                    <div class="flex flex-col gap-4">
                        <div class="flex gap-3 items-start">
                            <div class="w-6 h-6 rounded-full bg-white/10 border border-white/15 text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-0.5">Report submitted</p>
                                <p class="text-xs text-slate-400 leading-relaxed">Your report is logged with a timestamp and linked to your collection point.</p>
                            </div>
                        </div>
                        <div class="w-full h-px bg-white/[.06]"></div>
                        <div class="flex gap-3 items-start">
                            <div class="w-6 h-6 rounded-full bg-white/10 border border-white/15 text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-0.5">Barangay notified</p>
                                <p class="text-xs text-slate-400 leading-relaxed">Your barangay officer receives an alert and reviews the report on their dashboard.</p>
                            </div>
                        </div>
                        <div class="w-full h-px bg-white/[.06]"></div>
                        <div class="flex gap-3 items-start">
                            <div class="w-6 h-6 rounded-full bg-white/10 border border-white/15 text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-0.5">Action taken</p>
                                <p class="text-xs text-slate-400 leading-relaxed">The barangay acknowledges or resolves the report. You'll see the status update here.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Note --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 animate-slideUp" style="animation-delay: 0.15s;">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-amber-800 mb-1">Before submitting</p>
                        <p class="text-xs text-amber-700 leading-relaxed">Make sure you placed your garbage out at least 15 minutes before the scheduled time, as required by City Ordinance No. 500. Reports with photo evidence are prioritized.</p>
                    </div>
                </div>
            </div>

            {{-- My Reports History --}}
            <div class="bg-white rounded-2xl border border-slate-200 flex-1 animate-slideUp" style="animation-delay: 0.2s;">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">My Reports</h3>
                    <span class="text-[11px] text-slate-400">3 reports</span>
                </div>

                <div class="divide-y divide-slate-100">

                    {{-- Report 1 - Resolved --}}
                    <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between gap-3 mb-1">
                            <p class="text-sm font-semibold text-slate-800">Camino Nuevo Drop-off</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700 border border-green-200 flex-shrink-0">Resolved</span>
                        </div>
                        <p class="text-xs text-slate-400 mb-1">May 15, 2026 · 8:00 AM schedule</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Truck did not pass. Garbage was out since 7:30 AM.</p>
                    </div>

                    {{-- Report 2 - Acknowledged --}}
                    <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between gap-3 mb-1">
                            <p class="text-sm font-semibold text-slate-800">Camino Nuevo Drop-off</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200 flex-shrink-0">Acknowledged</span>
                        </div>
                        <p class="text-xs text-slate-400 mb-1">May 10, 2026 · 8:00 AM schedule</p>
                        <p class="text-xs text-slate-500 leading-relaxed">No collection for 2 consecutive days.</p>
                    </div>

                    {{-- Report 3 - Pending --}}
                    <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between gap-3 mb-1">
                            <p class="text-sm font-semibold text-slate-800">Camino Nuevo Drop-off</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 border border-slate-200 flex-shrink-0">Pending</span>
                        </div>
                        <p class="text-xs text-slate-400 mb-1">Today, 9:00 AM</p>
                        <p class="text-xs text-slate-500 leading-relaxed">Truck passed by but skipped our point.</p>
                    </div>

                </div>

                <div class="px-5 py-4 border-t border-slate-100 text-center">
                    <button class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">View all reports</button>
                </div>
            </div>

        </div>
    </div>

    {{-- JS: photo preview --}}
    <script>
        const photoInput   = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreviewWrap');
        const photoDefault = document.getElementById('photoDropDefault');
        const photoImg     = document.getElementById('photoPreviewImg');
        const photoName    = document.getElementById('photoPreviewName');

        photoInput.addEventListener('change', () => {
            const file = photoInput.files[0];
            if (!file) return;
            photoImg.src = URL.createObjectURL(file);
            photoName.textContent = file.name;
            photoPreview.classList.remove('hidden');
            photoDefault.classList.add('hidden');
        });
    </script>

@endsection