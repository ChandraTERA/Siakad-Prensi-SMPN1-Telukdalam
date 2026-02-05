<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PresensiStatistics extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'tanggal',
        'periode',
        'total_siswa',
        'hadir',
        'izin',
        'sakit',
        'alpa',
        'persentase_hadir',
        'persentase_izin',
        'persentase_sakit',
        'persentase_alpa',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'persentase_hadir' => 'decimal:2',
        'persentase_izin' => 'decimal:2',
        'persentase_sakit' => 'decimal:2',
        'persentase_alpa' => 'decimal:2',
    ];

    // Relationships
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
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

    public function scopeByPeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    public function scopeByTanggalRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeDaily($query)
    {
        return $query->where('periode', 'daily');
    }

    public function scopeWeekly($query)
    {
        return $query->where('periode', 'weekly');
    }

    public function scopeMonthly($query)
    {
        return $query->where('periode', 'monthly');
    }

    // Helper methods
    public function getTotalPresensiAttribute()
    {
        return $this->hadir + $this->izin + $this->sakit + $this->alpa;
    }

    public function getPersentaseTidakHadirAttribute()
    {
        return $this->izin + $this->sakit + $this->alpa;
    }

    public function getStatusKelasAttribute()
    {
        if ($this->persentase_hadir >= 90) {
            return 'excellent';
        } elseif ($this->persentase_hadir >= 80) {
            return 'good';
        } elseif ($this->persentase_hadir >= 70) {
            return 'fair';
        } else {
            return 'poor';
        }
    }

    public function getStatusKelasTextAttribute()
    {
        return match($this->status_kelas) {
            'excellent' => 'Sangat Baik',
            'good' => 'Baik',
            'fair' => 'Cukup',
            'poor' => 'Perlu Perhatian',
            default => 'Tidak Diketahui'
        };
    }

    public function getStatusKelasColorAttribute()
    {
        return match($this->status_kelas) {
            'excellent' => 'text-green-600',
            'good' => 'text-blue-600',
            'fair' => 'text-yellow-600',
            'poor' => 'text-red-600',
            default => 'text-gray-600'
        };
    }

    public function getStatusKelasBadgeAttribute()
    {
        return match($this->status_kelas) {
            'excellent' => 'bg-green-100 text-green-800',
            'good' => 'bg-blue-100 text-blue-800',
            'fair' => 'bg-yellow-100 text-yellow-800',
            'poor' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Static methods for chart data
    public static function getChartDataForGuru($guruId, $periode = 'daily', $startDate = null, $endDate = null)
    {
        $query = self::byGuru($guruId)->byPeriode($periode);
        
        if ($startDate && $endDate) {
            $query->byTanggalRange($startDate, $endDate);
        }

        $statistics = $query->with('kelas')->get();

        $chartData = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => [],
                    'backgroundColor' => '#10B981',
                    'borderColor' => '#059669',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Izin',
                    'data' => [],
                    'backgroundColor' => '#F59E0B',
                    'borderColor' => '#D97706',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Sakit',
                    'data' => [],
                    'backgroundColor' => '#EF4444',
                    'borderColor' => '#DC2626',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Alpa',
                    'data' => [],
                    'backgroundColor' => '#6B7280',
                    'borderColor' => '#4B5563',
                    'borderWidth' => 1
                ]
            ]
        ];

        foreach ($statistics as $stat) {
            $chartData['labels'][] = $stat->kelas->nama_kelas;
            $chartData['datasets'][0]['data'][] = $stat->hadir;
            $chartData['datasets'][1]['data'][] = $stat->izin;
            $chartData['datasets'][2]['data'][] = $stat->sakit;
            $chartData['datasets'][3]['data'][] = $stat->alpa;
        }

        return $chartData;
    }

    public static function getSummaryForGuru($guruId, $periode = 'daily', $startDate = null, $endDate = null)
    {
        $query = self::byGuru($guruId)->byPeriode($periode);
        
        if ($startDate && $endDate) {
            $query->byTanggalRange($startDate, $endDate);
        }

        $statistics = $query->get();

        return [
            'total_kelas' => $statistics->count(),
            'total_siswa' => $statistics->sum('total_siswa'),
            'total_hadir' => $statistics->sum('hadir'),
            'total_izin' => $statistics->sum('izin'),
            'total_sakit' => $statistics->sum('sakit'),
            'total_alpa' => $statistics->sum('alpa'),
            'rata_rata_persentase_hadir' => $statistics->avg('persentase_hadir'),
            'kelas_excellent' => $statistics->where('persentase_hadir', '>=', 90)->count(),
            'kelas_good' => $statistics->whereBetween('persentase_hadir', [80, 89.99])->count(),
            'kelas_fair' => $statistics->whereBetween('persentase_hadir', [70, 79.99])->count(),
            'kelas_poor' => $statistics->where('persentase_hadir', '<', 70)->count(),
        ];
    }
}