@extends('layouts.guru-sidebar')

@section('page-title', 'Data Siswa')
@section('page-description', 'Daftar siswa yang Anda ajar')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Data Siswa</h2>
            <a href="{{ route('guru.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
            </a>
        </div>

        @if ($kelasList->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach ($kelasList as $kelas)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $kelas->nama_kelas }}</h3>
                                    <p class="text-sm text-gray-500">{{ $kelas->tingkat }}</p>
                                </div>
                                <span
                                    class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $kelas->siswa_count }} Siswa
                                </span>
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            @if ($kelas->siswa->isNotEmpty())
                                <div class="space-y-3 max-h-64 overflow-y-auto">
                                    @foreach ($kelas->siswa->take(5) as $siswa)
                                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-800">
                                                        {{ substr($siswa->name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $siswa->name }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    NIS: {{ $siswa->siswa->nis ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($kelas->siswa->count() > 5)
                                        <div class="text-center pt-2">
                                            <p class="text-sm text-gray-500">
                                                dan {{ $kelas->siswa->count() - 5 }} siswa lainnya...
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <p class="text-gray-500 text-sm">Belum ada siswa di kelas ini.</p>
                                </div>
                            @endif
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <div class="flex justify-between items-center">
                                <a href="{{ route('guru.siswa.show', $kelas->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                                <a href="{{ route('guru.presensi.create', $kelas->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-check mr-2"></i>Input Presensi
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl border border-gray-100">
                <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg">Anda belum ditugaskan untuk mengajar di kelas manapun.</p>
                <p class="text-gray-400 text-sm mt-2">Hubungi admin untuk mendapatkan tugas mengajar.</p>
            </div>
        @endif
    </div>
@endsection
