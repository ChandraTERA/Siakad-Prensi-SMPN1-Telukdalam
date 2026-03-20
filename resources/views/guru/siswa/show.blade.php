@extends('layouts.guru-sidebar')

@section('page-title', 'Detail Siswa - ' . $kelas->nama_kelas)
@section('page-description', 'Daftar lengkap siswa di kelas ' . $kelas->nama_kelas)

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Detail Siswa</h2>
                <p class="text-gray-600">{{ $kelas->nama_kelas }} - {{ $kelas->tingkat }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('guru.presensi.create', $kelas->id) }}" class="btn btn-primary">
                    <i class="fas fa-check mr-2"></i>Input Presensi
                </a>
                <a href="{{ route('guru.siswa.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Class Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $kelas->siswa->count() }}</div>
                    <div class="text-sm text-gray-500">Total Siswa</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">
                        {{ $kelas->siswa->where('siswa.jenis_kelamin', 'L')->count() }}</div>
                    <div class="text-sm text-gray-500">Laki-laki</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-pink-600">
                        {{ $kelas->siswa->where('siswa.jenis_kelamin', 'P')->count() }}</div>
                    <div class="text-sm text-gray-500">Perempuan</div>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Daftar Siswa</h3>
            </div>

            @if ($kelas->siswa->isNotEmpty())
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Siswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    NIS
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis Kelamin
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($kelas->siswa->sortBy('name') as $siswa)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-800">
                                                        {{ substr($siswa->name, 0, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $siswa->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $siswa->siswa->nis ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if ($siswa->siswa && $siswa->siswa->jenis_kelamin)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $siswa->siswa->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                {{ $siswa->siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $siswa->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('guru.presensi.rekap', $kelas->id) }}"
                                            class="text-blue-600 hover:text-blue-900 text-xs">
                                            <i class="fas fa-chart-line mr-1"></i>Lihat Presensi
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada siswa di kelas ini.</p>
                    <p class="text-gray-400 text-sm mt-2">Hubungi admin untuk menambahkan siswa ke kelas.</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('guru.presensi.create', $kelas->id) }}" class="btn btn-primary">
                    <i class="fas fa-check mr-2"></i>Input Presensi Hari Ini
                </a>
                <a href="{{ route('guru.presensi.rekap', $kelas->id) }}" class="btn btn-secondary">
                    <i class="fas fa-chart-bar mr-2"></i>Lihat Rekap Presensi
                </a>
                <a href="{{ route('guru.presensi.index') }}" class="btn btn-outline">
                    <i class="fas fa-list mr-2"></i>Kelas Lainnya
                </a>
            </div>
        </div>
    </div>
@endsection
