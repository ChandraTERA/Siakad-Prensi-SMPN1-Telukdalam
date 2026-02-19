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
        Schema::create('absen_mandiris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_absen');
            $table->string('foto_absen');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_guru')->nullable();
            $table->timestamp('waktu_persetujuan')->nullable();
            $table->timestamps();
            
            $table->unique(['siswa_id', 'tanggal']); // Satu siswa hanya bisa absen sekali per hari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_mandiris');
    }
};
