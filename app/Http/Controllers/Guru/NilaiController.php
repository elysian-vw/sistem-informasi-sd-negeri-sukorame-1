<?php

namespace App\Http\Controllers\Guru;

use App\Exports\NilaiExport;
use App\Imports\NilaiImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\{Nilai, Kelas, MataPelajaran, Siswa};
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    // Halaman awal: Pilih Mata Pelajaran yang ingin dinilai
    public function index()
    {
        $guru = auth()->user()->guru;
        
        if (!$guru->kelas_id) {
            return back()->with('error', 'Anda belum ditugaskan ke kelas manapun.');
        }

        $kelas = Kelas::find($guru->kelas_id);
        $mapel = MataPelajaran::where('guru_id', $guru->id)
            ->where('kelas_id', $guru->kelas_id)
            ->get();

        return view('guru.nilai.index', compact('kelas', 'mapel'));
    }

    // Halaman form input nilai untuk seluruh siswa di kelas
    public function input($mapel_id)
    {
        $guru = auth()->user()->guru;
        $mapel = MataPelajaran::where('id', $mapel_id)->where('guru_id', $guru->id)->firstOrFail();
        $kelas = Kelas::find($guru->kelas_id);
        
        // Ambil semua siswa di kelas ini
        $siswaKelas = Siswa::where('kelas_id', $kelas->id)->orderBy('nama_lengkap')->get();

        // Ambil nilai yang sudah ada (jika pernah diinput sebelumnya)
        // Kita asumsikan semester ini
        $semesterAktif = 'Ganjil'; // Nanti bisa diganti dinamis dari database/pengaturan
        $tahunAjaran = '2024/2025';

        $nilaiExisting = Nilai::where('mata_pelajaran_id', $mapel->id)
            ->where('semester', $semesterAktif)
            ->where('tahun_ajaran', $tahunAjaran)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.nilai.input', compact('mapel', 'kelas', 'siswaKelas', 'nilaiExisting', 'semesterAktif', 'tahunAjaran'));
    }

    // Proses simpan semua nilai
    public function store(Request $request, $mapel_id)
    {
        $guru = auth()->user()->guru;
        $mapel = MataPelajaran::where('id', $mapel_id)->where('guru_id', $guru->id)->firstOrFail();

        $request->validate([
            'semester'     => 'required|string',
            'tahun_ajaran' => 'required|string',
            'nilai_tugas'  => 'array',
            'nilai_uts'    => 'array',
            'nilai_uas'    => 'array',
        ]);

        foreach ($request->nilai_tugas as $siswaId => $valTugas) {
            $tugas = $valTugas ?? 0;
            $uts   = $request->nilai_uts[$siswaId] ?? 0;
            $uas   = $request->nilai_uas[$siswaId] ?? 0;
            
            // Hitung nilai akhir otomatis (Contoh: Rata-rata)
            // Bisa diganti bobotnya misal: (Tugas 30%) + (UTS 30%) + (UAS 40%)
            $nilaiAkhir = ($tugas + $uts + $uas) / 3;

            Nilai::updateOrCreate(
                [
                    'siswa_id'          => $siswaId,
                    'mata_pelajaran_id' => $mapel->id,
                    'semester'          => $request->semester,
                    'tahun_ajaran'      => $request->tahun_ajaran,
                ],
                [
                    'nilai_tugas' => $tugas,
                    'nilai_uts'   => $uts,
                    'nilai_uas'   => $uas,
                    'nilai_akhir' => round($nilaiAkhir, 2), // Pembulatan 2 desimal
                ]
            );
        }

        return redirect()->route('guru.nilai.index')->with('success', 'Nilai Rapor berhasil disimpan!');
    }

    // Fungsi untuk Download Template/Data Nilai
    public function export($mapel_id) 
    {
        return Excel::download(new NilaiExport($mapel_id), 'Data_Nilai_Siswa.xlsx');
    }

    // Fungsi untuk Upload/Impor Data Nilai
    public function import(Request $request, $mapel_id) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // Sesuaikan semester dan tahun ajaran dengan data yang aktif
        Excel::import(new NilaiImport($mapel_id, 'Ganjil', '2024/2025'), $request->file('file'));

        return back()->with('success', 'Data nilai berhasil diimpor!');
    }
}