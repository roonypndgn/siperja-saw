<?php

namespace App\Exports;

use App\Models\Jalan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class JalanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, ShouldAutoSize, WithTitle
{
    protected $search;
    protected $status;
    
    public function __construct($search = null, $status = null)
    {
        $this->search = $search;
        $this->status = $status;
    }
    
    public function collection()
    {
        $query = Jalan::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('kode', 'like', '%' . $this->search . '%')
                  ->orWhere('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($this->status === 'nonaktif') {
            $query->where('is_active', false);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }
    
    public function headings(): array
    {
        return [
            'NO',
            'KODE JALAN',
            'NAMA JALAN',
            'LOKASI',
            'PANJANG (M)',
            'DESKRIPSI',
            'LATITUDE',
            'LONGITUDE',
            'STATUS',
            'DIBUAT OLEH',
            'TANGGAL DIBUAT',
            'TERAKHIR DIUBAH'
        ];
    }
    
    public function map($jalan): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $jalan->kode,
            $jalan->nama,
            $jalan->lokasi,
            $jalan->panjang,
            $jalan->deskripsi ?? '-',
            $jalan->latitude ?? '-',
            $jalan->longitude ?? '-',
            $jalan->is_active ? 'AKTIF' : 'NONAKTIF',
            $jalan->createdBy->name ?? '-',
            $jalan->created_at->format('d/m/Y H:i:s'),
            $jalan->updated_at->format('d/m/Y H:i:s'),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A2A3A'], // Biru kehitaman
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Style untuk semua cell
        $sheet->getStyle('A1:L' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Style untuk baris data
        $sheet->getStyle('A2:L' . ($sheet->getHighestRow()))->applyFromArray([
            'font' => [
                'size' => 11,
            ],
        ]);
        
        // Warna bergantian untuk baris
        $rowCount = $sheet->getHighestRow();
        for ($i = 2; $i <= $rowCount; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':L' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8FAFC'],
                    ],
                ]);
            }
            
            // Warna merah untuk status NONAKTIF
            $statusCell = $sheet->getCell('I' . $i);
            if ($statusCell->getValue() == 'NONAKTIF') {
                $sheet->getStyle('I' . $i)->applyFromArray([
                    'font' => ['color' => ['rgb' => 'EF4444'], 'bold' => true],
                ]);
            } else {
                $sheet->getStyle('I' . $i)->applyFromArray([
                    'font' => ['color' => ['rgb' => '10B981'], 'bold' => true],
                ]);
            }
        }
        
        // Tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        return $sheet;
    }
    
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // NO
            'B' => 12,  // KODE
            'C' => 30,  // NAMA
            'D' => 25,  // LOKASI
            'E' => 12,  // PANJANG
            'F' => 40,  // DESKRIPSI
            'G' => 15,  // LATITUDE
            'H' => 15,  // LONGITUDE
            'I' => 12,  // STATUS
            'J' => 20,  // DIBUAT OLEH
            'K' => 18,  // TANGGAL DIBUAT
            'L' => 18,  // TERAKHIR DIUBAH
        ];
    }
    
    public function title(): string
    {
        return 'Data Jalan ' . date('Y-m-d');
    }
}