<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jalan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jalans';

    protected $fillable = [
        'kode', 'nama', 'deskripsi', 'lokasi', 'panjang',
        'latitude', 'longitude', 'is_active', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'panjang' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    // Relasi
    public function nilaiKriteria()
    {
        return $this->hasMany(NilaiKriteriaJalan::class);
    }

    public function hasilSaw()
    {
        return $this->hasMany(HasilSaw::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scope
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor
    public function getNamaLengkapAttribute()
    {
        return "{$this->kode} - {$this->nama}";
    }
}