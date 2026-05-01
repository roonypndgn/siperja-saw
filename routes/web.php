<?php

use App\Http\Controllers\Admin\JalanController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Petugas\NilaiKController;
use App\Http\Controllers\Petugas\PanduanController;
use App\Http\Controllers\Petugas\ProfilController;
use App\Http\Controllers\Admin\NilaiKriteriaController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Petugas\JalanPController;
use App\Http\Controllers\PetugasDashboardController;

/*
|--------------------------------------------------------------------------
| ROUTE UNTUK GUEST (BELUM LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Halaman form login - GET
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

    // Proses login - POST (INI YANG SERING KETINGGALAN)
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Redirect root
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

/*
|--------------------------------------------------------------------------
| ROUTE LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jalan', JalanController::class);

    // Route tambahan untuk jalan
    Route::prefix('jalan')->name('jalan.')->group(function () {
        Route::post('{id}/restore', [JalanController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [JalanController::class, 'forceDelete'])->name('force-delete');
        Route::patch('{id}/toggle-status', [JalanController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('export/excel', [JalanController::class, 'export'])->name('export.excel');
        Route::get('export/csv', [JalanController::class, 'exportCsv'])->name('export.csv');
        Route::get('export/pdf', [JalanController::class, 'exportPdf'])->name('export.pdf');
    });
    //Route untuk kriteria 
    Route::resource('kriteria', KriteriaController::class);
    Route::prefix('kriteria')->name('kriteria.')->group(function () {
        Route::get('/cek-kode', [KriteriaController::class, 'cekKode'])->name('cekKode');
        Route::get('/cek-urutan', [KriteriaController::class, 'cekUrutan'])->name('cekUrutan');
        Route::post('/update-bobot', [KriteriaController::class, 'updateBobot'])->name('updateBobot');
        Route::post('/reorder', [KriteriaController::class, 'reorder'])->name('reorder');
        Route::post('{id}/toggle-status', [KriteriaController::class, 'toggleStatus'])->name('toggle-status');
    });
    //Route untuk nilai kriteria
    Route::resource('nilai-kriteria', NilaiKriteriaController::class);
    Route::prefix('nilai-kriteria')->name('nilai-kriteria.')->group(function () {
        Route::post('/{id}/validate', [NilaiKriteriaController::class, 'validateData'])->name('validate');
        Route::post('/validate-mass', [NilaiKriteriaController::class, 'validateMass'])->name('validate-mass');
        Route::get('/cek-kelengkapan', [NilaiKriteriaController::class, 'cekKelengkapan'])->name('cek-kelengkapan');
        Route::get('/get-by-jalan', [NilaiKriteriaController::class, 'getNilaiByJalan'])->name('get-by-jalan');
        Route::delete('/delete-by-jalan/{jalanId}/{tahun}', [NilaiKriteriaController::class, 'deleteByJalan'])->name('delete-by-jalan');
        Route::get('export/excel', [NilaiKriteriaController::class, 'exportExcel'])->name('export-excel');
        Route::get('export/csv', [NilaiKriteriaController::class, 'exportCsv'])->name('export-csv');
        Route::get('export/pdf', [NilaiKriteriaController::class, 'exportPdf'])->name('export-pdf');
        Route::get('export/per-jalan-excel', [NilaiKriteriaController::class, 'exportPerJalanExcel'])->name('export-per-jalan-excel');
        Route::get('/get-pending-ids', [NilaiKriteriaController::class, 'getPendingIds'])->name('get-pending-ids');
    });
});


/*
|--------------------------------------------------------------------------
| ROUTE PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {

    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

    // Route Jalan
    Route::get('/jalan/get-koordinat', [JalanPController::class, 'getKoordinatFromAlamat'])->name('jalan.getKoordinat');
    Route::resource('jalan', JalanPController::class);

    // Route Nilai Kriteria
    // Cukup gunakan 'nilai-kriteria', otomatis akan menjadi petugas.nilai-kriteria.index dst.
    Route::resource('nilai-kriteria', NilaiKController::class)->except(['destroy','']);

    // Route Tambahan Nilai Kriteria
    Route::prefix('nilai-kriteria')->name('nilai-kriteria.')->group(function () {
        // Redirect index ke create
        Route::get('/', [NilaiKController::class, 'index'])->name('index');
        Route::get('/create', [NilaiKController::class, 'create'])->name('create');
        Route::post('/', [NilaiKController::class, 'store'])->name('store');
        Route::get('/{id}', [NilaiKController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [NilaiKController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NilaiKController::class, 'update'])->name('update');
        Route::delete('/{id}', [NilaiKController::class, 'destroy'])->name('destroy');
        
        // AJAX
        Route::get('/get-by-jalan', [NilaiKController::class, 'getNilaiByJalan'])->name('get-by-jalan');
        Route::get('/cek-status', [NilaiKController::class, 'cekStatusValidasi'])->name('cek-status');
        
        // RIWAYAT
        Route::get('/riwayat/nilai', [NilaiKController::class, 'riwayat'])->name('riwayat');
    });
    // Route Panduan
    Route::prefix('panduan')->name('panduan.')->group(function () {
        Route::get('/', [PanduanController::class, 'index'])->name('index');
        Route::get('/input-nilai', [PanduanController::class, 'inputNilai'])->name('input-nilai');
        Route::get('/manajemen-jalan', [PanduanController::class, 'manajemenJalan'])->name('manajemen-jalan');
        Route::get('/riwayat-penilaian', [PanduanController::class, 'riwayatPenilaian'])->name('riwayat-penilaian');
        Route::get('/faq', [PanduanController::class, 'faq'])->name('faq');
    });
    Route::prefix('profil')->name('petugas.profil.')->group(function () {
        Route::get('/', [ProfilController::class, 'index'])->name('index');
        Route::get('/edit', [ProfilController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfilController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfilController::class, 'changePasswordForm'])->name('change-password');
        Route::post('/update-password', [ProfilController::class, 'updatePassword'])->name('update-password');
        Route::delete('/remove-foto', [ProfilController::class, 'removeFoto'])->name('remove-foto');
    });
});
