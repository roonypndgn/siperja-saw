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
        return view('petugas.jalan.create');
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
            'latitude.between' => 'Latitude tidak valid',
            'longitude.between' => 'Longitude tidak valid',
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
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
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
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
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
}