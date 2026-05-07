<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\{Absensi, Siswa, Tugas, PengumpulanTugas, Pengumuman, Nilai};

class DashboardController extends Controller
{
    public function index()
    {
        $user      = auth()->user();
        $anakList  = Siswa::where('wali_murid_id', $user->id)->with('kelas')->get();
        $selectedId = request('anak');
        $anakUtama  = $selectedId
            ? $anakList->firstWhere('id', $selectedId)
            : $anakList->first();

        $absensiRekap  = [];
        $tugasRekap    = [];

        foreach ($anakList as $anak) {
            // Absensi bulan ini per anak
            $absensi = Absensi::where('siswa_id', $anak->id)
                ->whereMonth('tanggal', now()->month)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
            $absensiRekap[$anak->id] = $absensi;

            // Tugas aktif per anak
            $tugasAktif = Tugas::where('kelas_id', $anak->kelas_id)
                ->where('status', 'aktif')
                ->count();
            $tugasDikumpul = PengumpulanTugas::where('siswa_id', $anak->id)->count();
            $tugasRekap[$anak->id] = ['aktif' => $tugasAktif, 'dikumpul' => $tugasDikumpul];
        }

        // Pengumuman untuk wali
        $pengumuman = Pengumuman::where('status', 'aktif')
            ->whereIn('untuk', ['semua', 'wali_murid'])
            ->latest()->take(5)->get();

        // Absensi terbaru anak utama (5 data terakhir)
        $absensiTerbaru = $anakUtama
            ? Absensi::where('siswa_id', $anakUtama->id)
                ->with('kelas')
                ->latest('tanggal')
                ->take(5)
                ->get()
            : collect();

        return view('wali.dashboard', compact(
            'anakList', 'anakUtama', 'absensiRekap',
            'tugasRekap', 'pengumuman', 'absensiTerbaru'
        ));
    }
}
