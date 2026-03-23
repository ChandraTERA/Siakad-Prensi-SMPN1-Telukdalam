@extends('layouts.siswa-sidebar')

@section('page-title', 'Detail Materi')
@section('page-description', 'Lihat detail materi pembelajaran')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Detail Materi</h2>
            <a href="{{ route('siswa.materi.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Materi -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ $materi->nama_materi }}</h3>
                            <div class="flex items-center space-x-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $materi->mataPelajaran->nama_mapel }}
                                </span>
                                <span class="text-sm text-gray-500">{{ $materi->kelas->nama_kelas }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Uploaded</div>
                            <div class="text-sm font-medium">{{ $materi->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    @if ($materi->deskripsi)
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-700 mb-3">Deskripsi</h4>
                            <p class="text-gray-600 leading-relaxed">{{ $materi->deskripsi }}</p>
                        </div>
                    @endif

                    <!-- File Information -->
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-medium text-gray-700 mb-4">File Materi</h4>
                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-file text-4xl text-gray-400"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">{{ $materi->file_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $materi->file_size_formatted }}</div>
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('siswa.materi.download', $materi) }}"
                                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-download mr-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Materi Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Materi</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Guru</span>
                            <span class="text-sm font-medium">{{ $materi->guru->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Mata Pelajaran</span>
                            <span class="text-sm font-medium">{{ $materi->mataPelajaran->nama_mapel }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Kelas</span>
                            <span class="text-sm font-medium">{{ $materi->kelas->nama_kelas }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">File Size</span>
                            <span class="text-sm font-medium">{{ $materi->file_size_formatted }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tanggal Upload</span>
                            <span class="text-sm font-medium">{{ $materi->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Download Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Statistik Download</h4>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 mb-2">{{ $materi->downloads->count() }}</div>
                        <div class="text-sm text-gray-500">Total Downloads</div>
                    </div>

                    @if ($materi->downloads->count() > 0)
                        <div class="mt-4">
                            <a href="{{ route('siswa.materi.downloads', $materi) }}"
                                class="text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-chart-bar mr-1"></i>Lihat Detail Downloads
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h4>
                    <div class="space-y-3">
                        <a href="{{ route('siswa.materi.download', $materi) }}"
                            class="w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition-colors duration-200 block">
                            <i class="fas fa-download mr-2"></i>Download File
                        </a>
                        <a href="{{ route('siswa.materi.index') }}"
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-4 rounded-lg transition-colors duration-200 block">
                            <i class="fas fa-list mr-2"></i>Lihat Semua Materi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
