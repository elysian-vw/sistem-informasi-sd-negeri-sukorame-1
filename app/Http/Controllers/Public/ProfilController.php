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

        return view('public.profil.visi-misi', compact('visi', 'misi'));
    }

    public function sejarah()
    {
        return view('public.profil.sejarah', array_merge($this->baseData(), [
            'pageTitle' => 'Sejarah Sekolah — SDN Sukorame 1',
        ]));
    }

    public function struktur()
    {
        return view('public.profil.struktur', array_merge($this->baseData(), [
            'pageTitle' => 'Struktur Organisasi — SDN Sukorame 1',
        ]));
    }


    public function komite()
    {
        $komite = Komite::aktif()->orderBy('urutan')->get();
        $pageTitle = 'Komite Sekolah — SDN Sukorame 1';

        return view('public.profil.komite', compact('komite', 'pageTitle'));
    }

    public function guru()
    {
        // nama ada di tabel users, filter role=guru supaya siswa tidak ikut terpilih
        $guru = Guru::with('user')
                    ->where('status', 'aktif')
                    ->whereHas('user', fn($q) => $q->where('role', 'guru'))
                    ->get()
                    ->sortBy(fn($g) => $g->user?->name);

        return view('public.profil.guru', array_merge($this->baseData(), [
            'pageTitle' => 'Profil Guru & Karyawan — SDN Sukorame 1',
            'guru'      => $guru,
        ]));
    }

    public function sarana()
    {
        $pageTitle = 'Sarana & Prasarana — SDN Sukorame 1';
        // $sarana = \App\Models\Sarana::orderBy('urutan')->get(); // kalau sudah ada model
        return view('public.profil.sarana', compact('pageTitle'));
    }

    public function akreditasi()
    {
        $sekolah   = \App\Models\Sekolah::first();
        $pageTitle = 'Akreditasi — SDN Sukorame 1';
        return view('public.profil.akreditasi', compact('sekolah', 'pageTitle'));
    }

    public function prestasi()
    {
        return view('public.profil.prestasi', array_merge($this->baseData(), [
            'pageTitle' => 'Prestasi Sekolah — SDN Sukorame 1',
        ]));
    }
}