<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIPERJA | Sistem Prioritas Perbaikan Jalan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-pu.png') }}">
    <style>
        :root {
            --primary: #0A2E3D;
            --primary-dark: #061E28;
            --primary-light: #1A4A5F;
            --secondary: #F5A623;
            --secondary-dark: #E8912A;
            --secondary-light: #FDE6C5;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --text-muted: #94A3B8;
            --bg-main: #F8FAFC;
            --bg-card: #FFFFFF;
            --border-light: #E2E8F0;
            --danger: #EF4444;
            --success: #10B981;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path fill="white" d="M10,10 L90,10 M10,20 L90,20 M10,30 L90,30 M10,40 L90,40 M10,50 L90,50 M10,60 L90,60 M10,70 L90,70 M10,80 L90,80 M10,90 L90,90 M20,10 L20,90 M30,10 L30,90 M40,10 L40,90 M50,10 L50,90 M60,10 L60,90 M70,10 L70,90 M80,10 L80,90 M90,10 L90,90"/></svg>');
            background-size: 30px 30px;
            pointer-events: none;
        }
        
        .login-container {
            display: flex;
            max-width: 1100px;
            width: 100%;
            background: var(--bg-card);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            position: relative;
            z-index: 1;
        }
        
        /* Left Side - Branding & PU */
        .login-left {
            flex: 1.2;
            background: linear-gradient(145deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 48px;
            color: white;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(245, 166, 35, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        
        /* Logo PU Container */
        .logo-pu {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 48px;
            position: relative;
            z-index: 1;
        }
        
        .logo-pu-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .logo-pu-icon img {
            width: 50px;
            height: auto;
        }
        
        .logo-pu-icon i {
            font-size: 32px;
            color: var(--primary);
        }
        
        .logo-pu-text h2 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }
        
        .logo-pu-text p {
            font-size: 10px;
            opacity: 0.7;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }
        
        .logo-pu-text span {
            color: var(--secondary);
        }
        
        /* Welcome Section */
        .welcome-section {
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }
        
        .welcome-badge {
            display: inline-block;
            background: rgba(245, 166, 35, 0.2);
            padding: 6px 14px;
            border-radius: 40px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 24px;
            border: 1px solid rgba(245, 166, 35, 0.3);
        }
        
        .welcome-badge i {
            margin-right: 8px;
            color: var(--secondary);
        }
        
        .welcome-section h1 {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 16px;
        }
        
        .welcome-section h1 span {
            color: var(--secondary);
        }
        
        .welcome-section p {
            font-size: 14px;
            opacity: 0.8;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        
        /* Feature List */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 32px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
        }
        
        .feature-item i {
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--secondary);
        }
        
        /* Slogan PU */
        .pu-slogan {
            margin-top: auto;
            padding-top: 48px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .pu-slogan .slogan-text {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 2px;
            opacity: 0.6;
        }
        
        .pu-slogan .slogan-text i {
            margin: 0 8px;
        }
        
        .pu-slogan hr {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin: 16px 0;
        }
        
        /* Right Side - Login Form */
        .login-right {
            flex: 0.8;
            padding: 48px;
            background: var(--bg-card);
        }
        
        .login-header {
            margin-bottom: 32px;
        }
        
        .login-header h3 {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        
        .login-header p {
            font-size: 14px;
            color: var(--text-secondary);
        }
        
        /* Alert Messages */
        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-danger {
            background: #FEF2F2;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .alert-success {
            background: #ECFDF5;
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }
        
        .form-label i {
            margin-right: 8px;
            color: var(--secondary);
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-wrapper i.input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
        }
        
        .input-wrapper i.toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .input-wrapper i.toggle-password:hover {
            color: var(--secondary);
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 1.5px solid var(--border-light);
            border-radius: 14px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: var(--bg-card);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(245, 166, 35, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: var(--danger);
        }
        
        .invalid-feedback {
            color: var(--danger);
            font-size: 11px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        /* Button */
        .btn-login {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        /* Demo Account */
        .demo-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-light);
        }
        
        .demo-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .demo-card {
            background: var(--bg-main);
            padding: 12px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        
        .demo-card:hover {
            border-color: var(--secondary);
            background: var(--secondary-light);
        }
        
        .demo-role {
            font-size: 11px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .demo-role i {
            font-size: 10px;
            color: var(--secondary);
        }
        
        .demo-nip {
            font-size: 12px;
            font-family: 'Monaco', monospace;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        
        .demo-pass {
            font-size: 10px;
            color: var(--text-muted);
        }
        
        /* Footer */
        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
        }
        
        /* Responsive */
        @media (max-width: 900px) {
            .login-left {
                display: none;
            }
            .login-right {
                flex: 1;
                padding: 40px 32px;
            }
            .feature-grid {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .login-right {
                padding: 32px 24px;
            }
            .demo-grid {
                grid-template-columns: 1fr;
            }
            .login-header h3 {
                font-size: 24px;
            }
        }
        
        /* Loading State */
        .btn-login.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .btn-login.loading i {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - PU Branding -->
        <div class="login-left">
            <!-- Logo PUPR -->
            <div class="logo-pu">
                <div class="logo-pu-icon">
                    @if(file_exists(public_path('images/logo-pu.png')))
                        <img src="{{ asset('images/logo-pu.png') }}" alt="Logo PUPR">
                    @else
                        <i class="fas fa-building"></i>
                    @endif
                </div>
                <div class="logo-pu-text">
                    <h2>KEMENTERIAN <span>PUPR</span></h2>
                    <p>Pekerjaan Umum dan Perumahan Rakyat</p>
                </div>
            </div>
            
            <div class="welcome-section">
                <div class="welcome-badge">
                    <i class="fas fa-road"></i> SIPERJA v1.0
                </div>
                <h1>Sistem Prioritas <span>Perbaikan Jalan</span></h1>
                <p>Sistem pendukung keputusan berbasis metode SAW (Simple Additive Weighting) untuk menentukan prioritas perbaikan jalan secara objektif dan transparan.</p>
                
                <div class="feature-grid">
                    <div class="feature-item">
                        <i class="fas fa-chart-line"></i>
                        <span>Analisis Multi-Kriteria</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-trophy"></i>
                        <span>Ranking Prioritas</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan Lengkap</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Pemetaan Jalan</span>
                    </div>
                </div>
            </div>
            
            <div class="pu-slogan">
                <hr>
                <div class="slogan-text">
                     SIGAP MEMBANGUN NEGERI
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-header">
                <h3>Masuk ke Akun</h3>
                <p>Masukkan NIP dan password untuk melanjutkan</p>
            </div>
            
            <!-- Alert Success -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Alert Error -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            
<form method="POST" action="{{ route('login.post') }}" id="loginForm">
    @csrf
    
    <div class="form-group">
        <label class="form-label">
            <i class="fas fa-id-card"></i> NIP
        </label>
        <div class="input-wrapper">
            <i class="fas fa-user input-icon"></i>
            <input type="text" 
                   name="nip" 
                   id="nip"
                   class="form-control @error('nip') is-invalid @enderror" 
                   value="{{ old('nip') }}" 
                   placeholder="Masukkan NIP"
                   autofocus>
        </div>
        @error('nip')
            <div class="invalid-feedback">
                <i class="fas fa-info-circle"></i> {{ $message }}
            </div>
        @enderror
    </div>
    
    <div class="form-group">
        <label class="form-label">
            <i class="fas fa-lock"></i> Password
        </label>
        <div class="input-wrapper">
            <i class="fas fa-key input-icon"></i>
            <input type="password" 
                   name="password" 
                   id="password"
                   class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Masukkan password">
            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
        </div>
        @error('password')
            <div class="invalid-feedback">
                <i class="fas fa-info-circle"></i> {{ $message }}
            </div>
        @enderror
    </div>
    
    <button type="submit" class="btn-login" id="btnLogin">
        <i class="fas fa-sign-in-alt"></i> Masuk ke Dashboard
    </button>
</form>
            
            <div class="login-footer">
                <i class="fas fa-copyright"></i> {{ date('Y') }} Dinas Pekerjaan Umum dan Perumahan Rakyat
            </div>
        </div>
    </div>
    
    <script>
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
        
        // Fill Demo Account
        function fillLogin(nip, password) {
            document.getElementById('nip').value = nip;
            document.getElementById('password').value = password;
            
            // Highlight effect
            const nipField = document.getElementById('nip');
            const passField = document.getElementById('password');
            nipField.style.borderColor = '#F5A623';
            passField.style.borderColor = '#F5A623';
            
            setTimeout(() => {
                nipField.style.borderColor = '';
                passField.style.borderColor = '';
            }, 1000);
        }
        
        // Loading state on submit
        const loginForm = document.getElementById('loginForm');
        const btnLogin = document.getElementById('btnLogin');
        
        if (loginForm) {
            loginForm.addEventListener('submit', function() {
                btnLogin.classList.add('loading');
                btnLogin.innerHTML = '<i class="fas fa-spinner"></i> Memproses...';
                btnLogin.disabled = true;
            });
        }
        
        // Enter key to submit
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const activeElement = document.activeElement;
                if (activeElement && (activeElement.id === 'nip' || activeElement.id === 'password')) {
                    loginForm.submit();
                }
            }
        });
    </script>
</body>
</html>