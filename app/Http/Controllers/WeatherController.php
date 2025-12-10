<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WeatherController extends Controller
{
    /**
     * Get barangay location coordinates from config
     */
    private function getBarangayLocation()
    {
        $latitude = config('weather.latitude');
        $longitude = config('weather.longitude');
        $address = config('weather.address');
        $locationName = config('weather.location_name', 'Barangay Health Center');

        // If coordinates are not set or are defaults, try to geocode the address
        if (($latitude == 14.6760 && $longitude == 121.0437) && $address) {
            $coords = $this->geocodeAddress($address);
            if ($coords) {
                $latitude = $coords['lat'];
                $longitude = $coords['lon'];
            }
        }

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'name' => $locationName,
        ];
    }

    /**
     * Geocode an address to get coordinates (using OpenWeatherMap Geocoding API)
     */
    private function geocodeAddress($address)
    {
        $apiKey = config('weather.api.openweathermap_key');
        
        if (!$apiKey) {
            return null; // Can't geocode without API key
        }

        try {
            $response = Http::timeout(5)->get('http://api.openweathermap.org/geo/1.0/direct', [
                'q' => $address,
                'limit' => 1,
                'appid' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                    return [
                        'lat' => $data[0]['lat'],
                        'lon' => $data[0]['lon'],
                    ];
                }
            }
        } catch (\Exception $e) {
            // Silently fail and use default coordinates
        }

        return null;
    }

    /**
     * Get the API provider name
     */
    private function getApiProvider()
    {
        return config('weather.api.provider', 'openmeteo');
    }

    /**
     * Fetch real-time weather data (supports both Open-Meteo and OpenWeatherMap)
     * No automatic fallback when OpenWeatherMap is selected; it will show error instead.
     */
    private function fetchWeatherData($latitude, $longitude)
    {
        $provider = $this->getApiProvider();
        
        if ($provider === 'openweathermap') {
            return $this->fetchFromOpenWeatherMap($latitude, $longitude);
        }
        
        // Default to Open-Meteo
        return $this->fetchFromOpenMeteo($latitude, $longitude);
    }

    /**
     * Fetch real-time weather data from Open-Meteo API
     */
    private function fetchFromOpenMeteo($latitude, $longitude)
    {
        try {
            $response = Http::timeout(8)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current' => 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code,is_day',
                'timezone' => 'Asia/Manila',
            ]);

            if ($response->successful()) {
                $current = $response->json('current', []);
                if (!empty($current)) {
                    return [
                        'temperature' => round($current['temperature_2m'] ?? 0, 1),
                        'humidity' => $current['relative_humidity_2m'] ?? null,
                        'wind_speed' => round(($current['wind_speed_10m'] ?? 0) * 3.6, 1), // Convert m/s to km/h
                        'description' => $this->mapWeatherCode($current['weather_code'] ?? null),
                        'weather_code' => $current['weather_code'] ?? null,
                        'is_day' => $current['is_day'] ?? 1,
                        'time' => $current['time'] ?? null,
                        'api_provider' => 'openmeteo',
                    ];
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    /**
     * Fetch real-time weather data from OpenWeatherMap API
     */
    private function fetchFromOpenWeatherMap($latitude, $longitude)
    {
        $apiKey = config('weather.api.openweathermap_key');
        
        if (!$apiKey || $apiKey === 'your_api_key_here') {
            return null; // Can't fetch without API key
        }

        try {
            $response = Http::timeout(8)->get('https://api.openweathermap.org/data/2.5/weather', [
                'lat' => $latitude,
                'lon' => $longitude,
                'appid' => $apiKey,
                'units' => 'metric', // Use Celsius
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Check for API errors in response
                if (isset($data['cod']) && $data['cod'] !== 200) {
                    // API returned an error (e.g., invalid API key, rate limit)
                    Log::warning('OpenWeatherMap API error: ' . ($data['message'] ?? 'Unknown error'));
                    return null;
                }
                
                if (!empty($data) && isset($data['main'])) {
                    return [
                        'temperature' => round($data['main']['temp'] ?? 0, 1),
                        'humidity' => $data['main']['humidity'] ?? null,
                        'wind_speed' => round(($data['wind']['speed'] ?? 0) * 3.6, 1), // Convert m/s to km/h
                        'description' => ucfirst($data['weather'][0]['description'] ?? 'Unavailable'),
                        'weather_code' => $data['weather'][0]['id'] ?? null,
                        'is_day' => isset($data['sys']['sunrise']) && time() > $data['sys']['sunrise'] && time() < $data['sys']['sunset'] ? 1 : 0,
                        'time' => now()->toIso8601String(),
                        'api_provider' => 'openweathermap',
                    ];
                }
            } else {
                // Log the error for debugging
                Log::warning('OpenWeatherMap API request failed: ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('OpenWeatherMap API exception: ' . $e->getMessage());
            return null;
        }

        return null;
    }

    /**
     * Display weather page
     */
    public function show()
    {
        $location = $this->getBarangayLocation();
        $weather = null;
        $error = null;

        $weather = $this->fetchWeatherData($location['latitude'], $location['longitude']);

        if (!$weather) {
            $error = 'Unable to fetch weather data at this time. Please check your API configuration or try again later.';
        }

        // Determine API provider name (show which API actually provided the data)
        $apiProvider = $weather['api_provider'] ?? $this->getApiProvider();
        $apiName = $apiProvider === 'openweathermap' ? 'OpenWeatherMap API' : 'Open-Meteo API';
        
        $updatedAtDisplay = $weather && isset($weather['time'])
            ? Carbon::parse($weather['time'])->timezone('Asia/Manila')
            : now('Asia/Manila');

        return view('weather', [
            'weather' => $weather,
            'location' => $location['name'],
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'apiProvider' => $apiName,
            'updatedAt' => $updatedAtDisplay->format('M d, Y h:i A'),
            'error' => $error,
        ]);
    }

    /**
     * Admin weather page (same data, admin layout)
     */
    public function showAdmin()
    {
        $location = $this->getBarangayLocation();
        $weather = null;
        $error = null;

        $weather = $this->fetchWeatherData($location['latitude'], $location['longitude']);

        if (!$weather) {
            $error = 'Unable to fetch weather data at this time. Please check your API configuration or try again later.';
        }

        $apiProvider = $weather['api_provider'] ?? $this->getApiProvider();
        $apiName = $apiProvider === 'openweathermap' ? 'OpenWeatherMap API' : 'Open-Meteo API';

        $updatedAtDisplay = $weather && isset($weather['time'])
            ? Carbon::parse($weather['time'])->timezone('Asia/Manila')
            : now('Asia/Manila');

        return view('admin.weather', [
            'weather' => $weather,
            'location' => $location['name'],
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'apiProvider' => $apiName,
            'updatedAt' => $updatedAtDisplay->format('M d, Y h:i A'),
            'error' => $error,
        ]);
    }

    /**
     * API endpoint for AJAX weather updates
     */
    public function api()
    {
        $location = $this->getBarangayLocation();
        $weather = null;
        $error = null;

        $weather = $this->fetchWeatherData($location['latitude'], $location['longitude']);

        if (!$weather) {
            $error = 'Unable to fetch weather data at this time. Please check your API configuration or try again later.';
        }

        $apiProvider = $weather['api_provider'] ?? $this->getApiProvider();
        $apiName = $apiProvider === 'openweathermap' ? 'OpenWeatherMap API' : 'Open-Meteo API';
        
        $updatedAtDisplay = $weather && isset($weather['time'])
            ? Carbon::parse($weather['time'])->timezone('Asia/Manila')
            : now('Asia/Manila');

        return response()->json([
            'weather' => $weather,
            'location' => $location['name'],
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
            'apiProvider' => $apiName,
            'updatedAt' => $updatedAtDisplay->format('M d, Y h:i A'),
            'error' => $error,
        ]);
    }

    private function mapWeatherCode($code)
    {
        $map = [
            0 => 'Clear sky',
            1 => 'Mainly clear',
            2 => 'Partly cloudy',
            3 => 'Overcast',
            45 => 'Foggy',
            48 => 'Depositing rime fog',
            51 => 'Light drizzle',
            53 => 'Moderate drizzle',
            55 => 'Dense drizzle',
            56 => 'Freezing drizzle',
            57 => 'Heavy freezing drizzle',
            61 => 'Slight rain',
            63 => 'Moderate rain',
            65 => 'Heavy rain',
            66 => 'Freezing rain',
            67 => 'Heavy freezing rain',
            71 => 'Slight snow fall',
            73 => 'Moderate snow fall',
            75 => 'Heavy snow fall',
            77 => 'Snow grains',
            80 => 'Slight rain showers',
            81 => 'Moderate rain showers',
            82 => 'Violent rain showers',
            85 => 'Slight snow showers',
            86 => 'Heavy snow showers',
            95 => 'Thunderstorm',
            96 => 'Thunderstorm with slight hail',
            99 => 'Thunderstorm with heavy hail',
        ];

        return $map[$code] ?? 'Unavailable';
    }
}
