<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EkskulTableSeeder extends Seeder
{
    public function run(): void
    {
        $guru = DB::table('guru')->pluck('id');
        $ekskul = [
            ['nama' => 'Pramuka', 'hari' => 'Sabtu', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:00'],
            ['nama' => 'Futsal', 'hari' => 'Jumat', 'waktu_mulai' => '14:00', 'waktu_selesai' => '16:00'],
            ['nama' => 'Musik', 'hari' => 'Rabu', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:00'],
        ];

        foreach ($ekskul as $e) {
            $ekskulId = DB::table('ekskul')->insertGetId([
                'nama'         => $e['nama'],
                'deskripsi'    => 'Deskripsi ' . $e['nama'],
                'hari'         => $e['hari'],
                'waktu_mulai'  => $e['waktu_mulai'],
                'waktu_selesai'=> $e['waktu_selesai'],
                'tempat'       => 'Lapangan',
                'pembina_id'   => $guru->random(),
                'kuota'        => 30,
                'foto'         => null,
                'is_aktif'     => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // Anggota ekskul (beberapa siswa)
            $siswa = DB::table('siswa')->inRandomOrder()->limit(15)->pluck('id');
            foreach ($siswa as $idSiswa) {
                DB::table('ekskul_siswa')->insert([
                    'ekskul_id'    => $ekskulId,
                    'siswa_id'     => $idSiswa,
                    'tahun_ajaran' => '2024/2025',
                    'status'       => 'aktif',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }
    }
}