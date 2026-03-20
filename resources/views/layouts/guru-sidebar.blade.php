<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Guru') - SIAKAD</title>
    @vite('resources/css/app.css')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="flex flex-shrink-0">
            <div class="flex flex-col w-80 bg-gradient-to-b from-green-900 to-green-800 text-white">
                <!-- Header -->
                <div class="p-6 border-b border-green-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold">SIAKAD</h1>
                            <p class="text-green-200 text-sm">Panel Guru</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 overflow-y-auto">

                    <!-- Main Menu -->
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('guru.dashboard') }}"
                            class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.dashboard') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Presensi Section -->
                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-semibold text-green-200 uppercase tracking-wider mb-2">Presensi
                            </h3>

                            <!-- Input Presensi -->
                            <a href="{{ route('guru.presensi.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.presensi.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Input Presensi</span>
                            </a>
                        </div>

                        <!-- Tugas Section -->
                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-semibold text-green-200 uppercase tracking-wider mb-2">Tugas
                            </h3>

                            <!-- Kelola Tugas -->
                            <a href="{{ route('guru.assignment.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.assignment.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Kelola Tugas</span>
                            </a>
                        </div>

                        <!-- Data Section -->
                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-semibold text-green-200 uppercase tracking-wider mb-2">Data
                            </h3>

                            <!-- Data Siswa -->
                            <a href="{{ route('guru.siswa.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.siswa.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <span>Data Siswa</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- User Info -->
                <div class="p-4 border-t border-green-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-green-200">Guru</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-green-200 hover:text-white p-1 rounded transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="fixed inset-0 z-50 lg:hidden" style="display: none;">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileSidebar()"></div>
            <div class="relative flex flex-col w-80 bg-gradient-to-b from-green-900 to-green-800 text-white h-full">
                <!-- Same content as desktop sidebar -->
                <div class="p-6 border-b border-green-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold">SIAKAD</h1>
                                <p class="text-green-200 text-sm">Panel Guru</p>
                            </div>
                        </div>
                        <button onclick="toggleMobileSidebar()" class="text-green-200 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <nav class="flex-1 p-4 overflow-y-auto">
                    <!-- Same navigation as desktop -->
                    <div class="space-y-2">
                        <a href="{{ route('guru.dashboard') }}"
                            class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.dashboard') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-semibold text-green-200 uppercase tracking-wider mb-2">
                                Presensi</h3>
                            <a href="{{ route('guru.presensi.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.presensi.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Input Presensi</span>
                            </a>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-semibold text-green-200 uppercase tracking-wider mb-2">Data
                            </h3>
                            <a href="{{ route('guru.siswa.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.siswa.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <span>Data Siswa</span>
                            </a>
                        </div>

                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-semibold text-green-200 uppercase tracking-wider mb-2">Materi
                            </h3>
                            <a href="{{ route('guru.materi.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.materi.*') ? 'bg-green-700 text-white' : 'text-green-100 hover:bg-green-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Materi Pembelajaran</span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <div class="hidden">
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <button onclick="toggleMobileSidebar()" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        <div class="w-6"></div>
                    </div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-gray-600 mt-1">@yield('page-description', 'Panel guru untuk mengelola presensi dan data siswa')</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Notification Bell -->
                            <x-notification-bell />

                            <!-- User Info -->
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-sm font-medium text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                sidebar.style.display = 'flex';
            } else {
                sidebar.style.display = 'none';
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
