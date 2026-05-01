<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('petugas.dashboard') }}" class="brand">
            <div class="brand-icon">
                @if(file_exists(public_path('images/logo-pu.png')))
                <img src="{{ asset('images/logo-pu.png') }}" alt="Logo PU">
                @else
                <i class="fas fa-road"></i>
                @endif
            </div>
            <div class="brand-text">
                <div class="brand-name">SIPERJA</div>
                <div class="brand-tagline">Sistem Prioritas Perbaikan Jalan</div>
            </div>
        </a>
    </div>

<div class="user-card">
    <a href="{{ route('petugas.profil.index') }}" style="text-decoration: none; display: block; color: inherit;">
        <div class="user-avatar" style="display: flex; align-items: center; gap: 12px;">
            @if(Auth::user()->foto)
                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" class="avatar-img" style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover;">
            @else
                <div class="avatar-img" style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%); display: flex; align-items: center; justify-content: center; color: var(--primary-dark); font-weight: 700; font-size: 18px;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                </div>
            @endif
            <div class="user-details" style="flex: 1;">
                <div class="user-name" style="font-weight: 600; color: white;">{{ Auth::user()->name ?? 'Petugas' }}</div>
                <div class="user-role" style="font-size: 11px; color: #F9A826; margin-top: 2px;">
                    <i class="fas fa-user-check" style="font-size: 10px; margin-right: 4px;"></i>
                    @if(Auth::user()->role == 'admin')
                        Administrator
                    @elseif(Auth::user()->role == 'petugas')
                        Petugas Lapangan
                    @else
                        {{ ucfirst(Auth::user()->role ?? 'Petugas') }}
                    @endif
                </div>
                @if(Auth::user()->nip)
                <div class="user-nip" style="font-size: 10px; color: #8BA3BC; margin-top: 2px;">
                    <i class="fas fa-id-card" style="font-size: 9px; margin-right: 3px;"></i>
                    NIP: {{ Auth::user()->nip }}
                </div>
                @endif
            </div>
            <div class="user-link-icon" style="color: #8BA3BC;">
                <i class="fas fa-chevron-right" style="font-size: 12px;"></i>
            </div>
        </div>
    </a>
</div>

    <div class="sidebar-nav">
        <!-- Menu Utama Petugas -->
        <div class="nav-group">
            <div class="nav-label">UTAMA</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="{{ route('petugas.dashboard') }}" class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-chart-pie"></i></div>
                        <div class="nav-text">Dashboard</div>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Data Jalan -->
        <div class="nav-group">
            <div class="nav-label">DATA JALAN</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="{{ route('petugas.jalan.index') }}" class="nav-link {{ request()->routeIs('petugas.jalan.index') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-road"></i></div>
                        <div class="nav-text">Daftar Jalan</div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-group">
            <div class="nav-label">PENGISIAN NILAI</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="{{ route('petugas.nilai-kriteria.create') }}" class="nav-link {{ request()->routeIs('petugas.nilai-kriteria.create') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-edit"></i></div>
                        <div class="nav-text">Input Nilai Kriteria</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('petugas.nilai-kriteria.riwayat') }}" class="nav-link {{ request()->routeIs('petugas.nilai-kriteria.riwayat') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-history"></i></div>
                        <div class="nav-text">Riwayat Penilaian</div>
                        @php
                        $pendingCount = \App\Models\NilaiKriteriaJalan::where('created_by', Auth::id())
                        ->where('status_validasi', 'pending')
                        ->count();
                        @endphp
                        @if($pendingCount > 0)
                        <span class="nav-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <!-- Informasi -->
        <div class="nav-group">
            <div class="nav-label">PANDUAN</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="{{ route('petugas.panduan.index') }}" class="nav-link {{ request()->routeIs('petugas.panduan.*') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-book-open"></i></div>
                        <div class="nav-text">Panduan Penggunaan</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="logout-section">
        <ul class="nav-items">
            <li class="nav-item">
                <a href="#" id="logoutBtn" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-sign-out-alt"></i></div>
                    <div class="nav-text">Keluar</div>
                </a>
            </li>
        </ul>
    </div>

    <div class="toggle-sidebar" id="toggleSidebar">
        <i class="fas fa-chevron-left"></i>
    </div>
</aside>