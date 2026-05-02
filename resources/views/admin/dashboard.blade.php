{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard - Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . Auth::user()->name . '!')

@section('content')
<div class="dashboard-container">
    
    <!-- Filter Tahun -->
    <div class="filter-bar" style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <select name="tahun" class="filter-select" onchange="this.form.submit()" style="padding: 8px 16px; border: 1px solid #E2E8F0; border-radius: 10px; background: white; cursor: pointer;">
                @for($year = date('Y'); $year >= date('Y')-5; $year--)
                    <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>Tahun {{ $year }}</option>
                @endfor
            </select>
        </form>
    </div>
    
    <!-- Welcome Card -->
    <div class="welcome-card" style="background: linear-gradient(135deg, #1A2A3A 0%, #2A3F54 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h2 style="color: white; margin-bottom: 8px; font-size: 24px;">
                    <i class="fas fa-chart-line"></i> Sistem Prioritas Perbaikan Jalan
                </h2>
                <p style="color: #8BA3BC; margin: 0;">Dinas Pekerjaan Umum dan Penataan Ruang</p>
                <div style="margin-top: 16px;">
                    <span style="background: rgba(255,255,255,0.1); padding: 4px 12px; border-radius: 20px; font-size: 12px; color: #F9A826;">
                        <i class="fas fa-calendar"></i> Periode: {{ $tahun }}
                    </span>
                </div>
            </div>
            <div style="text-align: center;">
                <div style="background: #F9A826; border-radius: 16px; padding: 15px 25px;">
                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #1A2A3A;">Progress Data</div>
                    <div style="font-size: 36px; font-weight: 800; color: #1A2A3A;">{{ $progress_kelengkapan['persen'] }}%</div>
                    <div style="font-size: 11px; color: #1A2A3A;">{{ $progress_kelengkapan['lengkap'] }}/{{ $progress_kelengkapan['total'] }} Jalan Lengkap</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistik Cards Utama - 4 Kolom -->
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 30px;">
        
        <!-- Card 1: Total Jalan -->
        <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #3B82F6; transition: all 0.3s;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Total Jalan</div>
                    <div style="font-size: 32px; font-weight: 800; color: #1A2A3A;">{{ $total_jalan }}</div>
                    <div style="margin-top: 8px;">
                        <span style="font-size: 12px; color: #10B981;">
                            <i class="fas fa-check-circle"></i> Aktif: {{ $jalan_aktif }}
                        </span>
                        <span style="font-size: 12px; color: #EF4444; margin-left: 12px;">
                            <i class="fas fa-times-circle"></i> Nonaktif: {{ $jalan_nonaktif }}
                        </span>
                    </div>
                </div>
                <div style="width: 48px; height: 48px; background: #EFF6FF; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-road" style="font-size: 24px; color: #3B82F6;"></i>
                </div>
            </div>
        </div>
        
        <!-- Card 2: Total Penilaian -->
        <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #8B5CF6;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Total Penilaian</div>
                    <div style="font-size: 32px; font-weight: 800; color: #1A2A3A;">{{ $total_penilaian }}</div>
                    <div style="margin-top: 8px;">
                        <span style="font-size: 12px; color: #8B5CF6;">
                            <i class="fas fa-calendar"></i> Tahun {{ $tahun }}
                        </span>
                    </div>
                </div>
                <div style="width: 48px; height: 48px; background: #EDE9FE; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-chart-line" style="font-size: 24px; color: #8B5CF6;"></i>
                </div>
            </div>
        </div>
        
        <!-- Card 3: Menunggu Validasi -->
        <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #F59E0B;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Menunggu Validasi</div>
                    <div style="font-size: 32px; font-weight: 800; color: #F59E0B;">{{ $penilaian_pending }}</div>
                    <div style="margin-top: 8px;">
                        <span style="font-size: 12px; color: #F59E0B;">
                            <i class="fas fa-clock"></i> Perlu diverifikasi
                        </span>
                    </div>
                </div>
                <div style="width: 48px; height: 48px; background: #FEF3C7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-hourglass-half" style="font-size: 24px; color: #F59E0B;"></i>
                </div>
            </div>
        </div>
        
        <!-- Card 4: Sudah Divalidasi -->
        <div class="stat-card" style="background: white; border-radius: 16px; padding: 20px; border-left: 4px solid #10B981;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 13px; color: #6B7280; margin-bottom: 4px;">Sudah Divalidasi</div>
                    <div style="font-size: 32px; font-weight: 800; color: #10B981;">{{ $penilaian_divalidasi }}</div>
                    <div style="margin-top: 8px;">
                        <span style="font-size: 12px; color: #10B981;">
                            <i class="fas fa-check-circle"></i> Siap digunakan
                        </span>
                    </div>
                </div>
                <div style="width: 48px; height: 48px; background: #D1FAE5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="font-size: 24px; color: #10B981;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafik Section - 2 Kolom -->
    <div class="charts-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 30px;">
        
        <!-- Grafik Perkembangan Bulanan -->
        <div class="chart-card" style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #F9A826;">
                <i class="fas fa-chart-line" style="color: #F9A826; margin-right: 8px;"></i> Perkembangan Penilaian {{ $tahun }}
            </h3>
            <div style="height: 280px;">
                <canvas id="chartBulanan"></canvas>
            </div>
        </div>
        
        <!-- Grafik Status Validasi -->
        <div class="chart-card" style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #F9A826;">
                <i class="fas fa-chart-pie" style="color: #F9A826; margin-right: 8px;"></i> Status Validasi
            </h3>
            <div style="height: 220px;">
                <canvas id="chartStatus"></canvas>
            </div>
            <div class="legend-status" style="display: flex; justify-content: center; gap: 24px; margin-top: 16px;">
                <div><span style="display: inline-block; width: 12px; height: 12px; background: #F59E0B; border-radius: 2px;"></span> Pending: {{ $penilaian_pending }}</div>
                <div><span style="display: inline-block; width: 12px; height: 12px; background: #10B981; border-radius: 2px;"></span> Divalidasi: {{ $penilaian_divalidasi }}</div>
                <div><span style="display: inline-block; width: 12px; height: 12px; background: #EF4444; border-radius: 2px;"></span> Ditolak: {{ $penilaian_ditolak }}</div>
            </div>
        </div>
    </div>
    
    <!-- Top 5 Ranking & Aktivitas Terbaru -->
    <div class="bottom-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 30px;">
        
        <!-- Top 5 Ranking Prioritas -->
        <div class="ranking-card" style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #E2E8F0; background: #F8FAFC;">
                <h3 style="font-size: 16px; font-weight: 700; margin: 0;">
                    <i class="fas fa-trophy" style="color: #F9A826; margin-right: 8px;"></i> Top 5 Prioritas Perbaikan
                </h3>
            </div>
            <div>
                @forelse($top5_ranking as $item)
                <div style="padding: 14px 20px; border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="rank-badge" style="width: 32px; height: 32px; background: {{ $item->peringkat == 1 ? '#EF4444' : ($item->peringkat <= 3 ? '#F59E0B' : '#10B981') }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                            {{ $item->peringkat }}
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 14px;">{{ $item->jalan->nama ?? '-' }}</div>
                            <div style="font-size: 11px; color: #6B7280;">{{ $item->jalan->lokasi ?? '-' }}</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 800; color: #F9A826;">{{ number_format($item->skor_akhir, 4) }}</div>
                        <div style="font-size: 10px; color: #6B7280;">Skor Akhir</div>
                    </div>
                </div>
                @empty
                <div style="padding: 40px; text-align: center; color: #6B7280;">
                    <i class="fas fa-chart-simple" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p>Belum ada hasil perhitungan SAW</p>
                    <a href="{{ route('admin.saw.form') }}" class="btn-saw-small" style="display: inline-block; margin-top: 12px; background: #F9A826; color: #1A2A3A; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px;">
                        <i class="fas fa-calculator"></i> Proses SAW Sekarang
                    </a>
                </div>
                @endforelse
            </div>
            @if($top5_ranking->isNotEmpty())
            <div style="padding: 12px 20px; border-top: 1px solid #E2E8F0; background: #F8FAFC;">
                <a href="{{ route('admin.hasil-saw.index') }}" style="color: #F9A826; text-decoration: none; font-size: 13px;">
                    Lihat semua ranking <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            @endif
        </div>
        
        <!-- Aktivitas Terbaru -->
        <div class="activity-card" style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; overflow: hidden;">
            <div style="padding: 16px 20px; border-bottom: 1px solid #E2E8F0; background: #F8FAFC;">
                <h3 style="font-size: 16px; font-weight: 700; margin: 0;">
                    <i class="fas fa-history" style="color: #F9A826; margin-right: 8px;"></i> Aktivitas Terbaru
                </h3>
            </div>
            <div style="max-height: 340px; overflow-y: auto;">
                @forelse($aktivitas_terbaru as $item)
                <div style="padding: 12px 20px; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 32px; height: 32px; background: #FEF3E0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        @if($item->status_validasi == 'pending')
                            <i class="fas fa-clock" style="color: #F59E0B;"></i>
                        @elseif($item->status_validasi == 'divalidasi')
                            <i class="fas fa-check-circle" style="color: #10B981;"></i>
                        @else
                            <i class="fas fa-times-circle" style="color: #EF4444;"></i>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 13px; font-weight: 500;">{{ $item->jalan->nama ?? '-' }}</div>
                        <div style="font-size: 11px; color: #6B7280;">
                            {{ $item->kriteria->nama ?? '-' }}: {{ number_format($item->nilai, 2) }} | 
                            {{ $item->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div>
                        @if($item->status_validasi == 'pending')
                            <span style="background: #FEF3C7; color: #D97706; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 600;">Pending</span>
                        @elseif($item->status_validasi == 'divalidasi')
                            <span style="background: #D1FAE5; color: #059669; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 600;">Valid</span>
                        @else
                            <span style="background: #FEE2E2; color: #DC2626; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: 600;">Ditolak</span>
                        @endif
                    </div>
                </div>
                @empty
                <div style="padding: 40px; text-align: center; color: #6B7280;">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p>Belum ada aktivitas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Achievement & Statistik Tambahan -->
    <div class="extra-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
        
        <!-- Achievement Cards -->
        <div class="achievement-card" style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #F9A826;">
                <i class="fas fa-medal" style="color: #F9A826; margin-right: 8px;"></i> Pencapaian
            </h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                @foreach($achievements as $ach)
                <div style="text-align: center; padding: 16px; background: #F8FAFC; border-radius: 12px;">
                    <div style="width: 48px; height: 48px; background: {{ $ach['color'] }}20; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                        <i class="{{ $ach['icon'] }}" style="font-size: 24px; color: {{ $ach['color'] }};"></i>
                    </div>
                    <div style="font-size: 20px; font-weight: 800;">{{ $ach['value'] }}</div>
                    <div style="font-size: 12px; font-weight: 600;">{{ $ach['title'] }}</div>
                    <div style="font-size: 10px; color: #6B7280; margin-top: 4px;">{{ $ach['target'] }}</div>
                    <div style="margin-top: 8px; background: #E2E8F0; border-radius: 10px; height: 4px;">
                        <div style="width: {{ $ach['progress'] }}%; background: {{ $ach['color'] }}; height: 4px; border-radius: 10px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Top Petugas Aktif -->
        <div class="top-user-card" style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #F9A826;">
                <i class="fas fa-users" style="color: #F9A826; margin-right: 8px;"></i> Petugas Teraktif
            </h3>
            <div>
                @forelse($top_petugas as $petugas)
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #E2E8F0;">
                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #F9A826, #E8912A); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                        {{ strtoupper(substr($petugas->createdBy->name ?? 'P', 0, 1)) }}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600;">{{ $petugas->createdBy->name ?? '-' }}</div>
                        <div style="font-size: 11px; color: #6B7280;">Petugas Lapangan</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 800; color: #F9A826;">{{ $petugas->total }}</div>
                        <div style="font-size: 10px; color: #6B7280;">Penilaian</div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 40px; color: #6B7280;">
                    <i class="fas fa-user" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                    <p>Belum ada aktivitas petugas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .stat-card:hover,
    .chart-card:hover,
    .ranking-card:hover,
    .activity-card:hover,
    .achievement-card:hover,
    .top-user-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #F9A826;
    }
    
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        
        .charts-grid {
            grid-template-columns: 1fr !important;
        }
        
        .bottom-grid {
            grid-template-columns: 1fr !important;
        }
        
        .extra-grid {
            grid-template-columns: 1fr !important;
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr !important;
        }
        
        .welcome-card {
            padding: 20px;
        }
        
        .stat-card {
            padding: 16px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik Perkembangan Bulanan
    const ctxBulanan = document.getElementById('chartBulanan').getContext('2d');
    new Chart(ctxBulanan, {
        type: 'line',
        data: {
            labels: {!! json_encode($bulan_list) !!},
            datasets: [{
                label: 'Jumlah Penilaian',
                data: {!! json_encode($penilaian_per_bulan) !!},
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
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Penilaian: ${context.raw} data`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Jumlah Penilaian', color: '#6B7280' },
                    grid: { color: '#E2E8F0' }
                },
                x: {
                    title: { display: true, text: 'Bulan', color: '#6B7280' },
                    grid: { display: false }
                }
            }
        }
    });
    
    // Grafik Status Validasi
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($validasi_stats['labels']) !!},
            datasets: [{
                data: {!! json_encode($validasi_stats['data']) !!},
                backgroundColor: {!! json_encode($validasi_stats['colors']) !!},
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = {{ $total_penilaian }};
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