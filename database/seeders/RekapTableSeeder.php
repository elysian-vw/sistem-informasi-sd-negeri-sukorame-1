<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RekapTableSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = DB::table('kelas')->pluck('id');
        $userId = DB::table('users')->where('role', 'admin')->value('id');

        // Rekap kehadiran
        for ($bulan = 1; $bulan <= 6; $bulan++) {
            foreach ($kelas as $idKelas) {
                DB::table('rekap_kehadiran')->insert([
                    'kelas_id'          => $idKelas,
                    'bulan'             => $bulan,
                    'tahun'             => 2025,
                    'total_hadir'       => rand(200, 300),
                    'total_sakit'       => rand(10, 30),
                    'total_izin'        => rand(5, 20),
                    'total_alpa'        => rand(0, 10),
                    'total_hari_efektif'=> 20,
                    'dibuat_oleh'       => $userId,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }

        // Rekap nilai
        $mapel = DB::table('mata_pelajaran')->pluck('id');
        for ($semester = 1; $semester <= 2; $semester++) {
            foreach ($kelas as $idKelas) {
                foreach ($mapel as $idMapel) {
                    DB::table('rekap_nilai')->insert([
                        'kelas_id'            => $idKelas,
                        'mata_pelajaran_id'   => $idMapel,
                        'semester'            => $semester,
                        'tahun_ajaran_mulai'  => 2024,
                        'rata_rata_kelas'     => rand(70, 85),
                        'nilai_tertinggi'     => rand(90, 100),
                        'nilai_terendah'      => rand(50, 69),
                        'jumlah_tuntas'       => rand(20, 30),
                        'jumlah_tidak_tuntas' => rand(0, 10),
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ]);
                }
            }
        }
    }
}