<?php

namespace App\Http\Controllers\Admin;

use App\Exports\JalanExport;
use App\Http\Controllers\Controller;
use App\Models\Jalan;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jalan::query();
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }
        
        // Filter status aktif
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'aktif');
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $jalan = $query->paginate(10)->withQueryString();
        
        return view('admin.jalan.index', compact('jalan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate kode jalan otomatis
        $lastJalan = Jalan::withTrashed()->orderBy('id', 'desc')->first();
        if ($lastJalan) {
            $lastNumber = intval(substr($lastJalan->kode, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $kodeOtomatis = 'JL-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
        return view('admin.jalan.create', compact('kodeOtomatis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|max:50|unique:jalans,kode',
            'nama' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'panjang' => 'required|numeric|min:0|max:99999999.99',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ], [
            'kode.required' => 'Kode jalan wajib diisi',
            'kode.unique' => 'Kode jalan sudah terdaftar',
            'nama.required' => 'Nama jalan wajib diisi',
            'lokasi.required' => 'Lokasi jalan wajib diisi',
            'panjang.required' => 'Panjang jalan wajib diisi',
            'panjang.numeric' => 'Panjang jalan harus berupa angka',
            'latitude.between' => 'Latitude harus antara -90 dan 90',
            'longitude.between' => 'Longitude harus antara -180 dan 180',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $jalan = Jalan::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'panjang' => $request->panjang,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => $request->has('is_active') ? true : false,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        
        return redirect()->route('admin.jalan.index')
            ->with('success', 'Data jalan "' . $jalan->nama . '" berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jalan = Jalan::with('createdBy', 'updatedBy')->findOrFail($id);
        
        // Ambil nilai kriteria untuk jalan ini
        $nilaiKriteria = $jalan->nilaiKriteria()
            ->with('kriteria')
            ->where('tahun_penilaian', date('Y'))
            ->get();
        
        // Ambil hasil SAW terbaru
        $hasilSaw = $jalan->hasilSaw()
            ->where('tahun_perhitungan', date('Y'))
            ->first();
        
        return view('admin.jalan.show', compact('jalan', 'nilaiKriteria', 'hasilSaw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jalan = Jalan::findOrFail($id);
        return view('admin.jalan.edit', compact('jalan'));
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
            'deskripsi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'panjang' => 'required|numeric|min:0|max:99999999.99',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ], [
            'kode.required' => 'Kode jalan wajib diisi',
            'kode.unique' => 'Kode jalan sudah terdaftar',
            'nama.required' => 'Nama jalan wajib diisi',
            'lokasi.required' => 'Lokasi jalan wajib diisi',
            'panjang.required' => 'Panjang jalan wajib diisi',
            'panjang.numeric' => 'Panjang jalan harus berupa angka',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $jalan->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'panjang' => $request->panjang,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => $request->has('is_active') ? true : false,
            'updated_by' => Auth::id(),
        ]);
        
        return redirect()->route('admin.jalan.index')
            ->with('success', 'Data jalan "' . $jalan->nama . '" berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy($id)
    {
        $jalan = Jalan::findOrFail($id);
        $namaJalan = $jalan->nama;
        $jalan->delete();
        
        return redirect()->route('admin.jalan.index')
            ->with('success', 'Data jalan "' . $namaJalan . '" berhasil dihapus');
    }
    
    /**
     * Restore soft deleted resource.
     */
    public function restore($id)
    {
        $jalan = Jalan::withTrashed()->findOrFail($id);
        $jalan->restore();
        
        return redirect()->route('admin.jalan.index')
            ->with('success', 'Data jalan "' . $jalan->nama . '" berhasil dipulihkan');
    }
    
    /**
     * Force delete resource.
     */
    public function forceDelete($id)
    {
        $jalan = Jalan::withTrashed()->findOrFail($id);
        $namaJalan = $jalan->nama;
        $jalan->forceDelete();
        
        return redirect()->route('admin.jalan.index')
            ->with('success', 'Data jalan "' . $namaJalan . '" berhasil dihapus permanen');
    }
    
    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleStatus($id)
    {
        $jalan = Jalan::findOrFail($id);
        $jalan->is_active = !$jalan->is_active;
        $jalan->save();
        
        $status = $jalan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.jalan.index')
            ->with('success', 'Status jalan "' . $jalan->nama . '" berhasil ' . $status);
    }
    
    /**
     * Export data jalan ke CSV/Excel.
     */
    public function export(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        
        $filename = 'Data_Jalan_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new JalanExport($search, $status), $filename);
    }
    
    /**
     * Export ke CSV (opsional)
     */
    public function exportCsv(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        
        $filename = 'Data_Jalan_' . date('Y-m-d_His') . '.csv';
        
        return Excel::download(new JalanExport($search, $status), $filename, \Maatwebsite\Excel\Excel::CSV);
    }
    public function exportPdf(Request $request)
{
    // Query data (sama seperti di atas)
    $query = Jalan::with('createdBy');
    
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('nama', 'like', "%{$search}%");
    }
    
    $jalan = $query->orderBy('created_at', 'desc')->get();
    
    $data = [
        'jalan' => $jalan,
        'title' => 'Data Jalan',
        'date' => date('d/m/Y H:i:s'),
        'total' => $jalan->count(),
        'user' => Auth::user()->name ?? 'Sistem'
    ];
    
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.jalan-pdf', $data);
    $pdf->setPaper('A4', 'landscape');
    
    return $pdf->download('Data_Jalan_' . date('Y-m-d_His') . '.pdf');
}
    
}