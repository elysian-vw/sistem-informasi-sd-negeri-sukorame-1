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
        if ($tugas->guru_id !== $guruId) {
            abort(403, 'Bukan tugasmu, jangan asal akses.');
        }
    }

    // ── INDEX ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->guru) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $guru = $user->guru;

        // Ambil data referensi untuk dropdown filter di view
        // Cuma ambil kelas yang ditugaskan ke guru ini
        $kelas = Kelas::where('id', $guru->kelas_id)->get();
        
        // Cuma ambil mapel yang diampu oleh guru ini
        $mapel = MataPelajaran::where('guru_id', $guru->id)->get();

        // QUERY UTAMA: Kunci berdasarkan guru_id DAN kelas_id si guru
        $query = Tugas::with(['kelas', 'mataPelajaran', 'pengumpulan'])
            ->where('guru_id', $guru->id)
            ->where('kelas_id', $guru->kelas_id);

        // Filter tambahan kalau gurunya milih dari dropdown
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
        $guru  = auth()->user()->guru;

        // PROTEKSI: Jika Admin belum menset kelas untuk guru ini
        if (!$guru->kelas_id) {
            return redirect()->route('guru.tugas.index')
                ->with('error', 'Anda belum ditugaskan ke kelas manapun. Silakan hubungi Admin untuk mengatur Tugas Kelas Anda.');
        }

        $mapel = MataPelajaran::where('guru_id', $guru->id)->get();
        
        // Ambil kelas yang HANYA diajar oleh guru ini
        $kelas = Kelas::where('id', $guru->kelas_id)->get();

        return view('guru.tugas.create', compact('mapel', 'kelas'));
    }

    // ── STORE ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        // Proteksi ganda di bagian simpan
        if (!$guru->kelas_id) {
            return redirect()->back()->with('error', 'Tugas kelas Anda belum diatur.');
        }

        $request->validate([
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'required',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id'          => 'required|in:' . $guru->kelas_id, // Kunci validasi harus sesuai kelas guru
            'deadline'          => 'required|date',
            'status'            => 'required|in:aktif,draft',
            'file'              => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        // Validasi soal kalau tipe CBT
        if ($request->tipe === 'cbt') {
            $rules['soal']                 = 'required|array|min:1';
            $rules['soal.*.soal']          = 'required|string';
            $rules['soal.*.pilihan_a']     = 'required|string|max:255';
            $rules['soal.*.pilihan_b']     = 'required|string|max:255';
            $rules['soal.*.pilihan_c']     = 'required|string|max:255';
            $rules['soal.*.pilihan_d']     = 'required|string|max:255';
            $rules['soal.*.jawaban_benar'] = 'required|in:A,B,C,D';
            $rules['soal.*.gambar_soal']   = 'nullable|image|max:2048';
        }

        $request->validate($rules);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas/file', 'public');
        }

        Tugas::create([
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id'          => $guru->kelas_id, // Kunci otomatis pakai data dari profil Guru
            'guru_id'           => $guru->id,
            'deadline'          => $request->deadline,
            'status'            => $request->status,
            'file'              => $filePath,
        ]);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dibuat.');
    }

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

        $guru  = auth()->user()->guru;
        $mapel = MataPelajaran::where('guru_id', $guru->id)->get();

        // Cuma ambil kelas yang ditugaskan ke guru ini
        $kelas = Kelas::where('id', $guru->kelas_id)->get();

        // Load soal kalau CBT
        $tugas->load('pertanyaans');

        return view('guru.tugas.edit', compact('tugas', 'mapel', 'kelas'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────
    public function update(Request $request, Tugas $tugas)
    {
        $this->authorize_guru($tugas);
        
        $guru = auth()->user()->guru;

        $rules = [
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'required',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id'          => 'required|in:' . $guru->kelas_id, // Kunci validasi
            'deadline'          => 'required|date',
            'status'            => 'required|in:aktif,draft',
            'file'              => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
        ];

        $request->validate($rules);

        $data = [
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id'          => $guru->kelas_id, // Tetap dikunci saat update
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

    /**
     * Halaman daftar & kelola soal CBT
     */
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

    /**
     * Simpan soal baru ke tugas CBT yang sudah ada
     */
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

    /**
     * Update soal CBT
     */
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

    /**
     * Hapus soal CBT
     */
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

        // Hapus gambar soal kalau CBT
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