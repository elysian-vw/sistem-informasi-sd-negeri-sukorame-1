<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // Profil
            ['slug' => 'profil-visi-misi', 'title' => 'Visi & Misi'],
            ['slug' => 'profil-sejarah', 'title' => 'Sejarah Sekolah'],
            ['slug' => 'profil-struktur', 'title' => 'Struktur Organisasi'],
            ['slug' => 'profil-komite', 'title' => 'Komite Sekolah'],
            ['slug' => 'profil-prestasi', 'title' => 'Prestasi'],
            // Akademik
            ['slug' => 'akademik-kurikulum', 'title' => 'Kurikulum'],
            ['slug' => 'akademik-kalender', 'title' => 'Kalender Akademik'],
            ['slug' => 'akademik-literasi', 'title' => 'Program Literasi'],
            // PPDB
            ['slug' => 'ppdb-alur', 'title' => 'Alur Pendaftaran'],
            ['slug' => 'ppdb-syarat', 'title' => 'Syarat Pendaftaran'],
            ['slug' => 'ppdb-jadwal', 'title' => 'Jadwal Pendaftaran'],
            // Layanan
            ['slug' => 'layanan-surat', 'title' => 'Layanan Surat'],
            ['slug' => 'layanan-mutasi', 'title' => 'Mutasi Siswa'],
            ['slug' => 'layanan-pip', 'title' => 'PIP (Program Indonesia Pintar)'],
        ];

        foreach ($pages as $page) {
            PageContent::firstOrCreate(['slug' => $page['slug']], [
                'title' => $page['title'],
                'content' => 'Konten untuk ' . $page['title'] . ' belum diisi.'
            ]);
        }
    }
}