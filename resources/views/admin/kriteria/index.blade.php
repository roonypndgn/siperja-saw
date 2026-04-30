@extends('layouts.admin')

@section('title', 'Data Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Data Kriteria & Bobot')
@section('page-subtitle', 'Kelola kriteria penilaian dan bobot untuk metode SAW')

@section('content')
<div class="page-tools" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.kriteria.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Kriteria Baru
        </a>
    </div>
    
    <!-- Form Filter -->
    <form method="GET" action="{{ route('admin.kriteria.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <div class="search-box" style="position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-lighter);"></i>
            <input type="text" name="search" placeholder="Cari kode, nama, atau keterangan..." value="{{ request('search') }}" 
                   style="padding: 10px 16px 10px 40px; border: 1px solid var(--border); border-radius: 10px; width: 280px; font-size: 14px;">
        </div>
        
        <select name="tipe" style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 10px; background: white; font-size: 14px;">
            <option value="">Semua Tipe</option>
            <option value="benefit" {{ request('tipe') == 'benefit' ? 'selected' : '' }}>Benefit</option>
            <option value="cost" {{ request('tipe') == 'cost' ? 'selected' : '' }}>Cost</option>
        </select>
        
        <select name="status" style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 10px; background: white; font-size: 14px;">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        
        <button type="submit" class="btn-filter">
            <i class="fas fa-filter"></i> Filter
        </button>
        
        @if(request('search') || request('tipe') || request('status'))
            <a href="{{ route('admin.kriteria.index') }}" class="btn-reset">
                <i class="fas fa-undo"></i> Reset
            </a>
        @endif
    </form>
</div>

<!-- Alert Total Bobot -->
<div style="background: {{ $totalBobot == 1 ? '#D1FAE5' : ($totalBobot > 1 ? '#FEE2E2' : '#FEF3C7') }}; border-left: 4px solid {{ $totalBobot == 1 ? '#10B981' : ($totalBobot > 1 ? '#EF4444' : '#F59E0B') }}; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="font-size: 13px; color: {{ $totalBobot == 1 ? '#065F46' : ($totalBobot > 1 ? '#991B1B' : '#92400E') }}; margin-bottom: 4px;">
                <i class="fas fa-chart-pie"></i> Total Bobot Semua Kriteria Aktif
            </div>
            <div style="font-size: 24px; font-weight: 800; color: {{ $totalBobot == 1 ? '#10B981' : ($totalBobot > 1 ? '#EF4444' : '#F59E0B') }};">
                {{ number_format($totalBobot * 100, 0) }}%
                <span style="font-size: 14px; font-weight: normal;">({{ number_format($totalBobot, 2) }} / 1.00)</span>
            </div>
        </div>
        <div>
            @if($totalBobot == 1)
                <span style="background: #10B981; color: white; padding: 6px 16px; border-radius: 30px; font-size: 13px;">
                    <i class="fas fa-check-circle"></i> Bobot Seimbang
                </span>
            @elseif($totalBobot > 1)
                <span style="background: #EF4444; color: white; padding: 6px 16px; border-radius: 30px; font-size: 13px;">
                    <i class="fas fa-exclamation-triangle"></i> Bobot Melebihi 100%
                </span>
            @else
                <span style="background: #F59E0B; color: white; padding: 6px 16px; border-radius: 30px; font-size: 13px;">
                    <i class="fas fa-info-circle"></i> Bobot Kurang dari 100%
                </span>
            @endif
        </div>
    </div>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th width="80">No</th>
                <th width="100">Kode</th>
                <th>Nama Kriteria</th>
                <th width="120">Tipe</th>
                <th width="120">Bobot</th>
                <th>Keterangan</th>
                <th width="100">Status</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kriteria as $index => $item)
            <tr>
                <td class="text-center">{{ $index + $kriteria->firstItem() }}</td>
                <td>
                    <span class="badge-kode">{{ $item->kode }}</span>
                </td>
                <td>
                    <strong>{{ $item->nama }}</strong>
                    @if($item->satuan)
                        <br>
                        <small style="color: var(--text-light); font-size: 11px;">Satuan: {{ $item->satuan }}</small>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->tipe == 'benefit')
                        <span class="type-benefit">
                            <i class="fas fa-arrow-up"></i> Benefit
                        </span>
                    @else
                        <span class="type-cost">
                            <i class="fas fa-arrow-down"></i> Cost
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    <span style="font-weight: 700; font-size: 16px; color: var(--secondary);">{{ number_format($item->bobot * 100, 0) }}%</span>
                    <br>
                    <small style="color: var(--text-lighter);">({{ number_format($item->bobot, 2) }})</small>
                </td>
                <td>
                    <span style="color: var(--text-light); font-size: 13px;">
                        {{ Str::limit($item->keterangan, 50) ?: '-' }}
                    </span>
                </td>
                <td class="text-center">
                    @if($item->is_active)
                        <span class="status-active">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    @else
                        <span class="status-inactive">
                            <i class="fas fa-times-circle"></i> Nonaktif
                        </span>
                    @endif
                </td>
                <td class="action-buttons">
                    <a href="{{ route('admin.kriteria.show', $item->id) }}" class="btn-action btn-view" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.kriteria.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn-action btn-toggle" onclick="toggleStatus({{ $item->id }}, '{{ $item->nama }}', {{ $item->is_active ? 'true' : 'false' }})" 
                            title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                        <i class="fas {{ $item->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                    </button>
                    @if($item->nilaiKriteriaJalan()->count() == 0)
                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $item->id }}, '{{ $item->nama }}')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                    
                    <!-- Form untuk toggle status -->
                    <form id="toggle-form-{{ $item->id }}" action="{{ route('admin.kriteria.toggle-status', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    
                    <!-- Form untuk delete -->
                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.kriteria.destroy', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center empty-state">
                    <i class="fas fa-sliders-h" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p style="margin-bottom: 8px;">Belum ada data kriteria</p>
                    <a href="{{ route('admin.kriteria.create') }}" class="btn-primary-sm">
                        <i class="fas fa-plus"></i> Tambah Kriteria Pertama
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($kriteria->hasPages())
<div class="pagination-container">
    {{ $kriteria->links() }}
</div>
@endif

<!-- Modal Konfirmasi Hapus -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-trash-alt" style="font-size: 28px; color: #EF4444;"></i>
            </div>
            <h3 style="margin-bottom: 8px;">Hapus Data Kriteria</h3>
            <p style="color: var(--text-light); margin-bottom: 8px;">Apakah Anda yakin ingin menghapus kriteria:</p>
            <p style="font-weight: 700; margin-bottom: 16px;" id="deleteKriteriaName"></p>
            <p style="color: #EF4444; font-size: 13px; margin-bottom: 24px;">
                <i class="fas fa-exclamation-triangle"></i> Kriteria yang sudah memiliki data nilai tidak dapat dihapus
            </p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button id="cancelDelete" class="modal-btn modal-btn-cancel">Batal</button>
                <button id="confirmDelete" class="modal-btn modal-btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Toggle Status -->
<div class="modal-overlay" id="toggleModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEF3C7; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;" id="toggleIcon">
                <i class="fas fa-ban" style="font-size: 28px; color: #F59E0B;"></i>
            </div>
            <h3 style="margin-bottom: 8px;" id="toggleTitle">Nonaktifkan Kriteria</h3>
            <p style="color: var(--text-light); margin-bottom: 8px;" id="toggleMessage"></p>
            <div style="display: flex; gap: 12px; justify-content: center; margin-top: 24px;">
                <button id="cancelToggle" class="modal-btn modal-btn-cancel">Batal</button>
                <button id="confirmToggle" class="modal-btn modal-btn-warning">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
        min-width: 900px;
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
    .badge-kode {
        background: var(--primary-lighter);
        color: var(--primary);
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    /* Type Styles */
    .type-benefit {
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
    
    .type-cost {
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
    
    /* Status Styles */
    .status-active {
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
    
    .status-inactive {
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
    
    .btn-edit {
        background: #FEF3C7;
        color: #D97706;
    }
    
    .btn-edit:hover {
        background: #D97706;
        color: white;
    }
    
    .btn-toggle {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .btn-toggle:hover {
        background: #DC2626;
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
    
    /* Empty State */
    .empty-state {
        padding: 60px 20px !important;
        text-align: center;
        color: var(--text-light);
    }
    
    /* Pagination */
    .pagination-container {
        margin-top: 24px;
        display: flex;
        justify-content: flex-end;
    }
    
    .pagination-container nav {
        display: inline-block;
    }
    
    .pagination-container .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
    }
    
    .pagination-container .page-item .page-link {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-dark);
        text-decoration: none;
        transition: var(--transition);
    }
    
    .pagination-container .page-item.active .page-link {
        background: var(--secondary);
        border-color: var(--secondary);
        color: var(--primary-dark);
    }
    
    .pagination-container .page-item:hover .page-link {
        background: var(--secondary-lighter);
        border-color: var(--secondary);
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
    
    .modal-btn-warning {
        background: var(--secondary);
        color: var(--primary-dark);
    }
    
    .modal-btn-warning:hover {
        background: var(--secondary-dark);
    }
</style>
@endpush

@push('scripts')
<script>
    let deleteId = null;
    let toggleId = null;
    let toggleStatusValue = null;
    let toggleName = null;
    
    // Fungsi konfirmasi hapus
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteKriteriaName').innerHTML = '<strong>"' + name + '"</strong>';
        document.getElementById('deleteModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    // Fungsi toggle status
    function toggleStatus(id, name, currentStatus) {
        toggleId = id;
        toggleName = name;
        toggleStatusValue = currentStatus;
        
        const modal = document.getElementById('toggleModal');
        const title = document.getElementById('toggleTitle');
        const message = document.getElementById('toggleMessage');
        const icon = document.getElementById('toggleIcon');
        
        if (currentStatus) {
            title.innerHTML = 'Nonaktifkan Kriteria';
            message.innerHTML = 'Apakah Anda yakin ingin menonaktifkan kriteria <strong>"' + name + '"</strong>?<br>Kriteria nonaktif tidak akan digunakan dalam perhitungan SAW.';
            icon.innerHTML = '<i class="fas fa-ban" style="font-size: 28px; color: #F59E0B;"></i>';
        } else {
            title.innerHTML = 'Aktifkan Kriteria';
            message.innerHTML = 'Apakah Anda yakin ingin mengaktifkan kembali kriteria <strong>"' + name + '"</strong>?<br>Pastikan total bobot tidak melebihi 100%.';
            icon.innerHTML = '<i class="fas fa-check-circle" style="font-size: 28px; color: #10B981;"></i>';
        }
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    // Event listener untuk hapus
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
    
    // Event listener untuk toggle status
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
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        const deleteModal = document.getElementById('deleteModal');
        const toggleModal = document.getElementById('toggleModal');
        
        if (e.target === deleteModal) {
            deleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            deleteId = null;
        }
        
        if (e.target === toggleModal) {
            toggleModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            toggleId = null;
        }
    });
    
    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('deleteModal').style.display === 'flex') {
                document.getElementById('deleteModal').style.display = 'none';
                document.body.style.overflow = 'auto';
                deleteId = null;
            }
            if (document.getElementById('toggleModal').style.display === 'flex') {
                document.getElementById('toggleModal').style.display = 'none';
                document.body.style.overflow = 'auto';
                toggleId = null;
            }
        }
    });
    
    // Fungsi toggle dropdown
    function toggleDropdown() {
        const menu = document.getElementById('exportDropdownMenu');
        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    }
    
    // Tutup dropdown jika klik di luar
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.dropdown');
        const menu = document.getElementById('exportDropdownMenu');
        
        if (dropdown && !dropdown.contains(event.target)) {
            if (menu) {
                menu.style.display = 'none';
            }
        }
    });
    
    // Hover effect untuk dropdown items
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'var(--bg-light)';
        });
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'white';
        });
    });
</script>
@endpush