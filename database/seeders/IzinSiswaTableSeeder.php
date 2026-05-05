<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IzinSiswaTableSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = DB::table('siswa')->pluck('id');
        $wali  = DB::table('wali_murid')->pluck('id');
        $admin = DB::table('users')->where('role', 'admin')->value('id');
        $jenis = ['sakit', 'izin', 'dispensasi'];
        $status = ['menunggu', 'disetujui', 'ditolak'];

        for ($i = 1; $i <= 15; $i++) {
            DB::table('izin_siswa')->insert([
                'siswa_id'       => $siswa->random(),
                'wali_murid_id'  => $wali->random(),
                'tanggal_mulai'  => now()->addDays(rand(1,5))->toDateString(),
                'tanggal_selesai'=> now()->addDays(rand(6,10))->toDateString(),
                'jenis'          => $jenis[array_rand($jenis)],
                'keterangan'     => 'Keterangan izin',
                'lampiran'       => null,
                'status'         => $status[array_rand($status)],
                'catatan_guru'   => null,
                'diproses_oleh'  => $admin,
                'diproses_at'    => now(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}