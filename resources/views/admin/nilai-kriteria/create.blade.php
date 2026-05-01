@extends('layouts.admin')

@section('title', 'Input Nilai Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Input Nilai Kriteria')
@section('page-subtitle', 'Isi nilai penilaian untuk setiap kriteria pada jalan yang dipilih')

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('admin.nilai-kriteria.store') }}" id="formNilai">
        @csrf
        @php
            $isAdmin = Auth::user()->role === 'admin';
        @endphp
        
        @if($isAdmin)
            <div style="background: #D1FAE5; border-left: 4px solid #10B981; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-shield-alt" style="font-size: 24px; color: #10B981;"></i>
                    <div>
                        <strong style="color: #065F46;">Mode Administrator</strong>
                        <p style="margin: 5px 0 0; color: #065F46; font-size: 13px;">
                            Sebagai <strong>Admin</strong>, data nilai yang Anda input akan <strong>LANGSUNG TERVALIDASI</strong> 
                            dan siap digunakan untuk perhitungan SAW tanpa perlu proses validasi tambahan.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div style="background: #FEF3E0; border-left: 4px solid var(--secondary); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-info-circle" style="font-size: 24px; color: var(--secondary);"></i>
                    <div>
                        <strong style="color: var(--text-dark);">Informasi Penilaian</strong>
                        <p style="margin: 5px 0 0; color: var(--text-light); font-size: 13px;">
                            Isi nilai untuk setiap kriteria sesuai dengan skala yang ditentukan. 
                            Data akan masuk ke antrian validasi admin sebelum digunakan dalam perhitungan SAW.
                        </p>
                    </div>
                </div>
            </div>
        @endif
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            <div>
                <div class="form-card">
                    <h3 class="form-card-title">
                        <i class="fas fa-road" style="color: var(--secondary);"></i>
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
                                    <option value="{{ $j->id }}" {{ old('jalan_id', $jalanId) == $j->id ? 'selected' : '' }}>
                                        {{ $j->kode }} - {{ $j->nama }} ({{ $j->lokasi }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('jalan_id') 
                            <small class="text-danger">{{ $message }}</small> 
                        @enderror
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
                </div>
            </div>
            
            <!-- KOLOM KANAN - Catatan -->
            <div>
                <div class="form-card">
                    <h3 class="form-card-title">
                        <i class="fas fa-sticky-note" style="color: var(--secondary);"></i>
                        Catatan Penilaian
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Catatan / Keterangan
                            <span class="label-info">(Opsional)</span>
                        </label>
                        <textarea name="catatan" rows="6" class="form-control" 
                                  placeholder="Masukkan catatan terkait penilaian ini, misal: kondisi lapangan, metode pengukuran, dll...">{{ old('catatan') }}</textarea>
                        <small class="form-text text-muted">Maksimal 500 karakter</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabel Nilai Kriteria -->
        <div class="form-card" style="margin-top: 24px;">
            <h3 class="form-card-title">
                <i class="fas fa-table-list" style="color: var(--secondary);"></i>
                Input Nilai Kriteria
            </h3>
            
            <div class="table-wrapper">
                <table class="nilai-table">
                    <thead>
                        <tr>
                            <th width="10%">Kode</th>
                            <th width="25%">Nama Kriteria</th>
                            <th width="15%">Tipe</th>
                            <th width="15%">Bobot</th>
                            <th width="15%">Satuan</th>
                            <th width="20%">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $krit)
                        <tr>
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
                                @else
                                    <span class="type-cost">
                                        <i class="fas fa-arrow-down"></i> Cost
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-bobot">{{ number_format($krit->bobot * 100, 0) }}%</span>
                            </td>
                            <td>
                                @if($krit->satuan)
                                    <span class="badge-satuan">
                                        <i class="fas fa-ruler"></i> {{ $krit->satuan }}
                                    </span>
                                @else
                                    <span class="badge-satuan" style="background: #E2E8F0; color: #6B7280;">
                                        <i class="fas fa-chart-simple"></i> Skala Bebas
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
                                    <small class="input-hint">Contoh: {{ $krit->tipe == 'benefit' ? 'semakin besar semakin baik' : 'semakin kecil semakin baik' }}</small>
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
                <p>Belum ada kriteria aktif. Silakan tambahkan kriteria terlebih dahulu.</p>
                <a href="{{ route('admin.kriteria.create') }}" class="btn-primary-sm">Tambah Kriteria</a>
            </div>
            @endif
        </div>
        
        <!-- Progress Bar -->
        <div class="progress-card" style="margin-top: 24px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span><i class="fas fa-chart-line"></i> Progress Pengisian</span>
                <span id="progressPercent" class="progress-percent">0%</span>
            </div>
            <div class="progress-bar">
                <div id="progressFill" class="progress-fill" style="width: 0%;"></div>
            </div>
            <div id="progressInfo" class="progress-info"></div>
        </div>
        
        <hr style="margin: 30px 0 20px; border: none; border-top: 1px solid var(--border);">
        
        <!-- Tombol Aksi -->
        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('admin.nilai-kriteria.index') }}" class="btn-outline">
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
        background: var(--bg-white);
        border-radius: 20px;
        padding: 30px;
        border: 1px solid var(--border);
    }
    
    .form-card {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 24px;
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
    
    .btn-primary-sm {
        background: var(--secondary);
        color: var(--primary-dark);
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        color: var(--text-dark);
    }
    
    .nilai-table td {
        padding: 16px 12px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    
    .nilai-table tbody tr:hover {
        background: rgba(249, 168, 38, 0.05);
    }
    
    .badge-kriteria {
        background: var(--primary-lighter);
        color: var(--primary);
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-bobot {
        background: #FEF3E0;
        color: var(--secondary-dark);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
    
    .badge-satuan {
        background: #F8FAFC;
        color: var(--text-dark);
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
        min-width: 180px;
    }
    
    .nilai-input {
        text-align: center;
        font-weight: 600;
        font-size: 16px;
    }
    
    .input-hint {
        display: block;
        font-size: 10px;
        color: var(--text-lighter);
        margin-top: 4px;
        text-align: center;
    }
    
    .progress-card {
        background: var(--bg-light);
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
        background: linear-gradient(90deg, var(--secondary) 0%, var(--secondary-dark) 100%);
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .progress-percent {
        font-weight: 700;
        color: var(--secondary);
    }
    
    .progress-info {
        margin-top: 8px;
        font-size: 11px;
        color: var(--text-light);
    }
    
    .empty-kriteria {
        text-align: center;
        padding: 40px;
        color: var(--text-light);
    }
    
    .empty-kriteria i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
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
        
        .input-hint {
            display: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nilaiInputs = document.querySelectorAll('.nilai-input');
    const progressFill = document.getElementById('progressFill');
    const progressPercent = document.getElementById('progressPercent');
    const progressInfo = document.getElementById('progressInfo');
    const form = document.getElementById('formNilai');
    const jalanSelect = document.getElementById('jalan_id');
    const tahunSelect = document.getElementById('tahun_penilaian');
    
    const isAdmin = "{{ Auth::user()->role === 'admin' }}" === "1";
    
    // Update progress bar
    function updateProgress() {
        let filledCount = 0;
        
        nilaiInputs.forEach(input => {
            if (input.value && input.value.trim() !== '') {
                filledCount++;
            }
        });
        
        const total = nilaiInputs.length;
        const percent = total > 0 ? Math.round((filledCount / total) * 100) : 0;
        
        if (progressFill) {
            progressFill.style.width = percent + '%';
        }
        if (progressPercent) {
            progressPercent.innerText = percent + '%';
        }
        if (progressInfo) {
            if (percent === 100) {
                progressInfo.innerHTML = '<i class="fas fa-check-circle" style="color: #10B981;"></i> Semua nilai kriteria telah diisi!';
                progressInfo.style.color = '#10B981';
            } else if (percent > 0) {
                progressInfo.innerHTML = `<i class="fas fa-chart-line"></i> ${filledCount} dari ${total} kriteria terisi`;
                progressInfo.style.color = 'var(--text-light)';
            } else {
                progressInfo.innerHTML = '<i class="fas fa-info-circle"></i> Belum ada nilai yang diisi';
                progressInfo.style.color = 'var(--text-light)';
            }
        }
    }
    
    // Event listener untuk nilai inputs
    nilaiInputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        if (input.value) {
            updateProgress();
        }
    });
    
    // Load existing values when jalan is selected
    function loadExistingValues(jalanId, tahun) {
        if (!jalanId) return;
        
        fetch(`/admin/nilai-kriteria/get-by-jalan?jalan_id=${jalanId}&tahun=${tahun}`)
            .then(response => response.json())
            .then(data => {
                if (data && Object.keys(data).length > 0) {
                    for (const [kriteriaId, nilai] of Object.entries(data)) {
                        const input = document.getElementById(`nilai_${kriteriaId}`);
                        if (input && nilai.nilai) {
                            input.value = nilai.nilai;
                        }
                    }
                    updateProgress();
                    
                    // Tampilkan info bahwa data sudah ada
                    const infoDiv = document.createElement('div');
                    infoDiv.className = 'info-success';
                    infoDiv.style.cssText = 'margin-top: 10px; padding: 8px; background: #D1FAE5; border-radius: 8px; font-size: 12px;';
                    infoDiv.innerHTML = '<i class="fas fa-database"></i> Data nilai untuk jalan ini sudah ada. Anda dapat mengupdate jika diperlukan.';
                    
                    const existingInfo = document.querySelector('.info-success');
                    if (existingInfo) existingInfo.remove();
                    
                    const formCard = document.querySelector('.form-card');
                    if (formCard && !document.querySelector('.info-success')) {
                        formCard.appendChild(infoDiv);
                        setTimeout(() => infoDiv.remove(), 5000);
                    }
                } else {
                    // Clear all inputs
                    nilaiInputs.forEach(input => {
                        input.value = '';
                    });
                    updateProgress();
                }
            })
            .catch(error => console.error('Error loading values:', error));
    }
    
    // Load values on page load if jalan is selected
    if (jalanSelect && jalanSelect.value) {
        loadExistingValues(jalanSelect.value, tahunSelect.value);
    }
    
    // Reload values when jalan changes
    jalanSelect.addEventListener('change', function() {
        const jalanId = this.value;
        const tahun = tahunSelect.value;
        loadExistingValues(jalanId, tahun);
    });
    
    // Reload when tahun changes
    tahunSelect.addEventListener('change', function() {
        const jalanId = jalanSelect.value;
        const tahun = this.value;
        if (jalanId) {
            loadExistingValues(jalanId, tahun);
        }
    });
    
    // Validate before submit
    form.addEventListener('submit', function(e) {
        const jalanId = jalanSelect.value;
        let allFilled = true;
        let emptyFields = [];
        
        nilaiInputs.forEach((input) => {
            const row = input.closest('tr');
            const kriteriaKode = row?.querySelector('td:first-child .badge-kriteria')?.innerText || 'Kriteria';
            const kriteriaNama = row?.querySelector('td:nth-child(2) strong')?.innerText || '';
            const kriteriaLabel = `${kriteriaKode} - ${kriteriaNama}`;
            
            if (!input.value || input.value.trim() === '') {
                allFilled = false;
                emptyFields.push(kriteriaLabel);
            }
        });
        
        if (!jalanId) {
            e.preventDefault();
            alert('⚠️ Silakan pilih jalan terlebih dahulu');
            jalanSelect.focus();
            return;
        }
        
        if (!allFilled) {
            e.preventDefault();
            alert(`⚠️ Harap isi semua nilai kriteria!\n\nKriteria yang belum diisi:\n- ${emptyFields.join('\n- ')}`);
            return;
        }
        
        const confirmMessage = isAdmin 
            ? 'Konfirmasi: Data akan langsung tervalidasi dan siap digunakan untuk perhitungan SAW. Lanjutkan?'
            : 'Konfirmasi: Data akan disimpan dan masuk ke antrian validasi admin. Lanjutkan?';
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });
    
    // Initialize
    updateProgress();
});
</script>
@endsection