<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function index()
    {
        $prestasi = Prestasi::with('siswa.user')->orderByDesc('tanggal')->get();
        return view('admin.prestasi.index', compact('prestasi'));
    }

    public function create()
    {
        $siswa = Siswa::with('user')->get();
        return view('admin.prestasi.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lomba'    => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'tingkat'       => 'required|in:sekolah,kecamatan,kota,provinsi,nasional,internasional',
            'juara'         => 'required|in:1,2,3,harapan_1,harapan_2,harapan_3,finalis,peserta',
            'tanggal'       => 'required|date',
            'bidang'        => 'nullable|string|max:100',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_published'  => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('prestasi', 'public');
        }

        Prestasi::create($data);

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil ditambahkan.');
    }

    public function edit(Prestasi $prestasi)
    {
        $siswa = Siswa::with('user')->get();
        return view('admin.prestasi.edit', compact('prestasi', 'siswa'));
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $request->validate([
            'nama_lomba'    => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'tingkat'       => 'required|in:sekolah,kecamatan,kota,provinsi,nasional,internasional',
            'juara'         => 'required|in:1,2,3,harapan_1,harapan_2,harapan_3,finalis,peserta',
            'tanggal'       => 'required|date',
            'bidang'        => 'nullable|string|max:100',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_published'  => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($prestasi->foto && Storage::disk('public')->exists($prestasi->foto)) {
                Storage::disk('public')->delete($prestasi->foto);
            }
            $data['foto'] = $request->file('foto')->store('prestasi', 'public');
        }

        $prestasi->update($data);

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        if ($prestasi->foto && Storage::disk('public')->exists($prestasi->foto)) {
            Storage::disk('public')->delete($prestasi->foto);
        }
        $prestasi->delete();

        return redirect()->route('admin.prestasi.index')->with('success', 'Data prestasi berhasil dihapus.');
    }
}
