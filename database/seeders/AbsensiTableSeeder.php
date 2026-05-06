<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsensiTableSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = DB::table('siswa')->get();
        $status = ['hadir', 'sakit', 'izin', 'alpha'];

        foreach ($siswa as $s) {
            for ($i = 0; $i < 30; $i++) {
                $dt = now()->subDays($i); 
                $tanggal = $dt->toDateString();

                DB::table('absensi')->insert([
                    'siswa_id'   => $s->id,
                    'kelas_id'   => $s->kelas_id,
                    'tanggal'    => $tanggal,
                    'status'     => $status[array_rand($status)],
                    'keterangan' => null,
                    'created_at' => $dt, 
                    'updated_at' => $dt,
                ]);
            }
        }
    }
}