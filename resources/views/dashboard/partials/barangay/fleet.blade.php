@extends('dashboard.layout')

@section('title', 'Fleet & Tracking')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Welcome header --}}
    <div class="mb-8 animate-slideUp">
        <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">📡 Fleet Management & Live Tracking</h1>
        <p class="text-sm text-slate-500 font-medium">Monitor active collectors, track truck paths in real-time, and manage barangay fleet resources.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold animate-slideUp">
            {{ session('success') }}
        </div>
    @endif

    {{-- Live Tracking Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-2 shadow-sm mb-8 animate-slideUp" style="animation-delay: 0.1s;">
        <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100 bg-slate-50/30">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <h3 class="font-bold text-slate-900 text-sm">Live Collectors Telemetry Map</h3>
            </div>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Updates every 10s</span>
        </div>
        <div id="live-map" class="w-full rounded-xl" style="height: 380px; z-index: 1;"></div>
    </div>

    {{-- Management Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-slideUp" style="animation-delay: 0.2s;">
        
        {{-- Fleet Trucks Section --}}
        <div class="flex flex-col gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Add Fleet Truck</h3>
                <form method="POST" action="{{ route('dashboard.fleet.trucks.store') }}" class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    <div class="flex-1 flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Plate Number</label>
                        <input type="text" name="plate_number" placeholder="e.g. ZAM-991" required class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium bg-slate-50">
                    </div>
                    <div class="w-full sm:w-[140px] flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Capacity (Tons)</label>
                        <input type="number" step="0.1" name="capacity_tons" placeholder="e.g. 2.5" required class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium bg-slate-50">
                    </div>
                    <button type="submit" class="sm:self-end py-2 px-6 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                        Add
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900">Active Trucks</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Plate</th>
                                <th class="px-6 py-3">Capacity</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                            @forelse($trucks as $truck)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $truck->plate_number }}</td>
                                    <td class="px-6 py-4 text-xs">{{ $truck->capacity_tons }} Tons</td>
                                    <td class="px-6 py-4">
                                        @if($truck->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-200">Active</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold border border-slate-200">Out of Service</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form method="POST" action="{{ route('dashboard.fleet.trucks.toggle', $truck->id) }}">
                                            @csrf
                                            <button type="submit" class="text-[10px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1 rounded-lg transition-colors">
                                                {{ $truck->is_active ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No trucks in fleet yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Collectors Section --}}
        <div class="flex flex-col gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="font-bold text-slate-800 text-sm mb-4">Create Collector Account</h3>
                <form method="POST" action="{{ route('dashboard.fleet.collectors.store') }}" class="flex flex-col gap-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Full Name</label>
                            <input type="text" name="full_name" placeholder="John Doe" required class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium bg-slate-50">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Email Address</label>
                            <input type="email" name="email" placeholder="john@example.com" required class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium bg-slate-50">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Phone</label>
                            <input type="text" name="phone" placeholder="0917XXXXXXX" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium bg-slate-50">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Password</label>
                            <input type="password" name="password" placeholder="••••••••" required class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-medium bg-slate-50">
                        </div>
                    </div>
                    <button type="submit" class="mt-2 w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-sm">
                        Create Collector Account
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900">Active Collectors</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50 text-slate-400 text-[10px] uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3">Collector</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Phone</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                            @forelse($collectors as $collector)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-800">{{ $collector->full_name }}</td>
                                    <td class="px-6 py-4 text-xs font-mono">{{ $collector->email }}</td>
                                    <td class="px-6 py-4 text-xs">{{ $collector->phone ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-xs font-semibold">No collector accounts registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Live Map initialization
            const liveMap = L.map('live-map').setView([6.9214, 122.0790], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(liveMap);

            const sessionMarkers = {};
            const sessionLines = {};

            function updateLiveTelemetry() {
                fetch('/api/barangay/live-tracking')
                    .then(res => res.json())
                    .then(sessions => {
                        // Clear out existing route lines & markers not in active sessions
                        const activeSessionIds = sessions.map(s => s.id);
                        Object.keys(sessionMarkers).forEach(sid => {
                            if (!activeSessionIds.includes(sid)) {
                                liveMap.removeLayer(sessionMarkers[sid]);
                                if (sessionLines[sid]) {
                                    sessionLines[sid].forEach(line => liveMap.removeLayer(line));
                                }
                                delete sessionMarkers[sid];
                                delete sessionLines[sid];
                            }
                        });

                        if (sessions.length === 0) {
                            return;
                        }

                        sessions.forEach(session => {
                            const points = session.session_points || [];
                            if (points.length === 0) return;

                            // Draw Path
                            const latLngs = points.map(sp => [parseFloat(sp.garbage_point.latitude), parseFloat(sp.garbage_point.longitude)]);
                            
                            // Remove previous line if it exists
                            if (sessionLines[session.id]) {
                                sessionLines[session.id].forEach(line => liveMap.removeLayer(line));
                            }
                            
                            const polyline = L.polyline(latLngs, { color: '#3b82f6', weight: 4, opacity: 0.6 }).addTo(liveMap);
                            sessionLines[session.id] = [polyline];

                            // Find Collector Current Location (we can simulate it by locating the last collected/notified point, or first pending point)
                            const currentPoint = session.session_points.find(sp => sp.status === 'notified') 
                                                || session.session_points.find(sp => sp.status === 'pending') 
                                                || session.session_points[session.session_points.length - 1];

                            if (currentPoint && currentPoint.garbage_point) {
                                const lat = parseFloat(currentPoint.garbage_point.latitude);
                                const lng = parseFloat(currentPoint.garbage_point.longitude);

                                if (sessionMarkers[session.id]) {
                                    sessionMarkers[session.id].setLatLng([lat, lng]);
                                } else {
                                    // Custom Truck Icon marker
                                    const truckDiv = L.divIcon({
                                        className: 'custom-truck-icon',
                                        html: `<div style="background-color: #3b82f6; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; display: flex; items-center: center; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.15)">🚚</div>`,
                                        iconSize: [32, 32],
                                        iconAnchor: [16, 16]
                                    });
                                    
                                    const marker = L.marker([lat, lng], { icon: truckDiv }).addTo(liveMap);
                                    marker.bindPopup(`
                                        <div class="font-poppins p-1">
                                            <p class="font-bold text-xs text-slate-800 m-0">Collector: ${session.collector ? session.collector.full_name : 'Collector'}</p>
                                            <p class="text-[10px] text-slate-400 m-0">Current Point: ${currentPoint.garbage_point.name}</p>
                                        </div>
                                    `);
                                    sessionMarkers[session.id] = marker;
                                }
                            }
                        });

                        // Zoom to fit active session paths
                        const allLatLngs = [];
                        sessions.forEach(s => {
                            (s.session_points || []).forEach(sp => {
                                if (sp.garbage_point) {
                                    allLatLngs.push([parseFloat(sp.garbage_point.latitude), parseFloat(sp.garbage_point.longitude)]);
                                }
                            });
                        });
                        if (allLatLngs.length > 0) {
                            liveMap.fitBounds(allLatLngs);
                        }
                    });
            }

            // Update every 10 seconds
            setInterval(updateLiveTelemetry, 10000);
            updateLiveTelemetry();
        });
    </script>
@endsection