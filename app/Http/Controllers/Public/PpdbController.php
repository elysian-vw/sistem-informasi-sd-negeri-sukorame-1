<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;

class PpdbController extends Controller
{
    private function baseData(): array
    {
        return ['sekolah' => Sekolah::first()];
    }

    public function info()
    {
        $content = \App\Models\PageContent::where('slug', 'ppdb-info')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.ppdb.info', array_merge($this->baseData(), [
            'pageTitle' => 'Informasi PPDB 2025/2026 — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function syarat()
    {
        $content = \App\Models\PageContent::where('slug', 'ppdb-syarat')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.ppdb.syarat', array_merge($this->baseData(), [
            'pageTitle' => 'Syarat Pendaftaran PPDB — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function jadwal()
    {
        $content = \App\Models\PageContent::where('slug', 'ppdb-jadwal')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.ppdb.jadwal', array_merge($this->baseData(), [
            'pageTitle' => 'Jadwal PPDB — ' . $schoolName,
            'content' => $content
        ]));
    }

    public function alur()
    {
        $content = \App\Models\PageContent::where('slug', 'ppdb-alur')->first();
        $sekolah = $this->baseData()['sekolah'];
        $schoolName = $sekolah->nama_sekolah ?? config('app.name');
        return view('public.ppdb.alur', array_merge($this->baseData(), [
            'pageTitle' => 'Alur Pendaftaran PPDB — ' . $schoolName,
            'content' => $content
        ]));
    }
}