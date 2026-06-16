<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sekolah')->updateOrInsert(
            ['id' => 1],
            [
                'npsn' => '20534567',
                'nama_sekolah' => 'SD Negeri Sukorame 1',
                'alamat' => 'Jl. Veteran No. 123',
                'kelurahan' => 'Sukorame',
                'kecamatan' => 'Mojoroto',
                'kota' => 'Kediri',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '64114',
                'status_sekolah' => 'Negeri',
                'jenjang' => 'SD',
                'tahun_berdiri' => 1970,
                'telepon' => '0354-123456',
                'email' => 'sdnsukorame1@example.com',
                'website' => 'https://sdnsukorame1.sch.id',
                'nama_kepala_sekolah' => 'Budi Santoso, S.Pd., M.Pd.',
                'nip_kepala_sekolah' => '197001011995011001',
                'logo' => null,
                'nama_singkat' => 'SDN Sukorame 1',
                'slogan' => 'Cerdas, Berkarakter, dan Berbudaya',
                'akreditasi' => 'A',
                'tahun_akreditasi' => 2022,
                'nomor_sk_akreditasi' => '123/BAN-SM/2022',
                'nilai_akreditasi' => 95.00,
                'sambutan_kepsek' => 'Selamat datang di website resmi SD Negeri Sukorame 1 Kota Kediri. Kami berkomitmen untuk memberikan layanan pendidikan terbaik bagi putra-putri Anda.',
                'facebook' => 'https://facebook.com/sdnsukorame1',
                'instagram' => 'https://instagram.com/sdnsukorame1',
                'youtube' => 'https://youtube.com/sdnsukorame1',
                'tahun_ajaran_aktif' => '2025/2026',
                'semester_aktif' => 'Ganjil',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
