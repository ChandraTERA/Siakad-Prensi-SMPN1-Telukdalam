<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - SIAKAD</title>
    @vite('resources/css/app.css')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-80 bg-gradient-to-b from-blue-900 to-blue-800 text-white">
                <!-- Header -->
                <div class="p-6 border-b border-blue-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold">SIAKAD</h1>
                            <p class="text-blue-200 text-sm">Admin Panel</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4 overflow-y-auto">
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Data Master Section -->
                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-semibold text-blue-200 uppercase tracking-wider mb-2">Data
                                Master</h3>

                            <!-- Manajemen Guru -->
                            <a href="{{ route('admin.guru.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.guru.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                <span>Manajemen Guru</span>
                            </a>

                            <!-- Manajemen Siswa -->
                            <a href="{{ route('admin.siswa.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.siswa.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <span>Manajemen Siswa</span>
                            </a>

                            <!-- Manajemen Kelas -->
                            <a href="{{ route('admin.kelas.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.kelas.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                <span>Manajemen Kelas</span>
                            </a>

                            <!-- Lokasi Sekolah -->
                            <a href="{{ route('admin.school-location.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.school-location.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Lokasi Sekolah</span>
                            </a>
                        </div>

                        <!-- Akademik Section -->
                        <div class="pt-4">
                            <h3 class="px-4 text-xs font-semibold text-blue-200 uppercase tracking-wider mb-2">Akademik
                            </h3>

                            <a href="#"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.mapel.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                <span>Mata Pelajaran</span>
                            </a>

                            <a href="#"
                                class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.jadwal.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0v13a2 2 0 002 2h4a2 2 0 002-2V7m-8 0V6a2 2 0 012-2h4a2 2 0 012 2v1">
                                    </path>
                                </svg>
                                <span>Jadwal Pelajaran</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- User Info -->
                <div class="p-4 border-t border-blue-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-blue-200 truncate capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="text-blue-200 hover:text-white transition-colors duration-200" title="Logout">
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

        <!-- Mobile Sidebar Overlay -->
        <div class="lg:hidden">
            <div class="fixed inset-0 z-40 flex" id="mobile-sidebar" style="display: none;">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileSidebar()"></div>
                <div class="relative flex flex-col flex-1 w-full max-w-xs bg-gradient-to-b from-blue-900 to-blue-800">
                    <!-- Mobile sidebar content (same as desktop) -->
                    <div class="flex flex-col h-full text-white">
                        <!-- Header -->
                        <div class="p-6 border-b border-blue-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.84l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-xl font-bold">SIAKAD</h1>
                                        <p class="text-blue-200 text-sm">Admin Panel</p>
                                    </div>
                                </div>
                                <button onclick="toggleMobileSidebar()" class="text-blue-200 hover:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Navigation (same as desktop) -->
                        <nav class="flex-1 p-4 overflow-y-auto">
                            <div class="space-y-2">
                                <!-- Dashboard -->
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                    </svg>
                                    <span>Dashboard</span>
                                </a>

                                <!-- Data Master Section -->
                                <div class="pt-4">
                                    <h3 class="px-4 text-xs font-semibold text-blue-200 uppercase tracking-wider mb-2">
                                        Data Master</h3>

                                    <a href="{{ route('admin.guru.index') }}"
                                        class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.guru.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                            </path>
                                        </svg>
                                        <span>Manajemen Guru</span>
                                    </a>

                                    <a href="{{ route('admin.siswa.index') }}"
                                        class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.siswa.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                        <span>Manajemen Siswa</span>
                                    </a>

                                    <a href="{{ route('admin.kelas.index') }}"
                                        class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.kelas.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        <span>Manajemen Kelas</span>
                                    </a>

                                    <!-- Lokasi Sekolah -->
                                    <a href="{{ route('admin.school-location.index') }}"
                                        class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.school-location.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>Lokasi Sekolah</span>
                                    </a>
                                </div>

                                <!-- Akademik Section -->
                                <div class="pt-4">
                                    <h3 class="px-4 text-xs font-semibold text-blue-200 uppercase tracking-wider mb-2">
                                        Akademik
                                    </h3>

                                    <a href="#"
                                        class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.mapel.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                        <span>Mata Pelajaran</span>
                                    </a>

                                    <a href="#"
                                        class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.jadwal.*') ? 'bg-blue-700 text-white' : 'text-blue-100 hover:bg-blue-700 hover:text-white' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0v13a2 2 0 002 2h4a2 2 0 002-2V7m-8 0V6a2 2 0 012-2h4a2 2 0 012 2v1">
                                            </path>
                                        </svg>
                                        <span>Jadwal Pelajaran</span>
                                    </a>
                                </div>
                            </div>
                        </nav>

                        <!-- User Info -->
                        <div class="p-4 border-t border-blue-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-blue-200 truncate capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <button type="button" id="admin-logout-btn"
                                    class="text-blue-200 hover:text-white transition-colors duration-200"
                                    title="Logout">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Mobile Header -->
            <div class="lg:hidden bg-white shadow-sm border-b">
                <div class="px-4 py-3 flex items-center justify-between">
                    <button onclick="toggleMobileSidebar()" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">SIAKAD Admin</h1>
                    <div></div>
                </div>
            </div>

            <!-- Desktop Header -->
            <div class="hidden lg:block bg-white shadow-sm border-b">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-gray-600 mt-1">@yield('page-description', 'Kelola sistem informasi akademik')</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Notification Bell -->
                            <x-notification-bell />

                            <!-- User Info -->
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutBtn = document.getElementById('admin-logout-btn');

            if (logoutBtn) {
                logoutBtn.addEventListener('click', async function(e) {
                    e.preventDefault();

                    try {
                        const response = await fetch('/api/auth/logout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            window.location.href = data.redirect_url;
                        } else {
                            console.error('Logout failed:', data.message);
                            // Fallback to traditional logout
                            window.location.href = '{{ route('logout') }}';
                        }
                    } catch (error) {
                        console.error('Logout error:', error);
                        // Fallback to traditional logout
                        window.location.href = '{{ route('logout') }}';
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
