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

        // Proteksi jika user admin belum ada
        if (!$userId) {
            $this->command->warn('User Admin belum ditemukan! Lewati seeding Berita.');
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            $slug = Str::slug("berita-sekolah-$i");

            DB::table('berita')->updateOrInsert(
                ['slug' => $slug], // Kondisi pencarian berdasarkan slug
                [
                    'judul'        => "Berita Sekolah $i",
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
                ]
            );
        }
    }
}