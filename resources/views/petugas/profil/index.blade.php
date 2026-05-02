@extends('layouts.petugas')

@section('title', 'Profil Saya - Petugas')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun dan keamanan Anda')

@section('content')
<div class="profil-container">
    
    <!-- Header Profil -->
    <div class="profil-header" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(249, 168, 38, 0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(249, 168, 38, 0.05); border-radius: 50%;"></div>
        
        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap; position: relative; z-index: 1;">
            <!-- Avatar -->
            <div class="header-avatar" style="position: relative;">
                @if($user->foto)
                    <img src="{{ Storage::url($user->foto) }}" alt="Foto Profil" 
                         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #F9A826; box-shadow: 0 8px 20px rgba(0,0,0,0.2);">
                @else
                    <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #F9A826, #E8912A); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid #F9A826; box-shadow: 0 8px 20px rgba(0,0,0,0.2);">
                        <i class="fas fa-user" style="font-size: 48px; color: white;"></i>
                    </div>
                @endif
                <div class="avatar-badge" style="position: absolute; bottom: 5px; right: 5px; background: #10B981; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white;">
                    <i class="fas fa-check" style="font-size: 12px; color: white;"></i>
                </div>
            </div>
            
            <!-- Info User -->
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 8px;">
                    <h2 style="color: white; margin: 0; font-size: 28px;">{{ $user->name }}</h2>
                    @if($user->role == 'admin')
                        <span class="role-badge-admin">Administrator</span>
                    @else
                        <span class="role-badge-petugas">Petugas Lapangan</span>
                    @endif
                </div>
                <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-envelope" style="color: #F9A826; font-size: 14px;"></i>
                        <span style="color: #8BA3BC;">{{ $user->email }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-id-card" style="color: #F9A826; font-size: 14px;"></i>
                        <span style="color: #8BA3BC;">NIP: {{ $user->nip }}</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar-alt" style="color: #F9A826; font-size: 14px;"></i>
                        <span style="color: #8BA3BC;">Bergabung: {{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('petugas.profil.edit') }}" class="btn-header-edit">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
                <a href="{{ route('petugas.profil.change-password') }}" class="btn-header-password">
                    <i class="fas fa-key"></i> Ganti Password
                </a>
            </div>
        </div>
    </div>
    
    <!-- Dua Kolom Informasi -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
        
        <!-- Informasi Akun -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-info-circle"></i>
                <h3>Informasi Akun</h3>
                <a href="{{ route('petugas.profil.edit') }}" class="info-card-edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
            <div class="info-card-body">
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-user"></i> Nama Lengkap
                    </div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-envelope"></i> Email
                    </div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-id-card"></i> NIP
                    </div>
                    <div class="info-value">{{ $user->nip }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-user-tag"></i> Role
                    </div>
                    <div class="info-value">
                        @if($user->role == 'admin')
                            <span class="role-badge-admin-sm">Administrator</span>
                        @else
                            <span class="role-badge-petugas-sm">Petugas Lapangan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informasi Keamanan -->
        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-shield-alt"></i>
                <h3>Keamanan Akun</h3>
                <a href="{{ route('petugas.profil.change-password') }}" class="info-card-edit">
                    <i class="fas fa-key"></i> Ganti
                </a>
            </div>
            <div class="info-card-body">
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-calendar-plus"></i> Terdaftar Sejak
                    </div>
                    <div class="info-value">{{ $user->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-clock"></i> Terakhir Update
                    </div>
                    <div class="info-value">{{ $user->updated_at->timezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-lock"></i> Status Keamanan
                    </div>
                    <div class="info-value">
                        <span class="security-badge">
                            <i class="fas fa-check-circle"></i> Terproteksi
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">
                        <i class="fas fa-history"></i> Riwayat Aktivitas
                    </div>
                    <div class="info-value">
                        <a href="{{ route('petugas.nilai-kriteria.riwayat') }}" class="link-activity">
                            Lihat riwayat penilaian <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Aktivitas Terbaru -->
    <div class="activity-card" style="margin-top: 24px;">
        <div class="activity-header">
            <i class="fas fa-history"></i>
            <h3>Aktivitas Terbaru</h3>
            <a href="{{ route('petugas.nilai-kriteria.riwayat') }}" class="view-all">Lihat Semua →</a>
        </div>
        <div class="activity-body">
            @php
                $aktivitas = \App\Models\NilaiKriteriaJalan::with(['jalan', 'kriteria'])
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            @endphp
            
            @forelse($aktivitas as $item)
            <div class="activity-item">
                <div class="activity-icon">
                    @if($item->status_validasi == 'divalidasi')
                        <i class="fas fa-check-circle" style="color: #10B981;"></i>
                    @elseif($item->status_validasi == 'pending')
                        <i class="fas fa-clock" style="color: #F59E0B;"></i>
                    @else
                        <i class="fas fa-times-circle" style="color: #EF4444;"></i>
                    @endif
                </div>
                <div class="activity-content">
                    <div class="activity-title">
                        Input nilai untuk <strong>{{ $item->jalan->nama ?? '-' }}</strong>
                    </div>
                    <div class="activity-detail">
                        Kriteria: {{ $item->kriteria->nama ?? '-' }} | Nilai: {{ number_format($item->nilai, 2) }}
                    </div>
                    <div class="activity-time">
                        <i class="fas fa-calendar-alt"></i> {{ $item->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="activity-status">
                    @if($item->status_validasi == 'divalidasi')
                        <span class="badge-valid">Divalidasi</span>
                    @elseif($item->status_validasi == 'pending')
                        <span class="badge-pending">Pending</span>
                    @else
                        <span class="badge-rejected">Ditolak</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="activity-empty">
                <i class="fas fa-inbox"></i>
                <p>Belum ada aktivitas terbaru</p>
                <a href="{{ route('petugas.nilai-kriteria.create') }}" class="btn-start">Mulai Input Nilai</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .profil-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Header Styles */
    .btn-header-edit {
        background: #F9A826;
        color: #1A2A3A;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-header-edit:hover {
        background: #E8912A;
        transform: translateY(-2px);
    }
    
    .btn-header-password {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(10px);
    }
    
    .btn-header-password:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }
    
    .role-badge-admin {
        background: #10B981;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .role-badge-petugas {
        background: #F9A826;
        color: #1A2A3A;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    /* Stat Cards */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s;
        border: 1px solid #E2E8F0;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon i {
        font-size: 28px;
    }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #1A2A3A;
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 13px;
        color: #6B7280;
        margin-top: 4px;
    }
    
    /* Info Card */
    .info-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .info-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    
    .info-card-header {
        background: #F8FAFC;
        padding: 16px 20px;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .info-card-header i {
        font-size: 18px;
        color: #F9A826;
    }
    
    .info-card-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1A2A3A;
        margin: 0;
        flex: 1;
    }
    
    .info-card-edit {
        background: none;
        border: none;
        color: #F9A826;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }
    
    .info-card-edit:hover {
        color: #E8912A;
    }
    
    .info-card-body {
        padding: 20px;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #F1F5F9;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #4B6B8A;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .info-label i {
        width: 20px;
        color: #F9A826;
    }
    
    .info-value {
        color: #1A2A3A;
        font-weight: 500;
    }
    
    .role-badge-admin-sm {
        background: #D1FAE5;
        color: #059669;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .role-badge-petugas-sm {
        background: #FEF3C7;
        color: #D97706;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .security-badge {
        background: #D1FAE5;
        color: #059669;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .link-activity {
        color: #F9A826;
        text-decoration: none;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .link-activity:hover {
        color: #E8912A;
    }
    
    /* Activity Card */
    .activity-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        overflow: hidden;
    }
    
    .activity-header {
        background: #F8FAFC;
        padding: 16px 20px;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .activity-header i {
        font-size: 18px;
        color: #F9A826;
    }
    
    .activity-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1A2A3A;
        margin: 0;
        flex: 1;
    }
    
    .view-all {
        color: #F9A826;
        text-decoration: none;
        font-size: 13px;
    }
    
    .view-all:hover {
        text-decoration: underline;
    }
    
    .activity-body {
        padding: 0;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        border-bottom: 1px solid #F1F5F9;
        transition: background 0.2s;
    }
    
    .activity-item:hover {
        background: #F8FAFC;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        background: #F8FAFC;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .activity-icon i {
        font-size: 20px;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        font-size: 14px;
        color: #1A2A3A;
        margin-bottom: 4px;
    }
    
    .activity-detail {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 4px;
    }
    
    .activity-time {
        font-size: 11px;
        color: #9CA3AF;
    }
    
    .activity-status {
        min-width: 90px;
        text-align: right;
    }
    
    .badge-valid {
        background: #D1FAE5;
        color: #059669;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-rejected {
        background: #FEE2E2;
        color: #DC2626;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .activity-empty {
        text-align: center;
        padding: 48px 20px;
        color: #6B7280;
    }
    
    .activity-empty i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .btn-start {
        display: inline-block;
        background: #F9A826;
        color: #1A2A3A;
        padding: 8px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        margin-top: 12px;
        transition: all 0.2s;
    }
    
    .btn-start:hover {
        background: #E8912A;
        transform: translateY(-2px);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profil-header {
            padding: 20px;
        }
        
        .stat-card {
            padding: 16px;
        }
        
        .stat-value {
            font-size: 22px;
        }
        
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .activity-item {
            flex-wrap: wrap;
        }
        
        .activity-status {
            width: 100%;
            text-align: left;
            margin-top: 8px;
        }
    }
</style>
@endsection