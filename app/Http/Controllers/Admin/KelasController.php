<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with(['waliKelas', 'siswa']);

        if ($request->filled('cari')) {
            $query->where('nama_kelas', 'like', '%' . $request->cari . '%')
                  ->orWhere('rombel', 'like', '%' . $request->cari . '%')
                  ->orWhere('ruang_kelas', 'like', '%' . $request->cari . '%');
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kelas           = $query->orderBy('tingkat')->orderBy('rombel')->paginate(15)->withQueryString();
        $totalRombel     = Kelas::count();
        $totalSiswa      = \App\Models\Siswa::count();
        $kelasAktif      = Kelas::where('status', 'aktif')->count();
        $totalWaliKelas  = Kelas::whereNotNull('wali_kelas_id')->distinct('wali_kelas_id')->count();
        $guruList        = User::where('role', 'guru')->orderBy('name')->get();

        return view('admin.kelas.index', compact(
            'kelas', 'totalRombel', 'totalSiswa',
            'kelasAktif', 'totalWaliKelas', 'guruList'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas'    => 'required|string|max:20',
            'tingkat'       => 'required|integer|min:1|max:6',
            'rombel'        => 'nullable|string|max:10',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'kapasitas'     => 'required|integer|min:1|max:60',
            'ruang_kelas'   => 'nullable|string|max:50',
            'status'        => 'required|in:aktif,tidak_aktif',
        ]);

        Kelas::create($request->only([
            'nama_kelas', 'tingkat', 'rombel',
            'wali_kelas_id', 'kapasitas', 'ruang_kelas', 'status',
        ]));

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas'    => 'required|string|max:20',
            'tingkat'       => 'required|integer|min:1|max:6',
            'rombel'        => 'nullable|string|max:10',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'kapasitas'     => 'required|integer|min:1|max:60',
            'ruang_kelas'   => 'nullable|string|max:50',
            'status'        => 'required|in:aktif,tidak_aktif',
        ]);

        $kelas = Kelas::findOrFail($id); // Cari manual pakai ID
        $kelas->update($request->all());

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        // Kosongkan kelas_id siswa yang ada di kelas ini dulu
        $kelas->siswa()->update(['kelas_id' => null]);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}