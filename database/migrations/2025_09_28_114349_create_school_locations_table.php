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
        Schema::create('school_locations', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi')->comment('Nama lokasi sekolah');
            $table->decimal('latitude', 10, 8)->comment('Koordinat latitude');
            $table->decimal('longitude', 11, 8)->comment('Koordinat longitude');
            $table->integer('radius_meter')->default(100)->comment('Radius dalam meter');
            $table->text('alamat')->nullable()->comment('Alamat lengkap sekolah');
            $table->boolean('is_active')->default(true)->comment('Status aktif lokasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_locations');
    }
};
