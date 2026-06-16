<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengumuman;
use App\Models\PageContent;
use App\Models\Prestasi;
use App\Models\Kegiatan;
use App\Models\Sekolah;
use App\Models\Keunggulan;

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
        $pengumuman = Pengumuman::where('is_aktif', true)->latest()->take(3)->get();

        // Ambil 4 prestasi terbaru
        $prestasi = Prestasi::where('is_published', true)->latest()->take(4)->get();

        // Ambil 6 kegiatan terbaru (Agenda)
        $agendas = Kegiatan::where('is_published', true)
            ->where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(6)
            ->get();

        // Fallback jika tidak ada agenda mendatang, ambil yang terakhir lewat
        if ($agendas->isEmpty()) {
            $agendas = Kegiatan::where('is_published', true)
                ->latest('tanggal_mulai')
                ->take(6)
                ->get()
                ->sortBy('tanggal_mulai');
        }

        $sekolah = Sekolah::first();

        // Ambil data keunggulan dari DB, urut berdasarkan kolom urutan
        $keunggulan = Keunggulan::where('is_aktif', true)->orderBy('urutan')->get();

        // Ambil semua konten halaman untuk mapping
        $contents = PageContent::all();

        // Helper untuk mencari konten berdasarkan slug
        $getContent = function ($slug, $default = '') use ($contents) {
            $page = $contents->where('slug', $slug)->first();
            return $page ? $page->content : $default;
        };

        // Construct object agar welcome.blade.php tidak error
        $setting = (object) [
            // Slide 1
            'hero_title'      => $getContent('home-hero-title', $sekolah?->nama_sekolah ?? 'Sekolah'),
            'hero_subtitle'   => $getContent('home-hero-subtitle', $sekolah?->slogan ?? 'Mewujudkan generasi cerdas, berkarakter, dan berprestasi.'),
            // Sambutan
            'sambutan_nama'   => $getContent('home-sambutan-nama', $sekolah?->nama_kepala_sekolah ?? 'Kepala Sekolah'),
            'sambutan_konten' => $getContent('home-sambutan-konten', $sekolah?->sambutan_kepsek ?? 'Selamat datang di website resmi kami.'),
            // Profil
            'visi'            => $getContent('profil-visi-misi', 'Menjadi lembaga pendidikan yang unggul...'),
            'misi'            => $getContent('profil-misi', '1. Menyelenggarakan proses pembelajaran...'),
            // Slide 2 — E-Learning SIMAS
            'slide2_judul'    => $getContent(
                'home-slide2-judul',
                'Belajar Kapanpun,<br><span style="color:#7DD3FC">Dimanapun bersama SIMAS</span>'
            ),
            'slide2_konten'   => $getContent(
                'home-slide2-konten',
                'Akses materi pelajaran, pantau nilai harian, absensi, tugas, dan raport digital — semua dalam satu platform terintegrasi.'
            ),
            // Slide 3 — PPDB
            'slide3_label'    => $getContent(
                'home-slide3-label',
                'PPDB ' . ($sekolah?->tahun_ajaran_aktif ?? date('Y') . '/' . (date('Y') + 1))
            ),
            'slide3_konten'   => $getContent(
                'home-slide3-konten',
                'Penerimaan peserta didik baru tahun ajaran ' . ($sekolah?->tahun_ajaran_aktif ?? date('Y') . '/' . (date('Y') + 1)) . ' kini dibuka. Kuota terbatas — segera daftarkan putra-putri Anda!'
            ),
        ];

        $pageTitle = ($setting->hero_title ? strip_tags($setting->hero_title) : ($sekolah?->nama_sekolah ?? 'Sekolah')) . ' - Sistem Informasi Sekolah';

        return view('welcome', compact(
            'stats', 'pengumuman', 'prestasi', 'agendas',
            'sekolah', 'pageTitle', 'setting', 'keunggulan'
        ));
    }
}