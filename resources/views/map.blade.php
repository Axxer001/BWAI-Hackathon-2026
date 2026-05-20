<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garbage Collection Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 100vh;
        }
    </style>

    <!-- Leaflet creates the visual map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!--  Allows to create routes through the map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
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

    <!-- Helps in creating plots and coordinates -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Allows the access of the function to connect to the external server and calculates the route. -->
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
        // Handle Map Clicks to Save New Points
        map.on('click', function (e) {
            let lat = e.latlng.lat;
            let lng = e.latlng.lng;

            let pointName = prompt("Enter a name for this Garbage Point:");

            // Now using the UUID for Barangay Tetuan
            let validBarangayId = "d68a9f4e-2b5c-4b3d-8f1a-6c7e8d9f0a1b";

            if (pointName) {
                savePoint(lat, lng, pointName, validBarangayId);
            }
        } else if (validBarangayId === "d68a9f4e-2b5c-4b3d-8f1a-6c7e8d9f0a1b") {
            alert("Please update the code with a valid barangay_id from your database before saving.");
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