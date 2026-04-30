{{-- resources/views/layouts/petugas.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPERJA - Petugas')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Warna untuk Petugas - Lebih cerah dan friendly */
            --primary: #1A5F7A;      /* Biru kalem untuk petugas */
            --primary-dark: #0E3E52;
            --primary-light: #2B8BAE;
            --primary-soft: #E6F3F8;
            
            --secondary: #F9A826;     /* Kuning kunyit */
            --secondary-dark: #E8912A;
            --secondary-light: #FDEBD0;
            --secondary-glow: rgba(249, 168, 38, 0.12);
            
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #3B82F6;
            
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --text-muted: #94A3B8;
            --text-white: #FFFFFF;
            
            --bg-main: #F1F5F9;
            --bg-card: #FFFFFF;
            --bg-sidebar: linear-gradient(145deg, #1A5F7A 0%, #0E3E52 100%);
            
            --border-light: rgba(0, 0, 0, 0.06);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
            
            --sidebar-width: 280px;
            --sidebar-collapsed: 88px;
            --header-height: 68px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius: 16px;
            --border-radius-sm: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            line-height: 1.5;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* ==================== SIDEBAR PETUGAS ==================== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        /* Sidebar Header */
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .sidebar.collapsed .sidebar-header {
            padding: 24px 12px;
        }
        
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .sidebar.collapsed .brand {
            justify-content: center;
        }
        
        .brand-icon {
            width: 44px;
            height: 44px;
            background: white;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .brand-icon img {
            width: 70%;
            height: auto;
        }
        
        .brand-icon i {
            font-size: 22px;
            color: var(--primary);
        }
        
        .brand-text {
            flex: 1;
        }
        
        .sidebar.collapsed .brand-text {
            display: none;
        }
        
        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.3px;
        }
        
        .brand-name span {
            color: var(--secondary);
        }
        
        .brand-tagline {
            font-size: 9px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 2px;
        }
        
        /* User Profile - Petugas */
        .user-card {
            margin: 20px 16px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .sidebar.collapsed .user-card {
            padding: 12px;
            margin: 16px 12px;
        }
        
        .user-avatar {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .sidebar.collapsed .user-avatar {
            justify-content: center;
        }
        
        .avatar-img {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .sidebar.collapsed .avatar-img {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        
        .user-details {
            flex: 1;
        }
        
        .sidebar.collapsed .user-details {
            display: none;
        }
        
        .user-name {
            font-weight: 700;
            color: white;
            font-size: 14px;
            margin-bottom: 2px;
        }
        
        .user-role {
            font-size: 10px;
            color: var(--secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .user-role i {
            font-size: 9px;
        }
        
        /* Navigation Petugas - Menu terbatas */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0 12px;
            scrollbar-width: thin;
        }
        
        .sidebar-nav::-webkit-scrollbar {
            width: 3px;
        }
        
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 10px;
        }
        
        .nav-group {
            margin-bottom: 24px;
        }
        
        .nav-label {
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.35);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 12px 10px 12px;
        }
        
        .sidebar.collapsed .nav-label {
            display: none;
        }
        
        .nav-items {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: 4px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 12px;
            transition: var(--transition);
        }
        
        .sidebar.collapsed .nav-link {
            padding: 11px;
            justify-content: center;
        }
        
        .nav-link:hover {
            background: rgba(249, 168, 38, 0.1);
            color: var(--secondary);
        }
        
        .nav-link.active {
            background: rgba(249, 168, 38, 0.12);
            color: var(--secondary);
        }
        
        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .nav-text {
            flex: 1;
            font-size: 13px;
        }
        
        .sidebar.collapsed .nav-text {
            display: none;
        }
        
        .nav-badge {
            background: var(--secondary);
            color: var(--primary-dark);
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
        }
        
        /* Status Badge untuk Validasi */
        .status-badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            background: rgba(249, 168, 38, 0.15);
            color: var(--secondary);
        }
        
        /* Logout */
        .logout-section {
            padding: 16px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: auto;
        }
        
        #logoutBtn:hover {
            background: rgba(239, 68, 68, 0.12);
            color: #EF4444;
        }
        
        /* Toggle Button */
        .toggle-sidebar {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: -12px;
            width: 24px;
            height: 24px;
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 10px;
            z-index: 101;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        
        .toggle-sidebar:hover {
            background: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }
        
        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
        }
        
        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }
        
        /* Header */
        .main-header {
            background: var(--bg-card);
            padding: 0 28px;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
            border-bottom: 1px solid var(--border-light);
        }
        
        .header-left h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .header-left p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        /* Tahun Aktif */
        .tahun-info {
            background: var(--primary-soft);
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
        }
        
        .tahun-info i {
            margin-right: 6px;
        }
        
        /* Toast */
        .toast-modern {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--bg-card);
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            z-index: 1001;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            border-left: 4px solid var(--secondary);
        }
        
        .toast-modern.show {
            transform: translateX(0);
        }
        
        .toast-icon {
            width: 36px;
            height: 36px;
            background: var(--secondary-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-dark);
        }
        
        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .modal-card {
            background: var(--bg-card);
            border-radius: 20px;
            width: 90%;
            max-width: 400px;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Cards untuk Petugas */
        .action-card {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            padding: 20px;
            transition: var(--transition);
            border: 1px solid var(--border-light);
            cursor: pointer;
        }
        
        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--secondary-light);
        }
        
        .action-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-soft);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 22px;
            margin-bottom: 16px;
        }
        
        /* Table Styles */
        .data-table {
            width: 100%;
            background: var(--bg-card);
            border-radius: var(--border-radius);
            overflow: hidden;
            border-collapse: collapse;
        }
        
        .data-table th {
            text-align: left;
            padding: 14px 16px;
            background: var(--bg-main);
            font-weight: 600;
            font-size: 13px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-light);
        }
        
        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-light);
            font-size: 13px;
        }
        
        .data-table tr:hover td {
            background: var(--bg-main);
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: var(--transition);
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px var(--secondary-glow);
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-secondary {
            background: var(--secondary);
            color: var(--primary-dark);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        @media (max-width: 768px) {
            .main-header {
                padding: 0 16px;
            }
            .tahun-info {
                display: none;
            }
            .header-left h1 {
                font-size: 16px;
            }
        }
        
        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }
        
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Modal Logout -->
    <div class="modal-overlay" id="logoutModal" style="display: none;">
        <div class="modal-card">
            <div style="padding: 28px; text-align: center;">
                <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-sign-out-alt" style="font-size: 26px; color: #EF4444;"></i>
                </div>
                <h3 style="font-size: 20px; margin-bottom: 8px;">Keluar dari Sistem?</h3>
                <p style="color: var(--text-muted); margin-bottom: 24px;">Apakah Anda yakin ingin keluar?</p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button id="cancelLogout" style="padding: 10px 20px; border: 1px solid var(--border-light); background: white; border-radius: 40px; font-weight: 500; cursor: pointer;">Batal</button>
                    <button id="confirmLogout" style="padding: 10px 20px; background: #EF4444; color: white; border: none; border-radius: 40px; font-weight: 500; cursor: pointer;">Ya, Keluar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast -->
    <div class="toast-modern" id="logoutToast">
        <div class="toast-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <div style="font-weight: 600;">Berhasil Keluar</div>
            <div style="font-size: 12px; color: var(--text-muted);">Mengarahkan ke login...</div>
        </div>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
    @include('partials.sidebar-petugas')
    
    <main class="main-content">
        <header class="main-header">
            <div class="header-left">
                <h1>@yield('page-title', 'Dashboard Petugas')</h1>
                <p>@yield('page-subtitle', 'Kelola data jalan dan nilai kriteria')</p>
            </div>
            <div class="header-right">
                <div class="tahun-info">
                    <i class="fas fa-calendar-alt"></i> Tahun Penilaian: {{ date('Y') }}
                </div>
                <div class="notification-btn" style="position: relative; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: var(--bg-main); border-radius: 40px; cursor: pointer;">
                    <i class="fas fa-bell" style="color: var(--text-muted);"></i>
                </div>
            </div>
        </header>
        
        <div class="content-wrapper" style="padding: 24px 28px;">
            @yield('content')
        </div>
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    const icon = toggleBtn.querySelector('i');
                    icon.className = sidebar.classList.contains('collapsed') ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
                });
            }
            
            // Mobile
            if (window.innerWidth <= 1200) sidebar?.classList.add('open');
            
            // Logout
            const logoutBtn = document.getElementById('logoutBtn');
            const modal = document.getElementById('logoutModal');
            const cancelBtn = document.getElementById('cancelLogout');
            const confirmBtn = document.getElementById('confirmLogout');
            const toast = document.getElementById('logoutToast');
            const logoutForm = document.getElementById('logout-form');
            
            if (logoutBtn) {
                logoutBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    modal.style.display = 'flex';
                });
            }
            
            const closeModal = () => {
                modal.style.display = 'none';
            };
            
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            if (modal) modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
            
            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    closeModal();
                    toast.classList.add('show');
                    setTimeout(() => logoutForm.submit(), 1500);
                });
            }
            
            // Active nav
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && href !== '#' && currentPath.includes(href)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>