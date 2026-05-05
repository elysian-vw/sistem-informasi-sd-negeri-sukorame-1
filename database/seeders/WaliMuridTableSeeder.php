<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WaliMuridTableSeeder extends Seeder
{
    public function run(): void
    {
        $waliUsers = DB::table('users')->where('role', 'wali_murid')->get();
        $siswa = DB::table('siswa')->get();

        foreach ($waliUsers as $idx => $wali) {
            DB::table('wali_murid')->insert([
                'user_id'    => $wali->id,
                'siswa_id'   => $siswa[$idx % count($siswa)]->id,
                'hubungan'   => ['ayah', 'ibu', 'wali'][$idx % 3],
                'nik'        => '3174' . rand(100000, 999999),
                'pekerjaan'  => 'Swasta',
                'no_hp'      => $wali->no_hp,
                'alamat'     => 'Jl. Wali No. ' . ($idx+1),
                'is_aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}