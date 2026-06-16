<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Galeri;

class GaleriController extends Controller
{
    private function baseData(): array
    {
        return ['sekolah' => Sekolah::first()];
    }

    public function foto()
    {
        $fotos = Galeri::where('tipe', 'foto')->where('is_published', true)->orderBy('urutan')->orderBy('created_at', 'desc')->get();
        $base = $this->baseData();
        return view('public.galeri.foto', array_merge($base, [
            'pageTitle' => 'Galeri Foto — ' . ($base['sekolah']->nama_sekolah ?? 'Sekolah'),
            'fotos' => $fotos
        ]));
    }

    public function video()
    {
        $videos = Galeri::where('tipe', 'video')->where('is_published', true)->orderBy('urutan')->orderBy('created_at', 'desc')->get();
        $base = $this->baseData();
        return view('public.galeri.video', array_merge($base, [
            'pageTitle' => 'Galeri Video — ' . ($base['sekolah']->nama_sekolah ?? 'Sekolah'),
            'videos' => $videos
        ]));
    }
}