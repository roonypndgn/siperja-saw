@extends('layouts.admin')

@section('title', 'Edit Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Edit Kriteria')
@section('page-subtitle', 'Perbarui informasi kriteria penilaian')

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('admin.kriteria.update', $kriteria->id) }}" id="formKriteria">
        @csrf
        @method('PUT')
        
        <!-- Alert Informasi -->
        <div style="background: #FEF3E0; border-left: 4px solid var(--secondary); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-info-circle" style="font-size: 24px; color: var(--secondary);"></i>
                <div>
                    <strong style="color: var(--text-dark);">Informasi Bobot</strong>
                    <p style="margin: 5px 0 0; color: var(--text-light); font-size: 13px;">
                        Total bobot semua kriteria yang <strong>AKTIF</strong> harus <strong>100% (1.00)</strong>. 
                        Saat ini total bobot kriteria aktif (tanpa kriteria ini): <strong id="currentTotalBobot">0%</strong>
                    </p>
                </div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            
            <!-- KOLOM KIRI -->
            <div>
                <div class="form-card">
                    <h3 class="form-card-title">
                        <i class="fas fa-info-circle" style="color: var(--secondary);"></i>
                        Informasi Dasar
                    </h3>
                    
                    <!-- Kode Kriteria (Readonly) -->
                    <div class="form-group">
                        <label class="form-label">
                            Kode Kriteria <span class="text-danger">*</span>
                            <span class="label-info">(Tidak dapat diubah)</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-barcode input-icon"></i>
                            <input type="text" 
                                   name="kode" 
                                   id="kode"
                                   value="{{ old('kode', $kriteria->kode) }}" 
                                   class="form-control"
                                   readonly
                                   style="background: #F8FAFC; cursor: not-allowed;">
                        </div>
                        <small style="color: var(--text-light); font-size: 11px;">Kode kriteria tidak dapat diubah untuk menjaga integritas data</small>
                        @error('kode') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    
                    <!-- Nama Kriteria -->
                    <div class="form-group">
                        <label class="form-label">
                            Nama Kriteria <span class="text-danger">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-tag input-icon"></i>
                            <input type="text" 
                                   name="nama" 
                                   id="nama"
                                   value="{{ old('nama', $kriteria->nama) }}" 
                                   class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                                   placeholder="Contoh: Tingkat Kerusakan Jalan">
                        </div>
                        @error('nama') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    
                    <!-- Tipe Kriteria -->
                    <div class="form-group">
                        <label class="form-label">
                            Tipe Kriteria <span class="text-danger">*</span>
                        </label>
                        <div class="radio-group">
                            <label class="radio-option {{ old('tipe', $kriteria->tipe) == 'benefit' ? 'active' : '' }}">
                                <input type="radio" name="tipe" value="benefit" {{ old('tipe', $kriteria->tipe) == 'benefit' ? 'checked' : '' }}>
                                <span class="radio-custom benefit">
                                    <i class="fas fa-arrow-up"></i>
                                    Benefit
                                </span>
                                <small>Semakin besar nilai, semakin baik</small>
                            </label>
                            <label class="radio-option {{ old('tipe', $kriteria->tipe) == 'cost' ? 'active' : '' }}">
                                <input type="radio" name="tipe" value="cost" {{ old('tipe', $kriteria->tipe) == 'cost' ? 'checked' : '' }}>
                                <span class="radio-custom cost">
                                    <i class="fas fa-arrow-down"></i>
                                    Cost
                                </span>
                                <small>Semakin kecil nilai, semakin baik</small>
                            </label>
                        </div>
                        @error('tipe') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    
                    <!-- Satuan -->
                    <div class="form-group">
                        <label class="form-label">
                            Satuan
                            <span class="label-info">(Opsional)</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-ruler input-icon"></i>
                            <input type="text" 
                                   name="satuan" 
                                   value="{{ old('satuan', $kriteria->satuan) }}" 
                                   class="form-control"
                                   placeholder="Contoh: %, meter, kendaraan/hari, skala 1-10">
                        </div>
                        <small class="form-text text-muted">Satuan yang digunakan untuk nilai kriteria ini</small>
                        @error('satuan') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    
                    <!-- Urutan -->
                    <div class="form-group">
                        <label class="form-label">
                            Urutan Tampil <span class="text-danger">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-sort-numeric-down input-icon"></i>
                            <input type="number" 
                                   name="urutan" 
                                   id="urutan"
                                   value="{{ old('urutan', $kriteria->urutan) }}" 
                                   class="form-control {{ $errors->has('urutan') ? 'is-invalid' : '' }}"
                                   placeholder="Contoh: 1, 2, 3...">
                        </div>
                        <div id="urutanStatus" style="margin-top: 5px; font-size: 12px;"></div>
                        <small class="form-text text-muted">Menentukan urutan tampil kriteria (semakin kecil angka semakin atas)</small>
                        @error('urutan') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- KOLOM KANAN -->
            <div>
                <div class="form-card">
                    <h3 class="form-card-title">
                        <i class="fas fa-sliders-h" style="color: var(--secondary);"></i>
                        Bobot & Keterangan
                    </h3>
                    
                    <!-- Bobot -->
                    <div class="form-group">
                        <label class="form-label">
                            Bobot Kriteria <span class="text-danger">*</span>
                            <span class="label-info">(0 - 1 atau 0% - 100%)</span>
                        </label>
                        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                            <div style="flex: 1;">
                                <div class="input-with-icon">
                                    <i class="fas fa-percent input-icon"></i>
                                    <input type="number" 
                                           name="bobot" 
                                           id="bobot"
                                           step="0.01"
                                           value="{{ old('bobot', $kriteria->bobot) }}" 
                                           class="form-control {{ $errors->has('bobot') ? 'is-invalid' : '' }}"
                                           placeholder="Contoh: 0.30 atau 30">
                                </div>
                            </div>
                            <div style="width: 120px; text-align: center;">
                                <div id="bobotPreview" style="font-size: 24px; font-weight: 700; color: var(--secondary);">
                                    {{ number_format(old('bobot', $kriteria->bobot) * 100, 0) }}%
                                </div>
                            </div>
                        </div>
                        <div id="bobotWarning" style="margin-top: 8px; font-size: 12px;"></div>
                        <small class="form-text text-muted">
                            Masukkan bobot dalam format desimal (0.30 = 30%) atau langsung persen (30)
                        </small>
                        @error('bobot') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    
                    <!-- Bobot Visual (Progress Bar) -->
                    <div style="background: #F1F5F9; border-radius: 10px; padding: 12px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px;">
                            <span>Total Bobot Aktif (tanpa kriteria ini):</span>
                            <strong id="totalBobotDisplay">0%</strong>
                        </div>
                        <div style="background: #E2E8F0; border-radius: 10px; height: 8px; overflow: hidden;">
                            <div id="totalBobotBar" style="width: 0%; height: 100%; background: var(--secondary); border-radius: 10px; transition: width 0.3s;"></div>
                        </div>
                        <div id="totalBobotMessage" style="margin-top: 8px; font-size: 11px;"></div>
                    </div>
                    
                    <!-- Keterangan -->
                    <div class="form-group">
                        <label class="form-label">
                            Keterangan / Deskripsi
                            <span class="label-info">(Opsional)</span>
                        </label>
                        <textarea name="keterangan" 
                                  rows="5" 
                                  class="form-control"
                                  placeholder="Jelaskan kriteria ini, cara pengukuran, skala penilaian, dll...">{{ old('keterangan', $kriteria->keterangan) }}</textarea>
                        <small class="form-text text-muted">Maksimal 500 karakter</small>
                        @error('keterangan') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    
                    <!-- Status Aktif -->
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $kriteria->is_active) ? 'checked' : '' }}>
                            <span class="checkbox-custom"></span>
                            <span>
                                <strong>Aktifkan Kriteria</strong>
                                <div style="font-size: 12px; color: var(--text-light); margin-top: 4px;">
                                    Centang jika kriteria ini akan digunakan dalam perhitungan SAW
                                </div>
                            </span>
                        </label>
                    </div>
                    
                    <!-- Informasi Tambahan -->
                    <div style="margin-top: 16px; padding: 12px; background: #F8FAFC; border-radius: 8px; font-size: 12px; color: var(--text-light);">
                        <i class="fas fa-history"></i> 
                        Terakhir diperbarui: {{ $kriteria->updated_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}
                        <br>
                        <i class="fas fa-chart-line"></i> 
                        Total penggunaan: {{ $kriteria->nilaiKriteriaJalan()->count() }} kali penilaian
                    </div>
                </div>
            </div>
        </div>
        
        <hr style="margin: 30px 0 20px; border: none; border-top: 1px solid var(--border);">
        
        <!-- Tombol Aksi -->
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('admin.kriteria.index') }}" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
            <a href="{{ route('admin.kriteria.show', $kriteria->id) }}" class="btn-secondary">
                <i class="fas fa-eye"></i> Lihat Detail
            </a>
            <button type="submit" class="btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Update Kriteria
            </button>
        </div>
    </form>
</div>

<style>
    /* Form Container */
    .form-container {
        background: var(--bg-white);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid var(--border);
    }
    
    /* Form Card */
    .form-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 24px;
        height: 100%;
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid var(--secondary);
        padding-bottom: 12px;
    }
    
    /* Form Group */
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(249, 168, 38, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #EF4444;
    }
    
    .input-with-icon {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-lighter);
        font-size: 16px;
    }
    
    .input-with-icon .form-control {
        padding-left: 42px;
    }
    
    /* Radio Group */
    .radio-group {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        padding: 10px 16px;
        border: 2px solid var(--border);
        border-radius: 10px;
        transition: all 0.3s;
    }
    
    .radio-option.active {
        border-color: var(--secondary);
        background: #FEF3E0;
    }
    
    .radio-option:hover {
        border-color: var(--secondary);
        background: #FEF3E0;
    }
    
    .radio-option input {
        display: none;
    }
    
    .radio-option input:checked + .radio-custom {
        background: var(--secondary);
        color: var(--primary-dark);
    }
    
    .radio-custom {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .radio-custom.benefit {
        background: #D1FAE5;
        color: #059669;
    }
    
    .radio-custom.cost {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .radio-option small {
        font-size: 11px;
        color: var(--text-light);
    }
    
    /* Checkbox */
    .checkbox-group {
        margin-top: 20px;
        padding: 16px;
        background: #FEF3E0;
        border-radius: 12px;
        border-left: 4px solid var(--secondary);
    }
    
    .checkbox-label {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        cursor: pointer;
    }
    
    .checkbox-label input {
        width: 20px;
        height: 20px;
        margin-top: 2px;
        cursor: pointer;
    }
    
    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
        color: var(--primary-dark);
        padding: 12px 28px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3);
    }
    
    .btn-secondary {
        background: var(--bg-light);
        color: var(--text-dark);
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        border: 1px solid var(--border);
    }
    
    .btn-secondary:hover {
        background: var(--border);
        border-color: var(--secondary);
    }
    
    .btn-outline {
        background: transparent;
        color: var(--text-dark);
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid var(--border);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .btn-outline:hover {
        border-color: var(--secondary);
        color: var(--secondary);
    }
    
    /* Text Styles */
    .text-danger {
        color: #EF4444;
    }
    
    .text-muted {
        color: var(--text-light);
    }
    
    .label-info {
        font-size: 11px;
        font-weight: normal;
        color: var(--text-lighter);
        margin-left: 6px;
    }
    
    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 11px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const kodeInput = document.getElementById('kode');
    const namaInput = document.getElementById('nama');
    const urutanInput = document.getElementById('urutan');
    const bobotInput = document.getElementById('bobot');
    const bobotPreview = document.getElementById('bobotPreview');
    const bobotWarning = document.getElementById('bobotWarning');
    const totalBobotDisplay = document.getElementById('totalBobotDisplay');
    const totalBobotBar = document.getElementById('totalBobotBar');
    const totalBobotMessage = document.getElementById('totalBobotMessage');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('formKriteria');
    
    // Data dari server
    let totalBobotTanpaIni = {{ $totalBobotTanpaIni ?? 0 }};
    let currentBobot = {{ old('bobot', $kriteria->bobot) }};
    let isCurrentlyActive = {{ $kriteria->is_active ? 'true' : 'false' }};
    
    // Update total bobot display
    function updateTotalBobot() {
        let bobotValue = parseBobot(bobotInput?.value || 0);
        let willBeActive = document.querySelector('input[name="is_active"]')?.checked || false;
        
        let newTotal;
        if (willBeActive) {
            newTotal = totalBobotTanpaIni + bobotValue;
        } else {
            // Jika dinonaktifkan, tidak menambah ke total
            newTotal = totalBobotTanpaIni;
        }
        
        let percent = (newTotal * 100).toFixed(0);
        
        totalBobotDisplay.innerHTML = percent + '%';
        totalBobotBar.style.width = Math.min(percent, 100) + '%';
        
        if (newTotal > 1) {
            totalBobotBar.style.background = '#EF4444';
            totalBobotMessage.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Peringatan: Total bobot akan melebihi 100%!';
            totalBobotMessage.style.color = '#EF4444';
        } else if (Math.abs(newTotal - 1) < 0.01) {
            totalBobotBar.style.background = '#10B981';
            totalBobotMessage.innerHTML = '<i class="fas fa-check-circle"></i> Total bobot akan tepat 100% setelah update';
            totalBobotMessage.style.color = '#10B981';
        } else {
            totalBobotBar.style.background = 'var(--secondary)';
            let remaining = ((1 - newTotal) * 100).toFixed(0);
            totalBobotMessage.innerHTML = '<i class="fas fa-info-circle"></i> Sisa bobot yang dapat ditambahkan: ' + remaining + '%';
            totalBobotMessage.style.color = 'var(--text-light)';
        }
        
        // Update warning untuk bobot
        if (willBeActive && totalBobotTanpaIni + bobotValue > 1) {
            bobotWarning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Bobot terlalu besar! Total akan melebihi 100%';
            bobotWarning.style.color = '#EF4444';
        } else if (!willBeActive && isCurrentlyActive) {
            bobotWarning.innerHTML = '<i class="fas fa-info-circle"></i> Kriteria akan dinonaktifkan, tidak mempengaruhi total bobot';
            bobotWarning.style.color = '#F59E0B';
        } else {
            bobotWarning.innerHTML = '';
        }
    }
    
    // Parse bobot (30 -> 0.30)
    function parseBobot(value) {
        let num = parseFloat(value);
        if (isNaN(num)) return 0;
        
        // If value is greater than 1, treat as percentage
        if (num > 1) {
            return num / 100;
        }
        return num;
    }
    
    // Update bobot preview
    function updateBobotPreview() {
        let rawValue = bobotInput?.value || 0;
        let bobot = parseBobot(rawValue);
        let percent = (bobot * 100).toFixed(0);
        bobotPreview.innerHTML = percent + '%';
        
        currentBobot = bobot;
        updateTotalBobot();
        
        // Validate bobot
        if (bobot < 0) {
            bobotWarning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Bobot tidak boleh negatif!';
            bobotWarning.style.color = '#EF4444';
        } else if (bobot > 1) {
            bobotWarning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Bobot maksimal 1.00 (100%)';
            bobotWarning.style.color = '#EF4444';
        } else if (bobot === 0) {
            bobotWarning.innerHTML = '<i class="fas fa-info-circle"></i> Bobot 0% berarti kriteria ini tidak berpengaruh';
            bobotWarning.style.color = '#F59E0B';
        } else {
            // Warning will be updated by updateTotalBobot
        }
    }
    
    // Event listeners
    if (bobotInput) {
        bobotInput.addEventListener('input', updateBobotPreview);
        updateBobotPreview();
    }
    
    // Listen to checkbox change
    const activeCheckbox = document.querySelector('input[name="is_active"]');
    if (activeCheckbox) {
        activeCheckbox.addEventListener('change', function() {
            updateTotalBobot();
        });
    }
    
    // Check urutan unik (AJAX)
    let urutanTimeout = null;
    if (urutanInput) {
        urutanInput.addEventListener('input', function() {
            clearTimeout(urutanTimeout);
            const urutan = this.value.trim();
            const urutanStatus = document.getElementById('urutanStatus');
            const currentId = {{ $kriteria->id }};
            
            if (!urutan) {
                urutanStatus.innerHTML = '';
                return;
            }
            
            urutanTimeout = setTimeout(function() {
                fetch(`/admin/kriteria/cek-urutan?urutan=${urutan}&id=${currentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            urutanInput.style.borderColor = '#EF4444';
                            urutanStatus.innerHTML = '<i class="fas fa-times-circle" style="color: #EF4444;"></i> Urutan sudah digunakan!';
                            urutanStatus.style.color = '#EF4444';
                        } else {
                            urutanInput.style.borderColor = '#10B981';
                            urutanStatus.innerHTML = '<i class="fas fa-check-circle" style="color: #10B981;"></i> Urutan tersedia';
                            urutanStatus.style.color = '#10B981';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }, 500);
        });
        
        // Trigger initial check
        if (urutanInput.value) {
            const event = new Event('input');
            urutanInput.dispatchEvent(event);
        }
    }
    
    // Validate before submit
    if (form) {
        form.addEventListener('submit', function(e) {
            const nama = namaInput?.value.trim();
            const tipe = document.querySelector('input[name="tipe"]:checked');
            const bobot = parseBobot(bobotInput?.value);
            const urutan = urutanInput?.value;
            const isActive = activeCheckbox?.checked;
            
            let errors = [];
            
            if (!nama) errors.push('Nama kriteria wajib diisi');
            if (!tipe) errors.push('Tipe kriteria wajib dipilih');
            if (!bobotInput?.value) errors.push('Bobot kriteria wajib diisi');
            if (bobot <= 0) errors.push('Bobot harus lebih dari 0');
            if (bobot > 1) errors.push('Bobot maksimal 1.00 (100%)');
            if (!urutan) errors.push('Urutan tampil wajib diisi');
            
            // Check total bobot if active
            if (isActive && totalBobotTanpaIni + bobot > 1) {
                errors.push('Total bobot tidak boleh melebihi 100%');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('❌ ' + errors.join('\n❌ '));
                
                if (!nama) namaInput?.focus();
                else if (!tipe) document.querySelector('input[name="tipe"]')?.focus();
                else if (!bobotInput?.value) bobotInput?.focus();
                else if (!urutan) urutanInput?.focus();
            }
        });
    }
});
</script>
@endsection