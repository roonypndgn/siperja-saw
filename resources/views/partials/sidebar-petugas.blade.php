<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="#" class="brand">
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
            <div class="avatar-img">
                {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
            </div>
            <div class="user-details">
                <div class="user-name">{{ Auth::user()->name ?? 'Petugas Lapangan' }}</div>
                <div class="user-role">
                    <i class="fas fa-clipboard-list"></i> PETUGAS
                </div>
            </div>
        </div>
    </div>
    
    <div class="sidebar-nav">
        <!-- Menu Utama Petugas -->
        <div class="nav-group">
            <div class="nav-label">UTAMA</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="#" class="nav-link">
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
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-road"></i></div>
                        <div class="nav-text">Daftar Jalan</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-plus-circle"></i></div>
                        <div class="nav-text">Tambah Jalan</div>
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