<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil mapel beserta guru_id dan kelas_id yang berelasi
        $mapelList = DB::table('mata_pelajaran')
            ->whereNotNull('guru_id')
            ->get();

        if ($mapelList->isEmpty()) {
            $this->command->warn('Data mata pelajaran kosong! Lewati seeding Materi.');
            return;
        }

        $kategori = ['materi', 'tugas', 'uts', 'uas'];

        // Format media: hanya file atau link (tidak ada video)
        $contohLink = [
            'https://drive.google.com/file/abc123',
            'https://drive.google.com/file/def456',
            'https://docs.google.com/presentation/xyz789',
        ];

        $data = [];

        foreach ($mapelList as $mapel) {
            // Cari kelas yang sesuai dengan tingkat mapel
            $kelas = DB::table('kelas')->where('tingkat', $mapel->tingkat)->first();
            if (!$kelas) continue;

            // Format file
            $data[] = [
                'judul'             => 'Materi ' . $mapel->nama,
                'deskripsi'         => 'Materi pembelajaran ' . $mapel->nama . ' untuk kelas ' . $mapel->tingkat,
                'mata_pelajaran_id' => $mapel->id,
                'guru_id'           => $mapel->guru_id, // konsisten dengan mapel
                'kelas_id'          => $kelas->id,      // konsisten dengan tingkat mapel
                'tipe'              => 'materi',
                'file'              => null,
                'link_video'        => $contohLink[array_rand($contohLink)],
                'created_at'        => now(),
                'updated_at'        => now(),
            ];

            // Format link
            $data[] = [
                'judul'             => 'Referensi ' . $mapel->nama,
                'deskripsi'         => 'Bahan referensi tambahan ' . $mapel->nama,
                'mata_pelajaran_id' => $mapel->id,
                'guru_id'           => $mapel->guru_id,
                'kelas_id'          => $kelas->id,
                'tipe'              => $kategori[array_rand($kategori)],
                'file'              => null,
                'link_video'        => $contohLink[array_rand($contohLink)],
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('materi')->insert($chunk);
        }

        $this->command->info('Materi di-seed: ' . count($data) . ' data.');
    }
}