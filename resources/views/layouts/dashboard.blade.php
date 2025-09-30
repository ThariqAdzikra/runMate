@extends('layouts.app')

@section('title', 'Dashboard - RunTracker')

@push('styles')
<style>
    /* Story circles */
    .story-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        padding: 3px;
        background: linear-gradient(45deg, #f093fb, #f5576c, #4facfe);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .story-circle:hover {
        transform: scale(1.05);
    }
    
    .story-inner {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid white;
        overflow: hidden;
    }

    /* Achievement badge */
    .achievement-badge {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="lg:w-80 space-y-6">
            <div class="post-card p-6">
                <h3 id="weather-location" class="font-bold text-gray-800 mb-4">Current Weather</h3>
                <div class="space-y-4">
                    <div id="weather-widget">
                        <div id="weather-content" class="flex items-center space-x-3">
                            <div class="animate-spin">
                                <i class="fas fa-spinner text-blue-500 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Getting location...</p>
                        </div>
                    </div>

                    <div class="text-center pt-4 border-t border-gray-100">
                        <p id="clock-time" class="text-2xl font-bold text-gray-800">00:00:00</p>
                        <p id="current-date" class="text-sm text-gray-500">Loading date...</p>
                    </div>
                </div>
            </div>
            
            <div class="post-card p-6">
                <h3 class="font-bold text-gray-800 mb-4">Your Week</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-route text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">24.5 km</p>
                                <p class="text-sm text-gray-500">Distance</p>
                            </div>
                        </div>
                        <span class="text-green-500 font-semibold text-sm">+12%</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-fire text-orange-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">1,247 cal</p>
                                <p class="text-sm text-gray-500">Burned</p>
                            </div>
                        </div>
                        <span class="text-green-500 font-semibold text-sm">+8%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 max-w-xl mx-auto space-y-6">
            <div class="post-card overflow-hidden">
                <div class="gradient-primary p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-2">
                                Hey {{ Auth::user()->name }}! 👋
                            </h2>
                            <p class="text-blue-100">Ready for your next adventure?</p>
                        </div>
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-running text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="post-card">
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=365a91&background=63c6ee' }}" 
                             alt="Your avatar" class="w-12 h-12 rounded-full object-cover">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">completed a morning run • 2 hours ago</p>
                        </div>
                    </div>

                    <p class="text-gray-800 mb-3">Great morning run in the park! 🌅 The weather was perfect. Feeling energized!</p>
                    
                    <div class="bg-gray-50 rounded-lg p-4 grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-gray-800">5.2</p>
                            <p class="text-sm text-gray-500">km</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">28:34</p>
                            <p class="text-sm text-gray-500">duration</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800">5:29</p>
                            <p class="text-sm text-gray-500">avg pace</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:w-80 space-y-6">
              <div class="post-card p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-fire text-orange-500 mr-2"></i>
                    Highlights
                </h3>
                <div class="flex space-x-4 overflow-x-auto pb-2">
                    <div class="flex-shrink-0 text-center">
                        <div class="story-circle">
                            <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&color=365a91&background=63c6ee" 
                                 alt="Sarah" class="story-inner object-cover">
                        </div>
                        <p class="text-xs text-gray-600 mt-2">Sarah</p>
                    </div>
                    <div class="flex-shrink-0 text-center">
                        <div class="story-circle">
                            <img src="https://ui-avatars.com/api/?name=Mike+Chen&color=ed9774&background=e85a4f" 
                                 alt="Mike" class="story-inner object-cover">
                        </div>
                        <p class="text-xs text-gray-600 mt-2">Mike</p>
                    </div>
                </div>
            </div>
            
            <div class="post-card p-6">
                <h3 class="font-bold text-gray-800 mb-4">Trending Challenges</h3>
                <div class="space-y-3">
                    <div class="p-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg text-white">
                        <p class="font-semibold text-sm">30-Day Run Streak</p>
                        <p class="text-xs opacity-80">2.3k participants</p>
                    </div>
                    <div class="p-3 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg text-white">
                        <p class="font-semibold text-sm">5K in Under 25min</p>
                        <p class="text-xs opacity-80">892 participants</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Clock and Date Logic ---
    function updateClockAndDate() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-GB');
        document.getElementById('clock-time').textContent = timeString;

        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'long',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('current-date').textContent = dateString;
    }

    // --- Weather Logic Based on User Location ---
    const weatherWidget = document.getElementById('weather-content');
    const locationTitle = document.getElementById('weather-location');
    const apiKey = 'bef5209cb14428d6d1bd74731052cb41'; // Your OpenWeather API key

    function getLocalWeather() {
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(handleLocationSuccess, handleLocationError);
        } else {
            weatherWidget.innerHTML = `<p class="text-sm text-red-500">Geolocation is not supported.</p>`;
        }
    }

    function handleLocationSuccess(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        // NEW: First get the city name from coordinates, then fetch weather
        fetchCityAndWeather(lat, lon);
    }

    function handleLocationError(error) {
        console.error("Geolocation Error:", error.message);
        if(error.code == 1) { // 1 = PERMISSION_DENIED
             weatherWidget.innerHTML = `<p class="text-sm text-yellow-600">Location access denied. Showing weather for Pekanbaru instead.</p>`;
        } else {
             weatherWidget.innerHTML = `<p class="text-sm text-red-500">Could not get your location.</p>`;
        }
        // Fallback to a default city if location fails
        fetchWeatherByCity('Pekanbaru');
    }

    // NEW FUNCTION: To get city name first, then fetch weather
    async function fetchCityAndWeather(lat, lon) {
        // Use the Reverse Geocoding API endpoint
        const geocodeUrl = `https://api.openweathermap.org/geo/1.0/reverse?lat=${lat}&lon=${lon}&limit=1&appid=${apiKey}`;
        try {
            const geoResponse = await fetch(geocodeUrl);
            if (!geoResponse.ok) throw new Error('Could not determine city name from coordinates.');
            
            const geoData = await geoResponse.json();
            
            if (geoData.length > 0 && geoData[0].name) {
                const cityName = geoData[0].name;
                // Now that we have the correct city name, fetch weather for it
                await fetchWeatherByCity(cityName);
            } else {
                throw new Error('City name not found in geocoding response.');
            }
        } catch (error) {
            console.error("Geocoding Error:", error.message);
            weatherWidget.innerHTML = `<p class="text-sm text-yellow-600">Could not find city. Showing weather for Pekanbaru instead.</p>`;
            await fetchWeatherByCity('Pekanbaru'); // Fallback on geocoding error
        }
    }
    
    async function fetchWeatherByCity(city) {
        const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;
        await fetchWeatherData(url);
    }

    async function fetchWeatherData(url) {
        const weatherIcons = {
            '01d': 'fa-sun', '01n': 'fa-moon', '02d': 'fa-cloud-sun', '02n': 'fa-cloud-moon',
            '03d': 'fa-cloud', '03n': 'fa-cloud', '04d': 'fa-cloud-meatball', '04n': 'fa-cloud-meatball',
            '09d': 'fa-cloud-showers-heavy', '09n': 'fa-cloud-showers-heavy',
            '10d': 'fa-cloud-sun-rain', '10n': 'fa-cloud-moon-rain',
            '11d': 'fa-poo-storm', '11n': 'fa-poo-storm', '13d': 'fa-snowflake', '13n': 'fa-snowflake',
            '50d': 'fa-smog', '50n': 'fa-smog',
        };

        try {
            weatherWidget.innerHTML = `
                <div class="animate-spin">
                    <i class="fas fa-spinner text-blue-500 text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm">Fetching weather...</p>`;

            const response = await fetch(url);
            if (!response.ok) throw new Error('Weather data could not be retrieved.');
            
            const data = await response.json();
            
            const temp = Math.round(data.main.temp);
            const description = data.weather[0].description.replace(/\b\w/g, l => l.toUpperCase());
            const iconCode = data.weather[0].icon;
            const faIcon = weatherIcons[iconCode] || 'fa-cloud';
            
            // This will now correctly show the City Name
            locationTitle.textContent = `Right Now in ${data.name}`; 
            
            weatherWidget.innerHTML = `
                <i class="fas ${faIcon} text-blue-500 text-3xl"></i>
                <div>
                    <p class="font-bold text-2xl text-gray-800">${temp}°C</p>
                    <p class="text-sm text-gray-500">${description}</p>
                </div>
            `;
        } catch (error) {
            console.error('Error fetching weather:', error);
            weatherWidget.innerHTML = `<p class="text-red-500 text-sm font-semibold">${error.message}</p>`;
        }
    }

    // Initial calls
    updateClockAndDate();
    getLocalWeather();
    setInterval(updateClockAndDate, 1000);
});
</script>
@endpush