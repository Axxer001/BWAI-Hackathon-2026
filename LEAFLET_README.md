### 1. Data Retrieval (Backend API)

The system requires a communication layer between the frontend interface and the database. The `MapController` functions as this intermediary.

```php
    public function getGarbagePoints() {
        $points = DB::table('garbage_points')->where('is_active', 1)->get();
        return response()->json($points);
    }

```

* **Functionality:** This controller method handles data extraction. It queries the database for all active coordinates and returns a JSON payload, establishing a read-only API endpoint for the frontend application to consume upon initialization.

### 2. Data Insertion (Backend API)

The system must also process incoming coordinate data from the client side.

```php
    public function addGarbagePoint(Request $request) {
        // ... (validation rules applied here) ...
        
        $id = Str::uuid()->toString();

        DB::table('garbage_points')->insert([
            'id' => $id,
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'barangay_id' => $request->barangay_id,
            // ... timestamps ...
        ]);

        return response()->json(['success' => true, 'id' => $id]);
    }

```

* **Functionality:** This method processes incoming HTTP POST requests. It generates a unique identifier (UUID) for the primary key and inserts the new geographic data into the system's database. It subsequently returns a success response so the client interface can update dynamically.

### 3. Core Map Initialization (Frontend)

Within the Blade template, the map canvas must be instantiated and rendered within the Document Object Model (DOM).

```javascript
        const map = L.map('map').setView([6.9214, 122.0790], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

```

* **Functionality:** The Leaflet library utilizes these lines to instantiate the interactive map object. It targets a specific HTML element (`#map`), centers the geographic view on Zamboanga City using the coordinates `[6.9214, 122.0790]`, and loads OpenStreetMap tiles to render the visual cartography.

### 4. Geolocation Services (Frontend)

To facilitate routing, the system requires a baseline starting point.

```javascript
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                userLocation = L.latLng(position.coords.latitude, position.coords.longitude);

                L.circleMarker(userLocation, {
                    color: '#ef4444',
                    fillColor: '#ef4444',
                    radius: 8
                }).addTo(map).bindPopup("Your Current Location").openPopup();
            });
        }

```

* **Functionality:** This block invokes the browser's native Geolocation API. It retrieves the device's current GPS coordinates and renders a distinct red marker on the map. This live coordinate data is essential for establishing the origin point in subsequent routing calculations.

### 5. Asynchronous Data Synchronization (Frontend)

The frontend application must populate the stored locations seamlessly.

```javascript
        function loadExistingPoints() {
            fetch('/api/garbage-points')
                .then(response => response.json())
                .then(data => {
                    data.forEach(point => {
                        createInteractiveMarker(point.latitude, point.longitude, point.name);
                    });
                });
        }

```

* **Functionality:** Utilizing the JavaScript `fetch` API, this function asynchronously requests the stored coordinates from the backend endpoint. It iterates through the retrieved JSON array, dynamically generating interactive markers on the map for each valid location without requiring a page refresh.

### 6. Event-Driven Data Entry (Frontend)

The interface acts as a data entry tool via interactive event listeners.

```javascript
        map.on('click', function (e) {
            let lat = e.latlng.lat;
            let lng = e.latlng.lng;

            let pointName = prompt("Enter a name for this Garbage Point:");
            let validBarangayId = "d68a9f4e-2b5c-4b3d-8f1a-6c7e8d9f0a1b";

            if (pointName) {
                savePoint(lat, lng, pointName, validBarangayId);
            }
        });

```

* **Functionality:** An event listener is bound to the map object, capturing the precise latitude and longitude of any click event. It prompts for a location name and passes the captured data to an asynchronous function responsible for transmitting the payload back to the backend insertion method.

### 7. Routing Algorithm Integration (Frontend)

The system calculates operational metrics using an external routing engine.

```javascript
        function calculateETA(destinationLatLng) {
            routingControl = L.Routing.control({
                waypoints: [
                    userLocation, 
                    destinationLatLng
                ],
                show: false,
                lineOptions: { styles: [{ color: '#3b82f6', weight: 6 }] }
            }).addTo(map);

            routingControl.on('routesfound', function (e) {
                let summary = e.routes[0].summary;
                
                let distanceKm = (summary.totalDistance / 1000).toFixed(2);
                let timeMinutes = Math.round(summary.totalTime / 60);

                document.getElementById('eta-info').innerHTML = 
                    `🛣️ Distance: <strong>${distanceKm} km</strong> | ⏱️ ETA: <strong>~${timeMinutes} mins</strong>`;
            });
        }

```

* **Functionality:** This section leverages the Leaflet Routing Machine plugin. When an interactive marker is selected, it calculates the navigational path between the stored geolocation (origin) and the selected marker (destination). Once the Open Source Routing Machine (OSRM) algorithm returns a path, the event listener extracts the raw distance and duration data, formats the variables, and dynamically updates the DOM to display the calculated metrics.