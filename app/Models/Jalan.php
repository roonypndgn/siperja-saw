<?php

namespace App\Models;

use App\Models\HasilSaw;
use App\Models\NilaiKriteriaJalan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jalan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jalans';
    
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'lokasi',
        'panjang',
        'latitude',
        'longitude',
        'is_active'
    ];

    protected $casts = [
        'panjang' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    // Relasi ke nilai kriteria
    public function nilaiKriteria()
    {
        return $this->hasMany(NilaiKriteriaJalan::class);
    }

    // Relasi ke hasil SAW
    public function hasilSaw()
    {
        return $this->hasMany(HasilSaw::class);
    }

    // Mendapatkan nilai kriteria tahun ini
    public function getNilaiTahunIni()
    {
        return $this->nilaiKriteria()
            ->where('tahun_penilaian', date('Y'))
            ->get();
    }

    // Scope untuk jalan aktif
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor nama lengkap dengan kode
    public function getNamaLengkapAttribute()
    {
        return "{$this->kode} - {$this->nama}";
    }
}