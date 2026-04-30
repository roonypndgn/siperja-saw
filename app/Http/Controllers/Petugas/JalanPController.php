<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Jalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JalanPController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jalan::query();
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }
        
        // Filter status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'aktif');
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $jalan = $query->paginate(10)->withQueryString();
        
        return view('petugas.jalan.index', compact('jalan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate kode jalan otomatis
        $lastJalan = Jalan::withTrashed()->orderBy('id', 'desc')->first();
        
        if ($lastJalan && $lastJalan->kode) {
            // Ambil angka dari kode (format: JL-001)
            $lastNumber = intval(substr($lastJalan->kode, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $kodeOtomatis = 'JL-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
        return view('petugas.jalan.create', compact('kodeOtomatis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:jalans,kode',
            'nama' => 'required|string|max:200',
            'lokasi' => 'required|string|max:255',
            'panjang' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ], [
            'kode.required' => 'Kode jalan wajib diisi',
            'kode.unique' => 'Kode jalan sudah digunakan',
            'nama.required' => 'Nama jalan wajib diisi',
            'lokasi.required' => 'Lokasi jalan wajib diisi',
            'panjang.required' => 'Panjang jalan wajib diisi',
            'panjang.numeric' => 'Panjang jalan harus berupa angka',
            'latitude.numeric' => 'Latitude harus berupa angka',
            'longitude.numeric' => 'Longitude harus berupa angka',
            'latitude.between' => 'Latitude tidak valid (range -90 s/d 90)',
            'longitude.between' => 'Longitude tidak valid (range -180 s/d 180)',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Jalan::create([
            'kode' => strtoupper($request->kode),
            'nama' => ucwords($request->nama),
            'lokasi' => ucwords($request->lokasi),
            'panjang' => $request->panjang,
            'deskripsi' => $request->deskripsi,
            'latitude' => $request->latitude ? floatval($request->latitude) : null,
            'longitude' => $request->longitude ? floatval($request->longitude) : null,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('petugas.jalan.index')
            ->with('success', 'Data jalan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jalan = Jalan::with('createdBy')->findOrFail($id);
        return view('petugas.jalan.show', compact('jalan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jalan = Jalan::findOrFail($id);
        return view('petugas.jalan.edit', compact('jalan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jalan = Jalan::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:jalans,kode,' . $id,
            'nama' => 'required|string|max:200',
            'lokasi' => 'required|string|max:255',
            'panjang' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $jalan->update([
            'kode' => strtoupper($request->kode),
            'nama' => ucwords($request->nama),
            'lokasi' => ucwords($request->lokasi),
            'panjang' => $request->panjang,
            'deskripsi' => $request->deskripsi,
            'latitude' => $request->latitude ? floatval($request->latitude) : null,
            'longitude' => $request->longitude ? floatval($request->longitude) : null,
            'is_active' => $request->has('is_active'),
            'updated_by' => Auth::id(),
        ]);
        
        return redirect()->route('petugas.jalan.index')
            ->with('success', 'Data jalan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jalan = Jalan::findOrFail($id);
        
        // Cek apakah jalan sudah memiliki nilai kriteria
        if ($jalan->nilaiKriteria()->count() > 0) {
            return redirect()->route('petugas.jalan.index')
                ->with('error', 'Tidak dapat menghapus jalan yang sudah memiliki data nilai kriteria!');
        }
        
        $jalan->delete();
        
        return redirect()->route('petugas.jalan.index')
            ->with('success', 'Data jalan berhasil dihapus!');
    }
    
    /**
     * Check if kode already exists (for AJAX)
     */
    public function cekKode(Request $request)
    {
        $kode = $request->get('kode');
        $exists = Jalan::where('kode', $kode)->exists();
        
        return response()->json(['exists' => $exists]);
    }
    
    /**
     * Get coordinates from address (optional - for future use)
     */
    public function getKoordinatFromAlamat(Request $request)
{
    $alamat = $request->get('alamat');
    
    if (!$alamat) {
        return response()->json(['error' => 'Alamat tidak boleh kosong'], 400);
    }
    
    // Gunakan Nominatim (OpenStreetMap) - gratis, tanpa API key
    $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($alamat) . '&format=json&limit=1';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        
        if (!empty($data)) {
            return response()->json([
                'success' => true,
                'latitude' => $data[0]['lat'],
                'longitude' => $data[0]['lon'],
                'display_name' => $data[0]['display_name']
            ]);
        }
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Alamat tidak ditemukan. Coba dengan alamat yang lebih spesifik.'
    ]);
}
}