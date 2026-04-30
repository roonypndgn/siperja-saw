<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jalan;
use App\Models\Kriteria;
use App\Models\NilaiKriteriaJalan;
use App\Models\HasilSaw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));
    $status = $request->get('status', 'semua'); // Ubah default menjadi 'semua'
    
    // Debug: cek total data di database
    $totalData = NilaiKriteriaJalan::count();
    \Log::info('Total data nilai_kriteria_jalans: ' . $totalData);
    
    $nilai = NilaiKriteriaJalan::with(['jalan', 'kriteria', 'createdBy', 'validatedBy'])
        ->where('tahun_penilaian', $tahun)
        ->when($status != 'semua', function($query) use ($status) {
            $query->where('status_validasi', $status);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20)
        ->withQueryString();
    
    // Debug: cek jumlah data yang ditemukan
    \Log::info('Data ditemukan untuk tahun ' . $tahun . ': ' . $nilai->total());
    
    // Statistik
    $statistik = [
        'total' => NilaiKriteriaJalan::where('tahun_penilaian', $tahun)->count(),
        'pending' => NilaiKriteriaJalan::where('tahun_penilaian', $tahun)->where('status_validasi', 'pending')->count(),
        'divalidasi' => NilaiKriteriaJalan::where('tahun_penilaian', $tahun)->where('status_validasi', 'divalidasi')->count(),
        'ditolak' => NilaiKriteriaJalan::where('tahun_penilaian', $tahun)->where('status_validasi', 'ditolak')->count(),
    ];
    
    $tahunList = NilaiKriteriaJalan::select('tahun_penilaian')
        ->distinct()
        ->orderBy('tahun_penilaian', 'desc')
        ->pluck('tahun_penilaian');
    
    // Jika tahunList kosong, tambahkan tahun ini
    if ($tahunList->isEmpty()) {
        $tahunList = collect([date('Y')]);
    }
    
    return view('admin.nilai-kriteria.index', compact('nilai', 'tahun', 'status', 'statistik', 'tahunList'));
}
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $jalanId = $request->get('jalan_id');
        
        $jalan = Jalan::where('is_active', true)->orderBy('nama')->get();
        $kriteria = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        
        // Jika ada jalan_id yang dipilih, ambil nilai yang sudah ada
        $existingValues = [];
        if ($jalanId) {
            $existingValues = NilaiKriteriaJalan::where('jalan_id', $jalanId)
                ->where('tahun_penilaian', $tahun)
                ->pluck('nilai', 'kriteria_id')
                ->toArray();
        }
        
        return view('admin.nilai-kriteria.create', compact('jalan', 'kriteria', 'tahun', 'jalanId', 'existingValues'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // HAPUS validasi min:0|max:100, biarkan nilai bebas
        $validator = Validator::make($request->all(), [
            'jalan_id' => 'required|exists:jalans,id',
            'tahun_penilaian' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric', // Hanya required|numeric, tanpa batasan
            'catatan' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            // Tampilkan error detail untuk debugging
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . $validator->errors()->first());
        }
        
        $jalanId = $request->jalan_id;
        $tahun = $request->tahun_penilaian;
        $nilaiArray = $request->nilai;
        
        // Debug: cek data yang masuk
        \Log::info('Data yang disimpan:', [
            'jalan_id' => $jalanId,
            'tahun' => $tahun,
            'nilai' => $nilaiArray,
            'user_id' => Auth::id()
        ]);
        
        DB::beginTransaction();
        
        try {
            $savedCount = 0;
            foreach ($nilaiArray as $kriteriaId => $nilai) {
                // Pastikan nilai tidak null
                if ($nilai === null || $nilai === '') {
                    continue;
                }
                
                $result = NilaiKriteriaJalan::updateOrCreate(
                    [
                        'jalan_id' => $jalanId,
                        'kriteria_id' => $kriteriaId,
                        'tahun_penilaian' => $tahun,
                    ],
                    [
                        'nilai' => $nilai,
                        'catatan' => $request->catatan,
                        'status_validasi' => 'pending',
                        'created_by' => Auth::id(),
                    ]
                );
                $savedCount++;
            }
            
            DB::commit();
            
            \Log::info('Data berhasil disimpan', ['count' => $savedCount]);
            
            return redirect()->route('admin.nilai-kriteria.index', ['tahun' => $tahun])
                ->with('success', 'Data nilai kriteria berhasil disimpan! Menunggu validasi admin.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat menyimpan nilai kriteria:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $nilai = NilaiKriteriaJalan::with(['jalan', 'kriteria', 'createdBy', 'validatedBy'])
            ->findOrFail($id);
        
        return view('admin.nilai-kriteria.show', compact('nilai'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $nilai = NilaiKriteriaJalan::findOrFail($id);
        
        $jalan = Jalan::where('is_active', true)->orderBy('nama')->get();
        $kriteria = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        
        return view('admin.nilai-kriteria.edit', compact('nilai', 'jalan', 'kriteria'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $nilai = NilaiKriteriaJalan::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $nilai->update([
            'nilai' => $request->nilai,
            'catatan' => $request->catatan,
            'status_validasi' => 'pending',
            'validated_by' => null,
            'validated_at' => null,
        ]);
        
        return redirect()->route('admin.nilai-kriteria.index')
            ->with('success', 'Data nilai berhasil diperbarui! Menunggu validasi ulang.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $nilai = NilaiKriteriaJalan::findOrFail($id);
        $nilai->delete();
        
        return redirect()->route('admin.nilai-kriteria.index')
            ->with('success', 'Data nilai berhasil dihapus!');
    }
    
/**
 * Validasi data nilai (Admin only)
 */
public function validateData(Request $request, $id)
{
    $nilai = NilaiKriteriaJalan::findOrFail($id);
    
    $validator = Validator::make($request->all(), [
        'status' => 'required|in:divalidasi,ditolak',
        'catatan_validasi' => 'nullable|string',
    ]);
    
    if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        return redirect()->back()->withErrors($validator);
    }
    
    $nilai->update([
        'status_validasi' => $request->status,
        'validated_by' => Auth::id(),
        'validated_at' => now(),
        'catatan' => $request->catatan_validasi ?? $nilai->catatan,
    ]);
    
    if ($request->ajax()) {
        return response()->json(['success' => true]);
    }
    
    $message = $request->status == 'divalidasi' 
        ? 'Data nilai berhasil divalidasi!' 
        : 'Data nilai ditolak. Silakan input ulang.';
    
    return redirect()->route('admin.nilai-kriteria.index')
        ->with('success', $message);
}
    
    /**
     * Validasi massal (Admin only)
     */
    public function validateMass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:nilai_kriteria_jalans,id',
            'status' => 'required|in:divalidasi,ditolak',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        NilaiKriteriaJalan::whereIn('id', $request->ids)->update([
            'status_validasi' => $request->status,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);
        
        return response()->json(['success' => 'Data berhasil divalidasi!']);
    }
    
    /**
     * ==================== METODE SAW ====================
     * Proses perhitungan SAW untuk menentukan prioritas perbaikan jalan
     */
    
    /**
     * Tampilkan form perhitungan SAW
     */
    public function sawForm(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        // Cek apakah sudah ada hasil perhitungan untuk tahun ini
        $existingResult = HasilSaw::where('tahun_perhitungan', $tahun)->exists();
        
        // Statistik data yang siap dihitung
        $statistik = [
            'total_jalan' => Jalan::where('is_active', true)->count(),
            'sudah_dinilai' => NilaiKriteriaJalan::where('tahun_penilaian', $tahun)
                ->where('status_validasi', 'divalidasi')
                ->distinct('jalan_id')
                ->count('jalan_id'),
            'kriteria_aktif' => Kriteria::where('is_active', true)->count(),
        ];
        
        $statistik['siap_dihitung'] = ($statistik['sudah_dinilai'] == $statistik['total_jalan']) && $statistik['kriteria_aktif'] > 0;
        
        return view('admin.nilai-kriteria.saw-form', compact('tahun', 'existingResult', 'statistik'));
    }
    
    /**
     * Proses perhitungan SAW
     */
    public function sawProcess(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        // Validasi data sebelum proses
        $kriteriaAktif = Kriteria::where('is_active', true)->get();
        $jalanAktif = Jalan::where('is_active', true)->get();
        
        if ($kriteriaAktif->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada kriteria aktif. Silakan aktifkan kriteria terlebih dahulu.');
        }
        
        // Cek apakah semua jalan sudah memiliki nilai yang divalidasi
        $jalanTanpaNilai = [];
        foreach ($jalanAktif as $jalan) {
            $nilaiCount = NilaiKriteriaJalan::where('jalan_id', $jalan->id)
                ->where('tahun_penilaian', $tahun)
                ->where('status_validasi', 'divalidasi')
                ->count();
            
            if ($nilaiCount < $kriteriaAktif->count()) {
                $jalanTanpaNilai[] = $jalan->nama;
            }
        }
        
        if (!empty($jalanTanpaNilai)) {
            return redirect()->back()
                ->with('error', 'Masih ada jalan yang belum memiliki nilai lengkap: ' . implode(', ', array_slice($jalanTanpaNilai, 0, 5)) . 
                    (count($jalanTanpaNilai) > 5 ? ' dan ' . (count($jalanTanpaNilai) - 5) . ' lainnya' : ''));
        }
        
        DB::beginTransaction();
        
        try {
            // Step 1: Dapatkan semua nilai untuk tahun tersebut
            $nilaiKriteria = NilaiKriteriaJalan::with(['jalan', 'kriteria'])
                ->where('tahun_penilaian', $tahun)
                ->where('status_validasi', 'divalidasi')
                ->get()
                ->groupBy('jalan_id');
            
            // Step 2: Hitung nilai max dan min untuk setiap kriteria (untuk normalisasi)
            $maxValues = [];
            $minValues = [];
            
            foreach ($kriteriaAktif as $kriteria) {
                $nilaiForKriteria = NilaiKriteriaJalan::where('kriteria_id', $kriteria->id)
                    ->where('tahun_penilaian', $tahun)
                    ->where('status_validasi', 'divalidasi')
                    ->pluck('nilai')
                    ->toArray();
                
                $maxValues[$kriteria->id] = !empty($nilaiForKriteria) ? max($nilaiForKriteria) : 1;
                $minValues[$kriteria->id] = !empty($nilaiForKriteria) ? min($nilaiForKriteria) : 1;
            }
            
            // Step 3: Normalisasi dan hitung nilai akhir setiap alternatif
            $hasilPerhitungan = [];
            $detailPerhitungan = [];
            
            foreach ($nilaiKriteria as $jalanId => $nilaiItems) {
                $skorAkhir = 0;
                $detail = [];
                
                foreach ($kriteriaAktif as $kriteria) {
                    $nilaiItem = $nilaiItems->firstWhere('kriteria_id', $kriteria->id);
                    $nilaiAsli = $nilaiItem ? $nilaiItem->nilai : 0;
                    
                    // Normalisasi berdasarkan tipe kriteria
                    if ($kriteria->tipe == 'benefit') {
                        // Benefit: nilai / max
                        $nilaiNormalisasi = $maxValues[$kriteria->id] > 0 
                            ? $nilaiAsli / $maxValues[$kriteria->id] 
                            : 0;
                    } else {
                        // Cost: min / nilai
                        $nilaiNormalisasi = $nilaiAsli > 0 
                            ? $minValues[$kriteria->id] / $nilaiAsli 
                            : 0;
                    }
                    
                    // Simpan nilai ternormalisasi ke database
                    if ($nilaiItem) {
                        $nilaiItem->update(['nilai_ternormalisasi' => $nilaiNormalisasi]);
                    }
                    
                    // Hitung kontribusi bobot
                    $kontribusi = $nilaiNormalisasi * $kriteria->bobot;
                    $skorAkhir += $kontribusi;
                    
                    $detail[] = [
                        'kriteria_id' => $kriteria->id,
                        'kriteria_nama' => $kriteria->nama,
                        'nilai_asli' => $nilaiAsli,
                        'nilai_normalisasi' => round($nilaiNormalisasi, 6),
                        'bobot' => $kriteria->bobot,
                        'kontribusi' => round($kontribusi, 6),
                    ];
                }
                
                $hasilPerhitungan[$jalanId] = round($skorAkhir, 6);
                $detailPerhitungan[$jalanId] = $detail;
            }
            
            // Step 4: Urutkan berdasarkan skor tertinggi (ranking)
            arsort($hasilPerhitungan);
            
            // Step 5: Simpan hasil ke tabel hasil_saw
            // Hapus hasil lama untuk tahun yang sama
            HasilSaw::where('tahun_perhitungan', $tahun)->delete();
            
            $ranking = 1;
            foreach ($hasilPerhitungan as $jalanId => $skor) {
                HasilSaw::create([
                    'jalan_id' => $jalanId,
                    'skor_akhir' => $skor,
                    'peringkat' => $ranking,
                    'tahun_perhitungan' => $tahun,
                    'detail_perhitungan' => json_encode($detailPerhitungan[$jalanId]),
                    'tanggal_perhitungan' => now(),
                    'dihitung_oleh' => Auth::id(),
                ]);
                $ranking++;
            }
            
            DB::commit();
            
            return redirect()->route('admin.hasil-saw.index', ['tahun' => $tahun])
                ->with('success', 'Perhitungan SAW berhasil dilakukan! Ranking prioritas perbaikan jalan telah diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat perhitungan: ' . $e->getMessage());
        }
    }
    
    /**
     * Tampilkan hasil perhitungan SAW (ranking)
     */
    public function hasilSaw(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $hasil = HasilSaw::with(['jalan'])
            ->where('tahun_perhitungan', $tahun)
            ->orderBy('peringkat', 'asc')
            ->paginate(20);
        
        // Statistik perhitungan
        $statistik = [
            'total_jalan' => $hasil->total(),
            'rata_rata_skor' => HasilSaw::where('tahun_perhitungan', $tahun)->avg('skor_akhir'),
            'skor_tertinggi' => HasilSaw::where('tahun_perhitungan', $tahun)->max('skor_akhir'),
            'skor_terendah' => HasilSaw::where('tahun_perhitungan', $tahun)->min('skor_akhir'),
        ];
        
        $tahunList = HasilSaw::select('tahun_perhitungan')
            ->distinct()
            ->orderBy('tahun_perhitungan', 'desc')
            ->pluck('tahun_perhitungan');
        
        return view('admin.hasil-saw.index', compact('hasil', 'tahun', 'statistik', 'tahunList'));
    }
    
    /**
     * Detail hasil perhitungan SAW per jalan
     */
    public function hasilSawDetail($id)
    {
        $hasil = HasilSaw::with(['jalan'])
            ->findOrFail($id);
        
        $detailPerhitungan = json_decode($hasil->detail_perhitungan, true);
        
        return view('admin.hasil-saw.show', compact('hasil', 'detailPerhitungan'));
    }
    
    /**
     * Export hasil SAW ke PDF
     */
    public function exportHasilSaw($tahun)
    {
        $hasil = HasilSaw::with(['jalan'])
            ->where('tahun_perhitungan', $tahun)
            ->orderBy('peringkat', 'asc')
            ->get();
        
        $pdf = PDF::loadView('admin.hasil-saw.pdf', compact('hasil', 'tahun'));
        return $pdf->download('Hasil_SAW_' . $tahun . '.pdf');
    }
    
    /**
     * Cek kelengkapan data per jalan (untuk AJAX)
     */
    public function cekKelengkapan(Request $request)
    {
        $jalanId = $request->get('jalan_id');
        $tahun = $request->get('tahun', date('Y'));
        
        $kriteriaCount = Kriteria::where('is_active', true)->count();
        $nilaiCount = NilaiKriteriaJalan::where('jalan_id', $jalanId)
            ->where('tahun_penilaian', $tahun)
            ->where('status_validasi', 'divalidasi')
            ->count();
        
        $isComplete = $nilaiCount >= $kriteriaCount;
        $missingCount = $kriteriaCount - $nilaiCount;
        
        return response()->json([
            'is_complete' => $isComplete,
            'missing_count' => $missingCount,
            'total_kriteria' => $kriteriaCount,
            'nilai_count' => $nilaiCount,
        ]);
    }
    
    /**
     * Get nilai per kriteria untuk suatu jalan (untuk AJAX)
     */
    public function getNilaiByJalan(Request $request)
    {
        $jalanId = $request->get('jalan_id');
        $tahun = $request->get('tahun', date('Y'));
        
        $nilai = NilaiKriteriaJalan::where('jalan_id', $jalanId)
            ->where('tahun_penilaian', $tahun)
            ->with('kriteria')
            ->get()
            ->keyBy('kriteria_id');
        
        return response()->json($nilai);
    }
}