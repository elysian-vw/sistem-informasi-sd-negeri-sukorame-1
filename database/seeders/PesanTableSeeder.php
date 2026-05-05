<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesanTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');

        for ($i = 1; $i <= 20; $i++) {
            DB::table('pesan')->insert([
                'pengirim_id' => $users->random(),
                'penerima_id' => $users->random(),
                'subjek'      => "Subjek pesan $i",
                'isi'         => "Ini isi pesan ke-$i",
                'is_read'     => rand(0,1),
                'read_at'     => rand(0,1) ? now() : null,
                'parent_id'   => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}