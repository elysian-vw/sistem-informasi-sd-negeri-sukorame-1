<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GaleriController extends Controller
{
    public function index()
    {
        $galeries = Galeri::orderBy('urutan')->orderBy('created_at', 'desc')->get();
        return view('admin.galeri.index', compact('galeries'));
    }

    public function create()
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe'      => 'required|in:foto,video',
            'kategori'  => 'nullable|string',
            'tanggal'   => 'nullable|date',
            'file_path' => 'required_if:tipe,foto|image|mimes:jpg,jpeg,png|max:5120',
            'url_video' => 'required_if:tipe,video|nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['file_path', 'thumbnail']);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('galeri/foto', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('galeri/thumbnail', 'public');
        }

        Galeri::create($data);

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function edit(Galeri $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe'      => 'required|in:foto,video',
            'kategori'  => 'nullable|string',
            'tanggal'   => 'nullable|date',
            'file_path' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'url_video' => 'required_if:tipe,video|nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['file_path', 'thumbnail']);

        if ($request->hasFile('file_path')) {
            if ($galeri->file_path && Storage::disk('public')->exists($galeri->file_path)) {
                Storage::disk('public')->delete($galeri->file_path);
            }
            $data['file_path'] = $request->file('file_path')->store('galeri/foto', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($galeri->thumbnail && Storage::disk('public')->exists($galeri->thumbnail)) {
                Storage::disk('public')->delete($galeri->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('galeri/thumbnail', 'public');
        }

        $galeri->update($data);

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Galeri $galeri)
    {
        if ($galeri->file_path && Storage::disk('public')->exists($galeri->file_path)) {
            Storage::disk('public')->delete($galeri->file_path);
        }
        if ($galeri->thumbnail && Storage::disk('public')->exists($galeri->thumbnail)) {
            Storage::disk('public')->delete($galeri->thumbnail);
        }
        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Galeri berhasil dihapus.');
    }
}
