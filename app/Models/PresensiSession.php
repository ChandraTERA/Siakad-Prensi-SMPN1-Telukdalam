<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PresensiSession extends Model
{
    use HasFactory;

    protected $table = 'presensi_sessions';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'keterangan',
        'auto_close_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
        'auto_close_at' => 'datetime',
    ];

    // Relationships
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

    public function scopeBuka($query)
    {
        return $query->where('status', 'buka');
    }

    public function scopeTutup($query)
    {
        return $query->where('status', 'tutup');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'buka')
                    ->where(function($q) {
                        $q->whereNull('auto_close_at')
                          ->orWhere('auto_close_at', '>', now());
                    });
    }

    // Status constants
    const STATUS_BUKA = 'buka';
    const STATUS_TUTUP = 'tutup';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_BUKA => 'Buka',
            self::STATUS_TUTUP => 'Tutup',
        ];
    }

    // Helper methods
    public function isOpen()
    {
        return $this->status === self::STATUS_BUKA && 
               ($this->auto_close_at === null || $this->auto_close_at > now());
    }

    public function isClosed()
    {
        return $this->status === self::STATUS_TUTUP || 
               ($this->auto_close_at !== null && $this->auto_close_at <= now());
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->isOpen()) {
            return 'bg-green-100 text-green-800';
        } else {
            return 'bg-red-100 text-red-800';
        }
    }

    public function getStatusTextAttribute()
    {
        if ($this->isOpen()) {
            return 'Presensi Dibuka';
        } else {
            return 'Presensi Ditutup';
        }
    }

    // Static methods
    public static function getActiveSession($guruId, $kelasId, $tanggal = null)
    {
        $tanggal = $tanggal ?: Carbon::today();
        
        $query = self::where('kelas_id', $kelasId)
                    ->where('tanggal', $tanggal)
                    ->aktif();
        
        // If guruId is provided, filter by guru_id
        if ($guruId !== null) {
            $query->where('guru_id', $guruId);
        }
        
        return $query->first();
    }

    public static function createSession($guruId, $kelasId, $tanggal = null, $autoCloseMinutes = null)
    {
        $tanggal = $tanggal ?: Carbon::today();
        $waktuMulai = Carbon::now();
        $autoCloseAt = null;

        if ($autoCloseMinutes) {
            $autoCloseAt = $waktuMulai->copy()->addMinutes($autoCloseMinutes);
        }

        return self::create([
            'guru_id' => $guruId,
            'kelas_id' => $kelasId,
            'tanggal' => $tanggal,
            'waktu_mulai' => $waktuMulai,
            'status' => self::STATUS_BUKA,
            'auto_close_at' => $autoCloseAt,
        ]);
    }
}
