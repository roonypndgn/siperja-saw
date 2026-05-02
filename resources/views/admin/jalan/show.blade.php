@extends('layouts.admin')

@section('title', 'Detail Jalan - ' . $jalan->nama)
@section('page-title', 'Detail Data Jalan')
@section('page-subtitle', 'Informasi lengkap data jalan dan riwayat penilaian')

@section('content')
<div class="detail-container">
    <!-- Tombol Aksi -->
    <div class="action-bar">
        <a href="{{ route('admin.jalan.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div class="action-buttons">
            <a href="{{ route('admin.jalan.edit', $jalan->id) }}" class="btn-edit">
                <i class="fas fa-edit"></i> Edit Jalan
            </a>
            <button type="button" class="btn-delete" onclick="confirmDelete({{ $jalan->id }}, '{{ $jalan->nama }}')">
                <i class="fas fa-trash"></i> Hapus
            </button>
            @if(!$jalan->is_active)
            <button type="button" class="btn-activate" onclick="toggleStatus({{ $jalan->id }}, '{{ $jalan->nama }}', false)">
                <i class="fas fa-check-circle"></i> Aktifkan
            </button>
            @endif
        </div>
    </div>
    
    <!-- Form untuk delete & toggle -->
    <form id="delete-form-{{ $jalan->id }}" action="{{ route('admin.jalan.destroy', $jalan->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <form id="toggle-form-{{ $jalan->id }}" action="{{ route('admin.jalan.toggle-status', $jalan->id) }}" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
    </form>
    
    <div class="detail-grid">
        <!-- Kolom Kiri: Informasi Jalan -->
        <div class="detail-card">
            <div class="card-header">
                <div class="card-header-icon">
                    <i class="fas fa-road"></i>
                </div>
                <div class="card-header-title">
                    <h3>Informasi Jalan</h3>
                    <p>Data dasar dan identitas jalan</p>
                </div>
                <div class="status-badge">
                    @if($jalan->is_active)
                        <span class="status-active"><i class="fas fa-check-circle"></i> Aktif</span>
                    @else
                        <span class="status-inactive"><i class="fas fa-times-circle"></i> Nonaktif</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-barcode"></i> Kode Jalan
                    </div>
                    <div class="info-value">
                        <span class="badge-kode">{{ $jalan->kode }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-signature"></i> Nama Jalan
                    </div>
                    <div class="info-value">
                        <strong>{{ $jalan->nama }}</strong>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-map-marker-alt"></i> Lokasi
                    </div>
                    <div class="info-value">
                        <i class="fas fa-location-dot" style="color: var(--secondary); margin-right: 6px;"></i>
                        {{ $jalan->lokasi }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-ruler"></i> Panjang Jalan
                    </div>
                    <div class="info-value">
                        {{ number_format($jalan->panjang, 0, ',', '.') }} <span class="info-unit">meter</span>
                    </div>
                </div>
                @if($jalan->deskripsi)
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-align-left"></i> Deskripsi
                    </div>
                    <div class="info-value">
                        {{ $jalan->deskripsi }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Kolom Kanan: Koordinat & Informasi Sistem -->
        <div class="detail-card">
            <div class="card-header">
                <div class="card-header-icon">
                    <i class="fas fa-map-pin"></i>
                </div>
                <div class="card-header-title">
                    <h3>Lokasi & Sistem</h3>
                    <p>Koordinat geografis dan informasi audit</p>
                </div>
            </div>
            <div class="card-body">
                @if($jalan->latitude && $jalan->longitude)
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-latitude"></i> Latitude
                    </div>
                    <div class="info-value">
                        <code>{{ $jalan->latitude }}</code>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-longitude"></i> Longitude
                    </div>
                    <div class="info-value">
                        <code>{{ $jalan->longitude }}</code>
                    </div>
                </div>
                <div class="map-link">
                    <a href="https://www.google.com/maps?q={{ $jalan->latitude }},{{ $jalan->longitude }}" target="_blank" class="btn-map">
                        <i class="fas fa-map-marked-alt"></i> Buka di Google Maps
                    </a>
                </div>
                @else
                <div class="info-empty">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Belum ada data koordinat</p>
                    <small>Edit jalan untuk menambahkan koordinat</small>
                </div>
                @endif
                
                <div class="info-divider"></div>
                
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-user-plus"></i> Dibuat Oleh
                    </div>
                    <div class="info-value">
                        {{ $jalan->createdBy->name ?? '-' }}
                        <span class="info-date">{{ $jalan->created_at->timezone('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }}</span>
                    </div>
                </div>
                @if($jalan->updated_by)
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-user-edit"></i> Terakhir Diubah
                    </div>
                    <div class="info-value">
                        {{ $jalan->updatedBy->name ?? '-' }}
                        <span class="info-date">{{ $jalan->updated_at->timezone('Asia/Jakarta')->translatedFormat('d/m/Y H:i') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Nilai Kriteria -->
    <div class="detail-card full-width">
        <div class="card-header">
            <div class="card-header-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-header-title">
                <h3>Nilai Kriteria</h3>
                <p>Penilaian kondisi jalan berdasarkan kriteria yang ditentukan</p>
            </div>
            <div class="card-header-action">
                <span class="year-badge">{{ date('Y') }}</span>
            </div>
        </div>
        <div class="card-body">
            @if($nilaiKriteria && count($nilaiKriteria) > 0)
            <div class="criteria-grid">
                @foreach($nilaiKriteria as $nilai)
                <div class="criteria-item">
                    <div class="criteria-header">
                        <div class="criteria-name">
                            <i class="fas fa-sliders-h"></i>
                            {{ $nilai->kriteria->nama }}
                        </div>
                        <div class="criteria-badge {{ $nilai->kriteria->tipe }}">
                            {{ $nilai->kriteria->tipe == 'benefit' ? 'Benefit ↑' : 'Cost ↓' }}
                        </div>
                    </div>
                    <div class="criteria-value">
                        <div class="value-number">{{ number_format($nilai->nilai, 0, ',', '.') }}</div>
                        <div class="value-unit">{{ $nilai->kriteria->satuan ?? '-' }}</div>
                    </div>
                    @if($nilai->nilai_ternormalisasi)
                    <div class="criteria-normalized">
                        Nilai Normalisasi: {{ number_format($nilai->nilai_ternormalisasi, 4) }}
                    </div>
                    @endif
                    @if($nilai->catatan)
                    <div class="criteria-note">
                        <i class="fas fa-pencil-alt"></i> {{ $nilai->catatan }}
                    </div>
                    @endif
                    <div class="criteria-status">
                        @if($nilai->status_validasi == 'divalidasi')
                            <span class="validated"><i class="fas fa-check-circle"></i> Divalidasi</span>
                        @elseif($nilai->status_validasi == 'ditolak')
                            <span class="rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                        @else
                            <span class="pending"><i class="fas fa-clock"></i> Menunggu Validasi</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-criteria">
                <i class="fas fa-chart-simple"></i>
                <p>Belum ada data nilai kriteria untuk tahun ini</p>
                <a href="#" class="btn-primary-sm">
                    <i class="fas fa-plus"></i> Input Nilai
                </a>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Hasil SAW (jika ada) -->
    @if($hasilSaw)
    <div class="detail-card full-width">
        <div class="card-header">
            <div class="card-header-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="card-header-title">
                <h3>Hasil Perhitungan SAW</h3>
                <p>Hasil perhitungan prioritas perbaikan jalan</p>
            </div>
            <div class="card-header-action">
                <span class="year-badge">{{ $hasilSaw->tahun_perhitungan }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="saw-result">
                <div class="saw-score">
                    <div class="score-label">Skor Akhir</div>
                    <div class="score-value">{{ number_format($hasilSaw->skor_akhir, 6) }}</div>
                </div>
                <div class="saw-rank">
                    <div class="rank-label">Peringkat</div>
                    <div class="rank-value">#{{ $hasilSaw->peringkat }}</div>
                </div>
                <div class="saw-date">
                    <div class="date-label">Tanggal Perhitungan</div>
                    <div class="date-value">{{ $hasilSaw->tanggal_perhitungan->translatedFormat('d F Y') }}</div>
                </div>
                <div class="saw-user">
                    <div class="user-label">Dihitung Oleh</div>
                    <div class="user-value">{{ $hasilSaw->dihitungOleh->name ?? '-' }}</div>
                </div>
            </div>
            
            @if($hasilSaw->detail_perhitungan)
<div class="calculation-section" style="margin-top: 24px;">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #F9A826;">
        <h3 style="margin: 0; font-size: 18px; font-weight: 700;">
            <i class="fas fa-calculator" style="color: #F9A826;"></i> Detail Perhitungan SAW
        </h3>
        <button onclick="toggleDetailPerhitungan()" class="btn-toggle" style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 6px 12px; cursor: pointer;">
            <i class="fas fa-eye"></i> Tampilkan Detail
        </button>
    </div>
    
    <div id="detailPerhitungan" style="display: none;">
        @php
            $detailPerhitungan = json_decode($hasilSaw->detail_perhitungan, true);
            $total = 0;
        @endphp
        
        <div class="calculation-grid" style="display: grid; gap: 12px; margin-bottom: 20px;">
            @foreach($detailPerhitungan as $item)
            <div class="calculation-item" style="background: #F8FAFC; border-radius: 12px; padding: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div style="flex: 2;">
                        <div style="font-weight: 700; margin-bottom: 4px;">{{ $item['kriteria_nama'] }}</div>
                        <div style="font-size: 12px; color: #6B7280;">
                            <span style="margin-right: 16px;">Nilai: <strong>{{ number_format($item['nilai_asli'], 2) }}</strong></span>
                            <span>Bobot: <strong>{{ number_format($item['bobot'] * 100, 0) }}%</strong></span>
                        </div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 11px; color: #6B7280;">Normalisasi</div>
                        <div style="font-weight: 700; color: #F9A826;">{{ number_format($item['nilai_normalisasi'], 4) }}</div>
                    </div>
                    <div style="flex: 1; text-align: right;">
                        <div style="font-size: 11px; color: #6B7280;">Kontribusi</div>
                        <div style="font-weight: 700;">{{ number_format($item['kontribusi'], 4) }}</div>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div style="background: #E2E8F0; border-radius: 10px; height: 6px;">
                        <div style="width: {{ $item['nilai_normalisasi'] * 100 }}%; background: #F9A826; height: 6px; border-radius: 10px;"></div>
                    </div>
                </div>
            </div>
            @php $total += $item['kontribusi']; @endphp
            @endforeach
        </div>
        
        <div class="calculation-total" style="background: linear-gradient(135deg, #F9A826, #E8912A); border-radius: 12px; padding: 16px; text-align: center;">
            <div style="font-size: 14px; font-weight: 600; color: #1A2A3A;">TOTAL SKOR AKHIR (V)</div>
            <div style="font-size: 28px; font-weight: 800; color: #1A2A3A;">{{ number_format($total, 4) }}</div>
            <div style="font-size: 11px; color: #1A2A3A;">Semakin tinggi skor, semakin prioritas jalan tersebut</div>
        </div>
    </div>
</div>

<script>
    let detailVisible = false;
    
    function toggleDetailPerhitungan() {
        const detailDiv = document.getElementById('detailPerhitungan');
        const btn = document.querySelector('.btn-toggle');
        
        if (detailVisible) {
            detailDiv.style.display = 'none';
            btn.innerHTML = '<i class="fas fa-eye"></i> Tampilkan Detail';
            detailVisible = false;
        } else {
            detailDiv.style.display = 'block';
            btn.innerHTML = '<i class="fas fa-eye-slash"></i> Sembunyikan Detail';
            detailVisible = true;
        }
    }
</script>
@endif
        </div>
    </div>
    @endif
</div>

<!-- Modal Hapus -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-trash-alt" style="font-size: 28px; color: #EF4444;"></i>
            </div>
            <h3 style="margin-bottom: 8px;">Hapus Data Jalan</h3>
            <p style="color: var(--text-light); margin-bottom: 8px;">Apakah Anda yakin ingin menghapus data jalan:</p>
            <p style="font-weight: 700; margin-bottom: 16px;" id="deleteJalanName"></p>
            <p style="color: #EF4444; font-size: 13px; margin-bottom: 24px;">
                <i class="fas fa-exclamation-triangle"></i> Data yang dihapus dapat dipulihkan kembali
            </p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button id="cancelDelete" class="modal-btn modal-btn-cancel">Batal</button>
                <button id="confirmDelete" class="modal-btn modal-btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Toggle Status -->
<div class="modal-overlay" id="toggleModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEF3C7; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;" id="toggleIcon">
                <i class="fas fa-check-circle" style="font-size: 28px; color: #10B981;"></i>
            </div>
            <h3 style="margin-bottom: 8px;" id="toggleTitle">Aktifkan Jalan</h3>
            <p style="color: var(--text-light); margin-bottom: 16px;" id="toggleMessage"></p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button id="cancelToggle" class="modal-btn modal-btn-cancel">Batal</button>
                <button id="confirmToggle" class="modal-btn modal-btn-warning">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Action Bar */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .btn-back {
        background: var(--bg-white);
        color: var(--text-dark);
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        border: 1px solid var(--border);
    }
    
    .btn-back:hover {
        background: var(--bg-light);
        border-color: var(--secondary);
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
    }
    
    .btn-edit {
        background: var(--secondary);
        color: var(--primary-dark);
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
    }
    
    .btn-edit:hover {
        background: var(--secondary-dark);
        transform: translateY(-2px);
    }
    
    .btn-delete {
        background: #EF4444;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .btn-delete:hover {
        background: #DC2626;
        transform: translateY(-2px);
    }
    
    .btn-activate {
        background: #10B981;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .btn-activate:hover {
        background: #059669;
        transform: translateY(-2px);
    }
    
    /* Detail Grid */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }
    
    .detail-card {
        background: var(--bg-white);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: var(--transition);
    }
    
    .detail-card.full-width {
        grid-column: span 2;
    }
    
    .card-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    
    .card-header-icon {
        width: 44px;
        height: 44px;
        background: var(--secondary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: var(--primary-dark);
    }
    
    .card-header-title {
        flex: 1;
    }
    
    .card-header-title h3 {
        color: white;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    
    .card-header-title p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 13px;
    }
    
    .status-badge {
        display: flex;
        align-items: center;
    }
    
    .card-header-action {
        display: flex;
        align-items: center;
    }
    
    .year-badge {
        background: var(--secondary);
        color: var(--primary-dark);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }
    
    .card-body {
        padding: 24px;
    }
    
    /* Info Rows */
    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        width: 140px;
        font-weight: 600;
        color: var(--text-light);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-value {
        flex: 1;
        font-size: 14px;
        color: var(--text-dark);
    }
    
    .info-unit {
        font-size: 12px;
        color: var(--text-lighter);
        margin-left: 4px;
    }
    
    .info-date {
        font-size: 11px;
        color: var(--text-lighter);
        margin-left: 8px;
    }
    
    .badge-kode {
        background: var(--primary-lighter);
        color: var(--primary);
        padding: 4px 10px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 13px;
        font-weight: 600;
    }
    
    .status-active {
        background: #D1FAE5;
        color: #059669;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-inactive {
        background: #FEE2E2;
        color: #DC2626;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .info-divider {
        height: 1px;
        background: var(--border);
        margin: 16px 0;
    }
    
    .map-link {
        margin-top: 12px;
    }
    
    .btn-map {
        background: var(--bg-light);
        color: var(--primary);
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
    }
    
    .btn-map:hover {
        background: var(--primary-lighter);
    }
    
    .info-empty {
        text-align: center;
        padding: 30px;
        color: var(--text-light);
    }
    
    .info-empty i {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
    
    .info-empty p {
        margin-bottom: 8px;
    }
    
    .info-empty small {
        font-size: 12px;
    }
    
    /* Criteria Grid */
    .criteria-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .criteria-item {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 16px;
        transition: var(--transition);
        border: 1px solid var(--border);
    }
    
    .criteria-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .criteria-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .criteria-name {
        font-weight: 700;
        font-size: 14px;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .criteria-badge {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .criteria-badge.benefit {
        background: #D1FAE5;
        color: #059669;
    }
    
    .criteria-badge.cost {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .criteria-value {
        display: flex;
        align-items: baseline;
        gap: 6px;
        margin-bottom: 10px;
    }
    
    .value-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary);
    }
    
    .value-unit {
        font-size: 12px;
        color: var(--text-lighter);
    }
    
    .criteria-normalized {
        font-size: 11px;
        color: var(--text-light);
        margin-bottom: 8px;
    }
    
    .criteria-note {
        font-size: 11px;
        color: var(--text-lighter);
        background: white;
        padding: 6px 10px;
        border-radius: 8px;
        margin: 8px 0;
    }
    
    .criteria-status {
        margin-top: 8px;
    }
    
    .validated {
        font-size: 11px;
        color: #059669;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .rejected {
        font-size: 11px;
        color: #DC2626;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .pending {
        font-size: 11px;
        color: #F59E0B;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .empty-criteria {
        text-align: center;
        padding: 40px;
        color: var(--text-light);
    }
    
    .empty-criteria i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .btn-primary-sm {
        background: var(--secondary);
        color: var(--primary-dark);
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        transition: var(--transition);
    }
    
    .btn-primary-sm:hover {
        background: var(--secondary-dark);
        transform: translateY(-1px);
    }
    
    /* SAW Result */
    .saw-result {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .saw-score, .saw-rank, .saw-date, .saw-user {
        padding: 16px;
        background: var(--bg-light);
        border-radius: 12px;
    }
    
    .score-label, .rank-label, .date-label, .user-label {
        font-size: 12px;
        color: var(--text-light);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .score-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--secondary);
    }
    
    .rank-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--primary);
    }
    
    .date-value, .user-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    .detail-calculation {
        margin-top: 20px;
    }
    
    .detail-calculation summary {
        cursor: pointer;
        padding: 10px;
        background: var(--bg-light);
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .calculation-detail {
        margin-top: 12px;
        padding: 16px;
        background: #1E1E1E;
        color: #D4D4D4;
        border-radius: 8px;
        overflow-x: auto;
        font-size: 12px;
        font-family: monospace;
    }
    
    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    .modal-content {
        background: var(--bg-white);
        border-radius: 20px;
        width: 90%;
        max-width: 400px;
        overflow: hidden;
    }
    
    .modal-btn {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        border: none;
    }
    
    .modal-btn-cancel {
        background: var(--bg-light);
        color: var(--text-dark);
    }
    
    .modal-btn-danger {
        background: #EF4444;
        color: white;
    }
    
    .modal-btn-warning {
        background: var(--secondary);
        color: var(--primary-dark);
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .detail-card.full-width {
            grid-column: span 1;
        }
        
        .saw-result {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .action-bar {
            flex-direction: column;
        }
        
        .action-buttons {
            width: 100%;
            flex-direction: column;
        }
        
        .btn-edit, .btn-delete, .btn-activate {
            justify-content: center;
        }
        
        .info-row {
            flex-direction: column;
        }
        
        .info-label {
            width: 100%;
            margin-bottom: 6px;
        }
        
        .card-header {
            flex-direction: column;
            text-align: center;
        }
        
        .saw-result {
            grid-template-columns: 1fr;
        }
        
        .criteria-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let deleteId = null;
    let toggleId = null;
    let toggleStatusValue = null;
    let toggleName = null;
    
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteJalanName').innerHTML = '<strong>"' + name + '"</strong>';
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function toggleStatus(id, name, currentStatus) {
        toggleId = id;
        toggleName = name;
        
        const modal = document.getElementById('toggleModal');
        const title = document.getElementById('toggleTitle');
        const message = document.getElementById('toggleMessage');
        const icon = document.getElementById('toggleIcon');
        
        if (currentStatus) {
            title.innerHTML = 'Nonaktifkan Jalan';
            message.innerHTML = 'Apakah Anda yakin ingin menonaktifkan jalan <strong>"' + name + '"</strong>?';
            icon.innerHTML = '<i class="fas fa-ban" style="font-size: 28px; color: #F59E0B;"></i>';
        } else {
            title.innerHTML = 'Aktifkan Jalan';
            message.innerHTML = 'Apakah Anda yakin ingin mengaktifkan kembali jalan <strong>"' + name + '"</strong>?';
            icon.innerHTML = '<i class="fas fa-check-circle" style="font-size: 28px; color: #10B981;"></i>';
        }
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    document.getElementById('confirmDelete')?.addEventListener('click', function() {
        if (deleteId) {
            document.getElementById('delete-form-' + deleteId).submit();
        }
    });
    
    document.getElementById('cancelDelete')?.addEventListener('click', function() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteId = null;
    });
    
    document.getElementById('confirmToggle')?.addEventListener('click', function() {
        if (toggleId) {
            document.getElementById('toggle-form-' + toggleId).submit();
        }
    });
    
    document.getElementById('cancelToggle')?.addEventListener('click', function() {
        document.getElementById('toggleModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        toggleId = null;
    });
    
    window.addEventListener('click', function(e) {
        const deleteModal = document.getElementById('deleteModal');
        const toggleModal = document.getElementById('toggleModal');
        
        if (e.target === deleteModal) {
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        if (e.target === toggleModal) {
            toggleModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
</script>
@endpush