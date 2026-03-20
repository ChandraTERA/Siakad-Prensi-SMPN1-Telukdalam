@extends('layouts.guru-sidebar')

@section('page-title', 'Input Presensi')
@section('page-description', 'Pilih kelas untuk melakukan input presensi')

@push('styles')
    <style>
        .presensi-toggle {
            transition: all 0.3s ease;
        }

        .presensi-toggle.open {
            background-color: #10B981;
            color: white;
        }

        .presensi-toggle.closed {
            background-color: #EF4444;
            color: white;
        }

        .auto-close-timer {
            font-size: 0.875rem;
            color: #6B7280;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Input Presensi</h2>
        </div>

        @if ($kelasList->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($kelasList as $kelas)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xl font-bold text-gray-900">{{ $kelas->nama_kelas }}</h4>
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $kelas->tingkat }}
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ $kelas->siswa_count }} Siswa
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ \Carbon\Carbon::today()->format('d/m/Y') }}
                                </div>
                            </div>

                            <!-- Presensi Session Status -->
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Status Presensi:</span>
                                    <span id="status-badge-{{ $kelas->id }}"
                                        class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <i class="fas fa-circle mr-1"></i>
                                        <span id="status-text-{{ $kelas->id }}">Mengecek...</span>
                                    </span>
                                </div>

                                <div id="session-info-{{ $kelas->id }}" class="text-xs text-gray-500 hidden">
                                    <div id="waktu-mulai-{{ $kelas->id }}"></div>
                                    <div id="auto-close-{{ $kelas->id }}" class="auto-close-timer"></div>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <!-- Presensi Control Section -->
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-green-50 border-2 border-blue-200 rounded-xl p-4 mb-4">
                                    <h5 class="text-sm font-bold text-gray-800 mb-3 text-center">KONTROL PRESENSI</h5>

                                    <!-- Status Display -->
                                    <div class="text-center mb-3">
                                        @php
                                            $activeSession = \App\Models\PresensiSession::getActiveSession(
                                                Auth::user()->id,
                                                $kelas->id,
                                            );
                                        @endphp
                                        @if ($activeSession && $activeSession->isOpen())
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-circle mr-2"></i>
                                                Presensi Dibuka
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-circle mr-2"></i>
                                                Presensi Ditutup
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Toggle Button -->
                                    <div class="text-center">
                                        @if ($activeSession && $activeSession->isOpen())
                                            <button type="button"
                                                class="w-full px-6 py-4 rounded-xl font-bold text-lg transition-all duration-300 bg-green-500 text-white hover:bg-green-600 shadow-lg transform hover:scale-105"
                                                onclick="showClosePresensiModal({{ $kelas->id }}, '{{ $kelas->nama_kelas }}')">
                                                <i class="fas fa-stop mr-2"></i>
                                                TUTUP PRESENSI
                                            </button>
                                            <p class="text-xs text-gray-600 mt-2 font-medium">Klik untuk menutup sesi
                                                presensi</p>
                                        @else
                                            <button type="button"
                                                class="w-full px-6 py-4 rounded-xl font-bold text-lg transition-all duration-300 bg-red-500 text-white hover:bg-red-600 shadow-lg transform hover:scale-105"
                                                onclick="showOpenPresensiModal({{ $kelas->id }}, '{{ $kelas->nama_kelas }}')">
                                                <i class="fas fa-play mr-2"></i>
                                                BUKA PRESENSI
                                            </button>
                                            <p class="text-xs text-gray-600 mt-2 font-medium">Klik untuk membuka sesi
                                                presensi</p>
                                        @endif
                                    </div>

                                    <!-- Session Info -->
                                    <div id="session-info-{{ $kelas->id }}" class="text-xs text-gray-500 mt-3 hidden">
                                        <div id="waktu-mulai-{{ $kelas->id }}" class="text-center"></div>
                                        <div id="auto-close-{{ $kelas->id }}" class="auto-close-timer text-center">
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="grid grid-cols-3 gap-2">
                                    <a href="{{ route('guru.presensi.create', $kelas->id) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm text-center font-medium">
                                        <i class="fas fa-check mr-1"></i>Input
                                    </a>
                                    <a href="{{ route('guru.presensi.rekap', $kelas->id) }}"
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm text-center font-medium">
                                        <i class="fas fa-chart-bar mr-1"></i>Rekap
                                    </a>
                                    <a href="{{ route('guru.absen-mandiri.index', $kelas->id) }}"
                                        class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 rounded text-sm text-center font-medium">
                                        <i class="fas fa-camera mr-1"></i>Absen Mandiri
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl border border-gray-100">
                <i class="fas fa-chalkboard-teacher text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg">Anda belum ditugaskan untuk mengajar di kelas manapun.</p>
                <p class="text-gray-400 text-sm mt-2">Hubungi admin untuk mendapatkan tugas mengajar.</p>
            </div>
        @endif
    </div>

    <!-- Modal untuk konfirmasi presensi -->
    <div id="presensiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div id="modalIcon" class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center">
                            <!-- Icon akan diisi oleh JavaScript -->
                        </div>
                        <h3 id="modalTitle" class="text-xl font-semibold text-gray-900 mb-2"></h3>
                        <p id="modalMessage" class="text-gray-600"></p>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" onclick="closePresensiModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </button>
                        <button type="button" id="confirmButton"
                            class="flex-1 px-4 py-3 rounded-lg text-white font-medium transition-colors">
                            <!-- Text dan warna akan diisi oleh JavaScript -->
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentKelasId = null;
        let currentAction = null;

        function showOpenPresensiModal(kelasId, kelasNama) {
            currentKelasId = kelasId;
            currentAction = 'open';

            // Set modal content
            document.getElementById('modalIcon').innerHTML = '<i class="fas fa-play text-2xl text-red-500"></i>';
            document.getElementById('modalIcon').className =
                'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-red-100';
            document.getElementById('modalTitle').textContent = 'Buka Sesi Presensi';
            document.getElementById('modalMessage').textContent =
                `Apakah Anda yakin ingin membuka sesi presensi untuk kelas ${kelasNama}?`;

            // Set confirm button
            const confirmBtn = document.getElementById('confirmButton');
            confirmBtn.innerHTML = '<i class="fas fa-play mr-2"></i>Ya, Buka Presensi';
            confirmBtn.className =
                'flex-1 px-4 py-3 rounded-lg text-white font-medium transition-colors bg-red-500 hover:bg-red-600';
            confirmBtn.onclick = () => confirmPresensiAction();

            // Show modal
            document.getElementById('presensiModal').classList.remove('hidden');
        }

        function showClosePresensiModal(kelasId, kelasNama) {
            currentKelasId = kelasId;
            currentAction = 'close';

            // Set modal content
            document.getElementById('modalIcon').innerHTML = '<i class="fas fa-stop text-2xl text-green-500"></i>';
            document.getElementById('modalIcon').className =
                'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-green-100';
            document.getElementById('modalTitle').textContent = 'Tutup Sesi Presensi';
            document.getElementById('modalMessage').textContent =
                `Apakah Anda yakin ingin menutup sesi presensi untuk kelas ${kelasNama}?`;

            // Set confirm button
            const confirmBtn = document.getElementById('confirmButton');
            confirmBtn.innerHTML = '<i class="fas fa-stop mr-2"></i>Ya, Tutup Presensi';
            confirmBtn.className =
                'flex-1 px-4 py-3 rounded-lg text-white font-medium transition-colors bg-green-500 hover:bg-green-600';
            confirmBtn.onclick = () => confirmPresensiAction();

            // Show modal
            document.getElementById('presensiModal').classList.remove('hidden');
        }

        function closePresensiModal() {
            document.getElementById('presensiModal').classList.add('hidden');
            currentKelasId = null;
            currentAction = null;
        }

        function confirmPresensiAction() {
            if (!currentKelasId || !currentAction) return;

            let url = '';
            if (currentAction === 'open') {
                url = `/guru/presensi/${currentKelasId}/open-session`;
            } else if (currentAction === 'close') {
                url = `/guru/presensi/${currentKelasId}/close-session`;
            }

            // Redirect to the action URL
            window.location.href = url;
        }

        // Close modal when clicking outside
        document.getElementById('presensiModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePresensiModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePresensiModal();
            }
        });

        console.log('Presensi page with modal loaded successfully');
    </script>
@endpush
