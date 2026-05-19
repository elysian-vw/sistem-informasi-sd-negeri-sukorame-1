<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranTableSeeder extends Seeder
{
    public function run(): void
    {
        $mapelDasar = [
            ['kode' => 'MTK', 'nama' => 'Matematika', 'jenis' => 'wajib'],
            ['kode' => 'BIN', 'nama' => 'Bahasa Indonesia', 'jenis' => 'wajib'],
            ['kode' => 'BIG', 'nama' => 'Bahasa Inggris', 'jenis' => 'wajib'],
            ['kode' => 'IPA', 'nama' => 'IPA', 'jenis' => 'wajib'],
            ['kode' => 'IPS', 'nama' => 'IPS', 'jenis' => 'wajib'],
            ['kode' => 'PAI', 'nama' => 'Pendidikan Agama Islam', 'jenis' => 'wajib'],
            ['kode' => 'PJOK', 'nama' => 'PJOK', 'jenis' => 'wajib'],
            ['kode' => 'SBK', 'nama' => 'Seni Budaya', 'jenis' => 'wajib'],
            ['kode' => 'PPKn', 'nama' => 'PPKn', 'jenis' => 'wajib'],
            ['kode' => 'MULOK', 'nama' => 'Bahasa Daerah', 'jenis' => 'mulok'],
        ];

        $guruIds = DB::table('guru')->pluck('id')->toArray();

        // Proteksi jika tabel guru masih kosong
        if (empty($guruIds)) {
            $this->command->warn('Data Guru masih kosong! Lewati seeding Mata Pelajaran.');
            return;
        }

        for ($tingkat = 1; $tingkat <= 6; $tingkat++) {
            foreach ($mapelDasar as $mapel) {
                $kodeMapel = $mapel['kode'] . '-' . $tingkat;

                DB::table('mata_pelajaran')->updateOrInsert(
                    ['kode' => $kodeMapel], // Kondisi pencarian berdasarkan kode mata pelajaran
                    [
                        'nama'          => $mapel['nama'] . ' Kelas ' . $tingkat,
                        'tingkat'       => $tingkat,
                        'kkm'           => 70,
                        'guru_id'       => $guruIds[array_rand($guruIds)],
                        'jenis'         => $mapel['jenis'],
                        'status'        => 'aktif',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]
                );
            }
        }
    }
}