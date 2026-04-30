<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jalans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique(); // Kode jalan, misal: JL-001
            $table->string('nama', 200); // Nama jalan
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 255); // Lokasi/kelurahan/kecamatan
            $table->decimal('panjang', 10, 2); // Panjang jalan (meter)
            $table->decimal('latitude', 10, 8)->nullable(); // Untuk peta
            $table->decimal('longitude', 11, 8)->nullable(); // Untuk peta
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes(); // Soft delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jalans');
    }
};