<?php

namespace App\Models;

use App\Models\NilaiKriteriaJalan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriterias';

    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'tipe',
        'bobot',
        'satuan',
        'is_active',
        'urutan'
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi ke nilai kriteria jalan
    public function nilaiKriteriaJalan()
    {
        return $this->hasMany(NilaiKriteriaJalan::class);
    }

    // Scope untuk kriteria aktif
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk benefit
    public function scopeBenefit($query)
    {
        return $query->where('tipe', 'benefit');
    }

    // Scope untuk cost
    public function scopeCost($query)
    {
        return $query->where('tipe', 'cost');
    }

    // Accessor
    public function getNamaLengkapAttribute()
    {
        return "{$this->kode} - {$this->nama} ({$this->bobot})";
    }
}