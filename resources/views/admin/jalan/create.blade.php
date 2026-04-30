@extends('layouts.admin')

@section('title', 'Tambah Data Jalan - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Tambah Data Jalan')
@section('page-subtitle', 'Isi form berikut untuk menambahkan data jalan baru')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <div class="form-header-icon">
                <i class="fas fa-road"></i>
            </div>
            <div class="form-header-text">
                <h3>Form Tambah Jalan</h3>
                <p>Lengkapi data jalan dengan benar untuk mendukung akurasi perhitungan prioritas perbaikan</p>
            </div>
        </div>
        
        <form action="{{ route('admin.jalan.store') }}" method="POST" class="main-form" id="jalanForm">
            @csrf
            
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    <span>Informasi Dasar Jalan</span>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="kode" class="form-label required">
                            <i class="fas fa-barcode"></i> Kode Jalan
                        </label>
                        <input type="text" 
                               id="kode" 
                               name="kode" 
                               class="form-control @error('kode') is-invalid @enderror" 
                               value="{{ old('kode', $kodeOtomatis) }}" 
                               placeholder="Contoh: JL-001"
                               required>
                        <small class="form-text text-muted">Kode unik untuk identifikasi jalan</small>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="nama" class="form-label required">
                            <i class="fas fa-signature"></i> Nama Jalan
                        </label>
                        <input type="text" 
                               id="nama" 
                               name="nama" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama') }}" 
                               placeholder="Contoh: Jalan Merdeka"
                               required>
                        <small class="form-text text-muted">Nama lengkap jalan sesuai papan nama</small>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="deskripsi" class="form-label">
                            <i class="fas fa-align-left"></i> Deskripsi
                        </label>
                        <textarea id="deskripsi" 
                                  name="deskripsi" 
                                  class="form-control @error('deskripsi') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Deskripsikan kondisi jalan, lingkungan sekitar, dll...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Informasi Lokasi</span>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="lokasi" class="form-label required">
                            <i class="fas fa-city"></i> Lokasi
                        </label>
                        <input type="text" 
                               id="lokasi" 
                               name="lokasi" 
                               class="form-control @error('lokasi') is-invalid @enderror" 
                               value="{{ old('lokasi') }}" 
                               placeholder="Contoh: Kelurahan Merdeka, Kecamatan Kota"
                               required>
                        <small class="form-text text-muted">Kelurahan/Kecamatan/Daerah</small>
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="panjang" class="form-label required">
                            <i class="fas fa-ruler"></i> Panjang Jalan
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   id="panjang" 
                                   name="panjang" 
                                   step="0.01" 
                                   class="form-control @error('panjang') is-invalid @enderror" 
                                   value="{{ old('panjang') }}" 
                                   placeholder="0"
                                   required>
                            <span class="input-group-text">meter</span>
                        </div>
                        <small class="form-text text-muted">Panjang total jalan dalam meter</small>
                        @error('panjang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-map-pin"></i>
                    <span>Koordinat Lokasi (Opsional)</span>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="latitude" class="form-label">
                            <i class="fas fa-latitude"></i> Latitude
                        </label>
                        <input type="number" 
                               id="latitude" 
                               name="latitude" 
                               step="any" 
                               class="form-control @error('latitude') is-invalid @enderror" 
                               value="{{ old('latitude') }}" 
                               placeholder="Contoh: -6.200000">
                        <small class="form-text text-muted">Garis lintang (misal: -6.200000)</small>
                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="longitude" class="form-label">
                            <i class="fas fa-longitude"></i> Longitude
                        </label>
                        <input type="number" 
                               id="longitude" 
                               name="longitude" 
                               step="any" 
                               class="form-control @error('longitude') is-invalid @enderror" 
                               value="{{ old('longitude') }}" 
                               placeholder="Contoh: 106.800000">
                        <small class="form-text text-muted">Garis bujur (misal: 106.800000)</small>
                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Tips Mengisi Koordinat:</strong><br>
                        Buka Google Maps → Klik kanan pada lokasi → Pilih koordinat yang muncul → Copy paste di sini
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-toggle-on"></i>
                    <span>Status</span>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           class="form-check-input" 
                           value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">
                        Aktifkan jalan ini
                    </label>
                    <small class="form-text-check">Jalan aktif akan masuk dalam perhitungan prioritas perbaikan</small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Data Jalan
                </button>
                <a href="{{ route('admin.jalan.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }
    
    .form-card {
        background: var(--bg-white);
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    .form-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        padding: 24px 30px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .form-header-icon {
        width: 60px;
        height: 60px;
        background: var(--secondary);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: var(--primary-dark);
    }
    
    .form-header-text h3 {
        color: white;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .form-header-text p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
    }
    
    .main-form {
        padding: 30px;
    }
    
    .form-section {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border);
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        font-weight: 700;
        font-size: 16px;
        color: var(--text-dark);
    }
    
    .section-title i {
        color: var(--secondary);
        font-size: 18px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .form-group {
        display: flex;
        flex-direction: column;
    }
    
    .form-group.full-width {
        grid-column: span 2;
    }
    
    .form-label {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 8px;
        color: var(--text-dark);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-label.required::after {
        content: '*';
        color: #EF4444;
        margin-left: 4px;
    }
    
    .form-control {
        padding: 12px 16px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        transition: var(--transition);
        background: var(--bg-white);
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(249, 168, 38, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #EF4444;
        background-color: #FEF2F2;
    }
    
    .invalid-feedback {
        color: #EF4444;
        font-size: 12px;
        margin-top: 6px;
    }
    
    .form-text {
        font-size: 11px;
        color: var(--text-lighter);
        margin-top: 6px;
    }
    
    .input-group {
        display: flex;
        align-items: stretch;
    }
    
    .input-group .form-control {
        flex: 1;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .input-group-text {
        padding: 12px 16px;
        background: var(--bg-light);
        border: 1px solid var(--border);
        border-left: none;
        border-radius: 0 10px 10px 0;
        font-size: 14px;
        color: var(--text-light);
    }
    
    .form-check {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
        background: var(--bg-light);
        border-radius: 12px;
    }
    
    .form-check-input {
        width: 20px;
        height: 20px;
        margin-top: 2px;
        cursor: pointer;
        accent-color: var(--secondary);
    }
    
    .form-check-label {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-dark);
        cursor: pointer;
    }
    
    .form-text-check {
        font-size: 12px;
        color: var(--text-light);
        margin-top: 4px;
        display: block;
    }
    
    .info-box {
        background: #FEF3E0;
        border-left: 4px solid var(--secondary);
        padding: 12px 16px;
        border-radius: 10px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 16px;
    }
    
    .info-box i {
        color: var(--secondary);
        font-size: 18px;
        margin-top: 2px;
    }
    
    .info-box div {
        flex: 1;
        font-size: 13px;
        color: var(--text-dark);
        line-height: 1.5;
    }
    
    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
        color: var(--primary-dark);
        padding: 12px 28px;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3);
    }
    
    .btn-cancel {
        background: var(--bg-light);
        color: var(--text-dark);
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: var(--transition);
        border: 1px solid var(--border);
    }
    
    .btn-cancel:hover {
        background: var(--border);
    }
    
    @media (max-width: 768px) {
        .main-form {
            padding: 20px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-group.full-width {
            grid-column: span 1;
        }
        
        .form-header {
            padding: 20px;
            flex-direction: column;
            text-align: center;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn-submit, .btn-cancel {
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto capitalize nama jalan
    document.getElementById('nama')?.addEventListener('input', function(e) {
        let words = e.target.value.split(' ');
        let capitalized = words.map(word => {
            if (word.length > 0) {
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            }
            return word;
        }).join(' ');
        e.target.value = capitalized;
    });
    
    // Auto uppercase untuk kode
    document.getElementById('kode')?.addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
    
    // Validasi panjang jalan tidak boleh negatif
    document.getElementById('panjang')?.addEventListener('change', function(e) {
        if (parseFloat(e.target.value) < 0) {
            e.target.value = 0;
        }
    });
    
    // Preview koordinat (opsional)
    function validateCoordinates() {
        const lat = document.getElementById('latitude').value;
        const lon = document.getElementById('longitude').value;
        
        if (lat && (lat < -90 || lat > 90)) {
            alert('Latitude harus antara -90 dan 90');
            return false;
        }
        
        if (lon && (lon < -180 || lon > 180)) {
            alert('Longitude harus antara -180 dan 180');
            return false;
        }
        
        return true;
    }
    
    // Tambahkan validasi sebelum submit
    document.getElementById('jalanForm')?.addEventListener('submit', function(e) {
        if (!validateCoordinates()) {
            e.preventDefault();
        }
    });
</script>
@endpush