<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GaleriTableSeeder extends Seeder
{
    public function run(): void
    {
        $userId = DB::table('users')->where('role', 'admin')->value('id');

        $galleryItems = [
            [
                'judul'        => 'Upacara Bendera Semester Ganjaran',
                'deskripsi'    => 'Foto suasana upacara bendera rutin di halaman sekolah dengan seluruh siswa dan guru.',
                'tipe'         => 'foto',
                'file_path'    => null,
                'url_video'    => null,
                'thumbnail'    => null,
                'kategori'     => 'kegiatan',
                'tanggal'      => now()->subDays(18),
                'is_published' => true,
                'urutan'       => 1,
            ],
            [
                'judul'        => 'Ruang Kelas Terang dan Nyaman',
                'deskripsi'    => 'Dokumentasi interior ruang kelas yang mendukung kegiatan belajar mengajar.',
                'tipe'         => 'foto',
                'file_path'    => null,
                'url_video'    => null,
                'thumbnail'    => null,
                'kategori'     => 'profil',
                'tanggal'      => now()->subDays(15),
                'is_published' => true,
                'urutan'       => 2,
            ],
            [
                'judul'        => 'Kegiatan Literasi dan Baca Tulis',
                'deskripsi'    => 'Siswa-siswi sedang berpartisipasi dalam kegiatan literasi bersama guru pembimbing.',
                'tipe'         => 'foto',
                'file_path'    => null,
                'url_video'    => null,
                'thumbnail'    => null,
                'kategori'     => 'pembelajaran',
                'tanggal'      => now()->subDays(12),
                'is_published' => true,
                'urutan'       => 3,
            ],
            [
                'judul'        => 'Seni dan Kreativitas Ekstrakurikuler',
                'deskripsi'    => 'Kegiatan ekstrakurikuler seni yang memupuk kreativitas siswa di sekolah.',
                'tipe'         => 'foto',
                'file_path'    => null,
                'url_video'    => null,
                'thumbnail'    => null,
                'kategori'     => 'ekstra',
                'tanggal'      => now()->subDays(9),
                'is_published' => true,
                'urutan'       => 4,
            ],
            [
                'judul'        => 'Perpustakaan Sekolah Ramah Anak',
                'deskripsi'    => 'Suasana perpustakaan sekolah yang rapi dengan ruang baca anak-anak.',
                'tipe'         => 'foto',
                'file_path'    => null,
                'url_video'    => null,
                'thumbnail'    => null,
                'kategori'     => 'profil',
                'tanggal'      => now()->subDays(6),
                'is_published' => true,
                'urutan'       => 5,
            ],
            [
                'judul'        => 'Pembelajaran Sains di Laboratorium',
                'deskripsi'    => 'Praktik sains sederhana bersama guru yang menumbuhkan rasa ingin tahu siswa.',
                'tipe'         => 'foto',
                'file_path'    => null,
                'url_video'    => null,
                'thumbnail'    => null,
                'kategori'     => 'pembelajaran',
                'tanggal'      => now()->subDays(4),
                'is_published' => true,
                'urutan'       => 6,
            ],
            [
                'judul'        => 'Potret Ekosistem Sekolah Hijau',
                'deskripsi'    => 'Kebun sekolah dan area hijau yang menjadi lingkungan belajar siswa.',
                'tipe'         => 'foto',
                'file_path'    => null,
                'url_video'    => null,
                'thumbnail'    => null,
                'kategori'     => 'profil',
                'tanggal'      => now()->subDays(2),
                'is_published' => true,
                'urutan'       => 7,
            ],
            [
                'judul'        => 'Tur Video Keunggulan Fasilitas Sekolah',
                'deskripsi'    => 'Video singkat memperlihatkan fasilitas ruang kelas, perpustakaan, dan laboratorium.',
                'tipe'         => 'video',
                'file_path'    => null,
                'url_video'    => 'https://www.youtube.com/watch?v=5qap5aO4i9A',
                'thumbnail'    => null,
                'kategori'     => 'profil',
                'tanggal'      => now()->subDays(14),
                'is_published' => true,
                'urutan'       => 8,
            ],
            [
                'judul'        => 'Dokumentasi Kegiatan Upacara dan Penghargaan',
                'deskripsi'    => 'Video kegiatan upacara dan pembagian penghargaan siswa berprestasi.',
                'tipe'         => 'video',
                'file_path'    => null,
                'url_video'    => 'https://www.youtube.com/watch?v=JfVOs4VSpmA',
                'thumbnail'    => null,
                'kategori'     => 'kegiatan',
                'tanggal'      => now()->subDays(10),
                'is_published' => true,
                'urutan'       => 9,
            ],
            [
                'judul'        => 'Video Kunjungan Kelas dan Kegiatan Pembelajaran',
                'deskripsi'    => 'Rekaman suasana belajar di kelas, kegiatan kelompok, dan metode guru.',
                'tipe'         => 'video',
                'file_path'    => null,
                'url_video'    => 'https://www.youtube.com/watch?v=2vjPBrBU-TM',
                'thumbnail'    => null,
                'kategori'     => 'pembelajaran',
                'tanggal'      => now()->subDays(5),
                'is_published' => true,
                'urutan'       => 10,
            ],
        ];

        foreach ($galleryItems as $index => $item) {
            DB::table('galeri')->insert(array_merge($item, [
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}