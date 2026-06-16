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
            ['nama' => 'Pramuka', 'hari' => 'Jumat', 'waktu_mulai' => '14:00', 'waktu_selesai' => '16:00', 'deskripsi' => 'Membangun karakter kepemimpinan, disiplin, dan cinta alam melalui kegiatan kepramukaan.'],
            ['nama' => 'Seni Tari', 'hari' => 'Selasa', 'waktu_mulai' => '14:00', 'waktu_selesai' => '15:30', 'deskripsi' => 'Melestarikan seni tari tradisional Jawa dan mengembangkan ekspresi seni peserta didik.'],
            ['nama' => 'Futsal', 'hari' => 'Rabu', 'waktu_mulai' => '14:00', 'waktu_selesai' => '15:30', 'deskripsi' => 'Melatih teknik dasar futsal, kerja tim, sportivitas, dan semangat juang.'],
            ['nama' => 'Paduan Suara', 'hari' => 'Kamis', 'waktu_mulai' => '14:00', 'waktu_selesai' => '15:30', 'deskripsi' => 'Melatih vokal, pernapasan, dan harmoni dalam paduan suara untuk memperkuat rasa kebangsaan.'],
            ['nama' => 'Seni Lukis', 'hari' => 'Senin', 'waktu_mulai' => '14:00', 'waktu_selesai' => '15:30', 'deskripsi' => 'Mengembangkan kreativitas dan ekspresi artistik melalui berbagai media lukis dan gambar.'],
            ['nama' => 'Olimpiade Sains', 'hari' => 'Sabtu', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:00', 'deskripsi' => 'Mempersiapkan siswa untuk kompetisi OSN/O2SN di bidang IPA, Matematika, dan IPS.'],
            ['nama' => 'Kaligrafi', 'hari' => 'Rabu', 'waktu_mulai' => '14:00', 'waktu_selesai' => '15:30', 'deskripsi' => 'Mempelajari seni menulis indah Arab dan Latin untuk melestarikan budaya islami.'],
            ['nama' => 'Bulu Tangkis', 'hari' => 'Selasa', 'waktu_mulai' => '14:00', 'waktu_selesai' => '15:30', 'deskripsi' => 'Melatih teknik dasar bulu tangkis dan membentuk atlet muda berprestasi.'],
            ['nama' => 'Robotika', 'hari' => 'Sabtu', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'deskripsi' => 'Memperkenalkan teknologi robotika dasar dan pemrograman untuk mempersiapkan siswa di era digital.'],
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