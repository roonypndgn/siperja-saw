@extends('layouts.admin')

@section('title', 'Data Jalan - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Data Jalan')
@section('page-subtitle', 'Kelola data jalan yang terdaftar dalam sistem')

@section('content')
<div class="page-tools" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.jalan.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Jalan Baru
        </a>
        
        <!-- Dropdown Export -->
        <div class="dropdown" style="position: relative;">
            <button class="btn-secondary dropdown-toggle" type="button" id="exportDropdown" onclick="toggleDropdown()">
                <i class="fas fa-download"></i> Export Data
                <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 12px;"></i>
            </button>
            <div class="dropdown-menu" id="exportDropdownMenu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-width: 180px; z-index: 1000;">
                <a class="dropdown-item" href="{{ route('admin.jalan.export.excel', request()->query()) }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; text-decoration: none; color: var(--text-dark); transition: var(--transition);">
                    <i class="fas fa-file-excel" style="color: #10B981; font-size: 16px;"></i>
                    <span>Excel (.xlsx)</span>
                </a>
                <a class="dropdown-item" href="{{ route('admin.jalan.export.csv', request()->query()) }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; text-decoration: none; color: var(--text-dark); transition: var(--transition);">
                    <i class="fas fa-file-csv" style="color: #F59E0B; font-size: 16px;"></i>
                    <span>CSV</span>
                </a>
                <a class="dropdown-item" href="{{ route('admin.jalan.export.pdf', request()->query()) }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px; text-decoration: none; color: var(--text-dark); transition: var(--transition);">
                    <i class="fas fa-file-pdf" style="color: #EF4444; font-size: 16px;"></i>
                    <span>PDF</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Form Filter -->
    <form method="GET" action="{{ route('admin.jalan.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <div class="search-box" style="position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-lighter);"></i>
            <input type="text" name="search" placeholder="Cari kode, nama, atau lokasi..." value="{{ request('search') }}" 
                   style="padding: 10px 16px 10px 40px; border: 1px solid var(--border); border-radius: 10px; width: 280px; font-size: 14px;">
        </div>
        
        <select name="status" style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 10px; background: white; font-size: 14px;">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        
        <button type="submit" class="btn-filter">
            <i class="fas fa-filter"></i> Filter
        </button>
        
        @if(request('search') || request('status'))
            <a href="{{ route('admin.jalan.index') }}" class="btn-reset">
                <i class="fas fa-undo"></i> Reset
            </a>
        @endif
    </form>
</div>

<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th width="80">No</th>
                <th width="100">Kode</th>
                <th>Nama Jalan</th>
                <th>Lokasi</th>
                <th width="120">Panjang (m)</th>
                <th width="100">Status</th>
                <th width="150">Dibuat</th>
                <th width="180">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jalan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + $jalan->firstItem() }}</td>
                <td>
                    <span class="badge-kode">{{ $item->kode }}</span>
                </td>
                <td>
                    <strong>{{ $item->nama }}</strong>
                    @if($item->deskripsi)
                        <br>
                        <small style="color: var(--text-light); font-size: 11px;">{{ Str::limit($item->deskripsi, 50) }}</small>
                    @endif
                </td>
                <td>
                    <i class="fas fa-map-marker-alt" style="color: var(--secondary); margin-right: 6px;"></i>
                    {{ $item->lokasi }}
                </td>
                <td class="text-center">
                    {{ number_format($item->panjang, 0, ',', '.') }} m
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
                <td class="text-center">
                    <small>
                        {{ $item->created_at->format('d/m/Y') }}
                        <br>
                        <span style="color: var(--text-lighter); font-size: 10px;">{{ $item->created_at->timezone('Asia/Jakarta')->format('H:i') }}</span>
                    </small>
                </td>
                <td class="action-buttons">
                    <a href="{{ route('admin.jalan.show', $item->id) }}" class="btn-action btn-view" title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.jalan.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" class="btn-action btn-toggle" onclick="toggleStatus({{ $item->id }}, '{{ $item->nama }}', {{ $item->is_active ? 'true' : 'false' }})" title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                        <i class="fas {{ $item->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                    </button>
                    <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $item->id }}, '{{ $item->nama }}')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                    
                    <!-- Form untuk toggle status -->
                    <form id="toggle-form-{{ $item->id }}" action="{{ route('admin.jalan.toggle-status', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                    </form>
                    
                    <!-- Form untuk delete -->
                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.jalan.destroy', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center empty-state">
                    <i class="fas fa-road" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p style="margin-bottom: 8px;">Belum ada data jalan</p>
                    <a href="{{ route('admin.jalan.create') }}" class="btn-primary-sm">
                        <i class="fas fa-plus"></i> Tambah Jalan Pertama
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($jalan->hasPages())
<div class="pagination-container">
    {{ $jalan->links() }}
</div>
@endif

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
                <i class="fas fa-ban" style="font-size: 28px; color: #F59E0B;"></i>
            </div>
            <h3 style="margin-bottom: 8px;" id="toggleTitle">Nonaktifkan Jalan</h3>
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
        min-width: 800px;
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
    }
    
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
        document.getElementById('deleteJalanName').innerHTML = '<strong>"' + name + '"</strong>';
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
            title.innerHTML = 'Nonaktifkan Jalan';
            message.innerHTML = 'Apakah Anda yakin ingin menonaktifkan jalan <strong>"' + name + '"</strong>?<br>Jalan nonaktif tidak akan muncul dalam perhitungan SAW.';
            icon.innerHTML = '<i class="fas fa-ban" style="font-size: 28px; color: #F59E0B;"></i>';
        } else {
            title.innerHTML = 'Aktifkan Jalan';
            message.innerHTML = 'Apakah Anda yakin ingin mengaktifkan kembali jalan <strong>"' + name + '"</strong>?<br>Jalan aktif akan masuk dalam perhitungan SAW.';
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
            document.getElementById('deleteModal').style.display = 'none';
            document.getElementById('toggleModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            deleteId = null;
            toggleId = null;
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