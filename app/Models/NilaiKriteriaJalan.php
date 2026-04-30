<?php

namespace App\Models;

use App\Models\Jalan;
use App\Models\Kriteria;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKriteriaJalan extends Model
{
    use HasFactory;

    protected $table = 'nilai_kriteria_jalans';

    protected $fillable = [
        'jalan_id',
        'kriteria_id',
        'nilai',
        'nilai_ternormalisasi',
        'tahun_penilaian',
        'catatan'
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
        'nilai_ternormalisasi' => 'decimal:6',
        'tahun_penilaian' => 'integer',
    ];

    // Relasi ke jalan
    public function jalan()
    {
        return $this->belongsTo(Jalan::class);
    }

    // Relasi ke kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}