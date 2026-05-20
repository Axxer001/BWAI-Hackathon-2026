<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garbage Collection Map</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #374151;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #111827;
        }

        .info-panel {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
        }

        #eta-info {
            margin-top: 10px;
            font-size: 1.1em;
            color: #15803d;
            font-weight: 600;
        }

        #map {
            height: 600px;
            width: 100%;
            border-radius: 8px;
            z-index: 1;
            border: 1px solid #e5e7eb;
        }

        /* 1. Change the default map cursor to a crosshair for adding points */
        .leaflet-container {
            cursor: crosshair !important;
        }

        /* 2. Keep the closed hand "grabbing" cursor when the user is actively dragging/panning the map */
        .leaflet-dragging .leaflet-container {
            cursor: grabbing !important;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Garbage Collection Routes</h2>

        <div class="info-panel">
            <p style="margin: 0;"><strong>How to use:</strong> Click anywhere on the map to create a new collection
                point. Click on an existing blue marker to calculate the estimated time of arrival (ETA) from your
                current location.</p>
            <div id="eta-info">Waiting for a destination...</div>
        </div>

        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        // Initialize the map centered on Zamboanga City
        const map = L.map('map').setView([6.9214, 122.0790], 13);
        let routingControl = null;
        let userLocation = null;

        // Load OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // 1. Get User's Current Location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                userLocation = L.latLng(position.coords.latitude, position.coords.longitude);

                // Add a distinct red marker for the user/truck
                L.circleMarker(userLocation, {
                    color: '#ef4444',
                    fillColor: '#ef4444',
                    fillOpacity: 0.8,
                    radius: 8
                }).addTo(map).bindPopup("Your Current Location").openPopup();

                // Slightly pan to user location
                map.panTo(userLocation);
            }, (error) => {
                console.error("Geolocation error:", error);
                document.getElementById('eta-info').innerText = "Location access denied. Routing unavailable.";
            });
        }

        // 2. Fetch existing points from the database
        loadExistingPoints();

        // 3. Handle Map Clicks to Save New Points
        map.on('click', function (e) {
            let lat = e.latlng.lat;
            let lng = e.latlng.lng;

            let pointName = prompt("Enter a name for this Garbage Point:");

            // Replaced placeholder with the actual UUID for Barangay Tetuan
            let validBarangayId = "d68a9f4e-2b5c-4b3d-8f1a-6c7e8d9f0a1b";

            if (pointName) {
                savePoint(lat, lng, pointName, validBarangayId);
            }
        });

        function loadExistingPoints() {
            fetch('/api/garbage-points')
                .then(response => response.json())
                .then(data => {
                    data.forEach(point => {
                        createInteractiveMarker(point.latitude, point.longitude, point.name);
                    });
                })
                .catch(error => console.error('Error fetching points:', error));
        }

        function savePoint(lat, lng, name, barangayId) {
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/api/garbage-points', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    name: name,
                    barangay_id: barangayId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        createInteractiveMarker(lat, lng, name);
                        alert(data.message);
                    } else {
                        alert("Error saving point. Check console for details.");
                        console.error(data);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Helper to create a marker and attach the routing click event
        function createInteractiveMarker(lat, lng, name) {
            let marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(`<b>${name}</b><br><small style="color: #6b7280;">Click marker to route here</small>`);

            marker.on('click', function () {
                calculateETA(marker.getLatLng());
            });
        }

        // 4. Calculate Route and ETA using OSRM
        function calculateETA(destinationLatLng) {
            if (!userLocation) {
                alert("Still waiting for GPS location or permission was denied.");
                return;
            }

            document.getElementById('eta-info').innerText = "Calculating route...";

            // Remove existing route if one is already drawn
            if (routingControl) {
                map.removeControl(routingControl);
            }

            // Create the route using the free OSRM engine
            routingControl = L.Routing.control({
                waypoints: [
                    userLocation,
                    destinationLatLng
                ],
                routeWhileDragging: false,
                addWaypoints: false,
                show: false, // Hides the bulky turn-by-turn text box
                createMarker: function () { return null; }, // Prevents drawing duplicate markers
                lineOptions: {
                    styles: [{ color: '#3b82f6', opacity: 0.8, weight: 6 }] // Nice blue route line
                }
            }).addTo(map);

            // Listen for route calculation completion
            routingControl.on('routesfound', function (e) {
                let routes = e.routes;
                let summary = routes[0].summary;

                let distanceKm = (summary.totalDistance / 1000).toFixed(2);
                let timeMinutes = Math.max(1, Math.round(summary.totalTime / 60)); // Prevents showing 0 minutes

                document.getElementById('eta-info').innerHTML =
                    `🛣️ Distance: <strong>${distanceKm} km</strong> &nbsp;|&nbsp; ⏱️ Estimated Time: <strong>~${timeMinutes} mins</strong>`;
            });

            routingControl.on('routingerror', function (e) {
                document.getElementById('eta-info').innerText = "Error calculating route. Try another point.";
                console.error("Routing Error:", e);
            });
        }
    </script>
</body>

</html>