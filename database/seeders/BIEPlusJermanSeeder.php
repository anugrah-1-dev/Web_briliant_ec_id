<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramOffline;
use App\Models\ProgramOnline;
use Illuminate\Support\Str;

class BIEPlusJermanSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama Jerman di bieplus
        ProgramOffline::where('kursus', 'bieplus')->where('program_bahasa', 'Jerman')->delete();
        ProgramOnline::where('kursus', 'bieplus')->where('program_bahasa', 'Jerman')->delete();

        // =====================
        // PROGRAM OFFLINE
        // =====================

        $fasilitasA1 = [
            'Level Dasar Pemula',
            'Pembelajaran dengan Modul Khusus',
            'Gratis Camp Reguler',
            'Sertifikat',
        ];

        $fasilitasA2 = [
            'Level Dasar Lanjutan',
            'Pembelajaran dengan Modul Khusus',
            'Gratis Camp Reguler',
            'Sertifikat',
        ];

        $fasilitasB1 = [
            'Level Menengah',
            'Pembelajaran dengan Modul Khusus',
            'Program Presentasi Khusus (Hari Sabtu)',
            'Gratis Camp Reguler',
        ];

        $programsOffline = [
            [
                'nama'          => 'Program Offline Level A1 (Periode 15)',
                'lama_program'  => '15 Hari',
                'kategori'      => 'A1',
                'harga'         => 3500000,
                'jadwal_mulai'  => '2026-05-04',
                'jadwal_selesai'=> '2026-12-28',
                'features_program' => json_encode($fasilitasA1),
            ],
            [
                'nama'          => 'Program Offline Level A1 (Periode 30)',
                'lama_program'  => '15 Hari',
                'kategori'      => 'A1',
                'harga'         => 3500000,
                'jadwal_mulai'  => '2026-05-04',
                'jadwal_selesai'=> '2026-12-28',
                'features_program' => json_encode($fasilitasA1),
            ],
            [
                'nama'          => 'Program Offline Level A2 (Periode 15)',
                'lama_program'  => '15 Hari',
                'kategori'      => 'A2',
                'harga'         => 4000000,
                'jadwal_mulai'  => '2026-05-04',
                'jadwal_selesai'=> '2026-12-28',
                'features_program' => json_encode($fasilitasA2),
            ],
            [
                'nama'          => 'Program Offline Level A2 (Periode 30)',
                'lama_program'  => '15 Hari',
                'kategori'      => 'A2',
                'harga'         => 4000000,
                'jadwal_mulai'  => '2026-05-04',
                'jadwal_selesai'=> '2026-12-28',
                'features_program' => json_encode($fasilitasA2),
            ],
            [
                'nama'          => 'Program Offline Level B1 (Periode 15)',
                'lama_program'  => '15 Hari',
                'kategori'      => 'B1',
                'harga'         => 4500000,
                'jadwal_mulai'  => '2026-05-04',
                'jadwal_selesai'=> '2026-12-28',
                'features_program' => json_encode($fasilitasB1),
            ],
            [
                'nama'          => 'Program Offline Level B1 (Periode 30)',
                'lama_program'  => '15 Hari',
                'kategori'      => 'B1',
                'harga'         => 4500000,
                'jadwal_mulai'  => '2026-05-04',
                'jadwal_selesai'=> '2026-12-28',
                'features_program' => json_encode($fasilitasB1),
            ],
        ];

        foreach ($programsOffline as $data) {
            ProgramOffline::create([
                'nama'             => $data['nama'],
                'slug'             => Str::slug($data['nama']),
                'program_bahasa'   => 'Jerman',
                'lama_program'     => $data['lama_program'],
                'kategori'         => $data['kategori'],
                'harga'            => $data['harga'],
                'features_program' => $data['features_program'],
                'jadwal_mulai'     => $data['jadwal_mulai'],
                'jadwal_selesai'   => $data['jadwal_selesai'],
                'lokasi'           => 'Pare, Kediri',
                'kuota'            => 50,
                'is_active'        => 1,
                'kursus'           => 'bieplus',
                'thumbnail'        => null,
            ]);
        }

        // =====================
        // PROGRAM ONLINE
        // =====================

        $fasilitasOnline = [
            'Buku',
            'Sertifikat',
            'Zoom Meeting',
            'dssaaa',
        ];

        $programsOnline = [
            [
                'nama'          => 'Kelas Private Online',
                'lama_program'  => 'Kelas satuan online',
                'kategori'      => 'Private',
                'harga'         => 3960000,
                'features_program' => json_encode($fasilitasOnline),
            ],
        ];

        foreach ($programsOnline as $data) {
            ProgramOnline::create([
                'nama'             => $data['nama'],
                'slug'             => Str::slug($data['nama']) . '-jerman-bieplus',
                'program_bahasa'   => 'Jerman',
                'lama_program'     => $data['lama_program'],
                'kategori'         => $data['kategori'],
                'harga'            => $data['harga'],
                'features_program' => $data['features_program'],
                'is_active'        => 1,
                'kursus'           => 'bieplus',
                'thumbnail'        => null,
            ]);
        }
    }
}
