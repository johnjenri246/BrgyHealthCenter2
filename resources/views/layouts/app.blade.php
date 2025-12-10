<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Barangay Health System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('head')
</head>
<body class="bg-gray-100 font-sans">

    @if(session('success'))
        <!-- Success Message Popup -->
        <div id="success-alert" class="fixed top-5 right-5 z-50 p-4 bg-green-500 text-white rounded-lg shadow-xl transition-opacity duration-300" 
             style="animation: fadeout 5s forwards;">
            {{ session('success') }}
        </div>
        <script>
            // Simple JS to hide the alert after 4 seconds
            setTimeout(() => {
                document.getElementById('success-alert').style.opacity = '0';
            }, 4000);
            setTimeout(() => {
                document.getElementById('success-alert').style.display = 'none';
            }, 5000);
        </script>
    @endif

    <div class="flex flex-col min-h-screen">
        <!-- Mobile top bar -->
        <header class="md:hidden sticky top-0 z-30 bg-gray-900 text-white flex items-center justify-between px-4 py-3 shadow">
            <div class="flex items-center gap-2">
                <button id="mobile-menu-toggle" class="p-2 rounded hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400" aria-label="Open navigation">
                    <!-- Simple hamburger icon -->
                    <span class="block w-6 h-0.5 bg-white mb-1"></span>
                    <span class="block w-6 h-0.5 bg-white mb-1"></span>
                    <span class="block w-6 h-0.5 bg-white"></span>
                </button>
                <div>
                    <p class="text-sm font-semibold">Barangay App</p>
                    <p class="text-xs text-gray-300">Health & Records</p>
                </div>
            </div>
            <div class="text-xs">
                @auth
                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                @else
                    <a href="{{ route('login') }}" class="underline">Login</a>
                @endauth
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Backdrop for mobile -->
            <div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

            <!-- LEFT SIDEBAR NAVBAR -->
            <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white flex flex-col z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-200 md:static md:h-auto md:flex-shrink-0">
                <div class="p-6 text-center border-b border-gray-800">
                    <h1 class="text-2xl font-bold text-blue-400">Barangay App</h1>
                    <p class="text-xs text-gray-500 mt-1">Health & Records</p>
                </div>

                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <a href="{{ route('home') }}" class="block py-2.5 px-4 rounded transition duration-200 
                        @if(request()->routeIs('home')) bg-gray-700 text-white @else hover:bg-gray-800 @endif">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('appointments.create') }}" class="block py-2.5 px-4 rounded transition duration-200 
                        @if(request()->routeIs('appointments.*')) bg-gray-700 text-white @else hover:bg-gray-800 @endif">
                        👥 Make an Appointment
                    </a>
                    <a href="{{ route('requests.create') }}" class="block py-2.5 px-4 rounded transition duration-200 
                        @if(request()->routeIs('requests.*')) bg-gray-700 text-white @else hover:bg-gray-800 @endif">
                        🏥 Health Records
                    </a>
                    <a href="{{ route('weather') }}" class="block py-2.5 px-4 rounded transition duration-200 
                        @if(request()->routeIs('weather')) bg-gray-700 text-white @else hover:bg-gray-800 @endif">
                        🌤️ Weather
                    </a>
                </nav>
                <!-- End Navigation Links -->

                <div class="p-4 border-t border-gray-800">
                    @auth
                        <div class="mb-4 text-sm text-gray-400">
                            Logged in as <span class="text-white font-bold">{{ Auth::user()->name }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Logout
                            </button>
                        </form>
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="block w-full bg-transparent border border-gray-500 hover:border-white text-gray-300 hover:text-white text-center font-bold py-2 px-4 rounded">
                                Sign Up
                            </a>
                        </div>
                    @endauth
                </div>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 md:ml-64 p-4 md:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto w-full">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        (() => {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobile-menu-toggle');
            const backdrop = document.getElementById('sidebar-backdrop');

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            };

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            };

            toggle?.addEventListener('click', () => {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            backdrop?.addEventListener('click', closeSidebar);

            // Ensure sidebar is visible on resize for md+ screens
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            });
        })();
    </script>
</body>
</html>