@extends('layouts.admin-sidebar')

@section('page-title', 'Tambah Lokasi Sekolah')
@section('page-description', 'Tambahkan koordinat GPS dan radius untuk validasi absensi')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Lokasi Sekolah</h1>
                    <p class="text-gray-600 mt-1">Tambahkan koordinat GPS dan radius untuk validasi absensi siswa</p>
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
                <p class="text-sm text-gray-600 mt-1">Masukkan koordinat GPS dan radius untuk area sekolah</p>
            </div>

            <form action="{{ route('admin.school-location.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lokasi -->
                    <div class="md:col-span-2">
                        <label for="nama_lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lokasi" name="nama_lokasi" value="{{ old('nama_lokasi') }}"
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
                        <input type="number" id="latitude" name="latitude" step="any" value="{{ old('latitude') }}"
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
                        <input type="number" id="longitude" name="longitude" step="any" value="{{ old('longitude') }}"
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
                            value="{{ old('radius_meter', 100) }}"
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
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_active" value="0"
                                    {{ old('is_active') == '0' ? 'checked' : '' }}
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
                            placeholder="Masukkan alamat lengkap sekolah">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- GPS Helper Section -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-sm font-medium text-blue-900 mb-2">💡 Cara Mendapatkan Koordinat GPS</h3>
                    <div class="text-sm text-blue-800 space-y-1">
                        <p>1. Buka Google Maps di browser</p>
                        <p>2. Cari lokasi sekolah Anda</p>
                        <p>3. Klik kanan pada lokasi sekolah</p>
                        <p>4. Pilih koordinat yang muncul (contoh: -6.200000, 106.816666)</p>
                        <p>5. Salin angka pertama sebagai latitude dan angka kedua sebagai longitude</p>
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
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Preview Koordinat</h2>
                <p class="text-sm text-gray-600 mt-1">Lihat bagaimana koordinat akan ditampilkan</p>
            </div>
            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">Latitude:</span>
                            <span id="preview-lat" class="text-gray-900 ml-2">-6.200000</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Longitude:</span>
                            <span id="preview-lng" class="text-gray-900 ml-2">106.816666</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Radius:</span>
                            <span id="preview-radius" class="text-gray-900 ml-2">100 meter</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Status:</span>
                            <span id="preview-status" class="text-green-600 ml-2">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Real-time preview update
        document.addEventListener('DOMContentLoaded', function() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const radiusInput = document.getElementById('radius_meter');
            const statusInputs = document.querySelectorAll('input[name="is_active"]');

            function updatePreview() {
                document.getElementById('preview-lat').textContent = latInput.value || '-6.200000';
                document.getElementById('preview-lng').textContent = lngInput.value || '106.816666';
                document.getElementById('preview-radius').textContent = (radiusInput.value || '100') + ' meter';

                const activeStatus = document.querySelector('input[name="is_active"]:checked');
                const statusText = activeStatus && activeStatus.value === '1' ? 'Aktif' : 'Nonaktif';
                const statusColor = activeStatus && activeStatus.value === '1' ? 'text-green-600' : 'text-gray-600';

                const statusElement = document.getElementById('preview-status');
                statusElement.textContent = statusText;
                statusElement.className = statusColor + ' ml-2';
            }

            latInput.addEventListener('input', updatePreview);
            lngInput.addEventListener('input', updatePreview);
            radiusInput.addEventListener('input', updatePreview);
            statusInputs.forEach(input => input.addEventListener('change', updatePreview));
        });
    </script>
@endsection
