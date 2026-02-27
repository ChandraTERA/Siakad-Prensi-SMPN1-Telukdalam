<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        // 2. Akun Guru (beserta data relasi di tabel `gurus`)
        $guruUser = User::create([
            'name' => 'Guru Hebat',
            'email' => 'guru@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'guru',
            'nip' => '198901012010011001',
        ]);

        // Membuat data di tabel `gurus` yang terhubung dengan user guru
        $guruUser->guru()->create([
            'nip' => '198901012010011001',
            'jabatan' => 'Guru Matematika',
            'alamat' => 'Jl. Pendidikan No. 123',
            'no_telepon' => '081234567890'
        ]);

        // 3. Akun Siswa
        User::create([
            'name' => 'Siswa Rajin',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'siswa',
            'nis' => '2025001',
        ]);
    }
}