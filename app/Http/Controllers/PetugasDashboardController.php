<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jalan;
use App\Models\Kriteria;
use App\Models\NilaiKriteriaJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $tahun = date('Y');
        
        // Statistik Data Jalan
        $totalJalan = Jalan::where('is_active', true)->count();
        $jalanDenganNilai = NilaiKriteriaJalan::where('created_by', $userId)
            ->where('tahun_penilaian', $tahun)
            ->distinct('jalan_id')
            ->count('jalan_id');
        
        // Statistik Nilai Kriteria
        $totalNilai = NilaiKriteriaJalan::where('created_by', $userId)
            ->where('tahun_penilaian', $tahun)
            ->count();
        
        $nilaiPending = NilaiKriteriaJalan::where('created_by', $userId)
            ->where('tahun_penilaian', $tahun)
            ->where('status_validasi', 'pending')
            ->count();
        
        $nilaiDivalidasi = NilaiKriteriaJalan::where('created_by', $userId)
            ->where('tahun_penilaian', $tahun)
            ->where('status_validasi', 'divalidasi')
            ->count();
        
        $nilaiDitolak = NilaiKriteriaJalan::where('created_by', $userId)
            ->where('tahun_penilaian', $tahun)
            ->where('status_validasi', 'ditolak')
            ->count();
        
        // Grafik - Perkembangan Input per Bulan
        $bulanList = [];
        $nilaiPerBulan = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $bulanList[] = date('F', mktime(0, 0, 0, $i, 1));
            $count = NilaiKriteriaJalan::where('created_by', $userId)
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->count();
            $nilaiPerBulan[] = $count;
        }
        
        // Data Terbaru yang Diinput
        $dataTerbaru = NilaiKriteriaJalan::with(['jalan', 'kriteria'])
            ->where('created_by', $userId)
            ->where('tahun_penilaian', $tahun)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // 5 Jalan Terakhir yang Diinput Nilainya
        $jalanTerakhir = Jalan::whereHas('nilaiKriteria', function($query) use ($userId, $tahun) {
                $query->where('created_by', $userId)->where('tahun_penilaian', $tahun);
            })
            ->with(['nilaiKriteria' => function($query) use ($userId, $tahun) {
                $query->where('created_by', $userId)->where('tahun_penilaian', $tahun);
            }])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Ringkasan Validasi per Status
        $statusSummary = [
            'pending' => $nilaiPending,
            'divalidasi' => $nilaiDivalidasi,
            'ditolak' => $nilaiDitolak,
        ];
        
        // Target penyelesaian (berdasarkan jalan yang sudah lengkap nilainya)
        $kriteriaCount = Kriteria::where('is_active', true)->count();
        $jalanLengkap = 0;
        
        $allJalan = Jalan::where('is_active', true)->get();
        foreach ($allJalan as $jalan) {
            $nilaiCount = NilaiKriteriaJalan::where('jalan_id', $jalan->id)
                ->where('tahun_penilaian', $tahun)
                ->where('created_by', $userId)
                ->count();
            if ($nilaiCount >= $kriteriaCount && $kriteriaCount > 0) {
                $jalanLengkap++;
            }
        }
        
        $targetProgress = [
            'lengkap' => $jalanLengkap,
            'total' => $totalJalan,
            'persen' => $totalJalan > 0 ? round(($jalanLengkap / $totalJalan) * 100) : 0
        ];
        
        return view('petugas.dashboard', compact(
            'totalJalan',
            'jalanDenganNilai',
            'totalNilai',
            'nilaiPending',
            'nilaiDivalidasi',
            'nilaiDitolak',
            'bulanList',
            'nilaiPerBulan',
            'dataTerbaru',
            'jalanTerakhir',
            'statusSummary',
            'targetProgress',
            'tahun'
        ));
    }
}