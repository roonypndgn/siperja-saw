@extends('layouts.petugas')

@section('title', 'Detail Data Jalan - Petugas')
@section('page-title', 'Detail Data Jalan')
@section('page-subtitle', 'Informasi lengkap data jalan')

@section('content')
<div class="stat-card" style="padding: 0; overflow: hidden;">
    <!-- Header Card dengan Background -->
    <div style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); padding: 30px; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                    <div style="background: #F9A826; width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-road" style="font-size: 28px; color: #1A2A3A;"></i>
                    </div>
                    <div>
                        <div style="color: #F9A826; font-size: 14px; font-weight: 600; margin-bottom: 5px;">
                            <i class="fas fa-barcode"></i> {{ $jalan->kode }}
                        </div>
                        <h2 style="color: white; font-size: 24px; margin: 0;">{{ $jalan->nama }}</h2>
                        <p style="color: #8BA3BC; margin: 5px 0 0;">
                            <i class="fas fa-map-marker-alt"></i> {{ $jalan->lokasi }}
                        </p>
                    </div>
                </div>
            </div>
            <div>
                @if($jalan->is_active)
                    <span style="background: #10B981; color: white; padding: 8px 20px; border-radius: 30px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> AKTIF
                    </span>
                @else
                    <span style="background: #EF4444; color: white; padding: 8px 20px; border-radius: 30px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-times-circle"></i> NONAKTIF
                    </span>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Content -->
    <div style="padding: 30px;">
        <!-- Grid Informasi Utama -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 30px;">
            
            <!-- Kolom Kiri - Informasi Dasar -->
            <div>
                <div style="background: #F8FAFC; border-radius: 16px; padding: 20px; height: 100%;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1A2A3A; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #F9A826; padding-bottom: 10px;">
                        <i class="fas fa-info-circle" style="color: #F9A826;"></i>
                        Informasi Dasar
                    </h3>
                    
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 12px 8px; width: 35%; font-weight: 600; color: #4B6B8A;">
                                <i class="fas fa-barcode" style="width: 20px; color: #F9A826;"></i> Kode Jalan
                            </td>
                            <td style="padding: 12px 8px;">
                                <strong style="color: #1A2A3A;">{{ $jalan->kode }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 8px; font-weight: 600; color: #4B6B8A;">
                                <i class="fas fa-road" style="width: 20px; color: #F9A826;"></i> Nama Jalan
                            </td>
                            <td style="padding: 12px 8px;">
                                <strong style="color: #1A2A3A;">{{ $jalan->nama }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 8px; font-weight: 600; color: #4B6B8A;">
                                <i class="fas fa-map-marker-alt" style="width: 20px; color: #F9A826;"></i> Lokasi
                            </td>
                            <td style="padding: 12px 8px;">
                                {{ $jalan->lokasi }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 8px; font-weight: 600; color: #4B6B8A;">
                                <i class="fas fa-ruler" style="width: 20px; color: #F9A826;"></i> Panjang Jalan
                            </td>
                            <td style="padding: 12px 8px;">
                                <strong>{{ number_format($jalan->panjang, 0, ',', '.') }}</strong> meter 
                                @if($jalan->panjang >= 1000)
                                    <span style="color: #6B7280; font-size: 12px;">({{ number_format($jalan->panjang/1000, 2) }} km)</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 8px; font-weight: 600; color: #4B6B8A;">
                                <i class="fas fa-user" style="width: 20px; color: #F9A826;"></i> Dibuat Oleh
                            </td>
                            <td style="padding: 12px 8px;">
                                {{ $jalan->createdBy->name ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 8px; font-weight: 600; color: #4B6B8A;">
                                <i class="fas fa-calendar-plus" style="width: 20px; color: #F9A826;"></i> Tanggal Dibuat
                            </td>
                            <td style="padding: 12px 8px;">
                                {{ $jalan->created_at->translatedFormat('d F Y H:i:s') }}
                            </td>
                        </tr>
                        @if($jalan->updated_by)
                        <tr>
                            <td style="padding: 12px 8px; font-weight: 600; color: #4B6B8A;">
                                <i class="fas fa-user-edit" style="width: 20px; color: #F9A826;"></i> Terakhir Diubah
                            </td>
                            <td style="padding: 12px 8px;">
                                {{ $jalan->updatedBy->name ?? '-' }} - {{ $jalan->updated_at->translatedFormat('d F Y H:i:s') }}
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            
            <!-- Kolom Kanan - Koordinat & Peta -->
            <div>
                <div style="background: #F8FAFC; border-radius: 16px; padding: 20px; height: 100%;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1A2A3A; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #F9A826; padding-bottom: 10px;">
                        <i class="fas fa-map-pin" style="color: #F9A826;"></i>
                        Lokasi Koordinat
                    </h3>
                    
                    @if($jalan->latitude && $jalan->longitude)
                        <table style="width: 100%; margin-bottom: 20px;">
                            <tr>
                                <td style="padding: 12px 8px; width: 35%; font-weight: 600; color: #4B6B8A;">
                                    <i class="fas fa-latitude"></i> Latitude
                                </td>
                                <td style="padding: 12px 8px;">
                                    <code style="background: #E2E8F0; padding: 4px 8px; border-radius: 6px;">{{ $jalan->latitude }}</code>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 8px; font-weight: 600; color: #4B6B8A;">
                                    <i class="fas fa-longitude-alt"></i> Longitude
                                </td>
                                <td style="padding: 12px 8px;">
                                    <code style="background: #E2E8F0; padding: 4px 8px; border-radius: 6px;">{{ $jalan->longitude }}</code>
                                </td>
                            </tr>
                        </table>
                        
                        <!-- Google Maps Preview -->
                        <div style="background: white; border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0; margin-bottom: 15px;">
                            <div style="background: #E2E8F0; height: 200px; display: flex; align-items: center; justify-content: center; position: relative;">
                                <!-- Static Map Preview -->
                                <img src="https://maps.googleapis.com/maps/api/staticmap?center={{ $jalan->latitude }},{{ $jalan->longitude }}&zoom=15&size=400x200&markers=color:red%7C{{ $jalan->latitude }},{{ $jalan->longitude }}&key=YOUR_GOOGLE_MAPS_API_KEY" 
                                     alt="Peta Lokasi"
                                     style="width: 100%; height: 100%; object-fit: cover;"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div style=\'text-align:center;padding:50px;\'><i class=\'fas fa-map-marked-alt\' style=\'font-size:48px;color:#F9A826;\'></i><br><span style=\'color:#6B7280;\'>Peta akan muncul setelah API Key diisi</span></div>'">
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                            <a href="https://www.google.com/maps?q={{ $jalan->latitude }},{{ $jalan->longitude }}" 
                               target="_blank" 
                               class="btn-maps">
                                <i class="fab fa-google"></i> Buka di Google Maps
                            </a>
                            <a href="https://www.openstreetmap.org/?mlat={{ $jalan->latitude }}&mlon={{ $jalan->longitude }}&zoom=15" 
                               target="_blank" 
                               class="btn-maps-osm">
                                <i class="fas fa-map"></i> Buka di OpenStreetMap
                            </a>
                            <button onclick="copyCoordinates()" class="btn-copy">
                                <i class="fas fa-copy"></i> Salin Koordinat
                            </button>
                        </div>
                    @else
                        <div style="text-align: center; padding: 40px 20px;">
                            <div style="width: 80px; height: 80px; background: #FEF3E0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                <i class="fas fa-map-marker-alt" style="font-size: 36px; color: #F9A826;"></i>
                            </div>
                            <p style="color: #6B7280; margin-bottom: 16px;">
                                <i class="fas fa-info-circle"></i> Koordinat lokasi belum tersedia
                            </p>
                            <a href="{{ route('petugas.jalan.edit', $jalan->id) }}" class="btn-edit-coord">
                                <i class="fas fa-plus"></i> Tambah Koordinat
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Deskripsi Jalan -->
        <div style="background: #F8FAFC; border-radius: 16px; padding: 20px; margin-bottom: 30px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1A2A3A; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #F9A826; padding-bottom: 10px;">
                <i class="fas fa-align-left" style="color: #F9A826;"></i>
                Deskripsi Jalan
            </h3>
            
            @if($jalan->deskripsi)
                <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #F9A826;">
                    <p style="color: #4B6B8A; line-height: 1.8; margin: 0;">
                        {{ $jalan->deskripsi }}
                    </p>
                </div>
            @else
                <div style="background: white; padding: 20px; border-radius: 12px; text-align: center; color: #9CA3AF;">
                    <i class="fas fa-align-left" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    <p>Tidak ada deskripsi untuk jalan ini</p>
                    <a href="{{ route('petugas.jalan.edit', $jalan->id) }}" style="color: #F9A826; font-size: 13px;">
                        <i class="fas fa-plus"></i> Tambah Deskripsi
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Riwayat Nilai Kriteria (jika ada) -->
        @if($jalan->nilaiKriteria->count() > 0)
        <div style="background: #F8FAFC; border-radius: 16px; padding: 20px;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1A2A3A; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid #F9A826; padding-bottom: 10px;">
                <i class="fas fa-chart-line" style="color: #F9A826;"></i>
                Riwayat Nilai Kriteria
            </h3>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #E2E8F0;">
                            <th style="padding: 12px; text-align: left;">Tahun</th>
                            <th style="padding: 12px; text-align: left;">Kriteria</th>
                            <th style="padding: 12px; text-align: right;">Nilai</th>
                            <th style="padding: 12px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jalan->nilaiKriteria->groupBy('tahun_penilaian') as $tahun => $nilaiPerTahun)
                            @foreach($nilaiPerTahun as $index => $nilai)
                            <tr style="border-bottom: 1px solid #E2E8F0;">
                                @if($loop->parent->first && $index == 0)
                                <td style="padding: 12px;" rowspan="{{ $nilaiPerTahun->count() }}">
                                    <strong style="background: #F9A826; color: #1A2A3A; padding: 4px 12px; border-radius: 20px; font-size: 12px;">
                                        {{ $tahun }}
                                    </strong>
                                </td>
                                @endif
                                <td style="padding: 12px;">
                                    {{ $nilai->kriteria->nama ?? '-' }}
                                    <small style="color: #6B7280;">({{ $nilai->kriteria->tipe ?? '-' }})</small>
                                </td>
                                <td style="padding: 12px; text-align: right;">
                                    <strong>{{ number_format($nilai->nilai, 2) }}</strong>
                                    <small style="color: #6B7280;">{{ $nilai->kriteria->satuan ?? '' }}</small>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    @if($nilai->status_validasi == 'divalidasi')
                                        <span style="color: #10B981;"><i class="fas fa-check-circle"></i> Valid</span>
                                    @elseif($nilai->status_validasi == 'pending')
                                        <span style="color: #F59E0B;"><i class="fas fa-clock"></i> Pending</span>
                                    @else
                                        <span style="color: #EF4444;"><i class="fas fa-times-circle"></i> Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Tombol Aksi -->
        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #E2E8F0;">
            <a href="{{ route('petugas.jalan.index') }}" class="btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('petugas.jalan.edit', $jalan->id) }}" class="btn-edit-main">
                <i class="fas fa-edit"></i> Edit Data
            </a>
            @if($jalan->nilaiKriteria()->count() == 0)
            <button type="button" onclick="confirmDelete({{ $jalan->id }}, '{{ $jalan->nama }}')" class="btn-delete-main">
                <i class="fas fa-trash"></i> Hapus Data
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 16px; width: 90%; max-width: 400px; overflow: hidden; animation: modalSlideIn 0.3s ease;">
        <div style="padding: 24px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <i class="fas fa-trash-alt" style="font-size: 28px; color: #EF4444;"></i>
            </div>
            <h3 style="margin-bottom: 8px;">Konfirmasi Hapus</h3>
            <p id="deleteMessage" style="color: #6B7280; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus data ini?</p>
            <form id="deleteForm" method="POST" style="display: flex; gap: 12px; justify-content: center;">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal()" class="modal-btn-cancel">Batal</button>
                <button type="submit" class="modal-btn-confirm">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-maps {
        background: #1A2A3A;
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-maps:hover {
        background: #F9A826;
        color: #1A2A3A;
        transform: translateY(-2px);
    }
    .btn-maps-osm {
        background: #F8FAFC;
        color: #1A2A3A;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #E2E8F0;
        transition: all 0.3s;
    }
    .btn-maps-osm:hover {
        border-color: #F9A826;
        color: #F9A826;
        transform: translateY(-2px);
    }
    .btn-copy {
        background: #FEF3E0;
        color: #F9A826;
        padding: 10px 18px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-copy:hover {
        background: #F9A826;
        color: #1A2A3A;
        transform: translateY(-2px);
    }
    .btn-edit-coord {
        background: #F9A826;
        color: #1A2A3A;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-edit-coord:hover {
        background: #E8912A;
        transform: translateY(-2px);
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
    .btn-edit-main {
        background: #F9A826;
        color: #1A2A3A;
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-edit-main:hover {
        background: #E8912A;
        transform: translateY(-2px);
    }
    .btn-delete-main {
        background: #FEE2E2;
        color: #EF4444;
        padding: 12px 28px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .btn-delete-main:hover {
        background: #EF4444;
        color: white;
        transform: translateY(-2px);
    }
    .modal-btn-cancel {
        padding: 10px 20px;
        border: 1px solid #E2E8F0;
        background: white;
        border-radius: 10px;
        cursor: pointer;
    }
    .modal-btn-confirm {
        padding: 10px 20px;
        background: #EF4444;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    let deleteId = null;
    
    function confirmDelete(id, name) {
        deleteId = id;
        document.getElementById('deleteMessage').innerHTML = `Apakah Anda yakin ingin menghapus jalan <strong>${name}</strong>?`;
        document.getElementById('deleteForm').action = `/petugas/jalan/${id}`;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    
    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
    
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('deleteModal').style.display === 'flex') {
            closeModal();
        }
    });
    
    function copyCoordinates() {
        const lat = '{{ $jalan->latitude }}';
        const lng = '{{ $jalan->longitude }}';
        const coordText = `${lat}, ${lng}`;
        
        navigator.clipboard.writeText(coordText).then(function() {
            // Show toast notification
            const toast = document.createElement('div');
            toast.innerHTML = '<i class="fas fa-check-circle"></i> Koordinat disalin: ' + coordText;
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.background = '#10B981';
            toast.style.color = 'white';
            toast.style.padding = '12px 20px';
            toast.style.borderRadius = '8px';
            toast.style.zIndex = '9999';
            toast.style.animation = 'slideIn 0.3s ease';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 2000);
        });
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
</script>
@endsection