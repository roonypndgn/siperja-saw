<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique(); // Kode kriteria, misal: C1
            $table->string('nama', 100); // Nama kriteria
            $table->text('keterangan')->nullable();
            $table->enum('tipe', ['benefit', 'cost']); 
            // benefit = semakin besar nilai semakin baik
            // cost = semakin kecil nilai semakin baik
            $table->decimal('bobot', 5, 2); // Bobot (0-1), total semua bobot harus 1
            $table->string('satuan', 50)->nullable(); // Satuan, misal: %, meter, kendaraan/hari
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0); // Urutan tampil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};