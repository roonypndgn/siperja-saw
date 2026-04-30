@extends('layouts.admin')

@section('title', 'Input Nilai Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Input Nilai Kriteria')
@section('page-subtitle', 'Isi nilai penilaian untuk setiap kriteria pada jalan yang dipilih')

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('admin.nilai-kriteria.store') }}" id="formNilai">
        @csrf
        
        <!-- Alert Informasi -->
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
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            
            <!-- KOLOM KIRI - Pilih Jalan & Tahun -->
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
                            <th width="15%">Kode</th>
                            <th width="30%">Nama Kriteria</th>
                            <th width="20%">Tipe</th>
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
                                @if($krit->satuan)
                                    <span class="badge-satuan">
                                        <i class="fas fa-ruler"></i> {{ $krit->satuan }}
                                    </span>
                                @else
                                    <span class="badge-satuan">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="nilai-input-wrapper">
                                    {{-- PASTIKAN name array menggunakan format nilai[kriteria_id] --}}
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
                <p>Belum ada kriteria aktif. Silakan tambahkan kriteria terlebih dahulu.</p>
                <a href="{{ route('admin.kriteria.create') }}" class="btn-primary-sm">Tambah Kriteria</a>
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
        min-width: 150px;
    }
    
    .nilai-input {
        text-align: center;
        font-weight: 600;
        font-size: 16px;
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
            min-width: 100px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nilaiInputs = document.querySelectorAll('.nilai-input');
    const progressFill = document.getElementById('progressFill');
    const progressPercent = document.getElementById('progressPercent');
    const form = document.getElementById('formNilai');
    const jalanSelect = document.getElementById('jalan_id');
    
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
    }
    
    // Event listener untuk nilai inputs
    nilaiInputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        if (input.value) {
            updateProgress();
        }
    });
    
    // Load existing values when jalan is selected (if any)
    if (jalanSelect && jalanSelect.value) {
        const selectedJalanId = jalanSelect.value;
        const tahun = document.getElementById('tahun_penilaian').value;
        
        fetch(`/admin/nilai-kriteria/get-by-jalan?jalan_id=${selectedJalanId}&tahun=${tahun}`)
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
                }
            })
            .catch(error => console.error('Error loading values:', error));
    }
    
    // Reload values when jalan changes
    jalanSelect.addEventListener('change', function() {
        const jalanId = this.value;
        const tahun = document.getElementById('tahun_penilaian').value;
        
        if (jalanId) {
            fetch(`/admin/nilai-kriteria/get-by-jalan?jalan_id=${jalanId}&tahun=${tahun}`)
                .then(response => response.json())
                .then(data => {
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
                })
                .catch(error => console.error('Error loading values:', error));
        } else {
            // Clear all inputs if no jalan selected
            nilaiInputs.forEach(input => {
                input.value = '';
            });
            updateProgress();
        }
    });
    
    // Validate before submit
    form.addEventListener('submit', function(e) {
        const jalanId = jalanSelect.value;
        let allFilled = true;
        let emptyFields = [];
        
        nilaiInputs.forEach((input, index) => {
            const kriteriaName = input.closest('tr')?.querySelector('td:nth-child(2) strong')?.innerText || 'Kriteria';
            if (!input.value || input.value.trim() === '') {
                allFilled = false;
                emptyFields.push(kriteriaName);
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