<?php

namespace App\Exports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NilaiExport implements FromCollection, WithHeadings
{
    protected $mapel_id;
    
    public function __construct($mapel_id) { 
        $this->mapel_id = $mapel_id; 
    }

    public function collection()
    {
        return Nilai::where('mata_pelajaran_id', $this->mapel_id)
            ->with('siswa:id,nama_lengkap')
            ->get(['siswa_id', 'nilai_tugas', 'nilai_uts', 'nilai_uas']);
    }

    public function headings(): array {
        return ['siswa_id', 'tugas', 'uts', 'uas'];
    }
}