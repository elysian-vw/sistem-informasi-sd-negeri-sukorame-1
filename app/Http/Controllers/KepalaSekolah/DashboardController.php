<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\{Absensi, Kelas, User, Materi, Siswa};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama global
        $totalGuru  = User::where('role', 'guru')->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalKelas = Kelas::count();

        // Hitung persentase kehadiran hari ini secara aman
        $hariIni = now()->toDateString();
        $totalAbsen = Absensi::whereRaw('DATE(created_at) = ?', [$hariIni])->orWhere('tanggal', $hariIni)->count();
        $hadir = Absensi::where(function($q) use ($hariIni) {
            $q->whereRaw('DATE(created_at) = ?', [$hariIni])->orWhere('tanggal', $hariIni);
        })->whereIn('status', ['hadir', 'Hadir'])->count();
        
        $persentaseHadir = $totalAbsen > 0 ? round(($hadir / $totalAbsen) * 100) . '%' : '100%';

        // Ambil data kelas untuk opsi filter dropdown di grafik
        $kelasBaris = Kelas::all();

        // Log Aktivitas Pembelajaran Guru
        $materiTerbaru = Materi::with(['guru.user', 'mataPelajaran', 'kelas'])
            ->latest()
            ->take(5)
            ->get();

        $logAktivitas = $materiTerbaru->map(function ($materi) {
            return (object) [
                'guru' => (object) [
                    'name' => $materi->guru->user->name ?? 'Guru Sekolah'
                ],
                'mapel'      => $materi->mataPelajaran->nama ?? '-',
                'kelas'      => $materi->kelas->nama_kelas ?? '-',
                'keterangan' => 'Mengunggah materi pembelajaran baru: "' . \Str::limit($materi->judul, 25) . '"',
                'created_at' => $materi->created_at
            ];
        });

        return view('kepala_sekolah.dashboard', compact('totalGuru', 'totalSiswa', 'totalKelas', 'persentaseHadir', 'logAktivitas', 'kelasBaris'));
    }

    // DATA GRAFIK — REPLIKA DASHBOARD ADMIN
    public function getChartData(Request $request)
    {
        $periode = $request->get('periode', 'minggu');
        $kelasId = $request->get('kelas_id');
        $statusFilter = $request->get('status', 'hadir');

        $days = 7;
        if ($periode == 'bulan') $days = 30;
        if ($periode == 'semester') $days = 180;
        if ($periode == 'hari') $days = 1;

        $query = Absensi::whereIn('status', [strtolower($statusFilter), ucfirst(strtolower($statusFilter))]);
        
        if ($kelasId) {
            $query->whereHas('siswa', function($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $rawData = $query->where(function($q) use ($days) {
                            $q->where('created_at', '>=', now()->subDays($days))
                              ->orWhere('tanggal', '>=', now()->subDays($days)->toDateString());
                        })
                        ->selectRaw('CASE WHEN created_at IS NOT NULL THEN DATE(created_at) ELSE tanggal END as tgl, count(*) as total')
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

    // LAPORAN KEHADIRAN BULANAN GLOBAL SEKOLAH
    public function laporanKehadiran(Request $request)
    {
        $bulan = $request->get('bulan');
        $bulanDipilih = (is_string($bulan) || is_numeric($bulan)) && !empty($bulan) ? str_pad($bulan, 2, '0', STR_PAD_LEFT) : date('m');
        
        $tahun = $request->get('tahun');
        $tahunDipilih = (is_string($tahun) || is_numeric($tahun)) && !empty($tahun) ? $tahun : date('Y');

        $listKelas = Kelas::all();

        $rekapBulanan = [];
        $totalSiswaSekolah = 0;
        $globalHadir = 0;
        $globalIzin  = 0;
        $globalSakit = 0;
        $globalAlpha = 0;

        foreach ($listKelas as $kelas) {
            $jumlahSiswa = Siswa::where('kelas_id', $kelas->id)->count();
            $totalSiswaSekolah += $jumlahSiswa;

            $hadir = Absensi::where('kelas_id', $kelas->id)
                ->whereMonth('tanggal', $bulanDipilih)
                ->whereYear('tanggal', $tahunDipilih)
                ->whereIn('status', ['Hadir', 'hadir'])
                ->count();

            $izin = Absensi::where('kelas_id', $kelas->id)
                ->whereMonth('tanggal', $bulanDipilih)
                ->whereYear('tanggal', $tahunDipilih)
                ->whereIn('status', ['Izin', 'izin'])
                ->count();

            $sakit = Absensi::where('kelas_id', $kelas->id)
                ->whereMonth('tanggal', $bulanDipilih)
                ->whereYear('tanggal', $tahunDipilih)
                ->whereIn('status', ['Sakit', 'sakit'])
                ->count();

            $alpha = Absensi::where('kelas_id', $kelas->id)
                ->whereMonth('tanggal', $bulanDipilih)
                ->whereYear('tanggal', $tahunDipilih)
                ->whereIn('status', ['Alfa', 'alfa', 'Alpha', 'alpha'])
                ->count();

            $globalHadir += $hadir;
            $globalIzin  += $izin;
            $globalSakit += $sakit;
            $globalAlpha += $alpha;

            $totalAbsenKelas = $hadir + $izin + $sakit + $alpha;
            $persentase = $totalAbsenKelas > 0 ? round(($hadir / $totalAbsenKelas) * 100) : 0;

            $rekapBulanan[] = (object)[
                'nama_kelas'   => $kelas->nama_kelas,
                'total_siswa'  => $jumlahSiswa,
                'hadir'        => $hadir,
                'izin'         => $izin,
                'sakit'        => $sakit,
                'alpha'        => $alpha,
                'persentase'   => $persentase . '%'
            ];
        }

        $totalAbsenGlobal = $globalHadir + $globalIzin + $globalSakit + $globalAlpha;
        $rataRataHadir = $totalAbsenGlobal > 0 ? round(($globalHadir / $totalAbsenGlobal) * 100) . '%' : '0%';

        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        return view('kepala_sekolah.laporan_kehadiran', compact(
            'rekapBulanan', 'bulanDipilih', 'tahunDipilih', 'namaBulan',
            'totalSiswaSekolah', 'globalHadir', 'globalIzin', 'globalSakit', 'globalAlpha', 'rataRataHadir'
        ));
    }

    // ── LAPORAN CAPAIAN NILAI SISWA (BARU DIUBAH) ──
    public function laporanNilai()
    {
        $listKelas = Kelas::all();

        $rekapNilai = [];
        $totalNilaiGlobal = 0;
        $jumlahKelasDinilai = 0;
        $totalEvaluasiSekolah = 0;

        foreach ($listKelas as $kelas) {
            $jumlahSiswa = Siswa::where('kelas_id', $kelas->id)->count();
            $totalTugas  = \App\Models\Tugas::where('kelas_id', $kelas->id)->count();
            $totalEvaluasiSekolah += $totalTugas;

            // Menghitung rata-rata nilai dari tabel pengumpulan_tugas khusus untuk siswa di kelas ini
            $rataRata = DB::table('pengumpulan_tugas')
                ->join('siswa', 'pengumpulan_tugas.siswa_id', '=', 'siswa.id')
                ->where('siswa.kelas_id', $kelas->id)
                ->whereNotNull('pengumpulan_tugas.nilai')
                ->avg('pengumpulan_tugas.nilai');

            $rataRata = $rataRata ? round($rataRata, 1) : 0;

            // Menentukan predikat berdasarkan rata-rata
            if ($rataRata >= 90) {
                $predikat = 'A (Sangat Baik)';
                $warna = '#16a34a'; // Hijau
                $bg = '#dcfce7';
            } elseif ($rataRata >= 80) {
                $predikat = 'B (Baik)';
                $warna = '#2563eb'; // Biru
                $bg = '#dbeafe';
            } elseif ($rataRata >= 70) {
                $predikat = 'C (Cukup)';
                $warna = '#d97706'; // Orange
                $bg = '#fef3c7';
            } elseif ($rataRata > 0) {
                $predikat = 'D (Kurang)';
                $warna = '#dc2626'; // Merah
                $bg = '#fee2e2';
            } else {
                $predikat = 'Belum Ada Nilai';
                $warna = '#6b7280'; // Abu-abu
                $bg = '#f3f4f6';
            }

            $rekapNilai[] = (object)[
                'nama_kelas'  => $kelas->nama_kelas,
                'total_siswa' => $jumlahSiswa,
                'total_tugas' => $totalTugas,
                'rata_rata'   => $rataRata,
                'predikat'    => $predikat,
                'warna'       => $warna,
                'bg'          => $bg
            ];

            if ($rataRata > 0) {
                $totalNilaiGlobal += $rataRata;
                $jumlahKelasDinilai++;
            }
        }

        // Urutkan kelas dari rata-rata nilai tertinggi ke terendah (Ranking Kelas)
        usort($rekapNilai, function($a, $b) {
            return $b->rata_rata <=> $a->rata_rata;
        });

        $rataRataSekolah = $jumlahKelasDinilai > 0 ? round($totalNilaiGlobal / $jumlahKelasDinilai, 1) : 0;

        return view('kepala_sekolah.laporan_nilai', compact('rekapNilai', 'rataRataSekolah', 'totalEvaluasiSekolah'));
    }
}