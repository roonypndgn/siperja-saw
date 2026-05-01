@extends('layouts.admin')

@section('title', 'Detail Nilai Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Detail Nilai Kriteria')
@section('page-subtitle', 'Informasi lengkap nilai penilaian untuk semua kriteria')

@section('content')
<div class="detail-container">
    <!-- Header -->
    <div class="detail-header">
        <div class="detail-header-content">
            <div class="detail-header-left">
                <div class="detail-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="detail-badge">
                        <span class="badge-tahun">Tahun {{ $tahun }}</span>
                        @php
                            $allValid = true;
                            $hasPending = false;
                            $hasRejected = false;
                            foreach ($nilai as $n) {
                                if ($n->status_validasi == 'pending') $hasPending = true;
                                if ($n->status_validasi == 'ditolak') $hasRejected = true;
                                if ($n->status_validasi != 'divalidasi') $allValid = false;
                            }
                        @endphp
                        @if($allValid && $nilai->count() == $kriteria->count())
                            <span class="badge-status-valid">
                                <i class="fas fa-check-circle"></i> Lengkap & Tervalidasi
                            </span>
                        @elseif($hasPending)
                            <span class="badge-status-pending">
                                <i class="fas fa-clock"></i> Menunggu Validasi
                            </span>
                        @elseif($hasRejected)
                            <span class="badge-status-rejected">
                                <i class="fas fa-times-circle"></i> Ada Data Ditolak
                            </span>
                        @else
                            <span class="badge-status-incomplete">
                                <i class="fas fa-exclamation-triangle"></i> Belum Lengkap
                            </span>
                        @endif
                    </div>
                    <h2 class="detail-title">{{ $jalan->nama }}</h2>
                    <p class="detail-subtitle">
                        <i class="fas fa-map-marker-alt"></i> {{ $jalan->lokasi }}
                    </p>
                </div>
            </div>
            <div class="detail-header-right">
                <div class="info-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $nilai->count() }}</div>
                        <div class="stat-label">Kriteria Dinilai</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $kriteria->count() }}</div>
                        <div class="stat-label">Total Kriteria</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($rataRataNilai, 2) }}</div>
                        <div class="stat-label">Rata-rata Nilai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="detail-content">
        <!-- Informasi Jalan -->
        <div class="info-card">
            <h3 class="info-card-title">
                <i class="fas fa-road" style="color: var(--secondary);"></i>
                Informasi Jalan
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Kode Jalan</label>
                    <div class="info-value">{{ $jalan->kode }}</div>
                </div>
                <div class="info-item">
                    <label>Nama Jalan</label>
                    <div class="info-value">{{ $jalan->nama }}</div>
                </div>
                <div class="info-item">
                    <label>Lokasi</label>
                    <div class="info-value">{{ $jalan->lokasi }}</div>
                </div>
                <div class="info-item">
                    <label>Panjang Jalan</label>
                    <div class="info-value">{{ number_format($jalan->panjang, 0, ',', '.') }} meter</div>
                </div>
                <div class="info-item">
                    <label>Tahun Penilaian</label>
                    <div class="info-value">{{ $tahun }}</div>
                </div>
                <div class="info-item">
                    <label>Status</label>
                    <div class="info-value">
                        @if($jalan->is_active)
                            <span class="status-active">Aktif</span>
                        @else
                            <span class="status-inactive">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabel Nilai Kriteria -->
        <div class="table-card">
            <h3 class="table-card-title">
                <i class="fas fa-table-list" style="color: var(--secondary);"></i>
                Nilai Kriteria
            </h3>
            
            <div class="table-wrapper">
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th width="10%">Kode</th>
                            <th width="25%">Nama Kriteria</th>
                            <th width="12%">Tipe</th>
                            <th width="10%">Bobot</th>
                            <th width="10%">Satuan</th>
                            <th width="13%">Nilai</th>
                            <th width="10%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $krit)
                        @php
                            $nilaiItem = $nilai->firstWhere('kriteria_id', $krit->id);
                            $nilaiValue = $nilaiItem ? $nilaiItem->nilai : null;
                            $status = $nilaiItem ? $nilaiItem->status_validasi : null;
                            $catatan = $nilaiItem ? $nilaiItem->catatan : null;
                        @endphp
                        <tr>
                            <td>
                                <span class="badge-kode">{{ $krit->kode }}</span>
                            </td>
                            <td>
                                <strong>{{ $krit->nama }}</strong>
                                @if($catatan)
                                    <br>
                                    <small class="catatan-text">
                                        <i class="fas fa-sticky-note"></i> {{ Str::limit($catatan, 50) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($krit->tipe == 'benefit')
                                    <span class="type-benefit">
                                        <i class="fas fa-arrow-up"></i> Benefit
                                    </span>
                                    <br>
                                    <small class="text-muted">↑ Semakin besar semakin baik</small>
                                @else
                                    <span class="type-cost">
                                        <i class="fas fa-arrow-down"></i> Cost
                                    </span>
                                    <br>
                                    <small class="text-muted">↓ Semakin kecil semakin baik</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge-bobot">{{ number_format($krit->bobot * 100, 0) }}%</span>
                            </td>
                            <td class="text-center">
                                @if($krit->satuan)
                                    <span class="badge-satuan">
                                        <i class="fas fa-ruler"></i> {{ $krit->satuan }}
                                    </span>
                                @else
                                    <span class="badge-satuan-empty">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($nilaiValue !== null)
                                    <span class="nilai-value">
                                        {{ number_format($nilaiValue, 2) }}
                                    </span>
                                @else
                                    <span class="nilai-empty">
                                        <i class="fas fa-minus-circle"></i> Belum diisi
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($status == 'divalidasi')
                                    <span class="status-valid">
                                        <i class="fas fa-check-circle"></i> Valid
                                    </span>
                                @elseif($status == 'pending')
                                    <span class="status-pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @elseif($status == 'ditolak')
                                    <span class="status-invalid">
                                        <i class="fas fa-times-circle"></i> Ditolak
                                    </span>
                                @else
                                    <span class="status-empty">
                                        <i class="fas fa-question-circle"></i> -
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($nilaiItem)
                                    <a href="{{ route('admin.nilai-kriteria.edit', $nilaiItem->id) }}" 
                                       class="btn-action btn-edit" title="Edit Nilai">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @else
                                    <a href="{{ route('admin.nilai-kriteria.create', ['jalan_id' => $jalan->id, 'tahun' => $tahun]) }}" 
                                       class="btn-action btn-create" title="Input Nilai">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Ringkasan Validasi -->
        <div class="summary-card">
            <h3 class="summary-card-title">
                <i class="fas fa-clipboard-list" style="color: var(--secondary);"></i>
                Ringkasan Validasi
            </h3>
            <div class="summary-grid">
                <div class="summary-item valid">
                    <div class="summary-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="summary-info">
                        <div class="summary-value">{{ $summary['valid'] }}</div>
                        <div class="summary-label">Tervalidasi</div>
                    </div>
                </div>
                <div class="summary-item pending">
                    <div class="summary-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="summary-info">
                        <div class="summary-value">{{ $summary['pending'] }}</div>
                        <div class="summary-label">Pending</div>
                    </div>
                </div>
                <div class="summary-item rejected">
                    <div class="summary-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="summary-info">
                        <div class="summary-value">{{ $summary['rejected'] }}</div>
                        <div class="summary-label">Ditolak</div>
                    </div>
                </div>
                <div class="summary-item empty">
                    <div class="summary-icon">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                    <div class="summary-info">
                        <div class="summary-value">{{ $summary['empty'] }}</div>
                        <div class="summary-label">Belum Diisi</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Validator -->
        @if($validatorInfo)
        <div class="validator-card">
            <h3 class="validator-card-title">
                <i class="fas fa-user-check" style="color: var(--secondary);"></i>
                Informasi Validasi
            </h3>
            <div class="validator-info">
                <div class="validator-item">
                    <label>Divalidasi Oleh</label>
                    <div class="value">{{ $validatorInfo['name'] }}</div>
                </div>
                <div class="validator-item">
                    <label>Tanggal Validasi</label>
                    <div class="value">{{ $validatorInfo['date'] }}</div>
                </div>
                <div class="validator-item">
                    <label>Catatan Validasi</label>
                    <div class="value">{{ $validatorInfo['catatan'] ?? '-' }}</div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Tombol Aksi -->
        <div class="action-buttons-bottom">
            <a href="{{ route('admin.nilai-kriteria.index', ['tahun' => $tahun]) }}" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <div style="display: flex; gap: 12px;">
                @php
                    $firstNilaiId = $nilai->first()->id ?? null;
                @endphp
                @if($firstNilaiId)
                    <a href="{{ route('admin.nilai-kriteria.edit', $firstNilaiId) }}" class="btn-edit">
                        <i class="fas fa-edit"></i> Edit Semua Nilai
                    </a>
                @endif
                <button type="button" onclick="confirmDeleteBulk({{ $jalan->id }}, '{{ addslashes($jalan->nama) }}', {{ $tahun }})" class="btn-delete">
                    <i class="fas fa-trash-alt"></i> Hapus Semua Nilai
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Semua Nilai (Bulk) -->
<div class="modal-overlay" id="deleteBulkModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div style="padding: 24px; text-align: center;">
            <!-- Icon Alert Red -->
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-trash-alt" style="font-size: 28px; color: #EF4444;"></i>
            </div>

            <h3 style="margin-bottom: 8px; color: #111827; font-weight: 600;">Hapus Semua Nilai</h3>
            
            <p style="color: #6B7280; margin-bottom: 4px; font-size: 14px;">Apakah Anda yakin ingin menghapus semua nilai kriteria untuk:</p>
            
            <!-- Area Nama Jalan & Tahun yang akan diisi via JS -->
            <p style="font-weight: 700; color: #111827; margin-bottom: 16px; font-size: 15px;" id="deleteBulkInfo"></p>
            
            <!-- Warning Message -->
            <div style="background: #FFF5F5; border-radius: 8px; padding: 10px; margin-bottom: 24px;">
                <p style="color: #EF4444; font-size: 12px; margin: 0; font-weight: 500;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 4px;"></i> 
                    Tindakan ini tidak dapat dibatalkan!
                </p>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" 
                        onclick="closeDeleteBulkModal()" 
                        class="modal-btn modal-btn-cancel" 
                        style="flex: 1; padding: 10px 16px; border-radius: 8px; font-weight: 500; cursor: pointer;">
                    Batal
                </button>
                <button type="button" 
                        onclick="submitDeleteBulk()" 
                        class="modal-btn modal-btn-danger" 
                        style="flex: 1; padding: 10px 16px; border-radius: 8px; font-weight: 500; cursor: pointer; background: #EF4444; color: white; border: none;">
                    Ya, Hapus Semua
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Detail Container */
    .detail-container {
        background: var(--bg-white);
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
    }
    
    /* Header */
    .detail-header {
        background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%);
        padding: 30px;
    }
    
    .detail-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .detail-header-left {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .detail-icon {
        width: 70px;
        height: 70px;
        background: var(--secondary);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .detail-icon i {
        font-size: 32px;
        color: var(--primary-dark);
    }
    
    .detail-badge {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
        flex-wrap: wrap;
    }
    
    .badge-tahun {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .badge-status-valid {
        background: #10B981;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-status-pending {
        background: #F59E0B;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-status-rejected {
        background: #EF4444;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-status-incomplete {
        background: #6B7280;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .detail-title {
        font-size: 24px;
        font-weight: 800;
        color: white;
        margin: 0 0 5px 0;
    }
    
    .detail-subtitle {
        color: #8BA3BC;
        margin: 0;
        font-size: 13px;
    }
    
    .info-stats {
        display: flex;
        gap: 24px;
        background: rgba(255,255,255,0.1);
        padding: 12px 20px;
        border-radius: 16px;
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--secondary);
    }
    
    .stat-label {
        font-size: 11px;
        color: #8BA3BC;
    }
    
    /* Content */
    .detail-content {
        padding: 30px;
    }
    
    /* Info Card */
    .info-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .info-card-title, .table-card-title, .summary-card-title, .validator-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid var(--secondary);
        padding-bottom: 12px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    
    .info-item label {
        font-size: 12px;
        color: var(--text-light);
        display: block;
        margin-bottom: 4px;
    }
    
    .info-item .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    /* Table Card */
    .table-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .detail-table th {
        background: #E2E8F0;
        padding: 12px;
        text-align: left;
        font-weight: 700;
        font-size: 12px;
        color: var(--text-dark);
    }
    
    .detail-table td {
        padding: 14px 12px;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
        vertical-align: middle;
    }
    
    .detail-table tbody tr:hover {
        background: rgba(249, 168, 38, 0.05);
    }
    
    .text-center {
        text-align: center;
    }
    
    /* Badge Styles */
    .badge-kode {
        background: var(--primary-lighter);
        color: var(--primary);
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-bobot {
        background: #FEF3E0;
        color: var(--secondary-dark);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
    
    .badge-satuan {
        background: #F8FAFC;
        color: var(--text-dark);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-satuan-empty {
        color: #9CA3AF;
        font-size: 12px;
    }
    
    /* Type Styles */
    .type-benefit, .type-cost {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .type-benefit {
        background: #D1FAE5;
        color: #059669;
    }
    
    .type-cost {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    /* Nilai Styles */
    .nilai-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
    }
    
    .nilai-empty {
        color: #9CA3AF;
        font-size: 12px;
    }
    
    .catatan-text {
        font-size: 11px;
        color: var(--text-light);
    }
    
    /* Status Styles */
    .status-valid, .status-pending, .status-invalid, .status-empty {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-valid { background: #D1FAE5; color: #059669; }
    .status-pending { background: #FEF3C7; color: #D97706; }
    .status-invalid { background: #FEE2E2; color: #DC2626; }
    .status-empty { background: #F1F5F9; color: #6B7280; }
    .status-active { background: #D1FAE5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 11px; }
    .status-inactive { background: #FEE2E2; color: #DC2626; padding: 4px 10px; border-radius: 20px; font-size: 11px; }
    
    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: var(--transition);
    }
    
    .btn-edit {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-edit:hover {
        background: #D97706;
        color: white;
    }
    
    .btn-create {
        background: #D1FAE5;
        color: #059669;
    }
    
    .btn-create:hover {
        background: #059669;
        color: white;
    }
    
    /* Summary Card */
    .summary-card, .validator-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    
    .summary-item {
        background: white;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s;
    }
    
    .summary-item:hover {
        transform: translateY(-2px);
    }
    
    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .summary-item.valid .summary-icon { background: #D1FAE5; color: #10B981; }
    .summary-item.pending .summary-icon { background: #FEF3C7; color: #F59E0B; }
    .summary-item.rejected .summary-icon { background: #FEE2E2; color: #EF4444; }
    .summary-item.empty .summary-icon { background: #F1F5F9; color: #6B7280; }
    
    .summary-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-dark);
    }
    
    .summary-label {
        font-size: 12px;
        color: var(--text-light);
    }
    
    /* Validator Card */
    .validator-info {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    
    .validator-item label {
        font-size: 12px;
        color: var(--text-light);
        display: block;
        margin-bottom: 4px;
    }
    
    .validator-item .value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
    }
    
    /* Action Buttons Bottom */
    .action-buttons-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .btn-outline {
        background: transparent;
        color: var(--text-dark);
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid var(--border);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .btn-outline:hover {
        border-color: var(--secondary);
        color: var(--secondary);
    }
    
    .btn-edit {
        background: var(--secondary);
        color: var(--primary-dark);
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .btn-edit:hover {
        background: var(--secondary-dark);
        transform: translateY(-2px);
    }
    
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
        padding: 12px 28px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .btn-delete:hover {
        background: #DC2626;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Modal Buttons */
    .modal-btn-cancel {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        border: 1px solid var(--border);
        background: white;
    }
    
    .modal-btn-cancel:hover {
        background: var(--bg-light);
    }
    
    .modal-btn-danger {
        background: #EF4444;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .modal-btn-danger:hover {
        background: #DC2626;
    }
    
    .text-muted {
        color: var(--text-light);
    }
    
    @media (max-width: 768px) {
        .detail-content {
            padding: 20px;
        }
        
        .info-grid, .summary-grid, .validator-info {
            grid-template-columns: 1fr;
        }
        
        .info-stats {
            width: 100%;
            justify-content: space-around;
        }
        
        .action-buttons-bottom {
            flex-direction: column;
        }
        
        .action-buttons-bottom > div {
            width: 100%;
        }
        
        .btn-edit, .btn-delete, .btn-outline {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    let deleteBulkJalanId = null;
    let deleteBulkJalanName = null;
    let deleteBulkTahun = null;
    
    function confirmDeleteBulk(jalanId, jalanName, tahun) {
        deleteBulkJalanId = jalanId;
        deleteBulkJalanName = jalanName;
        deleteBulkTahun = tahun;
        
        document.getElementById('deleteBulkInfo').innerHTML = `
            <strong>"${jalanName}"</strong> untuk tahun <strong>${tahun}</strong>
            <br>
            <small style="color: #EF4444;">Semua data nilai untuk jalan ini akan dihapus!</small>
        `;
        document.getElementById('deleteBulkModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeDeleteBulkModal() {
        document.getElementById('deleteBulkModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteBulkJalanId = null;
        deleteBulkJalanName = null;
        deleteBulkTahun = null;
    }
    
    function submitDeleteBulk() {
        if (!deleteBulkJalanId) {
            alert('ID tidak ditemukan');
            return;
        }
        
        fetch(`/admin/nilai-kriteria/delete-by-jalan/${deleteBulkJalanId}/${deleteBulkTahun}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("admin.nilai-kriteria.index") }}?tahun=' + deleteBulkTahun;
            } else {
                alert('Gagal menghapus data: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        });
        
        closeDeleteBulkModal();
    }
    
    // Close modal on outside click
    window.addEventListener('click', function(e) {
        const deleteBulkModal = document.getElementById('deleteBulkModal');
        if (e.target === deleteBulkModal) {
            closeDeleteBulkModal();
        }
    });
    
    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('deleteBulkModal')?.style.display === 'flex') {
                closeDeleteBulkModal();
            }
        }
    });
</script>
@endsection