<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaranaTableSeeder extends Seeder
{
    public function run(): void
    {
        $sarana = [
            ['nama' => 'Ruang Kelas I', 'deskripsi' => 'Ruang kelas untuk siswa kelas I dengan kapasitas 32 siswa', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 1],
            ['nama' => 'Ruang Kelas II', 'deskripsi' => 'Ruang kelas untuk siswa kelas II dengan kapasitas 30 siswa', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 2],
            ['nama' => 'Ruang Kelas III', 'deskripsi' => 'Ruang kelas untuk siswa kelas III dengan kapasitas 34 siswa', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 3],
            ['nama' => 'Ruang Kelas IV', 'deskripsi' => 'Ruang kelas untuk siswa kelas IV dengan kapasitas 33 siswa', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 4],
            ['nama' => 'Ruang Kelas V', 'deskripsi' => 'Ruang kelas untuk siswa kelas V dengan kapasitas 32 siswa', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 5],
            ['nama' => 'Ruang Kelas VI', 'deskripsi' => 'Ruang kelas untuk siswa kelas VI dengan kapasitas 31 siswa', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 6],
            ['nama' => 'Ruang Kepala Sekolah', 'deskripsi' => 'Ruang kantor kepala sekolah untuk administrasi dan meeting', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 7],
            ['nama' => 'Ruang Guru', 'deskripsi' => 'Ruang guru untuk istirahat dan persiapan pembelajaran', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 8],
            ['nama' => 'Perpustakaan', 'deskripsi' => 'Perpustakaan sekolah dengan koleksi buku dan media pembelajaran', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 9],
            ['nama' => 'Lab Komputer', 'deskripsi' => 'Laboratorium komputer dengan 20 unit komputer untuk pembelajaran TIK', 'jumlah' => 20, 'kondisi' => 'Baik', 'urutan' => 10],
            ['nama' => 'Ruang UKS', 'deskripsi' => 'Unit Kesehatan Sekolah untuk perawatan kesehatan siswa', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 11],
            ['nama' => 'Mushola', 'deskripsi' => 'Tempat ibadah untuk siswa dan guru beragama Islam', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 12],
            ['nama' => 'Gudang', 'deskripsi' => 'Gudang untuk penyimpanan barang dan peralatan sekolah', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 13],
            ['nama' => 'Kantin Sekolah', 'deskripsi' => 'Kantin untuk menjual makanan dan minuman bagi siswa dan guru', 'jumlah' => 1, 'kondisi' => 'Baik', 'urutan' => 14],
            ['nama' => 'Toilet Siswa', 'deskripsi' => 'Fasilitas toilet untuk siswa dengan 6 bilik terpisah gender', 'jumlah' => 6, 'kondisi' => 'Baik', 'urutan' => 15],
        ];

        foreach ($sarana as $s) {
            DB::table('sarana')->insert([
                'nama'       => $s['nama'],
                'deskripsi'  => $s['deskripsi'],
                'foto'       => null,
                'jumlah'     => $s['jumlah'],
                'kondisi'    => $s['kondisi'],
                'urutan'     => $s['urutan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
