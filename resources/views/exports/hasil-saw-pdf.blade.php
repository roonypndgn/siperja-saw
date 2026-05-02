<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} Tahun {{ $statistik['tahun'] }}</title>
    <style>
        /* Standar Ukuran Kertas F4/A4 Landscape */
        @page { 
            size: A4 landscape;
            margin: 1.5cm 2cm 2cm 2cm; 
        }
        
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 11pt; /* Standar naskah dinas */
            line-height: 1.3;
            color: #000;
            margin: 0;
        }

        /* Desain Kop Surat Standar Pemerintah */
        .kop-container {
            width: 100%;
            border-bottom: 4px solid #000;
            padding-bottom: 2px;
            margin-bottom: 2px;
        }
        .kop-border-thin {
            border-bottom: 1px solid #000;
            margin-bottom: 20px;
        }
        .table-kop {
            width: 100%;
            border: none;
        }
        .td-logo {
            width: 15%;
            text-align: center;
            vertical-align: middle;
        }
        .td-instansi {
            width: 85%;
            text-align: center;
            vertical-align: middle;
            padding-right: 10%; /* Offset logo agar teks tetap center */
        }
        .logo-pupr {
            width: 75px;
            height: auto;
        }
        .kop-instansi-1 { font-size: 14pt; text-transform: uppercase; margin: 0; }
        .kop-instansi-2 { font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 0; }
        .kop-alamat { font-size: 9pt; font-style: italic; margin: 0; }

        /* Nomor & Klasifikasi Surat */
        .meta-surat {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
        }

        /* Judul Laporan */
        .report-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .report-title {
            text-decoration: underline;
            font-weight: bold;
            font-size: 13pt;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .report-subtitle {
            font-size: 11pt;
            margin-top: 5px;
            text-transform: uppercase;
        }
        .report-periode {
            font-size: 10pt;
            font-style: italic;
            margin-top: 3px;
        }

        /* Informasi Ringkasan (Disesuaikan dengan gaya referensi) */
        .info-ringkasan {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #1A2A3A;
            font-size: 9pt;
        }
        .info-ringkasan table {
            width: 100%;
            border: none;
        }
        .info-ringkasan td {
            border: none;
            padding: 3px 5px;
        }

        /* Rekomendasi Box */
        .rekomendasi-box {
            margin: 15px 0;
            padding: 10px 15px;
            background: #FEF3E0;
            border-left: 4px solid #F9A826;
        }
        .rekomendasi-box h5 {
            font-size: 10pt;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: #000;
        }
        .rekomendasi-box ol {
            margin-left: 20px;
            margin-top: 0;
            margin-bottom: 0;
            font-size: 9pt;
        }
        .rekomendasi-box li {
            margin-bottom: 4px;
        }

        /* Desain Tabel Laporan */
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 9pt;
            margin: 15px 0;
        }
        .table-data th { 
            background-color: #e9ecef; 
            padding: 10px 5px; 
            border: 1px solid #000;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
        }
        .table-data td { 
            padding: 7px 5px; 
            border: 1px solid #000; 
            vertical-align: middle;
        }
        /* Zebra Striping Halus */
        .table-data tr:nth-child(even) {
            background-color: #fafafa;
        }
        
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        /* Badge Peringkat */
        .rank-1 { background: #EF4444; color: white; font-weight: bold; display: inline-block; width: 22px; height: 22px; line-height: 22px; border-radius: 50%; font-size: 9pt; }
        .rank-2 { background: #F59E0B; color: white; font-weight: bold; display: inline-block; width: 22px; height: 22px; line-height: 22px; border-radius: 50%; font-size: 9pt; }
        .rank-3 { background: #10B981; color: white; font-weight: bold; display: inline-block; width: 22px; height: 22px; line-height: 22px; border-radius: 50%; font-size: 9pt; }
        .rank-other { background: #6B7280; color: white; font-weight: bold; display: inline-block; width: 22px; height: 22px; line-height: 22px; border-radius: 50%; font-size: 9pt; }
        
        /* Badge Prioritas */
        .priority-1 { background: #FEE2E2; color: #DC2626; padding: 2px 8px; border-radius: 10px; font-size: 8pt; font-weight: bold; display: inline-block; }
        .priority-2 { background: #FEF3C7; color: #D97706; padding: 2px 8px; border-radius: 10px; font-size: 8pt; font-weight: bold; display: inline-block; }
        .priority-3 { background: #D1FAE5; color: #059669; padding: 2px 8px; border-radius: 10px; font-size: 8pt; font-weight: bold; display: inline-block; }
        .priority-4 { background: #E2E8F0; color: #4B5563; padding: 2px 8px; border-radius: 10px; font-size: 8pt; font-weight: bold; display: inline-block; }

        .badge-kode { font-family: monospace; font-weight: bold; }
        .skor-value { font-weight: bold; font-size: 10pt; }

        /* Catatan & Checklist */
        .catatan-box {
            margin: 15px 0;
            padding: 10px;
            background: #F8FAFC;
            border-left: 3px solid #1A2A3A;
        }
        .catatan-box p { margin: 0; font-size: 8pt; color: #333; }
        
        .checklist-container {
            margin-top: 20px; 
            padding-top: 10px; 
            border-top: 1px solid #000; 
            font-size: 9pt;
        }
        .checklist-items {
            display: flex; 
            flex-wrap: wrap; 
            gap: 20px; 
            margin-top: 5px;
        }

        /* Tanda Tangan (Sesuai Tata Naskah) */
        .ttd-container {
            margin-top: 30px;
            width: 100%;
        }
        .ttd-table {
            width: 100%;
        }
        .ttd-space { 
            height: 70px; 
        }

        /* Footer */
        .footer-note { 
            position: fixed;
            bottom: 0.5cm;
            font-size: 8pt; 
            color: #555;
            font-style: italic;
            left: 2cm;
            right: 2cm;
        }

        @media print {
            .table-data th {
                background-color: #e9ecef !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .rank-1, .rank-2, .rank-3, .rank-other, .priority-1, .priority-2, .priority-3, .priority-4 {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- KOP SURAT STRUKTUR TABEL -->
    <div class="kop-container">
        <table class="table-kop">
            <tr>
                <td class="td-logo">
                    <!-- Menggunakan gambar base64 sesuai referensi desain -->
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo-pu.png'))) }}" class="logo-pupr" alt="Logo">
                </td>
                <td class="td-instansi">
                    <div class="kop-instansi-1">Pemerintah Republik Indonesia</div>
                    <div class="kop-instansi-1">Kementerian Pekerjaan Umum dan Perumahan Rakyat</div>
                    <div class="kop-instansi-2">DINAS PEKERJAAN UMUM DAN PENATAAN RUANG</div>
                    <div class="kop-alamat">
                        Jl. Jenderal Besar Jl. Jenderal Besar A.H. Nasution No.30, Pangkalan Masyhur, Kec. Medan Johor, Kota Medan, Sumatera Utara 20143<br>
                        Telepon: (061) 7861455, Email: pusat@pu.go.id, Website: www.pu.go.id<br>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="kop-border-thin"></div>

    <!-- META DATA SURAT -->
    <table class="meta-surat">
        <tr>
            <td width="10%">Nomor</td>
            <td width="2%">:</td>
            <td width="48%">005 / LAP-SAWAW / {{ $statistik['tahun'] }}</td>
            <td width="40%" align="right">Jakarta, {{ now('Asia/Jakarta')->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Sifat</td>
            <td>:</td>
            <td>Biasa</td>
            <td></td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>1 (satu) Berkas</td>
            <td></td>
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="report-header">
        <div class="report-title">{{ $title }}</div>
        <div class="report-subtitle">PRIORITAS PERBAIKAN JALAN TAHUN {{ $statistik['tahun'] }}</div>
        <div class="report-periode">Periode Penilaian: Januari - Desember {{ $statistik['tahun'] }}</div>
    </div>

    <!-- INFORMASI RINGKASAN -->
    <div class="info-ringkasan">
        <table>
            <tr>
                <td width="25%"><strong>Total Jalan:</strong> {{ $statistik['total_jalan'] }} jalan</td>
                <td width="25%"><strong>Skor Tertinggi:</strong> {{ number_format($statistik['skor_tertinggi'], 4) }}</td>
                <td width="25%"><strong>Skor Terendah:</strong> {{ number_format($statistik['skor_terendah'], 4) }}</td>
                <td width="25%"><strong>Rata-rata Skor:</strong> {{ number_format($statistik['rata_rata'], 4) }}</td>
            </tr>
        </table>
    </div>

    <!-- REKOMENDASI TINDAK LANJUT -->
    <div class="rekomendasi-box">
        <h5>📋 REKOMENDASI TINDAK LANJUT</h5>
        <ol>
            <li><strong>Prioritas Utama:</strong> Segera lakukan perbaikan pada <strong>{{ $prioritasUtama->jalan->nama ?? '-' }}</strong> dengan skor tertinggi ({{ number_format($prioritasUtama->skor_akhir ?? 0, 4) }}).</li>
            <li>Alokasikan anggaran untuk <strong>{{ $top3->count() ?? 0 }} jalan prioritas tinggi</strong> (Peringkat 1-3) pada semester 1 tahun anggaran berjalan.</li>
            <li>Lakukan survei lanjutan untuk <strong>{{ isset($top5) ? ($top5->count() - 3) : 0 }} jalan</strong> dengan peringkat 4-5 untuk persiapan perbaikan tahap berikutnya.</li>
            <li>Evaluasi ulang data dan lakukan penilaian ulang untuk jalan dengan skor terendah.</li>
        </ol>
    </div>

    <!-- TABEL DATA RANKING -->
    <table class="table-data">
        <thead>
            <tr>
                <th width="6%">Peringkat</th>
                <th width="10%">Kode</th>
                <th width="25%">Nama Jalan</th>
                <th width="20%">Lokasi</th>
                <th width="10%">Skor Akhir</th>
                <th width="14%">Tingkat Prioritas</th>
                <th width="15%">Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasil as $item)
            <tr>
                <td class="text-center">
                    @if($item->peringkat == 1)
                        <span class="rank-1">{{ $item->peringkat }}</span>
                    @elseif($item->peringkat == 2)
                        <span class="rank-2">{{ $item->peringkat }}</span>
                    @elseif($item->peringkat == 3)
                        <span class="rank-3">{{ $item->peringkat }}</span>
                    @else
                        <span class="rank-other">{{ $item->peringkat }}</span>
                    @endif
                </td>
                <td class="text-center"><span class="badge-kode">{{ $item->jalan->kode ?? '-' }}</span></td>
                <td class="text-left"><strong>{{ $item->jalan->nama ?? '-' }}</strong></td>
                <td class="text-left">{{ $item->jalan->lokasi ?? '-' }}</td>
                <td class="text-center"><span class="skor-value">{{ number_format($item->skor_akhir, 4) }}</span></td>
                <td class="text-center">
                    @if($item->peringkat == 1)
                        <span class="priority-1">SANGAT PRIORITAS</span>
                    @elseif($item->peringkat <= 3)
                        <span class="priority-2">PRIORITAS TINGGI</span>
                    @elseif($item->peringkat <= 5)
                        <span class="priority-3">PRIORITAS</span>
                    @else
                        <span class="priority-4">PRIORITAS RENDAH</span>
                    @endif
                </td>
                <td class="text-left">
                    @if($item->peringkat == 1)
                        Segera perbaiki
                    @elseif($item->peringkat <= 3)
                        Jadwalkan perbaikan
                    @elseif($item->peringkat <= 5)
                        Rencanakan perbaikan
                    @else
                        Prioritas rendah
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 30px;">
                    <strong>Tidak ada data prioritas yang ditemukan</strong>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- CATATAN -->
    <div class="catatan-box">
        <p><strong>* Catatan:</strong> Laporan ini dihasilkan secara otomatis oleh <strong>Sistem Prioritas Perbaikan Jalan - Dinas PUPR</strong>. Perhitungan menggunakan metode <strong>SAW (Simple Additive Weighting)</strong> dengan mempertimbangkan bobot dan tipe masing-masing kriteria. Prioritas perbaikan ditentukan berdasarkan skor akhir tertinggi. Semakin tinggi skor (mendekati 1), semakin prioritas jalan tersebut untuk diperbaiki.</p>
    </div>

    <!-- TANDA TANGAN DENGAN FORMAT TATA NASKAH -->
    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td width="60%"></td>
                <td width="40%" align="center">
                    <p style="margin: 0;">Jakarta, {{ now('Asia/Jakarta')->translatedFormat('d F Y') }}<br>Kepala Dinas PUPR,</p>
                    <div class="ttd-space"></div>
                    <p style="margin: 0;"><strong><u>Ir. H. Rahmat Hidayat, M.T.</u></strong><br>
                    NIP. 19650515 199003 1 012</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- DAFTAR PERIKSA (Checklist) -->
    <div class="checklist-container">
        <strong>Lampiran Berkas:</strong>
        <div class="checklist-items">
            <div>✓ Data Nilai Kriteria</div>
            <div>✓ Hasil Perhitungan SAW</div>
            <div>✓ Tabel Ranking Prioritas</div>
            <div>✓ Rekomendasi Teknis</div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Prioritas Perbaikan Jalan - Dinas PUPR.<br>
        Dicetak secara sistem pada: {{ now('Asia/Jakarta')->format('d/m/Y H:i:s') }} WIB | Kode Dokumen: LAP-SAWAW-{{ $statistik['tahun'] }}
    </div>

</body>
</html>