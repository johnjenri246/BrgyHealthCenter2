@extends('layouts.admin')

@section('title', 'Weather')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">🌤️ Local Weather</h1>
                <p class="text-gray-500">Real-time conditions for your barangay.</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                    API Connected
                </span>
                <span class="text-xs text-gray-500">{{ $apiProvider ?? 'Open-Meteo API' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-6 py-4 flex items-center justify-between">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] opacity-80">Live Now</div>
                <div class="text-xl font-bold">{{ $location }}</div>
            </div>
            <div id="updatedAt" class="text-sm opacity-90">
                @if($updatedAt)
                    Updated: {{ $updatedAt }}
                @endif
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($error)
                <div class="md:col-span-3 bg-red-50 text-red-700 border border-red-200 rounded-lg p-4">
                    {{ $error }}
                </div>
            @elseif($weather)
                <div class="md:col-span-2 space-y-4" id="weatherContent">
                    <div class="flex items-center space-x-4">
                        <div class="text-5xl font-extrabold text-gray-900" id="temperature">
                            {{ $weather['temperature'] }}°C
                        </div>
                        <div>
                            <div class="text-lg font-semibold text-gray-800" id="description">{{ $weather['description'] }}</div>
                            <div class="text-sm text-gray-500">Real-time weather at {{ $location }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <div class="text-xs text-gray-500 uppercase">Humidity</div>
                            <div class="text-xl font-bold text-gray-900" id="humidity">{{ $weather['humidity'] }}%</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <div class="text-xs text-gray-500 uppercase">Wind</div>
                            <div class="text-xl font-bold text-gray-900" id="windSpeed">{{ $weather['wind_speed'] }} km/h</div>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 space-y-3">
                    <div class="text-blue-700 font-semibold text-center">
                        Stay updated before visiting the clinic.
                    </div>
                    <div class="border-t border-blue-200 pt-3">
                        <div class="text-xs text-blue-600 text-center">
                            <strong>API:</strong> {{ $apiProvider ?? 'Open-Meteo API' }}
                        </div>
                        <div class="text-xs text-blue-500 text-center mt-1">
                            Real-time data • Auto-refresh every 2 minutes
                        </div>
                    </div>
                </div>
            @else
                <div class="md:col-span-3 text-gray-500">No data available.</div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mt-6">
        <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-4 flex items-center justify-between">
            <div>
                <div class="text-xs uppercase tracking-[0.2em] opacity-80">Route to Clinic</div>
                <div class="text-xl font-bold">Fastest path to {{ $location }}</div>
            </div>
            <div class="text-sm opacity-90" id="routeStatus">Waiting for location…</div>
        </div>

        <div class="p-6 space-y-4">
            <div class="text-sm text-gray-700">
                Allow location to see directions from where you are to the barangay health center.
            </div>
            <div id="map" class="w-full rounded-xl border border-gray-200" style="height: 360px;"></div>
            <div class="flex flex-col gap-2 text-sm text-gray-700">
                <div><strong>Destination:</strong> {{ $location }} ({{ $latitude }}, {{ $longitude }})</div>
                <div id="routeDetails" class="text-gray-600">Route details will appear after locating you.</div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Map + routing
    const barangayLat = {{ $latitude }};
    const barangayLng = {{ $longitude }};
    const barangayName = @json($location);
    let userMarker = null;
    let routeLine = null;

    const map = L.map('map').setView([barangayLat, barangayLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const destinationMarker = L.marker([barangayLat, barangayLng]).addTo(map)
        .bindPopup(barangayName)
        .openPopup();

    function updateRoute(userLat, userLng) {
        const statusEl = document.getElementById('routeStatus');
        const detailsEl = document.getElementById('routeDetails');

        statusEl.textContent = 'Fetching fastest route…';

        fetch(`https://router.project-osrm.org/route/v1/foot/${userLng},${userLat};${barangayLng},${barangayLat}?overview=full&geometries=geojson`)
            .then(resp => resp.json())
            .then(data => {
                if (data.code !== 'Ok' || !data.routes || !data.routes[0]) {
                    statusEl.textContent = 'Routing unavailable';
                    detailsEl.textContent = 'Could not fetch a walking route right now.';
                    return;
                }

                const route = data.routes[0];
                const coords = route.geometry.coordinates.map(([lng, lat]) => [lat, lng]);

                if (routeLine) {
                    map.removeLayer(routeLine);
                }
                routeLine = L.polyline(coords, { color: '#16a34a', weight: 5, opacity: 0.8 }).addTo(map);

                const bounds = L.latLngBounds(coords);
                map.fitBounds(bounds, { padding: [20, 20] });

                const distanceKm = (route.distance / 1000).toFixed(2);
                const durationMin = Math.round(route.duration / 60);
                statusEl.textContent = 'Fastest route shown';
                detailsEl.textContent = `Walk ~${distanceKm} km, about ${durationMin} min.`;
            })
            .catch(() => {
                statusEl.textContent = 'Routing unavailable';
                detailsEl.textContent = 'Could not fetch a walking route right now.';
            });
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const { latitude, longitude } = pos.coords;
                const statusEl = document.getElementById('routeStatus');
                statusEl.textContent = 'Location found';

                userMarker = L.marker([latitude, longitude], { icon: L.icon({
                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png'
                }) }).addTo(map).bindPopup('You are here');

                updateRoute(latitude, longitude);
            },
            () => {
                const statusEl = document.getElementById('routeStatus');
                const detailsEl = document.getElementById('routeDetails');
                statusEl.textContent = 'Location blocked';
                detailsEl.textContent = 'Enable location services to see directions from your spot.';
                map.setView([barangayLat, barangayLng], 16);
            }
        );
    } else {
        document.getElementById('routeStatus').textContent = 'No geolocation support';
        document.getElementById('routeDetails').textContent = 'Your browser cannot share location for routing.';
    }

    // Auto-refresh weather every 2 minutes (120000 ms)
    let refreshInterval = setInterval(updateWeather, 120000);
    
    function updateWeather() {
        fetch('{{ route("weather.api") }}')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Weather update error:', data.error);
                    return;
                }
                
                if (data.weather) {
                    // Update temperature
                    const tempEl = document.getElementById('temperature');
                    if (tempEl) tempEl.textContent = data.weather.temperature + '°C';
                    
                    // Update description
                    const descEl = document.getElementById('description');
                    if (descEl) descEl.textContent = data.weather.description;
                    
                    // Update humidity
                    const humidityEl = document.getElementById('humidity');
                    if (humidityEl) humidityEl.textContent = data.weather.humidity + '%';
                    
                    // Update wind speed
                    const windEl = document.getElementById('windSpeed');
                    if (windEl) windEl.textContent = data.weather.wind_speed + ' km/h';
                    
                    // Update timestamp
                    const timeEl = document.getElementById('updatedAt');
                    if (timeEl && data.updatedAt) {
                        timeEl.textContent = 'Updated: ' + data.updatedAt;
                    }
                    
                    // Visual feedback
                    const weatherContent = document.getElementById('weatherContent');
                    if (weatherContent) {
                        weatherContent.style.transition = 'opacity 0.3s';
                        weatherContent.style.opacity = '0.7';
                        setTimeout(() => {
                            weatherContent.style.opacity = '1';
                        }, 300);
                    }
                }
            })
            .catch(error => {
                console.error('Failed to update weather:', error);
            });
    }
    
    // Clean up interval when page is closed
    window.addEventListener('beforeunload', () => {
        clearInterval(refreshInterval);
    });
</script>
@endsection

