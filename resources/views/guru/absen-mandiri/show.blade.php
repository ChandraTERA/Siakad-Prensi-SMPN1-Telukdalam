@extends('layouts.guru-sidebar')

@section('page-title', 'Detail Absen Mandiri')
@section('page-description', 'Lihat dan kelola absen mandiri siswa')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Absen Mandiri</h1>
                <p class="text-gray-600 mt-1">{{ $absenMandiri->siswa->name }} - {{ $kelas->nama_kelas }}</p>
            </div>
            <a href="{{ route('guru.absen-mandiri.index', $kelas->id) }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Student Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Siswa</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span
                                class="text-lg font-medium text-blue-600">{{ substr($absenMandiri->siswa->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-gray-900">{{ $absenMandiri->siswa->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $absenMandiri->siswa->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-500">Kelas</p>
                            <p class="text-sm font-medium text-gray-900">{{ $kelas->nama_kelas }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Absen</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($absenMandiri->tanggal)->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Waktu Absen</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($absenMandiri->waktu_absen)->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $absenMandiri->status_badge }}">
                                {{ $absenMandiri->status_text }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Absen</h3>
                @if ($absenMandiri->foto_absen)
                    <div class="text-center">
                        <img src="{{ Storage::url($absenMandiri->foto_absen) }}"
                            alt="Foto Absen {{ $absenMandiri->siswa->name }}"
                            class="max-w-full h-auto rounded-lg shadow-md mx-auto">
                        <p class="text-sm text-gray-500 mt-2">Foto yang diupload siswa</p>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p class="text-gray-500 text-sm">Tidak ada foto</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Approval Section -->
        @if ($absenMandiri->status === 'pending')
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Persetujuan Absen</h3>
                <form action="{{ route('guru.absen-mandiri.update-status', [$kelas->id, $absenMandiri->id]) }}"
                    method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (opsional)</label>
                            <textarea name="keterangan" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan keterangan untuk siswa..."></textarea>
                        </div>

                        <div class="flex space-x-3">
                            <button type="submit" name="status" value="approved"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                                <i class="fas fa-check mr-2"></i>Setujui Absen
                            </button>
                            <button type="submit" name="status" value="rejected"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors">
                                <i class="fas fa-times mr-2"></i>Tolak Absen
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <!-- Approval Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Persetujuan</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Disetujui Oleh</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $absenMandiri->guru ? $absenMandiri->guru->name : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Waktu Persetujuan</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $absenMandiri->waktu_approval ? \Carbon\Carbon::parse($absenMandiri->waktu_approval)->format('d/m/Y H:i') : '-' }}
                        </p>
                    </div>
                    @if ($absenMandiri->keterangan_guru)
                        <div class="col-span-2">
                            <p class="text-sm text-gray-500">Keterangan Guru</p>
                            <p class="text-sm font-medium text-gray-900">{{ $absenMandiri->keterangan_guru }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Additional Info -->
        @if ($absenMandiri->keterangan)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Keterangan Siswa</h3>
                <p class="text-gray-700">{{ $absenMandiri->keterangan }}</p>
            </div>
        @endif
    </div>
@endsection
