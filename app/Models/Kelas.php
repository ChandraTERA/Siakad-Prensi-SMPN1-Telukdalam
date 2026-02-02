<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'nama_kelas',
        'tingkat', 
        'wali_kelas_id'
    ];

    public function waliKelas()  // ← perbaiki nama method juga
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasManyThrough(User::class, Siswa::class, 'kelas_id', 'id', 'id', 'user_id');
    }

    public function siswaData()
    {
        return $this->hasMany(Siswa::class);
    }
    public function guruPengajar()
    {
        return $this->belongsToMany(User::class, 'guru_kelas');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }
}