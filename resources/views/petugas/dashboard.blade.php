@extends('layouts.petugas')

@section('title', 'Dashboard - Petugas')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . Auth::user()->name)

@section('content')
<div class="dashboard-container">
    
    <!-- Selamat Datang Card -->
    <div class="welcome-card" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h2 style="font-size: 24px; margin-bottom: 8px;">
                    <i class="fas fa-hands-helping"></i> Selamat Datang, {{ Auth::user()->name }}!
                </h2>
                <p style="color: #8BA3BC; margin: 0;">Sistem Prioritas Perbaikan Jalan - Dinas PUPR</p>
                <p style="margin-top: 12px; font-size: 14px;">
                    <i class="fas fa-calendar-alt"></i> {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
            <div style="text-align: center;">
                <div style="background: #F9A826; border-radius: 16px; padding: 15px 25px;">
                    <div style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Progress Input Data</div>
                    <div style="font-size: 48px; font-weight: 800;">{{ $targetProgress['persen'] }}%</div>
                    <div style="font-size: 12px;">{{ $targetProgress['lengkap'] }}/{{ $targetProgress['total'] }} Jalan Lengkap</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistik Cards -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 30px;">
        
        <!-- Card Total Jalan -->
        <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #3B82F6;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 13px; color: #6B7280;">Total Jalan</div>
                    <div style="font-size: 32px; font-weight: 800; color: #1A2A3A;">{{ $totalJalan }}</div>
                    <div style="font-size: 12px; color: #10B981; margin-top: 4px;">
                        <i class="fas fa-check-circle"></i> {{ $jalanDenganNilai }} sudah dinilai
                    </div>
                </div>
                <div style="width: 56px; height: 56px; background: #EFF6FF; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-road" style="font-size: 28px; color: #3B82F6;"></i>
                </div>
            </div>
        </div>
        
        <!-- Card Total Penilaian -->
        <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #8B5CF6;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 13px; color: #6B7280;">Total Penilaian</div>
                    <div style="font-size: 32px; font-weight: 800; color: #1A2A3A;">{{ $totalNilai }}</div>
                    <div style="font-size: 12px; color: #8B5CF6; margin-top: 4px;">
                        <i class="fas fa-chart-line"></i> Tahun {{ $tahun }}
                    </div>
                </div>
                <div style="width: 56px; height: 56px; background: #EDE9FE; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-chart-simple" style="font-size: 28px; color: #8B5CF6;"></i>
                </div>
            </div>
        </div>
        
        <!-- Card Menunggu Validasi -->
        <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #F59E0B;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 13px; color: #6B7280;">Menunggu Validasi</div>
                    <div style="font-size: 32px; font-weight: 800; color: #F59E0B;">{{ $nilaiPending }}</div>
                    <div style="font-size: 12px; color: #6B7280; margin-top: 4px;">
                        <i class="fas fa-clock"></i> Perlu diverifikasi admin
                    </div>
                </div>
                <div style="width: 56px; height: 56px; background: #FEF3C7; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-hourglass-half" style="font-size: 28px; color: #F59E0B;"></i>
                </div>
            </div>
        </div>
        
        <!-- Card Sudah Divalidasi -->
        <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #10B981;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 13px; color: #6B7280;">Sudah Divalidasi</div>
                    <div style="font-size: 32px; font-weight: 800; color: #10B981;">{{ $nilaiDivalidasi }}</div>
                    <div style="font-size: 12px; color: #6B7280; margin-top: 4px;">
                        <i class="fas fa-check-circle"></i> Siap digunakan SAW
                    </div>
                </div>
                <div style="width: 56px; height: 56px; background: #D1FAE5; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-certificate" style="font-size: 28px; color: #10B981;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafik dan Statistik -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 30px;">
        
        <!-- Grafik Perkembangan Input -->
        <div class="chart-card" style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">
                <i class="fas fa-chart-line" style="color: #F9A826;"></i> Perkembangan Input Nilai
                <small style="font-size: 12px; color: #6B7280; margin-left: 8px;">Tahun {{ $tahun }}</small>
            </h3>
            <div style="height: 250px; position: relative;">
                <canvas id="chartPerkembangan"></canvas>
            </div>
        </div>
        
        <!-- Ringkasan Status Validasi (Donut Chart) -->
        <div class="chart-card" style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">
                <i class="fas fa-chart-pie" style="color: #F9A826;"></i> Ringkasan Status Validasi
            </h3>
            <div style="height: 250px; position: relative;">
                <canvas id="chartStatus"></canvas>
            </div>
            <div style="display: flex; justify-content: center; gap: 24px; margin-top: 16px;">
                <div><span style="display: inline-block; width: 12px; height: 12px; background: #F59E0B; border-radius: 2px;"></span> Pending: {{ $nilaiPending }}</div>
                <div><span style="display: inline-block; width: 12px; height: 12px; background: #10B981; border-radius: 2px;"></span> Divalidasi: {{ $nilaiDivalidasi }}</div>
                <div><span style="display: inline-block; width: 12px; height: 12px; background: #EF4444; border-radius: 2px;"></span> Ditolak: {{ $nilaiDitolak }}</div>
            </div>
        </div>
    </div>
    
    <!-- Data Terbaru -->
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 30px;">
        
        <!-- 5 Data Terbaru -->
        <div class="recent-card" style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid #E2E8F0; background: #F8FAFC;">
                <h3 style="font-size: 16px; font-weight: 700;">
                    <i class="fas fa-history" style="color: #F9A826;"></i> Data Terbaru
                </h3>
            </div>
            <div style="padding: 0;">
                @forelse($dataTerbaru as $item)
                <div style="padding: 16px 20px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600;">{{ $item->jalan->nama ?? '-' }}</div>
                        <div style="font-size: 12px; color: #6B7280;">
                            {{ $item->kriteria->nama ?? '-' }}: {{ number_format($item->nilai, 2) }}
                        </div>
                    </div>
                    <div>
                        @if($item->status_validasi == 'pending')
                            <span class="badge-pending"><i class="fas fa-clock"></i> Pending</span>
                        @elseif($item->status_validasi == 'divalidasi')
                            <span class="badge-valid"><i class="fas fa-check-circle"></i> Valid</span>
                        @else
                            <span class="badge-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                        @endif
                    </div>
                </div>
                @empty
                <div style="padding: 40px; text-align: center; color: #6B7280;">
                    <i class="fas fa-database" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p>Belum ada data yang diinput</p>
                    <a href="{{ route('petugas.nilai-kriteria.create') }}" class="btn-primary-sm">Input Nilai Sekarang</a>
                </div>
                @endforelse
            </div>
            <div style="padding: 12px 20px; border-top: 1px solid #E2E8F0; background: #F8FAFC;">
                <a href="{{ route('petugas.nilai-kriteria.riwayat') }}" style="color: #F9A826; text-decoration: none; font-size: 13px;">
                    Lihat semua riwayat <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <!-- 5 Jalan Terakhir -->
        <div class="recent-card" style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid #E2E8F0; background: #F8FAFC;">
                <h3 style="font-size: 16px; font-weight: 700;">
                    <i class="fas fa-road" style="color: #F9A826;"></i> Jalan Terakhir Dinilai
                </h3>
            </div>
            <div style="padding: 0;">
                @forelse($jalanTerakhir as $jalan)
                <div style="padding: 16px 20px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600;">{{ $jalan->nama }}</div>
                        <div style="font-size: 12px; color: #6B7280;">
                            <i class="fas fa-map-marker-alt"></i> {{ $jalan->lokasi }}
                        </div>
                    </div>
                    <div>
                        @php
                            $nilaiCount = $jalan->nilaiKriteria->count();
                            $kriteriaCount = \App\Models\Kriteria::where('is_active', true)->count();
                            $isComplete = $nilaiCount >= $kriteriaCount;
                        @endphp
                        @if($isComplete)
                            <span class="badge-valid"><i class="fas fa-check"></i> Lengkap</span>
                        @else
                            <span class="badge-pending"><i class="fas fa-edit"></i> {{ $nilaiCount }}/{{ $kriteriaCount }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div style="padding: 40px; text-align: center; color: #6B7280;">
                    <i class="fas fa-road" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p>Belum ada jalan yang dinilai</p>
                </div>
                @endforelse
            </div>
            <div style="padding: 12px 20px; border-top: 1px solid #E2E8F0; background: #F8FAFC;">
                <a href="{{ route('petugas.nilai-kriteria.create') }}" style="color: #F9A826; text-decoration: none; font-size: 13px;">
                    Input nilai baru <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Aksi Cepat -->
    <div class="quick-actions" style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #E2E8F0;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">
            <i class="fas fa-bolt" style="color: #F9A826;"></i> Aksi Cepat
        </h3>
        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
            <a href="{{ route('petugas.nilai-kriteria.create') }}" class="quick-action-btn" style="background: #FEF3E0;">
                <i class="fas fa-edit" style="color: #F9A826;"></i>
                <span>Input Nilai Baru</span>
            </a>
            <a href="{{ route('petugas.jalan.index') }}" class="quick-action-btn" style="background: #E0F2FE;">
                <i class="fas fa-road" style="color: #0284C7;"></i>
                <span>Kelola Data Jalan</span>
            </a>
            <a href="{{ route('petugas.nilai-kriteria.riwayat') }}" class="quick-action-btn" style="background: #D1FAE5;">
                <i class="fas fa-history" style="color: #10B981;"></i>
                <span>Lihat Riwayat</span>
            </a>
            <a href="{{ route('petugas.panduan.index') }}" class="quick-action-btn" style="background: #EDE9FE;">
                <i class="fas fa-book-open" style="color: #8B5CF6;"></i>
                <span>Panduan Penggunaan</span>
            </a>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .stat-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    
    .badge-pending {
        background: #FEF3C7;
        color: #D97706;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-valid {
        background: #D1FAE5;
        color: #059669;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-rejected {
        background: #FEE2E2;
        color: #DC2626;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
    
    .chart-card {
        transition: transform 0.2s;
    }
    
    .chart-card:hover {
        transform: translateY(-2px);
    }
    
    .recent-card {
        transition: transform 0.2s;
    }
    
    .recent-card:hover {
        transform: translateY(-2px);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik Perkembangan Input per Bulan
    const ctx1 = document.getElementById('chartPerkembangan').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($bulanList) !!},
            datasets: [{
                label: 'Jumlah Input',
                data: {!! json_encode($nilaiPerBulan) !!},
                borderColor: '#F9A826',
                backgroundColor: 'rgba(249, 168, 38, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#F9A826',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Jumlah Input: ${context.raw} data`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Input',
                        color: '#6B7280'
                    },
                    grid: {
                        color: '#E2E8F0'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan',
                        color: '#6B7280'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Grafik Ringkasan Status Validasi (Donut)
    const ctx2 = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Divalidasi', 'Ditolak'],
            datasets: [{
                data: [{{ $nilaiPending }}, {{ $nilaiDivalidasi }}, {{ $nilaiDitolak }}],
                backgroundColor: ['#F59E0B', '#10B981', '#EF4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = {{ $nilaiPending + $nilaiDivalidasi + $nilaiDitolak }};
                            const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${context.raw} data (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
});
</script>
@endsection