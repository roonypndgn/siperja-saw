<aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
        <a href="#" class="brand">
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
            <div class="avatar-img">
            </div>
            <div class="user-details">
                <div class="user-name"></div>
                <div class="user-role">
                    <i class="fas fa-shield-alt" style="font-size: 10px;"></i> 
                </div>
            </div>
        </div>
    </div>
    
    <div class="sidebar-nav">
        <!-- Menu Utama -->
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
        
        <!-- Master Data -->
        <div class="nav-group">
            <div class="nav-label">MASTER DATA</div>
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-road"></i></div>
                        <div class="nav-text">Data Jalan</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-sliders-h"></i></div>
                        <div class="nav-text">Kriteria & Bobot</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-table"></i></div>
                        <div class="nav-text">Nilai Kriteria</div>
                            <span class="nav-badge"></span>
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