@extends('layouts.public')

@section('title', $pageTitle ?? 'Prestasi — SDN Sukorame 1')

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

    /* ── PRESTASI CARD ── */
    .prestasi-card {
        background: white; border-radius: 20px; overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all .3s; position: relative;
    }
    .prestasi-card:hover { transform: translateY(-5px); box-shadow: 0 20px 48px rgba(29,78,216,.11); border-color: #bfdbfe; }
    .prestasi-card::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
        background: #1d4ed8; opacity: 0; transition: opacity .3s;
    }
    .prestasi-card:hover::after { opacity: 1; }

    .prestasi-card.juara-1 { border-color: #fde68a; box-shadow: 0 8px 32px rgba(217,119,6,.12); }
    .prestasi-card.juara-1::after { background: linear-gradient(90deg,#d97706,#f59e0b); opacity: 1; }
    .prestasi-card.juara-1:hover { box-shadow: 0 20px 48px rgba(217,119,6,.22); border-color: #f59e0b; }

    .prestasi-card.juara-2 { border-color: #e2e8f0; }
    .prestasi-card.juara-2::after { background: linear-gradient(90deg,#64748b,#94a3b8); opacity: 1; }

    .prestasi-card.juara-3 { border-color: #fed7aa; }
    .prestasi-card.juara-3::after { background: linear-gradient(90deg,#c2410c,#ea580c); opacity: 1; }

    /* ── MEDAL ICON ── */
    .medal-wrap {
        height: 120px; display: flex; align-items: center; justify-content: center;
        position: relative;
    }
    .medal-icon {
        width: 64px; height: 64px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; position: relative;
    }
    .medal-icon.emas   { background: linear-gradient(135deg,#d97706,#fbbf24); box-shadow: 0 4px 16px rgba(251,191,36,.4); }
    .medal-icon.perak  { background: linear-gradient(135deg,#64748b,#cbd5e1); box-shadow: 0 4px 16px rgba(148,163,184,.4); }
    .medal-icon.perunggu{ background: linear-gradient(135deg,#c2410c,#fb923c); box-shadow: 0 4px 16px rgba(251,146,60,.35); }
    .medal-icon.lainnya { background: linear-gradient(135deg,#1d4ed8,#60a5fa); box-shadow: 0 4px 16px rgba(96,165,250,.35); }

    .rank-badge {
        position: absolute; top: 10px; right: 10px;
        font-size: 9px; font-weight: 800; letter-spacing: .06em;
        padding: 3px 10px; border-radius: 999px; text-transform: uppercase;
    }

    /* ── FILTER TABS ── */
    .filter-tab {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12px; font-weight: 700; padding: 7px 18px;
        border-radius: 999px; border: 1.5px solid #e5e7eb;
        background: white; color: #6b7280; cursor: pointer;
        transition: all .2s;
    }
    .filter-tab:hover { border-color: #93c5fd; color: #1d4ed8; background: #eff6ff; }
    .filter-tab.active { background: #1d4ed8; color: white; border-color: #1d4ed8; }

    /* ── UNGGULAN ROW (prestasi menonjol) ── */
    .unggulan-card {
        background: white; border-radius: 20px;
        border: 1px solid #f1f5f9;
        transition: all .3s; display: flex; align-items: center; gap: 20px;
        padding: 20px 24px; position: relative; overflow: hidden;
    }
    .unggulan-card:hover { transform: translateY(-3px); box-shadow: 0 16px 40px rgba(29,78,216,.1); border-color: #bfdbfe; }
    .unggulan-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
        border-radius: 0 3px 3px 0;
    }
    .unggulan-card.emas::before   { background: linear-gradient(180deg,#d97706,#fbbf24); }
    .unggulan-card.perak::before  { background: linear-gradient(180deg,#64748b,#cbd5e1); }
    .unggulan-card.perunggu::before { background: linear-gradient(180deg,#c2410c,#fb923c); }

    /* ── STAT BOX ── */
    .stat-box {
        background: white; border-radius: 18px; border: 1px solid #f1f5f9;
        padding: 20px; text-align: center; transition: all .25s;
    }
    .stat-box:hover { border-color: #bfdbfe; box-shadow: 0 8px 24px rgba(29,78,216,.08); transform: translateY(-3px); }

    /* Animasi */
    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }

    /* Filter hide/show */
    .prestasi-item { transition: opacity .25s, transform .25s; }
    .prestasi-item.hidden-item { opacity: 0; transform: scale(.95); pointer-events: none; height: 0; overflow: hidden; margin: 0; padding: 0; }
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
            <a href="#" class="hover:text-white transition-colors">Sekolah</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Prestasi</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-amber-300 rounded-full"></span> Kebanggaan Sekolah
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Prestasi<br>
                    <span style="color:#FDE68A;">Siswa & Sekolah</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-xl" style="font-size:1rem;">
                    Pencapaian membanggakan yang diraih oleh siswa dan sekolah SDN Sukorame 1
                    di berbagai bidang — akademik, seni, olahraga, dan lainnya.
                </p>

                <div class="flex flex-wrap gap-3 mt-7">
                    @foreach([
                        ['#unggulan', 'fa-trophy',      'Prestasi Unggulan'],
                        ['#akademik', 'fa-book',        'Akademik'],
                        ['#nonakademik','fa-futbol',    'Non-Akademik'],
                    ] as $n)
                    <a href="{{ $n[0] }}" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold px-4 py-2 rounded-full transition-all">
                        <i class="fa {{ $n[1] }} text-amber-300 text-[10px]"></i> {{ $n[2] }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Hero stat --}}
            @php
                $totalPrestasi  = isset($prestasi) ? $prestasi->count() : 24;
                $totalJuara1    = isset($prestasi) ? $prestasi->where('juara','Juara 1')->count() : 10;
                $hs = [
                    ['val' => $totalPrestasi, 'lbl' => 'Total Prestasi', 'ico' => 'fa-trophy'],
                    ['val' => $totalJuara1,   'lbl' => 'Juara 1',        'ico' => 'fa-medal'],
                    ['val' => date('Y'),      'lbl' => 'Tahun Ini',      'ico' => 'fa-calendar'],
                ];
            @endphp
            <div class="flex gap-3 flex-shrink-0">
                @foreach($hs as $h)
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
         RINGKASAN STAT
    ══════════════════════════════════════ --}}
    <section class="py-14 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 fade-up">
                @php
                    $stats = [
                        ['val'=>'10', 'lbl'=>'Juara 1',      'ico'=>'fa-trophy',       'bg'=>'bg-amber-50',  'ico_c'=>'text-amber-500'],
                        ['val'=>'8',  'lbl'=>'Juara 2',      'ico'=>'fa-medal',         'bg'=>'bg-slate-50',  'ico_c'=>'text-slate-500'],
                        ['val'=>'6',  'lbl'=>'Juara 3',      'ico'=>'fa-award',         'bg'=>'bg-orange-50', 'ico_c'=>'text-orange-500'],
                        ['val'=>'5+', 'lbl'=>'Penghargaan',  'ico'=>'fa-certificate',   'bg'=>'bg-blue-50',   'ico_c'=>'text-blue-600'],
                    ];
                @endphp
                @foreach($stats as $s)
                <div class="stat-box">
                    <div class="w-11 h-11 {{ $s['bg'] }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa {{ $s['ico'] }} {{ $s['ico_c'] }} text-lg"></i>
                    </div>
                    <p class="font-black text-gray-900 text-2xl">{{ $s['val'] }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $s['lbl'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         PRESTASI UNGGULAN
    ══════════════════════════════════════ --}}
    <section id="unggulan" class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Prestasi Unggulan</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Capaian Terbaik Kami</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Prestasi paling membanggakan yang diraih SDN Sukorame 1 di tingkat kota, provinsi, dan nasional.
                </p>
            </div>

            @php
                $unggulan = [
                    [
                        'juara'   => 'Juara 1',
                        'level'   => 'emas',
                        'nama'    => 'Olimpiade Matematika Tingkat Kota Kediri',
                        'tahun'   => '2024',
                        'tingkat' => 'Tingkat Kota',
                        'peserta' => 'Siswa Kelas VI',
                        'ico'     => 'fa-calculator',
                        'bidang'  => 'Akademik',
                    ],
                    [
                        'juara'   => 'Juara 1',
                        'level'   => 'emas',
                        'nama'    => 'Lomba Pidato Bahasa Indonesia',
                        'tahun'   => '2024',
                        'tingkat' => 'Tingkat Kecamatan',
                        'peserta' => 'Siswa Kelas V',
                        'ico'     => 'fa-microphone',
                        'bidang'  => 'Akademik',
                    ],
                    [
                        'juara'   => 'Juara 2',
                        'level'   => 'perak',
                        'nama'    => 'Festival Seni Tari Jawa Timur',
                        'tahun'   => '2023',
                        'tingkat' => 'Tingkat Provinsi',
                        'peserta' => 'Tim Seni',
                        'ico'     => 'fa-music',
                        'bidang'  => 'Non-Akademik',
                    ],
                    [
                        'juara'   => 'Juara 1',
                        'level'   => 'emas',
                        'nama'    => 'Turnamen Futsal Antara SD Se-Kota Kediri',
                        'tahun'   => '2023',
                        'tingkat' => 'Tingkat Kota',
                        'peserta' => 'Tim Futsal',
                        'ico'     => 'fa-futbol',
                        'bidang'  => 'Non-Akademik',
                    ],
                    [
                        'juara'   => 'Juara 3',
                        'level'   => 'perunggu',
                        'nama'    => 'Lomba Cerdas Cermat IPA',
                        'tahun'   => '2023',
                        'tingkat' => 'Tingkat Provinsi',
                        'peserta' => 'Siswa Kelas V & VI',
                        'ico'     => 'fa-flask',
                        'bidang'  => 'Akademik',
                    ],
                    [
                        'juara'   => 'Juara 2',
                        'level'   => 'perak',
                        'nama'    => 'Lomba Melukis Tingkat SD',
                        'tahun'   => '2024',
                        'tingkat' => 'Tingkat Kota',
                        'peserta' => 'Siswa Kelas IV',
                        'ico'     => 'fa-palette',
                        'bidang'  => 'Non-Akademik',
                    ],
                ];

                $medalStyle = [
                    'emas'     => ['ico'=>'emas',    'badge'=>'bg-amber-400 text-amber-900', 'card'=>'juara-1'],
                    'perak'    => ['ico'=>'perak',   'badge'=>'bg-slate-200 text-slate-700', 'card'=>'juara-2'],
                    'perunggu' => ['ico'=>'perunggu','badge'=>'bg-orange-100 text-orange-800','card'=>'juara-3'],
                    'lainnya'  => ['ico'=>'lainnya', 'badge'=>'bg-blue-100 text-blue-800',   'card'=>''],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($prestasi->take(6) as $idx => $p)
                @php $ms = $medalStyle[$p->level] ?? $medalStyle['lainnya']; @endphp
                <div class="prestasi-card {{ $ms['card'] }} fade-up" style="transition-delay: {{ ($idx % 3) * 70 }}ms">
                    <div class="medal-wrap bg-gradient-to-br from-gray-50 to-white">
                        <div class="medal-icon {{ $p->level }}">
                            <i class="fa {{ $p->ico }} text-white"></i>
                        </div>
                        <span class="rank-badge {{ $ms['badge'] }}">{{ $p->juara }}</span>
                    </div>
                    <div class="p-5">
                        <p class="font-black text-gray-900 text-sm leading-snug mb-2">{{ $p->nama }}</p>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full font-semibold">
                                <i class="fa fa-location-dot text-[9px]"></i> {{ $p->tingkat }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-semibold">
                                <i class="fa fa-calendar text-[9px]"></i> {{ $p->tahun }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500 border-t border-gray-50 pt-3">
                            <i class="fa fa-user text-gray-300 text-[10px]"></i>
                            {{ $p->peserta }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-10 text-gray-400">Belum ada data prestasi unggulan.</div>
                @endforelse
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         SEMUA PRESTASI (dengan filter)
    ══════════════════════════════════════ --}}
    <section id="akademik" class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-10 fade-up">
                <div class="sec-label">Daftar Lengkap</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Semua Prestasi</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Daftar lengkap pencapaian SDN Sukorame 1 dari berbagai bidang dan jenjang kompetisi.
                </p>
            </div>

            {{-- Filter tabs --}}
            <div class="flex flex-wrap gap-2 justify-center mb-10 fade-up">
                <button class="filter-tab active" data-filter="all">
                    <i class="fa fa-list text-[10px]"></i> Semua
                </button>
                <button class="filter-tab" data-filter="akademik">
                    <i class="fa fa-book text-[10px]"></i> Akademik
                </button>
                <button class="filter-tab" data-filter="non-akademik">
                    <i class="fa fa-futbol text-[10px]"></i> Non-Akademik
                </button>
                <button class="filter-tab" data-filter="seni">
                    <i class="fa fa-palette text-[10px]"></i> Seni & Budaya
                </button>
                <button class="filter-tab" data-filter="olahraga">
                    <i class="fa fa-dumbbell text-[10px]"></i> Olahraga
                </button>
            </div>

            @php
                $semuaPrestasi = isset($prestasi) && $prestasi->count() > 0 ? $prestasi : collect([
                    // Akademik
                    ['juara'=>'Juara 1','nama'=>'Olimpiade Matematika','tingkat'=>'Tingkat Kota','tahun'=>'2024','peserta'=>'Siswa Kelas VI','bidang'=>'akademik','level'=>'emas'],
                    ['juara'=>'Juara 1','nama'=>'Lomba Pidato Bahasa Indonesia','tingkat'=>'Tingkat Kecamatan','tahun'=>'2024','peserta'=>'Siswa Kelas V','bidang'=>'akademik','level'=>'emas'],
                    ['juara'=>'Juara 2','nama'=>'Cerdas Cermat IPA','tingkat'=>'Tingkat Kota','tahun'=>'2024','peserta'=>'Siswa Kelas V','bidang'=>'akademik','level'=>'perak'],
                    ['juara'=>'Juara 3','nama'=>'Lomba Cerdas Cermat IPA','tingkat'=>'Tingkat Provinsi','tahun'=>'2023','peserta'=>'Siswa Kelas V & VI','bidang'=>'akademik','level'=>'perunggu'],
                    ['juara'=>'Juara 1','nama'=>'Lomba Baca Puisi','tingkat'=>'Tingkat Kecamatan','tahun'=>'2023','peserta'=>'Siswa Kelas IV','bidang'=>'akademik','level'=>'emas'],
                    ['juara'=>'Juara 2','nama'=>'Olimpiade IPA','tingkat'=>'Tingkat Kota','tahun'=>'2023','peserta'=>'Siswa Kelas VI','bidang'=>'akademik','level'=>'perak'],
                    // Non-Akademik
                    ['juara'=>'Juara 1','nama'=>'Turnamen Futsal SD','tingkat'=>'Tingkat Kota','tahun'=>'2023','peserta'=>'Tim Futsal','bidang'=>'olahraga','level'=>'emas'],
                    ['juara'=>'Juara 2','nama'=>'Lomba Lari 100m','tingkat'=>'Tingkat Kecamatan','tahun'=>'2024','peserta'=>'Siswa Kelas V','bidang'=>'olahraga','level'=>'perak'],
                    ['juara'=>'Juara 1','nama'=>'Senam Irama SD','tingkat'=>'Tingkat Kota','tahun'=>'2023','peserta'=>'Tim Senam','bidang'=>'olahraga','level'=>'emas'],
                    ['juara'=>'Juara 3','nama'=>'Renang Gaya Bebas','tingkat'=>'Tingkat Kota','tahun'=>'2024','peserta'=>'Siswa Kelas VI','bidang'=>'olahraga','level'=>'perunggu'],
                    // Seni
                    ['juara'=>'Juara 2','nama'=>'Festival Seni Tari Jawa Timur','tingkat'=>'Tingkat Provinsi','tahun'=>'2023','peserta'=>'Tim Seni','bidang'=>'seni','level'=>'perak'],
                    ['juara'=>'Juara 2','nama'=>'Lomba Melukis Tingkat SD','tingkat'=>'Tingkat Kota','tahun'=>'2024','peserta'=>'Siswa Kelas IV','bidang'=>'seni','level'=>'perak'],
                    ['juara'=>'Juara 1','nama'=>'Lomba Tari Daerah','tingkat'=>'Tingkat Kecamatan','tahun'=>'2024','peserta'=>'Tim Tari','bidang'=>'seni','level'=>'emas'],
                    ['juara'=>'Juara 3','nama'=>'Festival Paduan Suara','tingkat'=>'Tingkat Kota','tahun'=>'2023','peserta'=>'Paduan Suara','bidang'=>'seni','level'=>'perunggu'],
                ]);

                $badgeJuara = [
                    'emas'     => 'bg-amber-400 text-amber-900',
                    'perak'    => 'bg-slate-200 text-slate-700',
                    'perunggu' => 'bg-orange-100 text-orange-800',
                    'lainnya'  => 'bg-blue-100 text-blue-800',
                ];
                $dotJuara = [
                    'emas'     => 'bg-amber-400',
                    'perak'    => 'bg-slate-400',
                    'perunggu' => 'bg-orange-400',
                    'lainnya'  => 'bg-blue-400',
                ];
                $icoJuara = [
                    'emas'     => 'fa-trophy text-amber-500',
                    'perak'    => 'fa-medal text-slate-400',
                    'perunggu' => 'fa-award text-orange-400',
                    'lainnya'  => 'fa-certificate text-blue-500',
                ];
            @endphp

            <div id="prestasi-grid" class="space-y-3">
                @foreach($semuaPrestasi as $idx => $p)
                @php
                    $level   = is_array($p) ? ($p['level']   ?? 'lainnya') : ($p->level   ?? 'lainnya');
                    $juara   = is_array($p) ? ($p['juara']   ?? '—')       : ($p->juara   ?? '—');
                    $nama    = is_array($p) ? ($p['nama']    ?? '—')       : ($p->nama    ?? '—');
                    $tingkat = is_array($p) ? ($p['tingkat'] ?? '—')       : ($p->tingkat ?? '—');
                    $tahun   = is_array($p) ? ($p['tahun']  ?? '—')        : ($p->tahun   ?? '—');
                    $peserta = is_array($p) ? ($p['peserta'] ?? '—')       : ($p->peserta ?? '—');
                    $bidang  = is_array($p) ? ($p['bidang']  ?? 'lainnya') : ($p->bidang  ?? 'lainnya');
                @endphp
                <div class="unggulan-card {{ $level }} prestasi-item fade-up"
                     data-bidang="{{ $bidang }}"
                     style="transition-delay: {{ ($idx % 8) * 40 }}ms">
                    <div class="w-10 h-10 medal-icon {{ $level }} flex-shrink-0" style="width:40px;height:40px;font-size:1rem;">
                        <i class="fa fa-trophy text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-gray-900 text-sm leading-snug">{{ $nama }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                            <span class="inline-flex items-center gap-1 text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full font-semibold">
                                <i class="fa fa-location-dot text-[9px]"></i> {{ $tingkat }}
                            </span>
                            <span class="text-xs text-gray-400 font-semibold">
                                <i class="fa fa-user text-[9px] mr-1"></i>{{ $peserta }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                        <span class="rank-badge {{ $badgeJuara[$level] ?? 'bg-blue-100 text-blue-800' }}" style="position:static;">
                            {{ $juara }}
                        </span>
                        <span class="text-xs text-gray-400 font-semibold">{{ $tahun }}</span>
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
    // Fade-up
    const obs = new IntersectionObserver(entries => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('visible'), i * 60);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));

    // Filter tabs
    const tabs  = document.querySelectorAll('.filter-tab');
    const items = document.querySelectorAll('.prestasi-item');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.dataset.filter;
            items.forEach(item => {
                const bidang = item.dataset.bidang;
                if (filter === 'all' || bidang === filter) {
                    item.classList.remove('hidden-item');
                } else {
                    item.classList.add('hidden-item');
                }
            });
        });
    });
});
</script>
@endpush