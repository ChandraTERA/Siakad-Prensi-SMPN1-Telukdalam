@extends('layouts.admin-sidebar')

@section('page-title', 'Kelola Guru - ' . $kelas->nama_kelas)
@section('page-description', 'Assign guru pengajar untuk kelas ' . $kelas->nama_kelas)

@section('content')
    <div class="space-y-6">
        <!-- Success Alert -->
        @if (session('success'))
            <div id="success-alert"
                class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center transition-opacity duration-500 ease-in-out animate-fade-in">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    const alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.classList.add('opacity-0');
                        setTimeout(() => alert.style.display = 'none', 500);
                    }
                }, 5000);
            </script>
            <style>
                @keyframes fade-in {
                    from {
                        opacity: 0;
                    }

                    to {
                        opacity: 1;
                    }
                }

                .animate-fade-in {
                    animation: fade-in 0.5s;
                }
            </style>
        @endif

        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Kelola Guru Pengajar</h2>
                <p class="text-gray-600">{{ $kelas->nama_kelas }} - {{ $kelas->tingkat }}</p>
            </div>
            <a href="{{ route('admin.kelas.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Kelas
            </a>
        </div>

        <!-- Class Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $kelas->nama_kelas }}</div>
                    <div class="text-sm text-gray-500">Nama Kelas</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $kelas->tingkat }}</div>
                    <div class="text-sm text-gray-500">Tingkat</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $kelas->waliKelas->name ?? '-' }}</div>
                    <div class="text-sm text-gray-500">Wali Kelas</div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.kelas.sync-guru', $kelas) }}">
                @csrf
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Guru Pengajar</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Centang guru yang akan mengajar di kelas <strong>{{ $kelas->nama_kelas }}</strong>.
                        Guru yang dipilih akan dapat mengajar, memberikan tugas, dan mengelola presensi di kelas ini.
                    </p>

                    @if ($semuaGuru->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($semuaGuru as $guru)
                                <div
                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <input type="checkbox" name="guru_ids[]" value="{{ $guru->id }}"
                                        id="guru_{{ $guru->id }}"
                                        {{ in_array($guru->id, $guruTerpilihIds) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="guru_{{ $guru->id }}" class="ml-3 flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $guru->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $guru->email }}</div>
                                        @if ($guru->nip)
                                            <div class="text-xs text-gray-400">NIP: {{ $guru->nip }}</div>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">Belum ada guru yang tersedia</p>
                            <p class="text-gray-400 text-sm mt-1">Silakan tambahkan guru terlebih dahulu di halaman
                                Manajemen Guru</p>
                            <a href="{{ route('admin.guru.index') }}"
                                class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Tambah Guru
                            </a>
                        </div>
                    @endif
                </div>

                @if ($semuaGuru->count() > 0)
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.kelas.index') }}"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                @endif
            </form>
        </div>

        <!-- Current Teachers Info -->
        @if (count($guruTerpilihIds) > 0)
            <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                <h4 class="text-lg font-medium text-blue-900 mb-4">Guru yang Sedang Mengajar</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach ($semuaGuru->whereIn('id', $guruTerpilihIds) as $guru)
                        <div class="flex items-center p-3 bg-white rounded-lg border border-blue-200">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $guru->name }}</div>
                                <div class="text-xs text-gray-500">{{ $guru->email }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
