<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $guru         = auth()->user()->guru;
        $semesterAktif = $request->get('semester', '1');
        $tahunAjaran  = '2025/2026';

        $hariMap = [
            'Monday'    => 'senin',
            'Tuesday'   => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday'  => 'kamis',
            'Friday'    => 'jumat',
            'Saturday'  => 'sabtu',
        ];
        $hariIni = $hariMap[now()->format('l')] ?? '';

        // Ambil semua jadwal kelas guru semester ini
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'guru.user'])
            ->where('kelas_id', $guru->kelas_id)
            ->where('semester', $semesterAktif)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('is_aktif', 1)
            ->orderBy('jam_ke')
            ->get();

        return view('guru.jadwal.index', compact(
            'jadwal', 'hariIni', 'semesterAktif', 'tahunAjaran'
        ));
    }
}