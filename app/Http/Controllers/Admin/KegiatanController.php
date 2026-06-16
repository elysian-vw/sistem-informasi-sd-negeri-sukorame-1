<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::latest()->paginate(10);
        return view('admin.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        return view('admin.kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'          => 'required|string|max:255',
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'    => 'nullable',
            'waktu_selesai'  => 'nullable',
            'tempat'         => 'nullable|string|max:255',
            'deskripsi'      => 'nullable|string',
            'kategori'       => 'required|in:upacara,penilaian,perpisahan,libur,ppdb,ekskul,rapat,lainnya',
            'sasaran'        => 'required|in:semua,guru,siswa,wali_murid',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();

        Kegiatan::create($data);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        return view('admin.kegiatan.edit', compact('kegiatan'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'judul'          => 'required|string|max:255',
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'waktu_mulai'    => 'nullable',
            'waktu_selesai'  => 'nullable',
            'tempat'         => 'nullable|string|max:255',
            'deskripsi'      => 'nullable|string',
            'kategori'       => 'required|in:upacara,penilaian,perpisahan,libur,ppdb,ekskul,rapat,lainnya',
            'sasaran'        => 'required|in:semua,guru,siswa,wali_murid',
        ]);

        $kegiatan->update($request->all());

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
