<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekskul;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EkskulController extends Controller
{
    public function index()
    {
        $ekskul = Ekskul::with('pembina.user')->get();
        return view('admin.ekskul.index', compact('ekskul'));
    }

    public function create()
    {
        $guru = Guru::with('user')->get();
        return view('admin.ekskul.create', compact('guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('ekskul', 'public');
        }

        Ekskul::create($data);

        return redirect()->route('admin.ekskul.index')->with('success', 'Ekskul berhasil ditambahkan.');
    }

    public function edit(Ekskul $ekskul)
    {
        $guru = Guru::with('user')->get();
        return view('admin.ekskul.edit', compact('ekskul', 'guru'));
    }

    public function update(Request $request, Ekskul $ekskul)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('foto')) {
            if ($ekskul->foto) Storage::disk('public')->delete($ekskul->foto);
            $data['foto'] = $request->file('foto')->store('ekskul', 'public');
        }

        $ekskul->update($data);

        return redirect()->route('admin.ekskul.index')->with('success', 'Ekskul berhasil diperbarui.');
    }

    public function destroy(Ekskul $ekskul)
    {
        if ($ekskul->foto) Storage::disk('public')->delete($ekskul->foto);
        $ekskul->delete();

        return redirect()->route('admin.ekskul.index')->with('success', 'Ekskul berhasil dihapus.');
    }
}
