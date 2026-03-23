@extends('layouts.siswa-sidebar')

@section('page-title', 'Absen Mandiri')
@section('page-description', 'Upload foto untuk absen mandiri')

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('siswa.absen-mandiri.index') }}"
                class="inline-flex items-center text-green-600 hover:text-green-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Error Alert -->
        @if (session('error'))
            <div id="error-alert"
                class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center transition-opacity duration-500 ease-in-out animate-fade-in">
                <svg class="w-5 h-5 mr-3 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
            <script>
                // Fade out after 8 seconds
                setTimeout(function() {
                    const alert = document.getElementById('error-alert');
                    if (alert) {
                        alert.classList.add('opacity-0');
                        setTimeout(() => alert.style.display = 'none', 500);
                    }
                }, 8000);
            </script>
        @endif

        <!-- Instructions Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Petunjuk Absen Mandiri</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Langkah 1:</strong> Klik tombol "Akses GPS" untuk mengizinkan lokasi</li>
                            <li><strong>Langkah 2:</strong> Pastikan Anda berada dalam radius sekolah (100 meter)</li>
                            <li><strong>Langkah 3:</strong> Setelah GPS valid, klik "Buka Kamera" untuk mengambil foto</li>
                            <li><strong>Langkah 4:</strong> Ambil foto selfie dengan wajah terlihat jelas</li>
                            <li>Absen akan ditolak jika Anda berada di luar radius sekolah</li>
                            <li>Absen akan menunggu persetujuan dari guru</li>
                            <li>Anda hanya bisa absen sekali per hari</li>
                            <li>Pastikan browser memiliki akses ke kamera dan GPS</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Form Absen Mandiri</h3>
                <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>

            <form action="{{ route('siswa.absen-mandiri.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf

                <!-- Camera Capture -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Foto Absen <span class="text-red-500">*</span>
                    </label>

                    <!-- Camera View -->
                    <div class="bg-gray-100 rounded-lg overflow-hidden" id="camera-container">
                        <div id="camera-view" class="relative">
                            <video id="video" autoplay muted class="w-full h-64 object-cover bg-gray-200"></video>
                            <div class="absolute inset-0 flex items-center justify-center" id="camera-placeholder">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-sm">Klik tombol untuk mengakses kamera</p>
                                </div>
                            </div>
                        </div>

                        <!-- Camera Controls -->
                        <div class="p-4 bg-white border-t">
                            <div class="flex justify-center space-x-4">
                                <button type="button" id="start-camera"
                                    class="bg-gray-400 text-white px-4 py-2 rounded-md transition-colors duration-200 cursor-not-allowed"
                                    disabled>
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span id="camera-button-text">Validasi GPS Terlebih Dahulu</span>
                                </button>
                                <button type="button" id="take-photo"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200 hidden">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ambil Foto
                                </button>
                                <button type="button" id="retake-photo"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors duration-200 hidden">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Foto Ulang
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Captured Photo -->
                    <div id="photo-preview" class="hidden mt-4">
                        <div class="bg-gray-100 rounded-lg overflow-hidden">
                            <img id="captured-image" class="w-full h-64 object-cover" alt="Captured photo">
                        </div>
                        <p class="text-sm text-green-600 mt-2 text-center">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Foto berhasil diambil
                        </p>
                    </div>

                    <!-- Hidden canvas for photo capture -->
                    <canvas id="canvas" style="display: none;"></canvas>

                    <!-- Hidden file input for form submission -->
                    <input type="file" id="foto_absen" name="foto_absen" style="display: none;" required>

                    @error('foto_absen')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- GPS Location Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lokasi GPS <span class="text-red-500">*</span>
                    </label>

                    <!-- GPS Status -->
                    <div id="gps-status" class="mb-4 p-4 rounded-lg border">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Status Lokasi</p>
                                    <p class="text-sm text-gray-600" id="gps-status-text">Klik tombol untuk mengakses GPS
                                    </p>
                                </div>
                            </div>
                            <button type="button" id="get-gps-btn"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200 text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Akses GPS
                            </button>
                        </div>
                    </div>

                    <!-- GPS Info -->
                    <div id="gps-info" class="hidden mb-4 p-4 rounded-lg border">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Latitude:</span>
                                <span id="gps-lat" class="text-gray-900 ml-2">-</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Longitude:</span>
                                <span id="gps-lng" class="text-gray-900 ml-2">-</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Jarak dari Sekolah:</span>
                                <span id="gps-distance" class="text-gray-900 ml-2">-</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Status:</span>
                                <span id="gps-location-status" class="ml-2">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs for GPS coordinates -->
                    <input type="hidden" id="latitude" name="latitude" value="">
                    <input type="hidden" id="longitude" name="longitude" value="">

                    <!-- GPS Error Message -->
                    <div id="gps-error" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-red-800">Error GPS</p>
                                <p class="text-sm text-red-700" id="gps-error-text"></p>
                            </div>
                        </div>
                    </div>

                    <!-- GPS Success Message -->
                    <div id="gps-success" class="hidden mt-2 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-green-800">Lokasi GPS Valid</p>
                                <p class="text-sm text-green-700">Anda berada dalam radius sekolah yang diizinkan</p>
                            </div>
                        </div>
                    </div>

                    <!-- GPS Warning Message -->
                    <div id="gps-warning" class="hidden mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Peringatan Lokasi</p>
                                <p class="text-sm text-yellow-700" id="gps-warning-text"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Note -->
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan (Opsional)
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Time Display -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Waktu Absen</p>
                            <p class="text-lg font-semibold text-gray-900" id="current-time">
                                {{ \Carbon\Carbon::now()->format('H:i:s') }} WIB</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('siswa.absen-mandiri.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-md transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submit-btn">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Kirim Absen
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update current time every second
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID') + ' WIB';
            document.getElementById('current-time').textContent = timeString;
        }

        setInterval(updateTime, 1000);
        updateTime(); // Initial call

        // Camera functionality
        let stream = null;
        let photoTaken = false;
        let gpsValid = false;
        let currentLocation = null;

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const capturedImage = document.getElementById('captured-image');
        const photoPreview = document.getElementById('photo-preview');
        const cameraPlaceholder = document.getElementById('camera-placeholder');
        const submitBtn = document.getElementById('submit-btn');

        const startCameraBtn = document.getElementById('start-camera');
        const takePhotoBtn = document.getElementById('take-photo');
        const retakePhotoBtn = document.getElementById('retake-photo');
        const cameraButtonText = document.getElementById('camera-button-text');

        // Start camera
        startCameraBtn.addEventListener('click', async () => {
            try {
                // Request camera access (front camera preferred for selfie)
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user', // Front camera
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 480
                        }
                    }
                });

                video.srcObject = stream;
                cameraPlaceholder.classList.add('hidden');
                startCameraBtn.classList.add('hidden');
                takePhotoBtn.classList.remove('hidden');

            } catch (err) {
                console.error('Error accessing camera:', err);
                alert(
                    'Tidak dapat mengakses kamera. Pastikan Anda mengizinkan akses kamera dan menggunakan HTTPS.'
                );
            }
        });

        // Take photo
        takePhotoBtn.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert canvas to blob and create file
            canvas.toBlob((blob) => {
                // Create a new file from the blob
                const file = new File([blob], 'absen-selfie.jpg', {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                // Create a new FileList containing our file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('foto_absen').files = dataTransfer.files;

                // Show preview
                const imageUrl = canvas.toDataURL('image/jpeg', 0.8);
                capturedImage.src = imageUrl;
                photoPreview.classList.remove('hidden');

                // Hide camera view and show retake button
                video.style.display = 'none';
                takePhotoBtn.classList.add('hidden');
                retakePhotoBtn.classList.remove('hidden');

                // Stop camera stream
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                photoTaken = true;
                updateSubmitButton();

            }, 'image/jpeg', 0.8);
        });

        // Retake photo
        retakePhotoBtn.addEventListener('click', () => {
            // Reset everything
            photoPreview.classList.add('hidden');
            video.style.display = 'block';
            retakePhotoBtn.classList.add('hidden');
            startCameraBtn.classList.remove('hidden');

            // Clear file input
            document.getElementById('foto_absen').value = '';
            photoTaken = false;
            updateSubmitButton();
        });

        // Update submit button state
        function updateSubmitButton() {
            submitBtn.disabled = !photoTaken;
            if (photoTaken) {
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        // Initialize submit button state
        updateSubmitButton();

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', (e) => {
            if (!photoTaken) {
                e.preventDefault();
                alert('Silakan ambil foto terlebih dahulu');
                return false;
            }
        });

        // Cleanup camera stream when page unloads
        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });

        // GPS Location functionality
        // Variables already declared above

        // GPS Elements
        const gpsStatus = document.getElementById('gps-status');
        const gpsStatusText = document.getElementById('gps-status-text');
        const gpsInfo = document.getElementById('gps-info');
        const gpsError = document.getElementById('gps-error');
        const gpsErrorText = document.getElementById('gps-error-text');
        const gpsSuccess = document.getElementById('gps-success');
        const gpsWarning = document.getElementById('gps-warning');
        const gpsWarningText = document.getElementById('gps-warning-text');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const getGpsBtn = document.getElementById('get-gps-btn');

        // Event listener will be added in DOMContentLoaded

        // Get GPS location
        function getCurrentLocation() {
            if (!navigator.geolocation) {
                showGpsError('Browser tidak mendukung GPS');
                return;
            }

            // Get button element
            const getGpsBtn = document.getElementById('get-gps-btn');
            const gpsStatusText = document.getElementById('gps-status-text');

            // Update button state
            getGpsBtn.disabled = true;
            getGpsBtn.innerHTML =
                '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Mengambil GPS...';
            gpsStatusText.textContent = 'Meminta izin akses GPS...';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    currentLocation = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy
                    };

                    // Set hidden inputs
                    latitudeInput.value = currentLocation.latitude;
                    longitudeInput.value = currentLocation.longitude;

                    // Update status
                    gpsStatusText.textContent = 'GPS berhasil diambil, memvalidasi lokasi...';

                    // Validate location with server
                    validateLocationWithServer();
                },
                (error) => {
                    let errorMessage = 'Tidak dapat mengambil lokasi GPS';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Akses GPS ditolak. Silakan izinkan akses GPS di browser dan coba lagi.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Lokasi GPS tidak tersedia. Pastikan GPS aktif.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Timeout mengambil lokasi GPS. Coba lagi.';
                            break;
                    }
                    showGpsError(errorMessage);

                    // Reset button
                    resetGpsButton();
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 300000 // 5 minutes
                }
            );
        }

        // Reset GPS button
        function resetGpsButton() {
            const getGpsBtn = document.getElementById('get-gps-btn');
            getGpsBtn.disabled = false;
            getGpsBtn.innerHTML =
                '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>Akses GPS';
        }

        // Validate location with server
        async function validateLocationWithServer() {
            try {
                const response = await fetch('{{ route('siswa.absen-mandiri.validate-gps') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        latitude: currentLocation.latitude,
                        longitude: currentLocation.longitude
                    })
                });

                const data = await response.json();

                if (data.success) {
                    const gpsData = data.gps_data;

                    // Update GPS info display
                    document.getElementById('gps-lat').textContent = gpsData.latitude.toFixed(6);
                    document.getElementById('gps-lng').textContent = gpsData.longitude.toFixed(6);
                    document.getElementById('gps-distance').textContent = gpsData.distance_meters + ' meter';

                    if (gpsData.is_within_radius) {
                        gpsValid = true;
                        showGpsSuccess();
                        document.getElementById('gps-location-status').innerHTML =
                            '<span class="text-green-600 font-medium">Dalam Radius</span>';
                    } else {
                        gpsValid = false;
                        showGpsWarning(
                            `Anda berada ${gpsData.distance_meters} meter dari sekolah. Radius yang diizinkan: ${data.school_location.radius_meter} meter.`
                        );
                        document.getElementById('gps-location-status').innerHTML =
                            '<span class="text-red-600 font-medium">Di Luar Radius</span>';
                    }

                    gpsInfo.classList.remove('hidden');
                } else {
                    showGpsError(data.message || 'Error validasi lokasi');
                }
            } catch (error) {
                console.error('Error validating location:', error);
                showGpsError('Error komunikasi dengan server');
            }
        }

        // Show GPS success
        function showGpsSuccess() {
            hideAllGpsMessages();
            gpsSuccess.classList.remove('hidden');
            gpsStatusText.textContent = 'Lokasi GPS valid - Anda dapat melakukan absensi';
            gpsStatus.className = 'mb-4 p-4 rounded-lg border border-green-200 bg-green-50';

            // Hide GPS button and show success state
            const getGpsBtn = document.getElementById('get-gps-btn');
            getGpsBtn.style.display = 'none';

            // Update camera button state
            updateCameraButtonState();
        }

        // Show GPS warning
        function showGpsWarning(message) {
            hideAllGpsMessages();
            gpsWarningText.textContent = message;
            gpsWarning.classList.remove('hidden');
            gpsStatusText.textContent = 'Lokasi GPS tidak valid - Absensi mungkin ditolak';
            gpsStatus.className = 'mb-4 p-4 rounded-lg border border-yellow-200 bg-yellow-50';

            // Reset button for retry
            resetGpsButton();

            // Update camera button state (keep disabled)
            updateCameraButtonState();
        }

        // Show GPS error
        function showGpsError(message) {
            hideAllGpsMessages();
            gpsErrorText.textContent = message;
            gpsError.classList.remove('hidden');
            gpsStatusText.textContent = 'Error GPS - Tidak dapat melakukan absensi';
            gpsStatus.className = 'mb-4 p-4 rounded-lg border border-red-200 bg-red-50';

            // Reset button for retry
            resetGpsButton();

            // Update camera button state (keep disabled)
            updateCameraButtonState();
        }

        // Hide all GPS messages
        function hideAllGpsMessages() {
            gpsError.classList.add('hidden');
            gpsSuccess.classList.add('hidden');
            gpsWarning.classList.add('hidden');
        }

        // Update camera button based on GPS status
        function updateCameraButtonState() {
            if (gpsValid) {
                // GPS valid - enable camera button
                startCameraBtn.disabled = false;
                startCameraBtn.className =
                    'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors duration-200';
                cameraButtonText.textContent = 'Buka Kamera';
            } else {
                // GPS not valid - disable camera button
                startCameraBtn.disabled = true;
                startCameraBtn.className =
                    'bg-gray-400 text-white px-4 py-2 rounded-md transition-colors duration-200 cursor-not-allowed';
                cameraButtonText.textContent = 'Validasi GPS Terlebih Dahulu';
            }
        }

        // Update submit button based on GPS and photo status
        function updateSubmitButtonState() {
            const canSubmit = photoTaken && gpsValid;
            submitBtn.disabled = !canSubmit;

            if (canSubmit) {
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.textContent = 'Kirim Absen';
            } else {
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                if (!photoTaken && !gpsValid) {
                    submitBtn.textContent = 'Ambil Foto & Validasi GPS';
                } else if (!photoTaken) {
                    submitBtn.textContent = 'Ambil Foto';
                } else if (!gpsValid) {
                    submitBtn.textContent = 'Validasi GPS';
                }
            }
        }

        // Override the existing updateSubmitButton function
        function updateSubmitButton() {
            updateSubmitButtonState();
            updateCameraButtonState();
        }

        // Form validation before submit
        document.querySelector('form').addEventListener('submit', (e) => {
            if (!photoTaken) {
                e.preventDefault();
                alert('Silakan ambil foto terlebih dahulu');
                return false;
            }

            if (!gpsValid) {
                e.preventDefault();
                alert('Lokasi GPS tidak valid. Anda berada di luar radius sekolah yang diizinkan.');
                return false;
            }
        });

        // Initialize GPS button state
        document.addEventListener('DOMContentLoaded', () => {
            // Add event listener for GPS button
            const getGpsBtn = document.getElementById('get-gps-btn');
            if (getGpsBtn) {
                getGpsBtn.addEventListener('click', () => {
                    getCurrentLocation();
                });
                console.log('GPS button event listener added');
            } else {
                console.error('GPS button not found');
            }
        });
    </script>
@endsection
