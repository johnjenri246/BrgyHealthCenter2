<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Barangay Health Center Location
    |--------------------------------------------------------------------------
    |
    | Configure the location of your barangay health center/barangay hall
    | for real-time weather data. You can either:
    | 1. Set latitude and longitude directly
    | 2. Set an address and the system will geocode it (requires OpenWeatherMap API key)
    |
    */

    // Option 1: Direct coordinates (recommended)
    'latitude' => env('BARANGAY_LATITUDE', 14.6760),  // Default: Quezon City
    'longitude' => env('BARANGAY_LONGITUDE', 121.0437), // Default: Quezon City
    
    // Option 2: Address (will be geocoded if coordinates not set)
    'address' => env('BARANGAY_ADDRESS', 'Barangay Hall, Quezon City, Philippines'),
    
    // Location display name
    'location_name' => env('BARANGAY_LOCATION_NAME', 'Barangay Health Center'),
    
    // Weather API Configuration
    'api' => [
        // Open-Meteo API (free, no API key required)
        'provider' => env('WEATHER_API_PROVIDER', 'openmeteo'), // 'openmeteo' or 'openweathermap'
        
        // OpenWeatherMap API (optional, requires API key)
        'openweathermap_key' => env('OPENWEATHERMAP_API_KEY', null),
    ],
];

