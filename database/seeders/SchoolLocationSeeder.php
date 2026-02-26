<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolLocation;

class SchoolLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data untuk lokasi sekolah
        SchoolLocation::create([
            'nama_lokasi' => 'SMA Negeri 1 Jakarta',
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'radius_meter' => 100,
            'alamat' => 'Jl. Budi Utomo No. 7, Ps. Baru, Kecamatan Sawah Besar, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10710',
            'is_active' => true,
        ]);

        // Contoh lokasi sekolah lain (nonaktif)
        SchoolLocation::create([
            'nama_lokasi' => 'SMA Negeri 2 Jakarta',
            'latitude' => -6.194444,
            'longitude' => 106.822222,
            'radius_meter' => 150,
            'alamat' => 'Jl. Manggarai Utara VI, Manggarai, Kecamatan Tebet, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12850',
            'is_active' => false,
        ]);
    }
}
