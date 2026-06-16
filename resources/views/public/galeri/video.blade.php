@extends('layouts.public')

@section('title', 'Galeri Video — SDN Sukorame 1')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }

    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 80%, #2563eb 100%);
        position: relative; overflow: hidden;
    }
    .hero-pattern {
        position: absolute; inset: 0; opacity: .05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 28px 28px;
    }

    /* ── FILTER ── */
    .filter-btn {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11px; font-weight: 700; letter-spacing: .04em;
        padding: 6px 14px; border-radius: 999px; cursor: pointer;
        border: 1.5px solid #e0e7ff; background: white; color: #6b7280;
        transition: all .2s;
    }
    .filter-btn:hover { border-color: #93c5fd; color: #2563eb; }
    .filter-btn.active { background: #2563eb; border-color: #2563eb; color: white; }

    /* ── VIDEO CARD ── */
    .video-card {
        background: white; border-radius: 20px;
        border: 1.5px solid #e0e7ff; overflow: hidden;
        transition: all .25s; display: flex; flex-direction: column;
    }
    .video-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(37,99,235,.12);
        border-color: #93c5fd;
    }

    .video-thumb {
        position: relative; overflow: hidden;
        padding-bottom: 56.25%; /* 16:9 */
        background: #0f172a; cursor: pointer;
    }
    .video-thumb img {
        position: absolute; inset: 0; width: 100%; height: 100%;
        object-fit: cover; transition: transform .4s ease;
    }
    .video-card:hover .video-thumb img { transform: scale(1.04); }

    .video-thumb-overlay {
        position: absolute; inset: 0;
        background: rgba(15,23,42,.35);
        display: flex; align-items: center; justify-content: center;
        transition: background .25s;
    }
    .video-card:hover .video-thumb-overlay { background: rgba(15,23,42,.15); }

    .play-btn {
        width: 52px; height: 52px; border-radius: 50%;
        background: rgba(37,99,235,.92); border: 3px solid rgba(255,255,255,.8);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 18px; padding-left: 3px;
        transition: transform .25s, background .2s;
        box-shadow: 0 6px 24px rgba(37,99,235,.5);
    }
    .video-card:hover .play-btn { transform: scale(1.12); background: #1d4ed8; }

    .video-duration {
        position: absolute; bottom: 10px; right: 10px;
        background: rgba(0,0,0,.75); color: white;
        font-size: 10px; font-weight: 700; padding: 2px 8px;
        border-radius: 5px; letter-spacing: .03em;
    }

    .kat-badge {
        position: absolute; top: 10px; left: 10px;
        font-size: 9px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
        padding: 3px 9px; border-radius: 999px;
        background: rgba(255,255,255,.92); color: #1e3a8a;
    }

    .video-body { padding: 18px 20px; flex: 1; }
    .video-title { font-weight: 700; color: #0f172a; font-size: 14px; line-height: 1.4; margin-bottom: 6px; }
    .video-desc  { color: #6b7280; font-size: 12px; line-height: 1.6; margin-bottom: 10px;
                   display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .video-footer {
        padding: 12px 20px; background: #fafafa;
        border-top: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .video-meta { color: #9ca3af; font-size: 11px; display: flex; align-items: center; gap: 6px; }

    /* ── MODAL PLAYER ── */
    #video-modal {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(9,14,31,.92); backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity .25s;
    }
    #video-modal.open { opacity: 1; pointer-events: all; }

    .modal-container {
        position: relative; width: 92vw; max-width: 900px;
    }
    .modal-close {
        position: absolute; top: -48px; right: 0;
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
        color: white; font-size: 16px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
    }
    .modal-close:hover { background: rgba(255,255,255,.2); }

    .modal-player {
        border-radius: 20px; overflow: hidden;
        position: relative; padding-bottom: 56.25%; height: 0;
        box-shadow: 0 30px 80px rgba(0,0,0,.6);
    }
    .modal-player iframe {
        position: absolute; inset: 0; width: 100%; height: 100%; border: 0;
    }
    .modal-info { padding: 16px 4px 0; text-align: center; }
    .modal-title { color: white; font-size: 15px; font-weight: 700; margin-bottom: 4px; }
    .modal-meta  { color: rgba(255,255,255,.5); font-size: 12px; }

    /* Featured video */
    .featured-card {
        background: white; border-radius: 24px; overflow: hidden;
        border: 1.5px solid #e0e7ff; display: flex; flex-direction: column;
        transition: all .25s;
    }
    .featured-card:hover { box-shadow: 0 20px 50px rgba(37,99,235,.12); border-color: #93c5fd; }

    /* Section label */
    .section-label {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        color: #2563eb; margin-bottom: 16px;
    }
    .section-label::before { content:''; width:6px; height:6px; background:#2563eb; border-radius:50%; }

    #empty-video { display: none; }

    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

@php
$kategoriMap = [
    ''            => 'Semua',
    'profil'      => 'Profil Sekolah',
    'kegiatan'    => 'Kegiatan',
    'pembelajaran'=> 'Pembelajaran',
    'nasional'    => 'Hari Nasional',
    'ppdb'        => 'PPDB',
    'ekstra'      => 'Ekstrakurikuler',
];

// Fungsi untuk mendapatkan YouTube ID
function getYoutubeId($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    return $match[1] ?? 'dQw4w9WgXcQ';
}

$featured = $videos->where('kategori', 'profil')->first() ?? $videos->first();
$others   = $videos->where('id', '!=', optional($featured)->id);
@endphp

{{-- HERO --}}
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div style="position:absolute;width:500px;height:500px;right:-100px;top:-100px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.05) 0%,transparent 70%);pointer-events:none;"></div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Galeri Video</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-blue-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-blue-300 rounded-full"></span> Dokumentasi Video
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Galeri<br>
                    <span style="color:#BFDBFE;">Video Sekolah</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-lg" style="font-size:1rem;">
                    Dokumentasi kegiatan dan momen berkesan SD Negeri Sukorame 1
                    dalam format video.
                </p>
            </div>
            <div class="flex flex-col gap-3 flex-shrink-0">
                <div class="flex gap-3">
                    <div class="bg-white/15 border border-white/30 rounded-2xl p-4 text-center w-28 backdrop-blur-sm">
                        <i class="fa fa-play-circle text-blue-300 text-base mb-2 block"></i>
                        <p class="text-white font-black text-xl leading-none">{{ $videos->count() }}</p>
                        <p class="text-white/55 text-[10px] mt-1">Total Video</p>
                    </div>
                    <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center w-28 backdrop-blur-sm">
                        <i class="fa fa-folder text-blue-300 text-base mb-2 block"></i>
                        <p class="text-white font-black text-xl leading-none">{{ $videos->groupBy('kategori')->count() }}</p>
                        <p class="text-white/55 text-[10px] mt-1">Kategori</p>
                    </div>

                </div>
                <a href="{{ route('galeri.foto') }}"
                   class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors justify-center">
                    <i class="fa fa-images"></i> Lihat Galeri Foto
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
            <button onclick="filterVideo('{{ $key }}')"
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

        {{-- Video Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="video-grid">
            @foreach($videos as $vid)
            @php $ytId = getYoutubeId($vid->url_video); @endphp
            <div class="video-card fade-up" data-kat="{{ $vid->kategori }}">
                <div class="video-thumb" style="cursor:pointer;"
                     onclick="openModal('{{ $ytId }}', '{{ $vid->judul }}', '{{ $vid->tanggal ? $vid->tanggal->format('d M Y') : $vid->created_at->format('d M Y') }}')">
                    <img src="https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg"
                         alt="{{ $vid->judul }}"
                         onerror="this.src='https://img.youtube.com/vi/{{ $ytId }}/0.jpg'">
                    <div class="video-thumb-overlay">
                        <div class="play-btn"><i class="fa fa-play"></i></div>
                    </div>
                    <span class="kat-badge">{{ $kategoriMap[$vid->kategori] ?? $vid->kategori }}</span>
                    {{-- <span class="video-duration">{{ $vid['durasi'] }}</span> --}}
                </div>
                <div class="video-body">
                    <h3 class="video-title">{{ $vid->judul }}</h3>
                    <p class="video-desc">{{ $vid->deskripsi }}</p>
                </div>
                <div class="video-footer">
                    <div class="video-meta">
                        <i class="fa fa-calendar text-[10px]"></i>
                        <span>{{ $vid->tanggal ? $vid->tanggal->format('d M Y') : $vid->created_at->format('d M Y') }}</span>
                    </div>
                    <button onclick="openModal('{{ $ytId }}', '{{ addslashes($vid->judul) }}', '{{ $vid->tanggal ? $vid->tanggal->format('d M Y') : $vid->created_at->format('d M Y') }}')"
                       class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-100 text-[11px] font-bold px-3 py-1.5 rounded-lg transition-colors">
                        <i class="fa fa-play text-[9px]"></i> Tonton
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Empty state --}}
        <div id="empty-video" class="text-center py-20">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa fa-video text-blue-300 text-xl"></i>
            </div>
            <p class="text-gray-500 font-semibold text-sm">Belum ada video untuk kategori ini.</p>
        </div>

    </div>
</section>
</main>

{{-- Modal Player --}}
<div id="video-modal" role="dialog" aria-modal="true" aria-label="Pemutar video">
    <div class="modal-container">
        <button class="modal-close" onclick="closeModal()" aria-label="Tutup">
            <i class="fa fa-times"></i>
        </button>
        <div class="modal-player">
            <iframe id="modal-iframe" src="" allowfullscreen
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
            </iframe>
        </div>
        <div class="modal-info">
            <p class="modal-title" id="modal-title"></p>
            <p class="modal-meta"  id="modal-meta"></p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@php
    $videoJson = $videos->map(function($v) {
        return [
            'kategori' => $v->kategori,
            'judul' => $v->judul,
            'tanggal' => $v->tanggal ? $v->tanggal->format('d M Y') : $v->created_at->format('d M Y'),
            'deskripsi' => $v->deskripsi,
            'youtube_id' => getYoutubeId($v->url_video)
        ];
    });
@endphp
<script>
const videoData = @json($videoJson);

// ── FILTER ──
function filterVideo(kat) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`.filter-btn[data-kat="${kat}"]`).classList.add('active');

    let visible = 0;
    document.querySelectorAll('#video-grid .video-card').forEach(el => {
        const show = !kat || el.dataset.kat === kat;
        el.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    const empty = document.getElementById('empty-video');
    empty.style.display = visible === 0 ? 'block' : 'none';
}

// ── MODAL ──
function openModal(ytId, title, date) {
    document.getElementById('modal-iframe').src =
        `https://www.youtube.com/embed/${ytId}?autoplay=1&rel=0`;
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-meta').textContent  = date;
    document.getElementById('video-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    document.getElementById('modal-iframe').src = '';
    document.getElementById('video-modal').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});
document.getElementById('video-modal').addEventListener('click', e => {
    if (e.target === document.getElementById('video-modal')) closeModal();
});

// ── COUNT BADGES ──
document.addEventListener('DOMContentLoaded', () => {
    const counts = {};
    videoData.forEach(v => { counts[v.kategori] = (counts[v.kategori] || 0) + 1; });
    const total = videoData.length;

    document.querySelectorAll('.count-badge').forEach(el => {
        const kat = el.dataset.kat;
        el.textContent = kat === '' ? `(${total})` : counts[kat] ? `(${counts[kat]})` : '';
    });

    const obs = new IntersectionObserver(entries => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('visible'), i * 70);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.06 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
});
</script>
@endpush