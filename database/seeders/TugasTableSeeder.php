<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasTableSeeder extends Seeder
{
    public function run(): void
    {
        $mapel = DB::table('mata_pelajaran')->pluck('id');
        $kelas = DB::table('kelas')->pluck('id');
        $guru  = DB::table('guru')->pluck('id');

        for ($i = 1; $i <= 20; $i++) {
            DB::table('tugas')->insert([
                'judul'             => "Tugas $i",
                'deskripsi'         => "Kerjakan soal-soal latihan",
                'file'              => null,
                'mata_pelajaran_id' => $mapel->random(),
                'kelas_id'          => $kelas->random(),
                'guru_id'           => $guru->random(),
                'deadline'          => now()->addDays(rand(1, 14)),
                'status'            => 'aktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}