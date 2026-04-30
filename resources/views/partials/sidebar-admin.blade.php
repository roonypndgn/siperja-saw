<aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <div class="brand-icon" style="background: white; display: flex; align-items: center; justify-content: center;">
                @if(file_exists(public_path('images/logo-pu.png')))
                    <img src="{{ asset('images/logo-pu.png') }}" alt="Logo PU" style="width: 80%; height: 80%; object-fit: contain;">
                @else
                    <!-- Icon default jika logo belum ada -->
                    <div style="text-align: center;">
                        <i class="fas fa-hard-hat" style="color: var(--secondary-dark); font-size: 20px;"></i>
                        <div style="font-size: 8px; color: var(--primary-dark); font-weight: 800;">PUPR</div>
                    </div>
                @endif
            </div>
            <div class="brand-text">
                <div class="brand-name">SIPERJA</div>
                <div class="brand-tagline">Sistem Prioritas Perbaikan Jalan</div>
                <div style="font-size: 8px; color: var(--secondary); margin-top: 4px; letter-spacing: 0.5px;">
                    <i class="fas fa-hand-peace"></i> SIGAP MEMBANGUN NEGERI
                </div>
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
            <div class="user-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
            <div class="user-role">
                <i class="" style="font-size: 10px;"></i> 
                @if(Auth::user()->role == 'admin')
                    Administrator
                @elseif(Auth::user()->role == 'petugas')
                    Petugas Lapangan
                @else
                    {{ ucfirst(Auth::user()->role ?? 'Admin') }}
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
        <!-- Menu Utama -->
        <div class="nav-group">
            <div class="nav-label">UTAMA</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-chart-pie"></i></div>
                        <div class="nav-text">Dashboard</div>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Master Data -->
        <div class="nav-group">
            <div class="nav-label">MASTER DATA</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="{{ route('admin.jalan.index') }}" class="nav-link {{ request()->routeIs('admin.jalan.index') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-road"></i></div>
                        <div class="nav-text">Data Jalan</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kriteria.index') }}" class="nav-link {{ request()->routeIs('admin.kriteria.index') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-sliders-h"></i></div>
                        <div class="nav-text">Kriteria & Bobot</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.nilai-kriteria.index') }}" class="nav-link {{ request()->routeIs('admin.nilai-kriteria.index') ? 'active' : '' }}">
                        <div class="nav-icon"><i class="fas fa-table"></i></div>
                        <div class="nav-text">Nilai Kriteria</div>
                        @php
                            $pendingCount = \App\Models\NilaiKriteriaJalan::where('status_validasi', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="nav-badge">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Proses SAW -->
        <div class="nav-group">
            <div class="nav-label">PROSES & ANALISIS</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-calculator"></i></div>
                        <div class="nav-text">Proses SAW</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-trophy"></i></div>
                        <div class="nav-text">Ranking Prioritas</div>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Laporan -->
        <div class="nav-group">
            <div class="nav-label">LAPORAN</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="nav-text">Laporan</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-download"></i></div>
                        <div class="nav-text">Ekspor Data</div>
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