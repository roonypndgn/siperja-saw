<?php
// app/Http/Controllers/Petugas/NilaiKriteriaController.php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Jalan;
use App\Models\Kriteria;
use App\Models\NilaiKriteriaJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NilaiKController extends Controller
{
    /**
     * Display a listing of the resource (Group by Jalan).
     * Petugas HANYA bisa melihat data yang dibuat oleh dirinya sendiri
     */
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $userId = Auth::id();
        
        // Dapatkan semua jalan aktif
        $jalanQuery = Jalan::where('is_active', true);
        
        $jalan = $jalanQuery->orderBy('nama')->paginate(10)->withQueryString();
        
        // Dapatkan semua kriteria aktif
        $kriteriaList = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        
        // Group data nilai berdasarkan jalan - HANYA data milik petugas ini
        $dataNilai = [];
        foreach ($jalan as $j) {
            $nilai = NilaiKriteriaJalan::where('jalan_id', $j->id)
                ->where('tahun_penilaian', $tahun)
                ->where('created_by', $userId) // HANYA data milik petugas ini
                ->get();
            
            $dataNilai[] = [
                'jalan' => $j,
                'nilai' => $nilai,
                'is_complete' => $nilai->count() >= $kriteriaList->count()
            ];
        }
        
        // Statistik untuk petugas (hitung berdasarkan data miliknya)
        $totalJalan = Jalan::where('is_active', true)->count();
        $kriteriaCount = $kriteriaList->count();
        
        $jalanLengkap = 0;
        $jalanBelumLengkap = 0;
        $jalanPending = 0;
        $jalanDivalidasi = 0;
        
        foreach ($jalan as $j) {
            $nilaiCount = NilaiKriteriaJalan::where('jalan_id', $j->id)
                ->where('tahun_penilaian', $tahun)
                ->where('created_by', $userId)
                ->count();
            
            $pendingCount = NilaiKriteriaJalan::where('jalan_id', $j->id)
                ->where('tahun_penilaian', $tahun)
                ->where('created_by', $userId)
                ->where('status_validasi', 'pending')
                ->count();
            
            $divalidasiCount = NilaiKriteriaJalan::where('jalan_id', $j->id)
                ->where('tahun_penilaian', $tahun)
                ->where('created_by', $userId)
                ->where('status_validasi', 'divalidasi')
                ->count();
            
            if ($nilaiCount >= $kriteriaCount) {
                $jalanLengkap++;
            } else if ($nilaiCount > 0) {
                $jalanBelumLengkap++;
            }
            
            if ($pendingCount > 0) {
                $jalanPending++;
            }
            if ($divalidasiCount > 0) {
                $jalanDivalidasi++;
            }
        }
        
        $statistik = [
            'total_jalan' => $totalJalan,
            'data_lengkap' => $jalanLengkap,
            'belum_lengkap' => $jalanBelumLengkap,
            'pending_validasi' => $jalanPending,
            'sudah_divalidasi' => $jalanDivalidasi,
            'total_nilai' => NilaiKriteriaJalan::where('tahun_penilaian', $tahun)
                ->where('created_by', $userId)
                ->count(),
        ];
        
        $tahunList = NilaiKriteriaJalan::where('created_by', $userId)
            ->select('tahun_penilaian')
            ->distinct()
            ->orderBy('tahun_penilaian', 'desc')
            ->pluck('tahun_penilaian');
        
        if ($tahunList->isEmpty()) {
            $tahunList = collect([date('Y')]);
        }
        
        return view('petugas.nilai-kriteria.index', compact('dataNilai', 'kriteriaList', 'tahun', 'statistik', 'tahunList'));
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
        
        // Jika ada jalan_id yang dipilih, ambil nilai yang sudah ada (hanya milik petugas ini)
        $existingValues = [];
        if ($jalanId) {
            $existingValues = NilaiKriteriaJalan::where('jalan_id', $jalanId)
                ->where('tahun_penilaian', $tahun)
                ->where('created_by', Auth::id())
                ->pluck('nilai', 'kriteria_id')
                ->toArray();
        }
        
        return view('petugas.nilai-kriteria.create', compact('jalan', 'kriteria', 'tahun', 'jalanId', 'existingValues'));
    }
    
    /**
     * Store a newly created resource in storage.
     * Petugas: status selalu 'pending', menunggu validasi admin
     * Hanya bisa menyimpan data untuk jalan yang dipilih
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jalan_id' => 'required|exists:jalans,id',
            'tahun_penilaian' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric',
            'catatan' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal: ' . $validator->errors()->first());
        }
        
        $jalanId = $request->jalan_id;
        $tahun = $request->tahun_penilaian;
        $nilaiArray = $request->nilai;
        $userId = Auth::id();
        
        DB::beginTransaction();
        
        try {
            $savedCount = 0;
            foreach ($nilaiArray as $kriteriaId => $nilai) {
                if ($nilai === null || $nilai === '') {
                    continue;
                }
                
                // Cek apakah data sudah ada (untuk update)
                $existingData = NilaiKriteriaJalan::where('jalan_id', $jalanId)
                    ->where('kriteria_id', $kriteriaId)
                    ->where('tahun_penilaian', $tahun)
                    ->first();
                
                if ($existingData) {
                    // Update data yang sudah ada, reset status ke pending
                    $existingData->update([
                        'nilai' => $nilai,
                        'catatan' => $request->catatan,
                        'status_validasi' => 'pending',
                        'validated_by' => null,
                        'validated_at' => null,
                    ]);
                } else {
                    // Buat data baru
                    NilaiKriteriaJalan::create([
                        'jalan_id' => $jalanId,
                        'kriteria_id' => $kriteriaId,
                        'tahun_penilaian' => $tahun,
                        'nilai' => $nilai,
                        'catatan' => $request->catatan,
                        'status_validasi' => 'pending',
                        'created_by' => $userId,
                    ]);
                }
                $savedCount++;
            }
            
            DB::commit();
            
            return redirect()->route('petugas.nilai-kriteria.index', ['tahun' => $tahun])
                ->with('success', 'Data nilai kriteria berhasil disimpan! Menunggu validasi admin.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan nilai kriteria (Petugas):', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     * Menampilkan detail semua nilai kriteria untuk satu jalan dalam satu tahun
     * Petugas hanya bisa melihat data miliknya sendiri
     */
    public function show($id)
    {
        // Cari salah satu nilai untuk mendapatkan jalan_id dan tahun
        $sampleNilai = NilaiKriteriaJalan::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();
        
        $jalanId = $sampleNilai->jalan_id;
        $tahun = $sampleNilai->tahun_penilaian;
        
        // Ambil data jalan
        $jalan = Jalan::findOrFail($jalanId);
        
        // Ambil semua kriteria aktif
        $kriteria = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        
        // Ambil semua nilai untuk jalan dan tahun tersebut (hanya milik petugas ini)
        $nilai = NilaiKriteriaJalan::where('jalan_id', $jalanId)
            ->where('tahun_penilaian', $tahun)
            ->where('created_by', Auth::id())
            ->get()
            ->keyBy('kriteria_id');
        
        // Hitung rata-rata nilai
        $rataRataNilai = $nilai->avg('nilai') ?? 0;
        
        // Hitung ringkasan status
        $summary = [
            'valid' => $nilai->where('status_validasi', 'divalidasi')->count(),
            'pending' => $nilai->where('status_validasi', 'pending')->count(),
            'rejected' => $nilai->where('status_validasi', 'ditolak')->count(),
            'empty' => $kriteria->count() - $nilai->count(),
        ];
        
        return view('petugas.nilai-kriteria.show', compact('jalan', 'kriteria', 'nilai', 'tahun', 'rataRataNilai', 'summary'));
    }
    
    /**
     * Show the form for editing the specified resource.
     * Edit semua nilai kriteria untuk satu jalan dalam satu tahun
     * Petugas hanya bisa edit data miliknya sendiri
     */
    public function edit($id)
    {
        // Cari salah satu nilai untuk mendapatkan jalan_id dan tahun (hanya milik petugas ini)
        $nilai = NilaiKriteriaJalan::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();
        
        $jalanId = $nilai->jalan_id;
        $tahun = $nilai->tahun_penilaian;
        
        // Ambil semua nilai untuk jalan dan tahun tersebut (hanya milik petugas ini)
        $existingValues = NilaiKriteriaJalan::where('jalan_id', $jalanId)
            ->where('tahun_penilaian', $tahun)
            ->where('created_by', Auth::id())
            ->get()
            ->keyBy('kriteria_id');
        
        $jalan = Jalan::findOrFail($jalanId);
        $kriteria = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        
        return view('petugas.nilai-kriteria.edit', compact('jalan', 'kriteria', 'tahun', 'existingValues'));
    }
    
    /**
     * Update the specified resource in storage.
     * Update semua nilai kriteria untuk satu jalan dalam satu tahun
     * Petugas hanya bisa update data miliknya sendiri
     */
    public function update(Request $request, $id)
    {
        // Cari salah satu nilai untuk mendapatkan jalan_id dan tahun (hanya milik petugas ini)
        $nilaiLama = NilaiKriteriaJalan::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();
        
        $jalanId = $nilaiLama->jalan_id;
        $tahun = $nilaiLama->tahun_penilaian;
        
        $validator = Validator::make($request->all(), [
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric',
            'catatan' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $nilaiArray = $request->nilai;
        $userId = Auth::id();
        
        DB::beginTransaction();
        
        try {
            foreach ($nilaiArray as $kriteriaId => $nilai) {
                // Update atau create data (hanya untuk milik petugas ini)
                NilaiKriteriaJalan::updateOrCreate(
                    [
                        'jalan_id' => $jalanId,
                        'kriteria_id' => $kriteriaId,
                        'tahun_penilaian' => $tahun,
                        'created_by' => $userId,
                    ],
                    [
                        'nilai' => $nilai,
                        'catatan' => $request->catatan,
                        'status_validasi' => 'pending',
                        'validated_by' => null,
                        'validated_at' => null,
                    ]
                );
            }
            
            DB::commit();
            
            return redirect()->route('petugas.nilai-kriteria.index', ['tahun' => $tahun])
                ->with('success', 'Data nilai kriteria berhasil diperbarui! Menunggu validasi ulang dari admin.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     * PETUGAS TIDAK BISA MENGHAPUS DATA
     */
    public function destroy($id)
    {
        // Petugas tidak diizinkan menghapus data
        return redirect()->back()
            ->with('error', 'Anda tidak memiliki izin untuk menghapus data. Hanya admin yang dapat menghapus data.');
    }
    
    /**
     * Get nilai per kriteria untuk suatu jalan (untuk AJAX)
     * Hanya mengambil data milik petugas ini
     */
    public function getNilaiByJalan(Request $request)
    {
        $jalanId = $request->get('jalan_id');
        $tahun = $request->get('tahun', date('Y'));
        
        $nilai = NilaiKriteriaJalan::where('jalan_id', $jalanId)
            ->where('tahun_penilaian', $tahun)
            ->where('created_by', Auth::id())
            ->with('kriteria')
            ->get()
            ->keyBy('kriteria_id');
        
        return response()->json($nilai);
    }
    
    /**
     * Cek status validasi data untuk jalan tertentu
     */
    public function cekStatusValidasi(Request $request)
    {
        $jalanId = $request->get('jalan_id');
        $tahun = $request->get('tahun', date('Y'));
        
        $nilai = NilaiKriteriaJalan::where('jalan_id', $jalanId)
            ->where('tahun_penilaian', $tahun)
            ->where('created_by', Auth::id())
            ->get();
        
        $statusCount = [
            'pending' => $nilai->where('status_validasi', 'pending')->count(),
            'divalidasi' => $nilai->where('status_validasi', 'divalidasi')->count(),
            'ditolak' => $nilai->where('status_validasi', 'ditolak')->count(),
            'total' => $nilai->count(),
            'kriteria_count' => Kriteria::where('is_active', true)->count(),
        ];
        
        $statusCount['is_complete'] = $statusCount['total'] >= $statusCount['kriteria_count'];
        
        return response()->json($statusCount);
    }
}