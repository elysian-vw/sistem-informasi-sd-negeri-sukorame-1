@extends('layouts.public')

@section('title', $pageTitle ?? ('Galeri Foto — ' . ($sekolah->nama_sekolah ?? 'Sekolah')))

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }

    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 45%, #1d4ed8 80%, #3b82f6 100%);
        position: relative; overflow: hidden;
    }
    .hero-pattern {
        position: absolute; inset: 0; opacity: .05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 28px 28px;
    }

    /* ── FILTER BADGE ── */
    .filter-btn {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11px; font-weight: 700; letter-spacing: .04em;
        padding: 6px 14px; border-radius: 999px; cursor: pointer;
        border: 1.5px solid #e0e7ff; background: white; color: #6b7280;
        transition: all .2s;
    }
    .filter-btn:hover { border-color: #93c5fd; color: #2563eb; }
    .filter-btn.active { background: #2563eb; border-color: #2563eb; color: white; }

    /* ── FOTO GRID (masonry-like) ── */
    .foto-grid {
        columns: 3; column-gap: 16px;
    }
    @media (max-width: 900px) { .foto-grid { columns: 2; } }
    @media (max-width: 540px) { .foto-grid { columns: 1; } }

    .foto-item {
        break-inside: avoid; margin-bottom: 16px;
        border-radius: 16px; overflow: hidden;
        position: relative; cursor: pointer;
        border: 1.5px solid #e0e7ff;
        transition: all .28s;
    }
    .foto-item:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(37,99,235,.15); border-color: #93c5fd; }

    .foto-item img {
        width: 100%; display: block; object-fit: cover;
        transition: transform .4s ease;
    }
    .foto-item:hover img { transform: scale(1.04); }

    .foto-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(15,23,42,.75) 0%, transparent 55%);
        opacity: 0; transition: opacity .28s;
        display: flex; align-items: flex-end; padding: 16px;
    }
    .foto-item:hover .foto-overlay { opacity: 1; }

    .foto-overlay-inner { width: 100%; }
    .foto-title { color: white; font-size: 13px; font-weight: 700; line-height: 1.3; margin-bottom: 4px; }
    .foto-meta  { color: rgba(255,255,255,.65); font-size: 10px; }

    .foto-zoom-btn {
        position: absolute; top: 12px; right: 12px;
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(255,255,255,.9); border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity .25s;
        color: #1e3a8a; font-size: 13px;
    }
    .foto-item:hover .foto-zoom-btn { opacity: 1; }

    /* ── KATEGORI BADGE ── */
    .kat-badge {
        position: absolute; top: 12px; left: 12px;
        font-size: 9px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
        padding: 3px 9px; border-radius: 999px;
        background: rgba(255,255,255,.92); color: #1e3a8a;
        backdrop-filter: blur(4px);
    }

    /* ── LIGHTBOX ── */
    #lightbox {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(9,14,31,.92); backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity .25s;
    }
    #lightbox.open { opacity: 1; pointer-events: all; }

    .lb-container {
        position: relative; max-width: 880px; width: 92vw;
        display: flex; flex-direction: column; align-items: center; gap: 16px;
    }
    .lb-img-wrap {
        border-radius: 20px; overflow: hidden;
        box-shadow: 0 30px 80px rgba(0,0,0,.5);
        max-height: 72vh; display: flex; align-items: center;
    }
    .lb-img-wrap img { max-width: 100%; max-height: 72vh; object-fit: contain; display: block; }

    .lb-info { text-align: center; }
    .lb-title { color: white; font-size: 15px; font-weight: 700; margin-bottom: 4px; }
    .lb-meta  { color: rgba(255,255,255,.5); font-size: 12px; }

    .lb-close {
        position: absolute; top: -48px; right: 0;
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
        color: white; font-size: 16px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
    }
    .lb-close:hover { background: rgba(255,255,255,.2); }

    .lb-nav {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 44px; height: 44px; border-radius: 50%;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
        color: white; font-size: 15px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
    }
    .lb-nav:hover { background: rgba(37,99,235,.6); }
    .lb-prev { left: -60px; }
    .lb-next { right: -60px; }
    @media (max-width: 700px) {
        .lb-prev { left: 8px; } .lb-next { right: 8px; }
    }

    /* ── SECTION LABEL ── */
    .section-label {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        color: #2563eb; margin-bottom: 16px;
    }
    .section-label::before { content:''; width:6px; height:6px; background:#2563eb; border-radius:50%; }

    /* Empty state */
    #empty-foto { display: none; }

    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

@php
$kategoriMap = [
    ''           => 'Semua',
    'kegiatan'   => 'Kegiatan',
    'pembelajaran' => 'Pembelajaran',
    'prestasi'   => 'Prestasi',
    'nasional'   => 'Hari Nasional',
    'ppdb'       => 'PPDB',
    'ekstra'     => 'Ekstrakurikuler',
];
@endphp

{{-- HERO --}}
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div style="position:absolute;width:500px;height:500px;right:-120px;top:-120px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.05) 0%,transparent 70%);pointer-events:none;"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Galeri Foto</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-blue-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-blue-300 rounded-full"></span> Dokumentasi Sekolah
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Galeri<br>
                    <span style="color:#BFDBFE;">Foto Sekolah</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-lg" style="font-size:1rem;">
                    Kumpulan dokumentasi kegiatan, prestasi, dan momen berharga
                    di {{ $sekolah->nama_sekolah ?? 'Sekolah' }} {{ $sekolah->kota ? 'Kota ' . $sekolah->kota : '' }}.
                </p>
            </div>

            {{-- Navigasi ke Video --}}
            <div class="flex flex-col gap-3 flex-shrink-0">
                <div class="flex gap-3">
                    <div class="bg-white/15 border border-white/30 rounded-2xl p-4 text-center w-28 backdrop-blur-sm">
                        <i class="fa fa-images text-blue-300 text-base mb-2 block"></i>
                        <p class="text-white font-black text-xl leading-none">{{ $fotos->count() }}</p>
                        <p class="text-white/55 text-[10px] mt-1">Total Foto</p>
                    </div>
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center w-28 backdrop-blur-sm">
                        <i class="fa fa-folder text-blue-300 text-base mb-2 block"></i>
                        <p class="text-white font-black text-xl leading-none">{{ $fotos->groupBy('kategori')->count() }}</p>
                        <p class="text-white/55 text-[10px] mt-1">Kategori</p>
                    </div>
                </div>
                <a href="{{ route('galeri.video') }}"
                   class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors justify-center">
                    <i class="fa fa-play-circle"></i> Lihat Galeri Video
                </a>
            </div>
        </div>
    </div>
</div>

<main class="bg-gray-50 min-h-screen">
<section class="py-14">
    <div class="max-w-6xl mx-auto px-6">

        {{-- Filter --}}
        <div class="flex flex-wrap gap-2 mb-8 fade-up">
            @foreach($kategoriMap as $key => $label)
            <button onclick="filterFoto('{{ $key }}')"
                data-kat="{{ $key }}"
                class="filter-btn {{ $key === '' ? 'active' : '' }}">
                @if($key !== '')
                <span class="w-1.5 h-1.5 rounded-full" style="background: currentColor;"></span>
                @endif
                {{ $label }}
                <span class="ml-1 text-[9px] opacity-60 count-badge" data-kat="{{ $key }}"></span>
            </button>
            @endforeach
        </div>

        {{-- Masonry Grid --}}
        <div class="foto-grid fade-up" id="foto-grid">
            @foreach($fotos as $idx => $foto)
            @php
                $src = $foto->file_path ? asset('storage/' . $foto->file_path) : 'https://picsum.photos/seed/'.$foto->id.'/600/400';
                $heights = [200, 240, 260, 300, 220, 190, 280, 210, 230];
                $h = $heights[$idx % count($heights)];
            @endphp
            <div class="foto-item" data-kat="{{ $foto->kategori }}" data-idx="{{ $idx }}"
                  onclick="openLightbox({{ $idx }})">
                <img src="{{ $src }}"
                      alt="{{ $foto->judul }}"
                      style="height: {{ $h }}px;"
                      onerror="this.src='https://picsum.photos/seed/{{ $foto->id }}/600/{{ $h }}'">
                <span class="kat-badge">{{ $kategoriMap[$foto->kategori] ?? $foto->kategori }}</span>
                <div class="foto-overlay">
                    <div class="foto-overlay-inner">
                        <p class="foto-title">{{ $foto->judul }}</p>
                        <p class="foto-meta"><i class="fa fa-calendar text-[9px] mr-1"></i>{{ $foto->tanggal ? $foto->tanggal->format('d M Y') : $foto->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <button class="foto-zoom-btn" onclick="event.stopPropagation(); openLightbox({{ $idx }})">
                    <i class="fa fa-expand-alt"></i>
                </button>
            </div>
            @endforeach
        </div>
        {{-- Empty state --}}
        <div id="empty-foto" class="text-center py-20">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa fa-images text-blue-300 text-xl"></i>
            </div>
            <p class="text-gray-500 font-semibold text-sm">Belum ada foto untuk kategori ini.</p>
        </div>

    </div>
</section>
</main>

{{-- Lightbox --}}
<div id="lightbox" role="dialog" aria-modal="true" aria-label="Lightbox foto">
    <div class="lb-container">
        <button class="lb-close" onclick="closeLightbox()" aria-label="Tutup"><i class="fa fa-times"></i></button>
        <button class="lb-nav lb-prev" onclick="lbNav(-1)" aria-label="Sebelumnya"><i class="fa fa-chevron-left"></i></button>
        <button class="lb-nav lb-next" onclick="lbNav(1)"  aria-label="Berikutnya"><i class="fa fa-chevron-right"></i></button>
        <div class="lb-img-wrap">
            <img id="lb-img" src="" alt="">
        </div>
        <div class="lb-info">
            <p class="lb-title" id="lb-title"></p>
            <p class="lb-meta"  id="lb-meta"></p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@php
    $fotoJson = $fotos->map(function($f) {
        return [
            'judul' => $f->judul,
            'kategori' => $f->kategori,
            'tanggal' => $f->tanggal ? $f->tanggal->format('d M Y') : $f->created_at->format('d M Y'),
            'deskripsi' => $f->deskripsi,
            'src' => $f->file_path ? asset('storage/' . $f->file_path) : 'https://picsum.photos/seed/'.$f->id.'/800/500'
        ];
    });
@endphp
<script>
// ── DATA ──
const fotoData = @json($fotoJson);
let lbIndex = 0;
let visibleIndices = fotoData.map((_, i) => i);

// ── FILTER ──
function filterFoto(kat) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`.filter-btn[data-kat="${kat}"]`).classList.add('active');

    let visible = 0;
    document.querySelectorAll('#foto-grid .foto-item').forEach(el => {
        const show = !kat || el.dataset.kat === kat;
        el.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    visibleIndices = fotoData
        .map((f, i) => ({ f, i }))
        .filter(({ f }) => !kat || f.kategori === kat)
        .map(({ i }) => i);

    document.getElementById('empty-foto').style.display = visible === 0 ? 'flex' : 'none';
    document.getElementById('empty-foto').style.flexDirection = 'column';
    document.getElementById('empty-foto').style.alignItems   = 'center';
}

// ── LIGHTBOX ──
function openLightbox(idx) {
    lbIndex = visibleIndices.indexOf(idx);
    if (lbIndex === -1) lbIndex = 0;
    renderLb();
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
function lbNav(dir) {
    lbIndex = (lbIndex + dir + visibleIndices.length) % visibleIndices.length;
    renderLb();
}
function renderLb() {
    const foto = fotoData[visibleIndices[lbIndex]];
    const img  = document.getElementById('lb-img');
    img.src = foto.src;
    img.onerror = () => { img.src = `https://picsum.photos/seed/${visibleIndices[lbIndex]}/800/500`; };
    document.getElementById('lb-title').textContent = foto.judul;
    document.getElementById('lb-meta').textContent  = foto.tanggal + ' · ' + foto.deskripsi;
}

// Keyboard nav
document.addEventListener('keydown', e => {
    if (!document.getElementById('lightbox').classList.contains('open')) return;
    if (e.key === 'Escape')     closeLightbox();
    if (e.key === 'ArrowLeft')  lbNav(-1);
    if (e.key === 'ArrowRight') lbNav(1);
});
document.getElementById('lightbox').addEventListener('click', e => {
    if (e.target === document.getElementById('lightbox')) closeLightbox();
});

// ── COUNT BADGES ──
document.addEventListener('DOMContentLoaded', () => {
    const counts = {};
    fotoData.forEach(f => { counts[f.kategori] = (counts[f.kategori] || 0) + 1; });
    const total = fotoData.length;

    document.querySelectorAll('.count-badge').forEach(el => {
        const kat = el.dataset.kat;
        el.textContent = kat === '' ? `(${total})` : counts[kat] ? `(${counts[kat]})` : '';
    });
    document.querySelector('.count-badge[data-kat=""]').textContent = `(${total})`;

    // Scroll animation
    const obs = new IntersectionObserver(entries => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('visible'), i * 60);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.06 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
});
</script>
@endpush