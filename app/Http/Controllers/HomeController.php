<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengumuman;
use App\Models\PageContent; // Import model yang baru

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'siswa'  => Siswa::count(),
            'guru'   => Guru::count(),
            'kelas'  => Kelas::count(),
            'mapel'  => MataPelajaran::count(),
        ];

        // Ambil 3 pengumuman terbaru
        $pengumuman = Pengumuman::latest()->take(3)->get();

        // Ambil semua konten halaman untuk mapping
        $contents = PageContent::all();
        
        // Helper untuk mencari konten berdasarkan slug
        $getContent = function($slug, $default = '') use ($contents) {
            $page = $contents->where('slug', $slug)->first();
            return $page ? $page->content : $default;
        };

        // Construct object agar welcome.blade.php tidak error
        $setting = (object) [
            'hero_title'      => $getContent('home-hero-title', 'SD Negeri Sukorame 1 Kediri'),
            'hero_subtitle'   => $getContent('home-hero-subtitle', 'Mewujudkan generasi cerdas, berkarakter luhur, berbudaya, dan unggul dalam prestasi.'),
            'sambutan_nama'   => $getContent('home-sambutan-nama', 'Kepala Sekolah SDN Sukorame 1'),
            'sambutan_konten' => $getContent('home-sambutan-konten', 'Selamat datang di website resmi kami.'),
            'visi'            => $getContent('profil-visi-misi', 'Menjadi lembaga pendidikan yang unggul...'),
            'misi'            => $getContent('profil-misi', '1. Menyelenggarakan proses pembelajaran...')
        ];

        $pageTitle = $setting->hero_title . ' - Sistem Informasi Sekolah';

        return view('welcome', compact('stats', 'pengumuman', 'pageTitle', 'setting'));
    }
}