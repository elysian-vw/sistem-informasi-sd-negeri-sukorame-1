<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomiteTableSeeder extends Seeder
{
    public function run(): void
    {
        $komite = [
            ['nama' => 'Dr. H. Ahmad', 'jabatan' => 'Ketua', 'unsur' => 'Orang Tua Siswa'],
            ['nama' => 'Siti Maryam', 'jabatan' => 'Sekretaris', 'unsur' => 'Guru'],
            ['nama' => 'Bambang', 'jabatan' => 'Bendahara', 'unsur' => 'Masyarakat'],
        ];

        foreach ($komite as $idx => $k) {
            DB::table('komites')->insert([
                'nama'      => $k['nama'],
                'jabatan'   => $k['jabatan'],
                'unsur'     => $k['unsur'],
                'telepon'   => '0812' . rand(1000000, 9999999),
                'urutan'    => $idx + 1,
                'aktif'     => true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }
    }
}