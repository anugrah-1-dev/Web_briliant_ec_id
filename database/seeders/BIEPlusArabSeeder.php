<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramOffline;
use Illuminate\Support\Str;

class BIEPlusArabSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama Arab di bieplus
        ProgramOffline::where('kursus', 'bieplus')->where('program_bahasa', 'Arab')->delete();

        $programsOffline = [
            [
                'nama'             => "I'dad",
                'lama_program'     => '22 Hari',
                'kategori'         => "I'dad",
                'harga'            => 165000,
                'jadwal_mulai'     => '2026-05-04',
                'jadwal_selesai'   => '2026-12-28',
                'features_program' => json_encode([
                    'Pelajaran dasar bahasa Arab',
                    'Qowaid',
                    "Qira'ah",
                    'Muhadatsah',
                ]),
            ],
            [
                'nama'             => 'Mustawa Awwal',
                'lama_program'     => '22 Hari',
                'kategori'         => 'Mustawa 1',
                'harga'            => 460000,
                'jadwal_mulai'     => '2026-05-04',
                'jadwal_selesai'   => '2026-12-28',
                'features_program' => json_encode([
                    'Latihan percakapan',
                    'Penyusunan kalimat sederhana',
                    'Target hingga 1500 kosakata',
                    'Kelas dan suasana belajar yang nyaman',
                ]),
            ],
            [
                'nama'             => 'Mustawa Tsani',
                'lama_program'     => '22 Hari',
                'kategori'         => 'Mustawa 2',
                'harga'            => 460000,
                'jadwal_mulai'     => '2026-05-04',
                'jadwal_selesai'   => '2026-12-28',
                'features_program' => json_encode([
                    'Pendalaman kaidah',
                    'Percakapan lancar',
                    'Presentasi topik',
                    'Target hingga 2100 kosakata',
                ]),
            ],
            [
                'nama'             => 'Mustawa Tsalist',
                'lama_program'     => '22 Hari',
                'kategori'         => 'Mustawa 3',
                'harga'            => 460000,
                'jadwal_mulai'     => '2026-05-04',
                'jadwal_selesai'   => '2026-12-28',
                'features_program' => json_encode([
                    'Penekanan dalam fashohah',
                    "Insya' (mengarang)",
                    'Latihan alih bahasa',
                    'Target hingga 3000 lebih kosakata',
                ]),
            ],
        ];

        foreach ($programsOffline as $data) {
            ProgramOffline::create([
                'nama'             => $data['nama'],
                'slug'             => Str::slug($data['nama']) . '-arab-bieplus',
                'program_bahasa'   => 'Arab',
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
    }
}
