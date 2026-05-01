{{-- resources/views/admin/nilai-kriteria/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Data Nilai Kriteria - Sistem Prioritas Perbaikan Jalan')
@section('page-title', 'Data Nilai Kriteria')
@section('page-subtitle', 'Kelola nilai penilaian untuk setiap kriteria pada setiap jalan')

@section('content')
<!-- Filter & Tools -->
<div class="page-tools" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div style="display: flex; gap: 12px;">
        <a href="{{ route('admin.nilai-kriteria.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Input Nilai Baru
        </a>
        <a href="{{ route('admin.saw.form', ['tahun' => request('tahun', date('Y'))]) }}" class="btn-saw">
            <i class="fas fa-calculator"></i> Proses SAW
        </a>
        
        <!-- Dropdown Export -->
        <div class="dropdown" style="position: relative; display: inline-block;">
            <button class="btn-secondary dropdown-toggle" type="button" id="exportDropdownBtn" onclick="toggleExportDropdown()">
                <i class="fas fa-download"></i> Export
                <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 12px;"></i>
            </button>
            <div class="dropdown-menu" id="exportDropdownMenu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border: 1px solid var(--border); border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-width: 200px; z-index: 1000;">
                <a class="dropdown-item" href="{{ route('admin.nilai-kriteria.export-excel', ['tahun' => $tahun, 'status' => $status]) }}" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; text-decoration: none; color: var(--text-dark); transition: all 0.2s;">
                    <i class="fas fa-file-excel" style="color: #10B981; width: 20px; font-size: 16px;"></i>
                    <span>Excel (Detail)</span>
                </a>
                <a class="dropdown-item" href="{{ route('admin.nilai-kriteria.export-per-jalan-excel', ['tahun' => $tahun]) }}" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; text-decoration: none; color: var(--text-dark); transition: all 0.2s;">
                    <i class="fas fa-file-excel" style="color: #10B981; width: 20px; font-size: 16px;"></i>
                    <span>Excel (Per Jalan)</span>
                </a>
                <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
                <a class="dropdown-item" href="{{ route('admin.nilai-kriteria.export-csv', ['tahun' => $tahun, 'status' => $status]) }}" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; text-decoration: none; color: var(--text-dark); transition: all 0.2s;">
                    <i class="fas fa-file-csv" style="color: #F59E0B; width: 20px; font-size: 16px;"></i>
                    <span>CSV</span>
                </a>
                <div style="height: 1px; background: var(--border); margin: 4px 0;"></div>
                <a class="dropdown-item" href="{{ route('admin.nilai-kriteria.export-pdf', ['tahun' => $tahun, 'status' => $status]) }}" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; text-decoration: none; color: var(--text-dark); transition: all 0.2s;">
                    <i class="fas fa-file-pdf" style="color: #EF4444; width: 20px; font-size: 16px;"></i>
                    <span>PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Form Filter -->
    <form method="GET" action="{{ route('admin.nilai-kriteria.index') }}" id="filterForm" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <select name="tahun" id="filterTahun" style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 10px; background: white; font-size: 14px;">
            @foreach($tahunList as $thn)
            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
            @endforeach
        </select>

        <select name="status" id="filterStatus" style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 10px; background: white; font-size: 14px;">
            <option value="semua" {{ $status == 'semua' ? 'selected' : '' }}>Semua Status</option>
            <option value="lengkap" {{ $status == 'lengkap' ? 'selected' : '' }}>Data Lengkap</option>
            <option value="belum_lengkap" {{ $status == 'belum_lengkap' ? 'selected' : '' }}>Belum Lengkap</option>
        </select>

        <button type="submit" class="btn-filter">
            <i class="fas fa-filter"></i> Filter
        </button>

        @if(request('tahun') || request('status'))
        <a href="{{ route('admin.nilai-kriteria.index') }}" class="btn-reset">
            <i class="fas fa-undo"></i> Reset
        </a>
        @endif
    </form>
</div>

<!-- Statistik Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;">
    <div class="stat-card" style="background: white; border-left: 4px solid #3B82F6; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Total Jalan Dinilai</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['total_jalan_dinilai'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #EFF6FF; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-road" style="font-size: 24px; color: #3B82F6;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: white; border-left: 4px solid #10B981; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Data Lengkap</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['data_lengkap'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #D1FAE5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-check-circle" style="font-size: 24px; color: #10B981;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: white; border-left: 4px solid #F59E0B; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Belum Lengkap</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['belum_lengkap'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px; color: #F59E0B;"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: white; border-left: 4px solid #8B5CF6; border-radius: 12px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 13px; color: var(--text-light);">Total Penilaian</div>
                <div style="font-size: 28px; font-weight: 800;">{{ $statistik['total_nilai'] }}</div>
            </div>
            <div style="width: 48px; height: 48px; background: #EDE9FE; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chart-line" style="font-size: 24px; color: #8B5CF6;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Table Data Group by Jalan -->
<div class="table-container">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Kode Jalan</th>
                    <th width="18%">Nama Jalan</th>
                    <th width="15%">Lokasi</th>
                    @foreach($kriteriaList as $krit)
                    <th width="8%" style="text-align: center;">
                        {{ $krit->kode }}
                        <br>
                        <small style="font-weight: normal;">({{ number_format($krit->bobot * 100, 0) }}%)</small>
                    </th>
                    @endforeach
                    <th width="10%">Status</th>
                    <th width="12%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataNilai as $index => $item)
                @php
                    // Ambil ID nilai pertama untuk edit
                    $nilaiId = null;
                    $hasPending = false;
                    $pendingIds = [];
                    $pendingCount = 0;
                    
                    foreach ($item['nilai'] as $nilaiItem) {
                        if (!$nilaiId) {
                            $nilaiId = $nilaiItem->id;
                        }
                        if ($nilaiItem->status_validasi == 'pending') {
                            $hasPending = true;
                            $pendingIds[] = $nilaiItem->id;
                            $pendingCount++;
                        }
                    }
                @endphp
                <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" 
                    onmouseover="this.style.background='#FEF3E0'" 
                    onmouseout="this.style.background='white'">
                    <td class="text-center">{{ $loop->iteration + (($dataNilai instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dataNilai->firstItem() - 1 : 0)) }}</td>
                    <td style="padding: 14px;">
                        <span class="badge-kode">{{ $item['jalan']->kode }}</span>
                    </td>
                    <td style="padding: 14px;">
                        <strong>{{ $item['jalan']->nama }}</strong>
                    </td>
                    <td style="padding: 14px;">
                        <i class="fas fa-map-marker-alt" style="color: #F9A826; margin-right: 4px;"></i>
                        {{ $item['jalan']->lokasi }}
                    </td>

                    @foreach($kriteriaList as $krit)
                    @php
                        $nilaiItem = $item['nilai']->firstWhere('kriteria_id', $krit->id);
                        $nilai = $nilaiItem ? $nilaiItem->nilai : null;
                        $status = $nilaiItem ? $nilaiItem->status_validasi : null;
                        $nilaiIdItem = $nilaiItem ? $nilaiItem->id : null;
                    @endphp
                    <td class="text-center" style="background: {{ $nilai ? '#F8FAFC' : '#FEE2E2' }}; padding: 10px;">
                        @if($nilai)
                            <span class="nilai-value">{{ number_format($nilai, 2) }}</span>
                            @if($krit->satuan)
                                <small style="color: var(--text-lighter);">{{ $krit->satuan }}</small>
                            @endif
                            <br>
                            @if($status == 'divalidasi')
                                <span class="status-valid-small">
                                    <i class="fas fa-check-circle"></i> Valid
                                </span>
                            @elseif($status == 'pending')
                                <span class="status-pending-small" style="cursor: pointer;" 
                                      onclick="openValidateModal({{ $nilaiIdItem }}, '{{ addslashes($item['jalan']->nama) }}', '{{ addslashes($krit->nama) }}', {{ $nilai }})">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @elseif($status == 'ditolak')
                                <span class="status-invalid-small">
                                    <i class="fas fa-times-circle"></i> Ditolak
                                </span>
                            @endif
                        @else
                            <span class="status-empty">
                                <i class="fas fa-minus-circle"></i>
                                <span style="font-size: 11px;">Belum diisi</span>
                            </span>
                        @endif
                    </td>
                    @endforeach

                    <td class="text-center">
                        @php
                            $allFilled = true;
                            $hasPendingStatus = false;
                            $hasRejected = false;
                            foreach ($kriteriaList as $krit) {
                                $nilaiItem = $item['nilai']->firstWhere('kriteria_id', $krit->id);
                                if (!$nilaiItem) {
                                    $allFilled = false;
                                } elseif ($nilaiItem->status_validasi == 'pending') {
                                    $hasPendingStatus = true;
                                } elseif ($nilaiItem->status_validasi == 'ditolak') {
                                    $hasRejected = true;
                                }
                            }
                        @endphp

                        @if($allFilled)
                            @if($hasPendingStatus)
                                <span class="status-pending">
                                    <i class="fas fa-clock"></i> Menunggu Validasi
                                </span>
                            @elseif($hasRejected)
                                <span class="status-invalid">
                                    <i class="fas fa-times-circle"></i> Ada Ditolak
                                </span>
                            @else
                                <span class="status-valid">
                                    <i class="fas fa-check-circle"></i> Lengkap & Valid
                                </span>
                            @endif
                        @else
                            <span class="status-incomplete">
                                <i class="fas fa-exclamation-triangle"></i> Belum Lengkap
                            </span>
                        @endif
                    </td>
                    
                    <td class="action-buttons">
                        {{-- Tombol Validasi Massal (jika ada data pending) --}}
                        @if($hasPending)
                            <button type="button" class="btn-action btn-validate" 
                                    onclick="openValidateModalBulk({{ json_encode($pendingIds) }}, '{{ addslashes($item['jalan']->nama) }}', {{ $pendingCount }})" 
                                    title="Validasi {{ $pendingCount }} Data Pending">
                                <i class="fas fa-check-double"></i>
                            </button>
                        @endif

                        {{-- Tombol Detail --}}
                        @if($nilaiId)
                            <a href="{{ route('admin.nilai-kriteria.show', $nilaiId) }}" class="btn-action btn-view" title="Detail Nilai">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endif

                        {{-- Tombol Edit --}}
                        @if($nilaiId)
                            <a href="{{ route('admin.nilai-kriteria.edit', $nilaiId) }}" class="btn-action btn-edit" title="Edit Semua Nilai">
                                <i class="fas fa-edit"></i>
                            </a>
                        @else
                            <a href="{{ route('admin.nilai-kriteria.create', ['jalan_id' => $item['jalan']->id, 'tahun' => $tahun]) }}" 
                               class="btn-action btn-create" title="Input Nilai">
                                <i class="fas fa-plus"></i>
                            </a>
                        @endif

                        {{-- Tombol Hapus --}}
                        @if($nilaiId)
                            <button type="button" class="btn-action btn-delete" 
                                    onclick="confirmDeleteBulk({{ $item['jalan']->id }}, '{{ addslashes($item['jalan']->nama) }}', {{ $tahun }})" 
                                    title="Hapus Semua Nilai">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 7 + count($kriteriaList) }}" class="text-center empty-state">
                        <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <p style="margin-bottom: 8px;">Belum ada data nilai kriteria untuk tahun {{ $tahun }}</p>
                        <a href="{{ route('admin.nilai-kriteria.create') }}" class="btn-primary-sm">
                            <i class="fas fa-plus"></i> Input Nilai Baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($dataNilai instanceof \Illuminate\Pagination\LengthAwarePaginator && $dataNilai->hasPages())
    <div class="pagination-container">
        {{ $dataNilai->links() }}
    </div>
    @endif
</div>

<!-- MODAL VALIDASI SINGLE ITEM -->
<div class="modal-overlay" id="validateModal" style="display: none;">
    <div class="modal-content" style="max-width: 450px;">
        <div style="padding: 24px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="modal-icon" style="width: 60px; height: 60px; background: #FEF3C7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-check-double" style="font-size: 28px; color: #F59E0B;"></i>
                </div>
                <h3 style="margin-bottom: 8px;">Validasi Data Nilai</h3>
                <p style="color: var(--text-light);" id="validateInfo"></p>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Catatan Validasi (Opsional)</label>
                <textarea id="validateCatatan" rows="3" class="form-control" placeholder="Masukkan catatan jika diperlukan..."></textarea>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeValidateModal()" class="btn-modal-cancel">Batal</button>
                <button type="button" onclick="submitValidation('divalidasi')" class="btn-modal-validate">
                    <i class="fas fa-check-circle"></i> Setujui
                </button>
                <button type="button" onclick="submitValidation('ditolak')" class="btn-modal-reject">
                    <i class="fas fa-times-circle"></i> Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL VALIDASI MASSAL -->
<div class="modal-overlay" id="validateBulkModal" style="display: none;">
    <div class="modal-content" style="max-width: 450px;">
        <div style="padding: 24px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div class="modal-icon" style="width: 60px; height: 60px; background: #FEF3C7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <i class="fas fa-check-double" style="font-size: 28px; color: #F59E0B;"></i>
                </div>
                <h3 style="margin-bottom: 8px;">Validasi Massal</h3>
                <p style="color: var(--text-light);" id="validateBulkInfo"></p>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Catatan Validasi (Opsional)</label>
                <textarea id="validateBulkCatatan" rows="3" class="form-control" placeholder="Masukkan catatan jika diperlukan..."></textarea>
            </div>
            
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" onclick="closeValidateBulkModal()" class="btn-modal-cancel">Batal</button>
                <button type="button" onclick="submitBulkValidation('divalidasi')" class="btn-modal-validate">
                    <i class="fas fa-check-circle"></i> Setujui Semua
                </button>
                <button type="button" onclick="submitBulkValidation('ditolak')" class="btn-modal-reject">
                    <i class="fas fa-times-circle"></i> Tolak Semua
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL HAPUS SEMUA NILAI -->
<div class="modal-overlay" id="deleteBulkModal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-trash-alt" style="font-size: 28px; color: #EF4444;"></i>
            </div>
            <h3 style="margin-bottom: 8px;">Hapus Semua Nilai</h3>
            <p style="color: #6B7280; margin-bottom: 4px;">Apakah Anda yakin ingin menghapus semua nilai kriteria untuk:</p>
            <p style="font-weight: 700; color: #111827; margin-bottom: 16px;" id="deleteBulkInfo"></p>
            <div style="background: #FFF5F5; border-radius: 8px; padding: 10px; margin-bottom: 24px;">
                <p style="color: #EF4444; font-size: 12px; margin: 0; font-weight: 500;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 4px;"></i>
                    Tindakan ini tidak dapat dibatalkan!
                </p>
            </div>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" onclick="closeDeleteBulkModal()" class="btn-modal-cancel">Batal</button>
                <button type="button" onclick="submitDeleteBulk()" class="btn-modal-danger">Ya, Hapus Semua</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, #F9A826 0%, #E8912A 100%);
        color: #1A2A3A;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(249, 168, 38, 0.3);
    }
    .btn-saw {
        background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-saw:hover {
        transform: translateY(-2px);
        background: #2A3F54;
    }
    .btn-secondary {
        background: white;
        color: #1A2A3A;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        border: 1px solid #E2E8F0;
        cursor: pointer;
    }
    .btn-secondary:hover {
        background: #F8FAFC;
        border-color: #F9A826;
    }
    .btn-filter, .btn-reset {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    .btn-filter {
        background: #1A2A3A;
        color: white;
    }
    .btn-filter:hover {
        background: #2A3F54;
    }
    .btn-reset {
        background: #F8FAFC;
        color: #6B7280;
        text-decoration: none;
    }
    .btn-reset:hover {
        background: #E2E8F0;
    }
    .btn-primary-sm {
        background: #F9A826;
        color: #1A2A3A;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        overflow-x: auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    .data-table thead tr {
        background: #F8FAFC;
        border-bottom: 2px solid #E2E8F0;
    }
    .data-table th {
        padding: 14px 12px;
        text-align: left;
        font-weight: 700;
        font-size: 12px;
        color: #1A2A3A;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-right: 1px solid #E2E8F0;
    }
    .data-table th:last-child {
        border-right: none;
    }
    .data-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #E2E8F0;
        font-size: 13px;
        vertical-align: middle;
    }
    .text-center {
        text-align: center;
    }
    
    /* Badge Styles */
    .badge-kode {
        background: #E8EDF2;
        color: #1A2A3A;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    .nilai-value {
        font-weight: 700;
        font-size: 14px;
        color: #1A2A3A;
    }
    
    /* Status Styles */
    .status-valid {
        background: #D1FAE5;
        color: #059669;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-pending {
        background: #FEF3C7;
        color: #D97706;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-invalid {
        background: #FEE2E2;
        color: #DC2626;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-incomplete {
        background: #FEF3C7;
        color: #D97706;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-empty {
        color: #9CA3AF;
        font-size: 11px;
    }
    .status-valid-small, .status-pending-small, .status-invalid-small {
        font-size: 10px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .status-valid-small {
        background: #D1FAE5;
        color: #059669;
    }
    .status-pending-small {
        background: #FEF3C7;
        color: #D97706;
        cursor: pointer;
        transition: all 0.2s;
    }
    .status-pending-small:hover {
        background: #D97706;
        color: white;
        transform: scale(1.02);
    }
    .status-invalid-small {
        background: #FEE2E2;
        color: #DC2626;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
    }
    .btn-view {
        background: #E0F2FE;
        color: #0284C7;
    }
    .btn-view:hover {
        background: #0284C7;
        color: white;
        transform: translateY(-2px);
    }
    .btn-edit {
        background: #FEF3C7;
        color: #D97706;
    }
    .btn-edit:hover {
        background: #D97706;
        color: white;
        transform: translateY(-2px);
    }
    .btn-validate {
        background: #FEF3C7;
        color: #D97706;
    }
    .btn-validate:hover {
        background: #D97706;
        color: white;
        transform: translateY(-2px);
    }
    .btn-create {
        background: #D1FAE5;
        color: #10B981;
    }
    .btn-create:hover {
        background: #10B981;
        color: white;
        transform: translateY(-2px);
    }
    .btn-delete {
        background: #FEE2E2;
        color: #DC2626;
    }
    .btn-delete:hover {
        background: #DC2626;
        color: white;
        transform: translateY(-2px);
    }
    
    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-content {
        background: white;
        border-radius: 20px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }
    .modal-btn-cancel, .btn-modal-cancel {
        background: white;
        color: #1A2A3A;
        border: 1px solid #E2E8F0;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-btn-cancel:hover, .btn-modal-cancel:hover {
        background: #F8FAFC;
    }
    .btn-modal-validate {
        background: #10B981;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-modal-validate:hover {
        background: #059669;
        transform: translateY(-2px);
    }
    .btn-modal-reject {
        background: #EF4444;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-modal-reject:hover {
        background: #DC2626;
        transform: translateY(-2px);
    }
    .btn-modal-danger {
        background: #EF4444;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-modal-danger:hover {
        background: #DC2626;
        transform: translateY(-2px);
    }
    .form-group {
        margin-bottom: 16px;
    }
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
    }
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        font-size: 14px;
    }
    .form-control:focus {
        outline: none;
        border-color: #F9A826;
    }
    
    /* Empty State */
    .empty-state {
        padding: 60px 20px !important;
        text-align: center;
        color: #6B7280;
    }
    
    /* Pagination */
    .pagination-container {
        padding: 20px;
        border-top: 1px solid #E2E8F0;
        display: flex;
        justify-content: flex-end;
    }
    
    /* Stat Card */
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
</style>

<script>
    // ==================== VARIABLES ====================
    let validateId = null;
    let validateBulkIds = [];
    let validateBulkJalanName = null;
    let deleteBulkJalanId = null;
    let deleteBulkJalanName = null;
    let deleteBulkTahun = null;

    // ==================== DROPDOWN EXPORT ====================
    function toggleExportDropdown() {
        const menu = document.getElementById('exportDropdownMenu');
        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.dropdown');
        const menu = document.getElementById('exportDropdownMenu');
        if (dropdown && menu && !dropdown.contains(event.target)) {
            menu.style.display = 'none';
        }
    });

    // ==================== FILTER ====================
    const tahunSelect = document.getElementById('filterTahun');
    const statusSelect = document.getElementById('filterStatus');

    if (tahunSelect) {
        tahunSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    }

    // ==================== VALIDASI SINGLE ITEM ====================
    function openValidateModal(id, jalanNama, kriteriaNama, nilai) {
        validateId = id;
        const validateInfo = document.getElementById('validateInfo');
        if (validateInfo) {
            validateInfo.innerHTML = `Jalan: <strong>${jalanNama}</strong><br>Kriteria: <strong>${kriteriaNama}</strong><br>Nilai: <strong>${nilai}</strong>`;
        }
        const catatanTextarea = document.getElementById('validateCatatan');
        if (catatanTextarea) catatanTextarea.value = '';
        const modal = document.getElementById('validateModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeValidateModal() {
        const modal = document.getElementById('validateModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        validateId = null;
    }

    function submitValidation(status) {
        if (!validateId) {
            alert('ID validasi tidak ditemukan');
            return;
        }
        const catatan = document.getElementById('validateCatatan')?.value || '';
        const statusText = status === 'divalidasi' ? 'divalidasi' : 'ditolak';
        
        fetch(`/admin/nilai-kriteria/${validateId}/validate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: statusText,
                catatan_validasi: catatan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else if (data.error) {
                alert('Error: ' + JSON.stringify(data.error));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat validasi');
        });
        closeValidateModal();
    }

    // ==================== VALIDASI MASSAL ====================
    function openValidateModalBulk(pendingIds, jalanName, pendingCount) {
        validateBulkIds = pendingIds || [];
        validateBulkJalanName = jalanName;
        const validateBulkInfo = document.getElementById('validateBulkInfo');
        if (validateBulkInfo) {
            validateBulkInfo.innerHTML = `Jalan: <strong>${jalanName}</strong><br><span style="color: #F59E0B;">${pendingCount} data nilai pending akan divalidasi.</span>`;
        }
        const catatanTextarea = document.getElementById('validateBulkCatatan');
        if (catatanTextarea) catatanTextarea.value = '';
        const modal = document.getElementById('validateBulkModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeValidateBulkModal() {
        const modal = document.getElementById('validateBulkModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        validateBulkIds = [];
        validateBulkJalanName = null;
    }

    function submitBulkValidation(status) {
        if (validateBulkIds.length === 0) {
            alert('Tidak ada data pending untuk divalidasi');
            closeValidateBulkModal();
            return;
        }
        const catatan = document.getElementById('validateBulkCatatan')?.value || '';
        const statusText = status === 'divalidasi' ? 'divalidasi' : 'ditolak';
        
        fetch('{{ route("admin.nilai-kriteria.validate-mass") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ids: validateBulkIds,
                status: statusText,
                catatan_validasi: catatan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + JSON.stringify(data.error));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat validasi massal');
        });
        closeValidateBulkModal();
    }

    // ==================== HAPUS SEMUA NILAI ====================
    function confirmDeleteBulk(jalanId, jalanName, tahun) {
        deleteBulkJalanId = jalanId;
        deleteBulkJalanName = jalanName;
        deleteBulkTahun = tahun;
        document.getElementById('deleteBulkInfo').innerHTML = `<strong>"${jalanName}"</strong> untuk tahun <strong>${tahun}</strong>`;
        document.getElementById('deleteBulkModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteBulkModal() {
        document.getElementById('deleteBulkModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        deleteBulkJalanId = null;
        deleteBulkJalanName = null;
        deleteBulkTahun = null;
    }

    function submitDeleteBulk() {
        if (!deleteBulkJalanId) {
            alert('ID tidak ditemukan');
            return;
        }
        fetch(`/admin/nilai-kriteria/delete-by-jalan/${deleteBulkJalanId}/${deleteBulkTahun}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus data: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        });
        closeDeleteBulkModal();
    }

    // ==================== MODAL CLOSE ON OUTSIDE CLICK & ESC ====================
    window.addEventListener('click', function(e) {
        const validateModal = document.getElementById('validateModal');
        const validateBulkModal = document.getElementById('validateBulkModal');
        const deleteBulkModal = document.getElementById('deleteBulkModal');
        if (e.target === validateModal) closeValidateModal();
        if (e.target === validateBulkModal) closeValidateBulkModal();
        if (e.target === deleteBulkModal) closeDeleteBulkModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('validateModal')?.style.display === 'flex') closeValidateModal();
            if (document.getElementById('validateBulkModal')?.style.display === 'flex') closeValidateBulkModal();
            if (document.getElementById('deleteBulkModal')?.style.display === 'flex') closeDeleteBulkModal();
        }
    });
</script>
@endsection