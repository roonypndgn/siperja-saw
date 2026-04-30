<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilSaw extends Model
{
    use HasFactory;

    protected $table = 'hasil_saw';

    protected $fillable = [
        'jalan_id',
        'skor_akhir',
        'peringkat',
        'tahun_perhitungan',
        'detail_perhitungan',
        'tanggal_perhitangan'
    ];

    protected $casts = [
        'skor_akhir' => 'decimal:6',
        'tahun_perhitungan' => 'integer',
        'detail_perhitungan' => 'array',
        'tanggal_perhitungan' => 'date',
    ];

    // Relasi ke jalan
    public function jalan()
    {
        return $this->belongsTo(Jalan::class);
    }

    // Scope untuk tahun tertentu
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_perhitungan', $tahun);
    }

    // Mendapatkan peringkat teratas
    public function scopePeringkatTeratas($query, $limit = 10)
    {
        return $query->orderBy('peringkat', 'asc')->limit($limit);
    }
}