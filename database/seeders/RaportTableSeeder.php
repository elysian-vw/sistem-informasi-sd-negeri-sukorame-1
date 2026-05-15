<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Raport, Siswa, Nilai, MataPelajaran};

class RaportTableSeeder extends Seeder
{
    public function run(): void
    {
        $semester    = 'Ganjil';
        $tahunAjaran = '2024/2025';

        $siswaList = Siswa::with('kelas')->get();
        $mapelList = MataPelajaran::all();

        foreach ($siswaList as $siswa) {
            // Buat raport
            $raport = Raport::updateOrCreate(
                [
                    'siswa_id'    => $siswa->id,
                    'semester'    => $semester,
                    'tahun_ajaran'=> $tahunAjaran,
                ],
                [
                    'catatan_wali_kelas' => 'Siswa menunjukkan perkembangan yang baik selama semester ini.',
                    'status'             => 'terbit',
                ]
            );

            // Buat nilai untuk setiap mapel
            foreach ($mapelList as $mapel) {
                $tugas = rand(70, 100);
                $uts   = rand(65, 100);
                $uas   = rand(65, 100);
                $akhir = round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);

                Nilai::updateOrCreate(
                    [
                        'siswa_id'          => $siswa->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'semester'          => $semester,
                        'tahun_ajaran'      => $tahunAjaran,
                    ],
                    [
                        'nilai_tugas' => $tugas,
                        'nilai_uts'   => $uts,
                        'nilai_uas'   => $uas,
                        'nilai_akhir' => $akhir,
                    ]
                );
            }
        }
    }
}