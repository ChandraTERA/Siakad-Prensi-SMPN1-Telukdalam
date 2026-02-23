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
            $table->string('foto_absen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absen_mandiri', function (Blueprint $table) {
            $table->string('foto_absen')->nullable(false)->change();
        });
    }
};