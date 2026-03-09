@extends('layouts.admin-sidebar')

@section('page-title', 'Edit Lokasi Sekolah')
@section('page-description', 'Edit koordinat GPS dan radius untuk validasi absensi')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Lokasi Sekolah</h1>
                    <p class="text-gray-600 mt-1">Edit koordinat GPS dan radius untuk validasi absensi siswa</p>
                </div>
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

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Informasi Lokasi</h2>
                <p class="text-sm text-gray-600 mt-1">Edit koordinat GPS dan radius untuk area sekolah</p>
            </div>

            <form action="{{ route('admin.school-location.update', $schoolLocation) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lokasi -->
                    <div class="md:col-span-2">
                        <label for="nama_lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lokasi" name="nama_lokasi"
                            value="{{ old('nama_lokasi', $schoolLocation->nama_lokasi) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_lokasi') border-red-500 @enderror"
                            placeholder="Contoh: SMA Negeri 1 Jakarta" required>
                        @error('nama_lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Latitude -->
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Latitude <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="latitude" name="latitude" step="any"
                            value="{{ old('latitude', $schoolLocation->latitude) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('latitude') border-red-500 @enderror"
                            placeholder="-6.200000" required>
                        @error('latitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Koordinat latitude (-90 sampai 90)</p>
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Longitude <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="longitude" name="longitude" step="any"
                            value="{{ old('longitude', $schoolLocation->longitude) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('longitude') border-red-500 @enderror"
                            placeholder="106.816666" required>
                        @error('longitude')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Koordinat longitude (-180 sampai 180)</p>
                    </div>

                    <!-- Radius -->
                    <div>
                        <label for="radius_meter" class="block text-sm font-medium text-gray-700 mb-2">
                            Radius (Meter) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="radius_meter" name="radius_meter" min="10" max="1000"
                            value="{{ old('radius_meter', $schoolLocation->radius_meter) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('radius_meter') border-red-500 @enderror"
                            required>
                        @error('radius_meter')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Radius dalam meter (10-1000)</p>
                    </div>

                    <!-- Status Aktif -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Lokasi
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="1"
                                    {{ old('is_active', $schoolLocation->is_active) == '1' ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="0"
                                    {{ old('is_active', $schoolLocation->is_active) == '0' ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Nonaktif</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap sekolah">{{ old('alamat', $schoolLocation->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.school-location.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Update Lokasi
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Status -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Status Saat Ini</h2>
                <p class="text-sm text-gray-600 mt-1">Informasi lokasi yang sedang aktif</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Koordinat GPS</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm space-y-1">
                                <div><span class="font-medium">Latitude:</span>
                                    {{ number_format($schoolLocation->latitude, 6) }}</div>
                                <div><span class="font-medium">Longitude:</span>
                                    {{ number_format($schoolLocation->longitude, 6) }}</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Radius & Status</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm space-y-1">
                                <div><span class="font-medium">Radius:</span> {{ $schoolLocation->radius_meter }} meter
                                </div>
                                <div>
                                    <span class="font-medium">Status:</span>
                                    @if ($schoolLocation->is_active)
                                        <span class="text-green-600 font-medium">Aktif</span>
                                    @else
                                        <span class="text-gray-600 font-medium">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
