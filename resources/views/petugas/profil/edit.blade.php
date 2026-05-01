@extends('layouts.petugas')

@section('title', 'Edit Profil - Petugas')
@section('page-title', 'Edit Profil')
@section('page-subtitle', 'Perbarui informasi akun Anda')

@section('content')
<div class="edit-profile-container">
    
    <!-- Header -->
    <div class="edit-header" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div class="edit-header-icon" style="width: 60px; height: 60px; background: #F9A826; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-edit" style="font-size: 28px; color: #1A2A3A;"></i>
            </div>
            <div>
                <h2 style="color: white; margin: 0; font-size: 24px;">Edit Profil</h2>
                <p style="color: #8BA3BC; margin: 8px 0 0;">Perbarui informasi akun dan foto profil Anda</p>
            </div>
        </div>
    </div>
    
    <form method="POST" action="{{ route('petugas.profil.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="edit-grid" style="display: grid; grid-template-columns: 350px 1fr; gap: 30px;">
            
            <!-- Kolom Kiri - Foto Profil -->
            <div class="photo-card" style="background: white; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden;">
                <div class="photo-header" style="background: #F8FAFC; padding: 16px 20px; border-bottom: 1px solid #E2E8F0;">
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700;">
                        <i class="fas fa-camera" style="color: #F9A826;"></i> Foto Profil
                    </h3>
                </div>
                <div class="photo-body" style="padding: 30px 20px; text-align: center;">
                    
                    <!-- Foto Preview -->
                    <div class="photo-preview" style="position: relative; display: inline-block;">
                        @if($user->foto)
                            <img src="{{ Storage::url($user->foto) }}" alt="Foto Profil" 
                                 id="currentPhoto"
                                 style="width: 160px; height: 160px; border-radius: 50%; object-fit: cover; border: 4px solid #F9A826; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                        @else
                            <div id="defaultPhoto" style="width: 160px; height: 160px; background: linear-gradient(135deg, #F9A826, #E8912A); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid #F9A826; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                                <i class="fas fa-user" style="font-size: 70px; color: white;"></i>
                            </div>
                        @endif
                        
                        <!-- Badge Edit -->
                        <label for="foto" class="photo-edit-badge" style="position: absolute; bottom: 10px; right: 10px; background: #F9A826; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            <i class="fas fa-camera" style="color: #1A2A3A; font-size: 18px;"></i>
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" style="display: none;" onchange="previewFoto(this)">
                    </div>
                    
                    <!-- Preview Foto Baru -->
                    <div id="previewContainer" style="display: none; margin-top: 20px; padding: 15px; background: #D1FAE5; border-radius: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img id="previewImage" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                            <div style="text-align: left;">
                                <p style="margin: 0; font-weight: 600; color: #065F46;">Foto baru siap diupload</p>
                                <p style="margin: 0; font-size: 12px; color: #065F46;">Klik Simpan Perubahan untuk mengupdate</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Hapus Foto -->
                    @if($user->foto)
                    <div style="margin-top: 20px;">
                        <button type="button" class="btn-remove-photo" onclick="removeFoto()">
                            <i class="fas fa-trash-alt"></i> Hapus Foto
                        </button>
                        <form id="removeFotoForm" action="{{ route('petugas.profil.remove-foto') }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                    @endif
                    
                    <div class="photo-info" style="margin-top: 20px; padding: 12px; background: #F8FAFC; border-radius: 12px;">
                        <i class="fas fa-info-circle" style="color: #F9A826;"></i>
                        <small style="color: #6B7280;">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                    </div>
                </div>
            </div>
            
            <!-- Kolom Kanan - Form Edit -->
            <div class="form-card" style="background: white; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden;">
                <div class="form-header" style="background: #F8FAFC; padding: 16px 24px; border-bottom: 1px solid #E2E8F0;">
                    <h3 style="margin: 0; font-size: 16px; font-weight: 700;">
                        <i class="fas fa-info-circle" style="color: #F9A826;"></i> Informasi Akun
                    </h3>
                </div>
                
                <div style="padding: 24px;">
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user" style="color: #F9A826; width: 20px;"></i>
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope" style="color: #F9A826; width: 20px;"></i>
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    
                    <!-- NIP -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-id-card" style="color: #F9A826; width: 20px;"></i>
                            NIP <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" class="form-control" required>
                        @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    
                    <!-- Divider -->
                    <div class="form-divider" style="margin: 24px 0; border-top: 1px solid #E2E8F0;"></div>
                    
                    <!-- Tombol Aksi -->
                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="{{ route('petugas.profil.index') }}" class="btn-cancel">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .edit-profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Form Styles */
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
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #F9A826;
        box-shadow: 0 0 0 3px rgba(249, 168, 38, 0.1);
    }
    
    .text-danger {
        color: #EF4444;
        font-size: 12px;
    }
    
    /* Photo Card */
    .photo-edit-badge {
        transition: all 0.3s;
    }
    
    .photo-edit-badge:hover {
        background: #E8912A;
        transform: scale(1.1);
    }
    
    .btn-remove-photo {
        background: #FEE2E2;
        color: #EF4444;
        padding: 8px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-remove-photo:hover {
        background: #EF4444;
        color: white;
    }
    
    /* Button Styles */
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
    
    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .photo-card, .form-card {
        animation: fadeIn 0.4s ease-out forwards;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .edit-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .photo-card {
            order: 1;
        }
        
        .form-card {
            order: 2;
        }
        
        .edit-header {
            padding: 20px;
        }
        
        .photo-preview img,
        .photo-preview #defaultPhoto {
            width: 140px;
            height: 140px;
        }
        
        .photo-edit-badge {
            width: 35px;
            height: 35px;
        }
        
        .photo-edit-badge i {
            font-size: 14px;
        }
    }
    
    @media (max-width: 480px) {
        .btn-save, .btn-cancel {
            padding: 10px 20px;
            font-size: 13px;
        }
        
        .photo-body {
            padding: 20px;
        }
    }
</style>

<script>
    function previewFoto(input) {
        const previewContainer = document.getElementById('previewContainer');
        const previewImage = document.getElementById('previewImage');
        const currentPhoto = document.getElementById('currentPhoto');
        const defaultPhoto = document.getElementById('defaultPhoto');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validasi ukuran file (maks 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                input.value = '';
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung! Gunakan JPG, PNG, atau GIF.');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
                
                // Sembunyikan foto lama
                if (currentPhoto) currentPhoto.style.display = 'none';
                if (defaultPhoto) defaultPhoto.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
            if (currentPhoto) currentPhoto.style.display = 'block';
            if (defaultPhoto) defaultPhoto.style.display = 'flex';
        }
    }
    
    function removeFoto() {
        if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
            document.getElementById('removeFotoForm').submit();
        }
    }
</script>
@endsection