<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranTableSeeder extends Seeder
{
    public function run(): void
    {
        $mapelDasar = [
            ['kode' => 'MTK',   'nama' => 'Matematika',              'jenis' => 'wajib', 'is_mulok' => false],
            ['kode' => 'BIN',   'nama' => 'Bahasa Indonesia',        'jenis' => 'wajib', 'is_mulok' => false],
            ['kode' => 'BIG',   'nama' => 'Bahasa Inggris',          'jenis' => 'wajib', 'is_mulok' => true],
            ['kode' => 'IPA',   'nama' => 'IPA',                     'jenis' => 'wajib', 'is_mulok' => false],
            ['kode' => 'IPS',   'nama' => 'IPS',                     'jenis' => 'wajib', 'is_mulok' => false],
            ['kode' => 'PAI',   'nama' => 'Pendidikan Agama Islam',  'jenis' => 'wajib', 'is_mulok' => true],
            ['kode' => 'PJOK',  'nama' => 'PJOK',                    'jenis' => 'wajib', 'is_mulok' => true],
            ['kode' => 'SBK',   'nama' => 'Seni Budaya',             'jenis' => 'wajib', 'is_mulok' => true],
            ['kode' => 'PPKn',  'nama' => 'PPKn',                    'jenis' => 'wajib', 'is_mulok' => false],
            ['kode' => 'MULOK', 'nama' => 'Bahasa Daerah',           'jenis' => 'mulok', 'is_mulok' => true],
        ];

        $guruIds = DB::table('guru')->pluck('id')->toArray();

        if (empty($guruIds)) {
            $this->command->warn('Data Guru masih kosong! Lewati seeding Mata Pelajaran.');
            return;
        }

        for ($tingkat = 1; $tingkat <= 6; $tingkat++) {
            foreach ($mapelDasar as $mapel) {
                $kodeMapel = $mapel['kode'] . '-' . $tingkat;

                DB::table('mata_pelajaran')->updateOrInsert(
                    ['kode' => $kodeMapel],
                    [
                        'nama'       => $mapel['nama'] . ' Kelas ' . $tingkat,
                        'tingkat'    => $tingkat,
                        'kkm'        => 70,
                        'guru_id'    => $guruIds[array_rand($guruIds)],
                        'jenis'      => $mapel['jenis'],
                        'is_mulok'   => $mapel['is_mulok'], // ← tambahan
                        'status'     => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}