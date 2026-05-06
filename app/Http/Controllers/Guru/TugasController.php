<?php
// Tambahkan method ini ke TugasController yang sudah ada
// (ganti method penilaian yang lama)

// ── HALAMAN PENILAIAN TUGAS ───────────────────────────────────────────────
public function penilaian(Tugas $tugas)
{
    $this->authorize_guru($tugas);

    $siswaKelas  = $tugas->kelas->siswa()->orderBy('nama_lengkap')->get();

    $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
        ->get()
        ->keyBy('siswa_id');

    return view('guru.tugas.penilaian', compact('tugas', 'siswaKelas', 'pengumpulan'));
}

// ── SIMPAN NILAI TUGAS ────────────────────────────────────────────────────
public function simpanNilai(Request $request, Tugas $tugas)
{
    $this->authorize_guru($tugas);

    $request->validate([
        'nilai'      => 'required|array',
        'nilai.*'    => 'nullable|numeric|min:0|max:100',
        'feedback'   => 'nullable|array',
        'feedback.*' => 'nullable|string|max:500',
    ]);

    foreach ($request->nilai as $siswaId => $nilai) {
        if ($nilai === null || $nilai === '') continue;

        PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswaId)
            ->update([
                'nilai'    => $nilai,
                'feedback' => $request->feedback[$siswaId] ?? null,
            ]);
    }

    return back()->with('success', 'Nilai berhasil disimpan.');
}