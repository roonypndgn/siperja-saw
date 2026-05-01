<?php
namespace App\Exports;

use App\Models\Jalan;
use App\Models\Kriteria;
use App\Models\NilaiKriteriaJalan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class NilaiKriteriaPerJalanExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $tahun;
    
    public function __construct($tahun = null)
    {
        $this->tahun = $tahun ?? date('Y');
    }
    
    public function collection()
    {
        $jalan = Jalan::where('is_active', true)->orderBy('nama')->get();
        $kriteria = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        
        $data = [];
        $no = 1;
        
        foreach ($jalan as $j) {
            $row = [
                'no' => $no,
                'kode_jalan' => $j->kode,
                'nama_jalan' => $j->nama,
                'lokasi' => $j->lokasi,
                'panjang' => $j->panjang,
            ];
            
            // Tambahkan kolom nilai per kriteria
            foreach ($kriteria as $krit) {
                $nilai = NilaiKriteriaJalan::where('jalan_id', $j->id)
                    ->where('kriteria_id', $krit->id)
                    ->where('tahun_penilaian', $this->tahun)
                    ->first();
                
                $row['kriteria_' . $krit->id] = $nilai ? $nilai->nilai : '-';
            }
            
            // Status kelengkapan
            $nilaiCount = NilaiKriteriaJalan::where('jalan_id', $j->id)
                ->where('tahun_penilaian', $this->tahun)
                ->count();
            
            $row['status'] = ($nilaiCount == $kriteria->count()) ? 'Lengkap' : 'Belum Lengkap';
            
            $data[] = $row;
            $no++;
        }
        
        return collect($data);
    }
    
    public function headings(): array
    {
        $kriteria = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        $headings = [
            'NO',
            'KODE JALAN',
            'NAMA JALAN',
            'LOKASI',
            'PANJANG (m)'
        ];
        
        foreach ($kriteria as $krit) {
            $headings[] = $krit->nama . ' (' . ($krit->satuan ?? 'Skala Bebas') . ')';
        }
        
        $headings[] = 'STATUS KELENGKAPAN';
        
        return $headings;
    }
    
    public function styles(Worksheet $sheet)
    {
        $kriteriaCount = Kriteria::where('is_active', true)->count();
        $lastColumn = chr(69 + $kriteriaCount); // E + jumlah kriteria
        
        // Style header
        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A2A3A']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0']
                ]
            ]
        ]);
        
        $sheet->setAutoFilter('A1:' . $lastColumn . $lastRow);
        
        return [];
    }
    
    public function columnWidths(): array
    {
        $kriteriaCount = Kriteria::where('is_active', true)->count();
        $widths = [
            'A' => 5,
            'B' => 12,
            'C' => 30,
            'D' => 35,
            'E' => 12,
        ];
        
        // Set lebar untuk kolom kriteria
        for ($i = 0; $i < $kriteriaCount; $i++) {
            $column = chr(70 + $i); // F, G, H, dst
            $widths[$column] = 20;
        }
        
        $lastColumn = chr(70 + $kriteriaCount);
        $widths[$lastColumn] = 18;
        
        return $widths;
    }
}