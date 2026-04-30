@extends('layouts.petugas')

@section('title', 'Tambah Data Jalan - Petugas')
@section('page-title', 'Tambah Data Jalan')
@section('page-subtitle', 'Isi formulir di bawah ini untuk menambahkan data jalan baru')

@section('content')
<div class="stat-card">
    <form method="POST" action="{{ route('petugas.jalan.store') }}" id="formJalan">
        @csrf
        
        <!-- Alert Informasi -->
        <div style="background: #FEF3E0; border-left: 4px solid #F9A826; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-info-circle" style="font-size: 24px; color: #F9A826;"></i>
                <div>
                    <strong style="color: #1A2A3A;">Informasi</strong>
                    <p style="margin: 5px 0 0; color: #6B7280; font-size: 13px;">
                        Kode jalan akan <strong>digenerate secara otomatis</strong>. Gunakan tombol di bawah untuk mengisi koordinat.
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
                            <span style="font-size: 11px; color: #10B981;">(Otomatis)</span>
                        </label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode', $kodeOtomatis) }}" 
                               style="width: 100%; padding: 12px; border: 2px solid #10B981; border-radius: 10px; background: #F0FDF4;">
                        <div id="kodeError" style="display: none; margin-top: 5px; font-size: 11px; color: #EF4444;"></div>
                    </div>
                    
                    <!-- Nama Jalan -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            Nama Jalan <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" placeholder="Nama Jalan" 
                               style="width: 100%; padding: 12px; border: 2px solid #E2E8F0; border-radius: 10px;">
                        @error('nama') <small style="color: #EF4444;">{{ $message }}</small> @enderror
                    </div>
                    
                    <!-- Lokasi -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            Lokasi <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Lokasi" 
                               style="width: 100%; padding: 12px; border: 2px solid #E2E8F0; border-radius: 10px;">
                        @error('lokasi') <small style="color: #EF4444;">{{ $message }}</small> @enderror
                    </div>
                    
                    <!-- Panjang Jalan -->
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;">
                            Panjang (meter) <span style="color: #EF4444;">*</span>
                        </label>
                        <input type="number" step="0.01" name="panjang" id="panjang" value="{{ old('panjang') }}" placeholder="Panjang"
                               style="width: 100%; padding: 12px; border: 2px solid #E2E8F0; border-radius: 10px;">
                        <div id="panjangDisplay" style="margin-top: 5px; font-size: 12px; color: #6B7280;"></div>
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
                            <button type="button" id="btnGps" class="btn-method active">
                                <i class="fas fa-satellite-dish"></i> GPS / Lokasi Saya
                            </button>
                            <button type="button" id="btnAddress" class="btn-method">
                                <i class="fas fa-search-location"></i> Cari dari Alamat
                            </button>
                            <button type="button" id="btnManual" class="btn-method">
                                <i class="fas fa-keyboard"></i> Input Manual
                            </button>
                        </div>
                    </div>
                    
                    <!-- Panel GPS -->
                    <div id="panelGps" class="method-panel">
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
                                    <input type="text" id="manualLat" placeholder="-6.244747" 
                                           style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px;">
                                </div>
                                <div>
                                    <label style="font-size: 12px;">Longitude</label>
                                    <input type="text" id="manualLng" placeholder="106.814895" 
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
                            <i class="fas fa-map-pin" style="color: #F9A826;"></i> Koordinat Tersimpan
                        </label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            <div>
                                <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" 
                                       style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC;"
                                       placeholder="Latitude akan muncul di sini" readonly>
                            </div>
                            <div>
                                <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" 
                                       style="width: 100%; padding: 10px; border: 1px solid #E2E8F0; border-radius: 8px; background: #F8FAFC;"
                                       placeholder="Longitude akan muncul di sini" readonly>
                            </div>
                        </div>
                        <small style="color: #6B7280; font-size: 11px;">Koordinat akan otomatis terisi dari metode yang dipilih</small>
                    </div>
                    
                    <!-- Link Google Maps -->
                    <div id="googleMapsLink" style="display: none; margin-top: 15px; text-align: center;">
                        <a href="#" target="_blank" id="mapsUrl" style="color: #F9A826; text-decoration: none;">
                            <i class="fas fa-external-link-alt"></i> Lihat di Google Maps
                        </a>
                    </div>
                </div>
                
                <!-- Status Aktif -->
                <div style="background: #FEF3E0; padding: 20px; border-radius: 12px; border-left: 4px solid #F9A826;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                        <div>
                            <span style="font-weight: 700;">Status Aktif</span>
                            <div style="font-size: 12px; color: #6B7280;">Centang jika jalan masih aktif</div>
                        </div>
                    </label>
                </div>
                
                <!-- Tombol Reset -->
                <div style="margin-top: 15px; text-align: right;">
                    <button type="button" id="resetKode" style="background: none; border: none; color: #F9A826; cursor: pointer;">
                        <i class="fas fa-undo-alt"></i> Reset ke kode otomatis
                    </button>
                </div>
            </div>
        </div>
        
        <hr style="margin: 30px 0 20px;">
        
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('petugas.jalan.index') }}" class="btn-outline">Batal</a>
            <button type="submit" class="btn-primary">Simpan Data Jalan</button>
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
    }
    .btn-outline {
        background: transparent;
        color: #1A2A3A;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        border: 1px solid #E2E8F0;
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
    const kodeInput = document.getElementById('kode');
    const resetKodeBtn = document.getElementById('resetKode');
    const kodeOtomatis = '{{ $kodeOtomatis }}';
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const googleMapsLink = document.getElementById('googleMapsLink');
    const mapsUrl = document.getElementById('mapsUrl');
    
    // Panel elements
    const btnGps = document.getElementById('btnGps');
    const btnAddress = document.getElementById('btnAddress');
    const btnManual = document.getElementById('btnManual');
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
        btnGps.classList.remove('active');
        btnAddress.classList.remove('active');
        btnManual.classList.remove('active');
        
        // Hide all panels
        panelGps.style.display = 'none';
        panelAddress.style.display = 'none';
        panelManual.style.display = 'none';
        
        // Show selected panel
        if (activePanel === 'gps') {
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
    
    btnGps.addEventListener('click', () => switchPanel('gps'));
    btnAddress.addEventListener('click', () => switchPanel('address'));
    btnManual.addEventListener('click', () => switchPanel('manual'));
    
    // ==================== UPDATE KOORDINAT ====================
    function updateCoordinates(lat, lng, source) {
        latitudeInput.value = lat;
        longitudeInput.value = lng;
        
        // Update Google Maps link
        if (lat && lng) {
            mapsUrl.href = `https://www.google.com/maps?q=${lat},${lng}`;
            googleMapsLink.style.display = 'block';
        } else {
            googleMapsLink.style.display = 'none';
        }
    }
    
    // ==================== FITUR GPS ====================
    if (getLocationBtn) {
        getLocationBtn.addEventListener('click', function() {
            // Cek apakah browser support geolocation
            if (!navigator.geolocation) {
                gpsStatus.style.display = 'block';
                gpsStatus.style.background = '#FEE2E2';
                gpsStatus.style.color = '#991B1B';
                gpsStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Browser Anda tidak mendukung GPS. Silakan gunakan metode lain.';
                return;
            }
            
            // Loading state
            const originalText = getLocationBtn.innerHTML;
            getLocationBtn.innerHTML = '<div class="loading-spinner"></div> Mengambil lokasi...';
            getLocationBtn.disabled = true;
            gpsStatus.style.display = 'block';
            gpsStatus.style.background = '#FEF3E0';
            gpsStatus.style.color = '#92400E';
            gpsStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Meminta izin lokasi...';
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    // Success
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    
                    updateCoordinates(lat, lng, 'gps');
                    
                    gpsStatus.style.background = '#D1FAE5';
                    gpsStatus.style.color = '#065F46';
                    gpsStatus.innerHTML = `<i class="fas fa-check-circle"></i> Lokasi berhasil didapat! Lat: ${lat}, Lng: ${lng}`;
                    
                    setTimeout(() => {
                        gpsStatus.style.display = 'none';
                    }, 3000);
                    
                    getLocationBtn.innerHTML = originalText;
                    getLocationBtn.disabled = false;
                },
                function(error) {
                    // Error handling dengan pesan yang jelas
                    let errorMsg = '';
                    let solutionMsg = '';
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Izin lokasi ditolak.';
                            solutionMsg = 'Silakan: 1) Klik ikon kunci/lokasi di address bar browser, 2) Izinkan akses lokasi, 3) Refresh halaman. Atau gunakan metode "Cari dari Alamat" atau "Input Manual".';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Informasi lokasi tidak tersedia.';
                            solutionMsg = 'Pastikan GPS perangkat Anda aktif. Coba gunakan metode "Cari dari Alamat" atau "Input Manual".';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Waktu permintaan habis.';
                            solutionMsg = 'Coba lagi atau gunakan metode lain.';
                            break;
                        default:
                            errorMsg = 'Terjadi kesalahan.';
                            solutionMsg = 'Silakan coba metode "Cari dari Alamat" atau "Input Manual".';
                    }
                    
                    gpsStatus.style.background = '#FEE2E2';
                    gpsStatus.style.color = '#991B1B';
                    gpsStatus.innerHTML = `<i class="fas fa-exclamation-triangle"></i> <strong>${errorMsg}</strong><br><small>${solutionMsg}</small>`;
                    
                    getLocationBtn.innerHTML = originalText;
                    getLocationBtn.disabled = false;
                    
                    // Auto hide after 8 seconds
                    setTimeout(() => {
                        gpsStatus.style.display = 'none';
                    }, 8000);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
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
            
            // Loading
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
                    
                    // Isi otomatis field lokasi jika kosong
                    const lokasiInput = document.getElementById('lokasi');
                    if (!lokasiInput.value && data.display_name) {
                        lokasiInput.value = data.display_name.substring(0, 200);
                    }
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
                    if (addressStatus.style.display !== 'none') {
                        // Don't auto hide if success, let user see result
                        if (!addressStatus.innerHTML.includes('Ditemukan')) {
                            setTimeout(() => {
                                addressStatus.style.display = 'none';
                            }, 3000);
                        }
                    }
                }, 3000);
            }
        });
        
        // Search on Enter key
        if (searchAddress) {
            searchAddress.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    btnSearchAddress.click();
                }
            });
        }
    }
    
    // ==================== INPUT MANUAL ====================
    if (btnUseManual) {
        btnUseManual.addEventListener('click', function() {
            const lat = manualLat.value.trim();
            const lng = manualLng.value.trim();
            
            if (!lat || !lng) {
                alert('Masukkan latitude dan longitude terlebih dahulu.');
                return;
            }
            
            // Validasi sederhana
            if (isNaN(lat) || isNaN(lng)) {
                alert('Latitude dan longitude harus berupa angka.');
                return;
            }
            
            const latNum = parseFloat(lat);
            const lngNum = parseFloat(lng);
            
            if (latNum < -90 || latNum > 90) {
                alert('Latitude harus antara -90 dan 90.');
                return;
            }
            
            if (lngNum < -180 || lngNum > 180) {
                alert('Longitude harus antara -180 dan 180.');
                return;
            }
            
            updateCoordinates(lat, lng, 'manual');
            
            // Show success message briefly
            const manualStatus = document.createElement('div');
            manualStatus.style.background = '#D1FAE5';
            manualStatus.style.color = '#065F46';
            manualStatus.style.padding = '10px';
            manualStatus.style.borderRadius = '8px';
            manualStatus.style.marginTop = '10px';
            manualStatus.innerHTML = '<i class="fas fa-check-circle"></i> Koordinat manual tersimpan!';
            
            const panel = document.getElementById('panelManual');
            const oldStatus = panel.querySelector('.manual-status');
            if (oldStatus) oldStatus.remove();
            
            manualStatus.classList.add('manual-status');
            panel.appendChild(manualStatus);
            
            setTimeout(() => {
                manualStatus.remove();
            }, 2000);
        });
    }
    
    // ==================== RESET KODE ====================
    if (resetKodeBtn) {
        resetKodeBtn.addEventListener('click', function() {
            kodeInput.value = kodeOtomatis;
            kodeInput.style.borderColor = '#10B981';
            kodeInput.style.background = '#F0FDF4';
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
    
    // ==================== CEK KODE UNIK ====================
    let timeoutId = null;
    if (kodeInput) {
        kodeInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const kode = this.value.trim();
            
            if (kode === kodeOtomatis) {
                this.style.borderColor = '#10B981';
                this.style.background = '#F0FDF4';
                document.getElementById('kodeError').style.display = 'none';
                return;
            }
            
            if (kode.length < 3) {
                this.style.borderColor = '#EF4444';
                document.getElementById('kodeError').innerHTML = 'Minimal 3 karakter';
                document.getElementById('kodeError').style.display = 'block';
                return;
            }
            
            timeoutId = setTimeout(function() {
                fetch(`/petugas/jalan/cek-kode?kode=${kode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            kodeInput.style.borderColor = '#EF4444';
                            kodeInput.style.background = '#FEF2F2';
                            document.getElementById('kodeError').innerHTML = '<i class="fas fa-times-circle"></i> Kode sudah digunakan!';
                            document.getElementById('kodeError').style.display = 'block';
                        } else {
                            kodeInput.style.borderColor = '#10B981';
                            kodeInput.style.background = '#F0FDF4';
                            document.getElementById('kodeError').style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }, 500);
        });
    }
});
</script>
@endsection