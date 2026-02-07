<?php

namespace App\Services;

use App\Models\PresensiStatistics;
use App\Models\Presensi;
use App\Models\Kelas;
use App\Models\User;
use Carbon\Carbon;

class AdminStatisticsService
{
    /**
     * Get global attendance statistics for all students
     */
    public static function getGlobalAttendanceStatistics($startDate = null, $endDate = null)
    {
        $query = PresensiStatistics::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $statistics = $query->get();

        if ($statistics->isEmpty()) {
            return [
                'total_siswa' => 0,
                'total_hadir' => 0,
                'total_izin' => 0,
                'total_sakit' => 0,
                'total_alpa' => 0,
                'persentase_hadir' => 0,
                'persentase_izin' => 0,
                'persentase_sakit' => 0,
                'persentase_alpa' => 0,
            ];
        }

        $totalSiswa = $statistics->sum('total_siswa');
        $totalHadir = $statistics->sum('hadir');
        $totalIzin = $statistics->sum('izin');
        $totalSakit = $statistics->sum('sakit');
        $totalAlpa = $statistics->sum('alpa');

        $totalPresensi = $totalHadir + $totalIzin + $totalSakit + $totalAlpa;

        return [
            'total_siswa' => $totalSiswa,
            'total_hadir' => $totalHadir,
            'total_izin' => $totalIzin,
            'total_sakit' => $totalSakit,
            'total_alpa' => $totalAlpa,
            'persentase_hadir' => $totalPresensi > 0 ? round(($totalHadir / $totalPresensi) * 100, 2) : 0,
            'persentase_izin' => $totalPresensi > 0 ? round(($totalIzin / $totalPresensi) * 100, 2) : 0,
            'persentase_sakit' => $totalPresensi > 0 ? round(($totalSakit / $totalPresensi) * 100, 2) : 0,
            'persentase_alpa' => $totalPresensi > 0 ? round(($totalAlpa / $totalPresensi) * 100, 2) : 0,
        ];
    }

    /**
     * Get pie chart data for global attendance
     */
    public static function getGlobalAttendancePieChartData($startDate = null, $endDate = null)
    {
        $stats = self::getGlobalAttendanceStatistics($startDate, $endDate);

        if ($stats['total_hadir'] + $stats['total_izin'] + $stats['total_sakit'] + $stats['total_alpa'] === 0) {
            return [
                'labels' => ['Belum Ada Data'],
                'datasets' => [
                    [
                        'data' => [100],
                        'backgroundColor' => ['#E5E7EB'],
                        'borderColor' => ['#D1D5DB'],
                        'borderWidth' => 1
                    ]
                ]
            ];
        }

        return [
            'labels' => ['Hadir', 'Izin', 'Sakit', 'Alpa'],
            'datasets' => [
                [
                    'data' => [$stats['total_hadir'], $stats['total_izin'], $stats['total_sakit'], $stats['total_alpa']],
                    'backgroundColor' => [
                        '#10B981', // Green for Hadir
                        '#F59E0B', // Yellow for Izin
                        '#EF4444', // Red for Sakit
                        '#6B7280'  // Gray for Alpa
                    ],
                    'borderColor' => [
                        '#059669', // Darker green
                        '#D97706', // Darker yellow
                        '#DC2626', // Darker red
                        '#4B5563'  // Darker gray
                    ],
                    'borderWidth' => 2,
                    'hoverOffset' => 4
                ]
            ]
        ];
    }

    /**
     * Get attendance comparison between classes
     */
    public static function getClassComparisonData($startDate = null, $endDate = null)
    {
        $query = PresensiStatistics::with('kelas');
        
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $statistics = $query->get()->groupBy('kelas_id');

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Persentase Kehadiran',
                    'data' => [],
                    'backgroundColor' => [],
                    'borderColor' => [],
                    'borderWidth' => 1
                ]
            ]
        ];

        $colors = [
            '#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
        ];

        $colorIndex = 0;

        foreach ($statistics as $kelasId => $kelasStats) {
            $kelas = $kelasStats->first()->kelas;
            $avgAttendance = $kelasStats->avg('persentase_hadir');
            
            $chartData['labels'][] = $kelas->nama_kelas;
            $chartData['datasets'][0]['data'][] = round($avgAttendance, 2);
            $chartData['datasets'][0]['backgroundColor'][] = $colors[$colorIndex % count($colors)];
            $chartData['datasets'][0]['borderColor'][] = $colors[$colorIndex % count($colors)];
            
            $colorIndex++;
        }

        return $chartData;
    }

    /**
     * Get teacher presensi input distribution
     */
    public static function getTeacherPresensiDistribution($startDate = null, $endDate = null)
    {
        $gurus = User::where('role', 'guru')->get();
        
        $chartData = [
            'labels' => ['Sudah Input', 'Belum Input'],
            'datasets' => [
                [
                    'data' => [0, 0],
                    'backgroundColor' => ['#10B981', '#EF4444'],
                    'borderColor' => ['#059669', '#DC2626'],
                    'borderWidth' => 2,
                    'hoverOffset' => 4
                ]
            ]
        ];

        $sudahInput = 0;
        $belumInput = 0;

        foreach ($gurus as $guru) {
            $query = PresensiStatistics::where('guru_id', $guru->id);
            
            if ($startDate && $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            }

            $hasInput = $query->exists();
            
            if ($hasInput) {
                $sudahInput++;
            } else {
                $belumInput++;
            }
        }

        $chartData['datasets'][0]['data'] = [$sudahInput, $belumInput];

        return $chartData;
    }

    /**
     * Get comprehensive dashboard statistics
     */
    public static function getDashboardStatistics($startDate = null, $endDate = null)
    {
        $globalStats = self::getGlobalAttendanceStatistics($startDate, $endDate);
        
        // Get class statistics
        $query = PresensiStatistics::with('kelas');
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }
        $classStats = $query->get();

        // Get teacher statistics
        $totalGurus = User::where('role', 'guru')->count();
        $gurusWithInput = PresensiStatistics::when($startDate && $endDate, function($query) use ($startDate, $endDate) {
            return $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->distinct('guru_id')->count('guru_id');

        // Get class performance categories
        $excellentClasses = $classStats->where('persentase_hadir', '>=', 90)->count();
        $goodClasses = $classStats->whereBetween('persentase_hadir', [80, 89.99])->count();
        $fairClasses = $classStats->whereBetween('persentase_hadir', [70, 79.99])->count();
        $poorClasses = $classStats->where('persentase_hadir', '<', 70)->count();

        return [
            'global_stats' => $globalStats,
            'total_kelas' => Kelas::count(),
            'total_siswa' => User::where('role', 'siswa')->count(),
            'total_guru' => $totalGurus,
            'guru_sudah_input' => $gurusWithInput,
            'guru_belum_input' => $totalGurus - $gurusWithInput,
            'excellent_classes' => $excellentClasses,
            'good_classes' => $goodClasses,
            'fair_classes' => $fairClasses,
            'poor_classes' => $poorClasses,
            'class_details' => self::getClassDetails($startDate, $endDate),
            'teacher_details' => self::getTeacherDetails($startDate, $endDate),
        ];
    }

    /**
     * Get detailed class information
     */
    public static function getClassDetails($startDate = null, $endDate = null)
    {
        $query = PresensiStatistics::with('kelas');
        
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $statistics = $query->get()->groupBy('kelas_id');
        $classDetails = [];

        foreach ($statistics as $kelasId => $kelasStats) {
            $kelas = $kelasStats->first()->kelas;
            $avgAttendance = $kelasStats->avg('persentase_hadir');
            $totalHadir = $kelasStats->sum('hadir');
            $totalIzin = $kelasStats->sum('izin');
            $totalSakit = $kelasStats->sum('sakit');
            $totalAlpa = $kelasStats->sum('alpa');

            $classDetails[] = [
                'kelas_id' => $kelasId,
                'kelas_name' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat,
                'total_siswa' => $kelasStats->first()->total_siswa,
                'avg_attendance' => round($avgAttendance, 2),
                'total_hadir' => $totalHadir,
                'total_izin' => $totalIzin,
                'total_sakit' => $totalSakit,
                'total_alpa' => $totalAlpa,
                'status' => self::getClassStatus($avgAttendance),
                'status_text' => self::getClassStatusText($avgAttendance),
                'status_color' => self::getClassStatusColor($avgAttendance),
                'status_badge' => self::getClassStatusBadge($avgAttendance),
            ];
        }

        return collect($classDetails)->sortByDesc('avg_attendance')->values()->toArray();
    }

    /**
     * Get detailed teacher information
     */
    public static function getTeacherDetails($startDate = null, $endDate = null)
    {
        $gurus = User::where('role', 'guru')->get();
        $teacherDetails = [];

        foreach ($gurus as $guru) {
            $query = PresensiStatistics::where('guru_id', $guru->id);
            
            if ($startDate && $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            }

            $statistics = $query->get();
            $hasInput = $statistics->count() > 0;
            $avgAttendance = $hasInput ? $statistics->avg('persentase_hadir') : 0;

            $teacherDetails[] = [
                'guru_id' => $guru->id,
                'guru_name' => $guru->name,
                'has_input' => $hasInput,
                'input_count' => $statistics->count(),
                'avg_attendance' => round($avgAttendance, 2),
                'status' => $hasInput ? 'active' : 'inactive',
                'status_text' => $hasInput ? 'Aktif' : 'Tidak Aktif',
                'status_color' => $hasInput ? 'text-green-600' : 'text-red-600',
                'status_badge' => $hasInput ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
            ];
        }

        return collect($teacherDetails)->sortByDesc('avg_attendance')->values()->toArray();
    }

    /**
     * Export data for official reports
     */
    public static function exportToCsv($startDate = null, $endDate = null)
    {
        $data = self::getDashboardStatistics($startDate, $endDate);
        
        $csvData = [];
        
        // Header
        $csvData[] = [
            'Tanggal Export',
            'Periode',
            'Total Kelas',
            'Total Siswa',
            'Total Guru',
            'Guru Sudah Input',
            'Guru Belum Input',
            'Total Hadir',
            'Total Izin',
            'Total Sakit',
            'Total Alpa',
            'Persentase Hadir',
            'Persentase Izin',
            'Persentase Sakit',
            'Persentase Alpa',
            'Kelas Excellent',
            'Kelas Good',
            'Kelas Fair',
            'Kelas Poor'
        ];

        // Data
        $periode = $startDate && $endDate ? 
            Carbon::parse($startDate)->format('d M Y') . ' - ' . Carbon::parse($endDate)->format('d M Y') : 
            'Semua Data';

        $csvData[] = [
            Carbon::now()->format('d M Y H:i:s'),
            $periode,
            $data['total_kelas'],
            $data['total_siswa'],
            $data['total_guru'],
            $data['guru_sudah_input'],
            $data['guru_belum_input'],
            $data['global_stats']['total_hadir'],
            $data['global_stats']['total_izin'],
            $data['global_stats']['total_sakit'],
            $data['global_stats']['total_alpa'],
            $data['global_stats']['persentase_hadir'] . '%',
            $data['global_stats']['persentase_izin'] . '%',
            $data['global_stats']['persentase_sakit'] . '%',
            $data['global_stats']['persentase_alpa'] . '%',
            $data['excellent_classes'],
            $data['good_classes'],
            $data['fair_classes'],
            $data['poor_classes']
        ];

        return $csvData;
    }

    /**
     * Helper methods for class status
     */
    private static function getClassStatus($attendance)
    {
        if ($attendance >= 90) return 'excellent';
        if ($attendance >= 80) return 'good';
        if ($attendance >= 70) return 'fair';
        return 'poor';
    }

    private static function getClassStatusText($attendance)
    {
        return match(self::getClassStatus($attendance)) {
            'excellent' => 'Sangat Baik',
            'good' => 'Baik',
            'fair' => 'Cukup',
            'poor' => 'Perlu Perhatian',
            default => 'Tidak Diketahui'
        };
    }

    private static function getClassStatusColor($attendance)
    {
        return match(self::getClassStatus($attendance)) {
            'excellent' => 'text-green-600',
            'good' => 'text-blue-600',
            'fair' => 'text-yellow-600',
            'poor' => 'text-red-600',
            default => 'text-gray-600'
        };
    }

    private static function getClassStatusBadge($attendance)
    {
        return match(self::getClassStatus($attendance)) {
            'excellent' => 'bg-green-100 text-green-800',
            'good' => 'bg-blue-100 text-blue-800',
            'fair' => 'bg-yellow-100 text-yellow-800',
            'poor' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
