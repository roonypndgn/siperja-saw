@extends('layouts.petugas')

@section('title', 'Panduan Riwayat Penilaian - Petugas')
@section('page-title', 'Panduan Riwayat Penilaian')
@section('page-subtitle', 'Memantau status validasi data yang telah diinput')

@section('content')
<div class="panduan-detail">
    
    <a href="{{ route('petugas.panduan.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Panduan
    </a>
    
    <div class="detail-header">
        <div class="detail-icon" style="background: #E0F2FE;">
            <i class="fas fa-history" style="color: #0284C7;"></i>
        </div>
        <div>
            <h1>Panduan Riwayat Penilaian</h1>
            <p>Memantau status validasi data yang telah diinput</p>
        </div>
    </div>
    
    <div class="steps-container">
        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Melihat Riwayat Penilaian</h3>
                <p>Buka menu <strong>"Riwayat Penilaian"</strong> untuk melihat semua data nilai yang sudah Anda input. Data ditampilkan per kriteria.</p>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Memahami Status Validasi</h3>
                <p>Setiap data memiliki status yang menunjukkan proses validasi oleh admin:</p>
                <ul>
                    <li><span class="badge-pending">🕒 Pending</span> - Menunggu validasi admin</li>
                    <li><span class="badge-valid">✅ Divalidasi</span> - Data sudah disetujui admin</li>
                    <li><span class="badge-rejected">❌ Ditolak</span> - Data ditolak, perlu perbaikan</li>
                </ul>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Filter Data</h3>
                <p>Gunakan filter <strong>Tahun</strong> dan <strong>Status</strong> untuk menyaring data yang ingin dilihat.</p>
                <div class="step-note">
                    <i class="fas fa-filter"></i> Filter membantu Anda fokus pada data yang masih pending atau ditolak.
                </div>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Edit Data yang Ditolak</h3>
                <p>Jika data ditolak, klik tombol <strong>"Edit"</strong> untuk memperbaiki nilai. Setelah diperbaiki, simpan ulang untuk dikirim ke admin.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .panduan-detail {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        color: #F9A826;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 24px;
        cursor: pointer;
    }
    
    .detail-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 32px;
        padding-bottom: 20px;
        border-bottom: 2px solid #E2E8F0;
    }
    
    .detail-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .detail-header h1 {
        font-size: 24px;
        color: #1A2A3A;
        margin-bottom: 4px;
    }
    
    .detail-header p {
        color: #6B7280;
    }
    
    .step-item {
        display: flex;
        gap: 20px;
        margin-bottom: 24px;
        padding: 20px;
        background: #F8FAFC;
        border-radius: 12px;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        background: #F9A826;
        color: #1A2A3A;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .step-content h3 {
        font-size: 18px;
        margin-bottom: 8px;
        color: #1A2A3A;
    }
    
    .step-content p {
        color: #6B7280;
        margin-bottom: 8px;
    }
    
    .step-content ul {
        margin-left: 20px;
        color: #6B7280;
    }
    
    .step-note {
        background: #FEF3E0;
        padding: 10px 12px;
        border-radius: 8px;
        margin-top: 10px;
        font-size: 13px;
        color: #92400E;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-valid {
        background: #D1FAE5;
        color: #059669;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-rejected {
        background: #FEE2E2;
        color: #DC2626;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endsection