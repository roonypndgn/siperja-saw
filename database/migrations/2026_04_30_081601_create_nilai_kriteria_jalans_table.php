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
            $table->foreignId('jalan_id')->constrained('jalans')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade');
            $table->decimal('nilai', 15, 2);
            $table->decimal('nilai_ternormalisasi', 15, 6)->nullable();
            $table->year('tahun_penilaian')->default(date('Y'));
            $table->text('catatan')->nullable();
            $table->enum('status_validasi', ['pending', 'divalidasi', 'ditolak'])->default('pending');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
            
            $table->unique(['jalan_id', 'kriteria_id', 'tahun_penilaian'], 'unique_jalan_kriteria_tahun');
            $table->index(['tahun_penilaian', 'status_validasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_kriteria_jalans');
    }
};