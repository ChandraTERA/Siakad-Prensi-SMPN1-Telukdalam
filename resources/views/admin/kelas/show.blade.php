@extends('layouts.admin-sidebar')

@section('page-title', 'Detail Kelas')
@section('page-description', 'Informasi lengkap mengenai data kelas dan siswa')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.kelas.index') }}"
                    class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Data Kelas
                </a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-900 font-medium">{{ $kelas->nama_kelas }}</span>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('admin.kelas.edit', $kelas->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Data
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar - Class Information -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                        <h2 class="text-2xl font-bold text-white">{{ $kelas->nama_kelas }}</h2>
                        <p class="text-blue-100 mt-1">{{ $kelas->tingkat }}</p>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Total Siswa</p>
                                        <p class="text-lg font-bold text-green-600">{{ $kelas->siswa->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h4 class="text-sm font-medium text-gray-900">Wali Kelas</h4>
                                @if ($kelas->waliKelas)
                                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span
                                                    class="text-sm font-medium text-blue-600">{{ substr($kelas->waliKelas->name, 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $kelas->waliKelas->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $kelas->waliKelas->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-400 italic">Belum ada wali kelas</p>
                                @endif
                            </div>

                            <div class="space-y-3">
                                <h4 class="text-sm font-medium text-gray-900">Informasi Kelas</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Dibuat</span>
                                        <span class="text-sm text-gray-900">{{ $kelas->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Terakhir Update</span>
                                        <span
                                            class="text-sm text-gray-900">{{ $kelas->updated_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Students List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Daftar Siswa</h3>
                                <p class="text-sm text-gray-600 mt-1">Siswa yang terdaftar di kelas
                                    {{ $kelas->nama_kelas }}</p>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $kelas->siswa->count() }} siswa
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if ($kelas->siswa->count() > 0)
                            <div class="space-y-4">
                                @foreach ($kelas->siswa as $siswa)
                                    <div
                                        class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span
                                                        class="text-sm font-medium text-blue-600">{{ substr($siswa->user->name, 0, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $siswa->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $siswa->user->email }}</div>
                                                <div class="text-xs text-gray-400 font-mono">NIS: {{ $siswa->nis }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.siswa.show', $siswa->user->id) }}"
                                                class="text-blue-600 hover:text-blue-900 p-2 rounded transition-colors duration-200"
                                                title="Lihat Detail Siswa">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto h-12 w-12 text-gray-400">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="mt-4 text-sm font-medium text-gray-900">Belum ada siswa</h3>
                                <p class="mt-2 text-sm text-gray-500">Kelas ini belum memiliki siswa yang terdaftar.</p>
                                <div class="mt-6">
                                    <a href="{{ route('admin.siswa.index') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Kelola Siswa
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
