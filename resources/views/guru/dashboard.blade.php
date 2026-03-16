@extends('layouts.guru-sidebar')

@section('page-title', 'Dashboard Guru')
@section('page-description', 'Monitoring kelas dan presensi siswa')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Selamat Datang, {{ Auth::user()->name }}</h2>
                <p class="text-gray-600 mt-1">Dashboard monitoring kelas dan presensi siswa</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('guru.presensi.index') }}" class="btn btn-primary">
                    <i class="fas fa-check mr-2"></i>Input Presensi
                </a>
                <a href="{{ route('guru.siswa.index') }}" class="btn btn-secondary">
                    <i class="fas fa-users mr-2"></i>Data Siswa
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Monitoring</h3>
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                    <select name="periode"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="daily" {{ $periode === 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ $periode === 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $periode === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="flex-1 min-w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('guru.dashboard') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </form>

            <!-- Quick Filter Buttons -->
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('guru.dashboard', ['periode' => 'daily']) }}"
                    class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors">
                    Hari Ini
                </a>
                <a href="{{ route('guru.dashboard', ['periode' => 'weekly', 'start_date' => $currentWeek['start'], 'end_date' => $currentWeek['end']]) }}"
                    class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full hover:bg-green-200 transition-colors">
                    Minggu Ini
                </a>
                <a href="{{ route('guru.dashboard', ['periode' => 'monthly', 'start_date' => $currentMonth['start'], 'end_date' => $currentMonth['end']]) }}"
                    class="px-3 py-1 text-sm bg-purple-100 text-purple-800 rounded-full hover:bg-purple-200 transition-colors">
                    Bulan Ini
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Kelas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Kelas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dashboardStats['total_kelas'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Siswa -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dashboardStats['total_siswa'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Kehadiran -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <i class="fas fa-percentage text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rata-rata Kehadiran</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dashboardStats['rata_rata_persentase_hadir'] }}%
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status Kelas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Kelas Excellent</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $dashboardStats['kelas_excellent'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pie Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Presensi</h3>
                <div class="h-80">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>

            <!-- Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend Kehadiran 7 Hari Terakhir</h3>
                <div class="h-80">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Class Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Kelas</h3>
            @if (!empty($dashboardStats['kelas_details']))
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hadir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Izin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sakit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alpa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Persentase</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($dashboardStats['kelas_details'] as $kelas)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $kelas['kelas_name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kelas['total_siswa'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $kelas['hadir'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $kelas['izin'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $kelas['sakit'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $kelas['alpa'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kelas['persentase_hadir'] }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $kelas['status_kelas_badge'] }}">
                                            {{ $kelas['status_kelas_text'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-chart-pie text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-500">Belum ada data presensi untuk ditampilkan</p>
                </div>
            @endif
        </div>

        <!-- Classes Section -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kelas yang Anda Ajar</h3>
            @if ($kelasList->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($kelasList as $kelas)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6">
                                <h4 class="text-xl font-bold text-gray-900">{{ $kelas->nama_kelas }}</h4>
                                <p class="text-gray-600">{{ $kelas->tingkat }}</p>
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center justify-between mb-3">
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $kelas->siswa_count }} Siswa
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <i
                                                class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::today()->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('guru.presensi.create', $kelas->id) }}"
                                            class="flex-1 btn btn-primary btn-sm">
                                            <i class="fas fa-check mr-1"></i>Input Presensi
                                        </a>
                                        <a href="{{ route('guru.presensi.rekap', $kelas->id) }}"
                                            class="flex-1 btn btn-secondary btn-sm">
                                            <i class="fas fa-chart-bar mr-1"></i>Rekap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-xl border border-gray-100">
                    <p class="text-gray-500">Anda belum ditugaskan untuk mengajar di kelas manapun.</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Pie Chart
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: @json($pieChartData),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: @json($trendData),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Kehadiran: ${context.parsed.y}%`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
