<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasTableSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['nama_kelas' => '1', 'tingkat' => 1, 'kapasitas' => 30],
            ['nama_kelas' => '2', 'tingkat' => 2, 'kapasitas' => 30],
            ['nama_kelas' => '3', 'tingkat' => 3, 'kapasitas' => 30],
            ['nama_kelas' => '4', 'tingkat' => 4, 'kapasitas' => 30],
            ['nama_kelas' => '5', 'tingkat' => 5, 'kapasitas' => 30],
            ['nama_kelas' => '6', 'tingkat' => 6, 'kapasitas' => 30],
        ];

        foreach ($kelas as $k) {
            DB::table('kelas')->insert(array_merge($k, [
                'wali_kelas_id' => null,
                'ruang_kelas'   => 'Ruang ' . $k['nama_kelas'],
                'status'        => 'aktif',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]));
        }
    }
}