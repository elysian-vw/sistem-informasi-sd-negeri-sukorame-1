<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KegiatanTableSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('role', 'admin')->value('id');
        $kegiatan = [
            ['judul' => 'Upacara Bendera', 'tanggal_mulai' => '2025-04-17', 'kategori' => 'upacara', 'sasaran' => 'semua'],
            ['judul' => 'Penilaian Tengah Semester', 'tanggal_mulai' => '2025-05-01', 'tanggal_selesai' => '2025-05-10', 'kategori' => 'penilaian', 'sasaran' => 'siswa'],
            ['judul' => 'Perpisahan Kelas 6', 'tanggal_mulai' => '2025-06-15', 'kategori' => 'perpisahan', 'sasaran' => 'siswa'],
        ];

        foreach ($kegiatan as $k) {
            DB::table('kegiatan')->insert(array_merge($k, [
                'deskripsi'      => 'Deskripsi ' . $k['judul'],
                'waktu_mulai'    => '07:30:00',
                'waktu_selesai'  => '12:00:00',
                'tempat'         => 'Lapangan',
                'is_published'   => true,
                'created_by'     => $userId,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]));
        }
    }
}