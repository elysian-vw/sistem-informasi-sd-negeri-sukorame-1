<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ data_get($sekolah, 'nama_sekolah', config('app.name')) }} {{ data_get($sekolah, 'kota', '') }} — Sistem Informasi Sekolah SIMAS">
    <title>@yield('title', data_get($sekolah, 'nama_sekolah', config('app.name')))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root { --red: #1D4ED8; --red-dark: #1e3a8a; --red-light: #EFF6FF; }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        html { scroll-behavior: smooth; }
        #main-nav.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,.09); }
        /* Dropdown */
        .nav-item { position: relative; }
        .nav-item:hover > .dropdown,
        .nav-item:focus-within > .dropdown { display: block; }
        .dropdown {
            display: none; position: absolute; top: 100%; left: 0;
            background: #fff; min-width: 220px;
            border-top: 3px solid var(--red);
            border-radius: 0 0 12px 12px;
            box-shadow: 0 12px 32px rgba(0,0,0,.13); z-index: 999;
        }
        .dropdown a {
            display: block; padding: 9px 18px; font-size: 13px;
            color: #374151; border-bottom: 1px solid #f3f4f6;
            transition: background .15s, color .15s, padding-left .15s;
        }
        .dropdown a:last-child { border-bottom: none; }
        .dropdown a:hover { background: var(--red-light); color: var(--red); padding-left: 24px; }
        /* Mobile menu */
        #mobile-menu { max-height: 0; overflow: hidden; transition: max-height .4s ease; }
        #mobile-menu.open { max-height: 1200px; }
        .mob-sub { display: none; }
        .mob-sub.open { display: block; }
        .hover-lift { transition: transform .25s, box-shadow .25s; }
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,.10); }
        .badge-section {
            display: inline-block; font-size: 11px; font-weight: 700;
            color: var(--red); background: var(--red-light);
            border: 1px solid #bfdbfe;
            padding: 3px 12px; border-radius: 999px;
            text-transform: uppercase; letter-spacing: .05em; margin-bottom: 8px;
        }
        /* Active nav link */
        .nav-active { color: var(--red) !important; background: var(--red-light) !important; border-radius: 8px; }
    </style>
    @stack('head')
</head>
<body class="bg-white antialiased">

{{-- TOPBAR --}}
<div class="bg-blue-900 text-white text-xs py-1.5 hidden md:block">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
        <div class="flex items-center gap-5 opacity-90">
            <span><i class="fa fa-phone mr-1"></i>{{ data_get($sekolah, 'telepon', '(0354) 123456') }}</span>
            <span><i class="fa fa-envelope mr-1"></i>{{ data_get($sekolah, 'email', 'info@sekolah.local') }}</span>
            <span><i class="fa fa-map-marker-alt mr-1"></i>{{ data_get($sekolah, 'nama_singkat', data_get($sekolah, 'nama_sekolah', config('app.name'))) }}, {{ data_get($sekolah, 'kota', 'Kota') }}</span>
        </div>
        <div class="flex items-center gap-3 opacity-90">
            <a href="{{ $sekolah->facebook ?? 'https://www.facebook.com/sdnsatusukorame/' }}" class="hover:opacity-100" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="{{ $sekolah->instagram ?? 'https://www.instagram.com/sukoramekediri/' }}" class="hover:opacity-100" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="{{ $sekolah->youtube ?? 'https://www.youtube.com/@sdnsukorame1kediri865' }}" class="hover:opacity-100" target="_blank"><i class="fab fa-youtube"></i></a>
            <a href="{{ route('login') }}" class="ml-2 bg-white/20 hover:bg-white/30 px-3 py-1 rounded font-semibold transition-colors">
                <i class="fa fa-graduation-cap mr-1"></i>Login SIMAS
            </a>
        </div>
    </div>
</div>

{{-- NAVBAR --}}
<nav id="main-nav" class="sticky top-0 z-50 bg-white border-b border-gray-200 transition-shadow duration-300">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-700 flex items-center justify-center text-white font-black text-sm">SD</div>
                <div class="leading-tight hidden sm:block">
                    <p class="font-bold text-blue-700 text-sm">{{ data_get($sekolah, 'nama_singkat', data_get($sekolah, 'nama_sekolah', config('app.name'))) }}</p>
                    <p class="text-gray-400 text-xs">{{ data_get($sekolah, 'kota', 'Kota') }}</p>
                </div>
            </a>

            {{-- Desktop --}}
            <div class="hidden lg:flex items-center gap-0.5 text-sm font-semibold text-gray-700">

                <a href="{{ route('home') }}"
                   class="px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('home') ? 'nav-active' : '' }}">
                    Beranda
                </a>

                {{-- Profil --}}
                <div class="nav-item">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('profil.*') ? 'nav-active' : '' }}">
                        Profil <i class="fa fa-chevron-down text-xs opacity-50 ml-0.5"></i>
                    </button>
                    <div class="dropdown">
                        <a href="{{ route('profil.visi-misi') }}">Visi &amp; Misi</a>
                        <a href="{{ route('profil.sejarah') }}">Sejarah Sekolah</a>
                        <a href="{{ route('profil.struktur') }}">Struktur Organisasi</a>
                        <a href="{{ route('profil.komite') }}">Komite Sekolah</a>
                        <a href="{{ route('profil.guru') }}">Profil Guru &amp; Karyawan</a>
                        <a href="{{ route('profil.sarana') }}">Sarana &amp; Prasarana</a>
                        <a href="{{ route('profil.akreditasi') }}">Akreditasi Sekolah</a>
                        <a href="{{ route('profil.prestasi') }}">Prestasi Sekolah</a>
                    </div>
                </div>

                {{-- Akademik --}}
                <div class="nav-item">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('akademik.*') ? 'nav-active' : '' }}">
                        Akademik <i class="fa fa-chevron-down text-xs opacity-50 ml-0.5"></i>
                    </button>
                    <div class="dropdown">
                        <a href="{{ route('akademik.kurikulum') }}">Kurikulum</a>
                        <a href="{{ route('akademik.kalender') }}">Kalender Pendidikan</a>
                        <a href="{{ route('akademik.karakter') }}">Pendidikan Karakter</a>
                        <a href="{{ route('akademik.ekstrakurikuler') }}">Ekstrakulikuler</a>
                        <a href="{{ route('akademik.literasi') }}">Gerakan Literasi</a>
                        <a href="{{ route('login') }}" style="color:var(--red);font-weight:700;">
                            <i class="fa fa-graduation-cap mr-1"></i>E-Learning SIMAS
                        </a>
                    </div>
                </div>

                {{-- Berita --}}
                <div class="nav-item">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('berita.*') ? 'nav-active' : '' }}">
                        Berita <i class="fa fa-chevron-down text-xs opacity-50 ml-0.5"></i>
                    </button>
                    <div class="dropdown">
                        <a href="{{ route('berita.index') }}">Berita & Pengumuman</a>
                        <a href="{{ route('berita.agenda') }}">Agenda Kegiatan</a>
                        <a href="{{ route('berita.info-dinas') }}">Info Dinas Pendidikan</a>
                    </div>
                </div>

                {{-- Galeri --}}
                <div class="nav-item">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('galeri.*') ? 'nav-active' : '' }}">
                        Galeri <i class="fa fa-chevron-down text-xs opacity-50 ml-0.5"></i>
                    </button>
                    <div class="dropdown">
                        <a href="{{ route('galeri.foto') }}">Foto Kegiatan</a>
                        <a href="{{ route('galeri.video') }}">Video</a>
                    </div>
                </div>

                {{-- PPDB --}}
                <div class="nav-item">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('ppdb.*') ? 'nav-active' : '' }}">
                        PPDB <i class="fa fa-chevron-down text-xs opacity-50 ml-0.5"></i>
                    </button>
                    <div class="dropdown">
                        <a href="{{ route('ppdb.info') }}">Informasi PPDB</a>
                        <a href="{{ route('ppdb.syarat') }}">Syarat Pendaftaran</a>
                        <a href="{{ route('ppdb.jadwal') }}">Jadwal PPDB</a>
                        <a href="{{ route('ppdb.alur') }}">Alur Pendaftaran</a>
                    </div>
                </div>

                {{-- Layanan --}}
                <div class="nav-item">
                    <button class="flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-colors {{ request()->routeIs('layanan.*') ? 'nav-active' : '' }}">
                        Layanan <i class="fa fa-chevron-down text-xs opacity-50 ml-0.5"></i>
                    </button>
                    <div class="dropdown">
                        <a href="{{ route('layanan.mutasi') }}">Mutasi Siswa</a>
                        <a href="{{ route('layanan.surat') }}">Surat Keterangan Siswa</a>
                        <a href="{{ route('layanan.izin') }}">Izin</a>
                        <a href="{{ route('layanan.nisn') }}">Cek / Cetak NISN</a>
                        <a href="{{ route('layanan.pip') }}">Cek Beasiswa PIP</a>
                        <a href="{{ route('layanan.unduhan') }}">Unduhan Dokumen</a>
                        <a href="{{ route('layanan.alumni') }}">Penjaringan Alumni</a>
                    </div>
                </div>

                <a href="{{ route('login') }}"
                   class="ml-2 flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-bold px-4 py-2 rounded-lg transition-colors">
                    <i class="fa fa-sign-in-alt"></i> Login
                </a>
            </div>

            <button id="hamburger" class="lg:hidden p-2 text-gray-600">
                <i class="fa fa-bars text-lg"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="lg:hidden bg-white border-t border-gray-100 overflow-hidden">
        <div class="px-4 py-3 space-y-0.5 text-sm font-medium">
            <a href="{{ route('home') }}" class="block py-2.5 text-gray-700 border-b border-gray-100">Beranda</a>
            @php
                $mobs = [
                    'Profil' => [
                        ['label'=>'Visi & Misi','route'=>'profil.visi-misi'],
                        ['label'=>'Sejarah Sekolah','route'=>'profil.sejarah'],
                        ['label'=>'Struktur Organisasi','route'=>'profil.struktur'],
                        ['label'=>'Komite Sekolah','route'=>'profil.komite'],
                        ['label'=>'Profil Guru','route'=>'profil.guru'],
                        ['label'=>'Sarana & Prasarana','route'=>'profil.sarana'],
                        ['label'=>'Akreditasi','route'=>'profil.akreditasi'],
                        ['label'=>'Prestasi','route'=>'profil.prestasi'],
                    ],
                    'Akademik' => [
                        ['label'=>'Kurikulum','route'=>'akademik.kurikulum'],
                        ['label'=>'Kalender Pendidikan','route'=>'akademik.kalender'],
                        ['label'=>'Pendidikan Karakter','route'=>'akademik.karakter'],
                        ['label'=>'Ekstrakulikuler','route'=>'akademik.ekstrakurikuler'],
                        ['label'=>'Gerakan Literasi','route'=>'akademik.literasi'],
                        ['label'=>'E-Learning SIMAS','route'=>'login'],
                    ],
                    'Berita' => [
                        ['label'=>'Berita Sekolah','route'=>'berita.index'],
                        ['label'=>'Pengumuman','route'=>'berita.pengumuman'],
                        ['label'=>'Agenda Kegiatan','route'=>'berita.agenda'],
                        ['label'=>'Info Dinas Pendidikan','route'=>'berita.info-dinas'],
                    ],
                    'Galeri' => [
                        ['label'=>'Foto Kegiatan','route'=>'galeri.foto'],
                        ['label'=>'Video','route'=>'galeri.video'],
                    ],
                    'PPDB' => [
                        ['label'=>'Informasi PPDB','route'=>'ppdb.info'],
                        ['label'=>'Syarat Pendaftaran','route'=>'ppdb.syarat'],
                        ['label'=>'Jadwal PPDB','route'=>'ppdb.jadwal'],
                        ['label'=>'Alur Pendaftaran','route'=>'ppdb.alur'],
                    ],
                    'Layanan' => [
                        ['label'=>'Mutasi Siswa','route'=>'layanan.mutasi'],
                        ['label'=>'Surat Keterangan','route'=>'layanan.surat'],
                        ['label'=>'Izin PKL','route'=>'layanan.izin'],
                        ['label'=>'Cek NISN','route'=>'layanan.nisn'],
                        ['label'=>'Cek Beasiswa PIP','route'=>'layanan.pip'],
                        ['label'=>'Unduhan','route'=>'layanan.unduhan'],
                        ['label'=>'Penjaringan Alumni','route'=>'layanan.alumni'],
                    ],
                ];
            @endphp
            @foreach ($mobs as $label => $subs)
            <div>
                <button onclick="this.nextElementSibling.classList.toggle('open');this.querySelector('i').classList.toggle('rotate-180')"
                        class="w-full flex items-center justify-between py-2.5 text-gray-700 border-b border-gray-100">
                    {{ $label }} <i class="fa fa-chevron-down text-xs opacity-50 transition-transform duration-200"></i>
                </button>
                <div class="mob-sub bg-gray-50 rounded-b-lg">
                    @foreach ($subs as $s)
                    <a href="{{ route($s['route']) }}" class="block px-5 py-2 text-xs text-gray-600 border-b border-gray-100 last:border-0 hover:text-blue-700">{{ $s['label'] }}</a>
                    @endforeach
                </div>
            </div>
            @endforeach
            <a href="{{ route('login') }}" class="block mt-3 text-center bg-blue-700 text-white font-bold py-3 rounded-xl">
                <i class="fa fa-graduation-cap mr-2"></i>Login SIMAS
            </a>
        </div>
    </div>
</nav>

@yield('content')

{{-- FOOTER --}}
<footer class="bg-gray-900 text-gray-400" id="kontak">
    <div class="max-w-7xl mx-auto px-4 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-blue-700 flex items-center justify-center text-white font-black">SD</div>
                    <div>
                        <p class="font-bold text-white">{{ $sekolah->nama_sekolah ?? 'SD Negeri Sukorame 1' }}</p>
                        <p class="text-xs">Kota Kediri · NPSN: {{ $sekolah?->npsn ?? '20534318' }}</p>
                    </div>
                </div>
                <p class="text-sm leading-relaxed mb-4 max-w-sm">{{ $sekolah->slogan ?? 'Membentuk generasi cerdas, berkarakter, dan berprestasi berlandaskan iman dan teknologi melalui platform SIMAS.' }}</p>
                <div class="space-y-2 text-sm">
                    <div class="flex gap-2"><i class="fa fa-map-marker-alt text-blue-400 mt-0.5 w-4"></i><span>{{ $sekolah->alamat ?? 'Jl. Saharjo No. 1, Kediri' }}</span></div>
                    <div class="flex gap-2"><i class="fa fa-phone text-blue-400 w-4"></i><span>{{ $sekolah->telepon ?? '(0354) 123456' }}</span></div>
                    <div class="flex gap-2"><i class="fa fa-envelope text-blue-400 w-4"></i><span>{{ $sekolah->email ?? 'sdn.sukorame1@kediri.go.id' }}</span></div>
                </div>
                <div class="flex gap-3 mt-5">
                    @php
                        $socials = [
                            'facebook-f' => $sekolah->facebook ?? 'https://www.facebook.com/sdnsatusukorame/',
                            'instagram'  => $sekolah->instagram ?? 'https://www.instagram.com/sukoramekediri/',
                            'youtube'    => $sekolah->youtube ?? 'https://www.youtube.com/@sdnsukorame1kediri865'
                        ];
                    @endphp

                    @foreach ($socials as $icon => $url)
                        <a href="{{ $url }}" 
                        target="_blank" 
                        class="w-9 h-9 bg-gray-800 hover:bg-blue-700 rounded-full flex items-center justify-center text-sm transition-colors">
                            <i class="fab fa-{{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-4 pb-2 border-b border-gray-800">Profil Sekolah</h4>
                <div class="space-y-2 text-sm">
                    <a href="{{ route('profil.visi-misi') }}" class="block hover:text-blue-400 transition-colors">Visi &amp; Misi</a>
                    <a href="{{ route('profil.sejarah') }}" class="block hover:text-blue-400 transition-colors">Sejarah Sekolah</a>
                    <a href="{{ route('profil.struktur') }}" class="block hover:text-blue-400 transition-colors">Struktur Organisasi</a>
                    <a href="{{ route('profil.guru') }}" class="block hover:text-blue-400 transition-colors">Profil Guru</a>
                    <a href="{{ route('profil.sarana') }}" class="block hover:text-blue-400 transition-colors">Sarana &amp; Prasarana</a>
                    <a href="{{ route('profil.akreditasi') }}" class="block hover:text-blue-400 transition-colors">Akreditasi</a>
                    <a href="{{ route('profil.prestasi') }}" class="block hover:text-blue-400 transition-colors">Prestasi Sekolah</a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-4 pb-2 border-b border-gray-800">Layanan &amp; SIMAS</h4>
                <div class="space-y-2 text-sm">
                    <a href="{{ route('berita.index') }}" class="block hover:text-blue-400 transition-colors">Berita Sekolah</a>
                    <a href="{{ route('berita.pengumuman') }}" class="block hover:text-blue-400 transition-colors">Pengumuman</a>
                    <a href="{{ route('galeri.foto') }}" class="block hover:text-blue-400 transition-colors">Galeri Foto</a>
                    <a href="{{ route('akademik.kalender') }}" class="block hover:text-blue-400 transition-colors">Kalender Pendidikan</a>
                    <a href="{{ route('ppdb.info') }}" class="block hover:text-blue-400 transition-colors">PPDB Online</a>
                    <a href="{{ route('layanan.mutasi') }}" class="block hover:text-blue-400 transition-colors">Mutasi Siswa</a>
                    <a href="{{ route('layanan.nisn') }}" class="block hover:text-blue-400 transition-colors">Cek NISN</a>
                    <a href="{{ route('layanan.unduhan') }}" class="block hover:text-blue-400 transition-colors">Unduhan Dokumen</a>
                </div>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl transition-colors mt-5">
                    <i class="fa fa-graduation-cap"></i> Login SIMAS
                </a>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col md:flex-row items-center justify-between text-xs">
            <p>&copy; {{ date('Y') }} {{ $sekolah->nama_sekolah ?? config('app.name') }}. Hak Cipta Dilindungi.</p>
            <p class="mt-2 md:mt-0">Didukung <span class="text-blue-400 font-semibold">SIMAS</span> — Sistem Informasi &amp; E-Learning</p>
        </div>
    </div>
</footer>

<script>
    window.addEventListener('scroll', () => {
        document.getElementById('main-nav').classList.toggle('scrolled', window.scrollY > 10);
    });
    document.getElementById('hamburger')?.addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.toggle('open');
    });
    // Counter
    const statsEl = document.getElementById('stats-section');
    if (statsEl) {
        new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) {
                document.querySelectorAll('[data-count]').forEach(el => {
                    const target = +el.dataset.count, step = Math.max(1, Math.ceil(target/60));
                    let cur = 0;
                    const t = setInterval(() => {
                        cur = Math.min(cur + step, target);
                        el.textContent = cur.toLocaleString('id-ID');
                        if (cur >= target) clearInterval(t);
                    }, 20);
                });
            }
        }, { threshold: 0.3 }).observe(statsEl);
    }
</script>
@stack('scripts')
</body>
</html>