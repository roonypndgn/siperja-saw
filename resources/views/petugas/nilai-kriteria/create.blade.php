@extends('layouts.petugas')

@section('title', 'Input Nilai Kriteria - Petugas')
@section('page-title', 'Input Nilai Kriteria')
@section('page-subtitle', 'Isi nilai penilaian untuk setiap kriteria pada jalan yang dipilih')

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('petugas.nilai-kriteria.store') }}" id="formNilai">
        @csrf
        
        <!-- Alert Informasi -->
        <div style="background: #FEF3E0; border-left: 4px solid #F9A826; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-info-circle" style="font-size: 24px; color: #F9A826;"></i>
                <div>
                    <strong style="color: #1A2A3A;">Informasi Penilaian</strong>
                    <p style="margin: 5px 0 0; color: #6B7280; font-size: 13px;">
                        Isi nilai untuk setiap kriteria sesuai dengan skala yang ditentukan. 
                        Data akan masuk ke antrian validasi admin sebelum digunakan dalam perhitungan SAW.
                    </p>
                </div>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            
            <!-- KOLOM KIRI - Pilih Jalan & Tahun -->
            <div>
                <div class="form-card">
                    <h3 class="form-card-title">
                        <i class="fas fa-road" style="color: #F9A826;"></i>
                        Pilih Jalan & Tahun
                    </h3>
                    
                    <!-- Pilih Jalan -->
                    <div class="form-group">
                        <label class="form-label">
                            Pilih Jalan <span class="text-danger">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-road input-icon"></i>
                            <select name="jalan_id" id="jalan_id" class="form-control" required>
                                <option value="">-- Pilih Jalan --</option>
                                @foreach($jalan as $j)
                                    @php
                                        // Cek apakah jalan ini sudah memiliki nilai yang divalidasi lengkap
                                        $kriteriaCount = $kriteria->count();
                                        $nilaiValidCount = \App\Models\NilaiKriteriaJalan::where('jalan_id', $j->id)
                                            ->where('tahun_penilaian', $tahun)
                                            ->where('created_by', Auth::id())
                                            ->where('status_validasi', 'divalidasi')
                                            ->count();
                                        
                                        $isValidatedComplete = $nilaiValidCount >= $kriteriaCount && $kriteriaCount > 0;
                                        $disabledAttr = $isValidatedComplete ? 'disabled' : '';
                                        $disabledText = $isValidatedComplete ? ' (SUDAH VALID - Tidak bisa diubah)' : '';
                                    @endphp
                                    <option value="{{ $j->id }}" {{ old('jalan_id', $jalanId) == $j->id ? 'selected' : '' }} {{ $disabledAttr }}>
                                        {{ $j->kode }} - {{ $j->nama }} ({{ $j->lokasi }}){{ $disabledText }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('jalan_id') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                        <small class="form-text text-muted" style="margin-top: 8px; display: block; color: #F59E0B;">
                            <i class="fas fa-info-circle"></i> Jalan yang sudah berstatus <strong>"SUDAH VALID"</strong> tidak dapat dipilih karena data sudah lengkap dan tervalidasi.
                        </small>
                    </div>
                    
                    <!-- Tahun Penilaian -->
                    <div class="form-group">
                        <label class="form-label">
                            Tahun Penilaian <span class="text-danger">*</span>
                        </label>
                        <div class="input-with-icon">
                            <i class="fas fa-calendar input-icon"></i>
                            <select name="tahun_penilaian" id="tahun_penilaian" class="form-control" required>
                                @php
                                    $currentYear = date('Y');
                                @endphp
                                @for($year = $currentYear; $year >= $currentYear - 5; $year--)
                                    <option value="{{ $year }}" {{ old('tahun_penilaian', $tahun) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        @error('tahun_penilaian') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
                    </div>
                    
                    <!-- Status Validasi (info) -->
                    <div id="statusInfo" class="info-box" style="display: none; margin-top: 16px; padding: 12px; border-radius: 10px; font-size: 13px;"></div>
                </div>
            </div>
            
            <!-- KOLOM KANAN - Catatan -->
            <div>
                <div class="form-card">
                    <h3 class="form-card-title">
                        <i class="fas fa-sticky-note" style="color: #F9A826;"></i>
                        Catatan Penilaian
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Catatan / Keterangan
                            <span class="label-info">(Opsional)</span>
                        </label>
                        <textarea name="catatan" id="catatan" rows="6" class="form-control" 
                                  placeholder="Masukkan catatan terkait penilaian ini, misal: kondisi lapangan, metode pengukuran, dll...">{{ old('catatan') }}</textarea>
                        <small class="form-text text-muted">Maksimal 500 karakter</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabel Nilai Kriteria -->
        <div class="form-card" style="margin-top: 24px;">
            <h3 class="form-card-title">
                <i class="fas fa-table-list" style="color: #F9A826;"></i>
                Input Nilai Kriteria
            </h3>
            
            <div id="tableMessage" style="display: none; background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px;">
                <i class="fas fa-info-circle"></i> <span id="tableMessageText"></span>
            </div>
            
            <div class="table-wrapper">
                <table class="nilai-table">
                    <thead>
                        <tr>
                            <th width="15%">Kode</th>
                            <th width="30%">Nama Kriteria</th>
                            <th width="15%">Tipe</th>
                            <th width="15%">Satuan</th>
                            <th width="25%">Nilai</th>
                        </tr>
                    </thead>
                    <tbody id="kriteriaBody">
                        @foreach($kriteria as $krit)
                        <tr data-kriteria-id="{{ $krit->id }}" data-kriteria-nama="{{ $krit->nama }}">
                            <td>
                                <span class="badge-kriteria">{{ $krit->kode }}</span>
                            </td>
                            <td>
                                <strong>{{ $krit->nama }}</strong>
                            </td>
                            <td>
                                @if($krit->tipe == 'benefit')
                                    <span class="type-benefit">
                                        <i class="fas fa-arrow-up"></i> Benefit
                                    </span>
                                    <br>
                                    <small style="color: #059669;">Semakin besar, semakin baik</small>
                                @else
                                    <span class="type-cost">
                                        <i class="fas fa-arrow-down"></i> Cost
                                    </span>
                                    <br>
                                    <small style="color: #DC2626;">Semakin kecil, semakin baik</small>
                                @endif
                            </td>
                            <td>
                                @if($krit->satuan)
                                    <span class="badge-satuan">
                                        <i class="fas fa-ruler"></i> {{ $krit->satuan }}
                                    </span>
                                @else
                                    <span class="badge-satuan" style="background: #E2E8F0; color: #6B7280;">
                                        <i class="fas fa-chart-simple"></i> -
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="nilai-input-wrapper">
                                    <input type="number" 
                                           name="nilai[{{ $krit->id }}]" 
                                           id="nilai_{{ $krit->id }}"
                                           class="nilai-input form-control"
                                           step="any"
                                           value="{{ isset($existingValues[$krit->id]) ? $existingValues[$krit->id] : old('nilai.'.$krit->id) }}"
                                           placeholder="Masukkan nilai"
                                           required>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($kriteria->isEmpty())
            <div class="empty-kriteria">
                <i class="fas fa-exclamation-triangle"></i>
                <p>Belum ada kriteria aktif. Silakan hubungi admin untuk menambahkan kriteria.</p>
            </div>
            @endif
        </div>
        
        <!-- Progress Bar -->
        <div class="progress-card" style="margin-top: 24px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span>Progress Pengisian</span>
                <span id="progressPercent">0%</span>
            </div>
            <div class="progress-bar">
                <div id="progressFill" class="progress-fill" style="width: 0%;"></div>
            </div>
            <div id="progressInfo" style="margin-top: 8px; font-size: 12px; color: #6B7280;"></div>
        </div>
        
        <hr style="margin: 30px 0 20px; border: none; border-top: 1px solid #E2E8F0;">
        
        <!-- Tombol Aksi -->
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('petugas.nilai-kriteria.riwayat') }}" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Simpan Nilai
            </button>
        </div>
    </form>
</div>

<style>
    .form-container {
        background: white;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #E2E8F0;
    }
    
    .form-card {
        background: #F8FAFC;
        border-radius: 16px;
        padding: 24px;
    }
    
    .form-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #1A2A3A;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid #F9A826;
        padding-bottom: 12px;
    }
    
    .form-group {
        margin-bottom: 20px;
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
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #F9A826;
        box-shadow: 0 0 0 3px rgba(249, 168, 38, 0.1);
    }
    
    .form-control:disabled {
        background: #F8FAFC;
        cursor: not-allowed;
        opacity: 0.7;
    }
    
    .input-with-icon {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9CA3AF;
        font-size: 16px;
    }
    
    .input-with-icon .form-control {
        padding-left: 42px;
    }
    
    .btn-primary {
        background: #F9A826;
        color: #1A2A3A;
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
        background: #E8912A;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3);
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
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    .nilai-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .nilai-table th {
        background: #E2E8F0;
        padding: 12px;
        text-align: left;
        font-weight: 700;
        font-size: 13px;
        color: #1A2A3A;
    }
    
    .nilai-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #E2E8F0;
        vertical-align: middle;
    }
    
    .nilai-table tbody tr:hover {
        background: rgba(249, 168, 38, 0.05);
    }
    
    .badge-kriteria {
        background: #E8EDF2;
        color: #1A2A3A;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-satuan {
        background: #F8FAFC;
        color: #1A2A3A;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .type-benefit, .type-cost {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .type-benefit {
        background: #D1FAE5;
        color: #059669;
    }
    
    .type-cost {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    .nilai-input-wrapper {
        min-width: 160px;
    }
    
    .nilai-input {
        text-align: center;
        font-weight: 600;
        font-size: 16px;
    }
    
    .nilai-input:disabled {
        background: #F8FAFC;
        cursor: not-allowed;
    }
    
    .progress-card {
        background: #F8FAFC;
        border-radius: 16px;
        padding: 20px;
    }
    
    .progress-bar {
        background: #E2E8F0;
        border-radius: 10px;
        height: 10px;
        overflow: hidden;
    }
    
    .progress-fill {
        background: linear-gradient(90deg, #F9A826 0%, #E8912A 100%);
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .empty-kriteria {
        text-align: center;
        padding: 40px;
        color: #6B7280;
    }
    
    .empty-kriteria i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .info-box {
        background: #E8EDF2;
        border-radius: 8px;
        padding: 12px;
    }
    
    .info-box.info-success {
        background: #D1FAE5;
        color: #065F46;
        border-left: 3px solid #10B981;
    }
    
    .info-box.info-warning {
        background: #FEF3C7;
        color: #92400E;
        border-left: 3px solid #F59E0B;
    }
    
    .info-box.info-error {
        background: #FEE2E2;
        color: #991B1B;
        border-left: 3px solid #EF4444;
    }
    
    .text-danger {
        color: #EF4444;
    }
    
    .text-muted {
        color: #6B7280;
    }
    
    .label-info {
        font-size: 11px;
        font-weight: normal;
        color: #9CA3AF;
        margin-left: 6px;
    }
    
    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 11px;
    }
    
    /* Select option disabled style */
    select option:disabled {
        background-color: #F8FAFC;
        color: #9CA3AF;
    }
    
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
        
        .nilai-table th, .nilai-table td {
            padding: 10px 8px;
        }
        
        .nilai-input-wrapper {
            min-width: 120px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const jalanSelect = document.getElementById('jalan_id');
    const tahunSelect = document.getElementById('tahun_penilaian');
    const statusInfo = document.getElementById('statusInfo');
    const tableMessage = document.getElementById('tableMessage');
    const tableMessageText = document.getElementById('tableMessageText');
    const form = document.getElementById('formNilai');
    const submitBtn = document.getElementById('submitBtn');
    
    // Nilai inputs
    const nilaiInputs = document.querySelectorAll('.nilai-input');
    const progressFill = document.getElementById('progressFill');
    const progressPercent = document.getElementById('progressPercent');
    const progressInfo = document.getElementById('progressInfo');
    
    // Update progress bar
    function updateProgress() {
        let filledCount = 0;
        
        nilaiInputs.forEach(input => {
            if (!input.disabled && input.value && input.value.trim() !== '') {
                filledCount++;
            }
        });
        
        const total = Array.from(nilaiInputs).filter(input => !input.disabled).length;
        const percent = total > 0 ? Math.round((filledCount / total) * 100) : 0;
        
        if (progressFill) {
            progressFill.style.width = percent + '%';
        }
        if (progressPercent) {
            progressPercent.innerText = percent + '%';
        }
        if (progressInfo) {
            if (percent === 100) {
                progressInfo.innerHTML = '<i class="fas fa-check-circle" style="color: #10B981;"></i> Semua nilai telah diisi!';
                progressInfo.style.color = '#10B981';
            } else if (percent > 0) {
                progressInfo.innerHTML = `<i class="fas fa-chart-line"></i> ${filledCount} dari ${total} kriteria terisi`;
                progressInfo.style.color = '#6B7280';
            } else {
                progressInfo.innerHTML = '<i class="fas fa-info-circle"></i> Belum ada nilai yang diisi';
                progressInfo.style.color = '#6B7280';
            }
        }
        
        return percent;
    }
    
    // Function to disable all inputs
    function disableAllInputs(disabled) {
        nilaiInputs.forEach(input => {
            input.disabled = disabled;
        });
        
        const catatanInput = document.getElementById('catatan');
        if (catatanInput) catatanInput.disabled = disabled;
        
        if (disabled) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
        } else {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    }
    
    // Check status validasi ketika jalan berubah
    async function checkStatusValidasi(jalanId, tahun) {
        if (!jalanId) {
            statusInfo.style.display = 'none';
            tableMessage.style.display = 'none';
            disableAllInputs(false);
            return;
        }
        
        try {
            const response = await fetch(`/petugas/nilai-kriteria/cek-status?jalan_id=${jalanId}&tahun=${tahun}`);
            const data = await response.json();
            
            // Cek apakah jalan sudah divalidasi lengkap
            if (data.divalidasi >= data.kriteria_count && data.kriteria_count > 0) {
                // Data sudah lengkap dan divalidasi, disable semua input
                disableAllInputs(true);
                tableMessage.style.display = 'block';
                tableMessageText.innerHTML = '<strong>⚠️ Jalan ini sudah memiliki data yang tervalidasi lengkap!</strong> Anda tidak dapat mengubah atau menambah nilai untuk jalan ini. Hubungi admin jika perlu perubahan.';
                tableMessage.style.background = '#FEE2E2';
                tableMessage.style.borderLeftColor = '#EF4444';
                statusInfo.style.display = 'none';
                return;
            }
            
            // Jika belum divalidasi lengkap, enable inputs
            disableAllInputs(false);
            
            if (data.total > 0) {
                let statusHtml = '';
                let statusClass = '';
                
                if (data.pending > 0) {
                    statusClass = 'info-warning';
                    statusHtml = `<i class="fas fa-clock"></i> <strong>Menunggu Validasi</strong><br>
                                  Terdapat ${data.pending} data yang masih menunggu validasi admin. 
                                  Setelah divalidasi, data akan digunakan dalam perhitungan SAW.`;
                } else if (data.ditolak > 0) {
                    statusClass = 'info-error';
                    statusHtml = `<i class="fas fa-times-circle"></i> <strong>Data Ditolak</strong><br>
                                  Terdapat ${data.ditolak} data yang ditolak oleh admin. 
                                  Silakan periksa kembali nilai yang Anda input.`;
                } else if (data.divalidasi > 0 && data.total >= data.kriteria_count) {
                    statusClass = 'info-success';
                    statusHtml = `<i class="fas fa-check-circle"></i> <strong>Data Valid</strong><br>
                                  Data Anda sudah divalidasi oleh admin dan siap digunakan dalam perhitungan SAW.`;
                } else if (data.divalidasi > 0) {
                    statusClass = 'info-success';
                    statusHtml = `<i class="fas fa-check-circle"></i> <strong>Sebagian Data Valid</strong><br>
                                  ${data.divalidasi} dari ${data.kriteria_count} data sudah divalidasi. 
                                  Silakan lengkapi nilai yang belum diisi.`;
                }
                
                if (statusHtml) {
                    statusInfo.className = `info-box ${statusClass}`;
                    statusInfo.innerHTML = statusHtml;
                    statusInfo.style.display = 'block';
                } else {
                    statusInfo.style.display = 'none';
                }
            } else {
                statusInfo.style.display = 'none';
            }
            
            tableMessage.style.display = 'none';
            
        } catch (error) {
            console.error('Error checking status:', error);
            statusInfo.style.display = 'none';
            disableAllInputs(false);
        }
    }
    
    // Load existing values when jalan is selected
    async function loadExistingValues(jalanId, tahun) {
        if (!jalanId) {
            // Clear all inputs
            nilaiInputs.forEach(input => {
                input.value = '';
            });
            updateProgress();
            statusInfo.style.display = 'none';
            return;
        }
        
        try {
            const response = await fetch(`/petugas/nilai-kriteria/get-by-jalan?jalan_id=${jalanId}&tahun=${tahun}`);
            const data = await response.json();
            
            // Clear all inputs first
            nilaiInputs.forEach(input => {
                input.value = '';
            });
            
            // Fill existing values
            if (data && Object.keys(data).length > 0) {
                for (const [kriteriaId, nilai] of Object.entries(data)) {
                    const input = document.getElementById(`nilai_${kriteriaId}`);
                    if (input && nilai.nilai) {
                        input.value = nilai.nilai;
                    }
                }
            }
            
            updateProgress();
            
            // Check status validasi
            await checkStatusValidasi(jalanId, tahun);
            
        } catch (error) {
            console.error('Error loading values:', error);
        }
    }
    
    // Event listener for jalan select change
    if (jalanSelect) {
        jalanSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.disabled) {
                this.value = '';
                alert('Jalan ini sudah memiliki data yang tervalidasi lengkap dan tidak dapat dipilih.');
                return;
            }
            
            const tahun = tahunSelect.value;
            loadExistingValues(this.value, tahun);
        });
    }
    
    // Event listener for tahun select change
    if (tahunSelect) {
        tahunSelect.addEventListener('change', function() {
            const jalanId = jalanSelect.value;
            if (jalanId) {
                const selectedOption = jalanSelect.options[jalanSelect.selectedIndex];
                if (!selectedOption.disabled) {
                    loadExistingValues(jalanId, this.value);
                }
            }
        });
    }
    
    // Load initial values if jalan_id is selected
    if (jalanSelect && jalanSelect.value) {
        const selectedOption = jalanSelect.options[jalanSelect.selectedIndex];
        if (!selectedOption.disabled) {
            loadExistingValues(jalanSelect.value, tahunSelect.value);
        } else {
            disableAllInputs(true);
            tableMessage.style.display = 'block';
            tableMessageText.innerHTML = '<strong>⚠️ Jalan ini sudah memiliki data yang tervalidasi lengkap!</strong> Tidak dapat dipilih untuk pengisian.';
            tableMessage.style.background = '#FEE2E2';
            tableMessage.style.borderLeftColor = '#EF4444';
        }
    }
    
    // Validate before submit
    form.addEventListener('submit', function(e) {
        const jalanId = jalanSelect.value;
        let allFilled = true;
        let emptyFields = [];
        
        // Only check non-disabled inputs
        nilaiInputs.forEach((input, index) => {
            if (!input.disabled) {
                const kriteriaName = input.closest('tr')?.querySelector('td:nth-child(2) strong')?.innerText || 'Kriteria';
                if (!input.value || input.value.trim() === '') {
                    allFilled = false;
                    emptyFields.push(kriteriaName);
                }
            }
        });
        
        if (!jalanId) {
            e.preventDefault();
            alert('Silakan pilih jalan terlebih dahulu');
            jalanSelect.focus();
            return;
        }
        
        if (!allFilled) {
            e.preventDefault();
            alert(`Harap isi semua nilai kriteria!\n\nKriteria yang belum diisi:\n- ${emptyFields.join('\n- ')}`);
            return;
        }
        
        if (!confirm('Apakah Anda yakin dengan data yang diinput? Data akan disimpan dan masuk ke antrian validasi admin.')) {
            e.preventDefault();
        }
    });
    
    // Initialize
    updateProgress();
});
</script>
@endsection