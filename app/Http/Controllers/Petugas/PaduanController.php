<?php
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanduanController extends Controller
{
    /**
     * Display panduan penggunaan sistem untuk petugas.
     */
    public function index()
    {
        return view('petugas.panduan.index');
    }

    /**
     * Display panduan input nilai kriteria.
     */
    public function inputNilai()
    {
        return view('petugas.panduan.input-nilai');
    }

    /**
     * Display panduan manajemen data jalan.
     */
    public function manajemenJalan()
    {
        return view('petugas.panduan.manajemen-jalan');
    }

    /**
     * Display panduan riwayat penilaian.
     */
    public function riwayatPenilaian()
    {
        return view('petugas.panduan.riwayat-penilaian');
    }

    /**
     * Display FAQ (Frequently Asked Questions).
     */
    public function faq()
    {
        return view('petugas.panduan.faq');
    }
}