<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Tugas extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'guru_id',
        'kelas_id',
        'deadline',
        'waktu_deadline',
        'file_attachment',
        'file_name',
        'status'
    ];

    protected $casts = [
        'deadline' => 'date',
        'waktu_deadline' => 'datetime:H:i',
    ];

    // Relationships
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute()
    {
        $deadline = Carbon::parse($this->deadline);
        if ($this->waktu_deadline) {
            $deadline->setTimeFromTimeString($this->waktu_deadline);
        }
        return $deadline->isPast();
    }

    public function getDeadlineFormattedAttribute()
    {
        $deadline = Carbon::parse($this->deadline);
        if ($this->waktu_deadline) {
            $deadline->setTimeFromTimeString($this->waktu_deadline);
            return $deadline->format('d F Y, H:i');
        }
        return $deadline->format('d F Y');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'aktif' => 'bg-green-100 text-green-800',
            'selesai' => 'bg-blue-100 text-blue-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    // Count submissions
    public function getTotalSubmissionsAttribute()
    {
        return $this->pengumpulanTugas()->count();
    }

    public function getOnTimeSubmissionsAttribute()
    {
        return $this->pengumpulanTugas()->where('status', 'tepat_waktu')->count();
    }

    public function getLateSubmissionsAttribute()
    {
        return $this->pengumpulanTugas()->where('status', 'terlambat')->count();
    }
}