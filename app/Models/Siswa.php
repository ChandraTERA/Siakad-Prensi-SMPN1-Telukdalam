<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Siswa extends Model
{
    protected $fillable = [
        'user_id',
        'nis',
        'kelas_id',
        'alamat',
        'nama_orang_tua',
        'kontak_orang_tua',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
