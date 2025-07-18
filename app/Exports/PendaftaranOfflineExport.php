<?php

namespace App\Exports;

use App\Models\PendaftaranProgramOffline;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PendaftaranOfflineExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return PendaftaranProgramOffline::select([
            'trx_id',
            'nama_lengkap',
            'email',
            'no_hp',
            'asal_kota',
            'no_wali',
            'program_id',
            'period_id',
            'transport_id',
            'bukti_pembayaran',
            'status',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Lengkap',
            'Email',
            'No HP',
            'Asal Kota',
            'No Wali',
            'Program ID',
            'Periode ID',
            'Transportasi ID',
            'Bukti Pembayaran',
            'Status',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getDelegate()->getHighestRow();
                $highestColumn = $sheet->getDelegate()->getHighestColumn();
                $cellRange = 'A1:' . $highestColumn . $highestRow;

                // ✅ 1. Set border, alignment, wrap text
                $sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // ✅ 2. Set row height
                for ($row = 1; $row <= $highestRow; $row++) {
                    $sheet->getDelegate()->getRowDimension($row)->setRowHeight(25);
                }

                // ✅ 3. Auto size for all columns
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }

                // ✅ 4. Header styling
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => ['bold' => true],
                ]);
                $sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
}
