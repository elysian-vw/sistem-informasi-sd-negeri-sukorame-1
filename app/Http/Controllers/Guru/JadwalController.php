<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;

class JadwalController extends Controller
{
    public function index()
    {
        $guru  = auth()->user()->guru;
        $urutan = ['senin','selasa','rabu','kamis','jumat','sabtu'];

        // Ambil jadwal kelas guru, aktif, urutkan per hari & jam
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'guru'])
            ->where('kelas_id', $guru->kelas_id)
            ->where('guru_id', $guru->id)
            ->where('is_aktif', 1)
            ->get()
            ->sortBy(fn($j) => [array_search($j->hari, $urutan), $j->jam_ke])
            ->groupBy('hari');

        // Urutkan group sesuai urutan hari
        $jadwalTerurut = collect();
        foreach ($urutan as $hari) {
            if ($jadwal->has($hari)) {
                $jadwalTerurut[$hari] = $jadwal[$hari];
            }
        }

        $hariIni = strtolower(now()->locale('id')->dayName);
        // Normalize: Senin→senin, dll.
        $hariMap = [
            'monday'    => 'senin',
            'tuesday'   => 'selasa',
            'wednesday' => 'rabu',
            'thursday'  => 'kamis',
            'friday'    => 'jumat',
            'saturday'  => 'sabtu',
            'sunday'    => 'minggu',
        ];
        $hariIni = $hariMap[strtolower(now()->format('l'))] ?? '';

        return view('guru.jadwal.index', compact('jadwalTerurut', 'hariIni'));
    }
}