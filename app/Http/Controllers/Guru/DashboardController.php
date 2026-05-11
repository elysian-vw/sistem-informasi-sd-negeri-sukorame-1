<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
// Bagian use — tambah JadwalPelajaran
use App\Models\{Absensi, Kelas, Tugas, Materi, Pengumuman, Forum, JadwalPelajaran};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $guru      = auth()->user()->guru;
        $kelasIds  = Kelas::where('wali_kelas_id', auth()->id())->pluck('id');

        // Statistik absensi hari ini
        $absensiHariIni = Absensi::whereIn('kelas_id', $kelasIds)
            ->whereDate('tanggal', today())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Tugas aktif
        $tugasAktif = Tugas::where('guru_id', $guru->id)
            ->where('status', 'aktif')
            ->with(['kelas', 'mataPelajaran', 'pengumpulan'])
            ->latest()
            ->take(5)
            ->get();

        // Materi terbaru
        $materiTerbaru = Materi::where('guru_id', $guru->id)
            ->with('mataPelajaran')
            ->latest()
            ->take(5)
            ->get();

        // Pengumuman aktif
        $pengumuman = Pengumuman::where('status', 'aktif')
            ->whereIn('untuk', ['semua', 'guru'])
            ->latest()
            ->take(3)
            ->get();

        // Grafik absensi 7 hari terakhir
        $absensiRaw = Absensi::whereIn('kelas_id', $kelasIds)
            ->where('created_at', '>=', now()->subDays(6))
            ->where('status', 'hadir')
            ->selectRaw('DATE(tanggal) as tgl, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->get()
            ->keyBy('tgl');

        $absensiLabels = [];
        $absensiData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $absensiLabels[] = now()->subDays($i)->format('d/m');
            $absensiData[]   = $absensiRaw[$tgl]->total ?? 0;
        }

        // Diskusi terbaru
        $diskusiTerbaru = Forum::with(['user', 'komentar'])
            ->where(function ($q) use ($kelasIds) {
                $q->where('untuk', 'semua')->orWhereIn('kelas_id', $kelasIds);
            })
            ->latest()->take(3)->get();

        $hariMap = ['Monday'=>'senin','Tuesday'=>'selasa','Wednesday'=>'rabu','Thursday'=>'kamis','Friday'=>'jumat','Saturday'=>'sabtu'];
        $hariIni = $hariMap[now()->format('l')] ?? '';
        $jadwalHariIni = JadwalPelajaran::with('mataPelajaran')
            ->where('kelas_id', $guru->kelas_id)
            ->where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->where('is_aktif', 1)
            ->where('tahun_ajaran', '2025/2026') 
            ->where('semester', '1')
            ->orderBy('jam_ke')
            ->get();

        return view('guru.dashboard', compact(
            'guru', 'absensiHariIni', 'tugasAktif', 'materiTerbaru',
            'pengumuman', 'absensiLabels', 'absensiData', 'diskusiTerbaru',
            'jadwalHariIni', 'hariIni' // ← tambah ini
        ));
    }
}
