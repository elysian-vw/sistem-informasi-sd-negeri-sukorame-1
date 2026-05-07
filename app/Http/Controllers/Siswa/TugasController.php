<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\{Tugas, PengumpulanTugas};
use Illuminate\Http\Request;

class TugasController extends Controller
{
    private function getSiswa()
    {
        return auth()->user()->siswa;
    }

    // ── DAFTAR TUGAS AKTIF (belum dikumpulkan & belum expired) ────────────────
    public function index(Request $request)
    {
        $siswa = $this->getSiswa();

        // 1. Ambil ID tugas yang SUDAH dikumpulkan (biar bisa kita sembunyikan)
        $sudahKumpul = PengumpulanTugas::where('siswa_id', $siswa->id)
            ->pluck('tugas_id');

        // 2. Query Tugas
        $tugas = Tugas::with(['mataPelajaran', 'guru.user'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('status', 'aktif') // Hanya yang statusnya aktif
            ->whereNotIn('id', $sudahKumpul) // Sembunyikan yang sudah dikerjakan
            ->where(function ($q) {
                // Sembunyikan yang sudah lewat deadline
                // (Hanya tampilkan jika deadline null ATAU deadline masih di masa depan)
                $q->whereNull('deadline')
                ->orWhere('deadline', '>', now()); 
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('siswa.tugas.index', compact('tugas', 'siswa'));
    }

    // ── DETAIL & KUMPULKAN TUGAS ──────────────────────────────────────────────
    public function show(Tugas $tugas)
    {
        $siswa       = $this->getSiswa();
        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan', 'siswa'));
    }

    // ── KUMPULKAN TUGAS ───────────────────────────────────────────────────────
    public function kumpulkan(Request $request, Tugas $tugas)
    {
        $siswa = $this->getSiswa();

        $request->validate([
            'file'    => 'nullable|file|max:10240',
            'catatan' => 'nullable|string|max:500',
        ]);

        $sudahAda = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Kamu sudah mengumpulkan tugas ini.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas/pengumpulan', 'public');
        }

        PengumpulanTugas::create([
            'tugas_id'        => $tugas->id,
            'siswa_id'        => $siswa->id,
            'file'            => $filePath,
            'catatan'         => $request->catatan,
            'dikumpulkan_at'  => now(),
            'status'          => 'tepat_waktu',
        ]);

        return back()->with('success', 'Yeay! Tugas berhasil dikumpulkan! 🎉');
    }

    public function kerjakan(Tugas $tugas) {
    // Ambil soal secara acak biar anak-anak nggak nyontek (opsional)
        $pertanyaan = $tugas->pertanyaans()->inRandomOrder()->get();
        
        return view('siswa.tugas.kerjakan_cbt', compact('tugas', 'pertanyaan'));
    }
    
    public function simpanCBT(Request $request, Tugas $tugas)
    {
        $siswa       = $this->getSiswa();
        $pertanyaans = $tugas->pertanyaans;
    
        // Cegah submit ulang
        $sudahAda = \App\Models\PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->exists();
    
        if ($sudahAda) {
            return response()->json(['error' => 'Kamu sudah mengerjakan soal ini.'], 422);
        }
    
        $benar = 0;
    
        \Illuminate\Support\Facades\DB::transaction(function () use (
            $request, $tugas, $siswa, $pertanyaans, &$benar
        ) {
            foreach ($pertanyaans as $p) {
                $jawaban  = strtoupper($request->input('jawaban_' . $p->id) ?? '');
                $isBenar  = $jawaban === $p->jawaban_benar;
                if ($isBenar) $benar++;
    
                // Simpan jawaban per soal (untuk rekap guru)
                \App\Models\JawabanCbt::updateOrCreate(
                    [
                        'tugas_id'      => $tugas->id,
                        'siswa_id'      => $siswa->id,
                        'pertanyaan_id' => $p->id,
                    ],
                    [
                        'jawaban'  => $jawaban ?: null,
                        'is_benar' => $isBenar,
                    ]
                );
            }
    
            $total = $pertanyaans->count();
            $nilai = $total > 0 ? round($benar / $total * 100) : 0;
    
            \App\Models\PengumpulanTugas::updateOrCreate(
                ['tugas_id' => $tugas->id, 'siswa_id' => $siswa->id],
                [
                    'nilai'          => $nilai,
                    'dikumpulkan_at' => now(),
                    'status'         => $tugas->isTerlambat() ? 'terlambat' : 'tepat_waktu',
                ]
            );
        });
    
        $nilai = $pertanyaans->count() > 0
            ? round($benar / $pertanyaans->count() * 100)
            : 0;
    
        return response()->json([
            'nilai' => $nilai,
            'benar' => $benar,
            'total' => $pertanyaans->count(),
        ]);
    }
}