<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TugasTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil mapel beserta relasi guru dan tingkat
        $mapelList = DB::table('mata_pelajaran')
            ->whereNotNull('guru_id')
            ->get();

        if ($mapelList->isEmpty()) {
            $this->command->warn('Data mata pelajaran kosong! Lewati seeding Tugas.');
            return;
        }

        $status = ['aktif', 'draft'];
        $data   = [];

        foreach ($mapelList as $mapel) {
            // Kelas konsisten dengan tingkat mapel
            $kelas = DB::table('kelas')->where('tingkat', $mapel->tingkat)->first();
            if (!$kelas) continue;

            $data[] = [
                'judul'             => 'Tugas ' . $mapel->nama,
                'deskripsi'         => 'Kerjakan soal-soal latihan ' . $mapel->nama,
                'file'              => null,
                'mata_pelajaran_id' => $mapel->id,
                'kelas_id'          => $kelas->id,      // konsisten dengan tingkat mapel
                'guru_id'           => $mapel->guru_id, // konsisten dengan mapel
                'deadline'          => now()->addDays(rand(7, 21)),
                'status'            => $status[array_rand($status)],
                'tipe'              => 'upload',
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('tugas')->insert($chunk);
        }

        $this->command->info('Tugas di-seed: ' . count($data) . ' data.');
    }
}