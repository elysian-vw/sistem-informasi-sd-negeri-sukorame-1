@extends('layouts.public')

@section('title', $pageTitle ?? 'Sejarah - SDN Sukorame 1')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --blue: #1D4ED8;
        --blue-dark: #1e3a8a;
        --blue-light: #EFF6FF;
        --gold: #D97706;
    }
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

    /* ── CMS CONTENT STYLING ── */
    .cms-content p { margin-bottom: 1.2rem; color: #4b5563; line-height: 1.8; font-size: 14px; }
    .cms-content strong { color: #1d4ed8; font-weight: 700; }
    .cms-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.2rem; color: #4b5563; }
    .cms-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.2rem; color: #4b5563; }

    /* ── STAT CARD ── */
    .stat-card {
        background: white; border-radius: 20px; padding: 24px 20px; text-align: center;
        border: 1px solid #f1f5f9;
        transition: all .25s;
        position: relative; overflow: hidden;
    }
    .stat-card::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(29,78,216,.1); border-color: #bfdbfe; }

    /* ── TIMELINE ── */
    .timeline-wrap { position: relative; }
    .timeline-wrap::before {
        content: ''; position: absolute;
        left: 50%; top: 0; bottom: 0; width: 2px;
        background: linear-gradient(to bottom, #bfdbfe 0%, #1d4ed8 30%, #1d4ed8 70%, #bfdbfe 100%);
        transform: translateX(-50%);
    }
    @media (max-width: 768px) {
        .timeline-wrap::before { left: 28px; }
    }

    .tl-item {
        display: flex; align-items: flex-start;
        gap: 0; margin-bottom: 0;
        position: relative;
    }

    .era-badge {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 10px; font-weight: 800; letter-spacing: .12em;
        text-transform: uppercase; padding: 4px 12px; border-radius: 999px;
        margin-bottom: 8px;
    }

    .tl-card {
        width: calc(50% - 52px);
        background: white; border-radius: 20px; padding: 22px 24px;
        border: 1px solid #f1f5f9;
        position: relative;
        transition: all .3s;
        cursor: default;
    }
    .tl-card:hover { border-color: #bfdbfe; box-shadow: 0 12px 36px rgba(29,78,216,.1); transform: scale(1.01); }
    .tl-card.left  { margin-right: auto; }
    .tl-card.right { margin-left: auto; }

    .tl-card.left::after {
        content: ''; position: absolute;
        top: 24px; right: -9px;
        border: 8px solid transparent;
        border-left-color: #f1f5f9;
    }
    .tl-card.left:hover::after { border-left-color: #bfdbfe; }
    .tl-card.right::after {
        content: ''; position: absolute;
        top: 24px; left: -9px;
        border: 8px solid transparent;
        border-right-color: #f1f5f9;
    }
    .tl-card.right:hover::after { border-right-color: #bfdbfe; }

    .tl-dot-wrap {
        position: absolute; left: 50%; top: 20px;
        transform: translateX(-50%);
        display: flex; align-items: center; justify-content: center;
        z-index: 10;
    }
    .tl-dot {
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: #1d4ed8; border: 4px solid white;
        box-shadow: 0 0 0 2px #bfdbfe;
        font-size: 13px; color: white; font-weight: 800;
        transition: all .25s;
        flex-shrink: 0;
    }
    .tl-item:hover .tl-dot { background: #1e3a8a; box-shadow: 0 0 0 4px #bfdbfe; transform: scale(1.12); }
    .tl-dot.milestone {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        box-shadow: 0 0 0 2px #fde68a;
    }
    .tl-item:hover .tl-dot.milestone { box-shadow: 0 0 0 4px #fde68a; }

    .tl-year {
        display: inline-flex; align-items: center;
        background: #1d4ed8; color: white;
        font-weight: 800; font-size: 12px;
        padding: 3px 12px; border-radius: 999px;
        margin-bottom: 8px; letter-spacing: .03em;
    }
    .tl-year.gold { background: linear-gradient(135deg,#d97706,#f59e0b); }

    @media (max-width: 768px) {
        .tl-dot-wrap { left: 28px; }
        .tl-card { width: calc(100% - 68px); margin-left: 68px !important; margin-right: 0 !important; }
        .tl-card.left::after, .tl-card.right::after {
            top: 24px; left: -9px; right: auto;
            border: 8px solid transparent;
            border-right-color: #f1f5f9;
            border-left-color: transparent;
        }
        .tl-card.left:hover::after, .tl-card.right:hover::after {
            border-right-color: #bfdbfe;
        }
    }

    /* ── NARASI SEJARAH ── */
    .narasi-card {
        background: white; border-radius: 24px; padding: 40px 44px;
        border: 1px solid #e0e7ff;
        position: relative; overflow: hidden;
        box-shadow: 0 4px 24px rgba(29,78,216,.06);
    }
    .narasi-card::before {
        content: ''; position: absolute;
        top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, #1e3a8a, #1d4ed8, #3b82f6, #d97706);
    }
    .quote-deco {
        font-family: 'Playfair Display', serif;
        font-size: 100px; line-height: .8;
        color: #dbeafe; position: absolute;
        top: 20px; right: 28px; pointer-events: none; user-select: none;
    }

    .section-divider { height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent); }

    /* Animasi masuk */
    .fade-up { opacity: 0; transform: translateY(20px); transition: opacity .5s ease, transform .5s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
    .tl-card { opacity: 0; transition: opacity .4s ease, transform .3s ease, border-color .3s, box-shadow .3s; }
    .tl-card.visible { opacity: 1; }
</style>
@endpush

@section('content')

{{-- 1. RUMUS SAKTI PENGAMBIL DATA DATABASE --}}
@php
    // Berikan default teks jika Admin belum mengisi
    $kontenDinams = $sejarah && !empty($sejarah->content) ? $sejarah->content : '<p>Konten sejarah belum diisi dari Admin. Silakan buka menu Kelola Konten dan tambahkan halaman dengan slug <strong>profil-sejarah</strong>.</p>';
@endphp

{{-- ══════════════════════════════════════
     PAGE HERO
══════════════════════════════════════ --}}
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div style="position:absolute;width:420px;height:420px;right:-100px;top:-120px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;width:250px;height:250px;left:8%;bottom:-80px;border-radius:50%;background:radial-gradient(circle,rgba(217,119,6,.12) 0%,transparent 70%);pointer-events:none;"></div>

    <div class="max-w-5xl mx-auto px-6 relative z-10">
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Profil</span>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Sejarah Sekolah</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-amber-300 rounded-full"></span> Profil Sekolah
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Sejarah<br>
                    <span style="color:#FDE68A;">SDN Sukorame 1</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-lg" style="font-size: 1rem;">
                    Hampir <strong class="text-white">6 dekade</strong> melayani pendidikan terbaik Kota Kediri.
                    Dari tahun 1965 hingga hari ini, sebuah perjalanan yang penuh dedikasi dan prestasi.
                </p>
            </div>

            <div class="flex gap-3 flex-shrink-0">
                @php
                    $hero_stats = [
                        ['val'=>'±1965','lbl'=>'Tahun Berdiri','ico'=>'fa-flag'],
                        ['val'=>'59+',  'lbl'=>'Tahun Melayani','ico'=>'fa-history'],
                        ['val'=>'B',    'lbl'=>'Akreditasi',   'ico'=>'fa-star'],
                    ];
                @endphp
                @foreach ($hero_stats as $hs)
                <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center w-24 backdrop-blur-sm">
                    <i class="fa {{ $hs['ico'] }} text-amber-300 text-base mb-2 block"></i>
                    <p class="text-white font-black text-lg leading-none">{{ $hs['val'] }}</p>
                    <p class="text-white/55 text-[10px] mt-1 leading-tight">{{ $hs['lbl'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<main class="bg-gray-50">

    {{-- ══════════════════════════════════════
         NARASI SEJARAH (DINAMIS DARI ADMIN)
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-10 fade-up">
                <div class="sec-label">Tentang Kami</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900">Kisah Awal Sebuah Sekolah</h2>
            </div>

            <div class="narasi-card fade-up">
                <div class="quote-deco">"</div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-8 items-start">
                    
                    {{-- 2. DISINILAH KONTEN DARI ADMIN DITAMPILKAN --}}
                    <div class="md:col-span-3 cms-content">
                        {!! $kontenDinams !!}
                    </div>

                    {{-- Kolom kanan: identitas cepat (Tetap Statis) --}}
                    <div class="md:col-span-2">
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 space-y-3">
                            <p class="text-blue-800 font-bold text-xs uppercase tracking-wider mb-4">Identitas Singkat</p>
                            @php
                                $ident = [
                                    ['lbl'=>'Didirikan',    'val'=>'Tahun 1965'],
                                    ['lbl'=>'NPSN',         'val'=>'20533972'],
                                    ['lbl'=>'Status',       'val'=>'Negeri / Pemerintah'],
                                    ['lbl'=>'Akreditasi',   'val'=>'B — Cukup Baik'],
                                    ['lbl'=>'Kurikulum',    'val'=>'Kurikulum Merdeka'],
                                    ['lbl'=>'Kecamatan',    'val'=>'Mojoroto, Kota Kediri'],
                                ];
                            @endphp
                            @foreach ($ident as $id)
                            <div class="flex justify-between items-start gap-2 text-xs border-b border-blue-100 pb-2 last:border-0 last:pb-0">
                                <span class="text-blue-500 font-semibold">{{ $id['lbl'] }}</span>
                                <span class="text-blue-900 font-bold text-right">{{ $id['val'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         STATISTIK (TETAP STATIS AGAR DESAIN AMAN)
    ══════════════════════════════════════ --}}
    <section class="py-14 bg-gray-50">
        <div class="max-w-4xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $stats = [
                        ['val'=>'±59','unit'=>'Tahun','lbl'=>'Berdiri & Melayani','ico'=>'fa-history','color'=>'blue','accent'=>'after:bg-blue-500'],
                        ['val'=>'5.000+','unit'=>'Alumni','lbl'=>'Telah Diluluskan','ico'=>'fa-user-graduate','color'=>'indigo','accent'=>'after:bg-indigo-500'],
                        ['val'=>'B','unit'=>'Akreditasi','lbl'=>'BAN-SM Kemdikbud','ico'=>'fa-award','color'=>'amber','accent'=>'after:bg-amber-500'],
                        ['val'=>'2022','unit'=>'Tahun','lbl'=>'Platform SIMAS Lahir','ico'=>'fa-laptop','color'=>'green','accent'=>'after:bg-green-500'],
                    ];
                    $sc = [
                        'blue'  =>['bg'=>'bg-blue-50',  'ico'=>'text-blue-600',  'val'=>'text-blue-700'],
                        'indigo'=>['bg'=>'bg-indigo-50','ico'=>'text-indigo-600','val'=>'text-indigo-700'],
                        'amber' =>['bg'=>'bg-amber-50', 'ico'=>'text-amber-600', 'val'=>'text-amber-700'],
                        'green' =>['bg'=>'bg-green-50', 'ico'=>'text-green-600', 'val'=>'text-green-700'],
                    ];
                @endphp
                @foreach ($stats as $s)
                @php $cc = $sc[$s['color']]; @endphp
                <div class="stat-card {{ $s['accent'] }}">
                    <div class="w-12 h-12 {{ $cc['bg'] }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa {{ $s['ico'] }} {{ $cc['ico'] }} text-xl"></i>
                    </div>
                    <p class="text-2xl font-black {{ $cc['val'] }} leading-none">{{ $s['val'] }}</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold mt-0.5">{{ $s['unit'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 leading-snug">{{ $s['lbl'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         TIMELINE (TETAP STATIS AGAR DESAIN AMAN)
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-white" id="timeline">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-16 fade-up">
                <div class="sec-label">Perjalanan Waktu</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Tonggak Sejarah Kami</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Setiap tahun adalah babak baru. Setiap pencapaian adalah bukti dedikasi seluruh warga sekolah.
                </p>
            </div>

            @php
                $timeline = [
                    [
                        'year'  => '1965', 'era' => 'Era Pendirian', 'era_c' => 'bg-blue-100 text-blue-800',
                        'icon'  => 'fa-flag', 'milestone' => false, 'title' => 'Pendirian Sekolah',
                        'desc'  => 'SDN Sukorame 1 resmi didirikan oleh Pemerintah Kota Kediri...',
                        'side'  => 'left', 'tags'  => ['Negeri','Mojoroto','Kota Kediri'],
                    ],
                    [
                        'year'  => '1975', 'era' => null, 'icon' => 'fa-users', 'milestone' => false,
                        'title' => 'Pertumbuhan Siswa Pesat', 'desc' => 'Animo masyarakat yang tinggi mendorong pertumbuhan jumlah siswa...',
                        'side'  => 'right', 'tags' => ['Pertumbuhan','Komunitas'],
                    ],
                    [
                        'year'  => '1985', 'era' => 'Era Pembangunan', 'era_c' => 'bg-indigo-100 text-indigo-800',
                        'icon'  => 'fa-building', 'milestone' => false, 'title' => 'Renovasi & Perluasan Gedung',
                        'desc'  => 'Dilaksanakan renovasi besar-besaran gedung sekolah...',
                        'side'  => 'left', 'tags' => ['Renovasi','Infrastruktur'],
                    ],
                    [
                        'year'  => '2000', 'era' => 'Era Mutu', 'era_c' => 'bg-amber-100 text-amber-800',
                        'icon'  => 'fa-award', 'milestone' => true, 'title' => 'Akreditasi A Pertama',
                        'desc'  => 'SDN Sukorame 1 berhasil meraih predikat Akreditasi A dari BAN-SM...',
                        'side'  => 'right', 'tags' => ['Akreditasi A','BAN-SM','Pencapaian'],
                    ],
                    [
                        'year'  => '2010', 'era' => null, 'icon' => 'fa-desktop', 'milestone' => false,
                        'title' => 'Lab Komputer & Teknologi', 'desc' => 'Pengenalan program komputer dan teknologi informasi...',
                        'side'  => 'left', 'tags' => ['Teknologi','Lab Komputer','Digital'],
                    ],
                    [
                        'year'  => '2018', 'era' => 'Era Kurikulum', 'era_c' => 'bg-green-100 text-green-800',
                        'icon'  => 'fa-book-open', 'milestone' => false, 'title' => 'Implementasi Kurikulum 2013',
                        'desc'  => 'Mengimplementasikan Kurikulum 2013 secara penuh...',
                        'side'  => 'right', 'tags' => ['K13','Tematik','Berpusat Siswa'],
                    ],
                    [
                        'year'  => '2022', 'era' => 'Era Digital', 'era_c' => 'bg-blue-100 text-blue-800',
                        'icon'  => 'fa-laptop-code', 'milestone' => true, 'title' => 'Peluncuran Platform SIMAS',
                        'desc'  => 'Babak baru digitalisasi sekolah: SIMAS resmi diluncurkan...',
                        'side'  => 'left', 'tags' => ['SIMAS','E-Learning','Digitalisasi'],
                    ],
                    [
                        'year'  => '2024', 'era' => null, 'icon' => 'fa-graduation-cap', 'milestone' => true,
                        'title' => 'Kurikulum Merdeka', 'desc' => 'SDN Sukorame 1 mengadopsi Kurikulum Merdeka...',
                        'side'  => 'right', 'tags' => ['Kurikulum Merdeka','Fasilitas'],
                    ],
                ];
            @endphp

            <div class="timeline-wrap space-y-10">
                @foreach ($timeline as $idx => $t)
                <div class="tl-item relative" style="min-height: 60px;">
                    <div class="tl-dot-wrap">
                        <div class="tl-dot {{ $t['milestone'] ? 'milestone' : '' }}">
                            <i class="fa {{ $t['icon'] }} text-sm"></i>
                        </div>
                    </div>
                    <div class="tl-card {{ $t['side'] }}" style="animation-delay: {{ $idx * 80 }}ms">
                        @if (!empty($t['era']))
                        <span class="era-badge {{ $t['era_c'] }}">
                            <i class="fa fa-bookmark text-[9px]"></i> {{ $t['era'] }}
                        </span>
                        @endif
                        <div class="tl-year {{ $t['milestone'] ? 'gold' : '' }} mb-2">{{ $t['year'] }}</div>
                        <h3 class="font-bold text-gray-900 text-base mb-2 leading-snug">{{ $t['title'] }}</h3>
                        <p class="text-gray-500 text-xs leading-relaxed mb-4">{{ $t['desc'] }}</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($t['tags'] as $tag)
                            <span class="text-[10px] font-semibold bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">{{ $tag }}</span>
                            @endforeach
                        </div>
                        @if ($t['milestone'])
                        <div class="absolute -top-3 {{ $t['side'] === 'right' ? 'right-4' : 'left-4' }} inline-flex items-center gap-1 bg-amber-400 text-amber-900 text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                            <i class="fa fa-star text-[8px]"></i> Tonggak Sejarah
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-center mt-10">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 rounded-full bg-blue-700 border-4 border-white shadow-lg flex items-center justify-center">
                        <i class="fa fa-ellipsis-h text-white text-xs"></i>
                    </div>
                    <p class="text-xs text-gray-400 font-semibold italic">Perjalanan berlanjut…</p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fadeEls = document.querySelectorAll('.fade-up');
    const fadeObs = new IntersectionObserver(entries => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('visible'), i * 80);
                fadeObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });
    fadeEls.forEach(el => fadeObs.observe(el));

    const tlCards = document.querySelectorAll('.tl-card');
    const tlObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                tlObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    tlCards.forEach((el, i) => {
        const isLeft = el.classList.contains('left');
        el.style.transform = isLeft ? 'translateX(-24px)' : 'translateX(24px)';
        el.style.transitionDelay = (i * 60) + 'ms';
        el.style.transitionProperty = 'opacity, transform, border-color, box-shadow';
        tlObs.observe(el);
    });

    const visObs = new MutationObserver(mutations => {
        mutations.forEach(m => {
            if (m.target.classList.contains('visible')) {
                m.target.style.transform = '';
            }
        });
    });
    tlCards.forEach(el => visObs.observe(el, { attributes: true, attributeFilter: ['class'] }));
});
</script>
@endpush