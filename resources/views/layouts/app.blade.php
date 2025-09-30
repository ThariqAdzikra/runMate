<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RunTracker')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        /* Custom gradient backgrounds */
        .gradient-primary {
            background: linear-gradient(135deg, #365a91 0%, #63c6ee 100%);
        }
        
        .gradient-secondary {
            background: linear-gradient(135deg, #ed9774 0%, #e85a4f 100%);
        }
        
        .gradient-story {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        /* Social media style cards */
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

        /* Navigation sticky */
        .nav-sticky {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* Dropdown animation */
        .dropdown-menu {
            display: none;
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
            transition: all 0.3s ease;
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    </style>
    {{-- Stack untuk style khusus per halaman --}}
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation -->
    <nav class="nav-sticky bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full gradient-primary flex items-center justify-center">
                        <i class="fas fa-running text-white text-lg"></i>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800">Run<span class="text-blue-500">Tracker</span></h1>
                </a>

                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-lg mx-8">
                    <div class="w-full relative">
                        <input type="text" 
                               placeholder="Search for routes, runners, challenges..." 
                               class="w-full pl-10 pr-4 py-2 bg-gray-100 border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('tracking.start') }}" class="gradient-secondary text-white px-6 py-2 rounded-full font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 text-sm inline-block">
                        <i class="fas fa-plus mr-2"></i>
                         New Run
                    </a>
                    
                    <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute top-1 right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- Profile Dropdown -->
                    @auth
                    <div class="relative dropdown">
                        <button class="flex items-center space-x-2 focus:outline-none">
                            <img class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-500" 
                                 src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=365a91&background=63c6ee' }}" 
                                 alt="{{ Auth::user()->name }}">
                        </button>
                        <div class="dropdown-menu absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl py-2 border border-gray-100">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-3">
                                <i class="fas fa-user-circle w-4 text-gray-400"></i>
                                <span>My Profile</span>
                            </a>
                            <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center space-x-3">
                                <i class="fas fa-cog w-4 text-gray-400"></i>
                                <span>Settings</span>
                            </a>
                            <div class="border-t border-gray-100 mt-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center space-x-3">
                                    <i class="fas fa-sign-out-alt w-4"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    {{-- Stack untuk script khusus per halaman --}}
    @stack('scripts')
</body>
</html>
