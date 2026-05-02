{{-- resources/views/admin/profil/change-password.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ganti Password - Admin')
@section('page-title', 'Ganti Password')
@section('page-subtitle', 'Perbarui password akun Anda')

@section('content')
<div class="change-password-container" style="max-width: 600px; margin: 0 auto;">
    
    <div class="password-header" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; text-align: center;">
        <div style="width: 70px; height: 70px; background: #F9A826; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
            <i class="fas fa-key" style="font-size: 32px; color: #1A2A3A;"></i>
        </div>
        <h2 style="color: white; margin: 0; font-size: 24px;">Ganti Password</h2>
        <p style="color: #8BA3BC; margin: 8px 0 0;">Gunakan password yang kuat dan mudah diingat</p>
    </div>
    
    <div class="password-card" style="background: white; border-radius: 20px; border: 1px solid #E2E8F0; padding: 30px;">
        <form method="POST" action="{{ route('admin.profil.update-password') }}">
            @csrf
            
            <div class="alert-info" style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 12px 16px; border-radius: 12px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-shield-alt" style="color: #F59E0B; font-size: 18px;"></i>
                    <div style="font-size: 13px; color: #92400E;">
                        <strong>Tips Keamanan:</strong> Gunakan minimal 6 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol.
                    </div>
                </div>
            </div>
            
            <!-- Password Saat Ini -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <i class="fas fa-lock" style="color: #F9A826; margin-right: 8px;"></i> Password Saat Ini <span class="text-danger">*</span>
                </label>
                <div class="input-wrapper" style="position: relative;">
                    <input type="password" name="current_password" id="current_password" class="form-control" style="width: 100%; padding: 12px 16px; border: 2px solid #E2E8F0; border-radius: 10px; padding-right: 45px;" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('current_password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9CA3AF;">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
            <!-- Password Baru -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <i class="fas fa-key" style="color: #F9A826; margin-right: 8px;"></i> Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-wrapper" style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control" style="width: 100%; padding: 12px 16px; border: 2px solid #E2E8F0; border-radius: 10px; padding-right: 45px;" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9CA3AF;">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                <div id="passwordStrength" style="margin-top: 8px;"></div>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
            <!-- Konfirmasi Password Baru -->
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600;">
                    <i class="fas fa-check-circle" style="color: #F9A826; margin-right: 8px;"></i> Konfirmasi Password Baru <span class="text-danger">*</span>
                </label>
                <div class="input-wrapper" style="position: relative;">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" style="width: 100%; padding: 12px 16px; border: 2px solid #E2E8F0; border-radius: 10px; padding-right: 45px;" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9CA3AF;">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                <div id="passwordMatch" style="margin-top: 8px;"></div>
                @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
            <hr style="margin: 24px 0; border: none; border-top: 1px solid #E2E8F0;">
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="{{ route('admin.profil.index') }}" class="btn-cancel" style="background: transparent; color: #1A2A3A; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 500; border: 1px solid #E2E8F0;">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button type="submit" class="btn-save" style="background: #F9A826; color: #1A2A3A; padding: 12px 28px; border-radius: 10px; border: none; font-weight: 700; cursor: pointer;">
                    <i class="fas fa-save"></i> Ganti Password
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-control:focus {
        outline: none;
        border-color: #F9A826 !important;
        box-shadow: 0 0 0 3px rgba(249, 168, 38, 0.1);
    }
    
    .btn-save:hover {
        background: #E8912A;
        transform: translateY(-2px);
        transition: all 0.3s;
    }
    
    .btn-cancel:hover {
        border-color: #F9A826;
        color: #F9A826;
        transition: all 0.3s;
    }
    
    .toggle-password:hover {
        color: #F9A826;
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
        const button = input.nextElementSibling.querySelector('i');
        
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
    const passwordStrength = document.getElementById('passwordStrength');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length === 0) {
                passwordStrength.innerHTML = '';
                return;
            }
            
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
            
            passwordStrength.innerHTML = `<small class="${strengthClass}"><i class="fas fa-shield-alt"></i> Kekuatan password: ${strengthText}</small>`;
        });
    }
    
    // Cek konfirmasi password
    const confirmInput = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('passwordMatch');
    
    if (confirmInput && passwordInput) {
        function checkPasswordMatch() {
            if (confirmInput.value === passwordInput.value && confirmInput.value !== '') {
                passwordMatch.innerHTML = '<small style="color: #10B981;"><i class="fas fa-check-circle"></i> Password cocok</small>';
            } else if (confirmInput.value !== '') {
                passwordMatch.innerHTML = '<small style="color: #EF4444;"><i class="fas fa-times-circle"></i> Password tidak cocok</small>';
            } else {
                passwordMatch.innerHTML = '';
            }
        }
        
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmInput.addEventListener('input', checkPasswordMatch);
    }
</script>
@endsection