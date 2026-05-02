<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Jalan;
use App\Models\Kriteria;
use App\Models\NilaiKriteriaJalan;
use App\Models\HasilSaw;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        // ==================== STATISTIK UTAMA ====================
        
        // Data Jalan
        $totalJalan = Jalan::count();
        $jalanAktif = Jalan::where('is_active', true)->count();
        $jalanNonaktif = $totalJalan - $jalanAktif;
        
        // Data Kriteria
        $totalKriteria = Kriteria::count();
        $kriteriaAktif = Kriteria::where('is_active', true)->count();
        $totalBobot = Kriteria::where('is_active', true)->sum('bobot');
        
        // Data Penilaian
        $totalPenilaian = NilaiKriteriaJalan::where('tahun_penilaian', $tahun)->count();
        $penilaianPending = NilaiKriteriaJalan::where('tahun_penilaian', $tahun)
            ->where('status_validasi', 'pending')
            ->count();
        $penilaianDivalidasi = NilaiKriteriaJalan::where('tahun_penilaian', $tahun)
            ->where('status_validasi', 'divalidasi')
            ->count();
        $penilaianDitolak = NilaiKriteriaJalan::where('tahun_penilaian', $tahun)
            ->where('status_validasi', 'ditolak')
            ->count();
        
        // Data Hasil SAW
        $hasilSaw = HasilSaw::where('tahun_perhitungan', $tahun)->get();
        $totalHasilSaw = $hasilSaw->count();
        $rataRataSkor = $hasilSaw->avg('skor_akhir') ?? 0;
        $skorTertinggi = $hasilSaw->max('skor_akhir') ?? 0;
        $skorTerendah = $hasilSaw->min('skor_akhir') ?? 0;
        
        // ==================== PROGRESS KELENGKAPAN DATA ====================
        
        $kriteriaCount = $kriteriaAktif;
        $jalanList = Jalan::where('is_active', true)->get();
        
        $jalanLengkap = 0;
        $jalanBelumLengkap = 0;
        
        foreach ($jalanList as $jalan) {
            $nilaiCount = NilaiKriteriaJalan::where('jalan_id', $jalan->id)
                ->where('tahun_penilaian', $tahun)
                ->where('status_validasi', 'divalidasi')
                ->count();
            
            if ($nilaiCount >= $kriteriaCount && $kriteriaCount > 0) {
                $jalanLengkap++;
            } else {
                $jalanBelumLengkap++;
            }
        }
        
        $progressKelengkapan = [
            'lengkap' => $jalanLengkap,
            'belum_lengkap' => $jalanBelumLengkap,
            'total' => $jalanList->count(),
            'persen' => $jalanList->count() > 0 ? round(($jalanLengkap / $jalanList->count()) * 100) : 0
        ];
        
        // ==================== GRAFIK PERKEMBANGAN PENILAIAN ====================
        
        // Per Bulan
        $bulanList = [];
        $penilaianPerBulan = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $bulanList[] = date('F', mktime(0, 0, 0, $i, 1));
            $count = NilaiKriteriaJalan::where('tahun_penilaian', $tahun)
                ->whereMonth('created_at', $i)
                ->count();
            $penilaianPerBulan[] = $count;
        }
        
        // Per Tahun (5 tahun terakhir)
        $tahunList = [];
        $penilaianPerTahun = [];
        
        for ($i = 4; $i >= 0; $i--) {
            $thn = date('Y') - $i;
            $tahunList[] = $thn;
            $count = NilaiKriteriaJalan::where('tahun_penilaian', $thn)->count();
            $penilaianPerTahun[] = $count;
        }
        
        // ==================== STATISTIK VALIDASI ====================
        
        $validasiStats = [
            'labels' => ['Pending', 'Divalidasi', 'Ditolak'],
            'data' => [$penilaianPending, $penilaianDivalidasi, $penilaianDitolak],
            'colors' => ['#F59E0B', '#10B981', '#EF4444']
        ];
        
        // ==================== RANKING 5 BESAR ====================
        
        $top5Ranking = HasilSaw::with('jalan')
            ->where('tahun_perhitungan', $tahun)
            ->orderBy('peringkat', 'asc')
            ->limit(5)
            ->get();
        
        // ==================== AKTIVITAS TERBARU ====================
        
        $aktivitasTerbaru = NilaiKriteriaJalan::with(['jalan', 'kriteria', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // ==================== STATISTIK WAKTU ====================
        
        // Hari ini
        $hariIni = NilaiKriteriaJalan::whereDate('created_at', today())->count();
        
        // Minggu ini
        $mingguIni = NilaiKriteriaJalan::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        // Bulan ini
        $bulanIni = NilaiKriteriaJalan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $aktivitasStatistik = [
            'hari_ini' => $hariIni,
            'minggu_ini' => $mingguIni,
            'bulan_ini' => $bulanIni,
        ];
        
        // ==================== PENCAPAIAN (Achievements) ====================
        
        $achievements = [
            [
                'title' => 'Data Lengkap',
                'value' => $progressKelengkapan['persen'] . '%',
                'target' => 'Target 100%',
                'progress' => $progressKelengkapan['persen'],
                'icon' => 'fas fa-check-circle',
                'color' => '#10B981'
            ],
            [
                'title' => 'Data Tervalidasi',
                'value' => $totalPenilaian > 0 ? round(($penilaianDivalidasi / $totalPenilaian) * 100) : 0 . '%',
                'target' => 'Dari ' . $totalPenilaian . ' data',
                'progress' => $totalPenilaian > 0 ? round(($penilaianDivalidasi / $totalPenilaian) * 100) : 0,
                'icon' => 'fas fa-certificate',
                'color' => '#8B5CF6'
            ],
            [
                'title' => 'Jalan Terisi',
                'value' => $jalanLengkap . '/' . $jalanList->count(),
                'target' => 'Dari ' . $jalanList->count() . ' jalan',
                'progress' => $jalanList->count() > 0 ? round(($jalanLengkap / $jalanList->count()) * 100) : 0,
                'icon' => 'fas fa-road',
                'color' => '#3B82F6'
            ],
            [
                'title' => 'Siap Hitung SAW',
                'value' => $progressKelengkapan['persen'] == 100 ? 'Siap' : 'Belum Siap',
                'target' => 'Data lengkap 100%',
                'progress' => $progressKelengkapan['persen'],
                'icon' => 'fas fa-calculator',
                'color' => '#F9A826'
            ]
        ];
        
        // ==================== STATISTIK PENGELOLA (User Activity) ====================
        
        $topPetugas = NilaiKriteriaJalan::with('createdBy')
            ->select('created_by', DB::raw('count(*) as total'))
            ->whereYear('created_at', $tahun)
            ->groupBy('created_by')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // ==================== DATA UNTUK VIEW ====================
        
        $data = [
            // Statistik Utama
            'total_jalan' => $totalJalan,
            'jalan_aktif' => $jalanAktif,
            'jalan_nonaktif' => $jalanNonaktif,
            'total_kriteria' => $totalKriteria,
            'kriteria_aktif' => $kriteriaAktif,
            'total_bobot' => $totalBobot,
            'total_penilaian' => $totalPenilaian,
            'penilaian_pending' => $penilaianPending,
            'penilaian_divalidasi' => $penilaianDivalidasi,
            'penilaian_ditolak' => $penilaianDitolak,
            'total_hasil_saw' => $totalHasilSaw,
            'rata_rata_skor' => $rataRataSkor,
            'skor_tertinggi' => $skorTertinggi,
            'skor_terendah' => $skorTerendah,
            
            // Progress
            'progress_kelengkapan' => $progressKelengkapan,
            
            // Grafik
            'bulan_list' => $bulanList,
            'penilaian_per_bulan' => $penilaianPerBulan,
            'tahun_list' => $tahunList,
            'penilaian_per_tahun' => $penilaianPerTahun,
            'validasi_stats' => $validasiStats,
            
            // Ranking & Aktivitas
            'top5_ranking' => $top5Ranking,
            'aktivitas_terbaru' => $aktivitasTerbaru,
            'aktivitas_statistik' => $aktivitasStatistik,
            
            // Lainnya
            'achievements' => $achievements,
            'top_petugas' => $topPetugas,
            'tahun' => $tahun,
            'tahun_aktif' => $tahun,
        ];
        
        return view('admin.dashboard', $data);
    }
}