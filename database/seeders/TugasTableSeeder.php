<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasTableSeeder extends Seeder
{
    public function run(): void
    {
        $mapelList = DB::table('mata_pelajaran')
            ->whereNotNull('guru_id')
            ->get();

        if ($mapelList->isEmpty()) {
            $this->command->warn('Data mata pelajaran kosong! Lewati seeding Tugas.');
            return;
        }

        $status = ['aktif', 'draft'];
        $data   = [];

        foreach ($mapelList as $mapel) {
            $kelas = DB::table('kelas')->where('tingkat', $mapel->tingkat)->first();
            if (!$kelas) continue;

            // ── Tugas upload biasa ────────────────────────────────────────────
            $data[] = [
                'judul'             => 'Tugas ' . $mapel->nama,
                'deskripsi'         => 'Kerjakan soal-soal latihan ' . $mapel->nama,
                'file'              => null,
                'mata_pelajaran_id' => $mapel->id,
                'kelas_id'          => $kelas->id,
                'guru_id'           => $mapel->guru_id,
                'deadline'          => now()->addDays(rand(7, 21)),
                'status'            => $status[array_rand($status)],
                'tipe'              => 'upload',
                'created_at'        => now(),
                'updated_at'        => now(),
            ];

            // ── Tugas CBT ─────────────────────────────────────────────────────
            $data[] = [
                'judul'             => 'Ulangan Harian ' . $mapel->nama,
                'deskripsi'         => 'Kerjakan soal pilihan ganda berikut dengan teliti.',
                'file'              => null,
                'mata_pelajaran_id' => $mapel->id,
                'kelas_id'          => $kelas->id,
                'guru_id'           => $mapel->guru_id,
                'deadline'          => now()->addDays(rand(7, 14)),
                'status'            => 'aktif',
                'tipe'              => 'cbt',
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('tugas')->insert($chunk);
        }

        $this->command->info('Tugas di-seed: ' . count($data) . ' data.');

        $this->seedSoalCbt();
    }

    private function seedSoalCbt(): void
    {
        $tugasCbt = DB::table('tugas')->where('tipe', 'cbt')->get();

        if ($tugasCbt->isEmpty()) {
            $this->command->warn('Tidak ada tugas CBT ditemukan.');
            return;
        }

        $templateSoal = [
            [
                'soal'          => 'Berapakah hasil dari 12 × 8?',
                'pilihan_a'     => '96',
                'pilihan_b'     => '86',
                'pilihan_c'     => '106',
                'pilihan_d'     => '76',
                'jawaban_benar' => 'A',
                'pakai_gambar'  => true,
            ],
            [
                'soal'          => 'Manakah yang merupakan bilangan prima?',
                'pilihan_a'     => '4',
                'pilihan_b'     => '6',
                'pilihan_c'     => '7',
                'pilihan_d'     => '9',
                'jawaban_benar' => 'C',
                'pakai_gambar'  => false,
            ],
            [
                'soal'          => 'Ibu kota negara Indonesia adalah?',
                'pilihan_a'     => 'Bandung',
                'pilihan_b'     => 'Surabaya',
                'pilihan_c'     => 'Medan',
                'pilihan_d'     => 'Jakarta',
                'jawaban_benar' => 'D',
                'pakai_gambar'  => true,
            ],
            [
                'soal'          => 'Hewan yang berkembang biak dengan bertelur disebut?',
                'pilihan_a'     => 'Vivipar',
                'pilihan_b'     => 'Ovipar',
                'pilihan_c'     => 'Ovovivipar',
                'pilihan_d'     => 'Mamalia',
                'jawaban_benar' => 'B',
                'pakai_gambar'  => false,
            ],
            [
                'soal'          => 'Pancasila memiliki berapa sila?',
                'pilihan_a'     => '3',
                'pilihan_b'     => '4',
                'pilihan_c'     => '5',
                'pilihan_d'     => '6',
                'jawaban_benar' => 'C',
                'pakai_gambar'  => true,
            ],
        ];

        $soalData   = [];
        $gambarSeed = 100;

        $totalCbt = $tugasCbt->count();
        $current  = 0;

        foreach ($tugasCbt as $tugas) {
            $current++;
            $this->command->info("Seeding soal CBT [{$current}/{$totalCbt}]: {$tugas->judul}");

            foreach ($templateSoal as $soal) {
                $gambarUrl = null;

                if ($soal['pakai_gambar']) {
                    $gambarUrl = "https://picsum.photos/seed/{$gambarSeed}/800/400";
                    $gambarSeed++;
                }

                $soalData[] = [
                    'tugas_id'      => $tugas->id,
                    'soal'          => $soal['soal'],
                    'gambar_soal'   => $gambarUrl,
                    'pilihan_a'     => $soal['pilihan_a'],
                    'pilihan_b'     => $soal['pilihan_b'],
                    'pilihan_c'     => $soal['pilihan_c'],
                    'pilihan_d'     => $soal['pilihan_d'],
                    'jawaban_benar' => $soal['jawaban_benar'],
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
        }

        foreach (array_chunk($soalData, 100) as $chunk) {
            DB::table('pertanyaans')->insert($chunk);
        }

        $this->command->info('Soal CBT di-seed: ' . count($soalData) . ' soal.');
        $this->command->info('Gambar pakai URL langsung, tidak ada file yang didownload.');
    }
}