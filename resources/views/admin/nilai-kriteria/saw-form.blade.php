@extends('layouts.admin')

@section('title', 'Perhitungan SAW - Admin')
@section('page-title', 'Perhitungan SAW')
@section('page-subtitle', 'Proses perhitungan prioritas perbaikan jalan menggunakan metode SAW')

@section('content')
<div class="saw-container" style="max-width: 800px; margin: 0 auto;">
    
    <!-- Header -->
    <div class="saw-header" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; text-align: center;">
        <div style="width: 70px; height: 70px; background: #F9A826; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <i class="fas fa-calculator" style="font-size: 32px; color: #1A2A3A;"></i>
        </div>
        <h2 style="color: white; margin: 0; font-size: 24px;">Perhitungan SAW</h2>
        <p style="color: #8BA3BC; margin: 8px 0 0;">Simple Additive Weighting untuk prioritas perbaikan jalan</p>
    </div>
    
    <!-- Alert Informasi -->
    <div class="alert-info" style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-info-circle" style="color: #F59E0B; font-size: 20px;"></i>
            <div style="font-size: 14px; color: #92400E;">
                <strong>Informasi:</strong> Pastikan semua data nilai kriteria sudah <strong>divalidasi</strong> sebelum melakukan perhitungan SAW.
            </div>
        </div>
    </div>
    
    <!-- Statistik Data -->
    <div class="stats-card" style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #E2E8F0; margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">
            <i class="fas fa-chart-simple" style="color: #F9A826;"></i> Statistik Data
        </h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <div style="text-align: center;">
                <div style="font-size: 13px; color: #6B7280;">Total Jalan Aktif</div>
                <div style="font-size: 28px; font-weight: 800; color: #1A2A3A;">{{ $statistik['total_jalan'] }}</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 13px; color: #6B7280;">Sudah Dinilai & Valid</div>
                <div style="font-size: 28px; font-weight: 800; color: #10B981;">{{ $statistik['sudah_dinilai'] }}</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 13px; color: #6B7280;">Kriteria Aktif</div>
                <div style="font-size: 28px; font-weight: 800; color: #F9A826;">{{ $statistik['kriteria_aktif'] }}</div>
            </div>
        </div>
        
        <div class="progress-status" style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 13px;">Progress Kelengkapan Data</span>
                <span style="font-size: 13px; font-weight: 600;">
                    {{ $statistik['sudah_dinilai'] }}/{{ $statistik['total_jalan'] }} jalan
                    ({{ $statistik['total_jalan'] > 0 ? round(($statistik['sudah_dinilai'] / $statistik['total_jalan']) * 100) : 0 }}%)
                </span>
            </div>
            <div style="background: #E2E8F0; border-radius: 10px; height: 8px; overflow: hidden;">
                <div style="width: {{ $statistik['total_jalan'] > 0 ? ($statistik['sudah_dinilai'] / $statistik['total_jalan']) * 100 : 0 }}%; background: #F9A826; height: 100%; border-radius: 10px;"></div>
            </div>
        </div>
    </div>
    
    <!-- Form Perhitungan -->
    <div class="form-card" style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #E2E8F0;">
        <form method="POST" action="{{ route('admin.saw.process') }}">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            
            <!-- Pilih Tahun -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label">
                    <i class="fas fa-calendar" style="color: #F9A826;"></i> Tahun Penilaian
                </label>
                <select name="tahun" class="form-control" style="width: 200px;">
                    @for($year = date('Y'); $year >= date('Y')-5; $year--)
                        <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
            </div>
            
            <!-- Alert jika sudah ada hasil -->
            @if($existingResult)
            <div class="alert-warning" style="background: #FEF3C7; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-exclamation-triangle" style="color: #F59E0B; font-size: 20px;"></i>
                    <div style="font-size: 14px; color: #92400E;">
                        <strong>Perhatian!</strong> Sudah ada hasil perhitungan untuk tahun {{ $tahun }}. 
                        Menghitung ulang akan <strong>menimpa</strong> hasil yang sudah ada.
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Alert jika data belum lengkap -->
            @if(!$statistik['siap_dihitung'])
            <div class="alert-danger" style="background: #FEE2E2; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-times-circle" style="color: #EF4444; font-size: 20px;"></i>
                    <div style="font-size: 14px; color: #991B1B;">
                        <strong>Data Belum Siap!</strong> 
                        Masih ada {{ $statistik['total_jalan'] - $statistik['sudah_dinilai'] }} jalan yang belum memiliki nilai lengkap dan tervalidasi.
                        Silakan lengkapi data terlebih dahulu sebelum melakukan perhitungan SAW.
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Tombol Proses -->
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-process" {{ !$statistik['siap_dihitung'] ? 'disabled' : '' }}
                        style="background: #F9A826; color: #1A2A3A; padding: 14px 32px; border-radius: 12px; border: none; font-weight: 700; font-size: 16px; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 10px;">
                    <i class="fas fa-play"></i> Proses Perhitungan SAW
                </button>
            </div>
        </form>
    </div>
    
    <!-- Penjelasan Metode SAW -->
    <div class="method-explanation" style="margin-top: 30px; background: #F8FAFC; border-radius: 16px; padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-lightbulb" style="color: #F9A826;"></i>
            Langkah-langkah Perhitungan SAW
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div style="display: flex; gap: 12px;">
                <div style="width: 32px; height: 32px; background: #F9A826; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1A2A3A;">1</div>
                <div>
                    <strong>Normalisasi Matriks</strong>
                    <p style="font-size: 12px; color: #6B7280; margin-top: 4px;">Setiap nilai dibagi dengan nilai maksimum (benefit) atau nilai minimum dibagi nilai (cost).</p>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <div style="width: 32px; height: 32px; background: #F9A826; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1A2A3A;">2</div>
                <div>
                    <strong>Perkalian dengan Bobot</strong>
                    <p style="font-size: 12px; color: #6B7280; margin-top: 4px;">Setiap nilai ternormalisasi dikalikan dengan bobot kriteria masing-masing.</p>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <div style="width: 32px; height: 32px; background: #F9A826; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1A2A3A;">3</div>
                <div>
                    <strong>Penjumlahan Skor</strong>
                    <p style="font-size: 12px; color: #6B7280; margin-top: 4px;">Menjumlahkan semua hasil perkalian untuk setiap alternatif (jalan).</p>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <div style="width: 32px; height: 32px; background: #F9A826; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1A2A3A;">4</div>
                <div>
                    <strong>Perankingan</strong>
                    <p style="font-size: 12px; color: #6B7280; margin-top: 4px;">Mengurutkan skor dari tertinggi ke terendah untuk menentukan prioritas.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control {
        padding: 12px 16px;
        border: 2px solid #E2E8F0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #F9A826;
        box-shadow: 0 0 0 3px rgba(249, 168, 38, 0.1);
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
    }
    
    .btn-process {
        transition: all 0.3s;
    }
    
    .btn-process:hover:not(:disabled) {
        background: #E8912A !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3);
    }
    
    .btn-process:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>
@endsection