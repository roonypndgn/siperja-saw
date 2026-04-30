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
    <div class="user-avatar">
        @if(Auth::user()->foto)
            <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto" class="avatar-img" style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover;">
        @else
            <div class="avatar-img" style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%); display: flex; align-items: center; justify-content: center; color: var(--primary-dark); font-weight: 700; font-size: 18px;">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
        @endif
        <div class="user-details">
            <div class="user-name">{{ Auth::user()->name ?? 'Petugas' }}</div>
            <div class="user-role">
                <i class="" style="font-size: 10px;"></i> 
                @if(Auth::user()->role == 'admin')
                    Administrator
                @elseif(Auth::user()->role == 'petugas')
                    Petugas Lapangan
                @else
                    {{ ucfirst(Auth::user()->role ?? 'Petugas') }}
                @endif
            </div>
            @if(Auth::user()->nip)
                <div class="user-nip" style="font-size: 10px; color: #fffff; margin-top: 2px;">
                    NIP: {{ Auth::user()->nip }}
                </div>
            @endif
        </div>
    </div>
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
        
        <!-- Nilai Kriteria (Tugas Utama Petugas) -->
        <div class="nav-group">
            <div class="nav-label">PENGISIAN NILAI</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-edit"></i></div>
                        <div class="nav-text">Input Nilai Kriteria</div>
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
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-history"></i></div>
                        <div class="nav-text">Riwayat Penilaian</div>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Informasi -->
        <div class="nav-group">
            <div class="nav-label">INFORMASI</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-question-circle"></i></div>
                        <div class="nav-text">Panduan</div>
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