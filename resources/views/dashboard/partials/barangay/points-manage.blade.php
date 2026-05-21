@extends('dashboard.layout')

@section('title', 'Collection Points Manager')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .leaflet-container { cursor: crosshair !important; }
        .leaflet-dragging .leaflet-container { cursor: grabbing !important; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        .truck-bounce { animation: bounce 1s ease-in-out infinite; }
    </style>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 animate-slideUp">
        <div>
            <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">ðŸ“ Collection Points Manager</h1>
            <p class="text-sm text-slate-500 font-medium">Designate waste drop-off zones, monitor capacities, and track live collection progress.</p>
        </div>
    </div>

    {{-- Live Session Banner --}}
    <div id="live-banner" class="hidden mb-6 animate-slideUp">
        <div class="bg-emerald-600 text-white rounded-2xl px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-lg shadow-emerald-600/25">
            <div class="flex items-center gap-4">
                <div class="text-3xl truck-bounce">ðŸšš</div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-200 mb-0.5">Active Collection Session</p>
                    <p id="banner-collector" class="font-bold text-lg leading-tight">Loading...</p>
                    <p id="banner-schedule" class="text-xs text-emerald-200 font-medium mt-0.5"></p>
                </div>
            </div>
            <div class="flex gap-6 shrink-0">
                <div class="text-center">
                    <p id="banner-collected" class="text-2xl font-extrabold">0</p>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-emerald-200">Collected</p>
                </div>
                <div class="text-center">
                    <p id="banner-pending" class="text-2xl font-extrabold">0</p>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-emerald-200">Pending</p>
                </div>
                <div class="text-center">
                    <p id="banner-skipped" class="text-2xl font-extrabold">0</p>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-emerald-200">Skipped</p>
                </div>
            </div>
        </div>
    </div>

    {{-- No-session Info Panel --}}
    <div id="no-session-info" class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl mb-6 shadow-sm animate-slideUp" style="animation-delay: 0.05s;">
        <p class="text-xs text-slate-700 font-semibold leading-relaxed">
            <strong>How to use:</strong> Click anywhere on the map to create a new collection point. 
            Click on an existing marker to calculate the estimated time of arrival (ETA) from your current location.
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

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const barangayId = "{{ auth()->user()->barangay_id }}";

            // â”€â”€ Icon Definitions â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            const defaultIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });
            const completedIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });
            const skippedIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });
            const pendingIcon = new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });
            const truckIcon = L.divIcon({
                html: '<div class="truck-bounce" style="font-size:36px;line-height:1;filter:drop-shadow(0 3px 6px rgba(0,0,0,0.35));">ðŸšš</div>',
                className: '',
                iconSize: [36, 36],
                iconAnchor: [18, 36]
            });

            // â”€â”€ Map Init â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            const map = L.map('map').setView([6.9214, 122.0790], 13);
            let routingControl = null;
            let userLocation = null;
            let truckMarkerLayer = null;
            let routePolyline = null;
            const markers = {};

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Â© OpenStreetMap contributors', maxZoom: 19
            }).addTo(map);

            // â”€â”€ Geolocation â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    userLocation = L.latLng(pos.coords.latitude, pos.coords.longitude);
                    L.circleMarker(userLocation, { color: '#ef4444', fillColor: '#ef4444', fillOpacity: 0.85, radius: 8 })
                        .addTo(map).bindPopup('Your Current Location').openPopup();
                    map.panTo(userLocation);
                }, err => {
                    userLocation = L.latLng(6.9214, 122.0790);
                    document.getElementById('eta-info').innerText = 'Location unavailable. Using Zamboanga Center fallback.';
                }, { enableHighAccuracy: false, timeout: 8000, maximumAge: Infinity });
            }

            // â”€â”€ Clear live markers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            function clearLiveOverlay() {
                if (truckMarkerLayer) { map.removeLayer(truckMarkerLayer); truckMarkerLayer = null; }
                if (routePolyline) { map.removeLayer(routePolyline); routePolyline = null; }
            }

            // â”€â”€ Load Points (static, no session) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            function loadPoints() {
                fetch('/api/garbage-points')
                    .then(r => r.json())
                    .then(data => {
                        const tbody = document.getElementById('points-table-body');
                        tbody.innerHTML = '';
                        Object.keys(markers).forEach(id => map.removeLayer(markers[id]));
                        for (const id in markers) delete markers[id];

                        document.getElementById('points-count').innerText = `${data.length} Points`;

                        if (data.length === 0) {
                            tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No collection points defined yet. Click on the map to add one!</td></tr>`;
                            return;
                        }

                        data.forEach(point => {
                            const marker = L.marker([point.latitude, point.longitude], { icon: defaultIcon }).addTo(map);
                            marker.bindPopup(`<b>${point.name}</b><br><small style="color:#6b7280;">Click marker to route here</small>`);
                            marker.on('click', () => calculateETA(marker.getLatLng()));
                            markers[point.id] = marker;

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
                                <td class="px-6 py-4 whitespace-nowrap">${activeBadge}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="locatePoint(${point.latitude}, ${point.longitude})" class="text-[10px] font-bold bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-lg transition-colors">Center Map</button>
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

            // â”€â”€ Fetch & render live session â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            function fetchLiveSession() {
                fetch('/api/barangay/live-tracking')
                    .then(r => r.json())
                    .then(sessions => {
                        clearLiveOverlay();

                        if (!sessions || sessions.length === 0) {
                            // No active session â€” show static map
                            document.getElementById('live-banner').classList.add('hidden');
                            document.getElementById('no-session-info').classList.remove('hidden');
                            loadPoints();
                            return;
                        }

                        // Active session found
                        const session = sessions[0];
                        const pts = session.session_points || [];

                        document.getElementById('live-banner').classList.remove('hidden');
                        document.getElementById('no-session-info').classList.add('hidden');

                        const collectorName = session.collector ? session.collector.full_name : 'Collector';
                        document.getElementById('banner-collector').textContent = 'ðŸšš ' + collectorName + ' is collecting now';
                        document.getElementById('banner-schedule').textContent = `Session started Â· ${pts.length} checkpoints`;

                        const collected = pts.filter(p => p.status === 'collected').length;
                        const pending   = pts.filter(p => p.status === 'pending').length;
                        const skipped   = pts.filter(p => p.status === 'skipped').length;
                        document.getElementById('banner-collected').textContent = collected;
                        document.getElementById('banner-pending').textContent   = pending;
                        document.getElementById('banner-skipped').textContent   = skipped;

                        // Clear existing static markers
                        Object.keys(markers).forEach(id => map.removeLayer(markers[id]));
                        for (const id in markers) delete markers[id];

                        // Plot session points
                        let firstPendingIndex = -1;
                        const latlngs = [];

                        pts.forEach((sp, i) => {
                            const gp  = sp.garbage_point;
                            if (!gp) return;

                            const lat = parseFloat(gp.latitude);
                            const lng = parseFloat(gp.longitude);
                            latlngs.push([lat, lng]);

                            let icon = pendingIcon;
                            let statusLabel = '<span style="color:#3b82f6">â³ Pending</span>';

                            if (sp.status === 'collected') {
                                icon = completedIcon;
                                statusLabel = '<span style="color:#6b7280">âœ… Collected</span>';
                            } else if (sp.status === 'skipped') {
                                icon = skippedIcon;
                                statusLabel = '<span style="color:#ef4444">âš ï¸ Skipped</span>';
                            } else if (firstPendingIndex === -1) {
                                firstPendingIndex = i;
                            }

                            const m = L.marker([lat, lng], { icon })
                                .addTo(map)
                                .bindPopup(`<strong>Stop ${i + 1}: ${gp.name}</strong><br>${statusLabel}`);
                            markers[gp.id] = m;
                        });

                        // Draw route polyline
                        if (latlngs.length > 1) {
                            routePolyline = L.polyline(latlngs, {
                                color: '#3b82f6', weight: 4, opacity: 0.6, dashArray: '6, 10'
                            }).addTo(map);
                        }

                        // Place truck at next pending stop
                        if (firstPendingIndex !== -1) {
                            const gp  = pts[firstPendingIndex].garbage_point;
                            const lat = parseFloat(gp.latitude);
                            const lng = parseFloat(gp.longitude);

                            truckMarkerLayer = L.marker([lat, lng], { icon: truckIcon })
                                .addTo(map)
                                .bindPopup(`<strong>ðŸšš Live Garbage Truck</strong><br>Next stop: ${gp.name}`);

                            map.setView([lat, lng], 15);
                        } else if (latlngs.length > 0) {
                            // All done or all skipped
                            map.fitBounds(L.polyline(latlngs).getBounds(), { padding: [40, 40] });
                        }

                        // Update points count badge
                        document.getElementById('points-count').innerText = `${pts.length} Session Points`;
                    })
                    .catch(() => {
                        // Silently fall back to static view
                        loadPoints();
                    });
            }

            // â”€â”€ Map click â€” create new point (only when no active session) â”€â”€â”€
            map.on('click', function (e) {
                // Only allow adding points if banner is hidden (no active session)
                if (!document.getElementById('live-banner').classList.contains('hidden')) return;
                const name = prompt('Enter a name for this Collection Point:');
                if (name) savePoint(e.latlng.lat, e.latlng.lng, name);
            });

            function savePoint(lat, lng, name) {
                fetch('/api/garbage-points', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng, name, barangay_id: barangayId })
                })
                .then(r => r.json())
                .then(d => { if (d.success) fetchLiveSession(); else alert(d.message || 'Error saving point.'); })
                .catch(err => console.error('Error:', err));
            }

            // â”€â”€ ETA Routing â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            function calculateETA(dest) {
                if (!userLocation) { alert('Still waiting for GPS location.'); return; }
                document.getElementById('eta-info').innerText = 'Calculating routeâ€¦';

                if (routingControl) { map.removeControl(routingControl); }

                const lrm = L.Routing.control({
                    waypoints: [userLocation, dest],
                    routeWhileDragging: false, addWaypoints: false, show: false,
                    createMarker: () => null,
                    lineOptions: { styles: [{ color: '#3b82f6', opacity: 0.8, weight: 6 }] }
                }).addTo(map);

                lrm.on('routesfound', e => {
                    const s = e.routes[0].summary;
                    document.getElementById('eta-info').innerHTML =
                        `ðŸ›£ï¸ Distance: <strong>${(s.totalDistance / 1000).toFixed(2)} km</strong> &nbsp;|&nbsp; â±ï¸ ETA: <strong>~${Math.max(1, Math.round(s.totalTime / 60))} mins</strong>`;
                });
                lrm.on('routingerror', () => {
                    document.getElementById('eta-info').innerText = 'Error calculating route. Try another point.';
                });
                routingControl = lrm;
            }

            // â”€â”€ Helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            window.locatePoint = (lat, lng) => map.setView([lat, lng], 16);

            window.togglePointStatus = id => {
                fetch(`/api/garbage-points/${id}/toggle`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(r => r.json())
                .then(res => { if (res.success) fetchLiveSession(); else alert(res.message || 'Error.'); });
            };

            // â”€â”€ Initial Load + polling every 15 s â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
            fetchLiveSession();
            setInterval(fetchLiveSession, 15000);
        });
    </script>

    {{-- Leaflet Routing Machine (for ETA only when no session) --}}
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
@endsection
