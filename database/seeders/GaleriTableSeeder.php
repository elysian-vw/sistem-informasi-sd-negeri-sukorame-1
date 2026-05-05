<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GaleriTableSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('role', 'admin')->value('id');

        for ($i = 1; $i <= 10; $i++) {
            DB::table('galeri')->insert([
                'judul'        => "Foto Kegiatan $i",
                'deskripsi'    => "Deskripsi foto kegiatan $i",
                'tipe'         => 'foto',
                'file_path'    => 'storage/galeri/foto'.$i.'.jpg',
                'url_video'    => null,
                'thumbnail'    => null,
                'kategori'     => 'kegiatan',
                'tanggal'      => now(),
                'is_published' => true,
                'urutan'       => $i,
                'created_by'   => $userId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}