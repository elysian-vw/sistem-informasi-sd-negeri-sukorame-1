<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with(['user', 'kelas']); // Load relasi kelas

        if ($request->filled('cari')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cari . '%');
            })->orWhere('nip', 'like', '%' . $request->cari . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $guru        = $query->orderByDesc('id')->paginate(15)->withQueryString();
        $totalGuru   = Guru::count();
        $guruAktif   = Guru::where('status', 'aktif')->count();
        $kelasList   = Kelas::orderBy('tingkat')->get();
        $mapelList   = ['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS', 'PJOK', 'SBdP', 'PAI', 'PKn', 'Bahasa Inggris', 'Mulok'];
        $jabatanList = ['Guru Kelas', 'Guru Mapel', 'Kepala Sekolah', 'Wakil Kepala Sekolah', 'Kepala TU', 'Operator Sekolah', 'Bendahara Sekolah', 'Staf Perpustakaan', 'Petugas UKS', 'Penjaga Sekolah'];

        return view('admin.guru.index', compact('guru', 'totalGuru', 'guruAktif', 'kelasList', 'mapelList', 'jabatanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'nullable|string|min:6',
            'nip'            => 'nullable|string|max:30|unique:guru,nip',
            'kelas_id'       => 'nullable|exists:kelas,id', // Diubah jadi nullable jika bukan wali kelas
            'mata_pelajaran' => 'nullable|string|max:255',
            'jabatan'        => 'nullable|string|max:255',
            'status'         => 'required|in:aktif,tidak_aktif',
            'no_hp'          => 'nullable|string|max:20',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('guru/foto', 'public');
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password ?? '12345678'),
                'role'     => 'guru',
                'no_hp'    => $request->no_hp,
                'foto'     => $fotoPath,
            ]);

            Guru::create([
                'user_id'        => $user->id,
                'kelas_id'       => $request->kelas_id,
                'nip'            => $request->nip,
                'mata_pelajaran' => $request->mata_pelajaran,
                'jabatan'        => $request->jabatan,
                'status'         => $request->status,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru dan kelas tugas berhasil ditambahkan.');
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $guru->user_id,
            'nip'            => 'nullable|string|max:30|unique:guru,nip,' . $guru->id,
            'kelas_id'       => 'nullable|exists:kelas,id',
            'mata_pelajaran' => 'nullable|string|max:255',
            'jabatan'        => 'nullable|string|max:255',
            'status'         => 'required|in:aktif,tidak_aktif',
            'no_hp'          => 'nullable|string|max:20',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request, $guru) {
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
            ];

            if ($request->hasFile('foto')) {
                if ($guru->user->foto) Storage::disk('public')->delete($guru->user->foto);
                $userData['foto'] = $request->file('foto')->store('guru/foto', 'public');
            }

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $guru->user->update($userData);

            $guru->update([
                'nip'            => $request->nip,
                'kelas_id'       => $request->kelas_id,
                'mata_pelajaran' => $request->mata_pelajaran,
                'jabatan'        => $request->jabatan,
                'status'         => $request->status,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            if ($guru->user?->foto) Storage::disk('public')->delete($guru->user->foto);
            $guru->user?->delete();
            $guru->delete();
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }

    public function export()
    {
        $guru = Guru::with(['user', 'kelas'])->orderByDesc('id')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data_guru.csv"',
        ];

        $callback = function () use ($guru) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nama', 'Email', 'No HP', 'NIP', 'Kelas Tugas', 'Mata Pelajaran', 'Status']);
            foreach ($guru as $g) {
                fputcsv($file, [
                    $g->user?->name,
                    $g->user?->email,
                    $g->user?->no_hp,
                    $g->nip,
                    $g->kelas?->nama_kelas ?? '-', // Tambahan pada export
                    $g->mata_pelajaran,
                    $g->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048']);

        $rows     = array_map('str_getcsv', file($request->file('file')->getRealPath()));
        array_shift($rows);
        $berhasil = 0;
        $gagal    = 0;

        foreach ($rows as $row) {
            if (count($row) < 4) { $gagal++; continue; }
            [$nama, $email, $nip, $mapel] = $row;

            if (User::where('email', trim($email))->exists()) { $gagal++; continue; }

            try {
                DB::transaction(function () use ($nama, $email, $nip, $mapel) {
                    $user = User::create([
                        'name'     => trim($nama),
                        'email'    => trim($email),
                        'password' => Hash::make('12345678'),
                        'role'     => 'guru',
                    ]);
                    Guru::create([
                        'user_id'        => $user->id,
                        'kelas_id'       => null, // Diisi manual oleh admin setelah import
                        'nip'            => trim($nip),
                        'mata_pelajaran' => trim($mapel),
                        'status'         => 'aktif',
                    ]);
                });
                $berhasil++;
            } catch (\Exception $e) {
                $gagal++;
            }
        }

        return redirect()->route('admin.guru.index')
            ->with('success', "Import selesai: {$berhasil} berhasil, {$gagal} gagal. (Pastikan untuk mengedit kelas tugas pada guru yang diimport).");
    }
}