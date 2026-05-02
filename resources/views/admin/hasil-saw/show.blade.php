@extends('layouts.admin')

@section('title', 'Detail Hasil SAW - Admin')
@section('page-title', 'Detail Perhitungan SAW')
@section('page-subtitle', 'Rincian perhitungan prioritas perbaikan jalan')

@section('content')
<div class="detail-container" style="max-width: 1000px; margin: 0 auto;">
    
    <!-- Header -->
    <div class="detail-header" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="background: #F9A826; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-trophy" style="font-size: 32px; color: #1A2A3A;"></i>
            </div>
            <div>
                <div style="color: #F9A826; margin-bottom: 4px;">Peringkat #{{ $hasil->peringkat }}</div>
                <h2 style="color: white; margin: 0;">{{ $hasil->jalan->nama ?? '-' }}</h2>
                <p style="color: #8BA3BC; margin: 8px 0 0;">{{ $hasil->jalan->lokasi ?? '-' }} | Kode: {{ $hasil->jalan->kode ?? '-' }}</p>
            </div>
            <div style="margin-left: auto; text-align: center;">
                <div style="background: rgba(255,255,255,0.1); border-radius: 16px; padding: 12px 24px;">
                    <div style="font-size: 12px; color: #8BA3BC;">Skor Akhir (V)</div>
                    <div style="font-size: 36px; font-weight: 800; color: #F9A826;">{{ number_format($hasil->skor_akhir, 4) }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabel Perhitungan Detail -->
    <div class="calculation-card" style="background: white; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden; margin-bottom: 30px;">
        <div style="background: #F8FAFC; padding: 16px 20px; border-bottom: 1px solid #E2E8F0;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 700;">
                <i class="fas fa-chart-line" style="color: #F9A826;"></i> Detail Perhitungan
            </h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #F1F5F9;">
                        <th style="padding: 12px; text-align: left;">Kriteria</th>
                        <th style="padding: 12px; text-align: center;">Tipe</th>
                        <th style="padding: 12px; text-align: center;">Bobot (W)</th>
                        <th style="padding: 12px; text-align: center;">Nilai Asli (X)</th>
                        <th style="padding: 12px; text-align: center;">Normalisasi (R)</th>
                        <th style="padding: 12px; text-align: center;">Kontribusi (W×R)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalKontribusi = 0; @endphp
                    @foreach($detailPerhitungan as $item)
                    @php $totalKontribusi += $item['kontribusi']; @endphp
                    <tr style="border-bottom: 1px solid #E2E8F0;">
                        <td style="padding: 12px;">
                            <strong>{{ $item['kriteria_nama'] }}</strong>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            @if(strpos($item['kriteria_nama'], 'Biaya') !== false || $item['kriteria_nama'] == 'Cost')
                                <span class="badge-cost">Cost</span>
                            @else
                                <span class="badge-benefit">Benefit</span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            {{ number_format($item['bobot'] * 100, 0) }}%
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            {{ number_format($item['nilai_asli'], 2) }}
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            {{ number_format($item['nilai_normalisasi'], 6) }}
                        </td>
                        <td style="padding: 12px; text-align: center; background: #FEF3E0; font-weight: 600;">
                            {{ number_format($item['kontribusi'], 6) }}
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background: #F8FAFC; font-weight: 700;">
                        <td colspan="5" style="padding: 12px; text-align: right;">TOTAL SKOR AKHIR (V)</td>
                        <td style="padding: 12px; text-align: center; background: #F9A826; color: #1A2A3A; font-size: 18px;">
                            {{ number_format($totalKontribusi, 6) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Informasi Perhitungan -->
    <div class="info-card" style="background: #F8FAFC; border-radius: 16px; padding: 20px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">
            <i class="fas fa-info-circle" style="color: #F9A826;"></i> Informasi Perhitungan
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <div>
                <div style="font-size: 12px; color: #6B7280;">Tanggal Perhitungan</div>
                <div style="font-weight: 600;">{{ $hasil->tanggal_perhitungan->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: #6B7280;">Dihitung Oleh</div>
                <div style="font-weight: 600;">{{ $hasil->dihitungOleh->name ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size: 12px; color: #6B7280;">Tahun Perhitungan</div>
                <div style="font-weight: 600;">{{ $hasil->tahun_perhitungan }}</div>
            </div>
        </div>
    </div>
    
    <!-- Tombol Kembali -->
    <div style="margin-top: 24px; text-align: center;">
        <a href="{{ route('admin.hasil-saw.index', ['tahun' => $hasil->tahun_perhitungan]) }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Ranking
        </a>
    </div>
</div>

<style>
    .badge-benefit {
        background: #D1FAE5;
        color: #059669;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-cost {
        background: #FEE2E2;
        color: #DC2626;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #E8EDF2;
        color: #1A2A3A;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-back:hover {
        background: #D1D9E6;
        transform: translateY(-2px);
    }
</style>
@endsection