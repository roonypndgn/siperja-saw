@extends('layouts.petugas')

@section('title', 'Data Jalan - Petugas')
@section('page-title', 'Data Jalan')
@section('page-subtitle', 'Kelola data jalan yang akan dinilai')

@section('content')
<div class="stat-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('petugas.jalan.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Jalan
            </a>
            <a href="{{ route('petugas.jalan.index') }}" class="btn-outline">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
        </div>
        
        <!-- Search Form -->
        <form method="GET" action="{{ route('petugas.jalan.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <div class="search-box" style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9CA3AF;"></i>
                <input type="text" name="search" placeholder="Cari kode, nama, atau lokasi..." 
                       value="{{ request('search') }}"
                       style="padding: 10px 16px 10px 40px; border: 1px solid #E2E8F0; border-radius: 10px; width: 280px;">
            </div>
            
            <select name="status" style="padding: 10px 16px; border: 1px solid #E2E8F0; border-radius: 10px; background: white;">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            
            <button type="submit" class="btn-secondary" style="padding: 10px 20px;">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="alert-success" style="background: #D1FAE5; border-left: 4px solid #10B981; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-check-circle" style="color: #10B981; font-size: 20px;"></i>
    <span style="color: #065F46;">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="alert-error" style="background: #FEE2E2; border-left: 4px solid #EF4444; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-exclamation-circle" style="color: #EF4444; font-size: 20px;"></i>
    <span style="color: #991B1B;">{{ session('error') }}</span>
</div>
@endif

@if($errors->any())
<div class="alert-error" style="background: #FEE2E2; border-left: 4px solid #EF4444; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px;">
    <ul style="margin-left: 20px; color: #991B1B;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Table Data -->
<div class="stat-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0;">
                    <th style="padding: 15px; text-align: center; width: 5%;">NO</th>
                    <th style="padding: 15px; text-align: left; width: 10%;">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'kode', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" style="color: #1A2A3A; text-decoration: none;">
                            KODE
                            @if(request('sort_by') == 'kode')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th style="padding: 15px; text-align: left; width: 20%;">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nama', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" style="color: #1A2A3A; text-decoration: none;">
                            NAMA JALAN
                            @if(request('sort_by') == 'nama')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th style="padding: 15px; text-align: left; width: 25%;">LOKASI</th>
                    <th style="padding: 15px; text-align: right; width: 10%;">
                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'panjang', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" style="color: #1A2A3A; text-decoration: none;">
                            PANJANG (m)
                            @if(request('sort_by') == 'panjang')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </a>
                    </th>
                    <th style="padding: 15px; text-align: center; width: 10%;">STATUS</th>
                    <th style="padding: 15px; text-align: center; width: 20%;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jalan as $index => $j)
                <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" onmouseover="this.style.background='#FEF3E0'" onmouseout="this.style.background='white'">
                    <td style="padding: 14px; text-align: center;">{{ $jalan->firstItem() + $index }}</td>
                    <td style="padding: 14px;">
                        <span style="font-weight: 700; color: #1A2A3A;">{{ $j->kode }}</span>
                    </td>
                    <td style="padding: 14px;">
                        <strong>{{ $j->nama }}</strong>
                        @if($j->deskripsi)
                            <br><small style="color: #6B7280; font-size: 11px;">{{ Str::limit($j->deskripsi, 50) }}</small>
                        @endif
                    </td>
                    <td style="padding: 14px;">
                        <i class="fas fa-map-marker-alt" style="color: #F9A826; margin-right: 6px;"></i>
                        {{ $j->lokasi }}
                    </td>
                    <td style="padding: 14px; text-align: right;">
                        <span style="font-weight: 600;">{{ number_format($j->panjang, 0, ',', '.') }}</span>
                        <span style="font-size: 11px; color: #6B7280;"> m</span>
                    </td>
                    <td style="padding: 14px; text-align: center;">
                        @if($j->is_active)
                            <span style="background: #10B981; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                <i class="fas fa-check-circle"></i> AKTIF
                            </span>
                        @else
                            <span style="background: #EF4444; color: white; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">
                                <i class="fas fa-times-circle"></i> NONAKTIF
                            </span>
                        @endif
                    </td>
                    <td style="padding: 14px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('petugas.jalan.show', $j->id) }}" 
                               style="background: #E8EDF2; color: #1A2A3A; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; transition: all 0.2s;"
                               onmouseover="this.style.background='#F9A826'; this.style.color='white'"
                               onmouseout="this.style.background='#E8EDF2'; this.style.color='#1A2A3A'">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('petugas.jalan.edit', $j->id) }}" 
                               style="background: #FEF3E0; color: #F9A826; padding: 6px 12px; border-radius: 8px; text-decoration: none; font-size: 12px; transition: all 0.2s;"
                               onmouseover="this.style.background='#F9A826'; this.style.color='white'"
                               onmouseout="this.style.background='#FEF3E0'; this.style.color='#F9A826'">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button type="button" onclick="confirmDelete({{ $j->id }}, '{{ $j->nama }}')" 
                                    style="background: #FEE2E2; color: #EF4444; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 12px; transition: all 0.2s;"
                                    onmouseover="this.style.background='#EF4444'; this.style.color='white'"
                                    onmouseout="this.style.background='#FEE2E2'; this.style.color='#EF4444'">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 60px; text-align: center; color: #6B7280;">
                        <i class="fas fa-road" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p style="font-size: 14px;">Belum ada data jalan</p>
                        <a href="{{ route('petugas.jalan.create') }}" style="display: inline-block; margin-top: 12px; background: #F9A826; color: #1A2A3A; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">
                            <i class="fas fa-plus"></i> Tambah Jalan Pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($jalan->hasPages())
    <div style="padding: 20px; border-top: 1px solid #E2E8F0;">
        {{ $jalan->links() }}
    </div>
    @endif
</div>

<!-- Modal Hapus -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; width: 90%; max-width: 400px; overflow: hidden;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-trash-alt" style="font-size: 28px; color: #EF4444;"></i>
            </div>
            <h3 style="margin-bottom: 8px;">Konfirmasi Hapus</h3>
            <p id="deleteMessage" style="color: #6B7280; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus data ini?</p>
            <form id="deleteForm" method="POST" style="display: flex; gap: 12px; justify-content: center;">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal()" style="padding: 10px 20px; border: 1px solid #E2E8F0; background: white; border-radius: 10px; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 10px 20px; background: #EF4444; color: white; border: none; border-radius: 10px; cursor: pointer;">Ya, Hapus</button>
            </form>
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
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary:hover {
        background: #E8912A;
        transform: translateY(-2px);
    }
    .btn-secondary {
        background: #E8EDF2;
        color: #1A2A3A;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }
    .btn-secondary:hover {
        background: #D1D9E6;
    }
    .btn-outline {
        background: transparent;
        color: #1A2A3A;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #E2E8F0;
    }
    .btn-outline:hover {
        border-color: #F9A826;
        color: #F9A826;
    }
</style>

<script>
    let deleteId = null;
    
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus jalan <strong>${name}</strong>?`;
        document.getElementById('deleteForm').action = `/petugas/jalan/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection