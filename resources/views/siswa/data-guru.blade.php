@extends('layouts.siswa-sidebar')

@section('page-title', 'Data Guru')
@section('page-description', 'Informasi guru-guru di sekolah')

@section('content')
    <div class="space-y-6">
        <!-- Search Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('siswa.data-guru.index') }}" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Guru</label>
                    <input type="text" name="search" id="search" value="{{ $search }}"
                        placeholder="Cari berdasarkan nama, NIP, atau mata pelajaran..."
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
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
                @if ($search)
                    <div>
                        <a href="{{ route('siswa.data-guru.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                            Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Teachers Grid -->
        @if ($guru->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($guru as $teacher)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="p-6">
                            <!-- Profile Photo -->
                            <div class="flex justify-center mb-4">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                                    @if ($teacher->profile_photo_path)
                                        <img src="{{ asset('storage/' . $teacher->profile_photo_path) }}"
                                            alt="{{ $teacher->name }}" class="w-20 h-20 rounded-full object-cover">
                                    @else
                                        <span class="text-2xl font-bold text-green-600">
                                            {{ substr($teacher->name, 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Teacher Info -->
                            <div class="text-center">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $teacher->name }}</h3>

                                @if ($teacher->guru)
                                    <div class="space-y-2 text-sm text-gray-600">
                                        <div class="flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span>{{ $teacher->guru->nip }}</span>
                                        </div>

                                        @if ($teacher->guru->mata_pelajaran)
                                            <div class="flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                    </path>
                                                </svg>
                                                <span>{{ $teacher->guru->mata_pelajaran }}</span>
                                            </div>
                                        @endif

                                        @if ($teacher->guru->telepon)
                                            <div class="flex items-center justify-center">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                    </path>
                                                </svg>
                                                <span>{{ $teacher->guru->telepon }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Contact Button -->
                            <div class="mt-4">
                                <a href="{{ route('siswa.data-guru.show', $teacher->id) }}"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-md transition-colors duration-200 block">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($guru->hasPages())
                <div class="mt-6">
                    {{ $guru->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if ($search)
                            Tidak ada guru yang ditemukan
                        @else
                            Belum ada data guru
                        @endif
                    </h3>
                    <p class="text-gray-500">
                        @if ($search)
                            Coba ubah kata kunci pencarian Anda
                        @else
                            Data guru belum tersedia saat ini
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>
@endsection
