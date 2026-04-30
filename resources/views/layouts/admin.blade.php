<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPERJA - Sistem Prioritas Perbaikan Jalan')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Modern Color Palette - Kuning Kunyit & Biru Kehitaman */
            --primary: #0A1927;      /* Deep navy/black-blue */
            --primary-dark: #051016;  /* Lebih gelap */
            --primary-light: #1A3A2F; /* Dark teal */
            --primary-soft: #E8F0EE;  /* Soft background */
            
            --secondary: #F5B041;     /* Kuning kunyit cerah */
            --secondary-dark: #E67E22; /* Oren gelap */
            --secondary-light: #FDEBD0; /* Kuning sangat lembut */
            --secondary-glow: rgba(245, 176, 65, 0.15);
            
            --accent: #2E8B57;        /* Sea green - untuk success */
            --danger: #E74C3C;
            --warning: #F39C12;
            --info: #3498DB;
            
            --text-primary: #1A2A3A;
            --text-secondary: #5A6E7A;
            --text-muted: #8A9DA8;
            --text-white: #FFFFFF;
            
            --bg-main: #F5F7FA;
            --bg-card: #FFFFFF;
            --bg-sidebar: linear-gradient(145deg, #0A1927 0%, #051016 100%);
            
            --border-light: rgba(0, 0, 0, 0.06);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 16px 40px rgba(0, 0, 0, 0.08);
            --shadow-glow: 0 4px 20px rgba(245, 176, 65, 0.15);
            
            --sidebar-width: 280px;
            --sidebar-collapsed: 88px;
            --header-height: 72px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius: 20px;
            --border-radius-sm: 14px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            line-height: 1.5;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* ==================== MODERN SIDEBAR ==================== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            backdrop-filter: blur(10px);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 100;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        /* Sidebar Header */
        .sidebar-header {
            padding: 24px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }
        
        .sidebar.collapsed .sidebar-header {
            padding: 24px 16px;
            text-align: center;
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
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            font-size: 22px;
            font-weight: 800;
            box-shadow: var(--shadow-glow);
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
            font-size: 10px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        
        /* User Profile Card */
        .user-card {
            margin: 20px 16px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: var(--transition);
        }
        
        .sidebar.collapsed .user-card {
            padding: 12px;
            margin: 16px 12px;
        }
        
        .user-avatar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .sidebar.collapsed .user-avatar {
            justify-content: center;
            margin-bottom: 0;
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
            font-size: 11px;
            color: var(--secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Navigation */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 0 12px;
            scrollbar-width: thin;
            scrollbar-color: var(--secondary) transparent;
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
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.35);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 12px 12px 12px;
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
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 12px;
            transition: var(--transition);
            position: relative;
        }
        
        .sidebar.collapsed .nav-link {
            padding: 12px;
            justify-content: center;
        }
        
        .nav-link:hover {
            background: rgba(245, 176, 65, 0.08);
            color: var(--secondary);
        }
        
        .nav-link.active {
            background: rgba(245, 176, 65, 0.12);
            color: var(--secondary);
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 20px;
            background: var(--secondary);
            border-radius: 0 4px 4px 0;
        }
        
        .nav-icon {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        
        .nav-text {
            flex: 1;
            font-size: 14px;
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
            min-width: 22px;
            text-align: center;
        }
        
        .sidebar.collapsed .nav-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            padding: 2px 6px;
            min-width: 18px;
            font-size: 9px;
        }
        
        /* Logout Section */
        .logout-section {
            padding: 16px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            margin-top: auto;
        }
        
        #logoutBtn {
            border-radius: 12px;
        }
        
        #logoutBtn:hover {
            background: rgba(231, 76, 60, 0.12);
            color: #E74C3C;
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
            font-size: 11px;
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
        
        /* Modern Header */
        .main-header {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            padding: 0 32px;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
            border-bottom: 1px solid var(--border-light);
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        /* Search Bar Modern */
        .search-wrapper {
            position: relative;
        }
        
        .search-input {
            padding: 10px 16px 10px 42px;
            border: 1px solid var(--border-light);
            border-radius: 40px;
            font-size: 14px;
            width: 280px;
            background: var(--bg-main);
            transition: var(--transition);
            font-family: inherit;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--secondary);
            width: 320px;
            background: var(--bg-card);
            box-shadow: var(--shadow-glow);
        }
        
        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }
        
        /* Notification Bell */
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 40px;
            background: var(--bg-main);
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }
        
        .notification-btn:hover {
            background: var(--secondary-light);
            color: var(--secondary-dark);
        }
        
        .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--secondary);
            border-radius: 50%;
            border: 2px solid var(--bg-card);
        }
        
        /* Content Wrapper */
        .content-wrapper {
            padding: 32px;
            min-height: calc(100vh - var(--header-height));
        }
        
        /* Modern Cards */
        .stat-card {
            background: var(--bg-card);
            border-radius: var(--border-radius);
            padding: 24px;
            transition: var(--transition);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, var(--secondary-glow) 0%, transparent 70%);
            opacity: 0;
            transition: var(--transition);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(245, 176, 65, 0.2);
        }
        
        .stat-card:hover::after {
            opacity: 0.5;
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .stat-icon {
            width: 52px;
            height: 52px;
            background: var(--secondary-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-dark);
            font-size: 24px;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }
        
        /* Toast Modern */
        .toast-modern {
            position: fixed;
            bottom: 32px;
            right: 32px;
            background: var(--bg-card);
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 320px;
            z-index: 1001;
            transform: translateX(400px);
            transition: transform 0.4s cubic-bezier(0.34, 1.2, 0.64, 1);
            border-left: 4px solid var(--secondary);
        }
        
        .toast-modern.show {
            transform: translateX(0);
        }
        
        .toast-icon {
            width: 40px;
            height: 40px;
            background: var(--secondary-light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-dark);
            font-size: 18px;
        }
        
        /* Modern Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeIn 0.2s ease;
        }
        
        .modal-card {
            background: var(--bg-card);
            border-radius: 24px;
            width: 90%;
            max-width: 420px;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }
        .sidebar.collapsed .brand-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto;
        }

        .sidebar.collapsed .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .sidebar.collapsed .brand-text {
            display: none;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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
            .search-input {
                width: 200px;
            }
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 20px;
            }
            .page-title {
                font-size: 20px;
            }
            .search-wrapper {
                display: none;
            }
            .stat-card {
                padding: 18px;
            }
            .toast-modern {
                right: 16px;
                left: 16px;
                min-width: auto;
            }
        }
        
        /* Utility Classes */
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
        }
        
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Modal Logout Modern -->
    <div class="modal-overlay" id="logoutModal" style="display: none;">
        <div class="modal-card">
            <div style="padding: 32px; text-align: center;">
                <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%); border-radius: 32px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-sign-out-alt" style="font-size: 28px; color: #E74C3C;"></i>
                </div>
                <h3 style="font-size: 22px; font-weight: 700; margin-bottom: 8px;">Keluar dari Sistem?</h3>
                <p style="color: var(--text-muted); margin-bottom: 24px;">Apakah Anda yakin ingin keluar? Data yang belum disimpan akan hilang.</p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button id="cancelLogout" style="padding: 12px 24px; border: 1px solid var(--border-light); background: var(--bg-main); border-radius: 40px; font-weight: 600; cursor: pointer; font-family: inherit;">Batal</button>
                    <button id="confirmLogout" style="padding: 12px 24px; background: #E74C3C; color: white; border: none; border-radius: 40px; font-weight: 600; cursor: pointer; font-family: inherit; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-sign-out-alt"></i> Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Modern -->
    <div class="toast-modern" id="logoutToast">
        <div class="toast-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div style="flex: 1;">
            <div style="font-weight: 700; margin-bottom: 2px;">Berhasil Keluar</div>
            <div style="font-size: 13px; color: var(--text-muted);">Mengarahkan ke halaman login...</div>
        </div>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @include('partials.sidebar-admin')
    
    <main class="main-content">
        <header class="main-header">
            <div class="header-left">
                <div>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    <div class="page-subtitle">@yield('page-subtitle', 'Sistem Prioritas Perbaikan Jalan')</div>
                </div>
            </div>
            <div class="header-right">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Cari data, laporan...">
                </div>
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
            </div>
        </header>
        
        <div class="content-wrapper">
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
                    if (sidebar.classList.contains('collapsed')) {
                        icon.className = 'fas fa-chevron-right';
                    } else {
                        icon.className = 'fas fa-chevron-left';
                    }
                });
            }
            
            // Handle mobile
            if (window.innerWidth <= 1200) {
                sidebar?.classList.add('open');
            }
            
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 1200) {
                    sidebar?.classList.add('open');
                } else {
                    sidebar?.classList.remove('open');
                }
            });
            
            // Logout functionality
            const logoutBtn = document.getElementById('logoutBtn');
            const modal = document.getElementById('logoutModal');
            const cancelBtn = document.getElementById('cancelLogout');
            const confirmBtn = document.getElementById('confirmLogout');
            const toast = document.getElementById('logoutToast');
            const logoutForm = document.getElementById('logout-form');
            
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                });
            }
            
            const closeModal = () => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            };
            
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) closeModal();
                });
            }
            
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    closeModal();
                    toast.classList.add('show');
                    setTimeout(() => {
                        logoutForm.submit();
                    }, 1500);
                });
            }
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    closeModal();
                }
            });
            
            // Active nav link highlighting
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
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