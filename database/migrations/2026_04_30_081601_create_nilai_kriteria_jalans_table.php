<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_kriteria_jalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jalan_id')->constrained('jalan')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai', 15, 2); // Nilai asli
            $table->decimal('nilai_ternormalisasi', 15, 6)->nullable(); // Nilai setelah normalisasi
            $table->year('tahun_penilaian')->default(date('Y')); // Tahun penilaian
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Satu jalan hanya punya satu nilai per kriteria per tahun
            $table->unique(['jalan_id', 'kriteria_id', 'tahun_penilaian'], 'unique_jalan_kriteria_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_kriteria_jalans');
    }
};