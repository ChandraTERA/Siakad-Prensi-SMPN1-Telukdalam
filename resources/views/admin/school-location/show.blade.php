@extends('layouts.admin-sidebar')

@section('page-title', 'Detail Lokasi Sekolah')
@section('page-description', 'Lihat detail koordinat GPS dan radius untuk validasi absensi')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Lokasi Sekolah</h1>
                    <p class="text-gray-600 mt-1">Informasi lengkap koordinat GPS dan radius untuk validasi absensi</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.school-location.edit', $schoolLocation) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.school-location.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-8">
            @if ($schoolLocation->is_active)
                <div
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Lokasi Aktif
                </div>
            @else
                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Lokasi Nonaktif
                </div>
            @endif
        </div>

        <!-- Main Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Dasar</h2>
                    <p class="text-sm text-gray-600 mt-1">Data lokasi sekolah</p>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Lokasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $schoolLocation->nama_lokasi }}</dd>
                        </div>
                        @if ($schoolLocation->alamat)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $schoolLocation->alamat }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $schoolLocation->created_at->format('d M Y, H:i') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $schoolLocation->updated_at->format('d M Y, H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- GPS Coordinates -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Koordinat GPS</h2>
                    <p class="text-sm text-gray-600 mt-1">Koordinat untuk validasi lokasi</p>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Latitude</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">
                                {{ number_format($schoolLocation->latitude, 8) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Longitude</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">
                                {{ number_format($schoolLocation->longitude, 8) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Radius</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $schoolLocation->radius_meter }} meter</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Map Preview -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Preview Peta</h2>
                <p class="text-sm text-gray-600 mt-1">Lokasi sekolah di Google Maps</p>
            </div>
            <div class="p-6">
                <div class="bg-gray-100 rounded-lg p-4 text-center">
                    <div class="mb-4">
                        <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $schoolLocation->nama_lokasi }}</h3>
                    <p class="text-sm text-gray-600 mb-4">Koordinat: {{ number_format($schoolLocation->latitude, 6) }},
                        {{ number_format($schoolLocation->longitude, 6) }}</p>
                    <a href="https://www.google.com/maps?q={{ $schoolLocation->latitude }},{{ $schoolLocation->longitude }}"
                        target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>

        <!-- GPS Testing -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Test GPS</h2>
                <p class="text-sm text-gray-600 mt-1">Test koordinat untuk memastikan radius berfungsi</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="test-lat" class="block text-sm font-medium text-gray-700 mb-2">Test Latitude</label>
                        <input type="number" id="test-lat" step="any"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ $schoolLocation->latitude }}">
                    </div>
                    <div>
                        <label for="test-lng" class="block text-sm font-medium text-gray-700 mb-2">Test Longitude</label>
                        <input type="number" id="test-lng" step="any"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="{{ $schoolLocation->longitude }}">
                    </div>
                </div>
                <div class="mt-4">
                    <button onclick="testLocation()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Test Koordinat
                    </button>
                </div>
                <div id="test-result" class="mt-4 hidden">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Hasil Test:</h4>
                        <div id="test-content" class="text-sm text-gray-700"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testLocation() {
            const lat = document.getElementById('test-lat').value;
            const lng = document.getElementById('test-lng').value;

            if (!lat || !lng) {
                alert('Masukkan koordinat test terlebih dahulu');
                return;
            }

            // Hitung jarak menggunakan Haversine formula
            const distance = calculateDistance(
                {{ $schoolLocation->latitude }},
                {{ $schoolLocation->longitude }},
                parseFloat(lat),
                parseFloat(lng)
            );

            const isWithinRadius = distance <= {{ $schoolLocation->radius_meter }};
            const margin = {{ $schoolLocation->radius_meter }} - distance;

            const resultDiv = document.getElementById('test-result');
            const contentDiv = document.getElementById('test-content');

            contentDiv.innerHTML = `
                <div class="space-y-2">
                    <div><strong>Jarak:</strong> ${Math.round(distance)} meter</div>
                    <div><strong>Radius:</strong> {{ $schoolLocation->radius_meter }} meter</div>
                    <div><strong>Status:</strong> 
                        <span class="${isWithinRadius ? 'text-green-600' : 'text-red-600'}">
                            ${isWithinRadius ? 'Dalam Radius ✓' : 'Di Luar Radius ✗'}
                        </span>
                    </div>
                    <div><strong>Margin:</strong> ${Math.round(margin)} meter ${margin > 0 ? 'dalam radius' : 'di luar radius'}</div>
                </div>
            `;

            resultDiv.classList.remove('hidden');
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const earthRadius = 6371000; // Radius bumi dalam meter

            const lat1Rad = lat1 * Math.PI / 180;
            const lon1Rad = lon1 * Math.PI / 180;
            const lat2Rad = lat2 * Math.PI / 180;
            const lon2Rad = lon2 * Math.PI / 180;

            const deltaLat = lat2Rad - lat1Rad;
            const deltaLon = lon2Rad - lon1Rad;

            const a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
                Math.cos(lat1Rad) * Math.cos(lat2Rad) *
                Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);

            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return earthRadius * c;
        }
    </script>
@endsection
