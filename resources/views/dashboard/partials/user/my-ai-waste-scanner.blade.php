@extends('dashboard.layout')

@section('title', 'AI Waste Scanner')

@section('content')

    {{-- Header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-poppins text-2xl font-bold text-slate-900 mb-1">AI Waste Scanner</h1>
        <p class="text-sm text-slate-500">Snap a photo of your garbage and the AI will classify it and tell you exactly what to do with it.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- ── LEFT: Upload + Result ── --}}
        <div class="lg:col-span-3 flex flex-col gap-5">

            {{-- Upload Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Upload Waste Photo</h3>
                        <p class="text-xs text-slate-400">JPG, PNG up to 10MB</p>
                    </div>
                </div>

                <div class="p-6">
                    <form id="scanForm">
                        {{-- Drop zone --}}
                        <div id="dropZone"
                             class="relative border-2 border-dashed border-slate-200 rounded-xl p-8 text-center cursor-pointer hover:border-purple-400 hover:bg-purple-50/30 transition-all group mb-5">
                            <input type="file" name="image" id="imageInput" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            {{-- Preview --}}
                            <div id="previewWrap" class="hidden">
                                <img id="previewImg" src="" alt="Preview"
                                     class="mx-auto max-h-48 rounded-xl object-contain mb-3">
                                <p id="previewName" class="text-xs text-slate-500 font-medium"></p>
                                <p class="text-xs text-slate-400 mt-1">Click to change photo</p>
                            </div>

                            {{-- Default state --}}
                            <div id="dropDefault">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 group-hover:bg-purple-100 flex items-center justify-center mx-auto mb-3 transition-colors">
                                    <svg class="w-6 h-6 text-slate-400 group-hover:text-purple-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-700 mb-1">Drop your photo here</p>
                                <p class="text-xs text-slate-400">or click to browse files</p>
                            </div>
                        </div>

                        {{-- Collection point --}}
                        <div class="mb-5">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                                Collection Point <span class="text-slate-300 font-normal normal-case">(optional)</span>
                            </label>
                            <select name="collection_point_id"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all"
                                    style="appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 1rem center;background-size:1em;">
                                <option value="">— Not linked to a point —</option>
                                <option value="1">Camino Nuevo Drop-off (Zone 2)</option>
                                <option value="2">Canelar Drop-off (Zone 1)</option>
                                <option value="3">Tetuan Drop-off (Zone 1)</option>
                            </select>
                        </div>

                        {{-- Submit --}}
                        <button type="button" id="scanBtn"
                                class="w-full py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            Scan with AI
                        </button>
                    </form>
                </div>
            </div>

            {{-- AI Result Card (demo) --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden animate-slideUp" style="animation-delay: 0.15s;">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">AI Classification Result</h3>
                    </div>
                    <span class="text-[11px] text-slate-400">Today, 8:45 AM</span>
                </div>

                <div class="p-6">
                    <div class="flex gap-4">
                        {{-- Thumbnail --}}
                        <div class="w-20 h-20 rounded-xl bg-blue-50 border border-blue-100 flex-shrink-0 flex items-center justify-center text-3xl">
                            🧴
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <span class="text-xs font-semibold text-slate-400">Classified as</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                    Non-Biodegradable (Plastic)
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                This is a plastic bottle. Rinse it out and remove the cap before placing it in the
                                <strong class="text-blue-600">blue bin</strong>. Caps go in a separate recyclables bag.
                                Your next plastic collection is <strong class="text-slate-800">Monday and Wednesday</strong>.
                                You earned <strong class="text-green-600">+5 Eco-Points</strong> for this scan. ♻️
                            </p>
                        </div>
                    </div>

                    {{-- Bin color guide --}}
                    <div class="mt-5 pt-5 border-t border-slate-100 grid grid-cols-3 gap-3">
                        <div class="text-center p-3 rounded-xl bg-green-50 border border-green-100">
                            <div class="w-5 h-5 rounded-full bg-green-500 mx-auto mb-1.5"></div>
                            <p class="text-[10px] font-bold text-green-700 uppercase tracking-wide">Green Bin</p>
                            <p class="text-[10px] text-green-600 mt-0.5">Biodegradable</p>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-blue-50 border border-blue-200">
                            <div class="w-5 h-5 rounded-full bg-blue-500 mx-auto mb-1.5"></div>
                            <p class="text-[10px] font-bold text-blue-700 uppercase tracking-wide">Blue Bin</p>
                            <p class="text-[10px] text-blue-600 mt-0.5">Non-Biodegradable</p>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="w-5 h-5 rounded-full bg-slate-400 mx-auto mb-1.5"></div>
                            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wide">Grey Bin</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">Residual</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT: Stats + Tips + History ── --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            {{-- Points from scans --}}
            <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
                <div class="absolute -bottom-6 -right-6 w-32 h-32 rounded-full bg-white/[.03]"></div>
                <div class="absolute -top-6 -left-6 w-24 h-24 rounded-full bg-white/[.03]"></div>
                <div class="relative z-10">
                    <p class="text-slate-400 text-[11px] font-semibold uppercase tracking-widest mb-4">Points from Scanning</p>
                    <div class="flex items-end gap-2 mb-1">
                        <span class="text-5xl font-extrabold text-white leading-none">15</span>
                        <span class="text-green-400 font-semibold text-sm mb-1">pts earned</span>
                    </div>
                    <p class="text-slate-500 text-xs mb-5">from 3 total scans · +5 pts each</p>
                    <div class="w-full h-px bg-white/[.08] mb-4"></div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 text-[11px] mb-0.5">This week</p>
                            <p class="text-green-400 font-bold text-sm">3 scans</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-500 text-[11px] mb-0.5">All time</p>
                            <p class="text-slate-300 font-bold text-sm">3 scans</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- City ordinance tip --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 animate-slideUp" style="animation-delay: 0.15s;">
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-amber-800 mb-1">City Ordinance Reminder</p>
                        <p class="text-xs text-amber-700 leading-relaxed">Per EO KHYM 062-2025, waste must be properly segregated before placement at your collection point. Use this scanner to confirm before setting out your bins.</p>
                    </div>
                </div>
            </div>

            {{-- Scan History --}}
            <div class="bg-white rounded-2xl border border-slate-200 flex-1 animate-slideUp" style="animation-delay: 0.2s;">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-900">Scan History</h3>
                    <span class="text-[11px] text-slate-400">3 scans</span>
                </div>

                <div class="divide-y divide-slate-100">
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex-shrink-0 flex items-center justify-center text-xl border border-blue-100">🧴</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">Plastic Bottle</p>
                            <p class="text-[11px] text-slate-400">Today, 8:45 AM</p>
                        </div>
                        <span class="text-[11px] font-bold text-green-600 flex-shrink-0">+5 pts</span>
                    </div>
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-green-50 flex-shrink-0 flex items-center justify-center text-xl border border-green-100">🍌</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">Food Waste (Biodegradable)</p>
                            <p class="text-[11px] text-slate-400">Yesterday, 7:30 AM</p>
                        </div>
                        <span class="text-[11px] font-bold text-green-600 flex-shrink-0">+5 pts</span>
                    </div>
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex-shrink-0 flex items-center justify-center text-xl border border-slate-100">📦</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">Cardboard Box</p>
                            <p class="text-[11px] text-slate-400">May 18, 2026</p>
                        </div>
                        <span class="text-[11px] font-bold text-green-600 flex-shrink-0">+5 pts</span>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-slate-100 text-center">
                    <button class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">View all scans</button>
                </div>
            </div>

        </div>
    </div>

    <script>
        const input    = document.getElementById('imageInput');
        const preview  = document.getElementById('previewWrap');
        const defView  = document.getElementById('dropDefault');
        const prevImg  = document.getElementById('previewImg');
        const prevName = document.getElementById('previewName');

        input.addEventListener('change', () => {
            const file = input.files[0];
            if (!file) return;
            prevImg.src = URL.createObjectURL(file);
            prevName.textContent = file.name;
            preview.classList.remove('hidden');
            defView.classList.add('hidden');
        });
    </script>

@endsection