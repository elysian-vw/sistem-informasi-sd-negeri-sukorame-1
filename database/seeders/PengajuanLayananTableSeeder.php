<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanLayananTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->whereIn('role', ['siswa', 'wali_murid'])->pluck('id');
        $siswa = DB::table('siswa')->pluck('id');
        $jenis = ['mutasi_masuk', 'surat_keterangan', 'cetak_nisn', 'izin_penelitian', 'lainnya'];
        $status = ['menunggu', 'diproses', 'selesai', 'ditolak'];

        for ($i = 1; $i <= 20; $i++) {
            DB::table('pengajuan_layanan')->insert([
                'user_id'        => $users->random(),
                'siswa_id'       => $siswa->random(),
                'jenis'          => $jenis[array_rand($jenis)],
                'keperluan'      => 'Keperluan ' . $i,
                'keterangan'     => 'Keterangan layanan',
                'lampiran'       => null,
                'no_pengajuan'   => 'PNJ-' . Str::random(8),
                'status'         => $status[array_rand($status)],
                'catatan_admin'  => null,
                'file_hasil'     => null,
                'diproses_oleh'  => null,
                'selesai_at'     => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
