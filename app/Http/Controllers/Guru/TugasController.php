<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{Tugas, PengumpulanTugas, Kelas, MataPelajaran};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    // Cek kepemilikan tugas biar nggak diacak-acak orang lain
    private function authorize_guru(Tugas $tugas)
    {
        $guruId = auth()->user()->guru->id;
        if ($tugas->guru_id !== $guruId) {
            abort(403, 'Bukan tugasmu, jangan asal akses.');
        }
    }

    public function index(Request $request)
    {
        // 1. Ambil data guru dari user yang login
        $user = auth()->user();
        
        // Pastikan user ini punya relasi ke table guru
        if (!$user->guru) {
            abort(403, 'Anda tidak terdaftar sebagai guru.');
        }

        $guru = $user->guru;

        // 2. Filter dropdown kelas: HANYA KELAS YANG DITUGASKAN KEPADA GURU INI
        $kelas = Kelas::where('id', $guru->kelas_id)->get();

        // 3. Ambil tugas: PAKSA filter berdasarkan guru_id
        $query = Tugas::with(['kelas', 'mataPelajaran', 'pengumpulan'])
            ->where('guru_id', $guru->id); // INI KUNCINYA

        // Filter tambahan dari form
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tugas = $query->latest()->paginate(10)->withQueryString();

        return view('guru.tugas.index', compact('tugas', 'kelas'));
    }
    
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

    public function edit(Tugas $tugas)
    {
        // Cek dulu, jangan asal masuk!
        $this->authorize_guru($tugas);
        
        $guru  = auth()->user()->guru;

        // Cuma ambil mapel yang emang diajar sama guru ini
        $mapel = MataPelajaran::where('guru_id', $guru->id)->get();

        // Cuma ambil kelas yang ditugaskan ke guru ini
        $kelas = Kelas::where('id', $guru->kelas_id)->get();

        return view('guru.tugas.edit', compact('tugas', 'mapel', 'kelas'));
    }

    public function update(Request $request, Tugas $tugas)
    {
        // Pastiin lagi ini tugas dia
        $this->authorize_guru($tugas);
        
        $guru = auth()->user()->guru;

        $request->validate([
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'required',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id'          => 'required|in:' . $guru->kelas_id, // Kunci validasi
            'deadline'          => 'required|date',
            'status'            => 'required|in:aktif,draft',
            'file'              => 'nullable|file|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        $data = [
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id'          => $guru->kelas_id, // Tetap dikunci saat update
            'deadline'          => $request->deadline,
            'status'            => $request->status,
        ];

        // Urusan file, jangan sampe numpuk di storage
        if ($request->hasFile('file')) {
            // Kalau ada file lama, hapus aja, menuh-menuhin disk doang
            if ($tugas->file) {
                Storage::disk('public')->delete($tugas->file);
            }
            $data['file'] = $request->file('file')->store('tugas/file', 'public');
        }

        $tugas->update($data);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil diupdate.');
    }

    // ── HALAMAN PENILAIAN TUGAS ───────────────────────────────────────────────
    public function penilaian(Tugas $tugas)
    {
        $this->authorize_guru($tugas);

        $siswaKelas = $tugas->kelas->siswa()->orderBy('nama_lengkap')->get();

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

    public function destroy(Tugas $tugas)
    {
        $this->authorize_guru($tugas);
        
        if ($tugas->file) {
            Storage::disk('public')->delete($tugas->file);
        }
        
        $tugas->delete();
        return redirect()->route('guru.tugas.index')->with('success', 'Tugas dihapus.');
    }
}