<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\{Tugas, PengumpulanTugas, JawabanCbt};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    private function getSiswa()
    {
        return auth()->user()->siswa;
    }

    /**
     * Memastikan siswa hanya dapat mengakses tugas yang aktif dan sesuai dengan kelasnya.
     */
    private function otorisasiTugas(Tugas $tugas, $siswa)
    {
        if ($tugas->kelas_id !== $siswa->kelas_id || $tugas->status !== 'aktif') {
            abort(403, 'Anda tidak memiliki otorisasi untuk mengakses tugas ini.');
        }
    }

    // ── DAFTAR TUGAS AKTIF (belum dikumpulkan & belum expired) ────────────────
    public function index(Request $request)
    {
        $siswa = $this->getSiswa();

        // 1. Ambil ID tugas yang SUDAH dikumpulkan agar bisa disembunyikan
        $sudahKumpul = PengumpulanTugas::where('siswa_id', $siswa->id)
            ->pluck('tugas_id');

        // 2. Query Tugas yang aktif, sesuai kelas, belum dikumpulkan, dan belum expired
        $tugas = Tugas::with(['mataPelajaran', 'guru.user'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('status', 'aktif')
            ->whereNotIn('id', $sudahKumpul)
            ->where(function ($q) {
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
        $siswa = $this->getSiswa();

        // Proteksi IDOR
        $this->otorisasiTugas($tugas, $siswa);

        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan', 'siswa'));
    }

    // ── KUMPULKAN TUGAS (UPLOAD FILE) ─────────────────────────────────────────
    public function kumpulkan(Request $request, Tugas $tugas)
    {
        $siswa = $this->getSiswa();

        // Proteksi IDOR
        $this->otorisasiTugas($tugas, $siswa);

        $request->validate([
            'file'    => 'nullable|file|max:10240',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Cegah submit ganda
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

        // Tentukan status ketepatan waktu pengumpulan secara konsisten
        $statusPengumpulan = $tugas->isTerlambat() ? 'terlambat' : 'tepat_waktu';

        PengumpulanTugas::create([
            'tugas_id'        => $tugas->id,
            'siswa_id'        => $siswa->id,
            'file'            => $filePath,
            'catatan'         => $request->catatan,
            'dikumpulkan_at'  => now(),
            'status'          => $statusPengumpulan,
        ]);

        return back()->with('success', 'Yeay! Tugas berhasil dikumpulkan! 🎉');
    }

    // ── HALAMAN PENGERJAAN CBT ────────────────────────────────────────────────
    public function kerjakan(Tugas $tugas)
    {
        $siswa = $this->getSiswa();

        // Proteksi IDOR
        $this->otorisasiTugas($tugas, $siswa);

        // Cegah pengerjaan ulang (Siswa tidak boleh masuk ke halaman CBT jika sudah mengumpulkan)
        $sudahAda = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahAda) {
            return redirect()->route('siswa.tugas.show', $tugas->id)
                ->with('error', 'Kamu sudah mengerjakan tugas ini.');
        }

        $pertanyaan = $tugas->pertanyaans()->inRandomOrder()->get();

        return view('siswa.tugas.kerjakan_cbt', compact('tugas', 'pertanyaan'));
    }

    // ── SIMPAN HASIL CBT ──────────────────────────────────────────────────────
    public function simpanCBT(Request $request, Tugas $tugas)
    {
        $siswa = $this->getSiswa();

        // Proteksi IDOR
        $this->otorisasiTugas($tugas, $siswa);

        // Cegah submit ulang
        $sudahAda = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if ($sudahAda) {
            return response()->json(['error' => 'Kamu sudah mengerjakan soal ini.'], 422);
        }

        $pertanyaans = $tugas->pertanyaans;
        $benar = 0;

        DB::transaction(function () use ($request, $tugas, $siswa, $pertanyaans, &$benar) {
            foreach ($pertanyaans as $p) {
                $jawaban = strtoupper($request->input('jawaban_' . $p->id) ?? '');
                $isBenar = $jawaban === $p->jawaban_benar;
                if ($isBenar) {
                    $benar++;
                }

                // Simpan rekap jawaban per pertanyaan
                JawabanCbt::updateOrCreate(
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
            $nilai = $total > 0 ? round(($benar / $total) * 100) : 0;

            // Simpan ke pengumpulan tugas
            PengumpulanTugas::updateOrCreate(
                ['tugas_id' => $tugas->id, 'siswa_id' => $siswa->id],
                [
                    'nilai'          => $nilai,
                    'dikumpulkan_at' => now(),
                    'status'         => $tugas->isTerlambat() ? 'terlambat' : 'tepat_waktu',
                ]
            );
        });

        // Hitung nilai akhir sekali saja untuk response JSON
        $total = $pertanyaans->count();
        $nilaiAkhir = $total > 0 ? round(($benar / $total) * 100) : 0;

        return response()->json([
            'nilai' => $nilaiAkhir,
            'benar' => $benar,
            'total' => $total,
        ]);
    }
}