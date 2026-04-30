<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_saws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jalan_id')->constrained('jalan')->onDelete('cascade');
            $table->decimal('skor_akhir', 10, 6); // Nilai akhir V
            $table->integer('peringkat'); // Peringkat/ranking
            $table->year('tahun_perhitungan')->default(date('Y'));
            $table->json('detail_perhitungan')->nullable(); // Detail perhitungan dalam JSON
            $table->date('tanggal_perhitungan')->default(now());
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index(['tahun_perhitungan', 'peringkat']);
            $table->unique(['jalan_id', 'tahun_perhitungan'], 'unique_jalan_hasil_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_saws');
    }
};