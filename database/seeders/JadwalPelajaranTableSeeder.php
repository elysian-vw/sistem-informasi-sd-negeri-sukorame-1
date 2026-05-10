<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalPelajaranTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal_pelajaran')->truncate();

        $tahun    = '2024/2025';
        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $jamList  = [
            1 => ['mulai' => '07:00:00', 'selesai' => '07:35:00'],
            2 => ['mulai' => '07:35:00', 'selesai' => '08:10:00'],
            3 => ['mulai' => '08:10:00', 'selesai' => '08:45:00'],
            4 => ['mulai' => '09:00:00', 'selesai' => '09:35:00'],
            5 => ['mulai' => '09:35:00', 'selesai' => '10:10:00'],
            6 => ['mulai' => '10:10:00', 'selesai' => '10:45:00'],
        ];

        $kelasPerTingkat = DB::table('kelas')->get()->keyBy('tingkat');
        $mapelPerGuru    = DB::table('mata_pelajaran')
            ->whereNotNull('guru_id')
            ->get()
            ->groupBy('guru_id');

        $slotTerpakai = [];
        $jadwal       = [];

        foreach ($mapelPerGuru as $guruId => $mapels) {
            foreach ($mapels as $mapel) {
                $kelas = $kelasPerTingkat[$mapel->tingkat] ?? null;
                if (!$kelas) continue;

                foreach (['1', '2'] as $semester) {
                    $hariAcak = collect($hariList)->shuffle();
                    $found    = false;

                    foreach ($hariAcak as $hari) {
                        foreach (collect(array_keys($jamList))->shuffle() as $jamKe) {
                            $key = "{$kelas->id}-{$hari}-{$jamKe}-{$semester}";
                            if (!isset($slotTerpakai[$key])) {
                                $slotTerpakai[$key] = true;
                                $found = true;
                                $jadwal[] = [
                                    'kelas_id'          => $kelas->id,
                                    'guru_id'           => $guruId,
                                    'mata_pelajaran_id' => $mapel->id,
                                    'hari'              => $hari,
                                    'jam_ke'            => $jamKe,
                                    'waktu_mulai'       => $jamList[$jamKe]['mulai'],
                                    'waktu_selesai'     => $jamList[$jamKe]['selesai'],
                                    'ruangan'           => 'Ruang ' . $kelas->id,
                                    'semester'          => $semester,
                                    'tahun_ajaran'      => $tahun,
                                    'is_aktif'          => true,
                                    'created_at'        => now(),
                                    'updated_at'        => now(),
                                ];
                                break 2;
                            }
                        }
                    }

                    if (!$found) {
                        $this->command->warn("Slot penuh untuk mapel ID {$mapel->id} kelas {$kelas->id} sem {$semester}");
                    }
                }
            }
        }

        foreach (array_chunk($jadwal, 50) as $chunk) {
            DB::table('jadwal_pelajaran')->insert($chunk);
        }

        $this->command->info("Jadwal berhasil di-seed: " . count($jadwal) . " data.");
    }
}