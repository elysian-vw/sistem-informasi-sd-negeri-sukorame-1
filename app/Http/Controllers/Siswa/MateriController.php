<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\{Materi, MataPelajaran};
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $siswa   = auth()->user()->siswa;
        $tingkat = $siswa->kelas?->tingkat ?? 0;

        $mapel = MataPelajaran::where('tingkat', $tingkat)->get();

        // Kunci murni berdasarkan kelas_id siswa DAN tipenya adalah 'materi'
        $query = Materi::with(['mataPelajaran', 'guru.user'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('tipe', 'materi');

        if ($request->filled('mata_pelajaran_id')) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        }

        $materi = $query->latest()->paginate(12)->withQueryString();

        return view('siswa.materi.index', compact('materi', 'mapel', 'siswa'));
    }

    public function show(Materi $materi)
    {
        if ($materi->kelas_id !== auth()->user()->siswa->kelas_id) {
            abort(403, 'Akses Ditolak! Materi ini bukan untuk kelas Anda.');
        }

        return view('siswa.materi.show', compact('materi'));
    }
}