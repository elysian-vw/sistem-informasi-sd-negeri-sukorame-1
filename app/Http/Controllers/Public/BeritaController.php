<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\PageContent;
use App\Models\Pengumuman;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    private function baseData(): array
    {
        return ['sekolah' => Sekolah::first()];
    }


    public function index(Request $request) 
    {
        $query = Pengumuman::query();

        // 1. Logika Filter Pencarian (Search)
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        // 2. Logika Filter Kategori (Untuk)
        if ($request->filled('untuk')) {
            $query->where('untuk', $request->untuk);
        }

        // Ambil datanya, urutkan yang terbaru
        $pengumuman = $query->latest()->paginate(9)->withQueryString(); 
        // ^ withQueryString() itu penting biar pas pindah halaman (pagination), 
        // filternya nggak ilang di URL.

        // 3. Tambahan buat variabel totalAktif (biar statistik di hero section jalan)
        $totalAktif = Pengumuman::where('is_aktif', true)->count();

        return view('public.berita.index', array_merge($this->baseData(), [
            'pageTitle'   => 'Berita Sekolah — ' . ($this->baseData()['sekolah']->nama_sekolah ?? config('app.name')),
            'pengumuman'  => $pengumuman,
            'totalAktif'  => $totalAktif, // Kirim ini juga ya!
        ]));
    }

    public function show($id)
    {
        $item = Pengumuman::findOrFail($id);

        return view('public.berita.show', array_merge($this->baseData(), [
            'pageTitle' => $item->judul . ' — ' . ($this->baseData()['sekolah']->nama_sekolah ?? config('app.name')) ,
            'item'      => $item,
        ]));
    }

    public function pengumuman()
    {
        $pengumuman = Pengumuman::latest()->paginate(10);

        return view('public.berita.pengumuman', array_merge($this->baseData(), [
            'pageTitle'   => 'Pengumuman — ' . ($this->baseData()['sekolah']->nama_sekolah ?? config('app.name')) ,
            'pengumuman'  => $pengumuman,
        ]));
    }

    public function agenda()
    {
        $agendaItems = Kegiatan::where('is_published', true)
            ->orderBy('tanggal_mulai')
            ->orderBy('waktu_mulai')
            ->get();

        return view('public.berita.agenda', array_merge($this->baseData(), [
            'pageTitle' => 'Agenda Kegiatan — ' . ($this->baseData()['sekolah']->nama_sekolah ?? config('app.name')) ,
            'agendaItems' => $agendaItems,
        ]));
    }

    public function infoDinas()
    {
        $page       = PageContent::where('slug', 'info-dinas')->first();
        $infoItems  = collect();

        if ($page && !empty($page->content)) {
            $decoded = json_decode($page->content, true);
            $infoItems = is_array($decoded) ? collect($decoded) : collect();
        }

        $linkContent = PageContent::where('slug', 'info-dinas-links')->first();
        $infoLinks = [];

        if ($linkContent && !empty($linkContent->content)) {
            $decoded = json_decode($linkContent->content, true);
            $infoLinks = is_array($decoded) ? $decoded : [];
        }

        return view('public.berita.info-dinas', array_merge($this->baseData(), [
            'pageTitle' => 'Info Dinas Pendidikan — ' . ($this->baseData()['sekolah']->nama_sekolah ?? config('app.name')) ,
            'infoItems' => $infoItems,
            'infoLinks' => $infoLinks,
        ]));
    }
}