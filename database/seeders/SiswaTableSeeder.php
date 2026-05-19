<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaTableSeeder extends Seeder
{
    public function run(): void
    {
        $siswaUsers = DB::table('users')->where('role', 'siswa')->get();
        $kelas = DB::table('kelas')->pluck('id')->toArray();
        $wali = DB::table('users')->where('role', 'wali_murid')->pluck('id')->toArray();

        // Pastikan tabel kelas dan wali murid tidak kosong agar tidak error division by zero
        if (count($kelas) === 0 || count($wali) === 0) {
            $this->command->warn('Data Kelas atau Wali Murid masih kosong! Lewati seeding Siswa.');
            return;
        }

        foreach ($siswaUsers as $idx => $siswa) {
            $nisn = '00' . str_pad($siswa->id, 8, '0', STR_PAD_LEFT);

            DB::table('siswa')->updateOrInsert(
                ['nisn' => $nisn], // Kondisi pencarian berdasarkan kolom unique
                [
                    'user_id'         => $siswa->id,
                    'nama_lengkap'    => $siswa->name,
                    'jenis_kelamin'   => $idx % 2 == 0 ? 'L' : 'P',
                    'tanggal_lahir'   => '2015-' . rand(1, 12) . '-' . rand(1, 28),
                    'tempat_lahir'    => 'Jakarta',
                    'alamat'          => 'Jl. Contoh No. ' . ($idx + 1),
                    'kelas_id'        => $kelas[$idx % count($kelas)],
                    'wali_murid_id'   => $wali[$idx % count($wali)],
                    'foto'            => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
        }
    }
}