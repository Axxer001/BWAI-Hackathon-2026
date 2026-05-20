@extends('dashboard.layout')

@section('title', 'AI Waste Scanner')

@section('content')

    {{-- Header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">AI Waste Scanner</h1>
        <p class="text-sm text-slate-500 font-medium">Snap a photo of your waste item, and our AI will classify it and provide precise segregation guidelines.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        {{-- ── LEFT: Upload + Result ── --}}
        <div class="lg:col-span-3 flex flex-col gap-6">

            {{-- Upload Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Upload Waste Photo</h3>
                        <p class="text-[11px] text-slate-400 font-semibold">JPG, PNG, WEBP up to 5MB</p>
                    </div>
                </div>

                <div class="p-6">
                    <form id="scanForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        {{-- Drop zone --}}
                        <div id="dropZone"
                             class="relative border-2 border-dashed border-slate-200 rounded-xl p-8 text-center cursor-pointer hover:border-purple-400 hover:bg-purple-50/30 transition-all group mb-5">
                            <input type="file" name="image" id="imageInput" accept="image/*" required
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            {{-- Preview --}}
                            <div id="previewWrap" class="hidden">
                                <img id="previewImg" src="" alt="Preview"
                                     class="mx-auto max-h-48 rounded-xl object-contain mb-3 border border-slate-100 shadow-sm">
                                <p id="previewName" class="text-xs text-slate-500 font-bold truncate max-w-xs mx-auto"></p>
                                <p class="text-[10px] text-slate-400 mt-1">Click or drag to replace photo</p>
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
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                                Link to Collection Point <span class="text-slate-300 font-normal normal-case">(optional)</span>
                            </label>
                            <select name="collection_point_id"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 font-semibold outline-none focus:ring-2 focus:ring-purple-300 focus:border-purple-400 transition-all"
                                    style="appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 1rem center;background-size:1em;">
                                <option value="">— Not linked to a point —</option>
                                @foreach($collectionPoints as $cp)
                                    <option value="{{ $cp->id }}">{{ $cp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" id="scanBtn"
                                class="w-full py-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold shadow-lg shadow-purple-500/20 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg id="scanIcon" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <svg id="loadingIcon" class="hidden animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span id="btnText">Scan with AI</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Error Message Alert --}}
            <div id="errorAlert" class="hidden p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-800 text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span id="errorText">An error occurred during assessment.</span>
            </div>

            {{-- AI Result Card --}}
            <div id="resultCard" class="hidden bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">AI Classification Result</h3>
                    </div>
                    <span class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Just Now</span>
                </div>

                <div class="p-6">
                    <div class="flex gap-5 flex-col md:flex-row">
                        {{-- Thumbnail --}}
                        <div id="resultThumbnail" class="w-24 h-24 rounded-xl bg-blue-50 border border-blue-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                            <img id="resultImage" class="w-full h-full object-cover" src="" alt="Assessed Waste">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                <span class="text-xs font-bold text-slate-400">Classified as</span>
                                <span id="resultCategory" class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                    Non-Biodegradable
                                </span>
                            </div>
                            <h4 id="resultName" class="font-bold text-slate-900 text-base mb-1"></h4>
                            <p id="resultAdvice" class="text-sm text-slate-600 leading-relaxed"></p>
                            <p class="text-xs text-emerald-600 font-bold mt-3 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                You earned +5 Eco-Points for this scan!
                            </p>
                        </div>
                    </div>

                    {{-- Bin color guide --}}
                    <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-3 gap-3">
                        <div class="text-center p-3 rounded-xl bg-green-50/50 border border-green-100">
                            <div class="w-4 h-4 rounded-full bg-green-500 mx-auto mb-1.5"></div>
                            <p class="text-[10px] font-extrabold text-green-700 uppercase tracking-wider">Green Bin</p>
                            <p class="text-[10px] text-green-600 font-medium">Biodegradable</p>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-blue-50/50 border border-blue-100">
                            <div class="w-4 h-4 rounded-full bg-blue-500 mx-auto mb-1.5"></div>
                            <p class="text-[10px] font-extrabold text-blue-700 uppercase tracking-wider">Blue Bin</p>
                            <p class="text-[10px] text-blue-600 font-medium">Non-Biodegradable</p>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="w-4 h-4 rounded-full bg-slate-400 mx-auto mb-1.5"></div>
                            <p class="text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">Grey Bin</p>
                            <p class="text-[10px] text-slate-500 font-medium">Residual / Special</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT: Stats + Tips + History ── --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Points from scans --}}
            <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden shadow-md animate-slideUp" style="animation-delay: 0.1s;">
                <div class="absolute -bottom-6 -right-6 w-32 h-32 rounded-full bg-white/[.03]"></div>
                <div class="absolute -top-6 -left-6 w-24 h-24 rounded-full bg-white/[.03]"></div>
                <div class="relative z-10">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-4">Points from Scanning</p>
                    <div class="flex items-end gap-2 mb-1">
                        <span id="pointsDisplay" class="text-5xl font-extrabold text-white leading-none">{{ $totalPoints }}</span>
                        <span class="text-green-400 font-bold text-sm mb-1">pts earned</span>
                    </div>
                    <p class="text-slate-500 text-xs mb-5">from <span id="scansCountDisplay">{{ $scans->count() }}</span> total scans · +5 pts each</p>
                    <div class="w-full h-px bg-white/[.08] mb-4"></div>
                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <p class="text-slate-500 mb-0.5">Scans Count</p>
                            <p class="text-green-400 font-bold">{{ $scans->count() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-500 mb-0.5">Eco-Points Balance</p>
                            <p class="text-slate-300 font-bold">{{ auth()->user()->eco_points ?? 0 }} pts</p>
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
                        <p class="text-xs font-bold text-amber-800 mb-1">City Ordinance Segregation</p>
                        <p class="text-xs text-amber-700 leading-relaxed font-semibold">Per EO KHYM 062-2025, waste must be properly segregated before placement at your collection point. Use this scanner to confirm before setting out your bins.</p>
                    </div>
                </div>
            </div>

            {{-- Scan History --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col animate-slideUp" style="animation-delay: 0.2s;">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900">Scan History</h3>
                    <span id="historyHeaderCount" class="text-[11px] text-slate-400 font-bold">{{ $scans->count() }} scans</span>
                </div>

                <div id="historyList" class="divide-y divide-slate-100 max-h-[300px] overflow-y-auto">
                    @forelse($scans as $scan)
                        <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 flex-shrink-0 flex items-center justify-center text-lg border border-purple-100">
                                🗑️
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $scan->ai_classification }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $scan->created_at->format('M d, h:i A') }}</p>
                            </div>
                            <span class="text-xs font-bold text-green-600 flex-shrink-0">+5 pts</span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs font-semibold">
                            No scans logged yet. Try scanning your first item!
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form         = document.getElementById('scanForm');
            const input        = document.getElementById('imageInput');
            const preview      = document.getElementById('previewWrap');
            const defView      = document.getElementById('dropDefault');
            const prevImg      = document.getElementById('previewImg');
            const prevName     = document.getElementById('previewName');
            
            const scanBtn      = document.getElementById('scanBtn');
            const scanIcon     = document.getElementById('scanIcon');
            const loadingIcon  = document.getElementById('loadingIcon');
            const btnText      = document.getElementById('btnText');
            
            const resultCard     = document.getElementById('resultCard');
            const resultImage    = document.getElementById('resultImage');
            const resultName     = document.getElementById('resultName');
            const resultCategory = document.getElementById('resultCategory');
            const resultAdvice   = document.getElementById('resultAdvice');
            
            const errorAlert     = document.getElementById('errorAlert');
            const errorText      = document.getElementById('errorText');

            const pointsDisplay       = document.getElementById('pointsDisplay');
            const scansCountDisplay   = document.getElementById('scansCountDisplay');
            const historyHeaderCount  = document.getElementById('historyHeaderCount');
            const historyList         = document.getElementById('historyList');

            // Handle preview changes
            input.addEventListener('change', () => {
                const file = input.files[0];
                if (!file) return;
                prevImg.src = URL.createObjectURL(file);
                prevName.textContent = file.name;
                preview.classList.remove('hidden');
                defView.classList.add('hidden');
                errorAlert.classList.add('hidden');
            });

            // Submit scanner via AJAX
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!input.files[0]) {
                    showError("Please select or drop a waste photo first.");
                    return;
                }

                // Show loading spinner
                setLoading(true);
                errorAlert.classList.add('hidden');
                resultCard.classList.add('hidden');

                const formData = new FormData(form);

                fetch('/api/assess-waste', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(res => {
                    setLoading(false);
                    if (res.status === 200 && res.body.success) {
                        const data = res.body.data;
                        
                        // Populate AI Result Card
                        resultImage.src = data.image_url;
                        resultName.textContent = data.name;
                        resultCategory.textContent = data.category;
                        resultAdvice.textContent = data.preparation_advice;
                        
                        // Apply styling class depending on category
                        resultCategory.className = "px-3 py-1 rounded-full text-xs font-bold border " + getCategoryClasses(data.category);
                        resultCard.classList.remove('hidden');

                        // Update stats displays
                        const oldPoints = parseInt(pointsDisplay.textContent);
                        const oldScans = parseInt(scansCountDisplay.textContent);
                        pointsDisplay.textContent = oldPoints + 5;
                        scansCountDisplay.textContent = oldScans + 1;
                        historyHeaderCount.textContent = (oldScans + 1) + " scans";

                        // Prepend to scan history list
                        const timestampString = new Date().toLocaleDateString('en-US', { month: 'short', day: '2-digit' }) + ", " + new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                        const historyItem = `
                            <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 transition-colors animate-slideUp">
                                <div class="w-10 h-10 rounded-lg bg-purple-50 flex-shrink-0 flex items-center justify-center text-lg border border-purple-100">
                                    🗑️
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-800 truncate">${data.name}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">${timestampString}</p>
                                </div>
                                <span class="text-xs font-bold text-green-600 flex-shrink-0">+5 pts</span>
                            </div>
                        `;
                        
                        // Remove empty state message if present
                        if (historyList.textContent.includes("No scans logged yet")) {
                            historyList.innerHTML = historyItem;
                        } else {
                            historyList.insertAdjacentHTML('afterbegin', historyItem);
                        }

                        // Clear input form preview
                        form.reset();
                        preview.classList.add('hidden');
                        defView.classList.remove('hidden');
                    } else {
                        showError(res.body.message || "An assessment error occurred. Please check Gemini API config.");
                    }
                })
                .catch(error => {
                    setLoading(false);
                    showError("Connection failed. Check your API server config.");
                    console.error(error);
                });
            });

            function setLoading(isLoading) {
                if (isLoading) {
                    scanBtn.disabled = true;
                    scanIcon.classList.add('hidden');
                    loadingIcon.classList.remove('hidden');
                    btnText.textContent = "Analyzing Waste with AI...";
                } else {
                    scanBtn.disabled = false;
                    scanIcon.classList.remove('hidden');
                    loadingIcon.classList.add('hidden');
                    btnText.textContent = "Scan with AI";
                }
            }

            function showError(msg) {
                errorText.textContent = msg;
                errorAlert.classList.remove('hidden');
            }

            function getCategoryClasses(category) {
                const cat = category.toLowerCase();
                if (cat.includes('biodegradable') && !cat.includes('non')) {
                    return "bg-green-100 text-green-700 border-green-200";
                } else if (cat.includes('non-biodegradable')) {
                    return "bg-blue-100 text-blue-700 border-blue-200";
                } else if (cat.includes('recyclable')) {
                    return "bg-purple-100 text-purple-700 border-purple-200";
                } else {
                    return "bg-amber-100 text-amber-700 border-amber-200";
                }
            }
        });
    </script>

@endsection