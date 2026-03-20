@extends('layouts.guru-sidebar')

@section('page-title', 'Input Presensi - ' . $kelas->nama_kelas)
@section('page-description', 'Input kehadiran siswa untuk tanggal ' . $tanggal->format('d/m/Y'))

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Input Presensi</h2>
                <p class="text-gray-600">{{ $kelas->nama_kelas }} - {{ $kelas->tingkat }}</p>
            </div>
            <a href="{{ route('guru.presensi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('guru.presensi.create', $kelas->id) }}"
                class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ $search }}"
                            placeholder="Cari berdasarkan nama atau NIS..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:w-48">
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                    <select name="per_page" id="per_page"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 siswa</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 siswa</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 siswa</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 siswa</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 siswa</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>

                    @if ($search)
                        <a href="{{ route('guru.presensi.create', $kelas->id) }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>

            @if ($search)
                <div class="mt-3 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
                    ({{ $siswaPaginated->total() }} siswa ditemukan)
                </div>
            @endif
        </div>

        <form action="{{ route('guru.presensi.store') }}" method="POST" id="presensiForm">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
            <input type="hidden" name="tanggal" value="{{ $tanggal->format('Y-m-d') }}">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Daftar Siswa</h3>
                            @if ($siswaPaginated->total() > 0)
                                <p class="text-sm text-gray-600">Total: {{ $siswaPaginated->total() }} siswa</p>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" onclick="setAllStatus('hadir')" class="btn btn-sm btn-success">
                                Semua Hadir
                            </button>
                            <button type="button" onclick="setAllStatus('alpa')" class="btn btn-sm btn-danger">
                                Semua Alpa
                            </button>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($siswaPaginated as $siswa)
                        @php
                            $existingStatus = $existingPresensi->get($siswa->id)?->status ?? 'hadir';
                            $existingKeterangan = $existingPresensi->get($siswa->id)?->keterangan ?? '';
                        @endphp
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $siswa->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $siswa->siswa->nis ?? 'NIS tidak tersedia' }}</p>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <input type="hidden" name="presensi[{{ $loop->index }}][siswa_id]"
                                        value="{{ $siswa->id }}">

                                    <!-- Status Radio Buttons -->
                                    <div class="flex space-x-3">
                                        @foreach (['hadir' => ['Hadir', 'success'], 'izin' => ['Izin', 'warning'], 'sakit' => ['Sakit', 'info'], 'alpa' => ['Alpa', 'danger']] as $status => $info)
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="presensi[{{ $loop->parent->index }}][status]"
                                                    value="{{ $status }}"
                                                    {{ $existingStatus === $status ? 'checked' : '' }}
                                                    class="form-radio text-{{ $info[1] }}-600 focus:ring-{{ $info[1] }}-500"
                                                    onchange="toggleKeterangan({{ $loop->parent->index }}, '{{ $status }}')">
                                                <span class="ml-2 text-sm text-gray-700">{{ $info[0] }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <!-- Keterangan Input -->
                                    <div class="keterangan-input" id="keterangan-{{ $loop->index }}"
                                        style="{{ in_array($existingStatus, ['izin', 'sakit']) ? '' : 'display: none;' }}">
                                        <input type="text" name="presensi[{{ $loop->index }}][keterangan]"
                                            value="{{ $existingKeterangan }}" placeholder="Keterangan..."
                                            class="form-input text-sm w-32">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <p class="text-gray-500">Tidak ada siswa di kelas ini.</p>
                        </div>
                    @endforelse
                </div>

                @if ($siswaPaginated->isNotEmpty())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-600">
                                Menampilkan {{ $siswaPaginated->firstItem() }} sampai {{ $siswaPaginated->lastItem() }}
                                dari {{ $siswaPaginated->total() }} siswa
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Simpan Presensi
                            </button>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if ($siswaPaginated->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-center">
                                {{ $siswaPaginated->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </form>
    </div>

    <script>
        function setAllStatus(status) {
            const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
            radios.forEach(radio => {
                radio.checked = true;
                const index = radio.name.match(/\[(\d+)\]/)[1];
                toggleKeterangan(index, status);
            });
        }

        function toggleKeterangan(index, status) {
            const keteranganDiv = document.getElementById(`keterangan-${index}`);
            if (status === 'izin' || status === 'sakit') {
                keteranganDiv.style.display = 'block';
            } else {
                keteranganDiv.style.display = 'none';
                keteranganDiv.querySelector('input').value = '';
            }
        }

        // Real-time save functionality
        document.getElementById('presensiForm').addEventListener('change', function() {
            // Auto-save functionality could be added here
            // For now, we'll just show a visual indicator
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan Presensi (Ada Perubahan)';
            submitBtn.classList.add('btn-warning');
            submitBtn.classList.remove('btn-primary');
        });
    </script>
@endsection
