@extends('layouts.siswa')
@section('title', 'Belajar Yuk!')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap');

    .materi-wrap {
        font-family: 'Nunito', sans-serif;
        min-height: 100vh;
        padding-bottom: 60px;
    }

    /* ── Hero ── */
    .hero-banner {
        padding: 32px 24px 80px;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(circle at 20% 80%, rgba(255,255,255,.15) 0%, transparent 50%),
                          radial-gradient(circle at 80% 20%, rgba(255,255,255,.12) 0%, transparent 40%);
    }
    /* Dekorasi bintang */
    .star {
        position: absolute;
        font-size: 20px;
        animation: floatStar 4s ease-in-out infinite;
        opacity: .7;
    }
    .star:nth-child(1)  { top:10%; left:8%;  animation-delay:0s;    font-size:28px; }
    .star:nth-child(2)  { top:20%; right:12%;animation-delay:.6s;   font-size:20px; }
    .star:nth-child(3)  { top:60%; left:5%;  animation-delay:1.2s;  font-size:16px; }
    .star:nth-child(4)  { top:50%; right:6%; animation-delay:1.8s;  font-size:24px; }
    .star:nth-child(5)  { top:75%; left:15%; animation-delay:.9s;   font-size:18px; }
    @keyframes floatStar {
        0%,100% { transform: translateY(0) rotate(0deg); }
        50%      { transform: translateY(-10px) rotate(15deg); }
    }

    .hero-emoji  { font-size: 64px; display: block; margin-bottom: 10px; filter: drop-shadow(0 4px 12px rgba(0,0,0,.15)); }
    .hero-title  { font-family: 'Fredoka One', cursive; font-size: 32px; color: #000000; margin: 0 0 6px; text-shadow: 0 2px 8px rgba(0,0,0,.15); }
    .hero-sub    { font-size: 15px; color: rgba(0, 0, 0, 0.9); font-weight: 700; margin: 0; }
    .hero-avatar {
        font-family: 'Fredoka One', cursive;
        font-size: 14px;
        color: rgba(0, 0, 0, 0.85);
        margin-top: 10px;
        background: rgba(255,255,255,.2);
        display: inline-block;
        padding: 5px 16px;
        border-radius: 50px;
        backdrop-filter: blur(6px);
    }

    /* ── Wave ── */
    .wave-divider {
        background: #fffbf0;
        margin-top: -44px;
        position: relative;
        z-index: 2;
    }
    .wave-divider svg { display:block; }

    /* ── Filter mapel ── */
    .filter-section {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 16px 20px;
    }
    .filter-title {
        font-family: 'Fredoka One', cursive;
        font-size: 18px;
        color: #e17055;
        margin: 0 0 12px;
    }
    .mapel-chips {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 800;
        border: 2.5px solid transparent;
        cursor: pointer;
        text-decoration: none;
        transition: .2s;
    }
    .chip.all     { background:#fff3e0; color:#e17055; border-color:#fdcb6e; }
    .chip.mapel   { background:#fff; color:#636e72; border-color:#dfe6e9; }
    .chip.active  { border-color: currentColor; box-shadow: 0 4px 14px rgba(0,0,0,.1); transform: translateY(-2px); }
    .chip:hover   { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.08); }

    /* ── Grid materi ── */
    .materi-section {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 16px;
    }
    .section-title {
        font-family: 'Fredoka One', cursive;
        font-size: 22px;
        color: #2d3436;
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .materi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;
    }

    /* Palet warna kartu */
    .card-colors {
        --c1: #6c5ce7; --c2: #a29bfe; /* ungu */
    }
    .materi-card {
        background: #fff;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.07);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: .25s cubic-bezier(.34,1.56,.64,1);
        position: relative;
        border: 2.5px solid transparent;
    }
    .materi-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 12px 36px rgba(0,0,0,.13);
    }

    /* Header kartu berwarna */
    .card-header-colored {
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .card-header-colored::before {
        content: '';
        position: absolute;
        inset: 0;
        background: inherit;
        opacity: .15;
    }
    .card-icon-big { font-size: 52px; position: relative; filter: drop-shadow(0 4px 8px rgba(0,0,0,.15)); }

    /* Warna per kartu: cycled via nth-child */
    .materi-card:nth-child(6n+1) .card-header-colored { background: linear-gradient(135deg,#6c5ce7,#a29bfe); }
    .materi-card:nth-child(6n+2) .card-header-colored { background: linear-gradient(135deg,#00b894,#00cec9); }
    .materi-card:nth-child(6n+3) .card-header-colored { background: linear-gradient(135deg,#e17055,#fdcb6e); }
    .materi-card:nth-child(6n+4) .card-header-colored { background: linear-gradient(135deg,#0984e3,#74b9ff); }
    .materi-card:nth-child(6n+5) .card-header-colored { background: linear-gradient(135deg,#fd79a8,#fab1d3); }
    .materi-card:nth-child(6n+6) .card-header-colored { background: linear-gradient(135deg,#00cec9,#81ecec); }

    .card-body-content {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .card-mapel-tag {
        font-size: 11px;
        font-weight: 800;
        color: #fff;
        background: #6c5ce7;
        border-radius: 50px;
        padding: 3px 10px;
        display: inline-block;
        margin-bottom: 8px;
        align-self: flex-start;
    }
    .card-title {
        font-size: 15px;
        font-weight: 800;
        color: #2d3436;
        margin: 0 0 6px;
        line-height: 1.35;
    }
    .card-guru {
        font-size: 12px;
        color: #b2bec3;
        font-weight: 600;
        margin-top: auto;
        padding-top: 10px;
        border-top: 1.5px dashed #f0f0f0;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .card-cta {
        position: absolute;
        bottom: 14px;
        right: 14px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f8f9ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c5ce7;
        font-size: 14px;
        transition: .2s;
    }
    .materi-card:hover .card-cta { background: #6c5ce7; color: #fff; transform: scale(1.1); }

    /* New badge */
    .badge-new {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #fdcb6e;
        color: #2d3436;
        font-size: 10px;
        font-weight: 900;
        padding: 3px 8px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: .5px;
        box-shadow: 0 2px 8px rgba(0,0,0,.1);
    }

    /* Empty state */
    .empty-materi {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 4px 20px rgba(0,0,0,.05);
    }
    .empty-materi .emoji { font-size: 64px; display: block; margin-bottom: 12px; }
    .empty-materi h3 { font-family: 'Fredoka One', cursive; font-size: 22px; color: #636e72; margin: 0 0 6px; }
    .empty-materi p  { font-size: 14px; color: #b2bec3; font-weight: 600; }

    /* Pagination custom */
    .pagination-wrap {
        display: flex;
        justify-content: center;
        margin-top: 28px;
    }
    .pagination-wrap .pagination { gap: 6px; display: flex; flex-wrap: wrap; justify-content: center; }
    .pagination-wrap .page-item .page-link {
        border-radius: 12px !important;
        font-weight: 800;
        font-family: 'Nunito', sans-serif;
        border: 2px solid #f0f0f0;
        color: #6c5ce7;
        padding: 8px 14px;
    }
    .pagination-wrap .page-item.active .page-link {
        background: linear-gradient(135deg,#6c5ce7,#a29bfe);
        border-color: #6c5ce7;
        color: #fff;
    }

    /* Motivasi strip */
    .motivasi-strip {
        max-width: 900px;
        margin: 0 auto 24px;
        padding: 0 16px;
    }
    .motivasi-card {
        background: linear-gradient(135deg,#6c5ce7,#a29bfe);
        border-radius: 18px;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 14px;
        color: #fff;
    }
    .motivasi-emoji { font-size: 36px; flex-shrink: 0; }
    .motivasi-text  { font-size: 14px; font-weight: 700; line-height: 1.5; }
    .motivasi-text strong { font-size: 16px; display: block; margin-bottom: 2px; }
</style>
@endpush

@section('content')
<div class="materi-wrap">

    {{-- ── HERO ── --}}
    <div class="hero-banner">
        <span class="star">⭐</span>
        <span class="star">🌟</span>
        <span class="star">✨</span>
        <span class="star">💫</span>
        <span class="star">⭐</span>
        <span class="hero-emoji">📚</span>
        <h1 class="hero-title">Ayo Belajar!</h1>
        <p class="hero-sub">Pilih materi yang ingin kamu pelajari hari ini</p>
        <div class="hero-avatar">
            👋 Halo, {{ $siswa->nama_lengkap ?? 'Adik' }}!
        </div>
    </div>

    

    {{-- ── MOTIVASI ── --}}
    @php
        $motivasiList = [
            ['💡','Setiap hari belajar sedikit, lama-lama jadi banyak!','Teruslah semangat ya!'],
            ['🌈','Belajar itu seru kalau kita mau mencoba!','Kamu pasti bisa!'],
            ['🚀','Belajar adalah petualangan yang menakjubkan!','Ayo mulai petualanganmu!'],
            ['🌻','Ilmu yang kamu pelajari hari ini adalah bekalmu masa depan!','Semangat terus!'],
        ];
        $mot = $motivasiList[date('N') % count($motivasiList)];
    @endphp
    <div class="motivasi-strip">
        <div class="motivasi-card">
            <div class="motivasi-emoji">{{ $mot[0] }}</div>
            <div class="motivasi-text">
                <strong>{{ $mot[1] }}</strong>
                {{ $mot[2] }}
            </div>
        </div>
    </div>

    {{-- ── FILTER MAPEL ── --}}
    <div class="filter-section">
        <div class="filter-title">📖 Pilih Mata Pelajaran</div>
        <div class="mapel-chips">
            <a href="{{ route('siswa.materi.index') }}"
               class="chip all {{ !request('mata_pelajaran_id') ? 'active' : '' }}">
                🎒 Semua Pelajaran
            </a>
            @foreach($mapel as $m)
            @php
                $mapelIcons = [
                    'Matematika'         => '🔢',
                    'Bahasa Indonesia'   => '📝',
                    'IPA'                => '🔬',
                    'IPS'                => '🌍',
                    'Bahasa Inggris'     => '🗣️',
                    'Seni Budaya'        => '🎨',
                    'PJOK'               => '⚽',
                    'Agama'              => '🙏',
                    'PKN'                => '🇮🇩',
                ];
                $icon = $mapelIcons[$m->nama] ?? '📗';
            @endphp
            <a href="{{ route('siswa.materi.index', ['mata_pelajaran_id' => $m->id]) }}"
               class="chip mapel {{ request('mata_pelajaran_id') == $m->id ? 'active' : '' }}"
               style="{{ request('mata_pelajaran_id') == $m->id ? 'background:#fff3e0;color:#e17055;border-color:#fdcb6e;' : '' }}">
                {{ $icon }} {{ $m->nama }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── GRID MATERI ── --}}
    <div class="materi-section">
        <div class="section-title">
            📂 Materi Pelajaran
            <span style="font-size:14px;font-family:'Nunito',sans-serif;font-weight:700;color:#b2bec3;margin-left:4px;">
                ({{ $materi->total() }} materi)
            </span>
        </div>

        @if($materi->count())
        <div class="materi-grid">
            @foreach($materi as $m)
            @php
                $mapelIcons2 = [
                    'Matematika'       => '🔢',
                    'Bahasa Indonesia' => '📝',
                    'IPA'              => '🔬',
                    'IPS'              => '🌍',
                    'Bahasa Inggris'   => '🗣️',
                    'Seni Budaya'      => '🎨',
                    'PJOK'             => '⚽',
                    'Agama'            => '🙏',
                    'PKN'              => '🇮🇩',
                ];
                $icon2   = $mapelIcons2[$m->mataPelajaran->nama ?? ''] ?? '📗';
                $isNew   = $m->created_at->diffInDays(now()) <= 7;
            @endphp
            <a href="{{ route('siswa.materi.show', $m) }}" class="materi-card">
                @if($isNew)
                <div class="badge-new">🆕 Baru!</div>
                @endif
                <div class="card-header-colored">
                    <div class="card-icon-big">{{ $icon2 }}</div>
                </div>
                <div class="card-body-content">
                    <div class="card-mapel-tag">{{ $m->mataPelajaran->nama ?? '—' }}</div>
                    <div class="card-title">{{ $m->judul }}</div>
                    <div class="card-guru">
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ $m->guru->user->name ?? 'Guru' }}
                    </div>
                </div>
                <div class="card-cta">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            @endforeach
        </div>

        <div class="pagination-wrap">{{ $materi->withQueryString()->links() }}</div>

        @else
        <div class="empty-materi">
            <span class="emoji">😴</span>
            <h3>Belum ada materi nih...</h3>
            <p>Guru belum menambahkan materi untuk pelajaran ini.<br>Coba pilih pelajaran lain ya!</p>
        </div>
        @endif
    </div>

</div>
@endsection