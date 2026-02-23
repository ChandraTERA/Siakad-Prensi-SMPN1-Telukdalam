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
        Schema::create('absen_mandiri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_absen');
            $table->string('foto_absen')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('waktu_approval')->nullable();
            $table->text('keterangan_guru')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['siswa_id', 'tanggal']);
            $table->index(['status', 'tanggal']);
            $table->index(['guru_id', 'waktu_approval']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_mandiri');
    }
};