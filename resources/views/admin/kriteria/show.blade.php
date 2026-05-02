@extends('layouts.admin')

@section('title', 'Detail Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Detail Kriteria')
@section('page-subtitle', 'Informasi lengkap kriteria penilaian')

@section('content')
<div class="detail-container">
    <!-- Header Card dengan Background -->
    <div class="detail-header">
        <div class="detail-header-content">
            <div class="detail-header-left">
                <div class="detail-icon">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div>
                    <div class="detail-badge">
                        <span class="badge-kode">{{ $kriteria->kode }}</span>
                        @if($kriteria->tipe == 'benefit')
                            <span class="badge-benefit">
                                <i class="fas fa-arrow-up"></i> Benefit
                            </span>
                        @else
                            <span class="badge-cost">
                                <i class="fas fa-arrow-down"></i> Cost
                            </span>
                        @endif
                        @if($kriteria->is_active)
                            <span class="badge-active">
                                <i class="fas fa-check-circle"></i> Aktif
                            </span>
                        @else
                            <span class="badge-inactive">
                                <i class="fas fa-times-circle"></i> Nonaktif
                            </span>
                        @endif
                    </div>
                    <h2 class="detail-title">{{ $kriteria->nama }}</h2>
                    @if($kriteria->satuan)
                        <p class="detail-subtitle">
                            <i class="fas fa-ruler"></i> Satuan: {{ $kriteria->satuan }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="detail-header-right">
                <div class="bobot-circle">
                    <div class="bobot-value">{{ number_format($kriteria->bobot * 100, 0) }}%</div>
                    <div class="bobot-label">Bobot</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div class="detail-content">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            
            <!-- Kolom Kiri - Informasi Detail -->
            <div>
                <div class="info-card">
                    <h3 class="info-card-title">
                        <i class="fas fa-info-circle" style="color: var(--secondary);"></i>
                        Informasi Detail
                    </h3>
                    
                    <table class="info-table">
                        <tr>
                            <td class="info-label">
                                <i class="fas fa-barcode"></i> Kode Kriteria
                            </td>
                            <td class="info-value">
                                <strong>{{ $kriteria->kode }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="fas fa-tag"></i> Nama Kriteria
                            </td>
                            <td class="info-value">
                                {{ $kriteria->nama }}
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="fas fa-chart-line"></i> Tipe
                            </td>
                            <td class="info-value">
                                @if($kriteria->tipe == 'benefit')
                                    <span class="type-benefit">
                                        <i class="fas fa-arrow-up"></i> Benefit
                                    </span>
                                    <small style="margin-left: 8px; color: var(--text-light);">(Semakin besar nilai, semakin baik)</small>
                                @else
                                    <span class="type-cost">
                                        <i class="fas fa-arrow-down"></i> Cost
                                    </span>
                                    <small style="margin-left: 8px; color: var(--text-light);">(Semakin kecil nilai, semakin baik)</small>
                                @endif
                            </td>
                        </tr>
                        @if($kriteria->satuan)
                        <tr>
                            <td class="info-label">
                                <i class="fas fa-ruler"></i> Satuan
                            </td>
                            <td class="info-value">
                                {{ $kriteria->satuan }}
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="info-label">
                                <i class="fas fa-percent"></i> Bobot
                            </td>
                            <td class="info-value">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <span style="font-size: 20px; font-weight: 800; color: var(--secondary);">
                                        {{ number_format($kriteria->bobot * 100, 0) }}%
                                    </span>
                                    <div style="flex: 1; max-width: 200px;">
                                        <div style="background: #E2E8F0; border-radius: 10px; height: 8px;">
                                            <div style="width: {{ $kriteria->bobot * 100 }}%; height: 100%; background: var(--secondary); border-radius: 10px;"></div>
                                        </div>
                                    </div>
                                    <span style="color: var(--text-light); font-size: 13px;">
                                        ({{ number_format($kriteria->bobot, 4) }})
                                    </span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="fas fa-sort-numeric-down"></i> Urutan Tampil
                            </td>
                            <td class="info-value">
                                <span class="badge-urutan">#{{ $kriteria->urutan }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="fas fa-calendar-plus"></i> Dibuat
                            </td>
                            <td class="info-value">
                                {{ $kriteria->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">
                                <i class="fas fa-calendar-edit"></i> Terakhir Update
                            </td>
                            <td class="info-value">
                                {{ $kriteria->updated_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Kolom Kanan - Statistik & Keterangan -->
            <div>
                <!-- Statistik Card -->
                <div class="stats-card">
                    <h3 class="stats-card-title">
                        <i class="fas fa-chart-bar" style="color: var(--secondary);"></i>
                        Statistik Penggunaan
                    </h3>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ $totalPenggunaan }}</div>
                                <div class="stat-label">Total Penilaian</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ $tahunTerakhir ?? '-' }}</div>
                                <div class="stat-label">Tahun Terakhir</div>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-chart-simple"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value">{{ $rataRataNilai ? number_format($rataRataNilai, 2) : '-' }}</div>
                                <div class="stat-label">Rata-rata Nilai</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Keterangan Card -->
                <div class="description-card">
                    <h3 class="description-card-title">
                        <i class="fas fa-align-left" style="color: var(--secondary);"></i>
                        Keterangan / Deskripsi
                    </h3>
                    
                    @if($kriteria->keterangan)
                        <div class="description-content">
                            <div class="description-text">
                                {{ $kriteria->keterangan }}
                            </div>
                        </div>
                    @else
                        <div class="description-empty">
                            <i class="fas fa-align-left"></i>
                            <p>Tidak ada keterangan untuk kriteria ini</p>
                            <a href="{{ route('admin.kriteria.edit', $kriteria->id) }}" class="btn-add-desc">
                                <i class="fas fa-plus"></i> Tambah Keterangan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Riwayat Penilaian (jika ada) -->
        @if($totalPenggunaan > 0)
        <div class="history-card">
            <h3 class="history-card-title">
                <i class="fas fa-history" style="color: var(--secondary);"></i>
                Riwayat Penilaian
                <span style="font-size: 13px; font-weight: normal; margin-left: 12px;">(Data 10 terbaru)</span>
            </h3>
            
            <div class="table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Jalan</th>
                            <th>Lokasi</th>
                            <th>Nilai</th>
                            <th>Tahun</th>
                            <th>Status Validasi</th>
                            <th>Dinilai Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayatPenilaian as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->jalan->nama ?? '-' }}</strong>
                                <br>
                                <small style="color: var(--text-lighter);">{{ $item->jalan->kode ?? '-' }}</small>
                            </td>
                            <td>{{ $item->jalan->lokasi ?? '-' }}</td>
                            <td class="text-center">
                                <span class="nilai-badge">{{ number_format($item->nilai, 2) }}</span>
                                @if($kriteria->satuan)
                                    <small style="color: var(--text-lighter);">{{ $kriteria->satuan }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->tahun_penilaian }}</td>
                            <td class="text-center">
                                @if($item->status_validasi == 'divalidasi')
                                    <span class="status-valid">
                                        <i class="fas fa-check-circle"></i> Valid
                                    </span>
                                @elseif($item->status_validasi == 'pending')
                                    <span class="status-pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @else
                                    <span class="status-invalid">
                                        <i class="fas fa-times-circle"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $item->createdBy->name ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($totalPenggunaan > 10)
            <div style="margin-top: 16px; text-align: center;">
                <small style="color: var(--text-light);">
                    <i class="fas fa-info-circle"></i> Menampilkan 10 dari {{ $totalPenggunaan }} data penilaian
                </small>
            </div>
            @endif
        </div>
        @endif
        
        <!-- Tombol Aksi -->
        <div class="action-buttons-bottom">
            <a href="{{ route('admin.kriteria.index') }}" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('admin.kriteria.edit', $kriteria->id) }}" class="btn-edit">
                    <i class="fas fa-edit"></i> Edit Kriteria
                </a>
                @if($totalPenggunaan == 0)
                <button type="button" onclick="confirmDelete({{ $kriteria->id }}, '{{ $kriteria->nama }}')" class="btn-delete">
                    <i class="fas fa-trash"></i> Hapus Kriteria
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-trash-alt" style="font-size: 28px; color: #EF4444;"></i>
            </div>
            <h3 style="margin-bottom: 8px;">Hapus Kriteria</h3>
            <p style="color: var(--text-light); margin-bottom: 8px;">Apakah Anda yakin ingin menghapus kriteria:</p>
            <p style="font-weight: 700; margin-bottom: 16px;" id="deleteKriteriaName"></p>
            <p style="color: #EF4444; font-size: 13px; margin-bottom: 24px;">
                <i class="fas fa-exclamation-triangle"></i> Kriteria yang sudah memiliki data nilai tidak dapat dihapus
            </p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button id="cancelDelete" class="modal-btn modal-btn-cancel">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-btn modal-btn-danger">Ya, Hapus</button>
                </form>
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
    
    .badge-kode {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-family: monospace;
    }
    
    .badge-benefit, .badge-cost {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-benefit {
        background: #D1FAE5;
        color: #059669;
    }
    
    .badge-cost {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .badge-active, .badge-inactive {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-active {
        background: #D1FAE5;
        color: #059669;
    }
    
    .badge-inactive {
        background: #FEE2E2;
        color: #DC2626;
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
    
    .bobot-circle {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--secondary);
    }
    
    .bobot-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--secondary);
    }
    
    .bobot-label {
        font-size: 11px;
        color: white;
        text-transform: uppercase;
    }
    
    /* Content */
    .detail-content {
        padding: 30px;
    }
    
    /* Info Card */
    .info-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 20px;
        height: 100%;
    }
    
    .info-card-title {
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
    
    .info-table {
        width: 100%;
    }
    
    .info-table tr {
        border-bottom: 1px solid var(--border);
    }
    
    .info-table td {
        padding: 12px 8px;
        vertical-align: top;
    }
    
    .info-label {
        width: 35%;
        font-weight: 600;
        color: var(--text-light);
    }
    
    .info-label i {
        width: 20px;
        color: var(--secondary);
        margin-right: 8px;
    }
    
    .info-value {
        color: var(--text-dark);
    }
    
    /* Stats Card */
    .stats-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .stats-card-title {
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
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    
    .stat-item {
        background: white;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        transition: all 0.3s;
    }
    
    .stat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        background: #FEF3E0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }
    
    .stat-icon i {
        font-size: 20px;
        color: var(--secondary);
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-dark);
    }
    
    .stat-label {
        font-size: 11px;
        color: var(--text-light);
        margin-top: 4px;
    }
    
    /* Description Card */
    .description-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 20px;
    }
    
    .description-card-title {
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
    
    .description-content {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border-left: 4px solid var(--secondary);
    }
    
    .description-text {
        color: var(--text-dark);
        line-height: 1.8;
        white-space: pre-wrap;
    }
    
    .description-empty {
        background: white;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        color: var(--text-light);
    }
    
    .description-empty i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .description-empty p {
        margin-bottom: 16px;
    }
    
    .btn-add-desc {
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
    }
    
    .btn-add-desc:hover {
        background: var(--secondary-dark);
    }
    
    /* History Card */
    .history-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 20px;
        margin-top: 24px;
    }
    
    .history-card-title {
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
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    .history-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .history-table th {
        background: #E2E8F0;
        padding: 12px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-light);
        text-transform: uppercase;
    }
    
    .history-table td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
    }
    
    .history-table tbody tr:hover {
        background: white;
    }
    
    .text-center {
        text-align: center;
    }
    
    .nilai-badge {
        background: #FEF3E0;
        color: var(--secondary-dark);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
    }
    
    .status-valid {
        color: #10B981;
        font-size: 12px;
    }
    
    .status-pending {
        color: #F59E0B;
        font-size: 12px;
    }
    
    .status-invalid {
        color: #EF4444;
        font-size: 12px;
    }
    
    .type-benefit, .type-cost {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
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
    
    .badge-urutan {
        background: var(--secondary-lighter);
        color: var(--secondary-dark);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    /* Action Buttons */
    .action-buttons-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
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
    .modal-btn {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: var(--transition);
        border: none;
    }
    
    .modal-btn-cancel {
        background: var(--bg-light);
        color: var(--text-dark);
    }
    
    .modal-btn-cancel:hover {
        background: var(--border);
    }
    
    .modal-btn-danger {
        background: #EF4444;
        color: white;
    }
    
    .modal-btn-danger:hover {
        background: #DC2626;
    }
</style>

<script>
    let deleteId = null;
    
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteKriteriaName').innerHTML = '<strong>"' + name + '"</strong>';
        document.getElementById('deleteForm').action = "#" + id;
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteId = null;
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('deleteModal').style.display === 'flex') {
            closeModal();
        }
    });
    
    document.getElementById('cancelDelete')?.addEventListener('click', closeModal);
</script>
@endsection