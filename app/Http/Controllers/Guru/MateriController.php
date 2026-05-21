<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{Materi, Kelas, MataPelajaran};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    // ── Auth helper ───────────────────────────────────────────────────────────
    private function authorizeGuru(Materi $materi): void
    {
        if ((int) $materi->guru_id !== (int) auth()->user()->guru->id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
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

        return null; // guru mapel umum tanpa kelas → diblokir
    }

    // ── INDEX ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $guru       = auth()->user()->guru;
        $mapel      = MataPelajaran::where('guru_id', $guru->id)->get();
        $kelasAkses = $this->resolveKelas($guru);

        $query = Materi::with(['mataPelajaran', 'kelas'])
            ->where('guru_id', $guru->id);

        if (is_null($kelasAkses)) {
            // Guru mapel umum tanpa kelas — blokir, tampilkan kosong
            $query->whereRaw('1 = 0');
            $kelas = collect();
        } elseif ($guru->kelas_id) {
            // Guru punya kelas tetap — filter ke kelasnya saja
            $query->where('kelas_id', $guru->kelas_id);
            $kelas = $kelasAkses;
        } else {
            // Guru mulok — tampilkan semua materi lintas kelas
            $kelas = $kelasAkses;
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('mata_pelajaran_id')) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $materi = $query->latest()->paginate(12)->withQueryString();

        return view('guru.materi.index', compact('materi', 'mapel', 'kelas'));
    }

    // ── CREATE ────────────────────────────────────────────────────────────────
    public function create()
    {
        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru);

        if (is_null($kelasAkses)) {
            return redirect()->route('guru.materi.index')
                ->with('error', 'Anda belum memiliki kelas. Silakan hubungi Admin untuk penugasan kelas.');
        }

        if ($kelasAkses->isEmpty()) {
            return redirect()->route('guru.materi.index')
                ->with('error', 'Data kelas belum tersedia di sistem. Silakan hubungi Admin.');
        }

        // Filter mapel sesuai tingkat jika guru punya kelas tetap
        $kelasTarget = Kelas::where('wali_kelas_id', auth()->id())->first()
            ?? ($guru->kelas_id ? Kelas::find($guru->kelas_id) : null);

        $mapel = $kelasTarget
            ? MataPelajaran::where('guru_id', $guru->id)->where('tingkat', $kelasTarget->tingkat)->get()
            : MataPelajaran::where('guru_id', $guru->id)->get();

        $kelas = $kelasAkses;

        return view('guru.materi.create', compact('mapel', 'kelas'));
    }

    // ── STORE ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru);

        if (is_null($kelasAkses)) {
            abort(403, 'Anda belum memiliki kelas.');
        }

        // Pastikan kelas_id yang dipilih ada dalam daftar yang diizinkan
        abort_unless(
            $kelasAkses->contains('id', (int) $request->kelas_id),
            403,
            'Kelas tidak valid.'
        );

        $request->validate([
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id'          => 'required|exists:kelas,id',
            'tipe'              => 'required|in:materi,tugas,uts,uas',
            'format_media'      => 'required|in:file,link',
            'link_video'        => 'nullable|url|required_if:format_media,link',
            'file'              => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480|required_if:format_media,file',
        ]);

        $filePath = null;
        if ($request->hasFile('file') && $request->format_media === 'file') {
            $filePath = $request->file('file')->store('materi/file', 'public');
        }

        Materi::create([
            'judul'             => $request->judul,
            'deskripsi'         => $request->deskripsi,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_id'          => $request->kelas_id, // dari form, bukan hardcode
            'guru_id'           => $guru->id,
            'tipe'              => $request->tipe,
            'link_video'        => $request->format_media === 'link' ? $request->link_video : null,
            'file'              => $filePath,
        ]);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    // ── SHOW ──────────────────────────────────────────────────────────────────
    public function show(Materi $materi)
    {
        $this->authorizeGuru($materi);
        $materi->load(['mataPelajaran', 'kelas']);

        return view('guru.materi.show', compact('materi'));
    }

    // ── EDIT ──────────────────────────────────────────────────────────────────
    public function edit(Materi $materi)
    {
        $this->authorizeGuru($materi);

        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru) ?? Kelas::all(); // fallback aman saat edit

        $kelasTarget = Kelas::where('wali_kelas_id', auth()->id())->first()
            ?? ($guru->kelas_id ? Kelas::find($guru->kelas_id) : null);

        $mapel = $kelasTarget
            ? MataPelajaran::where('guru_id', $guru->id)->where('tingkat', $kelasTarget->tingkat)->get()
            : MataPelajaran::where('guru_id', $guru->id)->get();

        $kelas = $kelasAkses;

        return view('guru.materi.edit', compact('materi', 'mapel', 'kelas'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────
    public function update(Request $request, Materi $materi)
    {
        $this->authorizeGuru($materi);

        $guru       = auth()->user()->guru;
        $kelasAkses = $this->resolveKelas($guru) ?? Kelas::all();

        abort_unless(
            $kelasAkses->contains('id', (int) $request->kelas_id),
            403,
            'Kelas tidak valid.'
        );

        $request->validate([
            'judul'             => 'required|string|max:255',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id'          => 'required|exists:kelas,id',
            'tipe'              => 'required|in:materi,tugas,uts,uas',
            'format_media'      => 'required|in:file,link',
            'link_video'        => 'nullable|url|required_if:format_media,link',
            'file'              => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'mata_pelajaran_id', 'tipe']);
        $data['kelas_id'] = $request->kelas_id; // dari form, bukan hardcode

        if ($request->format_media === 'link') {
            $data['link_video'] = $request->link_video;
            $data['file']       = null;
            if ($materi->file) {
                Storage::disk('public')->delete($materi->file);
            }
        } else {
            $data['link_video'] = null;
            if ($request->hasFile('file')) {
                if ($materi->file) {
                    Storage::disk('public')->delete($materi->file);
                }
                $data['file'] = $request->file('file')->store('materi/file', 'public');
            }
        }

        $materi->update($data);

        return redirect()->route('guru.materi.index')->with('success', 'Materi diperbarui.');
    }

    // ── DESTROY ───────────────────────────────────────────────────────────────
    public function destroy(Materi $materi)
    {
        $this->authorizeGuru($materi);

        if ($materi->file) {
            Storage::disk('public')->delete($materi->file);
        }

        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi dihapus.');
    }
}