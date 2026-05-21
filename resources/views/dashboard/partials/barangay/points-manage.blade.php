@extends('dashboard.layout')

@section('title', 'Collection Points Manager')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

    <style>
        .leaflet-container {
            cursor: crosshair !important;
        }
        .leaflet-dragging .leaflet-container {
            cursor: grabbing !important;
        }
    </style>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 animate-slideUp">
        <div>
            <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">📍 Collection Points Manager</h1>
            <p class="text-sm text-slate-500 font-medium">Designate waste drop-off zones, monitor capacities, and run routing ETAs.</p>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl mb-6 shadow-sm animate-slideUp" style="animation-delay: 0.05s;">
        <p class="text-xs text-slate-700 font-semibold leading-relaxed">
            <strong>How to use:</strong> Click anywhere on the map to create a new collection point. 
            Click on an existing blue marker to calculate the estimated time of arrival (ETA) from your current location.
        </p>
        <div id="eta-info" class="mt-2 text-sm font-bold text-emerald-800">Waiting for a destination...</div>
    </div>

    {{-- Map container --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-2 shadow-sm overflow-hidden mb-8 animate-slideUp" style="animation-delay: 0.1s;">
        <div id="map" class="w-full rounded-xl" style="height: 480px; z-index: 1;"></div>
    </div>

    {{-- Points Table List --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.2s;">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Registered Collection Points</h3>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider" id="points-count">0 Points</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 text-slate-500 text-[10px] uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Zone / Location</th>
                        <th class="px-6 py-4">Coordinates</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium" id="points-table-body">
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-xs font-semibold">Loading points data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- WRAP YOUR MODAL IN THIS PUSH DIRECTIVE --}}
    @push('modals')
        {{-- ═══════════ SMALL CREATE POINT MODAL ═══════════ --}}
        <div id="create-point-modal" class="fixed inset-0 z-[9999] hidden">
            
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm cursor-pointer transition-opacity duration-300" onclick="closeCreatePointModal()"></div>
            
            <div class="flex items-center justify-center min-h-screen px-4 pointer-events-none">
                <div class="bg-white rounded-3xl overflow-hidden shadow-2xl max-w-sm w-full p-6 relative pointer-events-auto transform transition-all animate-slideUp" style="animation-duration: 0.3s">
                    
                    <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-extrabold text-slate-900 mb-1">New Collection Point</h3>
                    <p class="text-xs text-slate-500 mb-5">Enter a descriptive name for this new GPS drop-off zone.</p>
                    
                    <input type="text" id="new-point-name" placeholder="e.g., Zone 2 Main Hub" 
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 outline-none focus:ring-2 focus:ring-emerald-500 transition-all mb-6"
                        onkeypress="if(event.key === 'Enter') submitNewPoint()">
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="closeCreatePointModal()" class="flex-1 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold transition-colors border border-slate-200">Cancel</button>
                        <button type="button" onclick="submitNewPoint()" class="flex-1 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition-colors shadow-sm">Save Point</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const barangayId = "{{ auth()->user()->barangay_id }}";

            // Initialize map (Zamboanga center)
            const map = L.map('map').setView([6.9214, 122.0790], 13);
            let routingControl = null;
            let userLocation = null;
            const markers = {};

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // 1. Get user current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    userLocation = L.latLng(position.coords.latitude, position.coords.longitude);

                    // User location red marker
                    L.circleMarker(userLocation, {
                        color: '#ef4444',
                        fillColor: '#ef4444',
                        fillOpacity: 0.8,
                        radius: 8
                    }).addTo(map).bindPopup("Your Current Location").openPopup();

                    map.panTo(userLocation);
                }, (error) => {
                    console.error("Geolocation error:", error);
                    // Fallback to Zamboanga Center
                    userLocation = L.latLng(6.9214, 122.0790);
                    L.circleMarker(userLocation, {
                        color: '#f59e0b',
                        fillColor: '#f59e0b',
                        fillOpacity: 0.8,
                        radius: 8
                    }).addTo(map).bindPopup("Fallback Location (Zamboanga Center)").openPopup();
                    
                    if (error.code === error.PERMISSION_DENIED) {
                        document.getElementById('eta-info').innerText = "Location permission denied. Using Zamboanga Center fallback.";
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        document.getElementById('eta-info').innerText = "GPS/Location services disabled in OS settings. Using Zamboanga Center fallback.";
                    } else if (error.code === error.TIMEOUT) {
                        document.getElementById('eta-info').innerText = "Location request timed out. Using Zamboanga Center fallback.";
                    } else {
                        document.getElementById('eta-info').innerText = "Location unavailable. Using Zamboanga Center fallback.";
                    }
                }, {
                    enableHighAccuracy: false,
                    timeout: 8000,
                    maximumAge: Infinity
                });
            } else {
                userLocation = L.latLng(6.9214, 122.0790);
                L.circleMarker(userLocation, {
                    color: '#f59e0b',
                    fillColor: '#f59e0b',
                    fillOpacity: 0.8,
                    radius: 8
                }).addTo(map).bindPopup("Fallback Location (Zamboanga Center)").openPopup();
                document.getElementById('eta-info').innerText = "Geolocation not supported. Using fallback location.";
            }

            // Load points
            function loadPoints() {
                fetch('/api/garbage-points')
                    .then(res => res.json())
                    .then(data => {
                        const tbody = document.getElementById('points-table-body');
                        tbody.innerHTML = '';

                        // Clear markers
                        Object.keys(markers).forEach(id => map.removeLayer(markers[id]));

                        document.getElementById('points-count').innerText = `${data.length} Points`;

                        if (data.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No collection points defined yet. Click on the map to add one!</td>
                                </tr>
                            `;
                            return;
                        }

                        data.forEach(point => {
                            // Add map marker
                            const marker = L.marker([point.latitude, point.longitude]).addTo(map);
                            marker.bindPopup(`<b>${point.name}</b><br><small style="color: #6b7280;">Click marker to route here</small>`);
                            
                            marker.on('click', function () {
                                calculateETA(marker.getLatLng());
                            });

                            markers[point.id] = marker;

                            // Add table row
                            const activeBadge = point.is_active 
                                ? `<span class="px-2.5 py-0.5 rounded-full bg-emerald-100 border border-emerald-200 text-[10px] text-emerald-800 font-bold uppercase">Active</span>`
                                : `<span class="px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] text-slate-500 font-bold uppercase">Disabled</span>`;

                            const tr = document.createElement('tr');
                            tr.className = 'hover:bg-slate-50/50 transition-colors';
                            tr.innerHTML = `
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">${point.name}</p>
                                    <p class="text-[10px] text-slate-400">${point.address || 'Barangay Area'}</p>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-500">
                                    ${parseFloat(point.latitude).toFixed(5)}, ${parseFloat(point.longitude).toFixed(5)}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${activeBadge}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="locatePoint(${point.latitude}, ${point.longitude})" class="text-[10px] font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-lg transition-colors">
                                            Center Map
                                        </button>
                                        <button onclick="togglePointStatus('${point.id}')" class="text-[10px] font-bold ${point.is_active ? 'bg-slate-100 hover:bg-slate-200 text-slate-700' : 'bg-emerald-600 hover:bg-emerald-700 text-white'} px-2.5 py-1 rounded-lg transition-colors">
                                            ${point.is_active ? 'Disable' : 'Enable'}
                                        </button>
                                    </div>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    });
            }

            // Variables to temporarily store coordinates when clicking the map
            let pendingLat = null;
            let pendingLng = null;

            // Trigger modal on map click
            map.on('click', function (e) {
                pendingLat = e.latlng.lat;
                pendingLng = e.latlng.lng;
                
                document.getElementById('new-point-name').value = ''; // Clear old input
                document.getElementById('create-point-modal').classList.remove('hidden');
                
                // Focus the input field automatically after the modal opens
                setTimeout(() => document.getElementById('new-point-name').focus(), 100);
            });

            // Modal logic: Close
            window.closeCreatePointModal = function() {
                document.getElementById('create-point-modal').classList.add('hidden');
                pendingLat = null;
                pendingLng = null;
            };

            // Modal logic: Submit
            window.submitNewPoint = function() {
                const nameInput = document.getElementById('new-point-name').value.trim();
                
                if (!nameInput) {
                    alert('Please enter a name for the collection point.');
                    return;
                }
                
                savePoint(pendingLat, pendingLng, nameInput);
                closeCreatePointModal(); // Hide modal after passing data to savePoint
            };

            // Execute the API fetch to save
            function savePoint(lat, lng, name) {
                fetch('/api/garbage-points', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng,
                        name: name,
                        barangay_id: barangayId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        loadPoints();
                    } else {
                        alert(data.message || "Error saving point.");
                    }
                })
                .catch(err => console.error("Error:", err));
            }

            // Calculate ETA
            function calculateETA(destinationLatLng) {
                if (!userLocation) {
                    alert("Still waiting for GPS location or permission was denied.");
                    return;
                }

                document.getElementById('eta-info').innerText = "Calculating route...";

                if (routingControl) {
                    map.removeControl(routingControl);
                }

                routingControl = L.Routing.control({
                    waypoints: [
                        userLocation,
                        destinationLatLng
                    ],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    show: false,
                    createMarker: function () { return null; },
                    lineOptions: {
                        styles: [{ color: '#3b82f6', opacity: 0.8, weight: 6 }]
                    }
                }).addTo(map);

                routingControl.on('routesfound', function (e) {
                    const routes = e.routes;
                    const summary = routes[0].summary;
                    const distanceKm = (summary.totalDistance / 1000).toFixed(2);
                    const timeMinutes = Math.max(1, Math.round(summary.totalTime / 60));

                    document.getElementById('eta-info').innerHTML =
                        `🛣️ Distance: <strong>${distanceKm} km</strong> &nbsp;|&nbsp; ⏱️ Estimated Time: <strong>~${timeMinutes} mins</strong>`;
                });

                routingControl.on('routingerror', function (e) {
                    document.getElementById('eta-info').innerText = "Error calculating route. Try another point.";
                    console.error("Routing Error:", e);
                });
            }

            // Center map helper
            window.locatePoint = function(lat, lng) {
                map.setView([lat, lng], 16);
            };

            // Toggle active status
            window.togglePointStatus = function(id) {
                fetch(`/api/garbage-points/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        loadPoints();
                    } else {
                        alert(res.message || 'Error updating point status.');
                    }
                });
            };

            // Initial load
            loadPoints();
        });
    </script>
@endsection