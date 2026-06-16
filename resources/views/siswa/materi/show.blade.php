@extends('layouts.siswa')
@section('title', $materi->judul)

@push('styles')
<style>

    .youtube-card{
        position:relative;
        border-radius:20px;
        overflow:hidden;
        margin-top:20px;
    }

    .youtube-card img{
        width:100%;
        display:block;
    }

    .youtube-overlay{
        position:absolute;
        inset:0;
        background:rgba(0,0,0,.3);
        display:flex;
        justify-content:center;
        align-items:center;
    }

    .play-btn{
        background:#ff0000;
        color:white;
        padding:12px 24px;
        border-radius:50px;
        text-decoration:none;
        font-weight:bold;
    }

    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap');

    .show-wrap {
        font-family: 'Nunito', sans-serif;
        min-height: 100vh;
        background: #f8f9ff;
        padding-bottom: 60px;
    }

    /* ── Top navigation ── */
    .materi-topnav {
        background: linear-gradient(135deg,#0984e3,#74b9ff);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 4px 20px rgba(9,132,227,.3);
    }
    .back-btn {
        width: 38px;
        height: 38px;
        background: rgba(255,255,255,.2);
        border: 2px solid rgba(255,255,255,.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        transition: .2s;
        flex-shrink: 0;
    }
    .back-btn:hover { background: rgba(255,255,255,.35); color: #fff; }
    .topnav-info { flex: 1; }
    .topnav-title {
        font-family: 'Fredoka One', cursive;
        color: #fff;
        font-size: 17px;
        line-height: 1.2;
        margin: 0;
    }
    .topnav-mapel {
        font-size: 11px;
        color: rgba(255,255,255,.8);
        font-weight: 700;
        margin-top: 2px;
    }

    /* ── Hero card ── */
    .hero-card {
        max-width: 780px;
        margin: 28px auto 0;
        padding: 0 16px;
    }
    .hero-inner {
        background: linear-gradient(135deg,#0984e3 0%,#6c5ce7 100%);
        border-radius: 24px;
        padding: 28px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 12px 36px rgba(9,132,227,.25);
    }
    .hero-inner::before {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        right: -40px;
        top: -60px;
    }
    .hero-inner::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        left: -30px;
        bottom: -40px;
    }
    .hero-emoji-big {
        font-size: 70px;
        flex-shrink: 0;
        filter: drop-shadow(0 4px 12px rgba(0,0,0,.2));
        position: relative;
        z-index: 1;
        animation: floatHero 3s ease-in-out infinite;
    }
    @keyframes floatHero {
        0%,100% { transform: translateY(0); }
        50%      { transform: translateY(-8px); }
    }
    .hero-text { position: relative; z-index: 1; }
    .hero-judul {
        font-family: 'Fredoka One', cursive;
        font-size: 24px;
        color: #fff;
        margin: 0 0 6px;
        line-height: 1.2;
        text-shadow: 0 2px 8px rgba(0,0,0,.15);
    }
    .hero-meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .meta-chip {
        background: rgba(255,255,255,.2);
        border: 1px solid rgba(255,255,255,.3);
        color: rgba(255,255,255,.95);
        font-size: 12px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 50px;
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* ── Content area ── */
    .content-area {
        max-width: 780px;
        margin: 20px auto 0;
        padding: 0 16px;
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 20px;
        align-items: start;
    }
    @media(max-width: 680px) {
        .content-area { grid-template-columns: 1fr; }
        .sidebar { order: -1; }
    }

    /* ── Materi card ── */
    .materi-content-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 4px 24px rgba(0,0,0,.07);
        overflow: hidden;
    }
    .content-card-header {
        background: #f8f9ff;
        border-bottom: 2px dashed #e8eaff;
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Fredoka One', cursive;
        font-size: 17px;
        color: #2d3436;
    }
    .content-card-body {
        padding: 24px 22px;
    }

    /* Konten materi */
    .materi-text {
        font-size: 16px;
        line-height: 1.8;
        color: #2d3436;
    }
    .materi-text h1,.materi-text h2,.materi-text h3 {
        font-family: 'Fredoka One', cursive;
        color: #0984e3;
        margin-top: 24px;
    }
    .materi-text h1 { font-size: 22px; }
    .materi-text h2 { font-size: 18px; }
    .materi-text h3 { font-size: 16px; }
    .materi-text p  { margin-bottom: 14px; }
    .materi-text ul,
    .materi-text ol { padding-left: 22px; margin-bottom: 14px; }
    .materi-text li { margin-bottom: 6px; }
    .materi-text strong { color: #e17055; }
    .materi-text img {
        max-width: 100%;
        border-radius: 14px;
        border: 3px solid #f0f3ff;
        margin: 12px 0;
    }
    .materi-text table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        margin: 16px 0;
        font-size: 14px;
    }
    .materi-text th {
        background: linear-gradient(135deg,#0984e3,#74b9ff);
        color: #fff;
        padding: 10px 14px;
        font-weight: 800;
    }
    .materi-text td {
        border-bottom: 1px solid #f0f0f0;
        padding: 10px 14px;
    }
    .materi-text tr:nth-child(even) td { background: #f8f9ff; }

    /* Video embed */
    .video-wrap {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        border-radius: 16px;
        overflow: hidden;
        background: #000;
        margin: 16px 0;
    }
    .video-wrap iframe { position:absolute;top:0;left:0;width:100%;height:100%;border:none; }

    /* File lampiran */
    .lampiran-card {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f0f7ff;
        border: 2px solid #bde0fe;
        border-radius: 14px;
        padding: 14px 18px;
        text-decoration: none;
        color: #0984e3;
        font-weight: 700;
        font-size: 14px;
        transition: .2s;
        margin-top: 20px;
    }
    .lampiran-card:hover { background: #e0f0ff; transform: translateY(-2px); }
    .lampiran-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg,#0984e3,#74b9ff);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        flex-shrink: 0;
    }

    /* ── Sidebar ── */
    .sidebar { display: flex; flex-direction: column; gap: 14px; }

    .side-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        overflow: hidden;
    }
    .side-card-header {
        padding: 12px 16px;
        font-family: 'Fredoka One', cursive;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        border-bottom: 2px dashed #f0f0f0;
    }
    .side-card-body { padding: 14px 16px; }

    /* Info guru */
    .guru-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg,#6c5ce7,#a29bfe);
        color: #fff;
        font-family: 'Fredoka One', cursive;
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }
    .guru-name { text-align:center; font-size:14px; font-weight:800; color:#2d3436; }
    .guru-mapel{ text-align:center; font-size:12px; color:#b2bec3; font-weight:600; }

    /* Fun facts box */
    .fun-fact {
        background: linear-gradient(135deg,#fdcb6e,#e17055);
        border-radius: 16px;
        padding: 16px;
        color: #fff;
    }
    .fun-fact .emoji { font-size: 32px; display:block; margin-bottom:8px; }
    .fun-fact .label { font-family:'Fredoka One',cursive; font-size:14px; margin-bottom:6px; }
    .fun-fact .text  { font-size:13px; font-weight:700; line-height:1.5; }

    /* Tips belajar */
    .tips-list { list-style: none; padding: 0; margin: 0; }
    .tips-list li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #2d3436;
        padding: 7px 0;
        border-bottom: 1px dashed #f0f0f0;
    }
    .tips-list li:last-child { border-bottom: none; }
    .tips-list .tip-num {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: linear-gradient(135deg,#0984e3,#74b9ff);
        color: #fff;
        font-size: 11px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ── Tombol aksi bawah ── */
    .action-footer {
        max-width: 780px;
        margin: 30px auto 0;
        padding: 0 16px 30px;
        display: flex;
        gap: 15px;
        align-items: center;
    }
    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 22px;
        min-width: 140px;
        height: 48px;
        border-radius: 50px;
        font-family: 'Nunito', sans-serif;
        font-weight: 800;
        font-size: 14px;
        white-space: nowrap;
    }
    .btn-back-home { background: #f0f3ff; color: #6c5ce7; }
    .btn-back-home:hover { background: #e4dfff; color: #6c5ce7; }
    .btn-selesai {
        background: linear-gradient(135deg,#00b894,#00cec9);
        color: #fff;
        box-shadow: 0 4px 18px rgba(0,184,148,.3);
        min-width: 180px;
        justify-content: center;
    }
    .btn-selesai:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(0,184,148,.4); color: #fff; }

    /* Scroll progress */
    .scroll-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg,#fdcb6e,#e17055);
        z-index: 999;
        transition: width .1s;
    }

    /* Fun floating emojis */
    .float-emoji {
        position: fixed;
        font-size: 24px;
        pointer-events: none;
        animation: floatUp 2.5s ease-out forwards;
        z-index: 500;
    }
    @keyframes floatUp {
        0%   { opacity:1; transform:translateY(0) scale(1); }
        100% { opacity:0; transform:translateY(-120px) scale(1.4); }
    }
</style>
@endpush

@section('content')
<div class="show-wrap">

    {{-- Scroll progress bar --}}
    <div class="scroll-progress" id="scrollProgress" style="width:0%"></div>

    {{-- ── TOP NAV ── --}}
    <div class="materi-topnav">
        <a href="{{ route('siswa.materi.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="topnav-info">
            <p class="topnav-title">{{ Str::limit($materi->judul, 40) }}</p>
            <div class="topnav-mapel">
                📖 {{ $materi->mataPelajaran->nama ?? 'Materi' }}
            </div>
        </div>
    </div>

    {{-- ── HERO ── --}}
    <div class="hero-card">
        <div class="hero-inner">
            @php
                $mapelIcons = ['Matematika'=>'🔢','Bahasa Indonesia'=>'📝','IPA'=>'🔬','IPS'=>'🌍','Bahasa Inggris'=>'🗣️','Seni Budaya'=>'🎨','PJOK'=>'⚽','Agama'=>'🙏','PKN'=>'🇮🇩'];
                $icon = $mapelIcons[$materi->mataPelajaran->nama ?? ''] ?? '📗';
            @endphp
            <div class="hero-emoji-big">{{ $icon }}</div>
            <div class="hero-text">
                <h1 class="hero-judul">{{ $materi->judul }}</h1>
                <div class="hero-meta">
                    <div class="meta-chip">
                        <i class="fas fa-book-open"></i>
                        {{ $materi->mataPelajaran->nama ?? '-' }}
                    </div>
                    <div class="meta-chip">
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ $materi->guru->user->name ?? 'Guru' }}
                    </div>
                    <div class="meta-chip">
                        <i class="fas fa-calendar"></i>
                        {{ $materi->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MAIN CONTENT ── --}}
    <div class="content-area">

        {{-- Konten Materi --}}
        <div>
            <div class="materi-content-card">
                <div class="content-card-header">
                    <i class="fas fa-book-open" style="color:#0984e3;"></i>
                    Isi Materi
                </div>
                <div class="content-card-body">
                    @if($materi->link_video)
                        @php
                            preg_match('/(?:v=|youtu\.be\/)([^&\?]+)/', $materi->link_video, $matches);
                            $youtubeId = $matches[1] ?? '';
                        @endphp

                        <div class="youtube-card">
                            <img src="https://img.youtube.com/vi/{{ $youtubeId }}/maxresdefault.jpg"
                                alt="Thumbnail Video">

                            <div class="youtube-overlay">
                                <a href="{{ $materi->link_video }}" target="_blank" class="play-btn">
                                    ▶ Tonton Video
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="materi-text" id="materiContent">
                        {!! $materi->konten !!}
                    </div>

                    @if($materi->file ?? false)
                    <a href="{{ asset('storage/'.$materi->file) }}" target="_blank" class="lampiran-card">
                        <div class="lampiran-icon"><i class="fas fa-file-pdf"></i></div>
                        <div>
                            <div>📎 Unduh Lampiran Materi</div>
                            <div style="font-size:11px;font-weight:600;color:#74b9ff;margin-top:2px;">Klik untuk membuka file</div>
                        </div>
                        <i class="fas fa-download" style="margin-left:auto;font-size:18px;"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ── AKSI FOOTER ── --}}
    <div class="action-footer">
        <a href="{{ route('siswa.materi.index') }}" class="btn-action btn-back-home">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button class="btn-action btn-selesai" onclick="tandaiSelesai(this)">
            <i class="fas fa-check-circle"></i> Selesai Belajar! 🎉
        </button>
    </div>

</div>

@push('scripts')
<script>
// Scroll progress
window.addEventListener('scroll', () => {
    const doc    = document.documentElement;
    const top    = doc.scrollTop || document.body.scrollTop;
    const height = doc.scrollHeight - doc.clientHeight;
    const pct    = height > 0 ? (top / height * 100) : 0;
    document.getElementById('scrollProgress').style.width = pct + '%';
});

// Selesai belajar
function tandaiSelesai(btn) {
    btn.innerHTML = '<i class="fas fa-check-circle"></i> Hore! Kamu keren! 🌟';
    btn.style.background = 'linear-gradient(135deg,#fdcb6e,#e17055)';
    btn.disabled = true;

    // Spawn emoji confetti
    const emojis = ['⭐','🌟','🎉','✨','💫','🎊','🏆','💪'];
    for (let i = 0; i < 12; i++) {
        setTimeout(() => {
            const el = document.createElement('div');
            el.className = 'float-emoji';
            el.textContent = emojis[Math.floor(Math.random() * emojis.length)];
            el.style.left = Math.random() * 80 + 10 + 'vw';
            el.style.bottom = '80px';
            el.style.fontSize = (18 + Math.random() * 20) + 'px';
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2500);
        }, i * 150);
    }

    // Redirect ke index setelah 2.5s
    setTimeout(() => {
        window.location.href = '{{ route("siswa.materi.index") }}';
    }, 2800);
}
</script>
@endpush

@endsection