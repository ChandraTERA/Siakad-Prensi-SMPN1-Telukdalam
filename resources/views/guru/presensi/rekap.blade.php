@extends('layouts.guru-sidebar')

@section('page-title', 'Rekap Presensi - ' . $kelas->nama_kelas)
@section('page-description', 'Rekapitulasi kehadiran siswa bulan ' . $startDate->format('F Y'))

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Rekap Presensi</h2>
                <p class="text-gray-600">{{ $kelas->nama_kelas }} - {{ $kelas->tingkat }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('guru.presensi.create', $kelas->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Input Presensi
                </a>
                <a href="{{ route('guru.presensi.export', $kelas->id) }}" class="btn btn-success">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
                <a href="{{ route('guru.presensi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <form method="GET" action="{{ route('guru.presensi.rekap', $kelas->id) }}"
                class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ $search }}"
                            placeholder="Cari berdasarkan nama atau NIS..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:w-48">
                    <label for="per_page" class="block text-sm font-medium text-gray-700 mb-2">Per Halaman</label>
                    <select name="per_page" id="per_page"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5 siswa</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 siswa</option>
                        <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 siswa</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 siswa</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 siswa</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>

                    @if ($search)
                        <a href="{{ route('guru.presensi.rekap', $kelas->id) }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>

            @if ($search)
                <div class="mt-3 text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
                    ({{ $siswaPaginated->total() }} siswa ditemukan)
                </div>
            @endif
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $totalHadir = collect($rekapData)->sum('summary.hadir');
                $totalIzin = collect($rekapData)->sum('summary.izin');
                $totalSakit = collect($rekapData)->sum('summary.sakit');
                $totalAlpa = collect($rekapData)->sum('summary.alpa');
                $totalSiswa = count($rekapData);
                $totalHari = count($tanggalList);
            @endphp

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">Total Hadir</p>
                        <p class="text-2xl font-bold text-green-900">{{ $totalHadir }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">Total Izin</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $totalIzin }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-plus-circle text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">Total Sakit</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $totalSakit }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-2xl text-red-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Total Alpa</p>
                        <p class="text-2xl font-bold text-red-900">{{ $totalAlpa }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">
                        Rekap Kehadiran - {{ $startDate->format('F Y') }}
                    </h3>
                    <div class="text-sm text-gray-600">
                        @if ($siswaPaginated->total() > 0)
                            Total: {{ $siswaPaginated->total() }} siswa
                        @endif
                    </div>
                </div>
            </div>

            @if (count($rekapData) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                                    Nama Siswa
                                </th>
                                @foreach ($tanggalList as $tanggal)
                                    <th
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[60px]">
                                        {{ \Carbon\Carbon::parse($tanggal)->format('d/m') }}
                                    </th>
                                @endforeach
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                    H
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                    I
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                    S
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                    A
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                    %
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($rekapData as $siswaId => $data)
                                <tr class="hover:bg-gray-50">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10">
                                        <div>
                                            <div class="font-medium">{{ $data['siswa']->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $data['siswa']->siswa->nis ?? 'NIS N/A' }}</div>
                                        </div>
                                    </td>
                                    @foreach ($tanggalList as $tanggal)
                                        <td class="px-3 py-4 whitespace-nowrap text-center text-sm">
                                            @if (isset($data['presensi'][$tanggal]))
                                                @php
                                                    $presensi = $data['presensi'][$tanggal];
                                                    $statusColors = [
                                                        'hadir' => 'bg-green-100 text-green-800',
                                                        'izin' => 'bg-yellow-100 text-yellow-800',
                                                        'sakit' => 'bg-blue-100 text-blue-800',
                                                        'alpa' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $statusLabels = [
                                                        'hadir' => 'H',
                                                        'izin' => 'I',
                                                        'sakit' => 'S',
                                                        'alpa' => 'A',
                                                    ];
                                                @endphp
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$presensi->status] }}"
                                                    title="{{ ucfirst($presensi->status) }}{{ $presensi->keterangan ? ': ' . $presensi->keterangan : '' }}">
                                                    {{ $statusLabels[$presensi->status] }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium bg-gray-50">
                                        <span class="text-green-600">{{ $data['summary']['hadir'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium bg-gray-50">
                                        <span class="text-yellow-600">{{ $data['summary']['izin'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium bg-gray-50">
                                        <span class="text-blue-600">{{ $data['summary']['sakit'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium bg-gray-50">
                                        <span class="text-red-600">{{ $data['summary']['alpa'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium bg-gray-50">
                                        @php
                                            $total = array_sum($data['summary']);
                                            $attendanceRate =
                                                $total > 0 ? round(($data['summary']['hadir'] / $total) * 100, 1) : 0;
                                        @endphp
                                        <span
                                            class="font-bold {{ $attendanceRate >= 80 ? 'text-green-600' : ($attendanceRate >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $attendanceRate }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($siswaPaginated->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan {{ $siswaPaginated->firstItem() }} sampai {{ $siswaPaginated->lastItem() }}
                                dari {{ $siswaPaginated->total() }} siswa
                            </div>
                            <div class="flex items-center space-x-2">
                                {{ $siswaPaginated->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="px-6 py-8 text-center">
                    <i class="fas fa-chart-bar text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada data presensi untuk bulan ini.</p>
                    <p class="text-gray-400 text-sm mt-2">Mulai input presensi untuk melihat rekapitulasi.</p>
                    <a href="{{ route('guru.presensi.create', $kelas->id) }}" class="btn btn-primary mt-4">
                        <i class="fas fa-plus mr-2"></i>Input Presensi Sekarang
                    </a>
                </div>
            @endif
        </div>

        <!-- Legend -->
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Keterangan:</h4>
            <div class="flex flex-wrap gap-4 text-sm">
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 bg-green-100 border border-green-200 rounded mr-2"></span>
                    <span class="text-gray-700">H = Hadir</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 bg-yellow-100 border border-yellow-200 rounded mr-2"></span>
                    <span class="text-gray-700">I = Izin</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 bg-blue-100 border border-blue-200 rounded mr-2"></span>
                    <span class="text-gray-700">S = Sakit</span>
                </div>
                <div class="flex items-center">
                    <span class="inline-block w-4 h-4 bg-red-100 border border-red-200 rounded mr-2"></span>
                    <span class="text-gray-700">A = Alpa</span>
                </div>
            </div>
        </div>
    </div>
@endsection
