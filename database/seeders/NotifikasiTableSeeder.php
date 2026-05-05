<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotifikasiTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');

        for ($i = 1; $i <= 50; $i++) {
            DB::table('notifikasi')->insert([
                'user_id'    => $users->random(),
                'judul'      => "Notifikasi $i",
                'pesan'      => "Ini adalah pesan notifikasi ke-$i",
                'tipe'       => 'info',
                'url'        => null,
                'is_read'    => rand(0,1),
                'read_at'    => rand(0,1) ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}