<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\{Raport, Siswa, Nilai};

class RaportWaliController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $anakList = Siswa::where('wali_murid_id', $user->id)->with('kelas')->get();

        $selectedId = request('anak');
        $anakUtama  = $selectedId
            ? $anakList->firstWhere('id', $selectedId)
            : $anakList->first();

        $raportList = collect();
        if ($anakUtama) {
            $raportList = Raport::where('siswa_id', $anakUtama->id)
                ->where('status', 'terbit')
                ->orderByDesc('tahun_ajaran')
                ->orderByDesc('semester')
                ->get();
        }

        return view('wali.raport.index', compact('anakList', 'anakUtama', 'raportList'));
    }

    public function show(Raport $raport)
    {
        // Pastikan raport milik anak wali ini
        $user     = auth()->user();
        $anakList = Siswa::where('wali_murid_id', $user->id)->pluck('id');

        abort_unless(
            $anakList->contains($raport->siswa_id) && $raport->status === 'terbit',
            403
        );

        $raport->load('siswa.kelas');

        // Filter nilai hanya untuk mata pelajaran sesuai tingkat kelas siswa
        $nilai = Nilai::where('siswa_id', $raport->siswa_id)
            ->where('semester', $raport->semester)
            ->where('tahun_ajaran', $raport->tahun_ajaran)
            ->with([
                'mataPelajaran' => function ($query) use ($raport) {
                    $query->where('tingkat', $raport->siswa->kelas->tingkat);
                }
            ])
            ->get()
            ->filter(function ($item) {
                return $item->mataPelajaran !== null;
            })
            ->values();

        return view('wali.raport.show', compact('raport', 'nilai'));
    }
}