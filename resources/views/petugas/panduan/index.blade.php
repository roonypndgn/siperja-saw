@extends('layouts.petugas')

@section('title', 'Panduan Penggunaan - Petugas')
@section('page-title', 'Panduan Penggunaan')
@section('page-subtitle', 'Pelajari cara menggunakan sistem prioritas perbaikan jalan')

@section('content')
<div class="panduan-container">
    
    <!-- Header -->
    <div class="welcome-card" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 40px; margin-bottom: 30px; color: white;">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="width: 80px; height: 80px; background: #F9A826; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-book-open" style="font-size: 40px; color: #1A2A3A;"></i>
            </div>
            <div>
                <h1 style="font-size: 28px; margin-bottom: 8px;">Panduan Penggunaan Sistem</h1>
                <p style="color: #8BA3BC; margin: 0;">Sistem Prioritas Perbaikan Jalan - Dinas PUPR</p>
            </div>
        </div>
    </div>
    
    <!-- Grid Menu Panduan -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 30px;">
        
        <!-- Kartu 1: Input Nilai Kriteria -->
        <div class="guide-card" onclick="window.location='{{ route('petugas.panduan.input-nilai') }}'" style="cursor: pointer;">
            <div class="guide-icon" style="background: #FEF3C7;">
                <i class="fas fa-edit" style="color: #F9A826;"></i>
            </div>
            <h3>Input Nilai Kriteria</h3>
            <p>Panduan lengkap cara menginput nilai penilaian untuk setiap kriteria pada jalan yang dipilih.</p>
            <div class="guide-link">Pelajari →</div>
        </div>
        
        <!-- Kartu 2: Manajemen Data Jalan -->
        <div class="guide-card" onclick="window.location='{{ route('petugas.panduan.manajemen-jalan') }}'" style="cursor: pointer;">
            <div class="guide-icon" style="background: #D1FAE5;">
                <i class="fas fa-road" style="color: #10B981;"></i>
            </div>
            <h3>Manajemen Data Jalan</h3>
            <p>Panduan mengelola data jalan: menambah, mengedit, hingga menghapus data jalan.</p>
            <div class="guide-link">Pelajari →</div>
        </div>
        
        <!-- Kartu 3: Riwayat Penilaian -->
        <div class="guide-card" onclick="window.location='{{ route('petugas.panduan.riwayat-penilaian') }}'" style="cursor: pointer;">
            <div class="guide-icon" style="background: #E0F2FE;">
                <i class="fas fa-history" style="color: #0284C7;"></i>
            </div>
            <h3>Riwayat Penilaian</h3>
            <p>Panduan melihat dan memantau status validasi data yang telah diinput.</p>
            <div class="guide-link">Pelajari →</div>
        </div>
        
        <!-- Kartu 4: FAQ -->
        <div class="guide-card" onclick="window.location='{{ route('petugas.panduan.faq') }}'" style="cursor: pointer;">
            <div class="guide-icon" style="background: #EDE9FE;">
                <i class="fas fa-question-circle" style="color: #8B5CF6;"></i>
            </div>
            <h3>FAQ</h3>
            <p>Pertanyaan yang sering diajukan seputar penggunaan sistem.</p>
            <div class="guide-link">Pelajari →</div>
        </div>
    </div>
    
    <!-- Tips Cepat -->
    <div class="tips-card" style="background: #F8FAFC; border-radius: 16px; padding: 24px;">
        <h3 style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
            <i class="fas fa-lightbulb" style="color: #F9A826;"></i>
            Tips Cepat
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; background: #FEF3C7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="color: #10B981;"></i>
                </div>
                <span>Pastikan semua kriteria terisi sebelum menyimpan</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; background: #FEF3C7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="color: #F59E0B;"></i>
                </div>
                <span>Data akan divalidasi admin dalam 1x24 jam</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; background: #FEF3C7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-edit" style="color: #F9A826;"></i>
                </div>
                <span>Data yang ditolak dapat diedit dan dikirim ulang</span>
            </div>
        </div>
    </div>
</div>

<style>
    .guide-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
        border: 1px solid #E2E8F0;
        text-align: center;
    }
    
    .guide-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: #F9A826;
    }
    
    .guide-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    
    .guide-icon i {
        font-size: 28px;
    }
    
    .guide-card h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #1A2A3A;
    }
    
    .guide-card p {
        font-size: 14px;
        color: #6B7280;
        line-height: 1.5;
        margin-bottom: 16px;
    }
    
    .guide-link {
        color: #F9A826;
        font-weight: 600;
        font-size: 14px;
    }
    
    .tips-card h3 {
        font-size: 16px;
        font-weight: 700;
    }
</style>
@endsection