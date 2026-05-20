@extends('dashboard.layout')

@section('title', 'Assigned Route Map')

@section('content')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <div class="mb-8 animate-slideUp flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="font-syne text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Active Shift Route</h1>
            <p class="text-sm text-slate-500">Live navigation display showing collection checkpoints throughout your active coverage loop.</p>
        </div>
        
        @if($session->status === 'pending')
            <form action="{{ route('dashboard.start-route', $session->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                    START ROUTE NAVIGATION
                </button>
            </form>
        @elseif($session->status === 'ongoing')
            <form action="{{ route('dashboard.complete-route', $session->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    COMPLETE ACTIVE SHIFT
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-slideUp" style="animation-delay: 0.1s;">
        
        <!-- Interactive Leaflet Map -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col min-h-[500px]">
            <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ $session->status === 'ongoing' ? 'bg-green-500 animate-pulse' : 'bg-amber-500' }}"></span> 
                    Status: {{ ucfirst($session->status) }} — Zone Coverage
                </span>
                <span class="text-xs text-slate-400 font-medium">Auto-connecting checkpoints in route order</span>
            </div>
            
            <div id="map" class="flex-1 w-full min-h-[450px] bg-slate-100 z-10"></div>
        </div>

        <!-- Route List & Actions -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col h-full">
            <h3 class="font-syne text-lg font-bold text-slate-900 mb-1">Navigation Checkpoints</h3>
            <p class="text-xs text-slate-400 mb-6">Complete each pickup checkpoint to update live boundary progress reports.</p>

            <div class="space-y-4 overflow-y-auto max-h-[400px] flex-1 pr-1">
                @forelse($sessionPoints as $point)
                    <div class="point-card p-4 rounded-xl border {{ $point->isCollected() ? 'bg-emerald-50/50 border-emerald-100' : ($point->isSkipped() ? 'bg-rose-50/50 border-rose-100' : 'bg-slate-50/50 border-slate-100') }} transition-all flex flex-col gap-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-slate-700 text-xs font-bold mb-1">
                                    {{ $point->route_order }}
                                </span>
                                <h4 class="text-sm font-bold text-slate-800 leading-snug">{{ $point->garbagePoint->name }}</h4>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $point->garbagePoint->address }}</p>
                            </div>
                            
                            <!-- Small status badge -->
                            <span>
                                <span class="status-badge px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">
                                    @if($point->isCollected())
                                        <span class="bg-emerald-100 text-emerald-800">Collected</span>
                                    @elseif($point->isSkipped())
                                        <span class="bg-rose-100 text-rose-800">Skipped</span>
                                    @else
                                        <span class="bg-slate-200 text-slate-600">Pending</span>
                                    @endif
                                </span>
                            </span>
                        </div>

                        <!-- Action controls if active -->
                        @if($session->status === 'ongoing')
                            <div class="flex items-center gap-2 border-t border-slate-100 pt-2">
                                <form action="{{ route('dashboard.update-point-status', [$session->id, $point->id]) }}" method="POST" class="point-status-form flex-1">
                                    @csrf
                                    <input type="hidden" name="point_id" value="{{ $point->id }}">
                                    <input type="hidden" name="status" value="collected">
                                    <button type="submit" class="btn-collect w-full py-1.5 px-3 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Collect
                                    </button>
                                </form>
                                
                                <form action="{{ route('dashboard.update-point-status', [$session->id, $point->id]) }}" method="POST" class="point-status-form flex-1">
                                    @csrf
                                    <input type="hidden" name="point_id" value="{{ $point->id }}">
                                    <input type="hidden" name="status" value="skipped">
                                    <button type="submit" class="btn-skip w-full py-1.5 px-3 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-100 transition-colors flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Skip
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">
                        No checkpoints assigned to this route session.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Collect points coordinates from PHP
            const points = [
                @foreach($sessionPoints as $point)
                {
                    id: "{{ $point->id }}",
                    name: "{{ $point->garbagePoint->name }}",
                    address: "{{ $point->garbagePoint->address }}",
                    lat: parseFloat("{{ $point->garbagePoint->latitude }}"),
                    lng: parseFloat("{{ $point->garbagePoint->longitude }}"),
                    order: parseInt("{{ $point->route_order }}"),
                    status: "{{ $point->status }}"
                },
                @endforeach
            ];

            if (points.length === 0) return;

            // Center map around first point or default to Zamboanga City center
            const mapCenter = [points[0].lat, points[0].lng];
            const map = L.map('map').setView(mapCenter, 15);

            // Light themed standard tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Custom markers colors based on status
            const createCustomIcon = (order, status) => {
                let colorClass = 'bg-blue-600';
                if (status === 'collected') colorClass = 'bg-emerald-600';
                if (status === 'skipped') colorClass = 'bg-rose-600';

                return L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="w-8 h-8 rounded-full ${colorClass} text-white flex items-center justify-center font-bold border-2 border-white shadow-md text-xs">${order}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                });
            };

            const markerGroup = L.featureGroup();
            const markerMap = {};
            const latlngs = [];

            const updateMarker = (pointId, status, order, name, address) => {
                const marker = markerMap[pointId];
                if (!marker) return;

                marker.setIcon(createCustomIcon(order, status));
                const popupHtml = `
                    <div class="p-1">
                        <strong class="text-sm font-bold text-slate-800">${name}</strong>
                        <p class="text-xs text-slate-500 mt-1">${address}</p>
                        <span class="inline-block mt-2 px-2 py-0.5 text-[10px] font-bold rounded ${status === 'collected' ? 'bg-emerald-100 text-emerald-800' : status === 'skipped' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-600'} uppercase">
                            Status: ${status}
                        </span>
                    </div>
                `;

                const popup = marker.getPopup();
                if (popup) {
                    popup.setContent(popupHtml);
                }
            };

            // Add markers
            points.forEach(point => {
                const marker = L.marker([point.lat, point.lng], {
                    icon: createCustomIcon(point.order, point.status)
                }).bindPopup(`
                    <div class="p-1">
                        <strong class="text-sm font-bold text-slate-800">${point.name}</strong>
                        <p class="text-xs text-slate-500 mt-1">${point.address}</p>
                        <span class="inline-block mt-2 px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-600 uppercase">
                            Status: ${point.status}
                        </span>
                    </div>
                `);
                
                markerMap[point.id] = marker;
                markerGroup.addLayer(marker);
                latlngs.push([point.lat, point.lng]);
            });

            markerGroup.addTo(map);

            // Connect checkpoints with route polyline
            if (latlngs.length > 1) {
                const routePolyline = L.polyline(latlngs, {
                    color: '#2563eb', // blue-600
                    weight: 4,
                    opacity: 0.8,
                    dashArray: '8, 8'
                }).addTo(map);
                
                // Fit bounds to display entire route nicely
                map.fitBounds(markerGroup.getBounds().pad(0.1));
            }

            // -- AJAX point status handlers (Collect / Skip) --
            document.querySelectorAll('.point-status-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formEl = e.currentTarget;
                    const action = formEl.action;
                    const formData = new FormData(formEl);
                    const token = formData.get('_token');
                    const status = formData.get('status');
                    let wasSuccess = false;

                    // Disable buttons to prevent duplicate
                    const parent = formEl.closest('.point-card');
                    const allForms = parent.querySelectorAll('.point-status-form');
                    allForms.forEach(f => f.querySelector('button').disabled = true);

                    try {
                        const res = await fetch(action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                            },
                            body: new URLSearchParams(Array.from(formData.entries())).toString()
                        });

                        if (!res.ok) throw new Error('Network response not ok');
                        const json = await res.json();

                        if (json.success) {
                            // Update status badge
                            const badgeWrap = parent.querySelector('.status-badge');
                            if (badgeWrap) {
                                if (status === 'collected') {
                                    badgeWrap.innerHTML = '<span class="bg-emerald-100 text-emerald-800">Collected</span>';
                                    parent.classList.remove('bg-slate-50/50','border-slate-100','bg-rose-50/50','border-rose-100');
                                    parent.classList.add('bg-emerald-50/50','border-emerald-100');
                                } else if (status === 'skipped') {
                                    badgeWrap.innerHTML = '<span class="bg-rose-100 text-rose-800">Skipped</span>';
                                    parent.classList.remove('bg-slate-50/50','border-slate-100','bg-emerald-50/50','border-emerald-100');
                                    parent.classList.add('bg-rose-50/50','border-rose-100');
                                } else {
                                    badgeWrap.innerHTML = '<span class="bg-slate-200 text-slate-600">Pending</span>';
                                    parent.classList.remove('bg-emerald-50/50','border-emerald-100','bg-rose-50/50','border-rose-100');
                                    parent.classList.add('bg-slate-50/50','border-slate-100');
                                }
                            }

                            // Update map marker color and popup
                            const pointId = formData.get('point_id');
                            const markerPoint = points.find(p => p.id === pointId);
                            if (markerPoint) {
                                markerPoint.status = status;
                                updateMarker(pointId, status, markerPoint.order, markerPoint.name, markerPoint.address);
                            }

                            // Disable both action buttons and show collected state
                            const collectBtn = parent.querySelector('.btn-collect');
                            const skipBtn = parent.querySelector('.btn-skip');
                            if (collectBtn) {
                                if (status === 'collected') {
                                    collectBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Collected';
                                    collectBtn.classList.remove('bg-emerald-600','hover:bg-emerald-700');
                                    collectBtn.classList.add('bg-emerald-700');
                                } else {
                                    collectBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Collect';
                                }
                            }
                            if (skipBtn) {
                                if (status === 'skipped') {
                                    skipBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> Skipped';
                                    skipBtn.classList.remove('bg-rose-50','hover:bg-rose-100');
                                    skipBtn.classList.add('bg-rose-100');
                                } else {
                                    skipBtn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> Skip';
                                }
                            }

                            allForms.forEach(f => f.querySelector('button').disabled = true);
                            wasSuccess = true;
                        } else {
                            alert(json.message || 'Failed to update point status');
                        }
                    } catch (err) {
                        console.error(err);
                        alert('Failed to update status — please try again');
                    } finally {
                        if (!wasSuccess) {
                            allForms.forEach(f => f.querySelector('button').disabled = false);
                        }
                    }
                });
            });
        });
    </script>
@endsection