<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestasiTableSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = DB::table('siswa')->pluck('id');
        $prestasi = [
            ['nama_lomba' => 'Olimpiade Matematika Tingkat Kota Kediri', 'penyelenggara' => 'Dinas Pendidikan Kota Kediri', 'tingkat' => 'kota', 'juara' => '1', 'tanggal' => '2024-05-15', 'bidang' => 'Akademik'],
            ['nama_lomba' => 'Lomba Pidato Bahasa Indonesia', 'penyelenggara' => 'Dinas Pendidikan', 'tingkat' => 'kecamatan', 'juara' => '1', 'tanggal' => '2024-04-20', 'bidang' => 'Akademik'],
            ['nama_lomba' => 'Festival Seni Tari Jawa Timur', 'penyelenggara' => 'Provinsi Jawa Timur', 'tingkat' => 'provinsi', 'juara' => '2', 'tanggal' => '2023-08-10', 'bidang' => 'Seni'],
            ['nama_lomba' => 'Turnamen Futsal Antara SD Se-Kota Kediri', 'penyelenggara' => 'Dinas Olahraga Kota Kediri', 'tingkat' => 'kota', 'juara' => '1', 'tanggal' => '2023-11-05', 'bidang' => 'Olahraga'],
            ['nama_lomba' => 'Lomba Cerdas Cermat IPA', 'penyelenggara' => 'Provinsi Jawa Timur', 'tingkat' => 'provinsi', 'juara' => '3', 'tanggal' => '2023-09-22', 'bidang' => 'Akademik'],
            ['nama_lomba' => 'Lomba Melukis Tingkat SD', 'penyelenggara' => 'Dinas Pendidikan Kota Kediri', 'tingkat' => 'kota', 'juara' => '2', 'tanggal' => '2024-03-18', 'bidang' => 'Seni'],
            ['nama_lomba' => 'FLS2N Paduan Suara', 'penyelenggara' => 'Dinas Pendidikan Kota Kediri', 'tingkat' => 'kota', 'juara' => '1', 'tanggal' => '2023-10-12', 'bidang' => 'Seni'],
            ['nama_lomba' => 'OSN IPA', 'penyelenggara' => 'Dinas Pendidikan Kota Kediri', 'tingkat' => 'kota', 'juara' => '1', 'tanggal' => '2023-07-28', 'bidang' => 'Akademik'],
            ['nama_lomba' => 'O2SN Bulu Tangkis', 'penyelenggara' => 'Dinas Pendidikan Kecamatan', 'tingkat' => 'kecamatan', 'juara' => '2', 'tanggal' => '2023-06-14', 'bidang' => 'Olahraga'],
            ['nama_lomba' => 'Lomba Robotik SD', 'penyelenggara' => 'Provinsi Jawa Timur', 'tingkat' => 'provinsi', 'juara' => '3', 'tanggal' => '2023-12-09', 'bidang' => 'Teknologi'],
        ];

        foreach ($prestasi as $p) {
            DB::table('prestasi')->insert([
                'siswa_id'     => $siswa->random(),
                'nama_lomba'   => $p['nama_lomba'],
                'penyelenggara'=> $p['penyelenggara'],
                'tingkat'      => $p['tingkat'],
                'juara'        => $p['juara'],
                'tanggal'      => $p['tanggal'],
                'bidang'       => $p['bidang'],
                'foto'         => null,
                'keterangan'   => 'Prestasi unggulan sekolah',
                'is_published' => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}