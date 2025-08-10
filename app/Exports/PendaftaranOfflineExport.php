<?php

namespace App\Exports;

use App\Models\PendaftaranProgramOffline;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PendaftaranOfflineExport implements FromCollection, WithHeadings, WithMapping, WithEvents, WithDrawings
{
    protected $pendaftarans;
    protected $row_height = 80;
    protected $image_column_width = 25;

    public function __construct($startDate, $endDate)
    {
        // Mengambil data pendaftar dari database, lengkap dengan relasinya (program, periode, bank, dll.)
        $this->pendaftarans = PendaftaranProgramOffline::with(['program', 'period', 'transport', 'bank'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();
    }

    public function collection()
    {
        // Mengembalikan data yang sudah diambil di constructor
        return $this->pendaftarans;
    }

    public function headings(): array
    {
        // Menentukan judul untuk setiap kolom di file Excel
        return [
            'ID Transaksi', 'Nama Lengkap', 'Email', 'No HP', 'Asal Kota', 'No Wali',
            'Nama Program', 'Tanggal Periode', 'Transportasi', 
            'Tipe Pembayaran', // Kolom baru
            'Bank Tujuan',     // Kolom baru
            'Bukti Pembayaran', 
            'Status',
        ];
    }

    public function map($pendaftaran): array
    {
        // Mengatur data apa saja yang akan ditampilkan di setiap baris
        $periodText = '-';
        if ($pendaftaran->period) {
            $tanggalMulai = $pendaftaran->period->tanggal_mulai ?? $pendaftaran->period->date;
            $tanggalSelesai = $pendaftaran->period->tanggal_selesai ?? $pendaftaran->period->date;
            $startDate = \Carbon\Carbon::parse($tanggalMulai);
            $endDate = \Carbon\Carbon::parse($tanggalSelesai);
            $periodText = $startDate->isSameDay($endDate) ? $startDate->translatedFormat('d F Y') : $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');
        }

        return [
            $pendaftaran->trx_id,
            $pendaftaran->nama_lengkap,
            $pendaftaran->email,
            $pendaftaran->no_hp,
            $pendaftaran->asal_kota,
            $pendaftaran->no_wali,
            $pendaftaran->program->nama ?? '-',
            $periodText,
            $pendaftaran->transport->name ?? '-',
            // Logika untuk kolom 'Tipe Pembayaran'
            ucfirst($pendaftaran->payment_type), // Menjadi 'Tunai' atau 'Transfer'
            // Logika untuk kolom 'Bank Tujuan'
            $pendaftaran->payment_type == 'transfer' ? ($pendaftaran->bank->name ?? '-') : '-',
            // Logika untuk kolom 'Bukti Pembayaran'
            $pendaftaran->payment_type == 'tunai' ? 'Tunai / Cash' : '', // Tulis 'Tunai / Cash' jika tunai, kosongkan jika transfer
            $pendaftaran->status,
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $columnWidthInPixels = $this->image_column_width * 7.5;

        foreach ($this->pendaftarans as $key => $pendaftaran) {
            // Logika ini memastikan gambar hanya ditambahkan jika tipe pembayaran adalah 'transfer'
            if ($pendaftaran->payment_type === 'transfer' && $pendaftaran->bukti_pembayaran) {
                $pathToFile = public_path('storage/' . $pendaftaran->bukti_pembayaran);
                if (!file_exists($pathToFile)) {
                    continue;
                }

                $drawing = new Drawing();
                $drawing->setName('Bukti Pembayaran');
                $drawing->setDescription($pendaftaran->nama_lengkap);
                $drawing->setPath($pathToFile);
                // Menempatkan gambar di kolom L (kolom ke-12)
                $drawing->setCoordinates('L' . ($key + 2)); 

                // Mengatur ukuran dan posisi gambar agar pas di tengah sel
                list($originalWidth, $originalHeight) = getimagesize($pathToFile);
                $newHeight = $this->row_height - 10;
                $drawing->setHeight($newHeight);
                $newWidth = ($originalWidth / $originalHeight) * $newHeight;
                $drawing->setOffsetX(($columnWidthInPixels - $newWidth) / 2);
                $drawing->setOffsetY(($this->row_height - $newHeight) / 2);

                $drawings[] = $drawing;
            }
        }
        return $drawings;
    }

    public function registerEvents(): array
    {
        // Mengatur style (tinggi baris, lebar kolom, border, dll) setelah file dibuat
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getDelegate()->getHighestRow();
                $highestColumn = $sheet->getDelegate()->getHighestColumn();
                $cellRange = 'A1:' . $highestColumn . $highestRow;

                // Pengaturan umum
                $sheet->getStyle($cellRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A1:' . $highestColumn.'1')->getFont()->setBold(true);
                $sheet->getStyle('A1:' . $highestColumn.'1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);

                // Pengaturan per baris
                foreach ($this->pendaftarans as $key => $pendaftaran) {
                    $rowNumber = $key + 2;
                    if ($pendaftaran->payment_type === 'transfer' && $pendaftaran->bukti_pembayaran && file_exists(public_path('storage/' . $pendaftaran->bukti_pembayaran))) {
                        $sheet->getDelegate()->getRowDimension($rowNumber)->setRowHeight($this->row_height);
                    } else {
                        $sheet->getDelegate()->getRowDimension($rowNumber)->setRowHeight(25);
                    }
                    // Logika ini menengahkan tulisan 'Tunai / Cash' di selnya
                    if ($pendaftaran->payment_type === 'tunai') {
                        $sheet->getStyle('L' . $rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }

                // Pengaturan lebar kolom
                foreach (range('A', 'K') as $col) { $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true); }
                $sheet->getDelegate()->getColumnDimension('M')->setAutoSize(true);
                $sheet->getDelegate()->getColumnDimension('L')->setWidth($this->image_column_width); // Kolom untuk gambar/teks bukti

                // Pengaturan border
                $sheet->getStyle($cellRange)->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]]);
            },
        ];
    }
}
