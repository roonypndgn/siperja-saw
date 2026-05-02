@extends('layouts.admin')

@section('title', 'Hasil Perhitungan SAW - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Hasil Perhitungan SAW')
@section('page-subtitle', 'Ranking prioritas perbaikan jalan berdasarkan metode SAW')

@section('content')
<!-- Filter Tahun -->
<div class="filter-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.saw.form') }}" class="btn-primary">
            <i class="fas fa-calculator"></i> Hitung Ulang SAW
        </a>
        <a href="{{ route('admin.hasil-saw.exports-hasil-saw-pdf', $tahun) }}" class="btn-secondary">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
    
    <form method="GET" action="{{ route('admin.hasil-saw.index') }}" style="display: flex; gap: 12px;">
        <select name="tahun" class="filter-select" onchange="this.form.submit()">
            @foreach($tahunList as $thn)
                <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
            @endforeach
        </select>
    </form>
</div>

<!-- Statistik Perhitungan -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card" style="background: linear-gradient(135deg, #1A2A3A, #2A3F54); border-radius: 16px; padding: 20px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; opacity: 0.8;">Total Jalan Dinilai</div>
                <div style="font-size: 32px; font-weight: 800;">{{ $statistik['total_jalan'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-road" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #F9A826;">
        <div>
            <div style="font-size: 13px; color: #6B7280;">Skor Tertinggi</div>
            <div style="font-size: 28px; font-weight: 800; color: #10B981;">{{ number_format($statistik['skor_tertinggi'], 4) }}</div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #3B82F6;">
        <div>
            <div style="font-size: 13px; color: #6B7280;">Skor Terendah</div>
            <div style="font-size: 28px; font-weight: 800; color: #EF4444;">{{ number_format($statistik['skor_terendah'], 4) }}</div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #8B5CF6;">
        <div>
            <div style="font-size: 13px; color: #6B7280;">Rata-rata Skor</div>
            <div style="font-size: 28px; font-weight: 800; color: #8B5CF6;">{{ number_format($statistik['rata_rata_skor'], 4) }}</div>
        </div>
    </div>
</div>

<!-- Tabel Ranking -->
<div class="table-container" style="background: white; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0;">
                    <th style="padding: 16px; text-align: center; width: 8%;">Peringkat</th>
                    <th style="padding: 16px; text-align: left; width: 12%;">Kode</th>
                    <th style="padding: 16px; text-align: left; width: 25%;">Nama Jalan</th>
                    <th style="padding: 16px; text-align: left; width: 20%;">Lokasi</th>
                    <th style="padding: 16px; text-align: center; width: 15%;">Skor Akhir (V)</th>
                    <th style="padding: 16px; text-align: center; width: 20%;">Tingkat Prioritas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasil as $index => $item)
                @php
                    $priorityClass = '';
                    $priorityLabel = '';
                    $priorityIcon = '';
                    $priorityColor = '';
                    
                    if ($item->peringkat == 1) {
                        $priorityClass = 'priority-1';
                        $priorityLabel = 'SANGAT PRIORITAS';
                        $priorityIcon = '🔴';
                        $priorityColor = '#EF4444';
                    } elseif ($item->peringkat <= 3) {
                        $priorityClass = 'priority-2';
                        $priorityLabel = 'PRIORITAS TINGGI';
                        $priorityIcon = '🟠';
                        $priorityColor = '#F59E0B';
                    } elseif ($item->peringkat <= 5) {
                        $priorityClass = 'priority-3';
                        $priorityLabel = 'PRIORITAS';
                        $priorityIcon = '🟢';
                        $priorityColor = '#10B981';
                    } else {
                        $priorityClass = 'priority-4';
                        $priorityLabel = 'PRIORITAS RENDAH';
                        $priorityIcon = '⚪';
                        $priorityColor = '#6B7280';
                    }
                    
                    // Hitung persentase skor terhadap skor tertinggi
                    $scorePercentage = $statistik['skor_tertinggi'] > 0 
                        ? ($item->skor_akhir / $statistik['skor_tertinggi']) * 100 
                        : 0;
                @endphp
                <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" 
                    onmouseover="this.style.background='#FEF3E0'" 
                    onmouseout="this.style.background='white'"
                    class="{{ $priorityClass }}">
                    <td style="padding: 16px; text-align: center;">
                        <div style="width: 40px; height: 40px; background: {{ $priorityColor }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-weight: 800; font-size: 18px;">
                            {{ $item->peringkat }}
                        </div>
                    </td>
                    <td style="padding: 16px;">
                        <span class="badge-kode">{{ $item->jalan->kode ?? '-' }}</span>
                    </td>
                    <td style="padding: 16px;">
                        <strong>{{ $item->jalan->nama ?? '-' }}</strong>
                    </td>
                    <td style="padding: 16px;">
                        <i class="fas fa-map-marker-alt" style="color: #F9A826; margin-right: 6px;"></i>
                        {{ $item->jalan->lokasi ?? '-' }}
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        <div style="font-weight: 800; font-size: 20px; color: #F9A826;">{{ number_format($item->skor_akhir, 4) }}</div>
                        <div class="score-bar" style="margin-top: 8px; background: #E2E8F0; border-radius: 10px; height: 6px; width: 100%; overflow: hidden;">
                            <div style="width: {{ $scorePercentage }}%; background: #F9A826; height: 100%; border-radius: 10px;"></div>
                        </div>
                        <small style="color: #6B7280;">{{ number_format($scorePercentage, 1) }}% dari skor tertinggi</small>
                    </td>
                    <td style="padding: 16px; text-align: center;">
                        <span style="background: {{ $priorityColor }}20; color: {{ $priorityColor }}; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 12px;">
                            {{ $priorityIcon }} {{ $priorityLabel }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 60px; text-align: center; color: #6B7280;">
                        <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p>Belum ada hasil perhitungan SAW untuk tahun {{ $tahun }}</p>
                        <a href="{{ route('admin.saw.form') }}" class="btn-primary" style="display: inline-block; margin-top: 12px;">
                            <i class="fas fa-calculator"></i> Lakukan Perhitungan SAW
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($hasil->hasPages())
    <div style="padding: 20px; border-top: 1px solid #E2E8F0;">
        {{ $hasil->links() }}
    </div>
    @endif
</div>

<!-- Informasi Metode SAW -->
<div class="info-saw" style="margin-top: 30px; background: #F8FAFC; border-radius: 16px; padding: 20px;">
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-info-circle" style="color: #F9A826;"></i>
        Tentang Metode SAW (Simple Additive Weighting)
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <div>
            <h4 style="font-weight: 600; margin-bottom: 8px;">📐 Rumus Normalisasi</h4>
            <p style="font-size: 13px; color: #6B7280;">
                <strong>Benefit:</strong> Rij = Xij / Max(Xj)<br>
                <strong>Cost:</strong> Rij = Min(Xj) / Xij
            </p>
        </div>
        <div>
            <h4 style="font-weight: 600; margin-bottom: 8px;">⚖️ Rumus Skor Akhir</h4>
            <p style="font-size: 13px; color: #6B7280;">
                Vi = Σ (Wj × Rij)<br>
                <small>Dimana Vi = skor akhir, Wj = bobot kriteria, Rij = nilai ternormalisasi</small>
            </p>
        </div>
        <div>
            <h4 style="font-weight: 600; margin-bottom: 8px;">📊 Interpretasi Skor</h4>
            <p style="font-size: 13px; color: #6B7280;">
                Semakin tinggi skor akhir (mendekati 1), semakin prioritas jalan tersebut untuk diperbaiki.
            </p>
        </div>
    </div>
</div>

<style>
    .btn-primary {
        background: #F9A826;
        color: #1A2A3A;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary:hover {
        background: #E8912A;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3);
    }
    
    .btn-secondary {
        background: #E8EDF2;
        color: #1A2A3A;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }
    
    .btn-secondary:hover {
        background: #D1D9E6;
    }
    
    .filter-select {
        padding: 10px 16px;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        background: white;
        font-size: 14px;
        cursor: pointer;
    }
    
    .badge-kode {
        background: #E8EDF2;
        color: #1A2A3A;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    
    /* Priority row styles */
    .priority-1 td {
        background: rgba(239, 68, 68, 0.05);
    }
    
    .priority-2 td {
        background: rgba(245, 158, 11, 0.05);
    }
    
    .priority-3 td {
        background: rgba(16, 185, 129, 0.05);
    }
    
    .info-saw {
        border-left: 4px solid #F9A826;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection