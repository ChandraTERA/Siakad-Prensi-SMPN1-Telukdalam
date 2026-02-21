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
    Schema::create('tugas', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('deskripsi');
        $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
        $table->date('deadline');
        $table->time('waktu_deadline')->nullable();
        $table->string('file_attachment')->nullable();
        $table->string('file_name')->nullable();
        $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
        $table->timestamps();
        
        $table->index(['guru_id', 'kelas_id']);
        $table->index(['deadline']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
