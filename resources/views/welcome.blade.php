@extends('layouts.public')

@section('title', $pageTitle ?? 'SD Negeri Sukorame 1 Kediri')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --red: #1D4ED8;
        --red-dark: #1e3a8a;
        --red-light: #EFF6FF;
        --gold: #D97706;
        --gold-light: #FEF3C7;
        --text-dark: #1f2937;
        --text-muted: #4b5563;
        --border-color: #e5e7eb;
    }

    .font-display { font-family: 'Playfair Display', serif; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-dark); margin:0; padding:0; }

    /* ── HERO BANNER ── */
    .hero-wrap {
        position: relative;
        min-height: 520px;
        background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 40%, #1d4ed8 70%, #3b82f6 100%);
        overflow: hidden;
        display: flex;
        align-items: center;
        color: #fff;
        padding: 40px 20px;
    }
    .hero-container { max-width: 1140px; margin: 0 auto; width: 100%; position: relative; z-index: 2; }
    .hero-title { font-size: 42px; font-weight: 800; margin-bottom: 16px; line-height: 1.2; }
    .hero-subtitle { font-size: 18px; opacity: 0.9; max-width: 650px; line-height: 1.6; margin-bottom: 24px; }
    
    /* ── CONTAINER UMUM ── */
    .section-container { max-width: 1140px; margin: 0 auto; padding: 60px 20px; }
    .section-title { font-size: 28px; font-weight: 700; text-align: center; margin-bottom: 40px; position: relative; color: var(--red-dark); }
    .section-title::after { content:''; display:block; width:60px; height:4px; background: var(--gold); margin: 10px auto 0; border-radius:2px; }

    /* ── SAMBUTAN KEPSEK ── */
    .sambutan-grid { display: grid; grid-template-columns: 280px 1fr; gap: 40px; align-items: start; background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid var(--border-color); }
    .avatar-box { background: #f3f4f6; border-radius: 8px; border: 3px solid var(--red-light); padding: 10px; text-align: center; }
    .avatar-icon { font-size: 80px; color: #9ca3af; margin-bottom: 12px; }
    .sambutan-name { font-size: 16px; font-weight: 700; color: var(--red-dark); margin:0; }
    .sambutan-text { font-size: 15px; color: var(--text-muted); line-height: 1.7; margin:0; }

    /* ── VISI MISI ── */
    .visimisi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px; }
    .card-box { background: #fff; border-radius: 12px; padding: 30px; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-box-title { font-size: 20px; font-weight: 700; color: var(--red-dark); margin-top: 0; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; }
    .card-box-title i { color: var(--gold); }
    .card-box-body { font-size: 15px; color: var(--text-muted); line-height: 1.7; }

    /* ── COUNTER STATISTIK ── */
    .stats-section { background: var(--red-light); padding: 50px 20px; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); }
    .stats-grid { max-width: 1140px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center; }
    .stat-item { background: #fff; padding: 24px; border-radius: 10px; border: 1px solid var(--border-color); box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .stat-num { font-size: 32px; font-weight: 800; color: var(--red); margin-bottom: 4px; display: block; }
    .stat-lbl { font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

    /* ── PENGUMUMAN ── */
    .announcement-list { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .announcement-card { background: #fff; border-radius: 8px; padding: 20px; border: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .announcement-date { font-size: 12px; color: var(--gold); font-weight: 600; }
    .announcement-title { font-size: 16px; font-weight: 700; color: var(--red-dark); margin:0; line-height: 1.4; }
    .announcement-desc { font-size: 14px; color: var(--text-muted); line-height: 1.5; margin:0; flex-grow: 1; }

    @media (max-width: 768px) {
        .sambutan-grid, .visimisi-grid, .announcement-list { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .hero-title { font-size: 32px; }
    }
</style>
@endpush

@section('content')

{{-- ─── 1. HERO SECTION (DINAMIS) ─── --}}
<section class="hero-wrap">
    <div class="hero-container">
        <h1 class="font-display hero-title">{{ $setting->hero_title }}</h1>
        <p class="hero-subtitle">{{ $setting->hero_subtitle }}</p>
        <div>
            <a href="{{ route('login') }}" style="background: var(--gold); color:#fff; text-decoration:none; padding:12px 28px; border-radius:6px; font-weight:700; display:inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i> Portal SIAKAD
            </a>
        </div>
    </div>
</section>

{{-- ─── 2. SAMBUTAN KEPALA SEKOLAH (DINAMIS) ─── --}}
<section class="section-container" style="background: #fafafa; border-radius: 16px; margin-top: 40px; padding: 40px 20px;">
    <h2 class="section-title">Sambutan Utama</h2>
    <div class="sambutan-grid">
        <div class="avatar-box">
            <div class="avatar-icon"><i class="fas fa-user-circle"></i></div>
            <p class="sambutan-name">{{ $setting->sambutan_nama }}</p>
            <span style="font-size:12px; color:var(--text-muted); font-weight: 500;">Kepala Sekolah</span>
        </div>
        <div>
            <p class="sambutan-text">
                {!! nl2br(e($setting->sambutan_konten)) !!}
            </p>
        </div>
    </div>
</section>

{{-- ─── 3. STATISTIK COUNTER SEKOLAH ─── --}}
<section class="stats-section" id="stats-section">
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-num" data-count="{{ $stats['siswa'] }}">0</span>
            <span class="stat-lbl">Siswa Aktif</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-count="{{ $stats['guru'] }}">0</span>
            <span class="stat-lbl">Tenaga Pengajar</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-count="{{ $stats['kelas'] }}">0</span>
            <span class="stat-lbl">Rombongan Belajar</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" data-count="{{ $stats['mapel'] }}">0</span>
            <span class="stat-lbl">Mata Pelajaran</span>
        </div>
    </div>
</section>

{{-- ─── 4. VISI & MISI SEKOLAH (DINAMIS) ─── --}}
<section class="section-container">
    <h2 class="section-title">Visi & Misi</h2>
    <div class="visimisi-grid">
        <div class="card-box">
            <h3 class="card-box-title"><i class="fas fa-eye"></i> Visi Sekolah</h3>
            <div class="card-box-body">
                <div style="font-style: italic; font-size:16px; text-align:center; color: var(--text-dark);">
                    {!! $setting->visi !!}
                </div>
            </div>
        </div>
        <div class="card-box">
            <h3 class="card-box-title"><i class="fas fa-bullseye"></i> Misi Sekolah</h3>
            <div class="card-box-body">
                <div style="text-align: left; padding-left: 10px;">
                    {!! $setting->misi !!}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── 5. PENGUMUMAN TERBARU ─── --}}
<section class="section-container" style="border-top: 1px solid var(--border-color); padding-top: 60px;">
    <h2 class="section-title">Pengumuman Terbaru</h2>
    <div class="announcement-list">
        @forelse($pengumuman as $p)
            <div class="announcement-card">
                <span class="announcement-date"><i class="far fa-calendar-alt"></i> {{ $p->created_at->translatedFormat('d M Y') }}</span>
                <h4 class="announcement-title">{{ $p->judul }}</h4>
                <p class="announcement-desc">{{ Str::limit(strip_tags($p->isi), 120) }}</p>
                <a href="{{ route('berita.pengumuman') }}" style="color:var(--red); font-size:13px; font-weight:700; text-decoration:none; margin-top: auto;">Baca Selengkapnya →</a>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align:center; color:var(--text-muted); padding:30px;">
                <i class="fas fa-info-circle" style="font-size:24px; margin-bottom:8px;"></i>
                <p style="margin:0;">Belum ada pengumuman terbaru yang dipublikasikan.</p>
            </div>
        @endforelse
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const statsEl = document.getElementById('stats-section');
        if (statsEl) {
            const observer = new IntersectionObserver(entries => {
                if (!entries[0].isIntersecting) return;
                document.querySelectorAll('[data-count]').forEach(el => {
                    const target = +el.dataset.count;
                    const step   = Math.max(1, Math.ceil(target / 40));
                    let current  = 0;
                    const timer  = setInterval(() => {
                        current = Math.min(current + step, target);
                        el.textContent = current.toLocaleString('id-ID');
                        if (current >= target) clearInterval(timer);
                    }, 25);
                });
                observer.disconnect();
            }, { threshold: 0.2 });
            observer.observe(statsEl);
        }
    });
</script>
@endpush