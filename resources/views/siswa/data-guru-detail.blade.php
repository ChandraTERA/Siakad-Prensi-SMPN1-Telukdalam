@extends('layouts.siswa-sidebar')

@section('page-title', 'Detail Guru')
@section('page-description', 'Informasi detail ' . $guru->name)

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div>
            <a href="{{ route('siswa.data-guru.index') }}"
                class="inline-flex items-center text-green-600 hover:text-green-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Guru
            </a>
        </div>

        <!-- Teacher Profile Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-8">
                <div class="flex flex-col md:flex-row items-center">
                    <!-- Profile Photo -->
                    <div class="mb-4 md:mb-0 md:mr-6">
                        <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            @if ($guru->profile_photo_path)
                                <img src="{{ asset('storage/' . $guru->profile_photo_path) }}" alt="{{ $guru->name }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-white border-opacity-20">
                            @else
                                <span class="text-4xl font-bold text-white">
                                    {{ substr($guru->name, 0, 1) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="text-center md:text-left text-white">
                        <h1 class="text-3xl font-bold mb-2">{{ $guru->name }}</h1>
                        @if ($guru->guru && $guru->guru->mata_pelajaran)
                            <p class="text-green-100 text-lg mb-1">{{ $guru->guru->mata_pelajaran }}</p>
                        @endif
                        @if ($guru->guru && $guru->guru->nip)
                            <p class="text-green-200">NIP: {{ $guru->guru->nip }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="p-6">
                @if ($guru->guru)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                            <div class="space-y-3">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                        </path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">Email</p>
                                        <p class="text-gray-600">{{ $guru->email }}</p>
                                    </div>
                                </div>

                                @if ($guru->guru->telepon)
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Telepon</p>
                                            <p class="text-gray-600">{{ $guru->guru->telepon }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($guru->guru->alamat)
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Alamat</p>
                                            <p class="text-gray-600">{{ $guru->guru->alamat }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Akademik</h3>
                            <div class="space-y-3">
                                @if ($guru->guru->mata_pelajaran)
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Mata Pelajaran</p>
                                            <p class="text-gray-600">{{ $guru->guru->mata_pelajaran }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">NIP</p>
                                        <p class="text-gray-600">{{ $guru->guru->nip }}</p>
                                    </div>
                                </div>

                                @if ($guru->guru->jenis_kelamin)
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Jenis Kelamin</p>
                                            <p class="text-gray-600">
                                                {{ $guru->guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($guru->guru->tanggal_lahir)
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-8 0v13a2 2 0 002 2h4a2 2 0 002-2V7m-8 0V6a2 2 0 012-2h4a2 2 0 012 2v1">
                                            </path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Tanggal Lahir</p>
                                            <p class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($guru->guru->tanggal_lahir)->translatedFormat('d F Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500">Detail informasi guru tidak tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
