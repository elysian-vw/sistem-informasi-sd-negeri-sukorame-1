<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BeritaTableSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('role', 'admin')->value('id');

        for ($i = 1; $i <= 5; $i++) {
            DB::table('berita')->insert([
                'judul'        => "Berita Sekolah $i",
                'slug'         => Str::slug("berita-sekolah-$i"),
                'ringkasan'    => "Ringkasan berita ke-$i",
                'isi'          => "<p>Isi berita lengkap ke-$i</p>",
                'thumbnail'    => null,
                'kategori'     => 'berita',
                'status'       => 'published',
                'published_at' => now(),
                'created_by'   => $userId,
                'views'        => rand(10, 500),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}