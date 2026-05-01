@extends('layouts.admin')

@section('title', 'Data Nilai Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Data Nilai Kriteria')
@section('page-subtitle', 'Kelola nilai penilaian untuk setiap kriteria pada setiap jalan')

@section('content')
<!-- Filter & Tools -->
<div class="page-tools" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.nilai-kriteria.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Input Nilai Baru
        </a>
        <a href="#" class="btn-saw">
            <i class="fas fa-calculator"></i> Proses SAW
        </a>

        <!-- Dropdown Export -->
        <div class="dropdown" style="position: relative;">
            <button class="btn-secondary dropdown-toggle" type="button" id="exportDropdown" onclick="toggleDropdown()">
                <i class="fas fa-download"></i> Export
                <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 12px;"></i>
            </button>
            <div class="dropdown-menu" id="exportDropdownMenu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-width: 180px; z-index: 1000;">
                <a class="dropdown-item" href="#" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; text-decoration: none; color: var(--text-dark);">
                    <i class="fas fa-file-excel" style="color: #10B981;"></i>
                    <span>Excel</span>
                </a>
                <a class="dropdown-item" href="#" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; text-decoration: none; color: var(--text-dark);">
                    <i class="fas fa-file-csv" style="color: #F59E0B;"></i>
                    <span>CSV</span>
                </a>
                <a class="dropdown-item" href="#" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; text-decoration: none; color: var(--text-dark);">
                    <i class="fas fa-file-pdf" style="color: #EF4444;"></i>
                    <span>PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form Filter -->
    <form method="GET" action="{{ route('admin.nilai-kriteria.index') }}" id="filterForm" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <select name="tahun" id="filterTahun" style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 10px; background: white; font-size: 14px;">
            @foreach($tahunList as $thn)
            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
            @endforeach
        </select>

        <select name="status" id="filterStatus" style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 10px; background: white; font-size: 14px;">
            <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
            <option value="lengkap" {{ $status == 'lengkap' ? 'selected' : '' }}>Data Lengkap</option>
            <option value="belum_lengkap" {{ $status == 'belum_lengkap' ? 'selected' : '' }}>Belum Lengkap</option>
        </select>

        <button type="submit" class="btn-filter">
            <i class="fas fa-filter"></i> Filter
        </button>

        @if(request('tahun') || request('status'))
        <a href="{{ route('admin.nilai-kriteria.index') }}" class="btn-reset">
            <i class="fas fa-undo"></i> Reset
        </a>
        @endif
    </form>
</div>

<!-- Statistik Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="stat-card" style="background: white; border-left: 4px solid #3B82F6; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Total Jalan Dinilai</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['total_jalan_dinilai'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #EFF6FF; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-road" style="font-size: 24px; color: #3B82F6;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: white; border-left: 4px solid #10B981; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Data Lengkap</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['data_lengkap'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #D1FAE5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-check-circle" style="font-size: 24px; color: #10B981;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: white; border-left: 4px solid #F59E0B; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Belum Lengkap</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['belum_lengkap'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-clock" style="font-size: 24px; color: #F59E0B;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: white; border-left: 4px solid #8B5CF6; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Total Nilai</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['total_nilai'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #EDE9FE; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chart-line" style="font-size: 24px; color: #8B5CF6;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Table Data Group by Jalan -->
<div class="table-container">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode Jalan</th>
                    <th width="20%">Nama Jalan</th>
                    <th width="15%">Lokasi</th>
                    @foreach($kriteriaList as $krit)
                    <th width="8%" style="text-align: center;">
                        {{ $krit->kode }}
                        <br>
                        <small style="font-weight: normal;">({{ number_format($krit->bobot * 100, 0) }}%)</small>
                    </th>
                    @endforeach
                    <th width="8%">Status</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataNilai as $index => $item)
                <tr style="border-bottom: 1px solid var(--border);"
                    onmouseover="this.style.background='#FEF3E0'"
                    onmouseout="this.style.background='white'">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="badge-kode">{{ $item['jalan']->kode }}</span>
                    </td>
                    <td>
                        <strong>{{ $item['jalan']->nama }}</strong>
                    </td>
                    <td>
                        <i class="fas fa-map-marker-alt" style="color: var(--secondary); margin-right: 4px;"></i>
                        {{ $item['jalan']->lokasi }}
                    </td>

                    @foreach($kriteriaList as $krit)
                    @php
                    $nilaiItem = $item['nilai']->firstWhere('kriteria_id', $krit->id);
                    $nilai = $nilaiItem ? $nilaiItem->nilai : null;
                    $status = $nilaiItem ? $nilaiItem->status_validasi : null;
                    @endphp
                    <td class="text-center" style="background: {{ $nilai ? '#F8FAFC' : '#FEE2E2' }};">
                        @if($nilai)
                        <span class="nilai-value">{{ number_format($nilai, 2) }}</span>
                        @if($krit->satuan)
                        <small style="color: var(--text-lighter);">{{ $krit->satuan }}</small>
                        @endif
                        <br>
                        @if($status == 'divalidasi')
                        <span class="status-valid-small">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        @elseif($status == 'pending')
                        <span class="status-pending-small">
                            <i class="fas fa-clock"></i>
                        </span>
                        @elseif($status == 'ditolak')
                        <span class="status-invalid-small">
                            <i class="fas fa-times-circle"></i>
                        </span>
                        @endif
                        @else
                        <span class="status-empty">
                            <i class="fas fa-minus-circle"></i>
                            <span style="font-size: 11px;">Belum diisi</span>
                        </span>
                        @endif
                    </td>
                    @endforeach

                    <td class="text-center">
                        @php
                        $allFilled = true;
                        $hasPending = false;
                        $hasRejected = false;
                        foreach ($kriteriaList as $krit) {
                        $nilaiItem = $item['nilai']->firstWhere('kriteria_id', $krit->id);
                        if (!$nilaiItem) {
                        $allFilled = false;
                        } elseif ($nilaiItem->status_validasi == 'pending') {
                        $hasPending = true;
                        } elseif ($nilaiItem->status_validasi == 'ditolak') {
                        $hasRejected = true;
                        }
                        }
                        @endphp

                        @if($allFilled)
                        @if($hasPending)
                        <span class="status-pending">
                            <i class="fas fa-clock"></i> Menunggu Validasi
                        </span>
                        @elseif($hasRejected)
                        <span class="status-invalid">
                            <i class="fas fa-times-circle"></i> Ada Ditolak
                        </span>
                        @else
                        <span class="status-valid">
                            <i class="fas fa-check-circle"></i> Lengkap & Valid
                        </span>
                        @endif
                        @else
                        <span class="status-incomplete">
                            <i class="fas fa-exclamation-triangle"></i> Belum Lengkap
                        </span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        @php
                        // Ambil ID nilai pertama untuk edit (bisa edit semua kriteria sekaligus)
                        $nilaiId = null;
                        foreach ($item['nilai'] as $nilaiItem) {
                        $nilaiId = $nilaiItem->id;
                        break; // ambil ID pertama
                        }
                        @endphp

                        {{-- Tombol Edit -> mengarah ke halaman edit semua kriteria untuk jalan ini --}}
                        @if($nilaiId)
                        <a href="{{ route('admin.nilai-kriteria.edit', $nilaiId) }}"
                            class="btn-action btn-edit" title="Edit Semua Nilai">
                            <i class="fas fa-edit"></i>
                        </a>
                        @else
                        <a href="{{ route('admin.nilai-kriteria.create', ['jalan_id' => $item['jalan']->id, 'tahun' => $tahun]) }}"
                            class="btn-action btn-create" title="Input Nilai">
                            <i class="fas fa-plus"></i>
                        </a>
                        @endif

                        {{-- Tombol Detail -> mengarah ke halaman show --}}
                        @if($nilaiId)
                        <a href="{{ route('admin.nilai-kriteria.show', $nilaiId) }}"
                            class="btn-action btn-view" title="Detail Nilai">
                            <i class="fas fa-eye"></i>
                        </a>
                        @else
                        <a href="{{ route('admin.nilai-kriteria.create', ['jalan_id' => $item['jalan']->id, 'tahun' => $tahun]) }}"
                            class="btn-action btn-create" title="Input Nilai">
                            <i class="fas fa-plus"></i>
                        </a>
                        @endif

                        {{-- Tombol Hapus -> menghapus SEMUA nilai untuk jalan dan tahun ini --}}
                        @if($nilaiId)
                        <button type="button" class="btn-action btn-delete" onclick="confirmDeleteBulk({{ $item['jalan']->id }}, '{{ addslashes($item['jalan']->nama) }}', {{ $tahun }})" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 7 + count($kriteriaList) }}" class="text-center empty-state">
                        <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p style="margin-bottom: 8px;">Belum ada data nilai kriteria untuk tahun {{ $tahun }}</p>
                        <a href="{{ route('admin.nilai-kriteria.create') }}" class="btn-primary-sm">
                            <i class="fas fa-plus"></i> Input Nilai Baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($dataNilai instanceof \Illuminate\Pagination\LengthAwarePaginator && $dataNilai->hasPages())
    <div class="pagination-container">
        {{ $dataNilai->links() }}
    </div>
    @endif
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
    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
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
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3);
    }

    .btn-saw {
        background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%);
        color: white;
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

    .btn-saw:hover {
        transform: translateY(-2px);
        background: #2A3F54;
    }
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-delete:hover {
        background: #DC2626;
        color: white;
    }
    .btn-secondary {
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
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: var(--bg-light);
        border-color: var(--secondary);
    }

    .btn-filter,
    .btn-reset {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: var(--transition);
        border: none;
    }

    .btn-filter {
        background: var(--primary);
        color: white;
    }

    .btn-filter:hover {
        background: var(--primary-light);
    }

    .btn-reset {
        background: var(--bg-light);
        color: var(--text-light);
        text-decoration: none;
    }

    .btn-reset:hover {
        background: var(--border);
    }

    .btn-primary-sm {
        background: var(--secondary);
        color: var(--primary-dark);
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    /* Table Styles */
    .table-container {
        background: var(--bg-white);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .data-table thead tr {
        background: var(--bg-light);
        border-bottom: 2px solid var(--border);
    }

    .data-table th {
        padding: 14px 12px;
        text-align: left;
        font-weight: 700;
        font-size: 12px;
        color: var(--text-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-right: 1px solid var(--border);
    }

    .data-table th:last-child {
        border-right: none;
    }

    .data-table td {
        padding: 14px 12px;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: #FEF3E0;
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

    .nilai-value {
        font-weight: 700;
        font-size: 14px;
        color: var(--text-dark);
    }

    /* Status Styles */
    .status-valid {
        background: #D1FAE5;
        color: #059669;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-pending {
        background: #FEF3C7;
        color: #D97706;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-invalid {
        background: #FEE2E2;
        color: #DC2626;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-incomplete {
        background: #FEF3C7;
        color: #D97706;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-empty {
        color: #9CA3AF;
        font-size: 11px;
    }

    .status-valid-small,
    .status-pending-small,
    .status-invalid-small {
        font-size: 10px;
    }

    .status-valid-small {
        color: #10B981;
    }

    .status-pending-small {
        color: #F59E0B;
    }

    .status-invalid-small {
        color: #EF4444;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
        border: none;
    }

    .btn-view {
        background: #E0F2FE;
        color: #0284C7;
    }

    .btn-view:hover {
        background: #0284C7;
        color: white;
    }

    .btn-edit {
        background: #FEF3C7;
        color: #D97706;
    }

    .btn-edit:hover {
        background: #D97706;
        color: white;
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
        background: white;
        border-radius: 20px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }

    .detail-table th,
    .detail-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .detail-table th {
        background: var(--bg-light);
        font-weight: 600;
        width: 35%;
    }

    .btn-modal-cancel {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: var(--transition);
        border: 1px solid var(--border);
        background: white;
    }

    .btn-modal-cancel:hover {
        background: var(--bg-light);
    }

    /* Empty State */
    .empty-state {
        padding: 60px 20px !important;
        text-align: center;
        color: var(--text-light);
    }

    /* Pagination */
    .pagination-container {
        padding: 20px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
    }

    /* Stat Card */
    .stat-card {
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }
</style>

<script>
    let currentDeleteId = null;

    // ==================== DROPDOWN ====================
    function toggleDropdown() {
        const menu = document.getElementById('exportDropdownMenu');
        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.dropdown');
        const menu = document.getElementById('exportDropdownMenu');

        if (dropdown && !dropdown.contains(event.target)) {
            if (menu) menu.style.display = 'none';
        }
    });

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // ==================== FILTER ====================
    const tahunSelect = document.getElementById('filterTahun');
    const statusSelect = document.getElementById('filterStatus');

    if (tahunSelect) {
        tahunSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }

    // ==================== HAPUS SEMUA NILAI PER JALAN ====================
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
                    location.reload();
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
    // ==================== MODAL CLOSE ====================
    window.addEventListener('click', function(e) {
        const detailModal = document.getElementById('detailModal');
        if (e.target === detailModal) {
            closeDetailModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('detailModal')?.style.display === 'flex') {
                closeDetailModal();
            }
        }
    });

    // ==================== DROPDOWN HOVER ====================
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'var(--bg-light)';
        });
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'white';
        });
    });
</script>
@endsection