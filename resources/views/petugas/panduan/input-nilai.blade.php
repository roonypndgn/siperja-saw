@extends('layouts.petugas')

@section('title', 'Panduan Input Nilai - Petugas')
@section('page-title', 'Panduan Input Nilai Kriteria')
@section('page-subtitle', 'Langkah-langkah menginput nilai penilaian')

@section('content')
<div class="panduan-detail">
    
    <!-- Tombol Kembali -->
    <a href="{{ route('petugas.panduan.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Panduan
    </a>
    
    <!-- Header -->
    <div class="detail-header">
        <div class="detail-icon" style="background: #FEF3C7;">
            <i class="fas fa-edit" style="color: #F9A826;"></i>
        </div>
        <div>
            <h1>Panduan Input Nilai Kriteria</h1>
            <p>Cara menginput nilai penilaian untuk setiap kriteria pada jalan yang dipilih</p>
        </div>
    </div>
    
    <!-- Langkah-langkah -->
    <div class="steps-container">
        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Pilih Jalan dan Tahun Penilaian</h3>
                <p>Pilih jalan yang akan dinilai dari dropdown <strong>"Pilih Jalan"</strong>. Kemudian pilih tahun penilaian yang sesuai.</p>
                <div class="step-note">
                    <i class="fas fa-info-circle"></i> Hanya jalan yang <strong>belum divalidasi lengkap</strong> yang dapat dipilih.
                </div>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Isi Nilai Setiap Kriteria</h3>
                <p>Masukkan nilai untuk setiap kriteria sesuai dengan skala yang ditentukan. Perhatikan tipe kriteria:</p>
                <ul>
                    <li><span class="badge-benefit">Benefit</span> - Semakin besar nilai, semakin baik</li>
                    <li><span class="badge-cost">Cost</span> - Semakin kecil nilai, semakin baik</li>
                </ul>
                <div class="step-note">
                    <i class="fas fa-lightbulb"></i> Gunakan tombol <strong>Tab</strong> untuk berpindah antar input dengan cepat.
                </div>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Isi Catatan (Opsional)</h3>
                <p>Tambahkan catatan penting terkait penilaian, seperti kondisi lapangan, metode pengukuran, atau informasi lain yang relevan.</p>
            </div>
        </div>
        
        <div class="step-item">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Simpan Data</h3>
                <p>Klik tombol <strong>"Simpan Nilai"</strong> untuk menyimpan data. Data akan masuk ke antrian validasi admin.</p>
                <div class="step-note">
                    <i class="fas fa-clock"></i> Data akan divalidasi oleh admin dalam waktu maksimal 1x24 jam.
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ilustrasi -->
    <div class="illustration">
        <h3>📸 Preview Form Input</h3>
        <div class="preview-box">
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div style="flex: 1; background: #F8FAFC; padding: 16px; border-radius: 12px;">
                    <strong>Pilih Jalan:</strong> JL-001 - Jalan Merdeka
                </div>
                <div style="flex: 1; background: #F8FAFC; padding: 16px; border-radius: 12px;">
                    <strong>Tahun:</strong> 2024
                </div>
            </div>
            <table style="width: 100%; margin-top: 16px; border-collapse: collapse;">
                <tr style="background: #E2E8F0;">
                    <th style="padding: 10px;">Kriteria</th>
                    <th style="padding: 10px;">Nilai</th>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #E2E8F0;">Tingkat Kerusakan</td>
                    <td style="padding: 10px; border-bottom: 1px solid #E2E8F0;"><input type="text" placeholder="85" style="padding: 8px; width: 100px;"></td>
                </tr>
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #E2E8F0;">Volume Kendaraan</td>
                    <td style="padding: 10px; border-bottom: 1px solid #E2E8F0;"><input type="text" placeholder="70" style="padding: 8px; width: 100px;"></td>
                </tr>
            </table>
            <div style="margin-top: 16px; text-align: right;">
                <button style="background: #F9A826; border: none; padding: 10px 20px; border-radius: 8px;">💾 Simpan Nilai</button>
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
    
    .btn-back:hover {
        text-decoration: underline;
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
    
    .steps-container {
        margin-bottom: 32px;
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
    
    .badge-benefit {
        background: #D1FAE5;
        color: #059669;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-cost {
        background: #FEE2E2;
        color: #DC2626;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .illustration {
        background: white;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #E2E8F0;
    }
    
    .illustration h3 {
        margin-bottom: 16px;
    }
    
    .preview-box {
        background: #F8FAFC;
        padding: 20px;
        border-radius: 12px;
    }
</style>
@endsection