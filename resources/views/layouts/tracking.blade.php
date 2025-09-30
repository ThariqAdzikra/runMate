@extends('layouts.app')

@section('title', 'Start Tracking - RunTracker')

@push('styles')
    {{-- Leaflet CSS untuk peta --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <style>
        /* Pastikan peta mengisi ruang yang tersedia */
        #map {
            height: 100%;
            width: 100%;
            z-index: 10;
        }
        
        /* Layout untuk mobile-first */
        .tracking-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 64px);
            width: 100%;
            overflow: hidden;
            background: linear-gradient(to bottom, #f8fafc 0%, #e2e8f0 100%);
        }

        .map-area {
            flex-grow: 1;
            position: relative;
            border-radius: 0 0 32px 32px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        }
        
        .stats-and-controls {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 20;
            background: linear-gradient(to top, 
                rgba(255,255,255,0.98) 0%, 
                rgba(255,255,255,0.95) 60%, 
                rgba(255,255,255,0) 100%);
            padding-top: 3rem;
            backdrop-filter: blur(10px);
        }

        /* Stats Panel Enhancement */
        .stats-panel {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
                        opacity 0.4s ease-in-out;
            background: white;
            border-radius: 24px 24px 0 0;
            box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.08);
            border-top: 3px solid #365a91;
        }
        
        .stats-panel.hidden {
            transform: translateY(120%);
            opacity: 0;
        }

        /* Stat Card Individual */
        .stat-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1rem;
            border-radius: 16px;
            border: 2px solid rgba(54, 90, 145, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #365a91, #63c6ee);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(54, 90, 145, 0.15);
            border-color: rgba(54, 90, 145, 0.3);
        }

        .stat-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #365a91 0%, #63c6ee 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .stat-unit {
            font-size: 0.875rem;
            font-weight: 600;
            color: #94a3b8;
            margin-left: 0.25rem;
        }

        /* Control Buttons */
        .control-button {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            font-size: 1.5rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 4px solid white;
            position: relative;
            overflow: hidden;
        }

        .control-button::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            padding: 2px;
            background: linear-gradient(135deg, rgba(255,255,255,0.4), rgba(255,255,255,0));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }

        .control-button:hover {
            transform: scale(1.08);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
        }
        
        .control-button:active {
            transform: scale(0.95);
        }

        .btn-start-pause {
            background: linear-gradient(135deg, #365a91 0%, #63c6ee 100%);
            position: relative;
        }

        .btn-start-pause::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: linear-gradient(135deg, #365a91, #63c6ee);
            z-index: -1;
            opacity: 0;
            animation: pulse 2s infinite;
        }

        .btn-start-pause.running::after {
            opacity: 1;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.15);
                opacity: 0;
            }
        }

        .btn-stop {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        /* Icon dalam button */
        .control-button i {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* GPS Status Indicator */
        .gps-indicator {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            z-index: 15;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.5);
        }

        .gps-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Speed Meter */
        .speed-indicator {
            position: absolute;
            top: 5rem;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 0.75rem 1.5rem;
            border-radius: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            font-weight: 700;
            font-size: 1.25rem;
            color: #365a91;
            z-index: 15;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(54, 90, 145, 0.1);
            display: none;
        }

        .speed-indicator.active {
            display: block;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .stat-value {
                font-size: 1.75rem;
            }
            
            .control-button {
                width: 64px;
                height: 64px;
                font-size: 1.25rem;
            }
        }

        /* Loading State */
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 30;
            backdrop-filter: blur(8px);
        }

        .loading-spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e2e8f0;
            border-top-color: #365a91;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
<div class="tracking-container">
    <div class="map-area">
        {{-- GPS Status Indicator --}}
        <div class="gps-indicator">
            <div class="gps-dot"></div>
            <span>GPS Ready</span>
        </div>

        {{-- Speed Indicator --}}
        <div id="speed-indicator" class="speed-indicator">
            <span id="current-speed">0.0</span> <span class="text-base">km/h</span>
        </div>

        {{-- Elemen Peta --}}
        <div id="map"></div>

        {{-- Panel Statistik & Kontrol --}}
        <div class="stats-and-controls">
            {{-- Panel Statistik --}}
            <div id="stats-panel" class="stats-panel max-w-lg mx-auto px-5 py-5 mb-4 hidden">
                <div class="grid grid-cols-3 gap-3">
                    {{-- Time Card --}}
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-clock text-blue-500"></i>
                            Time
                        </div>
                        <div id="time" class="stat-value">00:00:00</div>
                    </div>
                    
                    {{-- Distance Card --}}
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-route text-green-500"></i>
                            Distance
                        </div>
                        <div>
                            <span id="distance" class="stat-value">0.00</span>
                            <span class="stat-unit">km</span>
                        </div>
                    </div>
                    
                    {{-- Pace Card --}}
                    <div class="stat-card">
                        <div class="stat-label">
                            <i class="fas fa-tachometer-alt text-orange-500"></i>
                            Pace
                        </div>
                        <div>
                            <span id="pace" class="stat-value">--:--</span>
                            <span class="stat-unit">/km</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Kontrol --}}
            <div class="flex justify-center items-center space-x-6 pb-8 px-6">
                {{-- Tombol Start akan berubah menjadi Pause/Resume --}}
                <button id="start-pause-btn" class="control-button btn-start-pause text-white flex items-center justify-center">
                    <i id="start-pause-icon" class="fas fa-play"></i>
                </button>
                {{-- Tombol Stop akan muncul setelah lari dimulai --}}
                <button id="stop-btn" class="control-button btn-stop text-white flex items-center justify-center hidden">
                    <i class="fas fa-stop"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Inisialisasi Peta Leaflet ---
    let map;
    let polyline; // Untuk menggambar rute lari
    let userMarker; // Marker untuk posisi pengguna
    const mapElement = document.getElementById('map');
    
    // Pastikan elemen peta ada sebelum inisialisasi
    if (mapElement) {
        // Center peta di lokasi default (misal: Jakarta) sebelum GPS aktif
        map = L.map('map').setView([-6.2088, 106.8456], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
    } else {
        console.error("Map element not found!");
        return; // Hentikan eksekusi jika peta tidak ada
    }
    
    // --- Variabel untuk Tracking ---
    let isRunning = false;
    let isPaused = false;
    let watchId;
    let startTime;
    let totalSeconds = 0;
    let totalDistance = 0; // dalam kilometer
    let locations = [];
    
    // --- Elemen UI ---
    const startPauseBtn = document.getElementById('start-pause-btn');
    const startPauseIcon = document.getElementById('start-pause-icon');
    const stopBtn = document.getElementById('stop-btn');
    const statsPanel = document.getElementById('stats-panel');
    const timeDisplay = document.getElementById('time');
    const distanceDisplay = document.getElementById('distance');
    const paceDisplay = document.getElementById('pace');
    const speedIndicator = document.getElementById('speed-indicator');
    const currentSpeedDisplay = document.getElementById('current-speed');

    // --- Fungsi Event Listener ---
    startPauseBtn.addEventListener('click', toggleRun);
    stopBtn.addEventListener('click', stopRun);
    
    // --- Fungsi Utama ---
    function toggleRun() {
        if (!isRunning) {
            startRun();
        } else {
            pauseResumeRun();
        }
    }

    function startRun() {
        if (!('geolocation' in navigator)) {
            alert('Geolocation is not supported by your browser.');
            return;
        }
        
        isRunning = true;
        isPaused = false;
        startTime = Date.now();
        locations = [];
        totalDistance = 0;
        totalSeconds = 0;

        // Tampilkan UI tracking
        updateButtonUI('pause');
        stopBtn.classList.remove('hidden');
        statsPanel.classList.remove('hidden');
        speedIndicator.classList.add('active');
        startPauseBtn.classList.add('running');

        // Reset tampilan statistik
        updateStatsDisplay();

        // Mulai memantau lokasi GPS
        watchId = navigator.geolocation.watchPosition(
            handleLocationSuccess, 
            handleLocationError, 
            { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
        );
        
        // Mulai timer
        setInterval(updateTimer, 1000);
    }

    function pauseResumeRun() {
        isPaused = !isPaused;
        if (isPaused) {
            updateButtonUI('resume');
            startPauseBtn.classList.remove('running');
            navigator.geolocation.clearWatch(watchId); // Hentikan sementara pemantauan GPS
        } else {
            updateButtonUI('pause');
            startPauseBtn.classList.add('running');
            // Lanjutkan pemantauan GPS
            watchId = navigator.geolocation.watchPosition(
                handleLocationSuccess, 
                handleLocationError, 
                { enableHighAccuracy: true }
            );
        }
    }

    function stopRun() {
        if (!confirm('Are you sure you want to stop this run?')) {
            return;
        }
        
        isRunning = false;
        isPaused = false;
        navigator.geolocation.clearWatch(watchId);
        
        // Sembunyikan UI tracking
        updateButtonUI('start');
        stopBtn.classList.add('hidden');
        speedIndicator.classList.remove('active');
        startPauseBtn.classList.remove('running');
        
        // Disini Anda bisa menambahkan logika untuk menyimpan data lari ke server
        alert(`Run finished!\nDistance: ${totalDistance.toFixed(2)} km\nTime: ${formatTime(totalSeconds)}`);
        
        // Reset peta (opsional)
        // resetMap();
    }
    
    // --- Fungsi Helper ---

    function handleLocationSuccess(position) {
        if (isPaused) return;

        const { latitude, longitude, speed } = position.coords;
        const newPoint = [latitude, longitude];

        // Update speed indicator
        if (speed !== null && speed > 0) {
            const speedKmh = (speed * 3.6).toFixed(1);
            currentSpeedDisplay.textContent = speedKmh;
        }

        // Center peta pada lokasi pengguna saat pertama kali dideteksi
        if (locations.length === 0) {
            map.setView(newPoint, 16);
        }

        // Hitung jarak jika ini bukan titik pertama
        if (locations.length > 0) {
            const lastPoint = locations[locations.length - 1];
            totalDistance += calculateDistance(lastPoint, newPoint);
        }

        locations.push(newPoint);
        updateMap(newPoint);
        updateStatsDisplay();
    }

    function handleLocationError(error) {
        console.warn(`ERROR(${error.code}): ${error.message}`);
        alert('Could not get your location. Please ensure location services are enabled.');
    }

    function updateMap(currentPoint) {
        // Update marker posisi pengguna
        if (!userMarker) {
            userMarker = L.marker(currentPoint).addTo(map).bindPopup('You are here');
        } else {
            userMarker.setLatLng(currentPoint);
        }

        // Gambar atau perbarui garis rute
        if (!polyline) {
            polyline = L.polyline(locations, { color: '#365a91', weight: 4 }).addTo(map);
        } else {
            polyline.setLatLngs(locations);
        }

        // Jaga agar pengguna selalu terlihat di peta
        map.panTo(currentPoint);
    }

    function updateTimer() {
        if (!isRunning || isPaused) return;
        totalSeconds++;
        updateStatsDisplay();
    }

    function updateStatsDisplay() {
        timeDisplay.textContent = formatTime(totalSeconds);
        distanceDisplay.textContent = totalDistance.toFixed(2);
        paceDisplay.textContent = calculatePace();
    }

    function updateButtonUI(state) {
        if (state === 'pause') {
            startPauseIcon.classList.remove('fa-play', 'fa-redo');
            startPauseIcon.classList.add('fa-pause');
        } else if (state === 'resume') {
            startPauseIcon.classList.remove('fa-pause');
            startPauseIcon.classList.add('fa-play');
        } else { // start
            startPauseIcon.classList.remove('fa-pause');
            startPauseIcon.classList.add('fa-play');
        }
    }
    
    function calculateDistance(point1, point2) {
        const R = 6371; // Radius bumi dalam km
        const dLat = (point2[0] - point1[0]) * Math.PI / 180;
        const dLon = (point2[1] - point1[1]) * Math.PI / 180;
        const a = 
            0.5 - Math.cos(dLat)/2 + 
            Math.cos(point1[0] * Math.PI / 180) * Math.cos(point2[0] * Math.PI / 180) * (1 - Math.cos(dLon)) / 2;
        return R * 2 * Math.asin(Math.sqrt(a));
    }

    function calculatePace() {
        if (totalDistance === 0 || totalSeconds === 0) {
            return '--:--';
        }
        const paceDecimal = (totalSeconds / 60) / totalDistance;
        const paceMinutes = Math.floor(paceDecimal);
        const paceSeconds = Math.round((paceDecimal - paceMinutes) * 60);
        return `${String(paceMinutes).padStart(2, '0')}:${String(paceSeconds).padStart(2, '0')}`;
    }

    function formatTime(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        return [
            h,
            m,
            s
        ].map(v => String(v).padStart(2, '0')).join(':');
    }

    function resetMap() {
        if (userMarker) map.removeLayer(userMarker);
        if (polyline) map.removeLayer(polyline);
        userMarker = null;
        polyline = null;
    }
});
</script>
@endpush