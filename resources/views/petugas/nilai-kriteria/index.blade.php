@extends('layouts.petugas')

@section('title', 'Data Nilai Kriteria - Petugas')
@section('page-title', 'Data Nilai Kriteria')
@section('page-subtitle', 'Kelola nilai penilaian untuk setiap kriteria jalan')

@section('content')
<div class="stat-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('petugas.nilai-kriteria.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Input Nilai Baru
            </a>
            <a href="{{ route('petugas.nilai-kriteria.index') }}" class="btn-outline">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
        </div>
        
        <!-- Search & Filter Form -->
        <form method="GET" action="{{ route('petugas.nilai-kriteria.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <select name="tahun" class="filter-select">
                @foreach($tahunList as $thn)
                    <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
                @endforeach
            </select>
            
            <button type="submit" class="btn-secondary" style="padding: 10px 20px;">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>
</div>

<!-- Statistik Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="stat-card" style="background: white; border-left: 4px solid #3B82F6; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Total Jalan Dinilai</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['total_jalan'] }}</div>
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
                <div style="font-size: 13px; color: var(--text-light);">Menunggu Validasi</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['pending_validasi'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-clock" style="font-size: 24px; color: #F59E0B;"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-left: 4px solid #8B5CF6; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Sudah Divalidasi</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['sudah_divalidasi'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #EDE9FE; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-certificate" style="font-size: 24px; color: #8B5CF6;"></i>
            </div>
        </div>
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

<!-- Table Data -->
<div class="stat-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0;">
                    <th style="padding: 15px; text-align: center; width: 5%;">NO</th>
                    <th style="padding: 15px; text-align: left; width: 20%;">NAMA JALAN</th>
                    <th style="padding: 15px; text-align: left; width: 25%;">LOKASI</th>
                    <th style="padding: 15px; text-align: center; width: 12%;">STATUS NILAI</th>
                    <th style="padding: 15px; text-align: center; width: 13%;">STATUS VALIDASI</th>
                    <th style="padding: 15px; text-align: center; width: 25%;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataNilai as $index => $item)
                @php
                    $nilaiCount = $item['nilai']->count();
                    $kriteriaCount = $kriteriaList->count();
                    $isComplete = $nilaiCount >= $kriteriaCount;
                    
                    $pendingCount = $item['nilai']->where('status_validasi', 'pending')->count();
                    $divalidasiCount = $item['nilai']->where('status_validasi', 'divalidasi')->count();
                    $ditolakCount = $item['nilai']->where('status_validasi', 'ditolak')->count();
                    
                    $hasData = $nilaiCount > 0;
                @endphp
                <tr style="border-bottom: 1px solid #E2E8F0; transition: background 0.2s;" 
                    onmouseover="this.style.background='#FEF3E0'" 
                    onmouseout="this.style.background='white'">
                    <td style="padding: 14px; text-align: center;">{{ $loop->iteration + (($dataNilai instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dataNilai->firstItem() - 1 : 0)) }}</td>
                    <td style="padding: 14px;">
                        <strong>{{ $item['jalan']->nama }}</strong>
                        <br>
                        <small style="color: #6B7280; font-size: 11px;">{{ $item['jalan']->kode }}</small>
                    </td>
                    <td style="padding: 14px;">
                        <i class="fas fa-map-marker-alt" style="color: #F9A826; margin-right: 6px;"></i>
                        {{ $item['jalan']->lokasi }}
                    </td>
                    <td style="padding: 14px; text-align: center;">
                        @if($isComplete)
                            <span style="background: #D1FAE5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fas fa-check-circle"></i> Lengkap
                            </span>
                        @else
                            <span style="background: #FEF3C7; color: #D97706; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fas fa-exclamation-triangle"></i> Belum Lengkap
                            </span>
                        @endif
                        <br>
                        <small style="color: #6B7280; font-size: 10px;">{{ $nilaiCount }}/{{ $kriteriaCount }} kriteria</small>
                    </td>
                    <td style="padding: 14px; text-align: center;">
                        @if($hasData)
                            @if($pendingCount > 0)
                                <span style="background: #FEF3C7; color: #D97706; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @elseif($ditolakCount > 0)
                                <span style="background: #FEE2E2; color: #DC2626; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @elseif($divalidasiCount > 0)
                                <span style="background: #D1FAE5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-check-circle"></i> Divalidasi
                                </span>
                            @endif
                        @else
                            <span style="color: #9CA3AF; font-style: italic; font-size: 12px;">
                                <i class="fas fa-minus-circle"></i> Belum diisi
                            </span>
                        @endif
                    </td>
                    <td style="padding: 14px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            @if($hasData)
                                <a href="{{ route('petugas.nilai-kriteria.show', $item['nilai']->first()->id ?? 0) }}" 
                                   class="btn-action btn-view"
                                   title="Lihat Detail Nilai">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('petugas.nilai-kriteria.edit', $item['nilai']->first()->id ?? 0) }}" 
                                   class="btn-action btn-edit"
                                   title="Edit Nilai">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @else
                                <a href="{{ route('petugas.nilai-kriteria.create', ['jalan_id' => $item['jalan']->id, 'tahun' => $tahun]) }}" 
                                   class="btn-action btn-create"
                                   title="Input Nilai">
                                    <i class="fas fa-plus"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    <table>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 60px; text-align: center; color: #6B7280;">
                        <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p style="font-size: 14px;">Belum ada data nilai kriteria untuk tahun {{ $tahun }}</p>
                        <a href="{{ route('petugas.nilai-kriteria.create') }}" style="display: inline-block; margin-top: 12px; background: #F9A826; color: #1A2A3A; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">
                            <i class="fas fa-plus"></i> Input Nilai Pertama
                        </a>
                    </div>
                    </div>
                    </div>
                    </div>
                </td>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if(isset($dataNilai) && $dataNilai instanceof \Illuminate\Pagination\LengthAwarePaginator && $dataNilai->hasPages())
    <div style="padding: 20px; border-top: 1px solid #E2E8F0;">
        {{ $dataNilai->links() }}
    </div>
    @endif
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
    .filter-select {
        padding: 10px 16px;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        background: white;
        font-size: 14px;
        cursor: pointer;
    }
    .filter-select:focus {
        outline: none;
        border-color: #F9A826;
    }
    
    /* Action Buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 14px;
    }
    .btn-view {
        background: #E8EDF2;
        color: #1A2A3A;
    }
    .btn-view:hover {
        background: #F9A826;
        color: white;
        transform: translateY(-2px);
    }
    .btn-edit {
        background: #FEF3E0;
        color: #F9A826;
    }
    .btn-edit:hover {
        background: #F9A826;
        color: white;
        transform: translateY(-2px);
    }
    .btn-create {
        background: #D1FAE5;
        color: #10B981;
    }
    .btn-create:hover {
        background: #10B981;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Tooltip */
    .btn-action {
        position: relative;
    }
    .btn-action:hover::after {
        content: attr(title);
        position: absolute;
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: #1A2A3A;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 10px;
        white-space: nowrap;
        z-index: 10;
    }
    
    /* Stat Card */
    .stat-card {
        transition: all 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>

<script>
    // Auto submit when tahun filter changes
    document.querySelector('select[name="tahun"]')?.addEventListener('change', function() {
        this.form.submit();
    });
    
    // Tooltip for action buttons
    document.querySelectorAll('.btn-action').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            const title = this.getAttribute('title');
            if (title && !this.getAttribute('data-has-tooltip')) {
                this.setAttribute('data-has-tooltip', 'true');
            }
        });
    });
</script>
@endsection