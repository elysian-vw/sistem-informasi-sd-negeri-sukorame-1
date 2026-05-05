<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruTableSeeder extends Seeder
{
    public function run(): void
    {
        $guruUsers = DB::table('users')->where('role', 'guru')->get();
        $mapel = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS'];

        foreach ($guruUsers as $idx => $guru) {
            DB::table('guru')->insert([
                'user_id'        => $guru->id,
                'nip'            => '1965' . rand(100000, 999999),
                'mata_pelajaran' => $mapel[$idx % count($mapel)],
                'status'         => 'aktif',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}