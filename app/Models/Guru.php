<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
        'nip',
        'alamat',
        'no_telepon',
        'jabatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
