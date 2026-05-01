@extends('layouts.admin')

@section('title', 'Edit Nilai Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Edit Nilai Kriteria')
@section('page-subtitle', 'Perbarui nilai penilaian untuk semua kriteria pada jalan yang dipilih')

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('admin.nilai-kriteria.update', $existingValues->first()->id ?? 0) }}" id="formNilai">
        @csrf
        @method('PUT')
        
        <!-- Alert Informasi -->
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
                            Sebagai <strong>Admin</strong>, data nilai yang Anda edit akan <strong>LANGSUNG TERVALIDASI</strong> 
                            dan siap digunakan untuk perhitungan SAW.
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div style="background: #FEF3E0; border-left: 4px solid var(--secondary); border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-edit" style="font-size: 24px; color: var(--secondary);"></i>
                    <div>
                        <strong style="color: var(--text-dark);">Edit Data Nilai</strong>
                        <p style="margin: 5px 0 0; color: var(--text-light); font-size: 13px;">
                            Data yang diedit akan masuk ke antrian validasi ulang oleh admin.
                        </p>
                    </div>
                </div>
            </div>
        @endif
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            
            <!-- KOLOM KIRI - Informasi Jalan -->
            <div>
                <div class="form-card">
                    <h3 class="form-card-title">
                        <i class="fas fa-road" style="color: var(--secondary);"></i>
                        Informasi Jalan
                    </h3>
                    
                    <div style="background: white; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div>
                                <label style="font-size: 12px; color: var(--text-light);">Kode Jalan</label>
                                <div style="font-weight: 700; font-size: 16px;">{{ $jalan->kode }}</div>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: var(--text-light);">Nama Jalan</label>
                                <div style="font-weight: 700;">{{ $jalan->nama }}</div>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: var(--text-light);">Lokasi</label>
                                <div>{{ $jalan->lokasi }}</div>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: var(--text-light);">Panjang</label>
                                <div>{{ number_format($jalan->panjang, 0, ',', '.') }} m</div>
                            </div>
                            <div>
                                <label style="font-size: 12px; color: var(--text-light);">Tahun Penilaian</label>
                                <div><strong>{{ $tahun }}</strong></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ringkasan Status -->
                    <div class="info-status" style="margin-top: 16px; padding: 12px; background: #F8FAFC; border-radius: 10px;">
                        <div style="font-size: 13px; font-weight: 600; margin-bottom: 8px;">
                            <i class="fas fa-chart-simple"></i> Ringkasan Status Validasi
                        </div>
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            @php
                                $totalKriteria = $kriteria->count();
                                $validCount = $existingValues->where('status_validasi', 'divalidasi')->count();
                                $pendingCount = $existingValues->where('status_validasi', 'pending')->count();
                                $rejectedCount = $existingValues->where('status_validasi', 'ditolak')->count();
                                $emptyCount = $totalKriteria - $existingValues->count();
                            @endphp
                            <div><span class="status-valid-small">✅ Valid: {{ $validCount }}</span></div>
                            <div><span class="status-pending-small">⏳ Pending: {{ $pendingCount }}</span></div>
                            <div><span class="status-invalid-small">❌ Ditolak: {{ $rejectedCount }}</span></div>
                            <div><span class="status-empty-small">⚪ Belum Diisi: {{ $emptyCount }}</span></div>
                        </div>
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
                                  placeholder="Masukkan catatan terkait penilaian ini, misal: kondisi lapangan, metode pengukuran, dll...">{{ old('catatan', $existingValues->first()->catatan ?? '') }}</textarea>
                        <small class="form-text text-muted">Catatan akan diterapkan ke semua kriteria</small>
                    </div>
                    
                    <div class="alert-warning" style="background: #FEF3C7; border-radius: 10px; padding: 12px; margin-top: 16px;">
                        <i class="fas fa-info-circle" style="color: #D97706;"></i>
                        <span style="font-size: 13px; color: #92400E;">
                            @if($isAdmin)
                                Setelah diedit, semua data akan langsung <strong>tervalidasi</strong>.
                            @else
                                Setelah diedit, semua data akan berstatus <strong>Pending</strong> dan perlu divalidasi ulang oleh admin.
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabel Edit Semua Kriteria -->
        <div class="form-card" style="margin-top: 24px;">
            <h3 class="form-card-title">
                <i class="fas fa-edit" style="color: var(--secondary);"></i>
                Edit Nilai Semua Kriteria
            </h3>
            
            <div class="table-wrapper">
                <table class="nilai-table">
                    <thead>
                        <tr>
                            <th width="10%">Kode</th>
                            <th width="25%">Nama Kriteria</th>
                            <th width="12%">Tipe</th>
                            <th width="10%">Bobot</th>
                            <th width="10%">Satuan</th>
                            <th width="15%">Nilai Lama</th>
                            <th width="18%">Nilai Baru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $krit)
                        @php
                            $existing = $existingValues->get($krit->id);
                            $nilaiLama = $existing ? $existing->nilai : null;
                            $statusLama = $existing ? $existing->status_validasi : null;
                        @endphp
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
                                    <span class="badge-satuan">-</span>
                                @endif
                            </td>
                            <td>
                                @if($nilaiLama)
                                    <div class="nilai-lama">
                                        <strong>{{ number_format($nilaiLama, 2) }}</strong>
                                        @if($statusLama == 'divalidasi')
                                            <span class="status-valid-small">✅</span>
                                        @elseif($statusLama == 'pending')
                                            <span class="status-pending-small">⏳</span>
                                        @elseif($statusLama == 'ditolak')
                                            <span class="status-invalid-small">❌</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="nilai-kosong">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="nilai-input-wrapper">
                                    <input type="number" 
                                           name="nilai[{{ $krit->id }}]" 
                                           id="nilai_{{ $krit->id }}"
                                           class="nilai-input form-control"
                                           step="any"
                                           value="{{ old('nilai.'.$krit->id, $nilaiLama) }}"
                                           placeholder="Masukkan nilai"
                                           required>
                                    <div class="input-hint">
                                        <small>{{ $krit->tipe == 'benefit' ? '↑ Semakin besar semakin baik' : '↓ Semakin kecil semakin baik' }}</small>
                                    </div>
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
                <i class="fas fa-save"></i> Update Semua Nilai
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
        font-size: 12px;
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
        min-width: 150px;
    }
    
    .nilai-input {
        text-align: center;
        font-weight: 600;
        font-size: 14px;
    }
    
    .nilai-lama {
        background: #F1F5F9;
        padding: 6px 10px;
        border-radius: 8px;
        text-align: center;
        font-size: 13px;
    }
    
    .nilai-kosong {
        color: #9CA3AF;
        font-style: italic;
        font-size: 12px;
    }
    
    .input-hint {
        margin-top: 4px;
        text-align: center;
    }
    
    .input-hint small {
        color: var(--text-lighter);
        font-size: 10px;
    }
    
    .status-valid-small { color: #10B981; font-size: 12px; }
    .status-pending-small { color: #F59E0B; font-size: 12px; }
    .status-invalid-small { color: #EF4444; font-size: 12px; }
    .status-empty-small { color: #9CA3AF; font-size: 12px; }
    
    .alert-warning {
        background: #FEF3C7;
        border-radius: 10px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .info-status {
        background: #F8FAFC;
        border-radius: 10px;
        padding: 12px;
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
    
    .text-muted {
        color: var(--text-light);
    }
    
    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 11px;
    }
    
    .label-info {
        font-size: 11px;
        font-weight: normal;
        color: var(--text-lighter);
        margin-left: 6px;
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
    const nilaiInputs = document.querySelectorAll('.nilai-input');
    const progressFill = document.getElementById('progressFill');
    const progressPercent = document.getElementById('progressPercent');
    const progressInfo = document.getElementById('progressInfo');
    const form = document.getElementById('formNilai');
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
    
    // Validate before submit
    form.addEventListener('submit', function(e) {
        let allFilled = true;
        let emptyFields = [];
        
        nilaiInputs.forEach((input) => {
            const row = input.closest('tr');
            const kriteriaKode = row?.querySelector('td:first-child .badge-kriteria')?.innerText || 'Kriteria';
            
            if (!input.value || input.value.trim() === '') {
                allFilled = false;
                emptyFields.push(kriteriaKode);
            }
        });
        
        if (!allFilled) {
            e.preventDefault();
            alert(`⚠️ Harap isi semua nilai kriteria!\n\nKriteria yang belum diisi:\n- ${emptyFields.join('\n- ')}`);
            return;
        }
        
        const confirmMessage = isAdmin 
            ? '✅ Semua data akan langsung tervalidasi setelah diupdate. Lanjutkan?' 
            : '✅ Semua data akan masuk ke antrian validasi ulang setelah diupdate. Lanjutkan?';
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });
    
    // Initialize
    updateProgress();
});
</script>
@endsection