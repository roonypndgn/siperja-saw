<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_perhitungans', function (Blueprint $table) {
            $table->id();
            $table->year('tahun_perhitungan');
            $table->date('tanggal_perhitungan');
            $table->integer('total_jalan');
            $table->integer('total_kriteria');
            $table->json('bobot_yang_digunakan')->nullable(); // Bobot yang dipakai
            $table->string('dibuat_oleh')->nullable(); // User yang melakukan perhitungan
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_perhitungans');
    }
};