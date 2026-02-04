<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PengumpulanTugas extends Model
{
    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'jawaban',
        'file_submission',
        'file_name',
        'submitted_at',
        'status',
        'nilai',
        'feedback'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    // Relationships
    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    // Scopes
    public function scopeTepatWaktu($query)
    {
        return $query->where('status', 'tepat_waktu');
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', 'terlambat');
    }

    public function scopeByTugas($query, $tugasId)
    {
        return $query->where('tugas_id', $tugasId);
    }

    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    // Accessors & Mutators
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'tepat_waktu' => 'bg-green-100 text-green-800',
            'terlambat' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat' => 'Terlambat',
            default => 'Unknown',
        };
    }

    public function getNilaiFormattedAttribute()
    {
        return $this->nilai ? $this->nilai . '/100' : 'Belum Dinilai';
    }

    public function getSubmittedAtFormattedAttribute()
    {
        return $this->submitted_at ? $this->submitted_at->format('d F Y, H:i') : '-';
    }

    // Check if submission is late
    public function getIsLateAttribute()
    {
        if (!$this->submitted_at || !$this->tugas) {
            return false;
        }

        $deadline = Carbon::parse($this->tugas->deadline);
        if ($this->tugas->waktu_deadline) {
            $deadline->setTimeFromTimeString($this->tugas->waktu_deadline);
        }

        return $this->submitted_at->gt($deadline);
    }
}