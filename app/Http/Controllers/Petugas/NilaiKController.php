<?php

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
    
    public function index()
    {
        return redirect()->route('petugas.nilai-kriteria.create');
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
            foreach ($nilaiArray as $kriteriaId => $nilai) {
                if ($nilai === null || $nilai === '') {
                    continue;
                }

                // Cek apakah data sudah ada
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
            }

            DB::commit();

            return redirect()->route('petugas.nilai-kriteria.riwayat', ['tahun' => $tahun])
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
     * Menampilkan detail semua nilai kriteria untuk satu jalan
     */
    public function show($id)
    {
        $sampleNilai = NilaiKriteriaJalan::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $jalanId = $sampleNilai->jalan_id;
        $tahun = $sampleNilai->tahun_penilaian;

        $jalan = Jalan::findOrFail($jalanId);
        $kriteria = Kriteria::where('is_active', true)->orderBy('urutan')->get();

        $nilai = NilaiKriteriaJalan::where('jalan_id', $jalanId)
            ->where('tahun_penilaian', $tahun)
            ->where('created_by', Auth::id())
            ->get()
            ->keyBy('kriteria_id');

        $rataRataNilai = $nilai->avg('nilai') ?? 0;

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
     */
    public function edit($id)
    {
        $nilai = NilaiKriteriaJalan::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

        $jalanId = $nilai->jalan_id;
        $tahun = $nilai->tahun_penilaian;

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
     */
    public function update(Request $request, $id)
    {
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

            return redirect()->route('petugas.nilai-kriteria.riwayat', ['tahun' => $tahun])
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
        return redirect()->back()
            ->with('error', 'Anda tidak memiliki izin untuk menghapus data. Hanya admin yang dapat menghapus data.');
    }

    /**
     * RIWAYAT PENILAIAN - Daftar semua nilai yang sudah diinput petugas
     * INI ADALAH HALAMAN UTAMA UNTUK PETUGAS MELIHAT HASIL INPUTNYA
     */
    public function riwayat(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        $status = $request->get('status', 'semua');
        $userId = Auth::id();

        $query = NilaiKriteriaJalan::with(['jalan', 'kriteria', 'validatedBy'])
            ->where('created_by', $userId)
            ->where('tahun_penilaian', $tahun);

        if ($status != 'semua') {
            $query->where('status_validasi', $status);
        }

        $nilai = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $statistik = [
            'total' => NilaiKriteriaJalan::where('created_by', $userId)->where('tahun_penilaian', $tahun)->count(),
            'pending' => NilaiKriteriaJalan::where('created_by', $userId)->where('tahun_penilaian', $tahun)->where('status_validasi', 'pending')->count(),
            'divalidasi' => NilaiKriteriaJalan::where('created_by', $userId)->where('tahun_penilaian', $tahun)->where('status_validasi', 'divalidasi')->count(),
            'ditolak' => NilaiKriteriaJalan::where('created_by', $userId)->where('tahun_penilaian', $tahun)->where('status_validasi', 'ditolak')->count(),
        ];

        $tahunList = NilaiKriteriaJalan::where('created_by', $userId)
            ->select('tahun_penilaian')
            ->distinct()
            ->orderBy('tahun_penilaian', 'desc')
            ->pluck('tahun_penilaian');

        if ($tahunList->isEmpty()) {
            $tahunList = collect([date('Y')]);
        }

        return view('petugas.nilai-kriteria.riwayat', compact('nilai', 'tahun', 'status', 'statistik', 'tahunList'));
    }

    /**
     * Get nilai per kriteria untuk suatu jalan (AJAX)
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
     * Cek status validasi data untuk jalan tertentu (AJAX)
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