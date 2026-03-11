@extends('layouts.admin-sidebar')

@section('page-title', 'Detail Siswa')
@section('page-description', 'Informasi lengkap mengenai data siswa')

@section('content')
    <!-- Breadcrumb & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('admin.siswa.index') }}" class="hover:text-blue-600 transition-colors duration-200  ">Data Siswa</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900 font-medium">Detail Siswa</span>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.siswa.edit', $user->id) }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Edit Data
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="relative inline-block">
                        <div
                            class="w-32 h-32 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white">
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="mt-6">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                        <div class="mt-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status Aktif
                            </span>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            {{-- <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">-</div>
                                <div class="text-sm text-gray-600">Mata Pelajaran</div>
                            </div> --}}
                            {{-- <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">-</div>
                                <div class="text-sm text-gray-600">Kelas Diampu</div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Cards -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Personal</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">NIS</label>
                            <p class="text-gray-900 font-mono">{{ $user->siswa->nis ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Kelas</label>
                            @if ($user->siswa && $user->siswa->kelas)
                                <div class="flex items-center space-x-2">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $user->siswa->kelas->nama_kelas }}
                                    </span>
                                    <span class="text-gray-500">•</span>
                                    <span class="text-sm text-gray-600">{{ $user->siswa->kelas->tingkat }}</span>
                                </div>
                            @else
                                <p class="text-gray-400 italic">Belum ada kelas</p>
                            @endif
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Kontak Orang Tua</label>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                <p class="text-gray-900">{{ $user->siswa->kontak_orang_tua ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Nama Orang Tua</label>
                            <p class="text-gray-900">{{ $user->siswa->nama_orang_tua ?? '-' }}</p>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Alamat Siswa</label>
                            <p class="text-gray-900">{{ $user->siswa->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H10a2 2 0 00-2-2V6m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h2">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Akademik</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Kelas</label>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Belum ada data
                                </span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Status Siswa</label>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Aktif
                                </span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Tanggal Daftar</label>
                            <p class="text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Terakhir Update</label>
                            <p class="text-gray-900">
                                {{ $user->siswa->updated_at ? $user->siswa->updated_at->format('d F Y') : '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Prestasi Akademik</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Nilai Rata-rata</label>
                            <div class="flex items-center text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="italic">Belum ada data</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Ranking Kelas</label>
                            <div class="flex items-center text-gray-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="italic">Belum ada data</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Total Kehadiran</label>
                            <div class="flex items-center">
                                <span class="text-2xl font-bold text-gray-400">0</span>
                                <span class="text-gray-500 ml-2">%</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Ekstrakurikuler</label>
                            <div class="flex items-center">
                                <span class="text-2xl font-bold text-gray-400">0</span>
                                <span class="text-gray-500 ml-2">kegiatan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
