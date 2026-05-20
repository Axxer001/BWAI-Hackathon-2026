@extends('dashboard.layout')

@section('title', 'Track Fleet & Trucks')

@section('content')
    <div class="mb-8 animate-slideUp">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Track Fleet & Trucks</h1>
        <p class="text-sm text-slate-500">Monitor active garbage collection vehicles within your jurisdiction in real-time.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden animate-slideUp" style="animation-delay: 0.1s;">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <span class="text-sm font-bold text-slate-700 flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
                    </span>
                    Live GPS Tracking Active
                </span>
            </div>
            <div class="w-full h-[450px] bg-slate-200 relative">
                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" 
                    src="https://www.openstreetmap.org/export/embed.html?bbox=122.065%2C6.905%2C122.085%2C6.920&amp;layer=mapnik" 
                    class="block" style="filter: contrast(1.1) saturate(1.2);">
                </iframe>
            </div>
        </div>

        <div class="lg:col-span-1 flex flex-col gap-4 animate-slideUp" style="animation-delay: 0.2s;">
            <h3 class="text-sm font-bold text-slate-900 px-1">Active Vehicles ({{ isset($activeSessions) ? $activeSessions->count() : 0 }})</h3>
            
            @if(isset($activeSessions) && $activeSessions->count() > 0)
                @foreach($activeSessions as $session)
                    <div class="bg-white p-5 rounded-2xl border border-blue-200 shadow-sm shadow-blue-100 flex flex-col gap-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $session->truck_name ?? 'Truck Unassigned' }}</h4>
                                <p class="text-xs text-slate-500">Driver: {{ $session->collector->name ?? 'Unknown' }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-md">In Transit</span>
                        </div>
                        
                        @php
                            $totalPts = $session->sessionPoints ? $session->sessionPoints->count() : 0;
                            $collectedPts = $session->sessionPoints ? $session->sessionPoints->where('status', 'collected')->count() : 0;
                            $pct = $totalPts > 0 ? round(($collectedPts / $totalPts) * 100) : 0;
                        @endphp
                        
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="text-[11px] text-slate-500 text-right">Route {{ $pct }}% Complete ({{ $collectedPts }}/{{ $totalPts }} points)</p>
                    </div>
                @endforeach
            @else
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
                    <p class="text-xs text-slate-500 py-2">No active vehicles currently.</p>
                </div>
            @endif

            <h3 class="text-sm font-bold text-slate-900 px-1 mt-4">Idle/Pending Vehicles ({{ isset($idleSessions) ? $idleSessions->count() : 0 }})</h3>
            
            @if(isset($idleSessions) && $idleSessions->count() > 0)
                @foreach($idleSessions as $session)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-3 opacity-75">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $session->truck_name ?? 'Truck Unassigned' }}</h4>
                                <p class="text-xs text-slate-500">Driver: {{ $session->collector->name ?? 'Pending Assignment' }}</p>
                            </div>
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded-md">Idle</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm text-center">
                    <p class="text-xs text-slate-500 py-2">No idle vehicles currently.</p>
                </div>
            @endif
        </div>
    </div>
@endsection                                 @csrf
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