<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\{Raport, Siswa, Nilai, Absensi};
use Barryvdh\DomPDF\Facade\Pdf;

class RaportWaliController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $anakList = Siswa::where('wali_murid_id', $user->id)->with('kelas')->get();

        $selectedId = request('anak');
        $anakUtama  = $selectedId
            ? $anakList->firstWhere('id', $selectedId)
            : $anakList->first();

        $raportList = collect();
        if ($anakUtama) {
            $raportList = Raport::where('siswa_id', $anakUtama->id)
                ->where('status', 'terbit')
                ->orderByDesc('tahun_ajaran')
                ->orderByDesc('semester')
                ->get();
        }

        return view('wali.raport.index', compact('anakList', 'anakUtama', 'raportList'));
    }

    public function show(Raport $raport)
    {
        // Pastikan raport milik anak wali ini
        $user     = auth()->user();
        $anakList = Siswa::where('wali_murid_id', $user->id)->pluck('id');

        abort_unless(
            $anakList->contains($raport->siswa_id) && $raport->status === 'terbit',
            403
        );

        $raport->load('siswa.kelas');

        // Filter nilai
        $nilai = Nilai::where('siswa_id', $raport->siswa_id)
            ->where('semester', $raport->semester)
            ->where('tahun_ajaran', $raport->tahun_ajaran)
            ->with([
                'mataPelajaran' => function ($query) use ($raport) {
                    $query->where('tingkat', $raport->siswa->kelas->tingkat);
                }
            ])
            ->get()
            ->filter(function ($item) {
                return $item->mataPelajaran !== null;
            })
            ->values();

        // Hitung Absensi tanpa filter semester/tahun_ajaran (mengatasi error SQL)
        $absensi = [
            'sakit' => Absensi::where('siswa_id', $raport->siswa_id)->where('status', 'sakit')->count(),
            'izin'  => Absensi::where('siswa_id', $raport->siswa_id)->where('status', 'izin')->count(),
            'alpha' => Absensi::where('siswa_id', $raport->siswa_id)->where('status', 'alpha')->count(),
        ];

        return view('wali.raport.show', compact('raport', 'nilai', 'absensi'));
    }

    // --- FUNGSI BARU UNTUK EKSPOR PDF ---
    public function eksporPdf(Raport $raport)
    {
        // 1. Keamanan: Pastikan hanya wali murid yang sah yang bisa download
        $user = auth()->user();
        $anakList = Siswa::where('wali_murid_id', $user->id)->pluck('id');
        abort_unless($anakList->contains($raport->siswa_id) && $raport->status === 'terbit', 403);

        $raport->load('siswa.kelas');

        // 2. Ambil data Nilai (sama seperti fungsi show)
        $nilai = Nilai::where('siswa_id', $raport->siswa_id)
            ->where('semester', $raport->semester)
            ->where('tahun_ajaran', $raport->tahun_ajaran)
            ->with(['mataPelajaran' => function ($query) use ($raport) {
                $query->where('tingkat', $raport->siswa->kelas->tingkat);
            }])->get()->filter(function ($item) {
                return $item->mataPelajaran !== null;
            })->values();

        // 3. Ambil data Absensi tanpa filter semester/tahun_ajaran
        $absensi = [
            'sakit' => Absensi::where('siswa_id', $raport->siswa_id)->where('status', 'sakit')->count(),
            'izin'  => Absensi::where('siswa_id', $raport->siswa_id)->where('status', 'izin')->count(),
            'alpha' => Absensi::where('siswa_id', $raport->siswa_id)->where('status', 'alpha')->count(),
        ];

        // 4. Proses jadikan PDF
        $pdf = Pdf::loadView('wali.raport.pdf', compact('raport', 'nilai', 'absensi'));
        
        // 5. Download file
        $namaFile = 'e-Raport_' . $raport->siswa->nama_lengkap . '_Semester_' . $raport->semester . '.pdf';
        return $pdf->download(str_replace(' ', '_', $namaFile));
    }
}