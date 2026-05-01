@extends('layouts.petugas')

@section('title', 'Ganti Password - Petugas')
@section('page-title', 'Ganti Password')
@section('page-subtitle', 'Perbarui password akun Anda')

@section('content')
<div class="change-password-container" style="max-width: 600px; margin: 0 auto;">
    
    <!-- Header -->
    <div class="password-header" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; text-align: center;">
        <div style="width: 70px; height: 70px; background: #F9A826; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <i class="fas fa-key" style="font-size: 32px; color: #1A2A3A;"></i>
        </div>
        <h2 style="color: white; margin: 0; font-size: 24px;">Ganti Password</h2>
        <p style="color: #8BA3BC; margin: 8px 0 0;">Gunakan password yang kuat dan mudah diingat</p>
    </div>
    
    <!-- Form -->
    <div class="password-card" style="background: white; border-radius: 20px; border: 1px solid #E2E8F0; padding: 30px;">
        <form method="POST" action="{{ route('petugas.profil.update-password') }}">
            @csrf
            
            <!-- Alert Informasi -->
            <div class="alert-info" style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 12px 16px; border-radius: 12px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-shield-alt" style="color: #F59E0B; font-size: 20px;"></i>
                    <div style="font-size: 13px; color: #92400E;">
                        <strong>Tips Keamanan:</strong> Gunakan minimal 6 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol.
                    </div>
                </div>
            </div>
            
            <!-- Password Saat Ini -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-lock" style="color: #F9A826;"></i>
                    Password Saat Ini <span class="text-danger">*</span>
                </label>
                <div class="input-wrapper">
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
            <!-- Password Baru -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-key" style="color: #F9A826;"></i>
                    Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                <div id="passwordStrength" style="margin-top: 8px;"></div>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
            <!-- Konfirmasi Password Baru -->
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-check-circle" style="color: #F9A826;"></i>
                    Konfirmasi Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                <div id="passwordMatch" style="margin-top: 8px;"></div>
                @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
            <!-- Progress Bar Kekuatan Password -->
            <div class="strength-meter" style="margin: 20px 0; display: none;">
                <div style="display: flex; gap: 8px;">
                    <div class="strength-bar" data-strength="1" style="flex: 1; height: 6px; background: #E2E8F0; border-radius: 3px;"></div>
                    <div class="strength-bar" data-strength="2" style="flex: 1; height: 6px; background: #E2E8F0; border-radius: 3px;"></div>
                    <div class="strength-bar" data-strength="3" style="flex: 1; height: 6px; background: #E2E8F0; border-radius: 3px;"></div>
                    <div class="strength-bar" data-strength="4" style="flex: 1; height: 6px; background: #E2E8F0; border-radius: 3px;"></div>
                    <div class="strength-bar" data-strength="5" style="flex: 1; height: 6px; background: #E2E8F0; border-radius: 3px;"></div>
                </div>
            </div>
            
            <hr style="margin: 24px 0; border: none; border-top: 1px solid #E2E8F0;">
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('petugas.profil.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Ganti Password
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .change-password-container {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1A2A3A;
        font-size: 14px;
    }
    
    .form-label i {
        width: 24px;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        padding-right: 45px;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #F9A826;
        box-shadow: 0 0 0 3px rgba(249, 168, 38, 0.1);
    }
    
    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #9CA3AF;
        font-size: 16px;
        transition: all 0.2s;
    }
    
    .toggle-password:hover {
        color: #F9A826;
    }
    
    .btn-save {
        background: #F9A826;
        color: #1A2A3A;
        padding: 12px 28px;
        border-radius: 12px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .btn-save:hover {
        background: #E8912A;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3);
    }
    
    .btn-cancel {
        background: transparent;
        color: #1A2A3A;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid #E2E8F0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        border-color: #F9A826;
        color: #F9A826;
        transform: translateY(-2px);
    }
    
    .text-danger {
        color: #EF4444;
        font-size: 12px;
    }
    
    .strength-weak { color: #EF4444; }
    .strength-medium { color: #F59E0B; }
    .strength-strong { color: #10B981; }
    
    @media (max-width: 768px) {
        .change-password-container {
            padding: 0 16px;
        }
        
        .password-header {
            padding: 20px;
        }
        
        .password-card {
            padding: 20px;
        }
    }
</style>

<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const button = input.parentElement.querySelector('.toggle-password i');
        
        if (input.type === 'password') {
            input.type = 'text';
            button.classList.remove('fa-eye-slash');
            button.classList.add('fa-eye');
        } else {
            input.type = 'password';
            button.classList.remove('fa-eye');
            button.classList.add('fa-eye-slash');
        }
    }
    
    // Cek kekuatan password
    const passwordInput = document.getElementById('password');
    const strengthMeter = document.querySelector('.strength-meter');
    const strengthBars = document.querySelectorAll('.strength-bar');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length === 0) {
                strengthMeter.style.display = 'none';
                return;
            }
            
            strengthMeter.style.display = 'block';
            
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            let strengthText = '';
            let strengthClass = '';
            
            if (strength <= 2) {
                strengthText = 'Lemah';
                strengthClass = 'strength-weak';
            } else if (strength <= 4) {
                strengthText = 'Sedang';
                strengthClass = 'strength-medium';
            } else {
                strengthText = 'Kuat';
                strengthClass = 'strength-strong';
            }
            
            document.getElementById('passwordStrength').innerHTML = `<small class="${strengthClass}"><i class="fas fa-shield-alt"></i> Kekuatan password: ${strengthText}</small>`;
            
            // Update bars
            strengthBars.forEach((bar, index) => {
                if (index < strength) {
                    if (strength <= 2) bar.style.background = '#EF4444';
                    else if (strength <= 4) bar.style.background = '#F59E0B';
                    else bar.style.background = '#10B981';
                } else {
                    bar.style.background = '#E2E8F0';
                }
            });
        });
    }
    
    // Cek konfirmasi password
    const confirmInput = document.getElementById('password_confirmation');
    
    if (confirmInput && passwordInput) {
        function checkPasswordMatch() {
            if (confirmInput.value === passwordInput.value && confirmInput.value !== '') {
                document.getElementById('passwordMatch').innerHTML = '<small style="color: #10B981;"><i class="fas fa-check-circle"></i> Password cocok</small>';
            } else if (confirmInput.value !== '') {
                document.getElementById('passwordMatch').innerHTML = '<small style="color: #EF4444;"><i class="fas fa-times-circle"></i> Password tidak cocok</small>';
            } else {
                document.getElementById('passwordMatch').innerHTML = '';
            }
        }
        
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmInput.addEventListener('input', checkPasswordMatch);
    }
</script>
@endsection