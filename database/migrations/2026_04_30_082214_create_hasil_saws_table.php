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
            $table->foreignId('jalan_id')->constrained('jalans')->onDelete('cascade');
            $table->decimal('skor_akhir', 10, 6); // Nilai akhir V
            $table->integer('peringkat'); // Peringkat/ranking
            $table->year('tahun_perhitungan');
            $table->json('detail_perhitungan')->nullable(); // Detail perhitungan dalam JSON
            $table->date('tanggal_perhitungan');
            $table->foreignId('dihitung_oleh')->constrained('users');
            $table->timestamps();
            
            // Index untuk pencarian
            $table->unique(['jalan_id', 'tahun_perhitungan']);
            $table->index(['tahun_perhitungan', 'peringkat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_saws');
    }
};