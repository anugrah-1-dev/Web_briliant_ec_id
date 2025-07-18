<?php

namespace App\Exports;

use App\Models\PendaftaranProgramOnline;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PendaftaranOnlineExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return PendaftaranProgramOnline::select([
            'trx_id',
            'nama_lengkap',
            'email',
            'no_hp',
            'asal_kota',
            'program_id',
            'period_id',
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
            'Program ID',
            'Periode ID',
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

                // ✅ 1. Set style border, alignment, wrap text
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

                // ✅ 2. Atur tinggi baris
                for ($row = 1; $row <= $highestRow; $row++) {
                    $sheet->getDelegate()->getRowDimension($row)->setRowHeight(25); // atau lebih tinggi
                }

                // ✅ 3. Atur lebar kolom
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }

                // ✅ 4. Header bold & lebih tinggi
                $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
                    'font' => ['bold' => true],
                ]);
                $sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
}
