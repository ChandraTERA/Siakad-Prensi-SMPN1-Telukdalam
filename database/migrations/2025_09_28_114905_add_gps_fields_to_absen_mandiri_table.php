<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('absen_mandiri', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->comment('Koordinat latitude saat absen');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Koordinat longitude saat absen');
            $table->integer('distance_meters')->nullable()->comment('Jarak dari sekolah dalam meter');
            $table->boolean('is_within_radius')->default(false)->comment('Apakah berada dalam radius sekolah');
            $table->text('gps_error')->nullable()->comment('Error message jika GPS gagal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absen_mandiri', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'distance_meters', 'is_within_radius', 'gps_error']);
        });
    }
};
