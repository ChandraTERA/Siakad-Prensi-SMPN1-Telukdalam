<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'siswa_id',
        'guru_id', 
        'kelas_id',
        'tanggal',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Scopes
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    // Status constants
    const STATUS_HADIR = 'hadir';
    const STATUS_IZIN = 'izin';
    const STATUS_SAKIT = 'sakit';
    const STATUS_ALPA = 'alpa';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_IZIN => 'Izin',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_ALPA => 'Alpa',
        ];
    }

    // Status badge helper
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            self::STATUS_HADIR => 'bg-green-100 text-green-800',
            self::STATUS_IZIN => 'bg-yellow-100 text-yellow-800',
            self::STATUS_SAKIT => 'bg-blue-100 text-blue-800',
            self::STATUS_ALPA => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            self::STATUS_HADIR => 'Hadir',
            self::STATUS_IZIN => 'Izin',
            self::STATUS_SAKIT => 'Sakit',
            self::STATUS_ALPA => 'Alpa',
            default => 'Unknown',
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            self::STATUS_HADIR => 'fas fa-check-circle',
            self::STATUS_IZIN => 'fas fa-exclamation-circle',
            self::STATUS_SAKIT => 'fas fa-plus-circle',
            self::STATUS_ALPA => 'fas fa-times-circle',
            default => 'fas fa-question-circle',
        };
    }

    // Static methods for statistics
    public static function getStatusStatistics($kelasId, $startDate = null, $endDate = null)
    {
        $query = self::where('kelas_id', $kelasId);
        
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }
        
        $stats = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        return [
            'hadir' => $stats['hadir'] ?? 0,
            'izin' => $stats['izin'] ?? 0,
            'sakit' => $stats['sakit'] ?? 0,
            'alpa' => $stats['alpa'] ?? 0,
            'total' => array_sum($stats),
        ];
    }

    public static function getStudentStatistics($siswaId, $startDate = null, $endDate = null)
    {
        $query = self::where('siswa_id', $siswaId);
        
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }
        
        $stats = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $total = array_sum($stats);
        $hadir = $stats['hadir'] ?? 0;
        
        return [
            'hadir' => $hadir,
            'izin' => $stats['izin'] ?? 0,
            'sakit' => $stats['sakit'] ?? 0,
            'alpa' => $stats['alpa'] ?? 0,
            'total' => $total,
            'attendance_rate' => $total > 0 ? round(($hadir / $total) * 100, 2) : 0,
        ];
    }

    // Method to generate statistics for PresensiStatistics table
    public static function generateStatisticsForKelas($kelasId, $guruId, $tanggal, $periode = 'daily')
    {
        $kelas = Kelas::find($kelasId);
        if (!$kelas) {
            return false;
        }

        $totalSiswa = $kelas->siswaData->count();
        
        // Get presensi data for the specific date and class
        $presensiData = self::where('kelas_id', $kelasId)
            ->where('tanggal', $tanggal)
            ->get();

        $hadir = $presensiData->where('status', 'hadir')->count();
        $izin = $presensiData->where('status', 'izin')->count();
        $sakit = $presensiData->where('status', 'sakit')->count();
        $alpa = $presensiData->where('status', 'alpa')->count();

        // Calculate percentages
        $persentaseHadir = $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 2) : 0;
        $persentaseIzin = $totalSiswa > 0 ? round(($izin / $totalSiswa) * 100, 2) : 0;
        $persentaseSakit = $totalSiswa > 0 ? round(($sakit / $totalSiswa) * 100, 2) : 0;
        $persentaseAlpa = $totalSiswa > 0 ? round(($alpa / $totalSiswa) * 100, 2) : 0;

        // Create or update statistics record
        return PresensiStatistics::updateOrCreate(
            [
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'tanggal' => $tanggal,
                'periode' => $periode,
            ],
            [
                'total_siswa' => $totalSiswa,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'persentase_hadir' => $persentaseHadir,
                'persentase_izin' => $persentaseIzin,
                'persentase_sakit' => $persentaseSakit,
                'persentase_alpa' => $persentaseAlpa,
            ]
        );
    }

    // Method to generate weekly statistics
    public static function generateWeeklyStatisticsForKelas($kelasId, $guruId, $startDate, $endDate)
    {
        $kelas = Kelas::find($kelasId);
        if (!$kelas) {
            return false;
        }

        $totalSiswa = $kelas->siswaData->count();
        
        // Get presensi data for the week
        $presensiData = self::where('kelas_id', $kelasId)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        $hadir = $presensiData->where('status', 'hadir')->count();
        $izin = $presensiData->where('status', 'izin')->count();
        $sakit = $presensiData->where('status', 'sakit')->count();
        $alpa = $presensiData->where('status', 'alpa')->count();

        // Calculate percentages based on total possible attendance (students * days)
        $totalPossibleAttendance = $totalSiswa * $presensiData->groupBy('tanggal')->count();
        $persentaseHadir = $totalPossibleAttendance > 0 ? round(($hadir / $totalPossibleAttendance) * 100, 2) : 0;
        $persentaseIzin = $totalPossibleAttendance > 0 ? round(($izin / $totalPossibleAttendance) * 100, 2) : 0;
        $persentaseSakit = $totalPossibleAttendance > 0 ? round(($sakit / $totalPossibleAttendance) * 100, 2) : 0;
        $persentaseAlpa = $totalPossibleAttendance > 0 ? round(($alpa / $totalPossibleAttendance) * 100, 2) : 0;

        return PresensiStatistics::updateOrCreate(
            [
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'tanggal' => $startDate,
                'periode' => 'weekly',
            ],
            [
                'total_siswa' => $totalSiswa,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'persentase_hadir' => $persentaseHadir,
                'persentase_izin' => $persentaseIzin,
                'persentase_sakit' => $persentaseSakit,
                'persentase_alpa' => $persentaseAlpa,
            ]
        );
    }

    // Method to generate monthly statistics
    public static function generateMonthlyStatisticsForKelas($kelasId, $guruId, $year, $month)
    {
        $kelas = Kelas::find($kelasId);
        if (!$kelas) {
            return false;
        }

        $totalSiswa = $kelas->siswaData->count();
        
        // Get presensi data for the month
        $presensiData = self::where('kelas_id', $kelasId)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();

        $hadir = $presensiData->where('status', 'hadir')->count();
        $izin = $presensiData->where('status', 'izin')->count();
        $sakit = $presensiData->where('status', 'sakit')->count();
        $alpa = $presensiData->where('status', 'alpa')->count();

        // Calculate percentages based on total possible attendance
        $totalPossibleAttendance = $totalSiswa * $presensiData->groupBy('tanggal')->count();
        $persentaseHadir = $totalPossibleAttendance > 0 ? round(($hadir / $totalPossibleAttendance) * 100, 2) : 0;
        $persentaseIzin = $totalPossibleAttendance > 0 ? round(($izin / $totalPossibleAttendance) * 100, 2) : 0;
        $persentaseSakit = $totalPossibleAttendance > 0 ? round(($sakit / $totalPossibleAttendance) * 100, 2) : 0;
        $persentaseAlpa = $totalPossibleAttendance > 0 ? round(($alpa / $totalPossibleAttendance) * 100, 2) : 0;

        $tanggal = \Carbon\Carbon::create($year, $month, 1);

        return PresensiStatistics::updateOrCreate(
            [
                'kelas_id' => $kelasId,
                'guru_id' => $guruId,
                'tanggal' => $tanggal,
                'periode' => 'monthly',
            ],
            [
                'total_siswa' => $totalSiswa,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpa' => $alpa,
                'persentase_hadir' => $persentaseHadir,
                'persentase_izin' => $persentaseIzin,
                'persentase_sakit' => $persentaseSakit,
                'persentase_alpa' => $persentaseAlpa,
            ]
        );
    }
}