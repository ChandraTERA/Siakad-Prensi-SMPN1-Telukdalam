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
    Schema::create('pengumpulan_tugas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
        $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
        $table->text('jawaban')->nullable();
        $table->string('file_submission')->nullable();
        $table->string('file_name')->nullable();
        $table->timestamp('submitted_at');
        $table->enum('status', ['terlambat', 'tepat_waktu'])->default('tepat_waktu');
        $table->integer('nilai')->nullable();
        $table->text('feedback')->nullable();
        $table->timestamps();
        
        $table->unique(['tugas_id', 'siswa_id']);
        $table->index(['tugas_id', 'status']);
        $table->index(['siswa_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
