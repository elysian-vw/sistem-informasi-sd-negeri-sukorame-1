<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{Absensi, Kelas, Siswa};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    // Halaman utama absensi (menampilkan form input massal berdasarkan tanggal)
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        
        if (!$guru->kelas_id) {
            return back()->with('error', 'Anda belum ditugaskan ke kelas manapun. Hubungi Admin.');
        }

        $kelas = Kelas::find($guru->kelas_id);
        
        // Ambil tanggal dari request, jika kosong gunakan hari ini
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        
        // Ambil semua siswa di kelas ini
        $siswaKelas = Siswa::where('kelas_id', $kelas->id)->orderBy('nama_lengkap')->get();

        // Ambil data absensi yang sudah ada pada tanggal tersebut
        $absensiHariIni = Absensi::where('kelas_id', $kelas->id)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        // Ambil riwayat absensi dengan rekap jumlah (15 hari terakhir)
        $riwayatAbsensi = Absensi::where('kelas_id', $kelas->id)
            ->select('tanggal', 
                DB::raw('count(case when status = "hadir" then 1 end) as hadir'),
                DB::raw('count(case when status = "sakit" then 1 end) as sakit'),
                DB::raw('count(case when status = "izin" then 1 end) as izin'),
                DB::raw('count(case when status = "alpha" then 1 end) as alpha')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(15)
            ->get();

        return view('guru.absensi.index', compact('kelas', 'siswaKelas', 'tanggal', 'absensiHariIni', 'riwayatAbsensi'));
    }

    // Proses menyimpan data absensi seluruh siswa
    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        $request->validate([
            'tanggal'      => 'required|date',
            'status'       => 'required|array',
            'status.*'     => 'in:hadir,sakit,izin,alpha',
            'keterangan'   => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);

        $tanggal = $request->tanggal;

        foreach ($request->status as $siswaId => $status) {
            // Gunakan updateOrCreate agar tidak ada data ganda pada hari yang sama
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'kelas_id' => $guru->kelas_id,
                    'tanggal'  => $tanggal,
                ],
                [
                    'status'     => $status,
                    'keterangan' => $request->keterangan[$siswaId] ?? null,
                ]
            );
        }

        $tanggalFormat = Carbon::parse($tanggal)->translatedFormat('d F Y');
        return redirect()->route('guru.absensi.index', ['tanggal' => $tanggal])
                         ->with('success', "Absensi tanggal $tanggalFormat berhasil disimpan.");
    }
}