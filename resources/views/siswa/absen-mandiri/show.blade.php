@extends('layouts.siswa-sidebar')

@section('page-title', 'Detail Absen Mandiri')
@section('page-description', 'Detail pengajuan absen mandiri')

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('siswa.absen-mandiri.index') }}" 
               class="inline-flex items-center text-green-600 hover:text-green-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Absen
            </a>
        </div>

        <!-- Attendance Detail Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header with Status -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Detail Absen Mandiri</h3>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $absen->status_badge }}">
                        {{ $absen->status_text }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Photo Section -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Foto Absen</h4>
                        <div class="bg-gray-100 rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $absen->foto_absen) }}" 
                                 alt="Foto Absen" 
                                 class="w-full h-64 object-cover">
                        </div>
                        <div class="mt-2 text-center">
                            <a href="{{ asset('storage/' . $absen->foto_absen) }}" 
                               target="_blank"
                               class="text-green-600 hover:text-green-700 text-sm transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Lihat Foto Ukuran Penuh
                            </a>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Informasi Absen</h4>
                        <div class="space-y-4">
                            <!-- Date and Time -->
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0v13a2 2 0 002 2h4a2 2 0 002-2V7m-8 0V6a2 2 0 012-2h4a2 2 0 012 2v1"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Tanggal & Waktu</p>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}</p>
                                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($absen->waktu_absen)->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Status</p>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $absen->status_badge }}">
                                        {{ $absen->status_text }}
                                    </span>
                                </div>
                            </div>

                            <!-- Student Note -->
                            @if($absen->keterangan)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Keterangan Anda</p>
                                        <p class="text-gray-600">{{ $absen->keterangan }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Teacher Response -->
                            @if($absen->disetujui_oleh)
                                <div class="border-t pt-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-2">Respon Guru</h5>
                                    
                                    <div class="flex items-start mb-3">
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Diproses oleh</p>
                                            <p class="text-gray-600">{{ $absen->guru->name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start mb-3">
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Waktu Persetujuan</p>
                                            <p class="text-gray-600">{{ \Carbon\Carbon::parse($absen->waktu_persetujuan)->translatedFormat('l, d F Y H:i') }} WIB</p>
                                        </div>
                                    </div>

                                    @if($absen->catatan_guru)
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">Catatan Guru</p>
                                                <p class="text-gray-600">{{ $absen->catatan_guru }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @elseif($absen->status === 'pending')
                                <div class="border-t pt-4">
                                    <div class="flex items-center text-yellow-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-sm">Menunggu persetujuan guru...</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($absen->status === 'pending')
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex justify-end">
                            <form action="{{ route('siswa.absen-mandiri.destroy', $absen->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin membatalkan absen ini?')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Batalkan Absen
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
