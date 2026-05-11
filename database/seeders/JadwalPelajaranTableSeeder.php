<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalPelajaranTableSeeder extends Seeder
{
    /**
     * Jadwal realistis SD:
     * - Kelas 1: 10 mapel, guru berbeda, full Senin-Jumat (+ Sabtu bila perlu)
     * - Tiap hari ada ~6 jam pelajaran (dari 9 slot yang tersedia)
     * - Mapel banyak jam (MTK, B.Indo) muncul lebih sering dari mapel sedikit jam
     * - Setiap slot (kelas, hari, jam, semester) unik
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('jadwal_pelajaran')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $tahun = '2025/2026';

        $waktu = [
            1 => ['mulai' => '07:00:00', 'selesai' => '07:35:00'],
            2 => ['mulai' => '07:35:00', 'selesai' => '08:10:00'],
            3 => ['mulai' => '08:10:00', 'selesai' => '08:45:00'],
            4 => ['mulai' => '08:45:00', 'selesai' => '09:15:00'],
            5 => ['mulai' => '09:30:00', 'selesai' => '10:05:00'],
            6 => ['mulai' => '10:05:00', 'selesai' => '10:40:00'],
            7 => ['mulai' => '10:55:00', 'selesai' => '11:30:00'],
            8 => ['mulai' => '11:30:00', 'selesai' => '12:05:00'],
            9 => ['mulai' => '12:05:00', 'selesai' => '12:40:00'],
        ];

        /**
         * Template jadwal kelas 1 — manual & realistis
         * Format: [hari, jam_ke, mapel_id, guru_id]
         *
         * mapel_id kelas 1:
         *  1 = Matematika       (guru 1 - Budi)
         *  2 = Bahasa Indonesia (guru 5 - Eko)
         *  3 = Bahasa Inggris   (guru 1 - Budi)
         *  4 = IPA              (guru 1 - Budi)
         *  5 = IPS              (guru 2 - Siti)
         *  6 = PAI              (guru 2 - Siti)
         *  7 = PJOK             (guru 5 - Eko)
         *  8 = Seni Budaya      (guru 3 - Agus)
         *  9 = PPKn             (guru 5 - Eko)
         * 10 = Bahasa Daerah    (guru 1 - Budi)
         */
        $templateKelas1 = [
            // SENIN: MTK(3), B.Indo(2), PAI(1)
            ['senin',  1, 1,  1],
            ['senin',  2, 1,  1],
            ['senin',  3, 1,  1],
            ['senin',  4, 2,  5],
            ['senin',  5, 2,  5],
            ['senin',  6, 6,  2],
            ['senin',  7, 6,  2],
            ['senin',  8, 9,  5],
            ['senin',  9, 9,  5],

            // SELASA: B.Indo(2), PJOK(2), IPA(2), Seni(1)
            ['selasa', 1, 2,  5],
            ['selasa', 2, 2,  5],
            ['selasa', 3, 7,  5],
            ['selasa', 4, 7,  5],
            ['selasa', 5, 4,  1],
            ['selasa', 6, 4,  1],
            ['selasa', 7, 8,  3],
            ['selasa', 8, 8,  3],
            ['selasa', 9, 3,  1],

            // RABU: MTK(3), IPS(2), B.Inggris(2)
            ['rabu',   1, 1,  1],
            ['rabu',   2, 1,  1],
            ['rabu',   3, 1,  1],
            ['rabu',   4, 5,  2],
            ['rabu',   5, 5,  2],
            ['rabu',   6, 3,  1],
            ['rabu',   7, 3,  1],
            ['rabu',   8, 10, 1],
            ['rabu',   9, 10, 1],

            // KAMIS: B.Indo(2), MTK(2), PAI(2), PPKn(2)
            ['kamis',  1, 2,  5],
            ['kamis',  2, 2,  5],
            ['kamis',  3, 1,  1],
            ['kamis',  4, 1,  1],
            ['kamis',  5, 6,  2],
            ['kamis',  6, 6,  2],
            ['kamis',  7, 9,  5],
            ['kamis',  8, 4,  1],
            ['kamis',  9, 5,  2],

            // JUMAT: B.Inggris(2), IPA(2), Seni(2), Mulok(2)
            ['jumat',  1, 3,  1],
            ['jumat',  2, 3,  1],
            ['jumat',  3, 4,  1],
            ['jumat',  4, 4,  1],
            ['jumat',  5, 8,  3],
            ['jumat',  6, 8,  3],
            ['jumat',  7, 10, 1],
            ['jumat',  8, 10, 1],
            ['jumat',  9, 7,  5],

            // SABTU: PJOK(2), IPS(2), MTK(1)
            ['sabtu',  1, 7,  5],
            ['sabtu',  2, 7,  5],
            ['sabtu',  3, 5,  2],
            ['sabtu',  4, 5,  2],
            ['sabtu',  5, 1,  1],
            ['sabtu',  6, 6,  2],
        ];

        // Kelas 1 = id 1 (tingkat 1)
        $kelas1Id  = DB::table('kelas')->where('tingkat', 1)->value('id');
        $ruangan1  = DB::table('kelas')->where('id', $kelas1Id)->value('ruang_kelas') ?? 'Ruang 1';

        $jadwal = [];

        foreach (['1', '2'] as $semester) {
            foreach ($templateKelas1 as [$hari, $jamKe, $mapelId, $guruId]) {
                $jadwal[] = [
                    'kelas_id'          => $kelas1Id,
                    'guru_id'           => $guruId,
                    'mata_pelajaran_id' => $mapelId,
                    'hari'              => $hari,
                    'jam_ke'            => $jamKe,
                    'waktu_mulai'       => $waktu[$jamKe]['mulai'],
                    'waktu_selesai'     => $waktu[$jamKe]['selesai'],
                    'ruangan'           => $ruangan1,
                    'semester'          => $semester,
                    'tahun_ajaran'      => $tahun,
                    'is_aktif'          => true,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }
        }

        // ── Kelas 2-6: generate otomatis dari mata_pelajaran.guru_id ──────────
        $kelasByTingkat = DB::table('kelas')->get()->keyBy('tingkat');
        $ruanganMap     = DB::table('kelas')->pluck('ruang_kelas', 'id');
        $mapelPerGuru   = DB::table('mata_pelajaran')
            ->whereNotNull('guru_id')
            ->where('tingkat', '>', 1) // skip kelas 1, sudah manual
            ->get()
            ->groupBy('guru_id');

        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        foreach (['1', '2'] as $semester) {
            // Slot tracker hanya untuk kelas 2-6
            $slotUsed = [];
            // Isi slot kelas 1 sebagai sudah terpakai
            foreach ($templateKelas1 as [$hari, $jamKe]) {
                $slotUsed["{$kelas1Id}|{$hari}|{$jamKe}|{$semester}"] = true;
            }

            foreach ($mapelPerGuru as $guruId => $mapels) {
                $mapelList = $mapels->values();
                $total     = $mapelList->count();
                $assigned  = 0;

                // Buat slot queue merata per hari
                $slotPerHari = [];
                foreach ($hariList as $hari) {
                    $jams = array_keys($waktu);
                    shuffle($jams);
                    $slotPerHari[$hari] = $jams;
                }

                $slotQueue = [];
                for ($j = 0; $j < count($waktu); $j++) {
                    foreach ($hariList as $hari) {
                        if (isset($slotPerHari[$hari][$j])) {
                            $slotQueue[] = [$hari, $slotPerHari[$hari][$j]];
                        }
                    }
                }

                foreach ($slotQueue as [$hari, $jamKe]) {
                    if ($assigned >= $total) break;

                    $mapel = $mapelList[$assigned];
                    $kelas = $kelasByTingkat[$mapel->tingkat] ?? null;
                    if (!$kelas) { $assigned++; continue; }

                    $key = "{$kelas->id}|{$hari}|{$jamKe}|{$semester}";
                    if (!isset($slotUsed[$key])) {
                        $slotUsed[$key] = true;
                        $jadwal[] = [
                            'kelas_id'          => $kelas->id,
                            'guru_id'           => $guruId,
                            'mata_pelajaran_id' => $mapel->id,
                            'hari'              => $hari,
                            'jam_ke'            => $jamKe,
                            'waktu_mulai'       => $waktu[$jamKe]['mulai'],
                            'waktu_selesai'     => $waktu[$jamKe]['selesai'],
                            'ruangan'           => $ruanganMap[$kelas->id] ?? 'Ruang ' . $kelas->id,
                            'semester'          => $semester,
                            'tahun_ajaran'      => $tahun,
                            'is_aktif'          => true,
                            'created_at'        => now(),
                            'updated_at'        => now(),
                        ];
                        $assigned++;
                    }
                }
            }
        }

        foreach (array_chunk($jadwal, 100) as $chunk) {
            DB::table('jadwal_pelajaran')->insert($chunk);
        }

        $this->command->info("Jadwal di-seed: " . count($jadwal) . " data.");
    }
}