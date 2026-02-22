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
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_materi');
            $table->text('deskripsi')->nullable();
            $table->foreignId('mapel_id')->constrained('mata_pelajarans')->onDelete('cascade');
            $table->string('file_path',2048)->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('guru_id')->constrained('users');
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->unsignedBigInteger('link_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};
