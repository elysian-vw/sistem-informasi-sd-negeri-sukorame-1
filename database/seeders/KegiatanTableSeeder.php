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
            ['judul' => 'Upacara Bendera', 'tanggal_mulai' => '2025-04-17', 'waktu_mulai' => '07:30:00', 'waktu_selesai' => '08:30:00', 'tempat' => 'Halaman Sekolah', 'kategori' => 'upacara', 'sasaran' => 'semua'],
            ['judul' => 'Penilaian Tengah Semester', 'tanggal_mulai' => '2025-05-01', 'tanggal_selesai' => '2025-05-10', 'waktu_mulai' => '07:30:00', 'waktu_selesai' => '12:00:00', 'tempat' => 'Ruang Kelas', 'kategori' => 'penilaian', 'sasaran' => 'siswa'],
            ['judul' => 'Perpisahan Kelas 6', 'tanggal_mulai' => '2025-06-15', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '11:00:00', 'tempat' => 'Aula Sekolah', 'kategori' => 'perpisahan', 'sasaran' => 'siswa'],
            ['judul' => 'Masa Pengenalan Lingkungan Sekolah (MPLS)', 'tanggal_mulai' => '2025-07-14', 'tanggal_selesai' => '2025-07-16', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '11:30:00', 'tempat' => 'SDN Sukorame 1', 'kategori' => 'ekskul', 'sasaran' => 'siswa'],
            ['judul' => 'Penerimaan Peserta Didik Baru (PPDB)', 'tanggal_mulai' => '2025-07-01', 'tanggal_selesai' => '2025-07-10', 'waktu_mulai' => '08:00:00', 'waktu_selesai' => '14:00:00', 'tempat' => 'Online & SDN Sukorame 1', 'kategori' => 'ppdb', 'sasaran' => 'wali_murid'],
            ['judul' => 'Lomba Gerak Jalan Kemerdekaan', 'tanggal_mulai' => '2025-08-16', 'waktu_mulai' => '07:00:00', 'tempat' => 'Rute: SDN Sukorame 1 – Alun-Alun Kota Kediri', 'kategori' => 'ekskul', 'sasaran' => 'siswa'],
            ['judul' => 'Upacara HUT RI ke-80', 'tanggal_mulai' => '2025-08-17', 'waktu_mulai' => '07:00:00', 'tempat' => 'Halaman Upacara SDN Sukorame 1', 'kategori' => 'upacara', 'sasaran' => 'semua'],
        ];

        foreach ($kegiatan as $k) {
            DB::table('kegiatan')->insert(array_merge([
                'deskripsi'      => 'Deskripsi ' . $k['judul'],
                'waktu_mulai'    => $k['waktu_mulai'] ?? '07:30:00',
                'waktu_selesai'  => $k['waktu_selesai'] ?? '12:00:00',
                'tempat'         => $k['tempat'] ?? 'Lapangan',
            ], $k, [
                'is_published'   => true,
                'created_by'     => $userId,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]));
        }
    }
}