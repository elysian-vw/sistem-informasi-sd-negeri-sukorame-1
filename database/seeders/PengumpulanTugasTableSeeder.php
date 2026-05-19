<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengumpulanTugasTableSeeder extends Seeder
{
    public function run(): void
    {
        $tugas = DB::table('tugas')->pluck('id');
        $siswa = DB::table('siswa')->pluck('id');
        $status = ['belum', 'terlambat', 'tepat_waktu'];

        // Proteksi jika tabel tugas atau siswa masih kosong
        if ($tugas->isEmpty() || $siswa->isEmpty()) {
            $this->command->warn('Data Tugas atau Siswa masih kosong! Lewati seeding Pengumpulan Tugas.');
            return;
        }

        foreach ($tugas as $idTugas) {
            foreach ($siswa as $idSiswa) {
                if (rand(1, 100) <= 70) { // 70% siswa mengumpulkan
                    DB::table('pengumpulan_tugas')->updateOrInsert(
                        [
                            'tugas_id' => $idTugas, // Kondisi pencarian: tugas ini...
                            'siswa_id' => $idSiswa, // ...oleh siswa ini
                        ],
                        [
                            'file'            => null,
                            'catatan'         => 'Tugas dikerjakan',
                            'dikumpulkan_at'  => now()->subDays(rand(0, 5)),
                            'nilai'           => rand(60, 100),
                            'feedback'        => 'Bagus',
                            'status'          => $status[array_rand($status)],
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ]
                    );
                }
            }
        }
    }
}