<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Guru;
use App\Models\Komite;

class ProfilController extends Controller
{
    private function baseData(): array
    {
        return [
            'sekolah' => Sekolah::first(),
        ];
    }

    // Contoh di ProfilController.php
    public function visiMisi()
    {
        // Ambil konten dari database
        $visi = \App\Models\PageContent::where('slug', 'profil-visi-misi')->first();
        $misi = \App\Models\PageContent::where('slug', 'profil-misi')->first();
        $tujuan = \App\Models\PageContent::where('slug', 'profil-tujuan')->first();

        $base = $this->baseData();

        return view('public.profil.visi-misi', array_merge($base, [
            'pageTitle' => 'Visi & Misi — ' . ($base['sekolah']->nama_sekolah ?? 'Sekolah'),
            'visi' => $visi,
            'misi' => $misi,
            'tujuan' => $tujuan,
        ]));
    }

    public function sejarah()
    {
        $sejarah = \App\Models\PageContent::where('slug', 'profil-sejarah')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.profil.sejarah', array_merge($this->baseData(), [
            'pageTitle' => 'Sejarah Sekolah — ' . $schoolName,
            'sejarah' => $sejarah
        ]));
    }

    public function struktur()
    {
        $sekolah = Sekolah::first();
        $stats = [
            'guru' => Guru::where('status', 'aktif')->whereHas('user', fn($q) => $q->where('role', 'guru'))->count(),
            'tendik' => Guru::where('status', 'aktif')->whereHas('user', fn($q) => $q->where('role', 'tendik'))->count(),
            'kelas' => \App\Models\Kelas::count(),
        ];

        // Ambil data guru spesifik berdasarkan jabatan
        $kepsek    = Guru::with('user')->where('jabatan', 'Kepala Sekolah')->first();
        $wakasek   = Guru::with('user')->where('jabatan', 'Wakil Kepala Sekolah')->first();
        $tu        = Guru::with('user')->where('jabatan', 'Kepala TU')->first();
        $operator  = Guru::with('user')->where('jabatan', 'Operator Sekolah')->first();
        $bendahara = Guru::with('user')->where('jabatan', 'Bendahara Sekolah')->first();
        $komite    = Komite::where('jabatan', 'Ketua Komite')->first(); // Komite biasanya dari tabel komite

        $waliKelas = Guru::where('status', 'aktif')->whereNotNull('kelas_id')->with(['user', 'kelas'])->orderBy('kelas_id')->get();
        
        $tendik = Guru::with('user')
            ->where('status', 'aktif')
            ->whereIn('jabatan', ['Staf Perpustakaan', 'Petugas UKS', 'Penjaga Sekolah', 'Petugas Kebersihan', 'Satuan Pengamanan (Satpam)'])
            ->get();

        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.profil.struktur', array_merge($this->baseData(), [
            'pageTitle' => 'Struktur Organisasi — ' . $schoolName,
            'sekolah' => $sekolah,
            'stats' => $stats,
            'kepsek' => $kepsek,
            'wakasek' => $wakasek,
            'tu' => $tu,
            'operator' => $operator,
            'bendahara' => $bendahara,
            'komite' => $komite,
            'waliKelas' => $waliKelas,
            'tendik' => $tendik,
        ]));
    }


    public function komite()
    {
        $komite = Komite::aktif()->orderBy('urutan')->get();
        $sekolah = $this->baseData()['sekolah'];
        $pageTitle = 'Komite Sekolah — ' . ($sekolah->nama_sekolah ?? config('app.name'));

        return view('public.profil.komite', array_merge($this->baseData(), compact('komite', 'pageTitle')));
    }

    public function guru()
    {
        // nama ada di tabel users, filter role=guru supaya siswa tidak ikut terpilih
        $guru = Guru::with('user')
                    ->where('status', 'aktif')
                    ->whereHas('user', fn($q) => $q->where('role', 'guru'))
                    ->get()
                    ->sortBy(fn($g) => $g->user?->name);

        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.profil.guru', array_merge($this->baseData(), [
            'pageTitle' => 'Profil Guru & Karyawan — ' . $schoolName,
            'guru'      => $guru,
        ]));
    }

    public function sarana()
    {
        $sarana = \App\Models\Sarana::orderBy('urutan')->get();
        $sekolah = $this->baseData()['sekolah'];
        $pageTitle = 'Sarana & Prasarana — ' . ($sekolah->nama_sekolah ?? config('app.name'));
        return view('public.profil.sarana', array_merge($this->baseData(), compact('sarana', 'pageTitle')));
    }

    public function akreditasi()
    {
        $sekolah   = \App\Models\Sekolah::first();
        $pageTitle = 'Akreditasi — ' . ($sekolah->nama_sekolah ?? config('app.name'));
        return view('public.profil.akreditasi', compact('sekolah', 'pageTitle'));
    }

    public function prestasi()
    {
        $semuaPrestasi = \App\Models\Prestasi::with('siswa.user')
            ->where('is_published', true)
            ->orderByDesc('tanggal')
            ->get();
            
        // Map data agar sesuai dengan format di view
        $prestasi = $semuaPrestasi->map(function($p) {
            $level = 'lainnya';
            if($p->juara == '1') $level = 'emas';
            elseif($p->juara == '2') $level = 'perak';
            elseif($p->juara == '3') $level = 'perunggu';

            return (object)[
                'juara'   => is_numeric($p->juara) ? 'Juara ' . $p->juara : str_replace('_', ' ', ucfirst($p->juara)),
                'level'   => $level,
                'nama'    => $p->nama_lomba,
                'tahun'   => \Carbon\Carbon::parse($p->tanggal)->year,
                'tingkat' => 'Tingkat ' . ucfirst($p->tingkat),
                'peserta' => $p->siswa ? $p->siswa->user->name : 'Sekolah',
                'bidang'  => strtolower($p->bidang ?? 'umum'),
                'ico'     => $this->getBidangIcon($p->bidang),
                'foto'    => $p->foto
            ];
        });

        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.profil.prestasi', array_merge($this->baseData(), [
            'pageTitle' => 'Prestasi Sekolah — ' . $schoolName,
            'prestasi'  => $prestasi
        ]));
    }

    private function getBidangIcon($bidang)
    {
        $bidang = strtolower($bidang);
        if (str_contains($bidang, 'matematika') || str_contains($bidang, 'ipa') || str_contains($bidang, 'akademik')) return 'fa-calculator';
        if (str_contains($bidang, 'seni') || str_contains($bidang, 'tari') || str_contains($bidang, 'lukis')) return 'fa-palette';
        if (str_contains($bidang, 'olahraga') || str_contains($bidang, 'futsal') || str_contains($bidang, 'lari')) return 'fa-futbol';
        return 'fa-trophy';
    }
}