@extends('layouts.petugas')

@section('title', 'Riwayat Penilaian - Petugas')
@section('page-title', 'Riwayat Penilaian')
@section('page-subtitle', 'Lihat semua data nilai kriteria yang telah diinput')

@section('content')
<div class="stat-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('petugas.nilai-kriteria.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Input Nilai Baru
            </a>
            <a href="{{ route('petugas.nilai-kriteria.riwayat') }}" class="btn-outline">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
        </div>
        
        <form method="GET" action="{{ route('petugas.nilai-kriteria.riwayat') }}" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <select name="tahun" class="filter-select">
                @foreach($tahunList as $thn)
                    <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
                @endforeach
            </select>
            
            <select name="status" class="filter-select">
                <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="divalidasi" {{ $status == 'divalidasi' ? 'selected' : '' }}>Divalidasi</option>
                <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            
            <button type="submit" class="btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>
</div>

<!-- Table Data -->
<div class="stat-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0;">
                    <th style="padding: 15px; width: 5%; text-align: center;">NO</th>
                    <th style="padding: 15px; width: 20%;">Nama Jalan</th>
                    <th style="padding: 15px; width: 20%;">Kriteria</th>
                    <th style="padding: 15px; width: 10%; text-align: center;">Nilai</th>
                    <th style="padding: 15px; width: 10%; text-align: center;">Tahun</th>
                    <th style="padding: 15px; width: 15%; text-align: center;">Status</th>
                    <th style="padding: 15px; width: 20%; text-align: center;">Divalidasi Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilai as $index => $item)
                <tr style="border-bottom: 1px solid #E2E8F0;">
                    <td style="padding: 14px; text-align: center;">{{ $nilai->firstItem() + $index }}</td>
                    <td style="padding: 14px;">
                        <strong>{{ $item->jalan->nama ?? '-' }}</strong>
                        <br>
                        <small style="color: #6B7280;">{{ $item->jalan->kode ?? '-' }}</small>
                    </td>
                    <td style="padding: 14px;">
                        <span style="background: #E8EDF2; padding: 4px 8px; border-radius: 6px; font-size: 11px;">{{ $item->kriteria->kode ?? '-' }}</span>
                        <br>
                        <small>{{ $item->kriteria->nama ?? '-' }}</small>
                    </td>
                    <td style="padding: 14px; text-align: center;">
                        <span style="font-weight: 700; color: #F9A826;">{{ number_format($item->nilai, 2) }}</span>
                    </td>
                    <td style="padding: 14px; text-align: center;">{{ $item->tahun_penilaian }}</td>
                    <td style="padding: 14px; text-align: center;">
                        @if($item->status_validasi == 'divalidasi')
                            <span style="background: #D1FAE5; color: #059669; padding: 4px 10px; border-radius: 20px; font-size: 11px;">
                                <i class="fas fa-check-circle"></i> Valid
                            </span>
                        @elseif($item->status_validasi == 'pending')
                            <span style="background: #FEF3C7; color: #D97706; padding: 4px 10px; border-radius: 20px; font-size: 11px;">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @else
                            <span style="background: #FEE2E2; color: #DC2626; padding: 4px 10px; border-radius: 20px; font-size: 11px;">
                                <i class="fas fa-times-circle"></i> Ditolak
                            </span>
                        @endif
                    </td>
                    <td style="padding: 14px; text-align: center;">
                        @if($item->validated_by)
                            <small>{{ $item->validatedBy->name ?? '-' }}</small>
                            <br>
                            <small style="color: #6B7280; font-size: 10px;">{{ $item->validated_at ? $item->validated_at->timezone('Asia/Jakarta')->format('d/m/Y') : '-' }}</small>
                        @else
                            <span style="color: #9CA3AF;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 60px; text-align: center; color: #6B7280;">
                        <i class="fas fa-history" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p>Belum ada data riwayat penilaian</p>
                        <a href="{{ route('petugas.nilai-kriteria.create') }}" class="btn-primary" style="display: inline-block; margin-top: 12px;">
                            <i class="fas fa-plus"></i> Input Nilai Sekarang
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($nilai->hasPages())
    <div style="padding: 20px; border-top: 1px solid #E2E8F0;">
        {{ $nilai->links() }}
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
        border: none;
        cursor: pointer;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        border: 1px solid #E2E8F0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
    .stat-card {
        transition: all 0.2s;
        border-radius: 12px;
        padding: 20px;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>

<script>
    document.querySelector('select[name="tahun"]')?.addEventListener('change', function() {
        this.form.submit();
    });
    document.querySelector('select[name="status"]')?.addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endsection