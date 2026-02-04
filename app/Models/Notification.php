<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function getIconAttribute()
    {
        return match($this->type) {
            'presensi_verification' => 'fas fa-check-circle',
            'materi_upload' => 'fas fa-file-alt',
            'tugas_upload' => 'fas fa-tasks',
            'presensi_open' => 'fas fa-unlock',
            'presensi_close' => 'fas fa-lock',
            'kelas_baru' => 'fas fa-plus-circle',
            'siswa_baru' => 'fas fa-user-plus',
            'guru_baru' => 'fas fa-chalkboard-teacher',
            'system_update' => 'fas fa-cog',
            'announcement' => 'fas fa-bullhorn',
            default => 'fas fa-bell',
        };
    }

    public function getColorAttribute()
    {
        return match($this->type) {
            'presensi_verification' => 'text-green-600',
            'materi_upload' => 'text-blue-600',
            'tugas_upload' => 'text-purple-600',
            'presensi_open' => 'text-green-600',
            'presensi_close' => 'text-red-600',
            'kelas_baru' => 'text-blue-600',
            'siswa_baru' => 'text-green-600',
            'guru_baru' => 'text-purple-600',
            'system_update' => 'text-gray-600',
            'announcement' => 'text-yellow-600',
            default => 'text-gray-600',
        };
    }

    public function getBadgeColorAttribute()
    {
        return match($this->type) {
            'presensi_verification' => 'bg-green-100 text-green-800',
            'materi_upload' => 'bg-blue-100 text-blue-800',
            'tugas_upload' => 'bg-purple-100 text-purple-800',
            'presensi_open' => 'bg-green-100 text-green-800',
            'presensi_close' => 'bg-red-100 text-red-800',
            'kelas_baru' => 'bg-blue-100 text-blue-800',
            'siswa_baru' => 'bg-green-100 text-green-800',
            'guru_baru' => 'bg-purple-100 text-purple-800',
            'system_update' => 'bg-gray-100 text-gray-800',
            'announcement' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
