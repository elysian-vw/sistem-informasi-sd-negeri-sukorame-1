<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            SekolahSeeder::class,
            PageContentSeeder::class,
            KelasTableSeeder::class,
            GuruTableSeeder::class,
            SiswaTableSeeder::class,
            WaliMuridTableSeeder::class,
            MataPelajaranTableSeeder::class,
            NilaiTableSeeder::class,
            AbsensiTableSeeder::class,
            PengumumanTableSeeder::class,
            MateriTableSeeder::class,
            TugasTableSeeder::class,
            PengumpulanTugasTableSeeder::class,
            ForumTableSeeder::class,
            KegiatanTableSeeder::class,
            JadwalPelajaranTableSeeder::class,
            GaleriTableSeeder::class,
            BeritaTableSeeder::class,
            PesanTableSeeder::class,
            IzinSiswaTableSeeder::class,
            PrestasiTableSeeder::class,
            EkskulTableSeeder::class,
            SaranaTableSeeder::class,
            RekapTableSeeder::class,
            PengajuanLayananTableSeeder::class,
            NotifikasiTableSeeder::class,
            KomiteTableSeeder::class,
            RaportTableSeeder::class,
        ]);
    }
}