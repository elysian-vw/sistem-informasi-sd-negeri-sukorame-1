<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaTableSeeder extends Seeder
{
    public function run(): void
    {
        $siswaUsers = DB::table('users')->where('role', 'siswa')->get();
        $kelasList  = DB::table('kelas')->orderBy('tingkat')->get();
        $wali       = DB::table('users')->where('role', 'wali_murid')->pluck('id')->toArray();

        if ($kelasList->isEmpty() || count($wali) === 0) {
            $this->command->warn('Data Kelas atau Wali Murid masih kosong! Lewati seeding Siswa.');
            return;
        }

        // Distribusi 5 siswa per kelas secara berurutan
        // 6 kelas × 5 siswa = 30 siswa, cocok dengan UsersTableSeeder
        $siswaPerKelas = 5;

        foreach ($siswaUsers as $idx => $siswa) {
            $nisn = '00' . str_pad($siswa->id, 8, '0', STR_PAD_LEFT);

            // Tentukan kelas berdasarkan urutan: siswa 1-5 → kelas 1, 6-10 → kelas 2, dst
            $kelasIndex = (int) floor($idx / $siswaPerKelas);
            $kelas      = $kelasList[$kelasIndex] ?? $kelasList->last();

            // Distribusi wali murid merata
            $waliId = $wali[$idx % count($wali)];

            DB::table('siswa')->updateOrInsert(
                ['nisn' => $nisn],
                [
                    'user_id'       => $siswa->id,
                    'nama_lengkap'  => $siswa->name,
                    'jenis_kelamin' => $idx % 2 == 0 ? 'L' : 'P',
                    'tanggal_lahir' => '2015-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                    'tempat_lahir'  => 'Jakarta',
                    'alamat'        => 'Jl. Contoh No. ' . ($idx + 1),
                    'kelas_id'      => $kelas->id,
                    'wali_murid_id' => $waliId,
                    'foto'          => null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            );
        }

        // Tampilkan ringkasan distribusi
        foreach ($kelasList as $k) {
            $jumlah = DB::table('siswa')->where('kelas_id', $k->id)->count();
            $this->command->info("Kelas {$k->nama_kelas} (tingkat {$k->tingkat}): {$jumlah} siswa");
        }
    }
}