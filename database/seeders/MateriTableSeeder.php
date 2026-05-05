<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriTableSeeder extends Seeder
{
    public function run(): void
    {
        $mapel = DB::table('mata_pelajaran')->get();
        $guru = DB::table('guru')->pluck('id');
        $kelas = DB::table('kelas')->pluck('id');
        $tipe = ['file', 'link', 'video', 'materi', 'tugas'];

        foreach ($mapel as $m) {
            DB::table('materi')->insert([
                'judul'             => 'Materi ' . $m->nama,
                'deskripsi'         => 'Deskripsi materi ' . $m->nama,
                'file'              => null,
                'link_video'        => 'https://youtube.com/watch?v=abc123',
                'mata_pelajaran_id' => $m->id,
                'guru_id'           => $guru->random(),
                'kelas_id'          => $kelas->random(),
                'tipe'              => $tipe[array_rand($tipe)],
                'deadline'          => now()->addDays(7),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}