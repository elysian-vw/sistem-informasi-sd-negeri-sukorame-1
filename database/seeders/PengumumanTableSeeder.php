<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengumumanTableSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('role', 'admin')->value('id');

        $pengumuman = [
            ['judul' => 'Libur Hari Raya', 'isi' => 'Libur tanggal 10-11 April 2025', 'untuk' => 'semua', 'kategori' => 'libur'],
            ['judul' => 'Pembagian Raport', 'isi' => 'Pembagian raport semester ganjil', 'untuk' => 'siswa', 'kategori' => 'akademik'],
            ['judul' => 'Rapat Guru', 'isi' => 'Rapat seluruh guru', 'untuk' => 'guru', 'kategori' => 'rapat'],
        ];

        foreach ($pengumuman as $p) {
            DB::table('pengumuman')->insert(array_merge($p, [
                'user_id'    => $userId,
                'is_aktif'   => true,
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}