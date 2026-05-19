<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{Materi, Kelas, MataPelajaran};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    private function authorizeGuru(Materi $materi)
    {
        if ($materi->guru_id !== auth()->user()->guru->id) {
            abort(403, 'Anda tidak memiliki akses ke materi ini.');
        }
    }

    public function index(Request $request)
    {
        $guru  = auth()->user()->guru;
        $mapel = MataPelajaran::where('guru_id', $guru->id)->get();
        $kelas = Kelas::where('id', $guru->kelas_id)->get(); 

        $query = Materi::with(['mataPelajaran', 'kelas'])
            ->where('guru_id', $guru->id)
            ->where('kelas_id', $guru->kelas_id); 

        if ($request->filled('mata_pelajaran_id')) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        }
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        $materi = $query->latest()->paginate(12)->withQueryString();
        return view('guru.materi.index', compact('materi', 'mapel', 'kelas'));
    }

    public function create()
    {
        $guru  = auth()->user()->guru;
        $mapel = MataPelajaran::where('guru_id', $guru->id)->get();
        $kelas = Kelas::where('id', $guru->kelas_id)->get();

        return view('guru.materi.create', compact('mapel', 'kelas'));
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;

        $request->validate([
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tipe'              => 'required|in:materi,tugas,uts,uas', // Tipe sekarang menyimpan Kategori
            'format_media'      => 'required|in:file,link', // Format dipisah validasinya
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
            'kelas_id'          => $guru->kelas_id, 
            'guru_id'           => $guru->id,
            'tipe'              => $request->tipe, // Masuk ke DB sebagai 'materi'
            'link_video'        => $request->format_media === 'link' ? $request->link_video : null,
            'file'              => $filePath,
        ]);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function show(Materi $materi)
    {
        $this->authorizeGuru($materi);
        $materi->load(['mataPelajaran', 'kelas']);
        return view('guru.materi.show', compact('materi'));
    }

    public function edit(Materi $materi)
    {
        $this->authorizeGuru($materi);
        $guru  = auth()->user()->guru;
        $mapel = MataPelajaran::where('guru_id', $guru->id)->get();
        $kelas = Kelas::where('id', $guru->kelas_id)->get();

        return view('guru.materi.edit', compact('materi', 'mapel', 'kelas'));
    }

    public function update(Request $request, Materi $materi)
    {
        $this->authorizeGuru($materi);
        $guru = auth()->user()->guru;

        $request->validate([
            'judul'             => 'required|string|max:255',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tipe'              => 'required|in:materi,tugas,uts,uas',
            'format_media'      => 'required|in:file,link',
            'link_video'        => 'nullable|url|required_if:format_media,link',
            'file'              => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:20480',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'mata_pelajaran_id', 'tipe']);
        $data['kelas_id'] = $guru->kelas_id;

        if ($request->format_media === 'link') {
            $data['link_video'] = $request->link_video;
            $data['file'] = null;
            if ($materi->file) Storage::disk('public')->delete($materi->file);
        } else {
            $data['link_video'] = null;
            if ($request->hasFile('file')) {
                if ($materi->file) Storage::disk('public')->delete($materi->file);
                $data['file'] = $request->file('file')->store('materi/file', 'public');
            }
        }

        $materi->update($data);
        return redirect()->route('guru.materi.index')->with('success', 'Materi diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        $this->authorizeGuru($materi);
        if ($materi->file) Storage::disk('public')->delete($materi->file);
        $materi->delete();
        return redirect()->route('guru.materi.index')->with('success', 'Materi dihapus.');
    }
}