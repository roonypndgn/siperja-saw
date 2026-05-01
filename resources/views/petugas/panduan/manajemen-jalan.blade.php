@extends('layouts.petugas')

@section('title', 'Panduan Manajemen Jalan - Petugas')
@section('page-title', 'Panduan Manajemen Data Jalan')
@section('page-subtitle', 'Langkah-langkah mengelola data jalan')

@section('content')
<div class="panduan-detail">
    
    <a href="{{ route('petugas.panduan.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Panduan
    </a>
    
    <div class="detail-header">
        <div class="detail-icon" style="background: #D1FAE5;">
            <i class="fas fa-road" style="color: #10B981;"></i>
        </div>
        <div>
            <h1>Panduan Manajemen Data Jalan</h1>
            <p>Cara menambah, mengedit, dan menghapus data jalan</p>
        </div>
    </div>
    
    <div class="steps-container">
        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Tambah Data Jalan Baru</h3>
                <p>Klik tombol <strong>"Tambah Jalan"</strong> pada halaman Data Jalan. Isi form dengan lengkap:</p>
                <ul>
                    <li><strong>Kode Jalan</strong> - Otomatis terisi (JL-001, JL-002, dst)</li>
                    <li><strong>Nama Jalan</strong> - Nama lengkap jalan</li>
                    <li><strong>Lokasi</strong> - Lokasi/kelurahan jalan</li>
                    <li><strong>Panjang (m)</strong> - Panjang jalan dalam meter</li>
                    <li><strong>Deskripsi</strong> - Deskripsi kondisi jalan (opsional)</li>
                    <li><strong>Koordinat</strong> - Latitude dan Longitude (opsional)</li>
                </ul>
                <div class="step-note">
                    <i class="fas fa-map-marker-alt"></i> Koordinat dapat diisi otomatis dengan tombol <strong>"Gunakan Lokasi Saya"</strong>.
                </div>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Edit Data Jalan</h3>
                <p>Klik tombol <strong>"Edit"</strong> pada baris data yang ingin diubah. Perbarui informasi yang diperlukan lalu klik <strong>"Update Data"</strong>.</p>
                <div class="step-note">
                    <i class="fas fa-info-circle"></i> Kode jalan <strong>tidak dapat diubah</strong> setelah disimpan.
                </div>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Hapus Data Jalan</h3>
                <p>Klik tombol <strong>"Hapus"</strong> pada baris data yang ingin dihapus. Konfirmasi penghapusan pada dialog yang muncul.</p>
                <div class="step-note">
                    <i class="fas fa-exclamation-triangle"></i> Jalan yang sudah memiliki <strong>nilai kriteria</strong> tidak dapat dihapus.
                </div>
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
    
    .detail-icon i {
        font-size: 28px;
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
</style>
@endsection