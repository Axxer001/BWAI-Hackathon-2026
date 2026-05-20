@extends('dashboard.layout')

@section('title', 'Report Illegal Dumping')

@section('content')
    {{-- Leaflet CSS for the map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-poppins text-2xl font-bold text-slate-900 mb-1">Report Illegal Dumping</h1>
        <p class="text-sm text-slate-500">Spotted garbage dumped in the wrong place? Report it with a photo and location so the barangay can act on it.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- ── LEFT: Form ── --}}
        <div class="lg:col-span-3 flex flex-col gap-5">

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6H8l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Violation Report</h3>
                        <p class="text-xs text-slate-400">Fill in the details of the illegal dumping</p>
                    </div>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Barangay --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Barangay where it occurred <span class="text-red-400">*</span>
                        </label>
                        <select class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 transition-all"
                                style="appearance:none;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 1rem center;background-size:1em;">
                            <option value="">— Select barangay —</option>
                            <option>Camino Nuevo</option>
                            <option>Canelar</option>
                            <option>Tetuan</option>
                            <option>Baliwasan</option>
                            <option>Calarian</option>
                            <option>Tumaga</option>
                            <option>Talon-Talon</option>
                            <option>Santa Maria</option>
                            <option>Pasonanca</option>
                        </select>
                    </div>

                    {{-- Address / Location description --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Exact Location / Address <span class="text-red-400">*</span>
                        </label>
                        <input type="text" placeholder="e.g. Near Purok 3 corner, beside the sari-sari store"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 transition-all">
                    </div>

                    {{-- Map Selection --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Pin Location on Map
                        </label>
                        {{-- z-0 ensures the map controls don't overlap dropdowns or headers --}}
                        <div id="interactiveMap" class="w-full h-64 bg-slate-100 border border-slate-200 rounded-xl mb-2 z-0 relative"></div>
                        <p class="text-[11px] text-slate-500 mb-1">Click on the map to place a pin, drag it to adjust, or use your GPS location below.</p>
                    </div>

                    {{-- GPS coordinates row --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                                Latitude
                            </label>
                            <input type="number" step="any" id="latInput" placeholder="6.9214" readonly
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                                Longitude
                            </label>
                            <input type="number" step="any" id="lngInput" placeholder="122.0790" readonly
                                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed outline-none">
                        </div>
                    </div>

                    {{-- Use my location button --}}
                    <button type="button" id="getLocationBtn"
                            class="flex items-center justify-center gap-2 text-xs font-semibold text-orange-600 hover:text-orange-800 bg-orange-50 hover:bg-orange-100 border border-orange-200 px-4 py-2.5 rounded-lg transition-all w-full lg:w-auto">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Use My Current GPS Location
                    </button>

                    <hr class="border-slate-100 my-2">

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Description <span class="text-red-400">*</span>
                        </label>
                        <textarea rows="3" placeholder="Describe what you saw — type of waste, approximate volume, how long it's been there, etc."
                                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 placeholder-slate-400 outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 transition-all resize-none"></textarea>
                    </div>

                    {{-- Photo upload --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                            Photo Evidence <span class="text-red-400">*</span>
                        </label>
                        <div id="photoDropZone"
                             class="relative border-2 border-dashed border-slate-200 rounded-xl p-6 text-center cursor-pointer hover:border-orange-400 hover:bg-orange-50/20 transition-all group">
                            <input type="file" id="photoInput" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <div id="photoPreviewWrap" class="hidden">
                                <img id="photoPreviewImg" src="" alt="Preview"
                                     class="mx-auto max-h-36 rounded-xl object-contain mb-2">
                                <p id="photoPreviewName" class="text-xs text-slate-500 font-medium"></p>
                                <p class="text-xs text-slate-400 mt-0.5">Click to change</p>
                            </div>

                            <div id="photoDropDefault">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-orange-100 flex items-center justify-center mx-auto mb-2 transition-colors">
                                    <svg class="w-5 h-5 text-slate-400 group-hover:text-orange-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-600">Attach a clear photo of the dumping site</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">JPG, PNG up to 10MB</p>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">A photo is required — reports without evidence may be dismissed.</p>
                    </div>

                    {{-- Submit --}}
                    <button type="button"
                            class="w-full py-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2 shadow-sm shadow-orange-200 mt-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Submit Violation Report
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
                                <p class="text-sm font-semibold text-white mb-0.5">Report logged</p>
                                <p class="text-xs text-slate-400 leading-relaxed">Your report is recorded with photo, coordinates, and timestamp in the system.</p>
                            </div>
                        </div>
                        <div class="w-full h-px bg-white/[.06]"></div>
                        <div class="flex gap-3 items-start">
                            <div class="w-6 h-6 rounded-full bg-white/10 border border-white/15 text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-0.5">Barangay reviews</p>
                                <p class="text-xs text-slate-400 leading-relaxed">The barangay officer sees it on their dashboard and marks it under review.</p>
                            </div>
                        </div>
                        <div class="w-full h-px bg-white/[.06]"></div>
                        <div class="flex gap-3 items-start">
                            <div class="w-6 h-6 rounded-full bg-white/10 border border-white/15 text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</div>
                            <div>
                                <p class="text-sm font-semibold text-white mb-0.5">Action & outcome</p>
                                <p class="text-xs text-slate-400 leading-relaxed">Report is either fined, dismissed, or resolved. You'll see the status update here.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Status legend --}}
                    <div class="mt-5 pt-5 border-t border-white/[.08] grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-400 flex-shrink-0"></span>
                            <span class="text-[11px] text-slate-400">Pending</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-400 flex-shrink-0"></span>
                            <span class="text-[11px] text-slate-400">Under Review</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-400 flex-shrink-0"></span>
                            <span class="text-[11px] text-slate-400">Fined</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-500 flex-shrink-0"></span>
                            <span class="text-[11px] text-slate-400">Dismissed</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Eco-points earned --}}
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 animate-slideUp" style="animation-delay: 0.15s;">
                <div class="flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-xl bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-green-800 mb-1">Earn +15 Eco-Points</p>
                        <p class="text-xs text-green-700 leading-relaxed">Every valid violation report that gets acknowledged earns you 15 Eco-Points. Help keep Zamboanga City clean and get rewarded.</p>
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

                    {{-- Fined --}}
                    <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-sm font-semibold text-slate-800 leading-tight">Illegal dump near Purok 3, Camino Nuevo</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700 border border-red-200 flex-shrink-0">Fined</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mb-1">May 15, 2026</p>
                        <p class="text-xs text-slate-500">Mixed household waste dumped on the road.</p>
                    </div>

                    {{-- Under Review --}}
                    <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-sm font-semibold text-slate-800 leading-tight">Garbage pile beside canal, Canelar</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 border border-blue-200 flex-shrink-0">Under Review</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mb-1">May 18, 2026</p>
                        <p class="text-xs text-slate-500">Large pile of construction debris near the canal.</p>
                    </div>

                    {{-- Pending --}}
                    <div class="px-5 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-sm font-semibold text-slate-800 leading-tight">Plastic waste dumped at empty lot, Tetuan</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 border border-slate-200 flex-shrink-0">Pending</span>
                        </div>
                        <p class="text-[11px] text-slate-400 mb-1">Today, 10:15 AM</p>
                        <p class="text-xs text-slate-500">Bags of plastic waste left on an empty lot.</p>
                    </div>

                </div>

                <div class="px-5 py-4 border-t border-slate-100 text-center">
                    <button class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">View all reports</button>
                </div>
            </div>

        </div>
    </div>

    {{-- Leaflet JS for Map Interaction --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // --- 1. Photo preview Logic ---
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

        // --- 2. Interactive Map Logic ---
        // Default coordinates: Zamboanga City
        const defaultLat = 6.9214;
        const defaultLng = 122.0790;

        const latInput = document.getElementById('latInput');
        const lngInput = document.getElementById('lngInput');

        // Initialize Map
        const map = L.map('interactiveMap').setView([defaultLat, defaultLng], 14);

        // Load OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add a draggable marker
        let marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        // Function to update input fields
        function updateCoordinates(lat, lng) {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
        }

        // Initialize fields with default
        updateCoordinates(defaultLat, defaultLng);

        // Update when marker is dragged
        marker.on('dragend', function (e) {
            const pos = marker.getLatLng();
            updateCoordinates(pos.lat, pos.lng);
        });

        // Move marker and update inputs when map is clicked
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateCoordinates(e.latlng.lat, e.latlng.lng);
        });

        // --- 3. Get current GPS location button ---
        document.getElementById('getLocationBtn').addEventListener('click', () => {
            if (!navigator.geolocation) {
                alert("Geolocation is not supported by your browser.");
                return;
            }

            // Optional: change button text or add a spinner here
            const btn = document.getElementById('getLocationBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Locating...';

            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                
                // Update map and inputs
                const newLatLng = new L.LatLng(lat, lng);
                marker.setLatLng(newLatLng);
                map.setView(newLatLng, 17); // zoom in closer
                updateCoordinates(lat, lng);

                btn.innerHTML = originalText;
            }, (error) => {
                alert("Unable to retrieve your location. Please check your browser permissions.");
                btn.innerHTML = originalText;
            });
        });
    </script>

@endsection