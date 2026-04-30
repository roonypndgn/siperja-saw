@extends('layouts.admin')

@section('title', 'Data Nilai Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Data Nilai Kriteria')
@section('page-subtitle', 'Kelola nilai penilaian untuk setiap kriteria jalan')

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
            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="divalidasi" {{ $status == 'divalidasi' ? 'selected' : '' }}>Tervalidasi</option>
            <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
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
                <div style="font-size: 13px; color: var(--text-light);">Total Penilaian</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['total'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #EFF6FF; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chart-line" style="font-size: 24px; color: #3B82F6;"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-left: 4px solid #F59E0B; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Menunggu Validasi</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['pending'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-clock" style="font-size: 24px; color: #F59E0B;"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-left: 4px solid #10B981; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Tervalidasi</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['divalidasi'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #D1FAE5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-check-circle" style="font-size: 24px; color: #10B981;"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-left: 4px solid #EF4444; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Ditolak</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['ditolak'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #FEE2E2; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-times-circle" style="font-size: 24px; color: #EF4444;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Table Data -->
<div class="table-container">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="50">
                        <input type="checkbox" id="selectAll" style="width: 18px; height: 18px; cursor: pointer;">
                    </th>
                    <th width="60">No</th>
                    <th>Nama Jalan</th>
                    <th>Kriteria</th>
                    <th width="100">Nilai</th>
                    <th width="100">Tahun</th>
                    <th width="120">Status</th>
                    <th>Catatan</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilai as $index => $item)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" class="select-item" value="{{ $item->id }}" style="width: 18px; height: 18px; cursor: pointer;">
                    </td>
                    <td class="text-center">{{ $index + $nilai->firstItem() }}</td>
                    <td>
                        <strong>{{ $item->jalan->nama ?? '-' }}</strong>
                        <br>
                        <small style="color: var(--text-lighter);">{{ $item->jalan->kode ?? '-' }}</small>
                    </td>
                    <td>
                        <span class="badge-kriteria">{{ $item->kriteria->kode ?? '-' }}</span>
                        <br>
                        <small>{{ $item->kriteria->nama ?? '-' }}</small>
                        <br>
                        <small style="color: var(--text-lighter);">
                            <i class="fas {{ $item->kriteria->tipe == 'benefit' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            {{ ucfirst($item->kriteria->tipe ?? '-') }}
                        </small>
                    </td>
                    <td class="text-center">
                        <span class="nilai-badge">{{ number_format($item->nilai, 2) }}</span>
                        @if($item->kriteria && $item->kriteria->satuan)
                            <small style="color: var(--text-lighter);">{{ $item->kriteria->satuan }}</small>
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
                    <td>
                        @if($item->catatan)
                            <span style="color: var(--text-light); font-size: 12px;">
                                {{ Str::limit($item->catatan, 50) }}
                            </span>
                        @else
                            <span style="color: var(--text-lighter); font-style: italic;">-</span>
                        @endif
                    </td>
                    <td class="action-buttons">
                        <a href="{{ route('admin.nilai-kriteria.show', $item->id) }}" class="btn-action btn-view" title="Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($item->status_validasi == 'pending')
                            <button type="button" class="btn-action btn-validate" 
                                    onclick="openValidateModal({{ $item->id }}, '{{ addslashes($item->jalan->nama) }}', '{{ addslashes($item->kriteria->nama) }}', {{ $item->nilai }})" 
                                    title="Validasi">
                                <i class="fas fa-check-double"></i>
                            </button>
                        @endif
                        <a href="{{ route('admin.nilai-kriteria.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn-action btn-delete" 
                                onclick="confirmDelete({{ $item->id }}, '{{ addslashes($item->jalan->nama) }} - {{ addslashes($item->kriteria->nama) }}')" 
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                        
                        <!-- Form Delete -->
                        <form id="delete-form-{{ $item->id }}" 
                              action="{{ route('admin.nilai-kriteria.destroy', $item->id) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center empty-state">
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
    
    <!-- Mass Action Bar -->
    @if($statistik['pending'] > 0)
    <div id="massActionBar" style="display: none; padding: 16px 20px; border-top: 1px solid var(--border); background: #FEF3E0; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <strong id="selectedCount">0</strong> data dipilih
        </div>
        <div style="display: flex; gap: 12px;">
            <button onclick="validateSelected('divalidasi')" class="btn-mass-validate">
                <i class="fas fa-check-circle"></i> Validasi Terpilih
            </button>
            <button onclick="validateSelected('ditolak')" class="btn-mass-reject">
                <i class="fas fa-times-circle"></i> Tolak Terpilih
            </button>
            <button onclick="clearSelection()" class="btn-mass-cancel">
                <i class="fas fa-undo"></i> Batal
            </button>
        </div>
    </div>
    @endif
    
    @if($nilai->hasPages())
    <div class="pagination-container">
        {{ $nilai->links() }}
    </div>
    @endif
</div>

<!-- Modal Validasi -->
<div class="modal-overlay" id="validateModal" style="display: none;">
    <div class="modal-content" style="max-width: 450px;">
        <div style="padding: 24px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="width: 60px; height: 60px; background: #FEF3C7; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-check-double" style="font-size: 28px; color: #F59E0B;"></i>
                </div>
                <h3 style="margin-bottom: 8px;">Validasi Data Nilai</h3>
                <p style="color: var(--text-light);" id="validateInfo"></p>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan Validasi</label>
                <textarea id="validateCatatan" rows="3" class="form-control" placeholder="Masukkan catatan jika diperlukan..."></textarea>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeValidateModal()" class="btn-modal-cancel">Batal</button>
                <button type="button" onclick="submitValidation('divalidasi')" class="btn-modal-validate" style="background: #10B981;">
                    <i class="fas fa-check-circle"></i> Setujui
                </button>
                <button type="button" onclick="submitValidation('ditolak')" class="btn-modal-reject" style="background: #EF4444;">
                    <i class="fas fa-times-circle"></i> Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-trash-alt" style="font-size: 28px; color: #EF4444;"></i>
            </div>
            <h3 style="margin-bottom: 8px;">Hapus Data</h3>
            <p style="color: var(--text-light); margin-bottom: 8px;">Apakah Anda yakin ingin menghapus data:</p>
            <p style="font-weight: 700; margin-bottom: 16px;" id="deleteInfo"></p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" onclick="closeDeleteModal()" class="modal-btn-cancel">Batal</button>
                <button type="button" onclick="submitDelete()" class="modal-btn-danger">Ya, Hapus</button>
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
    
    .btn-filter, .btn-reset {
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
        min-width: 1000px;
    }
    
    .data-table thead tr {
        background: var(--bg-light);
        border-bottom: 1px solid var(--border);
    }
    
    .data-table th {
        padding: 16px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .data-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
        vertical-align: middle;
    }
    
    .data-table tbody tr:hover {
        background: var(--bg-light);
    }
    
    .text-center {
        text-align: center;
    }
    
    /* Badge Styles */
    .badge-kriteria {
        background: var(--primary-lighter);
        color: var(--primary);
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    
    .nilai-badge {
        background: #FEF3E0;
        color: var(--secondary-dark);
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 14px;
    }
    
    /* Status Styles */
    .status-valid {
        background: #D1FAE5;
        color: #059669;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
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
        font-size: 12px;
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
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
    
    .btn-validate {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-validate:hover {
        background: #D97706;
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
    
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-delete:hover {
        background: #DC2626;
        color: white;
    }
    
    /* Mass Action */
    .btn-mass-validate {
        background: #10B981;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-mass-reject {
        background: #EF4444;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-mass-cancel {
        background: var(--bg-light);
        color: var(--text-dark);
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid var(--border);
        cursor: pointer;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Modal Buttons */
    .modal-btn-cancel, .btn-modal-cancel {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: var(--transition);
        border: 1px solid var(--border);
        background: white;
    }
    
    .modal-btn-cancel:hover, .btn-modal-cancel:hover {
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
    
    .btn-modal-validate {
        background: #10B981;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-modal-validate:hover {
        background: #059669;
        transform: translateY(-2px);
    }
    
    .btn-modal-reject {
        background: #EF4444;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-modal-reject:hover {
        background: #DC2626;
        transform: translateY(-2px);
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
    
    /* Form */
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--secondary);
    }
</style>

<script>
    let selectedItems = [];
    let deleteId = null;
    let validateId = null;
    
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
    
    // ==================== SELECT ALL ====================
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.select-item');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
            updateSelectedCount();
        });
    }
    
    document.querySelectorAll('.select-item').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedCount();
            const allChecked = document.querySelectorAll('.select-item:checked').length === document.querySelectorAll('.select-item').length;
            if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
        });
    });
    
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.select-item:checked');
        const count = checkboxes.length;
        const massBar = document.getElementById('massActionBar');
        
        if (count > 0 && massBar) {
            massBar.style.display = 'flex';
            const selectedCountSpan = document.getElementById('selectedCount');
            if (selectedCountSpan) selectedCountSpan.innerText = count;
        } else if (massBar) {
            massBar.style.display = 'none';
        }
        
        selectedItems = Array.from(checkboxes).map(cb => cb.value);
    }
    
    function clearSelection() {
        document.querySelectorAll('.select-item').forEach(cb => cb.checked = false);
        if (selectAllCheckbox) selectAllCheckbox.checked = false;
        updateSelectedCount();
    }
    
    // ==================== VALIDASI MASSAL ====================
    function validateSelected(status) {
        if (selectedItems.length === 0) {
            alert('Tidak ada data yang dipilih');
            return;
        }
        
        const statusText = status === 'divalidasi' ? 'memvalidasi' : 'menolak';
        if (confirm(`Apakah Anda yakin ingin ${statusText} ${selectedItems.length} data?`)) {
            fetch('{{ route("admin.nilai-kriteria.validate-mass") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ids: selectedItems,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else if (data.error) {
                    alert('Error: ' + JSON.stringify(data.error));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat validasi massal');
            });
        }
    }
    
    // ==================== VALIDASI SATUAN ====================
    function openValidateModal(id, jalanNama, kriteriaNama, nilai) {
        validateId = id;
        const validateInfo = document.getElementById('validateInfo');
        if (validateInfo) {
            validateInfo.innerHTML = `Jalan: <strong>${jalanNama}</strong><br>Kriteria: <strong>${kriteriaNama}</strong><br>Nilai: <strong>${nilai}</strong>`;
        }
        
        const catatanTextarea = document.getElementById('validateCatatan');
        if (catatanTextarea) catatanTextarea.value = '';
        
        const modal = document.getElementById('validateModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeValidateModal() {
        const modal = document.getElementById('validateModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        validateId = null;
    }
    
    function submitValidation(status) {
        if (!validateId) {
            alert('ID validasi tidak ditemukan');
            return;
        }
        
        const catatan = document.getElementById('validateCatatan')?.value || '';
        const statusText = status === 'divalidasi' ? 'divalidasi' : 'ditolak';
        
        fetch(`/admin/nilai-kriteria/${validateId}/validate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: statusText,
                catatan_validasi: catatan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else if (data.error) {
                alert('Error: ' + JSON.stringify(data.error));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat validasi');
        });
    }
    
    // ==================== DELETE ====================
    function confirmDelete(id, info) {
        deleteId = id;
        const deleteInfo = document.getElementById('deleteInfo');
        if (deleteInfo) {
            deleteInfo.innerHTML = `<strong>"${info}"</strong>`;
        }
        
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
    function submitDelete() {
        if (!deleteId) {
            alert('ID tidak ditemukan');
            return;
        }
        
        const form = document.getElementById(`delete-form-${deleteId}`);
        if (form) {
            form.submit();
        } else {
            fetch(`/admin/nilai-kriteria/${deleteId}`, {
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
                    alert('Gagal menghapus data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
            });
        }
        
        closeDeleteModal();
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        deleteId = null;
    }
    
    // ==================== MODAL CLOSE ON OUTSIDE CLICK ====================
    window.addEventListener('click', function(e) {
        const deleteModal = document.getElementById('deleteModal');
        const validateModal = document.getElementById('validateModal');
        
        if (e.target === deleteModal) {
            closeDeleteModal();
        }
        if (e.target === validateModal) {
            closeValidateModal();
        }
    });
    
    // ==================== ESC KEY ====================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('deleteModal')?.style.display === 'flex') {
                closeDeleteModal();
            }
            if (document.getElementById('validateModal')?.style.display === 'flex') {
                closeValidateModal();
            }
        }
    });
    
    // ==================== FILTER TAHUN ====================
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
    
    // ==================== DROPDOWN ITEMS HOVER ====================
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