@extends('layouts.public')

@section('title', $pageTitle ?? 'Kurikulum — SDN Sukorame 1')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }

    /* ── PAGE HERO ── */
    .page-hero {
        background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 45%, #1d4ed8 75%, #3b82f6 100%);
        position: relative; overflow: hidden;
    }
    .hero-pattern {
        position: absolute; inset: 0; opacity: .05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 28px 28px;
    }

    /* ── SECTION LABEL ── */
    .sec-label {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe;
        padding: 4px 14px; border-radius: 999px; margin-bottom: 12px;
    }
    .sec-label::before { content: ''; width: 6px; height: 6px; background: #1d4ed8; border-radius: 50%; }

    /* ── DIVIDER ── */
    .section-divider { height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent); }

    /* ── KURIKULUM CARD ── */
    .kurikulum-card {
        background: white; border-radius: 20px;
        border: 1.5px solid #e0e7ff;
        transition: all .25s; overflow: hidden;
    }
    .kurikulum-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 12px 32px rgba(29,78,216,.12);
        transform: translateY(-3px);
    }
    .kurikulum-card-header {
        padding: 20px 22px 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 14px;
    }
    .kurikulum-card-body { padding: 18px 22px; }

    /* ── MAPEL ROW ── */
    .mapel-row {
        display: flex; align-items: center; gap: 14px;
        padding: 12px 16px; border-radius: 14px;
        border: 1px solid #f1f5f9; background: white;
        transition: all .22s;
    }
    .mapel-row:hover { border-color: #bfdbfe; background: #f8fbff; transform: translateX(4px); }

    /* ── STRUKTUR TABLE ── */
    .struktur-badge {
        display: inline-flex; align-items: center;
        font-size: 10px; font-weight: 700; letter-spacing: .05em;
        padding: 3px 10px; border-radius: 999px;
    }

    /* ── TIMELINE ── */
    .timeline-item { position: relative; padding-left: 32px; }
    .timeline-item::before {
        content: ''; position: absolute; left: 9px; top: 24px;
        bottom: -16px; width: 2px; background: #bfdbfe;
    }
    .timeline-item:last-child::before { display: none; }
    .timeline-dot {
        position: absolute; left: 0; top: 14px;
        width: 20px; height: 20px; border-radius: 50%;
        background: #1d4ed8; border: 3px solid #bfdbfe;
        display: flex; align-items: center; justify-content: center;
    }
    .timeline-dot i { font-size: 7px; color: white; }
    .timeline-card {
        background: white; border-radius: 16px;
        border: 1.5px solid #e0e7ff; padding: 16px 18px;
        margin-bottom: 16px; transition: all .22s;
    }
    .timeline-card:hover { border-color: #93c5fd; box-shadow: 0 8px 24px rgba(29,78,216,.1); }

    /* ── CTA BAND ── */
    .cta-band {
        background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 50%, #1d4ed8 100%);
        position: relative; overflow: hidden;
    }
    .cta-band::before {
        content: ''; position: absolute; inset: 0; opacity: .04;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 24px 24px;
    }

    /* ── STAT CARD ── */
    .stat-card {
        background: white; border-radius: 20px;
        border: 1.5px solid #e0e7ff; padding: 24px 20px;
        text-align: center; transition: all .25s;
    }
    .stat-card:hover { border-color: #93c5fd; box-shadow: 0 12px 28px rgba(29,78,216,.1); transform: translateY(-2px); }

    /* Animasi */
    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════
     PAGE HERO
══════════════════════════════════════ --}}
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div style="position:absolute;width:400px;height:400px;right:-100px;top:-100px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;width:240px;height:240px;left:6%;bottom:-70px;border-radius:50%;background:radial-gradient(circle,rgba(217,119,6,.12) 0%,transparent 70%);pointer-events:none;"></div>

    <div class="max-w-5xl mx-auto px-6 relative z-10">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <a href="#" class="hover:text-white transition-colors">Akademik</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Kurikulum</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-amber-300 rounded-full"></span> Akademik
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Kurikulum<br>
                    <span style="color:#FDE68A;">Sekolah</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-lg" style="font-size:1rem;">
                    Struktur program pendidikan SD Negeri Sukorame 1 Kota Kediri berdasarkan
                    Kurikulum Merdeka tahun ajaran <strong class="text-white">{{ date('Y') }}/{{ date('Y') + 1 }}</strong>.
                </p>
            </div>

            {{-- Quick stat --}}
            <div class="flex gap-3 flex-shrink-0">
                @php
                    $hs = [
                        ['val'=> $stats['mapel']  ?? '10+', 'lbl'=>'Mata Pelajaran', 'ico'=>'fa-book-open'],
                        ['val'=> $stats['kelas']  ?? '12',  'lbl'=>'Rombel',         'ico'=>'fa-door-open'],
                        ['val'=> $stats['ekskul'] ?? '6',   'lbl'=>'Ekskul',         'ico'=>'fa-star'],
                    ];
                @endphp
                @foreach ($hs as $h)
                <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center w-24 backdrop-blur-sm">
                    <i class="fa {{ $h['ico'] }} text-amber-300 text-base mb-2 block"></i>
                    <p class="text-white font-black text-xl leading-none">{{ $h['val'] }}</p>
                    <p class="text-white/55 text-[10px] mt-1">{{ $h['lbl'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<main class="bg-gray-50">

    {{-- ══════════════════════════════════════
         OVERVIEW KURIKULUM
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            @if($content)
            <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm mb-14 fade-up">
                <div class="cms-content">
                    {!! $content->content !!}
                </div>
            </div>
            @endif

            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Gambaran Umum</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Kurikulum Merdeka</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    SD Negeri Sukorame 1 mengimplementasikan Kurikulum Merdeka sebagai landasan
                    pembelajaran yang berpusat pada peserta didik, fleksibel, dan relevan dengan kebutuhan zaman.
                </p>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-14 fade-up">
                @php
                    $overviews = [
                        ['icon'=>'fa-graduation-cap', 'val'=>'Merdeka',    'lbl'=>'Jenis Kurikulum',    'color'=>'bg-blue-50 text-blue-600'],
                        ['icon'=>'fa-calendar-alt',   'val'=>date('Y'),    'lbl'=>'Tahun Implementasi', 'color'=>'bg-indigo-50 text-indigo-600'],
                        ['icon'=>'fa-book',            'val'=>'10',         'lbl'=>'Mata Pelajaran',     'color'=>'bg-violet-50 text-violet-600'],
                        ['icon'=>'fa-clock',           'val'=>'35 Menit',  'lbl'=>'Per Jam Pelajaran',  'color'=>'bg-amber-50 text-amber-600'],
                    ];
                @endphp
                @foreach ($overviews as $ov)
                <div class="stat-card fade-up">
                    <div class="w-12 h-12 {{ $ov['color'] }} rounded-2xl flex items-center justify-content-center mx-auto mb-3 flex items-center justify-center">
                        <i class="fa {{ $ov['icon'] }} text-lg"></i>
                    </div>
                    <p class="font-black text-gray-900 text-lg leading-tight">{{ $ov['val'] }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ $ov['lbl'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Deskripsi Kurikulum Merdeka --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-up">
                @php
                    $pillars = [
                        [
                            'icon'  => 'fa-seedling',
                            'color' => 'bg-green-50 text-green-600',
                            'title' => 'Berpusat pada Murid',
                            'desc'  => 'Pembelajaran dirancang sesuai potensi, kebutuhan, dan tahap perkembangan setiap peserta didik.',
                        ],
                        [
                            'icon'  => 'fa-project-diagram',
                            'color' => 'bg-blue-50 text-blue-600',
                            'title' => 'Pembelajaran Berbasis Proyek',
                            'desc'  => 'Proyek Penguatan Profil Pelajar Pancasila (P5) mengembangkan karakter dan kompetensi lintas mapel.',
                        ],
                        [
                            'icon'  => 'fa-tachometer-alt',
                            'color' => 'bg-amber-50 text-amber-600',
                            'title' => 'Fleksibel & Kontekstual',
                            'desc'  => 'Guru memiliki keleluasaan memilih perangkat ajar yang relevan dengan konteks dan lingkungan belajar.',
                        ],
                    ];
                @endphp
                @foreach ($pillars as $p)
                <div class="kurikulum-card">
                    <div class="kurikulum-card-header">
                        <div class="w-11 h-11 {{ $p['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa {{ $p['icon'] }}"></i>
                        </div>
                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ $p['title'] }}</p>
                    </div>
                    <div class="kurikulum-card-body">
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $p['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         STRUKTUR MATA PELAJARAN
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">

            <div class="flex items-end justify-between gap-4 mb-10 fade-up">
                <div>
                    <div class="sec-label">Struktur Mapel</div>
                    <h2 class="font-display text-2xl md:text-3xl font-black text-gray-900">Mata Pelajaran</h2>
                </div>
                <span class="text-xs text-gray-400 font-semibold bg-white border border-gray-200 px-3 py-1.5 rounded-full">
                    TA {{ date('Y') }}/{{ date('Y')+1 }}
                </span>
            </div>

            @php
                $mapelGroups = [
                    [
                        'label' => 'Kelompok A — Wajib Nasional',
                        'color' => 'bg-blue-700',
                        'items' => [
                            ['nama'=>'Pendidikan Agama & Budi Pekerti', 'icon'=>'fa-mosque',      'color'=>'bg-amber-50 text-amber-600', 'jp'=>'4 JP/minggu'],
                            ['nama'=>'PPKn',                             'icon'=>'fa-flag',         'color'=>'bg-red-50 text-red-600',    'jp'=>'5 JP/minggu'],
                            ['nama'=>'Bahasa Indonesia',                 'icon'=>'fa-pen-nib',      'color'=>'bg-blue-50 text-blue-600',  'jp'=>'8 JP/minggu'],
                            ['nama'=>'Matematika',                       'icon'=>'fa-calculator',   'color'=>'bg-indigo-50 text-indigo-600','jp'=>'6 JP/minggu'],
                            ['nama'=>'Ilmu Pengetahuan Alam & Sosial',  'icon'=>'fa-flask',        'color'=>'bg-green-50 text-green-600','jp'=>'6 JP/minggu'],
                        ],
                    ],
                    [
                        'label' => 'Kelompok B — Wajib Lokal',
                        'color' => 'bg-indigo-600',
                        'items' => [
                            ['nama'=>'Seni Budaya & Prakarya',           'icon'=>'fa-palette',      'color'=>'bg-pink-50 text-pink-600',  'jp'=>'4 JP/minggu'],
                            ['nama'=>'Pendidikan Jasmani & Olahraga',    'icon'=>'fa-running',      'color'=>'bg-teal-50 text-teal-600',  'jp'=>'4 JP/minggu'],
                        ],
                    ],
                    [
                        'label' => 'Kelompok C — Muatan Lokal',
                        'color' => 'bg-violet-600',
                        'items' => [
                            ['nama'=>'Bahasa Jawa',                      'icon'=>'fa-language',     'color'=>'bg-purple-50 text-purple-600','jp'=>'2 JP/minggu'],
                            ['nama'=>'Bahasa Inggris',                   'icon'=>'fa-globe',        'color'=>'bg-sky-50 text-sky-600',    'jp'=>'2 JP/minggu'],
                            ['nama'=>'Teknologi Informasi & Komunikasi', 'icon'=>'fa-laptop-code',  'color'=>'bg-cyan-50 text-cyan-600',  'jp'=>'2 JP/minggu'],
                        ],
                    ],
                ];
            @endphp

            <div class="space-y-8">
                @foreach ($mapelGroups as $group)
                <div class="fade-up">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-2 {{ $group['color'] }} text-white text-xs font-bold px-4 py-2 rounded-full">
                            <i class="fa fa-layer-group text-white/70"></i>
                            {{ $group['label'] }}
                        </span>
                    </div>
                    <div class="space-y-2.5">
                        @foreach ($group['items'] as $mapel)
                        <div class="mapel-row">
                            <div class="w-10 h-10 {{ $mapel['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa {{ $mapel['icon'] }} text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm">{{ $mapel['nama'] }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="text-[10px] font-semibold bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-full">
                                    {{ $mapel['jp'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         PROJEK P5
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Profil Pelajar Pancasila</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Projek P5</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Projek Penguatan Profil Pelajar Pancasila (P5) adalah kegiatan pembelajaran lintas
                    mata pelajaran yang berfokus pada pengembangan karakter dan kompetensi.
                </p>
            </div>

            @php
                $dimensi = [
                    ['icon'=>'fa-pray',          'color'=>'bg-amber-50 text-amber-600',   'title'=>'Beriman & Bertakwa',        'desc'=>'Berakhlak mulia dan menghargai keberagaman agama & kepercayaan.'],
                    ['icon'=>'fa-globe-asia',    'color'=>'bg-green-50 text-green-600',   'title'=>'Berkebhinekaan Global',     'desc'=>'Mengenal dan menghargai budaya bangsa serta terbuka terhadap dunia.'],
                    ['icon'=>'fa-users',         'color'=>'bg-blue-50 text-blue-600',     'title'=>'Bergotong Royong',          'desc'=>'Mampu bekerja sama, berkomunikasi, dan berkolaborasi dengan sesama.'],
                    ['icon'=>'fa-lightbulb',     'color'=>'bg-indigo-50 text-indigo-600', 'title'=>'Mandiri',                  'desc'=>'Memiliki prakarsa atas pengembangan diri dan mampu regulasi diri.'],
                    ['icon'=>'fa-brain',         'color'=>'bg-violet-50 text-violet-600', 'title'=>'Bernalar Kritis',           'desc'=>'Mampu memproses informasi, menganalisis, serta mengevaluasi secara objektif.'],
                    ['icon'=>'fa-magic',         'color'=>'bg-pink-50 text-pink-600',     'title'=>'Kreatif',                  'desc'=>'Menghasilkan gagasan, karya, dan tindakan orisinal yang bermakna.'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 fade-up">
                @foreach ($dimensi as $d)
                <div class="kurikulum-card">
                    <div class="kurikulum-card-header">
                        <div class="w-11 h-11 {{ $d['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa {{ $d['icon'] }}"></i>
                        </div>
                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ $d['title'] }}</p>
                    </div>
                    <div class="kurikulum-card-body">
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $d['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         JADWAL & BEBAN BELAJAR
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                {{-- Beban Belajar --}}
                <div class="fade-up">
                    <div class="sec-label">Beban Belajar</div>
                    <h2 class="font-display text-2xl md:text-3xl font-black text-gray-900 mb-6">Struktur Waktu</h2>

                    <div class="space-y-3">
                        @php
                            $beban = [
                                ['kelas'=>'Kelas I – II',   'jp'=>'30',  'hari'=>'5', 'masuk'=>'07.00',  'pulang'=>'12.00', 'color'=>'bg-blue-50 text-blue-700'],
                                ['kelas'=>'Kelas III',      'jp'=>'32',  'hari'=>'5', 'masuk'=>'07.00',  'pulang'=>'12.30', 'color'=>'bg-indigo-50 text-indigo-700'],
                                ['kelas'=>'Kelas IV – VI',  'jp'=>'36',  'hari'=>'5', 'masuk'=>'07.00',  'pulang'=>'13.30', 'color'=>'bg-violet-50 text-violet-700'],
                            ];
                        @endphp
                        @foreach ($beban as $b)
                        <div class="bg-white border border-gray-100 rounded-2xl p-5 flex items-center gap-4 hover:border-blue-200 hover:shadow-md transition-all">
                            <span class="struktur-badge {{ $b['color'] }} font-black text-sm px-3 py-1.5 min-w-[110px] text-center rounded-xl">
                                {{ $b['kelas'] }}
                            </span>
                            <div class="flex-1 grid grid-cols-3 gap-2 text-center">
                                <div>
                                    <p class="font-black text-gray-900 text-base">{{ $b['jp'] }}</p>
                                    <p class="text-gray-400 text-[10px]">JP/Minggu</p>
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 text-base">{{ $b['masuk'] }}</p>
                                    <p class="text-gray-400 text-[10px]">Masuk</p>
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 text-base">{{ $b['pulang'] }}</p>
                                    <p class="text-gray-400 text-[10px]">Pulang</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-5 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
                        <i class="fa fa-info-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                        <p class="text-amber-800 text-xs leading-relaxed">
                            Hari efektif sekolah minimal <strong>200 hari</strong> per tahun. Setiap jam pelajaran berdurasi <strong>35 menit</strong>.
                        </p>
                    </div>
                </div>

                {{-- Kalender Akademik Timeline --}}
                <div class="fade-up">
                    <div class="sec-label">Kalender Akademik</div>
                    <h2 class="font-display text-2xl md:text-3xl font-black text-gray-900 mb-6">Tahapan Semester</h2>

                    <div class="space-y-0">
                        @php
                            $kalender = [
                                ['bulan'=>'Juli – Agustus',     'kegiatan'=>'Awal Tahun Ajaran & MPLS',         'icon'=>'fa-flag-checkered', 'color'=>'bg-blue-600'],
                                ['bulan'=>'September',          'kegiatan'=>'Penilaian Tengah Semester 1',       'icon'=>'fa-clipboard-list',  'color'=>'bg-indigo-600'],
                                ['bulan'=>'November – Desember','kegiatan'=>'Penilaian Akhir Semester 1',        'icon'=>'fa-star',            'color'=>'bg-violet-600'],
                                ['bulan'=>'Januari',            'kegiatan'=>'Awal Semester 2',                   'icon'=>'fa-sync',            'color'=>'bg-blue-600'],
                                ['bulan'=>'Maret',              'kegiatan'=>'Penilaian Tengah Semester 2',       'icon'=>'fa-clipboard-list',  'color'=>'bg-indigo-600'],
                                ['bulan'=>'Mei – Juni',         'kegiatan'=>'Penilaian Akhir Tahun & Kenaikan', 'icon'=>'fa-trophy',          'color'=>'bg-amber-500'],
                            ];
                        @endphp
                        @foreach ($kalender as $k)
                        <div class="timeline-item">
                            <div class="timeline-dot" style="background: {{ str_contains($k['color'],'amber') ? '#f59e0b' : (str_contains($k['color'],'violet') ? '#7c3aed' : (str_contains($k['color'],'indigo') ? '#4338ca' : '#1d4ed8')) }};">
                                <i class="fa {{ $k['icon'] }}"></i>
                            </div>
                            <div class="timeline-card">
                                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mb-1">{{ $k['bulan'] }}</p>
                                <p class="font-bold text-gray-900 text-sm">{{ $k['kegiatan'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         EKSTRAKURIKULER
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">

            <div class="flex items-end justify-between gap-4 mb-10 fade-up">
                <div>
                    <div class="sec-label">Pengembangan Diri</div>
                    <h2 class="font-display text-2xl md:text-3xl font-black text-gray-900">Ekstrakurikuler</h2>
                </div>
            </div>

            @php
                $ekskul = [
                    ['nama'=>'Pramuka',             'icon'=>'fa-campground',     'color'=>'bg-amber-50 text-amber-600',   'hari'=>'Jumat',    'jam'=>'14.00 – 16.00', 'wajib'=>true],
                    ['nama'=>'Seni Tari',            'icon'=>'fa-music',          'color'=>'bg-pink-50 text-pink-600',     'hari'=>'Selasa',   'jam'=>'14.00 – 15.30', 'wajib'=>false],
                    ['nama'=>'Futsal',               'icon'=>'fa-futbol',         'color'=>'bg-green-50 text-green-600',   'hari'=>'Rabu',     'jam'=>'14.00 – 15.30', 'wajib'=>false],
                    ['nama'=>'Paduan Suara',         'icon'=>'fa-microphone',     'color'=>'bg-blue-50 text-blue-600',     'hari'=>'Kamis',    'jam'=>'14.00 – 15.30', 'wajib'=>false],
                    ['nama'=>'Seni Lukis',           'icon'=>'fa-paint-brush',    'color'=>'bg-purple-50 text-purple-600', 'hari'=>'Senin',    'jam'=>'14.00 – 15.30', 'wajib'=>false],
                    ['nama'=>'Olimpiade Sains',      'icon'=>'fa-atom',           'color'=>'bg-indigo-50 text-indigo-600', 'hari'=>'Sabtu',    'jam'=>'08.00 – 10.00', 'wajib'=>false],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 fade-up">
                @foreach ($ekskul as $ek)
                <div class="mapel-row">
                    <div class="w-12 h-12 {{ $ek['color'] }} rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa {{ $ek['icon'] }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="font-bold text-gray-900 text-sm">{{ $ek['nama'] }}</p>
                            @if($ek['wajib'])
                            <span class="text-[9px] font-bold bg-red-100 text-red-600 px-2 py-0.5 rounded-full">WAJIB</span>
                            @else
                            <span class="text-[9px] font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">PILIHAN</span>
                            @endif
                        </div>
                        <p class="text-gray-400 text-xs">
                            <i class="fa fa-calendar-day mr-1"></i>{{ $ek['hari'] }}
                            <span class="mx-1.5 text-gray-300">•</span>
                            <i class="fa fa-clock mr-1"></i>{{ $ek['jam'] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const obs = new IntersectionObserver(entries => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('visible'), i * 90);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
});
</script>
@endpush