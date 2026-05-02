@extends('layouts.admin')

@section('title', 'Edit Profil - Admin')
@section('page-title', 'Edit Profil')
@section('page-subtitle', 'Perbarui informasi akun Anda')

@section('content')
<div class="edit-profile-container" style="max-width: 800px; margin: 0 auto;">
    
    <div class="edit-header" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="width: 60px; height: 60px; background: #F9A826; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-edit" style="font-size: 28px; color: #1A2A3A;"></i>
            </div>
            <div>
                <h2 style="color: white; margin: 0; font-size: 24px;">Edit Profil</h2>
                <p style="color: #8BA3BC; margin: 8px 0 0;">Perbarui informasi akun dan foto profil Anda</p>
            </div>
        </div>
    </div>
    
    <div class="edit-card" style="background: white; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden;">
        <form method="POST" action="{{ route('admin.profil.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="padding: 30px;">
                <!-- Foto Profil -->
                <div class="form-group" style="text-align: center; margin-bottom: 30px;">
                    <label class="form-label" style="display: block; margin-bottom: 12px; font-weight: 600;">Foto Profil</label>
                    
                    <div class="photo-container">
                        @if($user->foto)
                            <img src="{{ Storage::url($user->foto) }}" id="currentPhoto" alt="Foto Profil" 
                                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 12px; border: 3px solid #F9A826;">
                        @else
                            <div id="defaultPhoto" style="width: 120px; height: 120px; background: linear-gradient(135deg, #F9A826, #E8912A); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <i class="fas fa-user" style="font-size: 48px; color: white;"></i>
                            </div>
                        @endif
                        
                        <div style="margin-top: 12px;">
                            <label for="foto" class="btn-upload" style="background: #E8EDF2; color: #1A2A3A; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas fa-upload"></i> Upload Foto Baru
                            </label>
                            <input type="file" name="foto" id="foto" accept="image/*" style="display: none;" onchange="previewFoto(this)">
                            
                            @if($user->foto)
                                <button type="button" class="btn-remove" onclick="removeFoto()" style="background: #FEE2E2; color: #EF4444; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px; font-weight: 600; margin-left: 8px;">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <form id="removeFotoForm" action="{{ route('admin.profil.remove-foto') }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                        
                        <div id="previewContainer" style="display: none; margin-top: 16px; padding: 12px; background: #D1FAE5; border-radius: 10px;">
                            <img id="previewImage" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                            <p style="margin: 8px 0 0; font-size: 12px; color: #065F46;">Foto baru siap diupload</p>
                        </div>
                        
                        <small class="form-text" style="display: block; margin-top: 8px; color: #6B7280;">Format: JPG, PNG, GIF. Maks 2MB</small>
                    </div>
                    @error('foto') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                
                <!-- Nama Lengkap -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600;">
                        <i class="fas fa-user" style="color: #F9A826; margin-right: 8px;"></i> Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" style="width: 100%; padding: 12px 16px; border: 2px solid #E2E8F0; border-radius: 10px;" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                
                <!-- Email -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600;">
                        <i class="fas fa-envelope" style="color: #F9A826; margin-right: 8px;"></i> Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" style="width: 100%; padding: 12px 16px; border: 2px solid #E2E8F0; border-radius: 10px;" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                
                <!-- NIP -->
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="display: block; margin-bottom: 8px; font-weight: 600;">
                        <i class="fas fa-id-card" style="color: #F9A826; margin-right: 8px;"></i> NIP <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" class="form-control" style="width: 100%; padding: 12px 16px; border: 2px solid #E2E8F0; border-radius: 10px;" required>
                    @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                
                <hr style="margin: 24px 0; border: none; border-top: 1px solid #E2E8F0;">
                
                <!-- Tombol Aksi -->
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('admin.profil.index') }}" class="btn-cancel" style="background: transparent; color: #1A2A3A; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 500; border: 1px solid #E2E8F0;">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn-save" style="background: #F9A826; color: #1A2A3A; padding: 12px 28px; border-radius: 10px; border: none; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
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
    
    .btn-upload:hover {
        background: #D1D9E6;
        transition: all 0.2s;
    }
    
    .btn-remove:hover {
        background: #EF4444;
        color: white;
        transition: all 0.2s;
    }
    
    .text-danger {
        color: #EF4444;
        font-size: 12px;
    }
    
    @media (max-width: 768px) {
        .edit-profile-container {
            padding: 0 16px;
        }
        
        .edit-header {
            padding: 20px;
        }
        
        .edit-card {
            padding: 0;
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
            
            // Validasi ukuran file
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
                
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