<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalPelajaranTableSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = DB::table('kelas')->pluck('id');
        $guru  = DB::table('guru')->pluck('id');
        $mapel = DB::table('mata_pelajaran')->pluck('id');
        $hari  = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

        for ($i = 1; $i <= 100; $i++) {
            DB::table('jadwal_pelajaran')->insert([
                'kelas_id'           => $kelas->random(),
                'guru_id'            => $guru->random(),
                'mata_pelajaran_id'  => $mapel->random(),
                'hari'               => $hari[array_rand($hari)],
                'jam_ke'             => rand(1, 6),
                'waktu_mulai'        => '07:30:00',
                'waktu_selesai'      => '08:10:00',
                'ruangan'            => 'Ruang ' . rand(1, 10),
                'semester'           => rand(1,2),
                'tahun_ajaran'       => '2024/2025',
                'is_aktif'           => true,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }
    }
}