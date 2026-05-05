<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestasiTableSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = DB::table('siswa')->pluck('id');
        $tingkat = ['sekolah', 'kecamatan', 'kota', 'provinsi', 'nasional'];
        $juara = ['1', '2', '3', 'harapan_1', 'harapan_2'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('prestasi')->insert([
                'siswa_id'     => $siswa->random(),
                'nama_lomba'   => "Lomba $i",
                'penyelenggara'=> "Penyelenggara $i",
                'tingkat'      => $tingkat[array_rand($tingkat)],
                'juara'        => $juara[array_rand($juara)],
                'tanggal'      => now()->subDays(rand(1, 365)),
                'bidang'       => 'Akademik',
                'foto'         => null,
                'keterangan'   => 'Keterangan prestasi',
                'is_published' => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}