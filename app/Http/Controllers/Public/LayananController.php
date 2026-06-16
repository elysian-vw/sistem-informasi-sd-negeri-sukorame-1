<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;

class LayananController extends Controller
{
    private function baseData(): array
    {
        return ['sekolah' => Sekolah::first()];
    }

    public function mutasi()
    {
        $content = \App\Models\PageContent::where('slug', 'layanan-mutasi')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.layanan.mutasi', array_merge($this->baseData(), [
            'pageTitle' => 'Mutasi Siswa — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function surat()
    {
        $content = \App\Models\PageContent::where('slug', 'layanan-surat')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.layanan.surat', array_merge($this->baseData(), [
            'pageTitle' => 'Surat Keterangan Siswa — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function izin()
    {
        $content = \App\Models\PageContent::where('slug', 'layanan-izin')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.layanan.izin', array_merge($this->baseData(), [
            'pageTitle' => 'Izin Penelitian / PKL — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function nisn()
    {
        $content = \App\Models\PageContent::where('slug', 'layanan-nisn')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.layanan.nisn', array_merge($this->baseData(), [
            'pageTitle' => 'Cek / Cetak NISN — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function pip()
    {
        $content = \App\Models\PageContent::where('slug', 'layanan-pip')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.layanan.pip', array_merge($this->baseData(), [
            'pageTitle' => 'Cek Beasiswa PIP — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function unduhan()
    {
        $content = \App\Models\PageContent::where('slug', 'layanan-unduhan')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.layanan.unduhan', array_merge($this->baseData(), [
            'pageTitle' => 'Unduhan Dokumen — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function alumni()
    {
        $content = \App\Models\PageContent::where('slug', 'layanan-alumni')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.layanan.alumni', array_merge($this->baseData(), [
            'pageTitle' => 'Penjaringan Alumni — ' . $schoolName,
            'content' => $content
        ]));
    }
}