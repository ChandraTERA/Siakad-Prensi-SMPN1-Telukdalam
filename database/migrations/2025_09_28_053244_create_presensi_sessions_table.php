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
        Schema::create('presensi_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->enum('status', ['buka', 'tutup'])->default('tutup');
            $table->text('keterangan')->nullable();
            $table->timestamp('auto_close_at')->nullable(); // Untuk automatic close
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['guru_id', 'kelas_id', 'tanggal']);
            $table->index(['status', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_sessions');
    }
};
