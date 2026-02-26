<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajarans = [
            [
                'nama_mapel' => 'Matematika',
                'kode_mapel' => 'MTK'
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'kode_mapel' => 'BIN'
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'kode_mapel' => 'BIG'
            ],
            [
                'nama_mapel' => 'Fisika',
                'kode_mapel' => 'FIS'
            ],
            [
                'nama_mapel' => 'Kimia',
                'kode_mapel' => 'KIM'
            ],
            [
                'nama_mapel' => 'Biologi',
                'kode_mapel' => 'BIO'
            ],
            [
                'nama_mapel' => 'Sejarah',
                'kode_mapel' => 'SEJ'
            ],
            [
                'nama_mapel' => 'Geografi',
                'kode_mapel' => 'GEO'
            ],
            [
                'nama_mapel' => 'Ekonomi',
                'kode_mapel' => 'EKO'
            ],
            [
                'nama_mapel' => 'Sosiologi',
                'kode_mapel' => 'SOS'
            ],
            [
                'nama_mapel' => 'Pendidikan Agama Islam',
                'kode_mapel' => 'PAI'
            ],
            [
                'nama_mapel' => 'Pendidikan Kewarganegaraan',
                'kode_mapel' => 'PKN'
            ],
            [
                'nama_mapel' => 'Seni Budaya',
                'kode_mapel' => 'SB'
            ],
            [
                'nama_mapel' => 'Pendidikan Jasmani',
                'kode_mapel' => 'PJOK'
            ],
            [
                'nama_mapel' => 'Teknologi Informasi dan Komunikasi',
                'kode_mapel' => 'TIK'
            ]
        ];

        foreach ($mataPelajarans as $mapel) {
            MataPelajaran::create($mapel);
        }
    }
}