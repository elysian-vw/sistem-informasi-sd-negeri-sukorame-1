@extends('layouts.public')

@section('title', $pageTitle ?? ('Visi & Misi - ' . ($sekolah->nama_sekolah ?? 'Sekolah')))

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --blue: #1D4ED8;
        --blue-dark: #1e3a8a;
        --blue-light: #EFF6FF;
        --gold: #D97706;
        --gold-light: #FEF3C7;
    }
    .font-display { font-family: 'Playfair Display', serif; }

    /* ── PAGE HERO ── */
    .page-hero {
        background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 45%, #1d4ed8 75%, #3b82f6 100%);
        position: relative; overflow: hidden;
    }
    .page-hero-pattern {
        position: absolute; inset: 0; opacity: .05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 28px 28px;
    }
    .page-hero-circle {
        position: absolute; border-radius: 50%; pointer-events: none;
    }

    /* ── VISI CARD ── */
    .visi-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #3b82f6 100%);
        border-radius: 28px; padding: 52px 48px;
        position: relative; overflow: hidden;
        box-shadow: 0 32px 80px rgba(29,78,216,.35);
    }
    .visi-card::before {
        content: ''; position: absolute; inset: 0; opacity: .04;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 24px 24px;
    }
    .visi-card-deco {
        position: absolute; border-radius: 50%; pointer-events: none;
    }
    .visi-quote {
        font-family: 'Playfair Display', serif;
        font-size: 120px; line-height: .8; color: rgba(255,255,255,.08);
        position: absolute; top: 0; left: 28px; pointer-events: none;
        user-select: none;
    }
    .visi-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
        color: #FDE68A; font-size: 11px; font-weight: 700;
        padding: 5px 14px; border-radius: 999px; letter-spacing: .08em;
        text-transform: uppercase; margin-bottom: 20px;
    }

    /* ── SECTION LABEL ── */
    .sec-label {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe;
        padding: 4px 14px; border-radius: 999px; margin-bottom: 12px;
    }
    .sec-label::before { content: ''; width: 6px; height: 6px; background: #1d4ed8; border-radius: 50%; }

    /* ── DYNAMIC CONTENT STYLING (CMS) ── */
    .cms-content p { margin-bottom: 1rem; line-height: 1.7; color: #4b5563; }
    .cms-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: #4b5563; line-height: 1.7; }
    .cms-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; color: #4b5563; line-height: 1.7; }
    .cms-content li { margin-bottom: 0.5rem; }
    .cms-content strong { color: #111827; }

    /* ── TUJUAN & NILAI ── */
    .tujuan-card {
        background: white; border-radius: 24px;
        border: 1px solid #e0e7ff; padding: 36px 40px;
        position: relative; overflow: hidden;
        box-shadow: 0 4px 24px rgba(29,78,216,.06);
    }
    .tujuan-card::after {
        content: ''; position: absolute;
        bottom: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, #1e3a8a, #1d4ed8, #3b82f6, #D97706);
    }
    .nilai-card {
        background: white; border-radius: 20px; padding: 28px 24px;
        border: 1px solid #f1f5f9; text-align: center;
        transition: all .3s; position: relative;
    }
    .nilai-card:hover { border-color: #bfdbfe; box-shadow: 0 12px 32px rgba(29,78,216,.08); transform: translateY(-3px); }
    .nilai-icon-ring {
        width: 68px; height: 68px; border-radius: 50%; margin: 0 auto 16px;
        display: flex; align-items: center; justify-content: center;
        position: relative; font-size: 26px;
    }
    .section-divider { height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent); }
</style>
@endpush

@section('content')

{{-- ── Mengambil Data Dinamis dari Database ── --}}
@php
    $teksVisi = $visi ? $visi->content : 'Visi belum diisi dari dashboard admin.';
    $teksMisi = $misi ? $misi->content : 'Misi belum diisi dari dashboard admin.';
    $teksTujuan = $tujuan ? $tujuan->content : 'Tujuan belum diisi dari dashboard admin.';
@endphp

{{-- ══════════════════════════════════════
     PAGE HERO
══════════════════════════════════════ --}}
<div class="page-hero py-20">
    <div class="page-hero-pattern"></div>
    <div class="page-hero-circle" style="width:400px;height:400px;right:-100px;top:-100px;background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 70%);"></div>
    <div class="page-hero-circle" style="width:260px;height:260px;left:10%;bottom:-80px;background:radial-gradient(circle,rgba(217,119,6,.12) 0%,transparent 70%);"></div>

    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <nav class="flex items-center justify-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Profil</span>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Visi &amp; Misi</span>
        </nav>

        <h1 class="font-display text-white font-black leading-tight mb-5" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
            Visi &amp; Misi<br>
            <span style="color:#FDE68A;">{{ $sekolah->nama_sekolah ?? 'Sekolah' }}</span>
        </h1>
        <p class="text-white/70 max-w-2xl mx-auto leading-relaxed" style="font-size: 1rem;">
            Arah, tujuan, dan nilai-nilai yang menjadi landasan pendidikan berkualitas
            di {{ $sekolah->nama_sekolah ?? 'Sekolah' }} {{ $sekolah->kota ? 'Kota ' . $sekolah->kota : '' }}.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
            @foreach ([['#visi','fa-eye','Visi'],['#misi','fa-bullseye','Misi'],['#tujuan','fa-star','Tujuan'],['#nilai','fa-heart','Nilai']] as $nav)
            <a href="{{ $nav[0] }}" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold px-4 py-2 rounded-full transition-all">
                <i class="fa {{ $nav[1] }} text-amber-300 text-[10px]"></i> {{ $nav[2] }}
            </a>
            @endforeach
        </div>
    </div>
</div>

<main class="bg-gray-50">

    {{-- ══════════════════════════════════════
         VISI (DINAMIS DARI DATABASE)
    ══════════════════════════════════════ --}}
    <section id="visi" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-10">
                <div class="sec-label">Visi Sekolah</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900">Ke Mana Kami Melangkah</h2>
            </div>

            <div class="visi-card">
                <div class="visi-card-deco" style="width:260px;height:260px;right:-60px;top:-60px;background:radial-gradient(circle,rgba(255,255,255,.07) 0%,transparent 65%);"></div>
                <div class="visi-quote">"</div>

                <div class="relative z-10 text-center max-w-2xl mx-auto">
                    <div class="visi-badge">
                        <i class="fa fa-eye text-[10px]"></i> Visi Sekolah
                    </div>
                    
                    {{-- Konten Visi dari Database --}}
                    <div class="font-display text-white leading-relaxed mb-8" style="font-size: clamp(1.15rem, 2vw, 1.5rem); font-weight: 700; font-style: italic;">
                        {!! $teksVisi !!}
                    </div>

                </div>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         MISI (DINAMIS DARI DATABASE)
    ══════════════════════════════════════ --}}
    <section id="misi" class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-12">
                <div>
                    <div class="sec-label">Misi Sekolah</div>
                    <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900">Cara Kami Mewujudkan</h2>
                </div>
            </div>

            {{-- Box Misi dari Database (menggunakan styling CMS agar rapi) --}}
            <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm cms-content">
                {!! $teksMisi !!}
            </div>

        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         TUJUAN (STATIS SEMENTARA)
    ══════════════════════════════════════ --}}
    <section id="tujuan" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-10">
                <div class="sec-label">Tujuan Sekolah</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900">Apa yang Kami Targetkan</h2>
            </div>

            <div class="tujuan-card">
                <div class="flex items-start gap-5 mb-8">
                    <div class="w-14 h-14 bg-amber-50 border-2 border-amber-200 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fa fa-star text-amber-500 text-xl"></i>
                    </div>
                    <div class="cms-content">
                        <h3 class="font-bold text-gray-900 text-base mb-1">Tujuan Umum Pendidikan</h3>
                        {!! $teksTujuan !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>
@endpush