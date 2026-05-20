<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{Tugas, Pertanyaan, PengumpulanTugas, Kelas, MataPelajaran};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, DB};

class TugasController extends Controller
{
    // ── Auth helper ───────────────────────────────────────────────────────────
    private function authorize_guru(Tugas $tugas)
    {
        $guruId = auth()->user()->guru->id;
        if ((int) $tugas->guru_id !== (int) $guruId) {
            abort(403, 'Bukan tugasmu, jangan asal akses.');
        }
    }

    /**
     * Resolve kelas yang boleh diakses guru:
     * - Wali kelas / punya kelas_id → hanya kelasnya sendiri
     * - Guru mulok tanpa kelas      → semua kelas
     * - Guru mapel umum tanpa kelas → null (tidak boleh akses)
     */
    private function resolveKelas($guru): ?\Illuminate\Database\Eloquent\Collection
    {
        $kelasTarget = Kelas::where('wali_kelas_id', auth()->id())->first()
            ?? ($guru->kelas_id ? Kelas::find($guru->kelas_id) : null);

        if ($kelasTarget) {
            return Kelas::where('id', $kelasTarget->id)->get();
        }

        if ($guru->isMapelMulok()) {
            return Kelas::all();
        }

        return null;
    }

    // ── INDEX ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->guru) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $guru        = $user->guru;
        $kelasAkses  = $this->resolveKelas($guru);

        // Guru mapel umum tanpa kelas — tampilkan kosong
        if (is_null($kelasAkses)) {
            $tugas = collect()->paginate(10) ?? Tugas::whereRaw('1=0')->paginate(10);
            $kelas = collect();
            $mapel = MataPelajaran::where('guru_id', $guru->id)->get();
            return view('guru.tugas.index', compact('tugas', 'kelas', 'mapel'));
        }

        $kelasTarget = Kelas::where('wali_kelas_id', auth()->id())->first()
            ?? ($guru->kelas_id ? Kelas::find($guru->kelas_id) : null);

        $query = Tugas::with(['kelas', 'mataPelajaran', 'pengumpulan'])
            ->where('guru_id', $guru->id);

        if ($kelasTarget) {
            // Wali kelas / guru kelas — kunci ke kelasnya
            $tingkat = $kelasTarget->tingkat;
            $kelas   = $kelasAkses;
            $mapel   = MataPelajaran::where('guru_id', $guru->id)->where('tingkat', $tingkat)->get();
            $query->where('kelas_id', $kelasTarget->id);
        } else {
            // Guru mulok — bebas filter
            $kelas = $kelasAkses;
            $mapel = MataPelajaran::where('guru_id', $guru->id)->get();

            if ($request->filled('kelas_id')) {
                $query->where('kelas_id', $request->kelas_id);
            }
        }

        if ($request->filled('mata_pelajaran_id')) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tugas = $query->latest()->paginate(10)->withQueryString();

        return view('guru.tugas.index', compact('tugas', 'kelas', 'mapel'));
    }

    // ── CREATE ────────────────────────────────────────────────────────────────
    public function create()
    {
        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru);

        if (is_null($kelasAkses)) {
            return redirect()->route('guru.tugas.index')
                ->with('error', 'Anda belum memiliki kelas. Silakan hubungi Admin untuk penugasan kelas.');
        }

        if ($kelasAkses->isEmpty()) {
            return redirect()->route('guru.tugas.index')
                ->with('error', 'Data kelas belum tersedia di sistem. Silakan hubungi Admin.');
        }

        $kelasTarget = Kelas::where('wali_kelas_id', auth()->id())->first()
            ?? ($guru->kelas_id ? Kelas::find($guru->kelas_id) : null);

        if ($kelasTarget) {
            $mapel = MataPelajaran::where('guru_id', $guru->id)
                ->where('tingkat', $kelasTarget->tingkat)
                ->get();
        } else {
            $mapel = MataPelajaran::where('guru_id', $guru->id)->get();
        }

        $kelas = $kelasAkses;

        return view('guru.tugas.create', compact('mapel', 'kelas'));
    }

    // ── STORE ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru);

        if (is_null($kelasAkses)) {
            abort(403, 'Anda belum memiliki kelas.');
        }

        // Pastikan kelas_id yang dipilih valid sesuai akses guru
        abort_unless($kelasAkses->contains('id', (int) $request->kelas_id), 403, 'Kelas tidak valid.');

        $request->validate([
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'required',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id'          => 'required|exists:kelas,id',
            'deadline'          => 'required|date',
            'status'            => 'required|in:aktif,draft',
            'file'              => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        if ($request->tipe === 'cbt') {
            $request->validate([
                'soal'                     => 'required|array|min:1',
                'soal.*.soal'              => 'required|string',
                'soal.*.pilihan_a'         => 'required|string|max:255',
                'soal.*.pilihan_b'         => 'required|string|max:255',
                'soal.*.pilihan_c'         => 'required|string|max:255',
                'soal.*.pilihan_d'         => 'required|string|max:255',
                'soal.*.jawaban_benar'     => 'required|in:A,B,C,D',
                'soal.*.gambar_soal'       => 'nullable|image|max:2048',
            ]);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas/file', 'public');
        }

        $tugas = Tugas::create([
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id'          => $request->kelas_id,
            'guru_id'           => $guru->id,
            'deadline'          => $request->deadline,
            'status'            => $request->status,
            'file'              => $filePath,
            'tipe'              => $request->tipe ?? 'upload',
        ]);

        if ($request->tipe === 'cbt' && $request->soal) {
            foreach ($request->soal as $s) {
                $gambarPath = null;
                if (isset($s['gambar_soal']) && $s['gambar_soal'] instanceof \Illuminate\Http\UploadedFile) {
                    $gambarPath = $s['gambar_soal']->store('tugas/gambar-soal', 'public');
                }

                Pertanyaan::create([
                    'tugas_id'      => $tugas->id,
                    'soal'          => $s['soal'],
                    'gambar_soal'   => $gambarPath,
                    'pilihan_a'     => $s['pilihan_a'],
                    'pilihan_b'     => $s['pilihan_b'],
                    'pilihan_c'     => $s['pilihan_c'],
                    'pilihan_d'     => $s['pilihan_d'],
                    'jawaban_benar' => strtoupper($s['jawaban_benar']),
                ]);
            }
        }

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dibuat.');
    }

    // ── SHOW ──────────────────────────────────────────────────────────────────
    public function show(Tugas $tugas)
    {
        $this->authorize_guru($tugas);
        $tugas->load(['mataPelajaran', 'kelas']);
        return view('guru.tugas.show', compact('tugas'));
    }

    // ── EDIT ──────────────────────────────────────────────────────────────────
    public function edit(Tugas $tugas)
    {
        $this->authorize_guru($tugas);

        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru) ?? Kelas::all(); // fallback aman saat edit

        $kelasTarget = Kelas::where('wali_kelas_id', auth()->id())->first()
            ?? ($guru->kelas_id ? Kelas::find($guru->kelas_id) : null);

        if ($kelasTarget) {
            $mapel = MataPelajaran::where('guru_id', $guru->id)
                ->where('tingkat', $kelasTarget->tingkat)
                ->get();
        } else {
            $mapel = MataPelajaran::where('guru_id', $guru->id)->get();
        }

        $kelas = $kelasAkses;
        $tugas->load('pertanyaans');

        return view('guru.tugas.edit', compact('tugas', 'mapel', 'kelas'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────
    public function update(Request $request, Tugas $tugas)
    {
        $this->authorize_guru($tugas);

        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru) ?? Kelas::all();

        abort_unless($kelasAkses->contains('id', (int) $request->kelas_id), 403, 'Kelas tidak valid.');

        $request->validate([
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'required',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id'          => 'required|exists:kelas,id',
            'deadline'          => 'required|date',
            'status'            => 'required|in:aktif,draft',
            'file'              => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        $data = [
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id'          => $request->kelas_id,
            'deadline'          => $request->deadline,
            'status'            => $request->status,
        ];

        if ($request->hasFile('file')) {
            if ($tugas->file) {
                Storage::disk('public')->delete($tugas->file);
            }
            $data['file'] = $request->file('file')->store('tugas/file', 'public');
        }

        $tugas->update($data);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil diupdate.');
    }

    // ── MANAJEMEN SOAL CBT ────────────────────────────────────────────────────
    public function soal(Tugas $tugas)
    {
        $this->authorize_guru($tugas);

        if (!$tugas->isCbt()) {
            return redirect()->route('guru.tugas.index')
                ->with('error', 'Tugas ini bukan tipe CBT.');
        }

        $pertanyaans = $tugas->pertanyaans()->orderBy('id')->get();

        return view('guru.tugas.soal', compact('tugas', 'pertanyaans'));
    }

    public function storeSoal(Request $request, Tugas $tugas)
    {
        $this->authorize_guru($tugas);

        $request->validate([
            'soal'          => 'required|string',
            'pilihan_a'     => 'required|string|max:255',
            'pilihan_b'     => 'required|string|max:255',
            'pilihan_c'     => 'required|string|max:255',
            'pilihan_d'     => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'gambar_soal'   => 'nullable|image|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar_soal')) {
            $gambarPath = $request->file('gambar_soal')->store('tugas/gambar-soal', 'public');
        }

        Pertanyaan::create([
            'tugas_id'      => $tugas->id,
            'soal'          => $request->soal,
            'gambar_soal'   => $gambarPath,
            'pilihan_a'     => $request->pilihan_a,
            'pilihan_b'     => $request->pilihan_b,
            'pilihan_c'     => $request->pilihan_c,
            'pilihan_d'     => $request->pilihan_d,
            'jawaban_benar' => strtoupper($request->jawaban_benar),
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateSoal(Request $request, Tugas $tugas, Pertanyaan $pertanyaan)
    {
        $this->authorize_guru($tugas);

        $request->validate([
            'soal'          => 'required|string',
            'pilihan_a'     => 'required|string|max:255',
            'pilihan_b'     => 'required|string|max:255',
            'pilihan_c'     => 'required|string|max:255',
            'pilihan_d'     => 'required|string|max:255',
            'jawaban_benar' => 'required|in:A,B,C,D',
            'gambar_soal'   => 'nullable|image|max:2048',
        ]);

        $data = [
            'soal'          => $request->soal,
            'pilihan_a'     => $request->pilihan_a,
            'pilihan_b'     => $request->pilihan_b,
            'pilihan_c'     => $request->pilihan_c,
            'pilihan_d'     => $request->pilihan_d,
            'jawaban_benar' => strtoupper($request->jawaban_benar),
        ];

        if ($request->hasFile('gambar_soal')) {
            if ($pertanyaan->gambar_soal) {
                Storage::disk('public')->delete($pertanyaan->gambar_soal);
            }
            $data['gambar_soal'] = $request->file('gambar_soal')->store('tugas/gambar-soal', 'public');
        }

        $pertanyaan->update($data);

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroySoal(Tugas $tugas, Pertanyaan $pertanyaan)
    {
        $this->authorize_guru($tugas);

        if ($pertanyaan->gambar_soal) {
            Storage::disk('public')->delete($pertanyaan->gambar_soal);
        }

        $pertanyaan->delete();

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // ── PENILAIAN ─────────────────────────────────────────────────────────────
    public function penilaian(Tugas $tugas)
    {
        $this->authorize_guru($tugas);

        $siswaKelas = $tugas->kelas->siswa()->orderBy('nama_lengkap')->get();

        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.tugas.penilaian', compact('tugas', 'siswaKelas', 'pengumpulan'));
    }

    public function indexPenilaian()
    {
        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru);

        $query = Tugas::with(['kelas', 'mataPelajaran', 'pengumpulan'])
            ->where('guru_id', $guru->id)
            ->where('status', 'aktif');

        if (!is_null($kelasAkses)) {
            $kelasTarget = Kelas::where('wali_kelas_id', auth()->id())->first()
                ?? ($guru->kelas_id ? Kelas::find($guru->kelas_id) : null);

            if ($kelasTarget) {
                $query->where('kelas_id', $kelasTarget->id);
            }
            // Guru mulok: tampilkan semua tugasnya lintas kelas, tidak perlu filter
        }

        $daftarTugas = $query->latest()->paginate(10);

        return view('guru.tugas.list-penilaian', compact('daftarTugas'));
    }

    // ── SIMPAN NILAI ──────────────────────────────────────────────────────────
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

    // ── DESTROY ───────────────────────────────────────────────────────────────
    public function destroy(Tugas $tugas)
    {
        $this->authorize_guru($tugas);

        if ($tugas->isCbt()) {
            foreach ($tugas->pertanyaans as $p) {
                if ($p->gambar_soal) {
                    Storage::disk('public')->delete($p->gambar_soal);
                }
            }
        }

        if ($tugas->file) {
            Storage::disk('public')->delete($tugas->file);
        }

        $tugas->delete();

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas dihapus.');
    }
}