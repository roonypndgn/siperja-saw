<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKriteriaJalan extends Model
{
    use HasFactory;

    protected $table = 'nilai_kriteria_jalans';

    protected $fillable = [
        'jalan_id', 'kriteria_id', 'nilai', 'nilai_ternormalisasi',
        'tahun_penilaian', 'catatan', 'status_validasi', 
        'created_by', 'validated_by', 'validated_at'
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'nilai_ternormalisasi' => 'decimal:6',
        'tahun_penilaian' => 'integer',
        'validated_at' => 'datetime',
    ];

    // Relasi
    public function jalan()
    {
        return $this->belongsTo(Jalan::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Scope
    public function scopePending($query)
    {
        return $query->where('status_validasi', 'pending');
    }

    public function scopeTervalidasi($query)
    {
        return $query->where('status_validasi', 'divalidasi');
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_penilaian', $tahun);
    }
}