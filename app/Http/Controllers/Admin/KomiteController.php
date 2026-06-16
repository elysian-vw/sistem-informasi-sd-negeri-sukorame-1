<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Komite;
use Illuminate\Http\Request;

class KomiteController extends Controller
{
    public function index()
    {
        $komite = Komite::orderBy('urutan')->get();
        return view('admin.komite.index', compact('komite'));
    }

    public function create()
    {
        return view('admin.komite.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'unsur'   => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'urutan'  => 'nullable|integer',
            'aktif'   => 'required|boolean',
        ]);

        Komite::create($request->all());

        return redirect()->route('admin.komite.index')->with('success', 'Data anggota komite berhasil ditambahkan.');
    }

    public function edit(Komite $komite)
    {
        return view('admin.komite.edit', compact('komite'));
    }

    public function update(Request $request, Komite $komite)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'unsur'   => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'urutan'  => 'nullable|integer',
            'aktif'   => 'required|boolean',
        ]);

        $komite->update($request->all());

        return redirect()->route('admin.komite.index')->with('success', 'Data anggota komite berhasil diperbarui.');
    }

    public function destroy(Komite $komite)
    {
        $komite->delete();
        return redirect()->route('admin.komite.index')->with('success', 'Data anggota komite berhasil dihapus.');
    }
}
