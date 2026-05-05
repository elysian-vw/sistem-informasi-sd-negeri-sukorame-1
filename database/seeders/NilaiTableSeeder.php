<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NilaiTableSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = DB::table('siswa')->pluck('id');
        $mapel = DB::table('mata_pelajaran')->pluck('id');
        $semester = ['Ganjil', 'Genap'];
        $tahunAjaran = '2024/2025';

        foreach ($siswa as $ids) {
            foreach ($mapel as $idm) {
                $tugas = rand(60, 95);
                $uts   = rand(60, 95);
                $uas   = rand(60, 95);
                $akhir = ($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4);
                DB::table('nilai')->insert([
                    'siswa_id'          => $ids,
                    'mata_pelajaran_id' => $idm,
                    'nilai_tugas'       => $tugas,
                    'nilai_uts'         => $uts,
                    'nilai_uas'         => $uas,
                    'nilai_akhir'       => round($akhir, 2),
                    'semester'          => $semester[array_rand($semester)],
                    'tahun_ajaran'      => $tahunAjaran,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }
}