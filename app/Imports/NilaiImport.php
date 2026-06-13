<?php

namespace App\Imports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiImport implements ToModel, WithHeadingRow
{
    protected $mapel_id, $semester, $tahun;

    public function __construct($mapel_id, $semester, $tahun) {
        $this->mapel_id = $mapel_id; 
        $this->semester = $semester; 
        $this->tahun = $tahun;
    }

    public function model(array $row)
    {
        $tugas = $row['tugas'] ?? 0;
        $uts   = $row['uts'] ?? 0;
        $uas   = $row['uas'] ?? 0;
        
        return Nilai::updateOrCreate(
            [
                'siswa_id'          => $row['siswa_id'], 
                'mata_pelajaran_id' => $this->mapel_id, 
                'semester'          => $this->semester, 
                'tahun_ajaran'      => $this->tahun
            ],
            [
                'nilai_tugas' => $tugas, 
                'nilai_uts'   => $uts, 
                'nilai_uas'   => $uas, 
                'nilai_akhir' => ($tugas + $uts + $uas) / 3
            ]
        );
    }
}