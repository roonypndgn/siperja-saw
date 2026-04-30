// app/Models/LogPerhitungan.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPerhitungan extends Model
{
    use HasFactory;

    protected $table = 'log_perhitungans';

    protected $fillable = [
        'tahun_perhitungan',
        'tanggal_perhitungan',
        'total_jalan',
        'total_kriteria',
        'bobot_yang_digunakan',
        'dibuat_oleh',
        'keterangan'
    ];

    protected $casts = [
        'tahun_perhitungan' => 'integer',
        'tanggal_perhitungan' => 'date',
        'bobot_yang_digunakan' => 'array',
    ];
}