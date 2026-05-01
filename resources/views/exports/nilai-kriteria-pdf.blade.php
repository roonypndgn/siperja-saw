<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai Kriteria - Sistem Prioritas Perbaikan Jalan</title>
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
        }

        /* Desain Tabel Laporan */
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 9pt;
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
            vertical-align: top;
        }
        /* Zebra Striping Halus */
        .table-data tr:nth-child(even) {
            background-color: #fafafa;
        }
        
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        
        /* Status Badge */
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
        }
        .status-valid { 
            color: #155724; 
            background-color: #d4edda; 
            border: 1px solid #c3e6cb;
        }
        .status-pending { 
            color: #856404; 
            background-color: #fff3cd; 
            border: 1px solid #ffeeba;
        }
        .status-invalid { 
            color: #721c24; 
            background-color: #f8d7da; 
            border: 1px solid #f5c6cb;
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

        .footer-note { 
            position: fixed;
            bottom: 0.5cm;
            font-size: 8pt; 
            color: #555;
            font-style: italic;
            left: 2cm;
            right: 2cm;
        }
        
        /* Informasi Ringkasan */
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
    </style>
</head>
<body>

    <!-- KOP SURAT STRUKTUR TABEL (LEBIH STABIL) -->
    <div class="kop-container">
        <table class="table-kop">
            <tr>
                <td class="td-logo">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo-pu.png'))) }}" class="logo-pupr">
                </td>
                <td class="td-instansi">
                    <div class="kop-instansi-1">Pemerintah Republik Indonesia</div>
                    <div class="kop-instansi-2">Kementerian Pekerjaan Umum dan Perumahan Rakyat</div>
                    <div class="kop-alamat">
                        Direktorat Jenderal Sumber Daya Air<br>
                        Jl. Jenderal Besar A.H. Nasution No.30, Pangkalan Masyhur, Kec. Medan Johor, Kota Medan, Sumatera Utara 20143<br>
                        Telepon: (061) 7861455, Email: pusat@pu.go.id, Website: www.pu.go.id
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
            <td width="48%">005 / LAP-NILAI-KRITERIA / {{ date('Y') }}</td>
            <td width="40%" align="right">Medan, {{ now('Asia/Jakarta')->format('d/m/Y H:i:s') }}</td>
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
        <div class="report-title">LAPORAN NILAI KRITERIA</div>
        <div class="report-subtitle">Sistem Informasi Prioritas Perbaikan Jalan Ruas Nasional</div>
    </div>

    <!-- INFORMASI RINGKASAN -->
    <div class="info-ringkasan">
        <table>
            <tr>
                <td width="33%"><strong>Tahun Penilaian:</strong> {{ $tahun }}</td>
                <td width="33%"><strong>Status:</strong> {{ $status == 'semua' ? 'Semua Status' : ucfirst($status) }}</td>
                <td width="34%"><strong>Total Data:</strong> {{ $statistik['total'] }} Nilai</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak:</strong> {{ $statistik['tanggal_cetak'] }}</td>
                <td><strong>Kode Dokumen:</strong> {{ strtoupper(\Illuminate\Support\Str::random(8)) }}</td>
            </tr>
        </table>
    </div>

    <!-- TABEL DATA -->
    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Kode Jalan</th>
                <th width="20%">Nama Jalan</th>
                <th width="15%">Lokasi</th>
                <th width="15%">Kriteria</th>
                <th width="8%">Nilai</th>
                <th width="8%">Tahun</th>
                <th width="10%">Status</th>
                <th width="10%">Validator</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center"><strong>{{ $item->jalan->kode ?? '-' }}</strong></td>
                <td class="text-left">{{ $item->jalan->nama ?? '-' }}</td>
                <td class="text-left">{{ $item->jalan->lokasi ?? '-' }}</td>
                <td class="text-left">{{ $item->kriteria->nama ?? '-' }}</td>
                <td class="text-center">
                    <strong>{{ number_format($item->nilai, 2) }}</strong>
                    @if($item->kriteria && $item->kriteria->satuan)
                        <br><small>{{ $item->kriteria->satuan }}</small>
                    @endif
                </td>
                <td class="text-center">{{ $item->tahun_penilaian }}</td>
                <td class="text-center">
                    @if($item->status_validasi == 'divalidasi')
                        <span class="status-badge status-valid">
                            Valid
                        </span>
                    @elseif($item->status_validasi == 'pending')
                        <span class="status-badge status-pending">
                            Pending
                        </span>
                    @else
                        <span class="status-badge status-invalid">
                            Ditolak
                        </span>
                    @endif
                </td>
                <td class="text-center">{{ $item->validatedBy->name ?? '-' }}</td>
            </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 40px;">
                        <strong>Tidak ada data nilai kriteria yang ditemukan</strong>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- CATATAN -->
    <p style="font-size: 9pt; margin-top: 10px;">
        * Total nilai yang dilaporkan: <strong>{{ $statistik['total'] }} data</strong>.
    </p>
<!-- TANDA TANGAN DENGAN NIP DARI USER -->
<div class="ttd-container">
    <table class="ttd-table">
        <tr>
            <td width="60%"></td>
            <td width="40%" align="center">
                <p>Kepala Bidang Operasional,</p>
                <div class="ttd-space"></div>
                <p><strong><u>{{ $statistik['user_name'] ?? 'Petugas' }}</u></strong><br>
                NIP. {{ $statistik['user_nip'] ?? '.........................' }}</p>
            </td>
        </tr>
    </table>
</div>

    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Prioritas Perbaikan Jalan - Dinas PUPR.<br>
        Dicetak pada: {{ now('Asia/Jakarta')->format('d/m/Y H:i:s') }} | Kode Dokumen: {{ strtoupper(\Illuminate\Support\Str::random(12)) }}
    </div>

</body>
</html>