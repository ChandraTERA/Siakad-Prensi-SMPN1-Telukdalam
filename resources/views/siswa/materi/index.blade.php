@extends('layouts.siswa-sidebar')

@section('page-title', 'Materi Pembelajaran')
@section('page-description', 'Akses materi pembelajaran dari guru')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Materi Pembelajaran</h2>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('siswa.materi.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Materi</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama materi..."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>
                <div class="w-64">
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                    <select name="mapel_id" id="mapel_id"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach ($mataPelajarans as $mapel)
                            <option value="{{ $mapel->id }}" {{ request('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari
                    </button>
                </div>
                @if (request('search') || request('mapel_id'))
                    <div>
                        <a href="{{ route('siswa.materi.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                            Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        @if ($materis->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($materis as $materi)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $materi->nama_materi }}</h3>
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $materi->mataPelajaran->nama_mapel }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $materi->kelas->nama_kelas }}</span>
                                    </div>
                                </div>
                                <i class="fas fa-file text-2xl text-gray-300"></i>
                            </div>

                            @if ($materi->deskripsi)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $materi->deskripsi }}</p>
                            @endif

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $materi->guru->name }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-download mr-1"></i>
                                    {{ $materi->downloads->count() }} download
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-file mr-1"></i>
                                    {{ $materi->file_size_formatted }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $materi->created_at->format('d M Y') }}
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('siswa.materi.show', $materi) }}"
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-md transition-colors duration-200">
                                    <i class="fas fa-eye mr-1"></i>Lihat Detail
                                </a>
                                <a href="{{ route('siswa.materi.download', $materi) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors duration-200">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $materis->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada materi</h3>
                <p class="text-gray-500">
                    @if (request('search') || request('mapel_id'))
                        Tidak ada materi yang sesuai dengan pencarian Anda.
                    @else
                        Guru belum mengupload materi pembelajaran untuk kelas Anda.
                    @endif
                </p>
                @if (request('search') || request('mapel_id'))
                    <a href="{{ route('siswa.materi.index') }}"
                        class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                        Lihat Semua Materi
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection
