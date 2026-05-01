<?php
namespace App\Exports;

use App\Models\NilaiKriteriaJalan;
use App\Models\Jalan;
use App\Models\Kriteria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class NilaiKriteriaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $tahun;
    protected $status;
    
    public function __construct($tahun = null, $status = null)
    {
        $this->tahun = $tahun ?? date('Y');
        $this->status = $status;
    }
    
    /**
     * Ambil data yang akan diexport
     */
    public function collection()
    {
        $query = NilaiKriteriaJalan::with(['jalan', 'kriteria', 'createdBy', 'validatedBy'])
            ->where('tahun_penilaian', $this->tahun);
        
        if ($this->status && $this->status != 'semua') {
            $query->where('status_validasi', $this->status);
        }
        
        return $query->orderBy('jalan_id')->orderBy('kriteria_id')->get();
    }
    
    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'NO',
            'KODE JALAN',
            'NAMA JALAN',
            'LOKASI',
            'PANJANG (m)',
            'KODE KRITERIA',
            'NAMA KRITERIA',
            'TIPE',
            'BOBOT',
            'SATUAN',
            'NILAI',
            'TAHUN PENILAIAN',
            'STATUS VALIDASI',
            'DIVALIDASI OLEH',
            'TANGGAL VALIDASI',
            'CATATAN',
            'DIBUAT OLEH',
            'TANGGAL DIBUAT'
        ];
    }
    
    /**
     * Mapping data ke kolom
     */
    public function map($item): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $item->jalan->kode ?? '-',
            $item->jalan->nama ?? '-',
            $item->jalan->lokasi ?? '-',
            $item->jalan->panjang ?? 0,
            $item->kriteria->kode ?? '-',
            $item->kriteria->nama ?? '-',
            $item->kriteria->tipe ?? '-',
            $item->kriteria->bobot ?? 0,
            $item->kriteria->satuan ?? '-',
            $item->nilai,
            $item->tahun_penilaian,
            $this->getStatusText($item->status_validasi),
            $item->validatedBy->name ?? '-',
            $item->validated_at ? $item->validated_at->format('d/m/Y H:i') : '-',
            $item->catatan ?? '-',
            $item->createdBy->name ?? '-',
            $item->created_at->format('d/m/Y H:i')
        ];
    }
    
    /**
     * Style untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:R1')->applyFromArray([
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
        
        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(20);
        
        // Style untuk semua sel
        $sheet->getStyle('A1:R' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0']
                ]
            ]
        ]);
        
        // Style untuk sel dengan status
        $lastRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $lastRow; $row++) {
            $status = $sheet->getCell('M' . $row)->getValue();
            
            if ($status == 'Valid') {
                $sheet->getStyle('M' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('D1FAE5');
            } elseif ($status == 'Pending') {
                $sheet->getStyle('M' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEF3C7');
            } elseif ($status == 'Ditolak') {
                $sheet->getStyle('M' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FEE2E2');
            }
        }
        
        // Auto filter
        $sheet->setAutoFilter('A1:R' . $lastRow);
        
        return [];
    }
    
    /**
     * Set lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 12,
            'C' => 25,
            'D' => 30,
            'E' => 12,
            'F' => 12,
            'G' => 25,
            'H' => 10,
            'I' => 8,
            'J' => 12,
            'K' => 12,
            'L' => 12,
            'M' => 15,
            'N' => 20,
            'O' => 18,
            'P' => 30,
            'Q' => 20,
            'R' => 18
        ];
    }
    
    private function getStatusText($status)
    {
        switch($status) {
            case 'divalidasi': return 'Valid';
            case 'pending': return 'Pending';
            case 'ditolak': return 'Ditolak';
            default: return '-';
        }
    }
}