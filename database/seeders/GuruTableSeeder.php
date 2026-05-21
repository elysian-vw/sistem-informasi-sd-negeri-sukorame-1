<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruTableSeeder extends Seeder
{
    public function run(): void
    {
        $guruUsers = DB::table('users')->where('role', 'guru')->get();

        // Sesuai urutan 5 guru di UsersTableSeeder:
        // Budi, Siti, Agus, Dewi, Eko
        // Mapel umum → butuh kelas_id
        // Mapel mulok → kelas_id null, bebas lintas kelas
        $petaGuru = [
            // [mata_pelajaran, is_mulok, kelas_tingkat_atau_null]
            ['Matematika',             false, 1], // Budi → mapel umum, kelas tingkat 1
            ['Bahasa Indonesia',       false, 2], // Siti → mapel umum, kelas tingkat 2
            ['Bahasa Inggris',         true,  null], // Agus → mulok, bebas kelas
            ['IPA',                    false, 4], // Dewi → mapel umum, kelas tingkat 4
            ['Pendidikan Agama Islam', true,  null], // Eko  → mulok, bebas kelas
        ];

        foreach ($guruUsers as $idx => $guruUser) {
            if (!isset($petaGuru[$idx])) continue;

            [$namaMapel, $isMulok, $tingkat] = $petaGuru[$idx];

            // Cari kelas_id berdasarkan tingkat (null jika guru mulok)
            $kelasId = null;
            if (!$isMulok && $tingkat !== null) {
                $kelas = DB::table('kelas')->where('tingkat', $tingkat)->first();
                $kelasId = $kelas?->id ?? null;
            }

            // Hindari duplikat jika seeder dijalankan ulang
            DB::table('guru')->updateOrInsert(
                ['user_id' => $guruUser->id],
                [
                    'nip'            => '1965' . str_pad($idx + 1, 6, '0', STR_PAD_LEFT),
                    'mata_pelajaran' => $namaMapel,
                    'kelas_id'       => $kelasId,
                    'status'         => 'aktif',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );
        }
    }
}