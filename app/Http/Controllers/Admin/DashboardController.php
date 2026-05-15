<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengumuman;
use App\Models\Absensi;
use Illuminate\Http\Request; // ← Tadi ini kurang, pantesan error Request
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Pake created_at sesuai DB kamu
        $absensiRaw = Absensi::selectRaw('DATE(created_at) as tgl, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(6))
            ->where('status', 'hadir')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('tgl');

        $absensiLabels = [];
        $absensiData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->format('Y-m-d');
            $absensiLabels[] = now()->subDays($i)->format('d/m');
            // Cek apakah ada datanya, kalau nggak ada kasih 0
            $absensiData[] = isset($absensiRaw[$tgl]) ? $absensiRaw[$tgl]->total : 0;
        }

        // Statistik Simple
        $data = [
            'total_siswa'        => Siswa::count(),
            'total_guru'         => Guru::count(),
            'total_kelas'        => Kelas::count(),
            'siswa_belum_aktif'  => Siswa::whereNull('user_id')->count(), // ← ganti ini
            'pengumuman_terbaru' => Pengumuman::latest()->take(5)->get(),
            'siswa_terbaru'      => Siswa::with('kelas')->latest()->take(5)->get(),
            'absensi_labels'     => $absensiLabels,
            'absensi_data'       => $absensiData,
            'aktivitas'          => $this->getAktivitasTerbaru(),
        ];

        return view('admin.dashboard', compact('data'));
    }

    public function getChartData(Request $request)
    {
        $periode = $request->get('periode', 'minggu');
        $kelasId = $request->get('kelas_id');
        // AMBIL STATUS DARI REQUEST! Defaultnya hadir
        $statusFilter = $request->get('status', 'hadir'); 

        $days = 7;
        if ($periode == 'bulan') $days = 30;
        if ($periode == 'semester') $days = 180;
        if ($periode == 'hari') $days = 1;

        // Pake variabel $statusFilter, jangan di-ketik manual 'hadir'
        $query = \App\Models\Absensi::where('status', $statusFilter);
        
        if ($kelasId) {
            $query->whereHas('siswa', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $rawData = $query->where('created_at', '>=', now()->subDays($days))
                        ->selectRaw('DATE(created_at) as tgl, count(*) as total')
                        ->groupBy('tgl')
                        ->get()
                        ->keyBy('tgl');

        $labels = [];
        $values = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            $values[] = $rawData->get($date)->total ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    // Aku pisahin biar nggak menuh-menuhin index
    private function getAktivitasTerbaru()
    {
        $aktivitas = collect();
        Siswa::latest()->take(3)->get()->each(function ($s) use (&$aktivitas) {
            $aktivitas->push(['pesan' => 'Siswa baru: ' . $s->nama_lengkap, 'waktu' => $s->created_at]);
        });
        Guru::with('user')->latest()->take(2)->get()->each(function ($g) use (&$aktivitas) {
            $aktivitas->push(['pesan' => 'Guru baru: ' . ($g->user?->name ?? '-'), 'waktu' => $g->created_at]);
        });
        return $aktivitas->sortByDesc('waktu')->take(5)->values()->map(function($item) {
            $item['waktu'] = $item['waktu']->diffForHumans();
            return $item;
        });
    }
}