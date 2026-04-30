<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        /* Standar Ukuran Kertas F4/A4 */
        @page { 
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

        /* Desain Tabel Laporan */
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 10pt;
        }
        .table-data th { 
            background-color: #e9ecef; 
            padding: 10px 5px; 
            border: 1px solid #000;
            text-transform: uppercase;
            font-weight: bold;
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
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            border: 1px solid #ccc;
        }
        .aktif { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .non-aktif { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }

        /* Tanda Tangan (Sesuai Tata Naskah) */
        .ttd-container {
            margin-top: 30px;
            width: 100%;
        }
        .ttd-table {
            width: 100%;
        }
        .ttd-space { height: 70px; }

        .footer-note { 
            position: fixed;
            bottom: 0.5cm;
            font-size: 8pt; 
            color: #555;
            font-style: italic;
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
                    <div class="kop-instansi-2">Kementerian Pekerjaan Umum Direktorat Jenderal Sumber Daya Air</div>
                    <div class="kop-alamat">
                        Jl. Jenderal Besar Jl. Jenderal Besar A.H. Nasution No.30, Pangkalan Masyhur, Kec. Medan Johor, Kota Medan, Sumatera Utara 20143<br>
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
            <td width="48%">005 / LAP-JALAN / {{ date('Y') }}</td>
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
        <div class="report-title">{{ $title }}</div>
        <div>Sistem Informasi Prioritas Perbaikan Jalan Ruas Nasional</div>
    </div>

    <!-- TABEL DATA -->
    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Kode</th>
                <th width="28%">Nama Ruas Jalan</th>
                <th width="23%">Wilayah/Lokasi</th>
                <th width="12%">Panjang (m)</th>
                <th width="10%">Status</th>
                <th width="13%">Update</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jalan as $index => $j)
            <tr>
                <td align="center">{{ $index + 1 }}</td>
                <td align="center"><strong>{{ $j->kode }}</strong></td>
                <td>{{ $j->nama }}</td>
                <td>{{ $j->lokasi }}</td>
                <td align="right">{{ number_format($j->panjang, 0, ',', '.') }}</td>
                <td align="center">
                    <span class="status-badge {{ $j->is_active ? 'aktif' : 'non-aktif' }}">
                        {{ $j->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                    </span>
                </td>
                <td align="center">{{ $j->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-size: 10pt; margin-top: 10px;">
        * Total data yang dilaporkan: <strong>{{ $total }} ruas jalan</strong>.
    </p>

    <!-- TANDA TANGAN -->
    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td width="60%"></td>
                <td width="40%" align="center">
                    <p>Kepala Bidang Operasional,</p>
                    <div class="ttd-space"></div>
                    <p><strong><u>{{ $user }}</u></strong><br>
                    NIP. 19850312 201001 1 002</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        Dicetak secara sistem pada: {{ now('Asia/Jakarta')->format('d/m/Y H:i:s') }} | Kode Dokumen: {{ strtoupper(Str::random(8)) }}
    </div>

</body>
</html>