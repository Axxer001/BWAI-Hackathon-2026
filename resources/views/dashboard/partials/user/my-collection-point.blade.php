@extends('dashboard.layout')

@section('title', 'My Collection Point')

@section('content')
    <!-- Calculate Live Route Status & ETA in PHP -->
    @php
        $stopsAway = null;
        $etaMinutes = null;
        $statusText = 'Ongoing';
        $statusColor = 'text-emerald-400';
        $isUserPointCollected = false;

        if ($activeSession && $collectionPoint && $sessionPoints->isNotEmpty()) {
            $userPointIndex = null;
            $currentTargetIndex = null;

            foreach ($sessionPoints as $index => $sp) {
                if ($sp->garbage_point_id === $collectionPoint->id) {
                    $userPointIndex = $index;
                    if ($sp->status === 'collected') {
                        $isUserPointCollected = true;
                    }
                }
                if ($currentTargetIndex === null && $sp->status === 'pending') {
                    $currentTargetIndex = $index;
                }
            }

            if ($isUserPointCollected) {
                $statusText = 'Collected';
                $statusColor = 'text-slate-400';
            } elseif ($currentTargetIndex !== null && $userPointIndex !== null) {
                if ($currentTargetIndex < $userPointIndex) {
                    $stopsAway = $userPointIndex - $currentTargetIndex;
                    $etaMinutes = $stopsAway * 7; // Estimated 7 mins per stop
                    $statusText = $stopsAway . ' Stop' . ($stopsAway > 1 ? 's' : '') . ' Away';
                    $statusColor = 'text-sky-400';
                } elseif ($currentTargetIndex === $userPointIndex) {
                    $stopsAway = 0;
                    $etaMinutes = 0;
                    $statusText = 'Arrived';
                    $statusColor = 'text-emerald-400';
                } else {
                    $statusText = 'Passed your Point';
                    $statusColor = 'text-amber-400';
                }
            } elseif ($currentTargetIndex === null) {
                $statusText = 'Completed';
                $statusColor = 'text-emerald-400';
            }
        }
    @endphp

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map {
            height: 480px;
            width: 100%;
            border-radius: 16px;
            z-index: 1;
            border: 1px solid #e2e8f0;
        }
        .custom-popup .leaflet-popup-content-wrapper {
            background: #0f172a;
            color: #ffffff;
            border-radius: 12px;
            padding: 4px;
        }
        .custom-popup .leaflet-popup-tip {
            background: #0f172a;
        }
    </style>

    {{-- Page Header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-poppins text-2xl font-bold text-slate-900 mb-1">My Collection Point</h1>
        <p class="text-sm text-slate-500">Select and view your assigned GPS drop-off location and track live collection status.</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center gap-2 animate-slideUp">
            <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── MAP CARD ── --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col shadow-sm animate-slideUp" style="animation-delay: 0.1s;">
            
            @if($collectionPoint)
                {{-- Map header when point is assigned --}}
                <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 border border-blue-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">{{ $collectionPoint->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $collectionPoint->address ?? 'Address not specified' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <form action="{{ route('dashboard.change-point') }}" method="POST" onsubmit="return confirm('Are you sure you want to change your collection point?');">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg transition-all shadow-sm">
                                Change Point
                            </button>
                        </form>
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $collectionPoint->latitude }},{{ $collectionPoint->longitude }}" target="_blank"
                           class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-100 px-3 py-2 rounded-lg transition-all">
                            Get Directions ↗
                        </a>
                    </div>
                </div>

                {{-- Leaflet Map --}}
                <div class="p-4 flex-1">
                    <div id="map"></div>
                </div>

            @else
                {{-- Point is not assigned yet --}}
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60">
                    <h3 class="text-sm font-bold text-slate-900">Select a Collection Point</h3>
                    <p class="text-xs text-slate-500">Choose your closest garbage collection point from the map or list below.</p>
                </div>

                <div class="p-4 flex-1">
                    <div id="map"></div>
                </div>

                <!-- Assignment Form Panel (Initially hidden, revealed on point selection) -->
                <div id="selection-panel" class="hidden border-t border-slate-100 bg-slate-50 p-6 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h4 id="selected-point-name" class="text-sm font-bold text-slate-900">No point selected</h4>
                            <p id="selected-point-address" class="text-xs text-slate-500">Click on any marker on the map to select it</p>
                        </div>
                        <form action="{{ route('dashboard.assign-point') }}" method="POST">
                            @csrf
                            <input type="hidden" name="garbage_point_id" id="hidden-point-id" value="">
                            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
                                Confirm Collection Point
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div class="lg:col-span-1 flex flex-col gap-6 animate-slideUp" style="animation-delay: 0.2s;">

            {{-- Live Route Status --}}
            <div class="bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden shadow-sm">
                <div class="absolute -bottom-8 -right-8 w-40 h-40 rounded-full bg-white/[.03]"></div>
                <div class="absolute -top-6 -left-6 w-28 h-28 rounded-full bg-white/[.03]"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-5">
                        <span class="relative flex h-2.5 w-2.5">
                            @if($activeSession)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-400"></span>
                            @else
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                            @endif
                        </span>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Live Route Status</span>
                    </div>

                    @if($activeSession)
                        <p class="text-slate-400 text-xs mb-1">Current Status</p>
                        <div class="flex items-end gap-2 mb-1">
                            <span class="text-2xl font-extrabold tracking-tight {{ $statusColor }} leading-none">{{ $statusText }}</span>
                        </div>
                        
                        @if($etaMinutes !== null && $etaMinutes > 0)
                            <p class="text-slate-300 text-sm mt-3 flex items-center gap-2">
                                <span>⏱️</span> ETA: <strong class="text-white">{{ $etaMinutes }} mins</strong>
                            </p>
                        @elseif($etaMinutes === 0 && !$isUserPointCollected)
                            <p class="text-emerald-400 text-sm mt-3 flex items-center gap-2 font-bold animate-pulse">
                                <span>🚚</span> The truck is at your collection point now!
                            </p>
                        @elseif($isUserPointCollected)
                            <p class="text-slate-400 text-sm mt-3 flex items-center gap-2 font-bold">
                                <span>✅</span> Your waste was successfully collected!
                            </p>
                        @endif

                        <p class="text-slate-400 text-xs mt-4 pt-4 border-t border-white/10">
                            Collector: <span class="text-white font-semibold">{{ $activeSession->collector->full_name ?? 'Assigned Driver' }}</span>
                        </p>
                    @else
                        <p class="text-slate-400 text-xs mb-1">Current Status</p>
                        <div class="flex items-end gap-2 mb-1">
                            <span class="text-2xl font-bold tracking-tight text-white leading-none">No Active Route</span>
                        </div>
                        <p class="text-slate-400 text-sm mb-5">
                            There are no trucks currently collecting in your barangay.
                        </p>
                    @endif
                </div>
            </div>

            {{-- Barangay List / Points Selection (Shown only if no point assigned) --}}
            @if(!$collectionPoint)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col max-h-[350px]">
                    <h3 class="text-sm font-bold text-slate-900 mb-4">Available Points</h3>
                    <div class="space-y-2 overflow-y-auto pr-1 flex-1">
                        @forelse($barangayPoints as $point)
                            <button type="button" onclick="selectPointOnMap('{{ $point->id }}', {{ $point->latitude }}, {{ $point->longitude }}, '{{ addslashes($point->name) }}', '{{ addslashes($point->address) }}')" 
                                    class="w-full text-left p-3 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all flex items-start gap-3 group">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 group-hover:bg-blue-100/50 text-slate-500 group-hover:text-blue-600 flex items-center justify-center flex-shrink-0 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $point->name }}</p>
                                    <p class="text-[10px] text-slate-500 truncate">{{ $point->address }}</p>
                                </div>
                            </button>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-xs text-slate-400 font-medium">No collection points defined for your Barangay yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            {{-- Today's Requirements --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex-1 flex flex-col shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 mb-5">Today's Requirements</h3>

                <div class="space-y-5 flex-1">
                    @if($todaySchedule)
                        {{-- Scheduled window --}}
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0 border border-green-100">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium mb-0.5">Scheduled Window</p>
                                <p class="text-sm font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($todaySchedule->collection_time)->format('h:i A') }} 
                                    ({{ ucfirst($todaySchedule->frequency) }})
                                </p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Place bins out 15 mins before start.</p>
                            </div>
                        </div>

                        <div class="w-full h-px bg-slate-100"></div>

                        {{-- Accepted waste --}}
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0 border border-slate-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium mb-2">General Guidelines</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-lg border border-green-200">Biodegradable</span>
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-lg border border-blue-200">Recyclable</span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-2.5 flex items-center gap-1 font-medium">
                                    <svg class="w-3 h-3 flex-shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Please separate waste properly.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-full text-center py-6">
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mb-3 border border-slate-100">
                                <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-slate-700">No Collection Today</p>
                            <p class="text-[11px] text-slate-400 mt-1">There are no scheduled pickups for your barangay today.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Leaflet Script Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Leaflet Marker Icon Colors configuration
            const defaultIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const selectedIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const completedIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const skippedIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const truckIcon = L.divIcon({
                html: '<div style="font-size: 32px; line-height: 1; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.35));" class="animate-bounce">🚚</div>',
                className: 'truck-emoji-marker',
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });

            @if($collectionPoint)
                @if($activeSession && $sessionPoints->isNotEmpty())
                    // CASE 1: Active route in progress and user has point assigned. Show all points + route + truck
                    const map = L.map('map').setView([{{ $collectionPoint->latitude }}, {{ $collectionPoint->longitude }}], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    const sessionPoints = [
                        @foreach($sessionPoints as $point)
                        {
                            id: "{{ $point->garbage_point_id }}",
                            name: "{{ $point->garbagePoint->name }}",
                            lat: parseFloat("{{ $point->garbagePoint->latitude }}"),
                            lng: parseFloat("{{ $point->garbagePoint->longitude }}"),
                            status: "{{ $point->status }}",
                            order: parseInt("{{ $point->route_order }}")
                        },
                        @endforeach
                    ];

                    // Find index of first pending point (current target of truck)
                    let currentPendingIndex = -1;
                    for (let i = 0; i < sessionPoints.length; i++) {
                        if (sessionPoints[i].status === 'pending') {
                            currentPendingIndex = i;
                            break;
                        }
                    }

                    sessionPoints.forEach(function (point, index) {
                        let iconToUse = defaultIcon;
                        let popupText = "<strong>Point " + point.order + ": " + point.name + "</strong>";

                        if (point.id === "{{ $collectionPoint->id }}") {
                            iconToUse = selectedIcon;
                            popupText += "<br><span style='color:#10b981; font-weight:bold;'>★ Your Assigned Collection Point</span>";
                            if (point.status === 'collected') {
                                popupText += " (Collected)";
                            }
                        } else if (point.status === 'collected') {
                            iconToUse = completedIcon;
                            popupText += "<br><span style='color:#6b7280;'>Collected</span>";
                        } else if (point.status === 'skipped') {
                            iconToUse = skippedIcon;
                            popupText += "<br><span style='color:#ef4444;'>Skipped</span>";
                        }

                        // Add normal marker
                        const m = L.marker([point.lat, point.lng], { icon: iconToUse }).addTo(map);
                        m.bindPopup(popupText);

                        // If this checkpoint is where the truck is currently heading, render the bouncing Truck on top of it!
                        if (index === currentPendingIndex) {
                            const truckMarker = L.marker([point.lat, point.lng], { icon: truckIcon }).addTo(map);
                            truckMarker.bindPopup("<strong>🚚 Live Garbage Truck</strong><br>Next Destination: " + point.name).openPopup();
                        }
                    });

                    // Draw the route lines connecting points
                    const routeCoordinates = sessionPoints.map(p => [p.lat, p.lng]);
                    L.polyline(routeCoordinates, {
                        color: '#3b82f6',
                        weight: 4,
                        opacity: 0.6,
                        dashArray: '5, 10'
                    }).addTo(map);

                @else
                    // CASE 2: No active session, just show user's single green assigned point
                    const map = L.map('map').setView([{{ $collectionPoint->latitude }}, {{ $collectionPoint->longitude }}], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);

                    const marker = L.marker([{{ $collectionPoint->latitude }}, {{ $collectionPoint->longitude }}], { icon: selectedIcon }).addTo(map);
                    marker.bindPopup("<div class='custom-popup font-bold text-xs p-1'>Your Assigned Collection Point:<br>{{ $collectionPoint->name }}</div>").openPopup();
                @endif

            @else
                // CASE 3: Point is not assigned yet. Allow selection from map
                const points = @json($barangayPoints);
                const defaultCenter = points.length > 0 ? [points[0].latitude, points[0].longitude] : [6.9214, 122.0790];
                const map = L.map('map').setView(defaultCenter, 14);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                const markers = {};
                let currentSelectedId = null;

                points.forEach(function (point) {
                    const marker = L.marker([point.latitude, point.longitude], { icon: defaultIcon }).addTo(map);
                    markers[point.id] = marker;

                    marker.on('click', function () {
                        selectPointOnMap(point.id, point.latitude, point.longitude, point.name, point.address);
                    });
                });

                window.selectPointOnMap = function (id, lat, lng, name, address) {
                    if (currentSelectedId && markers[currentSelectedId]) {
                        markers[currentSelectedId].setIcon(defaultIcon);
                    }

                    if (markers[id]) {
                        markers[id].setIcon(selectedIcon);
                        currentSelectedId = id;
                    }

                    document.getElementById('hidden-point-id').value = id;
                    document.getElementById('selected-point-name').textContent = name;
                    document.getElementById('selected-point-address').textContent = address;
                    
                    const panel = document.getElementById('selection-panel');
                    panel.classList.remove('hidden');

                    map.setView([lat, lng], 16);

                    if (markers[id]) {
                        markers[id].bindPopup("<div class='font-bold text-xs p-1'>" + name + "</div>").openPopup();
                    }
                }
            @endif
        });
    </script>
@endsection