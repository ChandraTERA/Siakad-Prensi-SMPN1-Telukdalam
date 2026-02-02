<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenMandiri extends Model
{
    protected $table = 'absen_mandiri';
    
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'waktu_absen',
        'foto_absen',
        'keterangan',
        'status',
        'guru_id',
        'waktu_approval',
        'keterangan_guru',
        'latitude',
        'longitude',
        'distance_meters',
        'is_within_radius',
        'gps_error',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_absen' => 'datetime:H:i',
        'waktu_approval' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_within_radius' => 'boolean',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Scope untuk absensi yang berada dalam radius sekolah
     */
    public function scopeWithinRadius($query)
    {
        return $query->where('is_within_radius', true);
    }

    /**
     * Scope untuk absensi yang berada di luar radius sekolah
     */
    public function scopeOutsideRadius($query)
    {
        return $query->where('is_within_radius', false);
    }

    /**
     * Scope untuk absensi dengan GPS error
     */
    public function scopeWithGpsError($query)
    {
        return $query->whereNotNull('gps_error');
    }

    /**
     * Get GPS status badge
     */
    public function getGpsStatusBadgeAttribute()
    {
        if ($this->gps_error) {
            return 'bg-red-100 text-red-800';
        }
        
        return $this->is_within_radius ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
    }

    /**
     * Get GPS status text
     */
    public function getGpsStatusTextAttribute()
    {
        if ($this->gps_error) {
            return 'GPS Error';
        }
        
        return $this->is_within_radius ? 'Dalam Radius' : 'Di Luar Radius';
    }
}