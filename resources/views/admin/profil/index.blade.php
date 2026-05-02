@extends('layouts.admin')

@section('title', 'Profil Saya - Admin')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Informasi akun administrator')

@section('content')
<div class="profil-container">
    <div class="profil-grid" style="display: grid; grid-template-columns: 300px 1fr; gap: 30px;">
        
        <!-- Sidebar Profil -->
        <div class="profil-sidebar" style="background: white; border-radius: 20px; padding: 30px; text-align: center; border: 1px solid #E2E8F0;">
            <!-- Foto Profil -->
            <div class="avatar-container" style="position: relative; display: inline-block;">
                @if($user->foto)
                    <img src="{{ Storage::url($user->foto) }}" alt="Foto Profil" 
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #F9A826;">
                @else
                    <div style="width: 150px; height: 150px; background: linear-gradient(135deg, #F9A826, #E8912A); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="fas fa-user" style="font-size: 64px; color: white;"></i>
                    </div>
                @endif
            </div>
            
            <h3 style="margin-top: 20px; margin-bottom: 4px; font-size: 20px;">{{ $user->name }}</h3>
            <p style="color: #F9A826; font-weight: 600; margin-bottom: 16px;">
                <i class="fas fa-shield-alt"></i> Administrator
            </p>
            
            <div style="border-top: 1px solid #E2E8F0; padding-top: 20px; margin-top: 10px;">
                <a href="{{ route('admin.profil.edit') }}" class="btn-edit-profile" style="display: inline-block; background: #F9A826; color: #1A2A3A; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; width: 100%; text-align: center;">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
            </div>
        </div>
        
        <!-- Informasi Profil -->
        <div class="profil-info" style="background: white; border-radius: 20px; padding: 30px; border: 1px solid #E2E8F0;">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #F9A826; padding-bottom: 12px;">
                <i class="fas fa-info-circle" style="color: #F9A826;"></i> Informasi Akun
            </h3>
            
            <div class="info-table" style="width: 100%;">
                <div class="info-row" style="display: flex; padding: 12px 0; border-bottom: 1px solid #F1F5F9;">
                    <div class="info-label" style="width: 30%; font-weight: 600; color: #4B6B8A;">
                        <i class="fas fa-user" style="width: 24px; color: #F9A826;"></i> Nama Lengkap
                    </div>
                    <div class="info-value" style="flex: 1;">: <strong>{{ $user->name }}</strong></div>
                </div>
                <div class="info-row" style="display: flex; padding: 12px 0; border-bottom: 1px solid #F1F5F9;">
                    <div class="info-label" style="width: 30%; font-weight: 600; color: #4B6B8A;">
                        <i class="fas fa-envelope" style="width: 24px; color: #F9A826;"></i> Email
                    </div>
                    <div class="info-value" style="flex: 1;">: {{ $user->email }}</div>
                </div>
                <div class="info-row" style="display: flex; padding: 12px 0; border-bottom: 1px solid #F1F5F9;">
                    <div class="info-label" style="width: 30%; font-weight: 600; color: #4B6B8A;">
                        <i class="fas fa-id-card" style="width: 24px; color: #F9A826;"></i> NIP
                    </div>
                    <div class="info-value" style="flex: 1;">: {{ $user->nip ?? '-' }}</div>
                </div>
                <div class="info-row" style="display: flex; padding: 12px 0; border-bottom: 1px solid #F1F5F9;">
                    <div class="info-label" style="width: 30%; font-weight: 600; color: #4B6B8A;">
                        <i class="fas fa-user-tag" style="width: 24px; color: #F9A826;"></i> Role
                    </div>
                    <div class="info-value" style="flex: 1;">: 
                        <span style="background: #D1FAE5; color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Administrator</span>
                    </div>
                </div>
                <div class="info-row" style="display: flex; padding: 12px 0; border-bottom: 1px solid #F1F5F9;">
                    <div class="info-label" style="width: 30%; font-weight: 600; color: #4B6B8A;">
                        <i class="fas fa-calendar-alt" style="width: 24px; color: #F9A826;"></i> Bergabung Sejak
                    </div>
                    <div class="info-value" style="flex: 1;">: {{ $user->created_at->translatedFormat('d F Y') }}</div>
                </div>
                <div class="info-row" style="display: flex; padding: 12px 0;">
                    <div class="info-label" style="width: 30%; font-weight: 600; color: #4B6B8A;">
                        <i class="fas fa-clock" style="width: 24px; color: #F9A826;"></i> Terakhir Update
                    </div>
                    <div class="info-value" style="flex: 1;">: {{ $user->updated_at->translatedFormat('d F Y H:i') }}</div>
                </div>
            </div>
            
            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #E2E8F0;">
                <a href="{{ route('admin.profil.change-password') }}" class="btn-change-password" style="display: inline-flex; align-items: center; gap: 8px; background: #E8EDF2; color: #1A2A3A; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-key"></i> Ganti Password
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .profil-container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .btn-edit-profile:hover {
        background: #E8912A;
        transform: translateY(-2px);
        transition: all 0.3s;
    }
    
    .btn-change-password:hover {
        background: #D1D9E6;
        transition: all 0.3s;
    }
    
    @media (max-width: 768px) {
        .profil-grid {
            grid-template-columns: 1fr !important;
            gap: 20px;
        }
        
        .profil-sidebar {
            order: 1;
        }
        
        .profil-info {
            order: 2;
        }
        
        .info-row {
            flex-direction: column;
            gap: 8px;
        }
        
        .info-label {
            width: 100% !important;
        }
    }
</style>
@endsection