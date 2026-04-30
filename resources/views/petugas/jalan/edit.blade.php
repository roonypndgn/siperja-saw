@extends('layouts.petugas')

@section('title', 'Edit Data Jalan - Petugas')
@section('page-title', 'Edit Data Jalan')
@section('page-subtitle', 'Perbarui informasi data jalan yang sudah ada')

@section('content')
<div class="stat-card">
    <form method="POST" action="{{ route('petugas.jalan.update', $jalan->id) }}" id="formJalan">
        @csrf
        @method('PUT')
        
        <!-- Alert Informasi -->
        <div style="background: #FEF3E0; border-left: 4px solid #F9A826; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-edit" style="font-size: 24px; color: #F9A826;"></i>
                <div>
                    <strong style="color: #1A2A3A;">Mode Edit</strong>
                    <p style="margin: 5px 0 0; color: #6B7280; font-size: 13px;">
                        Anda sedang mengedit data jalan. Perubahan akan langsung tersimpan setelah klik "Update Data".
                    </p>
                </div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            
            <!-- KOLOM KIRI - Informasi Dasar -->
            <div>
                <div style="background: #F8FAFC; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1A2A3A; margin-bottom: 20px;">
                        <i class="fas fa-info-circle" style="color: #F9A826;"></i> Informasi Dasar
                    </h3>
                    
                    <!-- Kode Jalan -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            Kode Jalan <span style="color: #EF4444;">*</span>
                            <span style="font-size: 11px; color: #F9A826;">(Tidak bisa diubah)</span>
                        </label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode', $jalan->kode) }}" 
                               style="width: 100%; padding: 12px; border: 2px solid #E2E8F0; border-radius: 10px; background: #F8FAFC;"
                               readonly>
                        <small style="color: #6B7280; font-size: 11px;">Kode jalan tidak dapat diubah untuk menjaga integritas data</small>
                    </div>
                    
                    <!-- Nama Jalan -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            Nama Jalan <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $jalan->nama) }}" 
                               style="width: 100%; padding: 12px; border: 2px solid {{ $errors->has('nama') ? '#EF4444' : '#E2E8F0' }}; border-radius: 10px;">
                        @error('nama') <small style="color: #EF4444;">{{ $message }}</small> @enderror
                    </div>
                    
                    <!-- Lokasi -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            Lokasi <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $jalan->lokasi) }}" 
                               style="width: 100%; padding: 12px; border: 2px solid {{ $errors->has('lokasi') ? '#EF4444' : '#E2E8F0' }}; border-radius: 10px;">
                        @error('lokasi') <small style="color: #EF4444;">{{ $message }}</small> @enderror
                    </div>
                    
                    <!-- Panjang Jalan -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            Panjang (meter) <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="number" step="0.01" name="panjang" id="panjang" value="{{ old('panjang', $jalan->panjang) }}" 
                               style="width: 100%; padding: 12px; border: 2px solid {{ $errors->has('panjang') ? '#EF4444' : '#E2E8F0' }}; border-radius: 10px;">
                        <div id="panjangDisplay" style="margin-top: 5px; font-size: 12px; color: #6B7280;"></div>
                        @error('panjang') <small style="color: #EF4444;">{{ $message }}</small> @enderror
                    </div>
                    
                    <!-- Deskripsi -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            Deskripsi Jalan
                        </label>
                        <textarea name="deskripsi" rows="4" 
                                  style="width: 100%; padding: 12px; border: 2px solid #E2E8F0; border-radius: 10px; resize: vertical;">{{ old('deskripsi', $jalan->deskripsi) }}</textarea>
                        <small style="color: #6B7280; font-size: 11px;">Deskripsikan kondisi jalan, jenis kerusakan, dll</small>
                    </div>
                </div>
            </div>
            
            <!-- KOLOM KANAN - Koordinat -->
            <div>
                <div style="background: #F8FAFC; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1A2A3A; margin-bottom: 20px;">
                        <i class="fas fa-map-marker-alt" style="color: #F9A826;"></i> Koordinat Lokasi
                    </h3>
                    
                    <!-- Pilihan Metode -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">Pilih Metode:</label>
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <button type="button" id="btnGps" class="btn-method">
                                <i class="fas fa-satellite-dish"></i> GPS / Lokasi Saya
                            </button>
                            <button type="button" id="btnAddress" class="btn-method">
                                <i class="fas fa-search-location"></i> Cari dari Alamat
                            </button>
                            <button type="button" id="btnManual" class="btn-method">
                                <i class="fas fa-keyboard"></i> Input Manual
                            </button>
                            <button type="button" id="btnCurrent" class="btn-method active">
                                <i class="fas fa-database"></i> Koordinat Saat Ini
                            </button>
                        </div>
                    </div>
                    
                    <!-- Panel Koordinat Saat Ini -->
                    <div id="panelCurrent" class="method-panel">
                        <div style="background: #E8EDF2; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                            <p style="margin-bottom: 10px; font-size: 13px;">
                                <i class="fas fa-info-circle"></i> Koordinat yang tersimpan saat ini.
                            </p>
                            @if($jalan->latitude && $jalan->longitude)
                                <div style="background: white; padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                                    <strong>Latitude:</strong> {{ $jalan->latitude }}<br>
                                    <strong>Longitude:</strong> {{ $jalan->longitude }}
                                </div>
                                <a href="https://www.google.com/maps?q={{ $jalan->latitude }},{{ $jalan->longitude }}" 
                                   target="_blank" style="color: #F9A826; text-decoration: none;">
                                    <i class="fas fa-external-link-alt"></i> Lihat di Google Maps
                                </a>
                            @else
                                <div style="background: #FEF3E0; padding: 10px; border-radius: 8px; color: #92400E;">
                                    <i class="fas fa-exclamation-triangle"></i> Belum ada koordinat tersimpan.
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Panel GPS -->
                    <div id="panelGps" class="method-panel" style="display: none;">
                        <div style="background: #E8EDF2; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                            <p style="margin-bottom: 10px; font-size: 13px;">
                                <i class="fas fa-info-circle"></i> Klik tombol di bawah untuk mendapatkan lokasi Anda saat ini.
                            </p>
                            <button type="button" id="getLocation" class="btn-gps">
                                <i class="fas fa-crosshairs"></i> Ambil Lokasi Saya
                            </button>
                        </div>
                        <div id="gpsStatus" style="display: none; padding: 10px; border-radius: 8px; margin-top: 10px;"></div>
                    </div>
                    
                    <!-- Panel Cari Alamat -->
                    <div id="panelAddress" class="method-panel" style="display: none;">
                        <div style="background: #E8EDF2; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                            <p style="margin-bottom: 10px; font-size: 13px;">
                                <i class="fas fa-info-circle"></i> Masukkan nama jalan atau lokasi untuk mencari koordinat.
                            </p>
                            <div style="display: flex; gap: 10px;">
                                <input type="text" id="searchAddress" placeholder="Contoh: Jalan Sudirman, Jakarta" 
                                       value="{{ old('lokasi', $jalan->lokasi) }}"
                                       style="flex: 1; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                                <button type="button" id="btnSearchAddress" class="btn-search">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                        <div id="addressStatus" style="display: none; padding: 10px; border-radius: 8px; margin-top: 10px;"></div>
                    </div>
                    
                    <!-- Panel Manual -->
                    <div id="panelManual" class="method-panel" style="display: none;">
                        <div style="background: #E8EDF2; padding: 15px; border-radius: 10px;">
                            <p style="margin-bottom: 10px; font-size: 13px;">
                                <i class="fas fa-info-circle"></i> Masukkan koordinat secara manual.
                            </p>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                <div>
                                    <label style="font-size: 12px;">Latitude</label>
                                    <input type="text" id="manualLat" value="{{ old('latitude', $jalan->latitude) }}" 
                                           style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                                </div>
                                <div>
                                    <label style="font-size: 12px;">Longitude</label>
                                    <input type="text" id="manualLng" value="{{ old('longitude', $jalan->longitude) }}" 
                                           style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                                </div>
                            </div>
                            <button type="button" id="btnUseManual" class="btn-use" style="margin-top: 10px; width: 100%;">
                                <i class="fas fa-check"></i> Gunakan Koordinat Ini
                            </button>
                        </div>
                    </div>
                    
                    <!-- Hasil Koordinat -->
                    <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 10px; border: 1px solid #E2E8F0;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            <i class="fas fa-map-pin" style="color: #F9A826;"></i> Koordinat Akan Disimpan
                        </label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div>
                                <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $jalan->latitude) }}" 
                                       style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC;"
                                       placeholder="Latitude">
                            </div>
                            <div>
                                <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $jalan->longitude) }}" 
                                       style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC;"
                                       placeholder="Longitude">
                            </div>
                        </div>
                        <small style="color: #6B7280; font-size: 11px;">Koordinat akan disimpan setelah klik "Update Data"</small>
                    </div>
                    
                    <!-- Link Google Maps -->
                    <div id="googleMapsLink" style="display: {{ ($jalan->latitude && $jalan->longitude) ? 'block' : 'none' }}; margin-top: 15px; text-align: center;">
                        <a href="https://www.google.com/maps?q={{ $jalan->latitude }},{{ $jalan->longitude }}" 
                           target="_blank" id="mapsUrl" style="color: #F9A826; text-decoration: none;">
                            <i class="fas fa-external-link-alt"></i> Lihat di Google Maps
                        </a>
                    </div>
                </div>
                
                <!-- Status Aktif -->
                <div style="background: #FEF3E0; padding: 20px; border-radius: 12px; border-left: 4px solid #F9A826;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $jalan->is_active) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                        <div>
                            <span style="font-weight: 700;">Status Aktif</span>
                            <div style="font-size: 12px; color: #6B7280;">Centang jika jalan masih aktif dan layak dinilai</div>
                        </div>
                    </label>
                </div>
                
                <!-- Info Tambahan -->
                <div style="margin-top: 15px; padding: 12px; background: #E8EDF2; border-radius: 8px; font-size: 12px; color: #6B7280;">
                    <i class="fas fa-history"></i> Terakhir diperbarui: {{ $jalan->updated_at->translatedFormat('d F Y H:i') }}
                </div>
            </div>
        </div>
        
        <hr style="margin: 30px 0 20px;">
        
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('petugas.jalan.index') }}" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
            <a href="{{ route('petugas.jalan.show', $jalan->id) }}" class="btn-secondary">
                <i class="fas fa-eye"></i> Lihat Detail
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Update Data
            </button>
        </div>
    </form>
</div>

<style>
    .btn-method {
        padding: 10px 20px;
        border: 2px solid #E2E8F0;
        background: white;
        border-radius: 30px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.3s;
    }
    .btn-method.active {
        border-color: #F9A826;
        background: #FEF3E0;
        color: #F9A826;
    }
    .btn-method:hover {
        border-color: #F9A826;
    }
    .btn-gps, .btn-search, .btn-use {
        background: #1A2A3A;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-gps:hover, .btn-search:hover, .btn-use:hover {
        background: #F9A826;
        color: #1A2A3A;
    }
    .btn-primary {
        background: #F9A826;
        color: #1A2A3A;
        padding: 12px 28px;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        background: #E8912A;
        transform: translateY(-2px);
    }
    .btn-secondary {
        background: #E8EDF2;
        color: #1A2A3A;
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-secondary:hover {
        background: #D1D9E6;
    }
    .btn-outline {
        background: transparent;
        color: #1A2A3A;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid #E2E8F0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-outline:hover {
        border-color: #F9A826;
        color: #F9A826;
    }
    .method-panel {
        margin-top: 15px;
    }
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid white;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.6s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const googleMapsLink = document.getElementById('googleMapsLink');
    const mapsUrl = document.getElementById('mapsUrl');
    
    // Panel elements
    const btnCurrent = document.getElementById('btnCurrent');
    const btnGps = document.getElementById('btnGps');
    const btnAddress = document.getElementById('btnAddress');
    const btnManual = document.getElementById('btnManual');
    const panelCurrent = document.getElementById('panelCurrent');
    const panelGps = document.getElementById('panelGps');
    const panelAddress = document.getElementById('panelAddress');
    const panelManual = document.getElementById('panelManual');
    
    // GPS elements
    const getLocationBtn = document.getElementById('getLocation');
    const gpsStatus = document.getElementById('gpsStatus');
    
    // Address elements
    const searchAddress = document.getElementById('searchAddress');
    const btnSearchAddress = document.getElementById('btnSearchAddress');
    const addressStatus = document.getElementById('addressStatus');
    
    // Manual elements
    const manualLat = document.getElementById('manualLat');
    const manualLng = document.getElementById('manualLng');
    const btnUseManual = document.getElementById('btnUseManual');
    
    // ==================== PANEL SWITCHING ====================
    function switchPanel(activePanel) {
        // Reset all buttons
        btnCurrent.classList.remove('active');
        btnGps.classList.remove('active');
        btnAddress.classList.remove('active');
        btnManual.classList.remove('active');
        
        // Hide all panels
        panelCurrent.style.display = 'none';
        panelGps.style.display = 'none';
        panelAddress.style.display = 'none';
        panelManual.style.display = 'none';
        
        // Show selected panel
        if (activePanel === 'current') {
            btnCurrent.classList.add('active');
            panelCurrent.style.display = 'block';
        } else if (activePanel === 'gps') {
            btnGps.classList.add('active');
            panelGps.style.display = 'block';
        } else if (activePanel === 'address') {
            btnAddress.classList.add('active');
            panelAddress.style.display = 'block';
        } else if (activePanel === 'manual') {
            btnManual.classList.add('active');
            panelManual.style.display = 'block';
        }
    }
    
    btnCurrent.addEventListener('click', () => switchPanel('current'));
    btnGps.addEventListener('click', () => switchPanel('gps'));
    btnAddress.addEventListener('click', () => switchPanel('address'));
    btnManual.addEventListener('click', () => switchPanel('manual'));
    
    // ==================== UPDATE KOORDINAT ====================
    function updateCoordinates(lat, lng, source) {
        latitudeInput.value = lat;
        longitudeInput.value = lng;
        
        // Update Google Maps link
        if (lat && lng && lat !== '' && lng !== '') {
            mapsUrl.href = `https://www.google.com/maps?q=${lat},${lng}`;
            googleMapsLink.style.display = 'block';
        } else {
            googleMapsLink.style.display = 'none';
        }
        
        // Tampilkan notifikasi sukses
        showToast('Koordinat berhasil diperbarui!', 'success');
    }
    
    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.right = '20px';
        toast.style.background = type === 'success' ? '#10B981' : '#EF4444';
        toast.style.color = 'white';
        toast.style.padding = '12px 20px';
        toast.style.borderRadius = '8px';
        toast.style.zIndex = '9999';
        toast.style.animation = 'slideIn 0.3s ease';
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    // ==================== FITUR GPS ====================
    if (getLocationBtn) {
        getLocationBtn.addEventListener('click', function() {
            if (!navigator.geolocation) {
                gpsStatus.style.display = 'block';
                gpsStatus.style.background = '#FEE2E2';
                gpsStatus.style.color = '#991B1B';
                gpsStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Browser Anda tidak mendukung GPS.';
                return;
            }
            
            getLocationBtn.innerHTML = '<div class="loading-spinner"></div> Mengambil lokasi...';
            getLocationBtn.disabled = true;
            gpsStatus.style.display = 'block';
            gpsStatus.style.background = '#FEF3E0';
            gpsStatus.style.color = '#92400E';
            gpsStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Meminta izin lokasi...';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    
                    updateCoordinates(lat, lng, 'gps');
                    
                    gpsStatus.style.background = '#D1FAE5';
                    gpsStatus.style.color = '#065F46';
                    gpsStatus.innerHTML = `<i class="fas fa-check-circle"></i> Lokasi berhasil didapat! Lat: ${lat}, Lng: ${lng}`;
                    
                    setTimeout(() => {
                        gpsStatus.style.display = 'none';
                    }, 3000);
                    
                    getLocationBtn.innerHTML = '<i class="fas fa-crosshairs"></i> Ambil Lokasi Saya';
                    getLocationBtn.disabled = false;
                },
                function(error) {
                    let errorMsg = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Izin lokasi ditolak. Silakan izinkan akses lokasi di browser.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Waktu permintaan habis.';
                            break;
                        default:
                            errorMsg = 'Terjadi kesalahan.';
                    }
                    
                    gpsStatus.style.background = '#FEE2E2';
                    gpsStatus.style.color = '#991B1B';
                    gpsStatus.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${errorMsg}`;
                    
                    getLocationBtn.innerHTML = '<i class="fas fa-crosshairs"></i> Ambil Lokasi Saya';
                    getLocationBtn.disabled = false;
                    
                    setTimeout(() => {
                        gpsStatus.style.display = 'none';
                    }, 5000);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    }
    
    // ==================== CARI DARI ALAMAT ====================
    if (btnSearchAddress) {
        btnSearchAddress.addEventListener('click', async function() {
            const alamat = searchAddress.value.trim();
            
            if (!alamat) {
                addressStatus.style.display = 'block';
                addressStatus.style.background = '#FEE2E2';
                addressStatus.style.color = '#991B1B';
                addressStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Masukkan alamat terlebih dahulu.';
                return;
            }
            
            btnSearchAddress.innerHTML = '<div class="loading-spinner"></div> Mencari...';
            btnSearchAddress.disabled = true;
            addressStatus.style.display = 'block';
            addressStatus.style.background = '#FEF3E0';
            addressStatus.style.color = '#92400E';
            addressStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari koordinat...';
            
            try {
                const response = await fetch(`/petugas/jalan/get-koordinat?alamat=${encodeURIComponent(alamat)}`);
                const data = await response.json();
                
                if (data.success) {
                    updateCoordinates(data.latitude, data.longitude, 'address');
                    
                    addressStatus.style.background = '#D1FAE5';
                    addressStatus.style.color = '#065F46';
                    addressStatus.innerHTML = `<i class="fas fa-check-circle"></i> Ditemukan! ${data.display_name.substring(0, 100)}...`;
                } else {
                    addressStatus.style.background = '#FEE2E2';
                    addressStatus.style.color = '#991B1B';
                    addressStatus.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${data.message || 'Alamat tidak ditemukan.'}`;
                }
            } catch (error) {
                addressStatus.style.background = '#FEE2E2';
                addressStatus.style.color = '#991B1B';
                addressStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Gagal menghubungi server.';
            } finally {
                btnSearchAddress.innerHTML = '<i class="fas fa-search"></i> Cari';
                btnSearchAddress.disabled = false;
                
                setTimeout(() => {
                    if (!addressStatus.innerHTML.includes('Ditemukan')) {
                        setTimeout(() => {
                            addressStatus.style.display = 'none';
                        }, 3000);
                    }
                }, 3000);
            }
        });
        
        searchAddress.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                btnSearchAddress.click();
            }
        });
    }
    
    // ==================== INPUT MANUAL ====================
    if (btnUseManual) {
        btnUseManual.addEventListener('click', function() {
            const lat = manualLat.value.trim();
            const lng = manualLng.value.trim();
            
            if (!lat || !lng) {
                showToast('Masukkan latitude dan longitude terlebih dahulu!', 'error');
                return;
            }
            
            if (isNaN(lat) || isNaN(lng)) {
                showToast('Latitude dan longitude harus berupa angka!', 'error');
                return;
            }
            
            const latNum = parseFloat(lat);
            const lngNum = parseFloat(lng);
            
            if (latNum < -90 || latNum > 90) {
                showToast('Latitude harus antara -90 dan 90!', 'error');
                return;
            }
            
            if (lngNum < -180 || lngNum > 180) {
                showToast('Longitude harus antara -180 dan 180!', 'error');
                return;
            }
            
            updateCoordinates(lat, lng, 'manual');
        });
    }
    
    // ==================== PREVIEW PANJANG ====================
    const panjangInput = document.getElementById('panjang');
    const panjangDisplay = document.getElementById('panjangDisplay');
    
    if (panjangInput && panjangDisplay) {
        function updatePanjangDisplay() {
            let value = parseFloat(panjangInput.value);
            if (!isNaN(value) && value > 0) {
                if (value >= 1000) {
                    panjangDisplay.innerHTML = `<i class="fas fa-chart-line"></i> ${(value/1000).toFixed(2)} kilometer`;
                } else {
                    panjangDisplay.innerHTML = `<i class="fas fa-ruler"></i> ${value.toFixed(2)} meter`;
                }
                panjangDisplay.style.color = '#10B981';
            } else {
                panjangDisplay.innerHTML = '';
            }
        }
        panjangInput.addEventListener('input', updatePanjangDisplay);
        updatePanjangDisplay();
    }
    
    // ==================== VALIDASI SEBELUM SUBMIT ====================
    const form = document.getElementById('formJalan');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value.trim();
            const lokasi = document.getElementById('lokasi').value.trim();
            const panjang = panjangInput.value;
            
            if (!nama || !lokasi || !panjang) {
                e.preventDefault();
                let errorMsg = 'Harap isi semua field yang wajib (*):\n';
                if (!nama) errorMsg += '- Nama Jalan\n';
                if (!lokasi) errorMsg += '- Lokasi\n';
                if (!panjang) errorMsg += '- Panjang Jalan\n';
                alert(errorMsg);
                
                if (!nama) document.getElementById('nama').focus();
                else if (!lokasi) document.getElementById('lokasi').focus();
                else if (!panjang) panjangInput.focus();
            }
        });
    }
});
</script>
@endsection