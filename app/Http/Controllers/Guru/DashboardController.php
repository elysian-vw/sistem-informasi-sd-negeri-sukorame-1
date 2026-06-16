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
        
        // Ambil ID kelas: Prioritaskan guru->kelas_id (sesuai AbsensiController)
        // Ditambah kelas dimana guru ini menjadi wali kelas (jika ada di masa depan)
        $kelasIds = [];
        if ($guru && $guru->kelas_id) {
            $kelasIds[] = $guru->kelas_id;
        }
        
        $waliKelasIds = Kelas::where('wali_kelas_id', auth()->id())->pluck('id')->toArray();
        $kelasIds = array_unique(array_merge($kelasIds, $waliKelasIds));

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
        // Gunakan filter pada kolom 'tanggal' agar konsisten dengan display chart
        $absensiRaw = Absensi::whereIn('kelas_id', $kelasIds)
            ->where('tanggal', '>=', now()->subDays(6)->format('Y-m-d'))
            ->where('status', 'hadir')
            ->selectRaw('tanggal as tgl, COUNT(*) as total')
            ->groupBy('tanggal')
            ->get()
            ->keyBy(function($item) {
                return is_string($item->tgl) ? substr($item->tgl, 0, 10) : $item->tgl->format('Y-m-d');
            });

        $absensiLabels = [];
        $absensiData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $absensiLabels[] = now()->subDays($i)->format('d/m');
            $absensiData[]   = isset($absensiRaw[$tgl]) ? $absensiRaw[$tgl]->total : 0;
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

    public function getChartData(\Illuminate\Http\Request $request)
    {
        $guru = auth()->user()->guru;
        
        $statusFilter = $request->get('status', 'hadir');
        $periode = $request->get('periode', 'minggu');
        
        $kelasIds = [];
        if ($guru && $guru->kelas_id) {
            $kelasIds[] = $guru->kelas_id;
        }
        $waliKelasIds = \App\Models\Kelas::where('wali_kelas_id', auth()->id())->pluck('id')->toArray();
        $kelasIds = array_unique(array_merge($kelasIds, $waliKelasIds));

        $days = 7;
        if ($periode == 'bulan') $days = 30;
        if ($periode == 'semester') $days = 180;
        if ($periode == 'hari') $days = 1;

        $rawData = \App\Models\Absensi::whereIn('kelas_id', $kelasIds)
            ->where('status', $statusFilter)
            ->where('tanggal', '>=', now()->subDays($days)->format('Y-m-d'))
            ->selectRaw('tanggal as tgl, count(*) as total')
            ->groupBy('tanggal')
            ->get()
            ->keyBy(function($item) {
                return is_string($item->tgl) ? substr($item->tgl, 0, 10) : $item->tgl->format('Y-m-d');
            });

        $labels = [];
        $values = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            $values[] = isset($rawData[$date]) ? $rawData[$date]->total : 0;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}
