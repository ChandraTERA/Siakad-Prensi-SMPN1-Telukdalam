@extends('layouts.guru-sidebar')

@section('page-title', 'Detail Materi')
@section('page-description', 'Lihat detail dan statistik download materi')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Detail Materi</h2>
            <div class="flex space-x-3">
                <a href="{{ route('guru.materi.edit', $materi) }}" class="btn btn-secondary">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('guru.materi.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Materi -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $materi->nama_materi }}</h3>
                            <div class="flex items-center space-x-4 mt-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Deskripsi</h4>
                            <p class="text-gray-600">{{ $materi->deskripsi }}</p>
                        </div>
                    @endif

                    <!-- File Information -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">File Materi</h4>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file text-2xl text-gray-400 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $materi->file_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $materi->file_size_formatted }}</div>
                                </div>
                            </div>
                            <a href="{{ Storage::url($materi->file_path) }}" target="_blank" class="btn btn-sm btn-outline">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="space-y-6">
                <!-- Download Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Statistik Download</h4>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Downloads</span>
                            <span class="text-2xl font-bold text-green-600">{{ $materi->downloads->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">File Size</span>
                            <span class="text-sm font-medium">{{ $materi->file_size_formatted }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Mata Pelajaran</span>
                            <span class="text-sm font-medium">{{ $materi->mataPelajaran->nama_mapel }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Kelas</span>
                            <span class="text-sm font-medium">{{ $materi->kelas->nama_kelas }}</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Downloads -->
                @if ($materi->downloads->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Download Terbaru</h4>
                        <div class="space-y-3">
                            @foreach ($materi->downloads->take(5) as $download)
                                <div
                                    class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $download->siswa->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $download->downloaded_at->format('d M Y, H:i') }}</div>
                                    </div>
                                    <i class="fas fa-download text-gray-400"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
