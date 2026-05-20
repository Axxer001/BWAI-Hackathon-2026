@extends('dashboard.layout')

@section('title', 'Report Missed Pickup')

@section('content')

    {{-- Header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-poppins text-2xl font-bold text-slate-900 mb-1">Report Missed Pickup</h1>
        <p class="text-sm text-slate-500">If your garbage was not collected during the scheduled route, submit the details so your barangay can review and act.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-semibold flex items-center gap-2 animate-slideUp">
            <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-semibold animate-slideUp">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Please fix the following errors:
            </div>
            <ul class="list-disc list-inside text-xs font-normal ml-7 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- ── LEFT: Report Form --}}
        <div class="lg:col-span-3 flex flex-col gap-5">

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-red-100 text-red-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Missed Pickup Report</h3>
                        <p class="text-xs text-slate-400">Submit the collection point and evidence from the missed route.</p>
                    </div>
                </div>

                <form action="{{ route('reports.missed.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ optional($activeSession)->id }}">

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Collection Point <span class="text-red-400">*</span>
                        </label>
                        <select name="collection_point_id" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all"
                            style="appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 1rem center;background-size:1em;">
                            <option value="">— Select your barangay collection point —</option>
                            @foreach($collectionPoints as $point)
                                <option value="{{ $point->id }}" {{ old('collection_point_id') == $point->id ? 'selected' : '' }}>
                                    {{ $point->name }}@if($point->address) · {{ $point->address }}@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Scheduled Session
                        </label>
                        @if($activeSession)
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-700">
                                <p class="font-semibold text-slate-900">{{ optional($activeSession->session_date)->format('F j, Y') }} · {{ $activeSession->scheduled_time ? \Carbon\Carbon::parse($activeSession->scheduled_time)->format('h:i A') : 'TBD' }}</p>
                                <p class="text-xs text-slate-500">Current route status: {{ ucfirst($activeSession->status) }}</p>
                            </div>
                        @else
                            <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-900">
                                No active collection session was found for your barangay. The latest session will be attached to this report if available.
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Additional Notes
                        </label>
                        <textarea name="notes" rows="4" placeholder="e.g. Garbage went out by 6:00 AM, truck did not arrive."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all resize-none">{{ old('notes') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Photo Evidence <span class="text-red-400">*</span>
                        </label>
                        <div id="photoDropZone"
                            class="relative border-2 border-dashed border-slate-200 rounded-xl p-6 text-center cursor-pointer hover:border-red-400 hover:bg-red-50/20 transition-all group">
                            <input type="file" name="photo" id="photoInput" accept="image/*" required
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
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-600">Attach a clear image of the missed pickup.</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG up to 5MB.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-bold transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 shadow-sm shadow-red-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Submit Report
                    </button>
                </form>
            </div>
        </div>

        {{-- ── RIGHT: Info + History --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
                <div class="absolute -bottom-6 -right-6 w-32 h-32 rounded-full bg-white/[.03]"></div>
                <div class="relative z-10">
                    <p class="text-slate-400 text-[11px] font-semibold uppercase tracking-widest mb-5">What Happens Next</p>
                    <div class="flex flex-col gap-4">
                        <div class="flex gap-3 items-start">
                            <div class="w-6 h-6 rounded-full bg-white/10 border border-white/15 text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-0.5">Report submitted</p>
                                <p class="text-xs text-slate-400 leading-relaxed">Your missed collection report is recorded and linked to the active route.</p>
                            </div>
                        </div>
                        <div class="w-full h-px bg-white/[.06]"></div>
                        <div class="flex gap-3 items-start">
                            <div class="w-6 h-6 rounded-full bg-white/10 border border-white/15 text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-0.5">Barangay reviews</p>
                                <p class="text-xs text-slate-400 leading-relaxed">Your barangay officer sees this report on their missed tickets dashboard.</p>
                            </div>
                        </div>
                        <div class="w-full h-px bg-white/[.06]"></div>
                        <div class="flex gap-3 items-start">
                            <div class="w-6 h-6 rounded-full bg-white/10 border border-white/15 text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-0.5">Action taken</p>
                                <p class="text-xs text-slate-400 leading-relaxed">The barangay updates status and resolves the missed pickup.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 animate-slideUp" style="animation-delay: 0.15s;">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-amber-800 mb-1">Before submitting</p>
                        <p class="text-xs text-amber-700 leading-relaxed">Ensure the photo clearly shows the uncollected waste and the collection point. This helps barangay staff verify the issue faster.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 flex-1 animate-slideUp" style="animation-delay: 0.2s;">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-bold text-slate-900">My Reports</h3>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">{{ $reports->count() }}</span>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($reports as $report)
                        @php
                            $status = strtolower($report->status);
                            $badgeClass = 'bg-slate-100 text-slate-500 border-slate-200';

                            if ($status === 'pending') {
                                $badgeClass = 'bg-amber-50 text-amber-600 border-amber-200';
                            } elseif ($status === 'resolved') {
                                $badgeClass = 'bg-green-50 text-green-600 border-green-200';
                            } elseif ($status === 'invalid') {
                                $badgeClass = 'bg-red-50 text-red-600 border-red-200';
                            }
                        @endphp

                        <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-3 mb-1">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $report->collectionPoint->name ?? 'Unknown collection point' }}</p>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border flex-shrink-0 {{ $badgeClass }}">{{ ucfirst($report->status) }}</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mb-1">{{ $report->created_at->diffForHumans() }}</p>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ \Illuminate\Support\Str::limit($report->notes ?? 'No notes provided.', 110) }}</p>
                        </div>
                    @empty
                        <div class="px-5 py-10 flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No reports yet</p>
                            <p class="text-xs text-slate-400 mt-1">Your missed pickup reports will appear here once submitted.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreviewWrap');
        const photoDefault = document.getElementById('photoDropDefault');
        const photoImg = document.getElementById('photoPreviewImg');
        const photoName = document.getElementById('photoPreviewName');

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
