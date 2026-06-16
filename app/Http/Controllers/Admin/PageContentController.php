<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class PageContentController extends Controller
{
    // Menampilkan semua daftar halaman statis
    public function index()
    {
        $pages = PageContent::all();
        return view('admin.content.index', compact('pages'));
    }

    // Membuka form tambah rute halaman baru
    public function create()
    {
        return view('admin.content.create');
    }

    // Menyimpan data rute halaman baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'slug'    => 'required|unique:page_contents,slug',
            'title'   => 'required',
            'content' => 'required',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'slug'    => $request->slug,
            'title'   => $request->title,
            'content' => $request->content
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        PageContent::create($data);

        return redirect()->route('admin.content.index')->with('success', 'Halaman baru berhasil dibuat!');
    }

    // Membuka form edit konten berdasarkan slug (otomatis buat jika data kosong)
    public function edit($slug)
    {
        $page = PageContent::firstOrCreate(
            ['slug' => $slug],
            [
                'title' => ucwords(str_replace('-', ' ', $slug)),
                'content' => '<p>Konten untuk ' . $slug . ' belum diisi.</p>'
            ]
        );

        return view('admin.content.edit', compact('page'));
    }

    // Memperbarui isi teks/gambar halaman ke database
    public function update(Request $request, $slug)
    {
        $request->validate([
            'content' => 'required',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $page = PageContent::where('slug', $slug)->firstOrFail();
        $data = [
            'content' => $request->input('content')
        ];

        if ($request->hasFile('image')) {
            if ($page->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($page->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($page->image);
            }
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        $page->update($data);

        return redirect()->route('admin.content.index')->with('success', 'Konten halaman berhasil diperbarui!');
    }

    // Fitur Core: Menerima upload gambar dari CKEditor ke server lokal
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $originName = $file->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            
            // Generate nama berkas unik memakai timestamp
            $fileName = $fileName . '_' . time() . '.' . $extension;
            
            // Simpan gambar ke folder public/uploads/images
            $file->move(public_path('uploads/images'), $fileName);
    
            $url = asset('uploads/images/' . $fileName);
    
            return response()->json([
                'fileName' => $fileName, 
                'uploaded' => 1, 
                'url'      => $url
            ]);
        }
    }
}