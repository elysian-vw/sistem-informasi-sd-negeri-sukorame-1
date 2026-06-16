<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sarana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SaranaController extends Controller
{
    public function index()
    {
        $sarana = Sarana::orderBy('urutan')->get();
        return view('admin.sarana.index', compact('sarana'));
    }

    public function create()
    {
        return view('admin.sarana.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah'    => 'required|integer|min:1',
            'kondisi'   => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'urutan'    => 'nullable|integer',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('sarana', 'public');
        }

        Sarana::create($data);

        return redirect()->route('admin.sarana.index')->with('success', 'Sarana berhasil ditambahkan.');
    }

    public function edit(Sarana $sarana)
    {
        return view('admin.sarana.edit', compact('sarana'));
    }

    public function update(Request $request, Sarana $sarana)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jumlah'    => 'required|integer|min:1',
            'kondisi'   => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'urutan'    => 'nullable|integer',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($sarana->foto && Storage::disk('public')->exists($sarana->foto)) {
                Storage::disk('public')->delete($sarana->foto);
            }
            $data['foto'] = $request->file('foto')->store('sarana', 'public');
        }

        $sarana->update($data);

        return redirect()->route('admin.sarana.index')->with('success', 'Sarana berhasil diperbarui.');
    }

    public function destroy(Sarana $sarana)
    {
        if ($sarana->foto && Storage::disk('public')->exists($sarana->foto)) {
            Storage::disk('public')->delete($sarana->foto);
        }
        $sarana->delete();

        return redirect()->route('admin.sarana.index')->with('success', 'Sarana berhasil dihapus.');
    }
}
