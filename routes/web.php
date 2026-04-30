<?php

use App\Http\Controllers\Admin\JalanController;
use App\Http\Controllers\Admin\KriteriaController;
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
    Route::get('/kriteria/cek-kode', [KriteriaController::class, 'cekKode'])->name('admin.kriteria.cekKode');
    Route::get('/kriteria/cek-urutan', [KriteriaController::class, 'cekUrutan'])->name('admin.kriteria.cekUrutan');
    Route::post('/kriteria/update-bobot', [KriteriaController::class, 'updateBobot'])->name('admin.kriteria.updateBobot');
    Route::post('/kriteria/reorder', [KriteriaController::class, 'reorder'])->name('admin.kriteria.reorder');
    Route::post('/kriteria/{id}/toggle-status', [KriteriaController::class, 'toggleStatus'])->name('admin.kriteria.toggleStatus');
});


/*
|--------------------------------------------------------------------------
| ROUTE PETUGAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/jalan/get-koordinat', [JalanPController::class, 'getKoordinatFromAlamat'])->name('petugas.jalan.getKoordinat');
    // Prefix 'jalan' untuk URL, dan Name 'jalan.' untuk route
    Route::prefix('jalan')->name('jalan.')->group(function () {
        // Cukup tulis seperti ini, otomatis jadi petugas.jalan.index, petugas.jalan.create, dst.
        Route::resource('/', JalanPController::class)->parameters(['' => 'jalan']);
    });
});
