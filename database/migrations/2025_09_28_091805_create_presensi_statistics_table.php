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
        Schema::create('presensi_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('guru_id');
            $table->date('tanggal');
            $table->string('periode'); // 'daily', 'weekly', 'monthly'
            $table->integer('total_siswa');
            $table->integer('hadir');
            $table->integer('izin');
            $table->integer('sakit');
            $table->integer('alpa');
            $table->decimal('persentase_hadir', 5, 2)->default(0);
            $table->decimal('persentase_izin', 5, 2)->default(0);
            $table->decimal('persentase_sakit', 5, 2)->default(0);
            $table->decimal('persentase_alpa', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['kelas_id', 'tanggal']);
            $table->index(['guru_id', 'tanggal']);
            $table->index(['periode', 'tanggal']);
            $table->unique(['kelas_id', 'guru_id', 'tanggal', 'periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_statistics');
    }
};