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
            [
                'slug' => 'profil-visi-misi', 
                'title' => 'Visi Sekolah',
                'content' => 'Terwujudnya Peserta Didik yang Beriman, Bertaqwa, Berakhlak Mulia, Unggul dalam Prestasi, Berwawasan Lingkungan, dan Berbudaya.'
            ],
            ['slug' => 'profil-misi', 
                'title' => 'Misi Sekolah',
                'content' => '<ul>
                    <li>Menumbuhkan penghayatan dan pengamalan terhadap ajaran agama yang dianut.</li>
                    <li>Melaksanakan pembelajaran dan bimbingan secara efektif sehingga setiap siswa berkembang secara optimal.</li>
                    <li>Mendorong dan membantu setiap siswa untuk mengenali potensi dirinya.</li>
                    <li>Menumbuhkan semangat keunggulan secara intensif kepada seluruh warga sekolah.</li>
                    <li>Menciptakan lingkungan sekolah yang bersih, sehat, asri, and nyaman.</li>
                </ul>'
            ],
            [
                'slug' => 'profil-tujuan',
                'title' => 'Tujuan Sekolah',
                'content' => 'Menghasilkan lulusan yang memiliki kompetensi akademik dan non-akademik, beriman dan bertaqwa kepada Tuhan Yang Maha Esa, berakhlak mulia, sehat jasmani dan rohani, serta memiliki kecakapan hidup.'
            ],
            ['slug' => 'profil-sejarah', 'title' => 'Sejarah Sekolah', 'content' => 'SD Negeri Sukorame 1 didirikan pada tahun...'],

            ['slug' => 'profil-struktur', 'title' => 'Struktur Organisasi', 'content' => 'Berikut adalah struktur organisasi SDN Sukorame 1...'],
            ['slug' => 'profil-komite', 'title' => 'Komite Sekolah', 'content' => 'Peran komite sekolah dalam mendukung pendidikan...'],
            ['slug' => 'profil-prestasi', 'title' => 'Prestasi', 'content' => 'Daftar prestasi yang telah diraih oleh SDN Sukorame 1...'],
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
            ['slug' => 'layanan-izin', 'title' => 'Layanan Izin Penelitian / PKL'],
            ['slug' => 'layanan-nisn', 'title' => 'Layanan NISN'],
            ['slug' => 'layanan-unduhan', 'title' => 'Pusat Unduhan'],
            ['slug' => 'layanan-alumni', 'title' => 'Ruang Alumni'],
            ['slug' => 'info-dinas', 'title' => 'Info Dinas Pendidikan', 'content' => json_encode([
                [
                    'judul'     => 'Program Indonesia Pintar (PIP) 2025',
                    'tipe'      => 'beasiswa',
                    'ringkasan' => 'Bantuan tunai pendidikan bagi peserta didik kurang mampu jenjang SD.',
                    'detail'    => 'Besaran bantuan: SD Rp450.000/tahun. Verifikasi data melalui operator sekolah. Pencairan Juni–Juli 2025.',
                    'sumber'    => 'Kemendikbudristek',
                    'tanggal'   => '2025-05-15',
                    'url'       => 'https://pip.kemdikbud.go.id',
                    'ico'       => 'fa-graduation-cap',
                    'warna'     => '#d97706',
                ],
                [
                    'judul'     => 'Kurikulum Merdeka — Implementasi Penuh 2025/2026',
                    'tipe'      => 'kebijakan',
                    'ringkasan' => 'Satuan pendidikan dasar wajib mengimplementasikan Kurikulum Merdeka secara penuh.',
                    'detail'    => 'Sekolah menyusun KOSP, modul ajar berbasis projek, dan melaksanakan P5 minimal 2 tema per tahun.',
                    'sumber'    => 'Dinas Pendidikan Kota Kediri',
                    'tanggal'   => '2025-05-10',
                    'url'       => 'https://kurikulum.kemdikbud.go.id',
                    'ico'       => 'fa-book-open',
                    'warna'     => '#1d4ed8',
                ],
                [
                    'judul'     => 'Pelatihan Guru: Asesmen Formatif & Sumatif',
                    'tipe'      => 'pelatihan',
                    'ringkasan' => 'Pelatihan bagi guru SD mengenai asesmen formatif dan sumatif berbasis Kurikulum Merdeka.',
                    'detail'    => 'Jadwal: 23–24 Juni 2025. Tempat: SMPN 1 Kota Kediri. Pendaftaran melalui SIMPKB paling lambat 15 Juni 2025.',
                    'sumber'    => 'Dinas Pendidikan Kota Kediri',
                    'tanggal'   => '2025-04-28',
                    'url'       => 'https://simpkb.id',
                    'ico'       => 'fa-chalkboard-teacher',
                    'warna'     => '#9333ea',
                ],
                [
                    'judul'     => 'BOS 2025: Juknis dan Pelaporan',
                    'tipe'      => 'administrasi',
                    'ringkasan' => 'Petunjuk Teknis BOS 2025 mengatur alokasi belanja dan pelaporan sekolah.',
                    'detail'    => 'Perubahan utama: alokasi ATK maksimal 5%, pembelian buku teks melalui e-katalog, dan pelaporan MARKAS triwulan.',
                    'sumber'    => 'Kemendikbudristek',
                    'tanggal'   => '2025-03-03',
                    'url'       => 'https://bos.kemdikbud.go.id',
                    'ico'       => 'fa-file-invoice-dollar',
                    'warna'     => '#2563eb',
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)],
            ['slug' => 'info-dinas-links', 'title' => 'Tautan Info Dinas', 'content' => json_encode([
                ['nama'=>'Kemendikbudristek',        'url'=>'https://kemdikbud.go.id',            'ico'=>'fa-landmark'],
                ['nama'=>'DAPODIK',                  'url'=>'https://dapo.kemdikbud.go.id',       'ico'=>'fa-database'],
                ['nama'=>'Program Indonesia Pintar', 'url'=>'https://pip.kemdikbud.go.id',        'ico'=>'fa-graduation-cap'],
                ['nama'=>'SIMPKB (GTK)',             'url'=>'https://simpkb.id',                  'ico'=>'fa-chalkboard-teacher'],
                ['nama'=>'BOS Online',               'url'=>'https://bos.kemdikbud.go.id',        'ico'=>'fa-file-invoice-dollar'],
                ['nama'=>'Kurikulum Merdeka',        'url'=>'https://kurikulum.kemdikbud.go.id',  'ico'=>'fa-book-open'],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)],
        ];

        foreach ($pages as $page) {
            PageContent::updateOrCreate(['slug' => $page['slug']], [
                'title' => $page['title'],
                'content' => $page['content'] ?? 'Konten untuk ' . $page['title'] . ' belum diisi.'
            ]);
        }
    }
}