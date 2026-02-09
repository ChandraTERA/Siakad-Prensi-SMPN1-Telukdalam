<?php

namespace App\Services;

use App\Models\PresensiStatistics;
use App\Models\Presensi;
use App\Models\Kelas;
use App\Models\User;
use Carbon\Carbon;

class PresensiStatisticsService
{
    /**
     * Generate statistics for all classes taught by a teacher
     */
    public static function generateStatisticsForGuru($guruId, $periode = 'daily', $startDate = null, $endDate = null)
    {
        $guru = User::find($guruId);
        if (!$guru || $guru->role !== 'guru') {
            return false;
        }

        // Get all classes taught by this teacher
        $kelasIds = Kelas::whereHas('guruPengajar', function($query) use ($guruId) {
            $query->where('user_id', $guruId);
        })->pluck('id');

        $results = [];

        foreach ($kelasIds as $kelasId) {
            switch ($periode) {
                case 'daily':
                    if ($startDate) {
                        $result = Presensi::generateStatisticsForKelas($kelasId, $guruId, $startDate, 'daily');
                    } else {
                        $result = Presensi::generateStatisticsForKelas($kelasId, $guruId, now()->toDateString(), 'daily');
                    }
                    break;
                
                case 'weekly':
                    if ($startDate && $endDate) {
                        $result = Presensi::generateWeeklyStatisticsForKelas($kelasId, $guruId, $startDate, $endDate);
                    } else {
                        $startOfWeek = now()->startOfWeek();
                        $endOfWeek = now()->endOfWeek();
                        $result = Presensi::generateWeeklyStatisticsForKelas($kelasId, $guruId, $startOfWeek, $endOfWeek);
                    }
                    break;
                
                case 'monthly':
                    if ($startDate) {
                        $date = Carbon::parse($startDate);
                        $result = Presensi::generateMonthlyStatisticsForKelas($kelasId, $guruId, $date->year, $date->month);
                    } else {
                        $result = Presensi::generateMonthlyStatisticsForKelas($kelasId, $guruId, now()->year, now()->month);
                    }
                    break;
                
                default:
                    $result = false;
            }

            if ($result) {
                $results[] = $result;
            }
        }

        return $results;
    }

    /**
     * Get chart data for pie chart visualization
     */
    public static function getPieChartDataForGuru($guruId, $periode = 'daily', $startDate = null, $endDate = null)
    {
        $query = PresensiStatistics::byGuru($guruId)->byPeriode($periode);
        
        if ($startDate && $endDate) {
            $query->byTanggalRange($startDate, $endDate);
        }

        $statistics = $query->with('kelas')->get();

        if ($statistics->isEmpty()) {
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

        // Aggregate data by status
        $totalHadir = $statistics->sum('hadir');
        $totalIzin = $statistics->sum('izin');
        $totalSakit = $statistics->sum('sakit');
        $totalAlpa = $statistics->sum('alpa');

        $chartData = [
            'labels' => ['Hadir', 'Izin', 'Sakit', 'Alpa'],
            'datasets' => [
                [
                    'data' => [$totalHadir, $totalIzin, $totalSakit, $totalAlpa],
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

        return $chartData;
    }

    /**
     * Get detailed statistics for dashboard
     */
    public static function getDashboardStatisticsForGuru($guruId, $periode = 'daily', $startDate = null, $endDate = null)
    {
        $query = PresensiStatistics::byGuru($guruId)->byPeriode($periode);
        
        if ($startDate && $endDate) {
            $query->byTanggalRange($startDate, $endDate);
        }

        $statistics = $query->with('kelas')->get();

        if ($statistics->isEmpty()) {
            return [
                'total_kelas' => 0,
                'total_siswa' => 0,
                'total_hadir' => 0,
                'total_izin' => 0,
                'total_sakit' => 0,
                'total_alpa' => 0,
                'rata_rata_persentase_hadir' => 0,
                'kelas_excellent' => 0,
                'kelas_good' => 0,
                'kelas_fair' => 0,
                'kelas_poor' => 0,
                'kelas_details' => []
            ];
        }

        $kelasDetails = [];
        foreach ($statistics as $stat) {
            $kelasDetails[] = [
                'kelas_id' => $stat->kelas_id,
                'kelas_name' => $stat->kelas->nama_kelas,
                'total_siswa' => $stat->total_siswa,
                'hadir' => $stat->hadir,
                'izin' => $stat->izin,
                'sakit' => $stat->sakit,
                'alpa' => $stat->alpa,
                'persentase_hadir' => $stat->persentase_hadir,
                'status_kelas' => $stat->status_kelas,
                'status_kelas_text' => $stat->status_kelas_text,
                'status_kelas_color' => $stat->status_kelas_color,
                'status_kelas_badge' => $stat->status_kelas_badge,
            ];
        }

        return [
            'total_kelas' => $statistics->count(),
            'total_siswa' => $statistics->sum('total_siswa'),
            'total_hadir' => $statistics->sum('hadir'),
            'total_izin' => $statistics->sum('izin'),
            'total_sakit' => $statistics->sum('sakit'),
            'total_alpa' => $statistics->sum('alpa'),
            'rata_rata_persentase_hadir' => round($statistics->avg('persentase_hadir'), 2),
            'kelas_excellent' => $statistics->where('persentase_hadir', '>=', 90)->count(),
            'kelas_good' => $statistics->whereBetween('persentase_hadir', [80, 89.99])->count(),
            'kelas_fair' => $statistics->whereBetween('persentase_hadir', [70, 79.99])->count(),
            'kelas_poor' => $statistics->where('persentase_hadir', '<', 70)->count(),
            'kelas_details' => $kelasDetails
        ];
    }

    /**
     * Get weekly date range for current week
     */
    public static function getCurrentWeekRange()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        return [
            'start' => $startOfWeek->toDateString(),
            'end' => $endOfWeek->toDateString(),
            'label' => $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M Y')
        ];
    }

    /**
     * Get monthly date range for current month
     */
    public static function getCurrentMonthRange()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        return [
            'start' => $startOfMonth->toDateString(),
            'end' => $endOfMonth->toDateString(),
            'label' => $startOfMonth->format('M Y')
        ];
    }

    /**
     * Get previous week date range
     */
    public static function getPreviousWeekRange()
    {
        $startOfWeek = now()->subWeek()->startOfWeek();
        $endOfWeek = now()->subWeek()->endOfWeek();
        
        return [
            'start' => $startOfWeek->toDateString(),
            'end' => $endOfWeek->toDateString(),
            'label' => $startOfWeek->format('d M') . ' - ' . $endOfWeek->format('d M Y')
        ];
    }

    /**
     * Get previous month date range
     */
    public static function getPreviousMonthRange()
    {
        $startOfMonth = now()->subMonth()->startOfMonth();
        $endOfMonth = now()->subMonth()->endOfMonth();
        
        return [
            'start' => $startOfMonth->toDateString(),
            'end' => $endOfMonth->toDateString(),
            'label' => $startOfMonth->format('M Y')
        ];
    }

    /**
     * Auto-generate statistics for all teachers
     */
    public static function generateAllStatistics($periode = 'daily')
    {
        $gurus = User::where('role', 'guru')->get();
        $results = [];

        foreach ($gurus as $guru) {
            $result = self::generateStatisticsForGuru($guru->id, $periode);
            if ($result) {
                $results[$guru->id] = $result;
            }
        }

        return $results;
    }

    /**
     * Get attendance trend data for line chart
     */
    public static function getAttendanceTrendForGuru($guruId, $days = 7)
    {
        $endDate = now();
        $startDate = now()->subDays($days - 1);

        $statistics = PresensiStatistics::byGuru($guruId)
            ->daily()
            ->byTanggalRange($startDate, $endDate)
            ->with('kelas')
            ->orderBy('tanggal')
            ->get();

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Persentase Kehadiran',
                    'data' => [],
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];

        foreach ($statistics as $stat) {
            $chartData['labels'][] = $stat->tanggal->format('d M');
            $chartData['datasets'][0]['data'][] = $stat->persentase_hadir;
        }

        return $chartData;
    }
}
