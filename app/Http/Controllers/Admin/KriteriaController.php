<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kriteria::query();
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }
        
        // Filter tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }
        
        // Filter status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'aktif');
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'urutan');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $kriteria = $query->paginate(10)->withQueryString();
        
        // Hitung total bobot
        $totalBobot = Kriteria::where('is_active', true)->sum('bobot');
        
        return view('admin.kriteria.index', compact('kriteria', 'totalBobot'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate kode otomatis
        $lastKriteria = Kriteria::orderBy('id', 'desc')->first();
        if ($lastKriteria && $lastKriteria->kode) {
            $lastNumber = intval(substr($lastKriteria->kode, 1));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $kodeOtomatis = 'C' . $newNumber;
        
        // Urutan terakhir
        $lastUrutan = Kriteria::max('urutan');
        $nextUrutan = $lastUrutan + 1;
        
        // Total bobot aktif saat ini
        $totalBobotAktif = Kriteria::where('is_active', true)->sum('bobot');
        
        return view('admin.kriteria.create', compact('kodeOtomatis', 'nextUrutan', 'totalBobotAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:kriterias,kode',
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0|max:1',
            'satuan' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'urutan' => 'required|integer|min:0|unique:kriterias,urutan',
        ], [
            'kode.required' => 'Kode kriteria wajib diisi',
            'kode.unique' => 'Kode kriteria sudah digunakan',
            'nama.required' => 'Nama kriteria wajib diisi',
            'tipe.required' => 'Tipe kriteria wajib dipilih',
            'bobot.required' => 'Bobot wajib diisi',
            'bobot.numeric' => 'Bobot harus berupa angka',
            'bobot.min' => 'Bobot minimal 0',
            'bobot.max' => 'Bobot maksimal 1',
            'urutan.required' => 'Urutan wajib diisi',
            'urutan.unique' => 'Urutan sudah digunakan',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Cek total bobot setelah ditambah
        $currentTotalBobot = Kriteria::where('is_active', true)->sum('bobot');
        $newTotalBobot = $currentTotalBobot + $request->bobot;
        
        if ($newTotalBobot > 1) {
            return redirect()->back()
                ->with('error', 'Total bobot tidak boleh melebihi 1 (satu). Saat ini total bobot aktif: ' . $currentTotalBobot)
                ->withInput();
        }
        
        Kriteria::create([
            'kode' => strtoupper($request->kode),
            'nama' => ucfirst($request->nama),
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
            'bobot' => $request->bobot,
            'satuan' => $request->satuan,
            'is_active' => $request->has('is_active'),
            'urutan' => $request->urutan,
        ]);
        
        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        
        // Statistik penggunaan kriteria
        $totalPenggunaan = $kriteria->nilaiKriteriaJalan()->count();
        $tahunTerakhir = $kriteria->nilaiKriteriaJalan()->max('tahun_penilaian');
        $rataRataNilai = $kriteria->nilaiKriteriaJalan()->avg('nilai');
        
        // Riwayat penilaian (10 terbaru)
        $riwayatPenilaian = $kriteria->nilaiKriteriaJalan()
            ->with(['jalan', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.kriteria.show', compact('kriteria', 'totalPenggunaan', 'tahunTerakhir', 'rataRataNilai', 'riwayatPenilaian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        
        // Total bobot aktif tanpa kriteria ini
        $totalBobotTanpaIni = Kriteria::where('is_active', true)
            ->where('id', '!=', $id)
            ->sum('bobot');
        
        return view('admin.kriteria.edit', compact('kriteria', 'totalBobotTanpaIni'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kriterias', 'kode')->ignore($id),
            ],
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0|max:1',
            'satuan' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'urutan' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('kriterias', 'urutan')->ignore($id),
            ],
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Cek total bobot setelah update
        $oldBobot = $kriteria->bobot;
        $oldStatus = $kriteria->is_active;
        
        $currentTotalBobot = Kriteria::where('is_active', true)
            ->where('id', '!=', $id)
            ->sum('bobot');
        
        $newBobotValue = $request->has('is_active') ? $request->bobot : 0;
        $newTotalBobot = $currentTotalBobot + $newBobotValue;
        
        if ($newTotalBobot > 1) {
            return redirect()->back()
                ->with('error', 'Total bobot tidak boleh melebihi 1 (satu). Saat ini total bobot aktif (tanpa kriteria ini): ' . $currentTotalBobot)
                ->withInput();
        }
        
        $kriteria->update([
            'kode' => strtoupper($request->kode),
            'nama' => ucfirst($request->nama),
            'keterangan' => $request->keterangan,
            'tipe' => $request->tipe,
            'bobot' => $request->bobot,
            'satuan' => $request->satuan,
            'is_active' => $request->has('is_active'),
            'urutan' => $request->urutan,
        ]);
        
        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        
        // Cek apakah kriteria sudah digunakan
        if ($kriteria->nilaiKriteriaJalan()->count() > 0) {
            return redirect()->route('admin.kriteria.index')
                ->with('error', 'Tidak dapat menghapus kriteria yang sudah memiliki data nilai! Silakan nonaktifkan saja.');
        }
        
        $kriteria->delete();
        
        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus!');
    }
    
    /**
     * Update bobot secara massal (AJAX)
     */
    public function updateBobot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bobots' => 'required|array',
            'bobots.*' => 'numeric|min:0|max:1',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        $totalBobot = array_sum($request->bobots);
        
        if ($totalBobot != 1) {
            return response()->json(['error' => 'Total bobot harus sama dengan 1'], 422);
        }
        
        foreach ($request->bobots as $id => $bobot) {
            $kriteria = Kriteria::find($id);
            if ($kriteria) {
                $kriteria->update(['bobot' => $bobot]);
            }
        }
        
        return response()->json(['success' => 'Bobot berhasil diperbarui!']);
    }
    
    /**
     * Cek kode unik (AJAX)
     */
    public function cekKode(Request $request)
    {
        $kode = $request->get('kode');
        $id = $request->get('id');
        
        $query = Kriteria::where('kode', $kode);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        $exists = $query->exists();
        
        return response()->json(['exists' => $exists]);
    }
    
    /**
     * Cek urutan unik (AJAX)
     */
    public function cekUrutan(Request $request)
    {
        $urutan = $request->get('urutan');
        $id = $request->get('id');
        
        $query = Kriteria::where('urutan', $urutan);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        $exists = $query->exists();
        
        return response()->json(['exists' => $exists]);
    }
    
    /**
     * Toggle status (aktif/nonaktif)
     */
    public function toggleStatus($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        
        // Jika akan mengaktifkan, cek total bobot
        if (!$kriteria->is_active) {
            $currentTotalBobot = Kriteria::where('is_active', true)->sum('bobot');
            $newTotalBobot = $currentTotalBobot + $kriteria->bobot;
            
            if ($newTotalBobot > 1) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat mengaktifkan kriteria karena total bobot akan melebihi 1');
            }
        }
        
        $kriteria->update(['is_active' => !$kriteria->is_active]);
        
        $status = $kriteria->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()
            ->with('success', "Kriteria berhasil {$status}!");
    }
    
    /**
     * Reorder urutan kriteria
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orders' => 'required|array',
            'orders.*' => 'integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        
        foreach ($request->orders as $id => $urutan) {
            Kriteria::where('id', $id)->update(['urutan' => $urutan]);
        }
        
        return response()->json(['success' => 'Urutan berhasil diperbarui!']);
    }
}