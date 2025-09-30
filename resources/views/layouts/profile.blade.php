@extends('layouts.app')

@section('title', 'My Profile - RunTracker')

@push('styles')
<style>
    /* Menggunakan style yang konsisten dengan dashboard */
    .post-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .post-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 128px;
        height: 128px;
        margin: -80px auto 1rem;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .profile-avatar-wrapper .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }

    .profile-avatar-wrapper:hover .overlay {
        opacity: 1;
    }

    .profile-header-bg {
        background: linear-gradient(135deg, #365a91 0%, #63c6ee 100%);
        height: 200px;
        border-radius: 16px;
    }

    .stat-item i {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .tab-btn {
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #4B5563; /* text-gray-600 */
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .tab-btn.active {
        color: #3B82F6; /* text-blue-500 */
        border-bottom-color: #3B82F6;
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.5s ease-in-out;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    
    <!-- Error Validasi -->
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6" role="alert">
            <p class="font-bold">Oops! Something went wrong.</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info Profil & Statistik -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Kartu Profil Utama -->
            <div class="post-card text-center">
                <div class="profile-header-bg"></div>
                <div class="px-6 pb-6">
                    <div class="profile-avatar-wrapper">
                        <img class="w-full h-full rounded-full object-cover" 
                             src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=365a91&background=63c6ee' }}" 
                             alt="{{ Auth::user()->name }}">
                        <label for="profile_photo" class="overlay">
                            <i class="fas fa-camera text-2xl"></i>
                        </label>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h1>
                    <p class="text-gray-500">@{{ strtolower(str_replace(' ', '', Auth::user()->name)) }}</p>

                    <!-- Form Upload Foto (Tersembunyi) -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="text-center mt-4">
                        @csrf
                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" onchange="this.form.submit()">
                        <p class="text-xs text-gray-400 mt-2">Click on the photo to change it.</p>
                    </form>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="post-card p-6">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">My Stats</h3>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-running text-blue-600 bg-blue-100 stat-item"></i>
                        <div>
                            <p class="font-semibold text-gray-800">127</p>
                            <p class="text-sm text-gray-500">Total Runs</p>
                        </div>
                    </div>
                     <div class="flex items-center space-x-4">
                        <i class="fas fa-road text-green-600 bg-green-100 stat-item"></i>
                        <div>
                            <p class="font-semibold text-gray-800">847 km</p>
                            <p class="text-sm text-gray-500">Total Distance</p>
                        </div>
                    </div>
                     <div class="flex items-center space-x-4">
                        <i class="fas fa-fire text-orange-600 bg-orange-100 stat-item"></i>
                        <div>
                            <p class="font-semibold text-gray-800">65,210 cal</p>
                            <p class="text-sm text-gray-500">Calories Burned</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-trophy text-purple-600 bg-purple-100 stat-item"></i>
                        <div>
                            <p class="font-semibold text-gray-800">21:45</p>
                            <p class="text-sm text-gray-500">Best 5K Time</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Aktivitas, Achievements, Settings -->
        <div class="lg:col-span-2">
            <div class="post-card">
                <!-- Navigasi Tab -->
                <div class="border-b border-gray-200">
                    <nav class="flex px-6 -mb-px">
                        <button class="tab-btn active" data-tab="activity">Activity</button>
                        <button class="tab-btn" data-tab="achievements">Achievements</button>
                        <button class="tab-btn" data-tab="settings">Settings</button>
                    </nav>
                </div>

                <!-- Konten Tab -->
                <div class="p-6">
                    <!-- Tab Aktivitas -->
                    <div id="activity" class="tab-content active">
                        <h3 class="font-bold text-gray-800 mb-4 text-lg">Recent Activities</h3>
                        <ul class="space-y-4">
                            <li class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Morning Run - Central Park</p>
                                    <p class="text-sm text-gray-500">5.2 KM • 28:34 MIN • 2 hours ago</p>
                                </div>
                            </li>
                             <li class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-mountain text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Trail Run - Riverside</p>
                                    <p class="text-sm text-gray-500">10.1 KM • 55:12 MIN • 1 day ago</p>
                                </div>
                            </li>
                             <li class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-bolt text-orange-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Speed Session - Track</p>
                                    <p class="text-sm text-gray-500">8.0 KM • 38:05 MIN • 3 days ago</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Tab Achievements -->
                    <div id="achievements" class="tab-content">
                        <h3 class="font-bold text-gray-800 mb-4 text-lg">My Achievements</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Unlocked -->
                            <div class="border border-gray-200 rounded-lg p-4 flex items-center space-x-4">
                                <i class="fas fa-trophy text-yellow-500 text-3xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Marathon Finisher</h4>
                                    <p class="text-sm text-gray-500">Completed a full marathon.</p>
                                </div>
                            </div>
                            <!-- Unlocked -->
                            <div class="border border-gray-200 rounded-lg p-4 flex items-center space-x-4">
                                <i class="fas fa-mountain text-green-500 text-3xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Hill Conqueror</h4>
                                    <p class="text-sm text-gray-500">Climbed 1000m elevation.</p>
                                </div>
                            </div>
                            <!-- Locked -->
                            <div class="border border-gray-200 rounded-lg p-4 flex items-center space-x-4 opacity-60">
                                <i class="fas fa-lock text-gray-400 text-3xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-500">5K Speedster</h4>
                                    <p class="text-sm text-gray-500">Finish a 5K under 20 min.</p>
                                </div>
                            </div>
                             <!-- Locked -->
                            <div class="border border-gray-200 rounded-lg p-4 flex items-center space-x-4 opacity-60">
                                <i class="fas fa-lock text-gray-400 text-3xl"></i>
                                <div>
                                    <h4 class="font-semibold text-gray-500">Consistency King</h4>
                                    <p class="text-sm text-gray-500">Run 30 days in a row.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Settings -->
                    <div id="settings" class="tab-content">
                         <h3 class="font-bold text-gray-800 mb-4 text-lg">Account Settings</h3>
                         <div class="space-y-4">
                             <div>
                                 <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                 <input type="text" id="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                             </div>
                             <div>
                                 <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                 <input type="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" disabled>
                             </div>
                             <button class="gradient-primary text-white px-6 py-2 rounded-full font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                Save Changes
                             </button>
                         </div>
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
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Deactivate all buttons and hide content
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Activate clicked button and show corresponding content
                const tabName = this.getAttribute('data-tab');
                this.classList.add('active');
                document.getElementById(tabName).classList.add('active');
            });
        });
    });
</script>
@endpush
