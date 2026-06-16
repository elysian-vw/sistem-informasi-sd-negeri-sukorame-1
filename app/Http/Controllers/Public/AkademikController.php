<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;

class AkademikController extends Controller
{
    private function baseData(): array
    {
        return ['sekolah' => Sekolah::first()];
    }

    public function kurikulum()
    {
        $content = \App\Models\PageContent::where('slug', 'akademik-kurikulum')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.akademik.kurikulum', array_merge($this->baseData(), [
            'pageTitle' => 'Kurikulum — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function kalender()
    {
        $content = \App\Models\PageContent::where('slug', 'akademik-kalender')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.akademik.kalender', array_merge($this->baseData(), [
            'pageTitle' => 'Kalender Pendidikan — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function karakter()
    {
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.akademik.karakter', array_merge($this->baseData(), [
            'pageTitle' => 'Pendidikan Karakter — ' . $schoolName,
        ]));
    }

    public function ekstrakurikuler()
    {
        $ekskul = \App\Models\Ekskul::where('is_aktif', true)->get();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.akademik.ekstrakurikuler', array_merge($this->baseData(), [
            'pageTitle' => 'Ekstrakulikuler — ' . $schoolName,
            'ekskul' => $ekskul,
        ]));
    }

    public function literasi()
    {
        $content = \App\Models\PageContent::where('slug', 'akademik-literasi')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.akademik.literasi', array_merge($this->baseData(), [
            'pageTitle' => 'Gerakan Literasi — ' . $schoolName,
            'content' => $content
        ]));
    }
}