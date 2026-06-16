@extends('layouts.siswa')
@section('title', 'Kerjakan — ' . $tugas->judul)

@push('styles')
<style>

    .btn-kembali-tugas{
        background:linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color:#fff;
        border-radius:12px;
        padding:12px 24px;
        font-weight:600;
        text-decoration:none;
        display:inline-block;
        border:none;
        cursor:pointer;
        transition:.2s;
    }

    .btn-kembali-tugas:hover{
        background:linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color:#fff;
    }

    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap');

    /* ── Base ── */
    .cbt-wrap {
        font-family: 'Nunito', sans-serif;
        min-height: 100vh;
        background: #f0f7ff;
        padding: 0 0 60px;
    }

    /* ── Header bar ── */
    .cbt-topbar {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 4px 20px rgba(108,92,231,.3);
        position: sticky;
        top: 0;
        z-index: 99;
    }
    .cbt-topbar .title {
        font-family: 'Fredoka One', cursive;
        color: #fff;
        font-size: 20px;
        letter-spacing: .5px;
    }
    .cbt-topbar .subtitle {
        color: rgba(255,255,255,.8);
        font-size: 12px;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Timer */
    .timer-box {
        background: rgba(255,255,255,.18);
        border: 2px solid rgba(255,255,255,.4);
        border-radius: 14px;
        padding: 8px 18px;
        text-align: center;
        backdrop-filter: blur(8px);
        min-width: 90px;
    }
    .timer-box .time-val {
        font-family: 'Fredoka One', cursive;
        font-size: 22px;
        color: #fff;
        line-height: 1;
    }
    .timer-box .time-label {
        font-size: 10px;
        color: rgba(255,255,255,.8);
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .timer-box.danger { background: rgba(255,100,100,.3); border-color: #ff6b6b; }
    .timer-box.danger .time-val { color: #ffd6d6; animation: blink .7s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.4} }

    /* Progres soal */
    .progress-pills {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        max-width: 420px;
    }
    .pill {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255,255,255,.5);
        color: rgba(255,255,255,.7);
        cursor: pointer;
        transition: .2s;
        flex-shrink: 0;
    }
    .pill.answered    { background: #00b894; border-color: #00b894; color: #fff; }
    .pill.current     { background: #fff; border-color: #fff; color: #6c5ce7; font-weight: 900; }
    .pill:hover       { transform: scale(1.12); }

    /* ── Main area ── */
    .cbt-body {
        max-width: 780px;
        margin: 28px auto 0;
        padding: 0 16px;
    }

    /* Soal card */
    .soal-wrapper { display: none; }
    .soal-wrapper.active { display: block; }

    .soal-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(108,92,231,.08);
        padding: 28px;
        margin-bottom: 16px;
        animation: popIn .3s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes popIn {
        from { opacity:0; transform:translateY(18px) scale(.97); }
        to   { opacity:1; transform:none; }
    }

    .soal-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg,#6c5ce7,#a29bfe);
        color: #fff;
        border-radius: 50px;
        padding: 5px 14px;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 14px;
        letter-spacing: .3px;
    }

    .soal-text {
        font-size: 18px;
        font-weight: 700;
        color: #2d3436;
        line-height: 1.55;
        margin-bottom: 18px;
    }

    .soal-img {
        max-width: 100%;
        border-radius: 14px;
        border: 3px solid #f0f3ff;
        margin-bottom: 18px;
    }

    /* Pilihan jawaban */
    .pilihan-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    @media(max-width:520px) { .pilihan-grid { grid-template-columns: 1fr; } }

    .pilihan-label {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8f9ff;
        border: 2.5px solid #e8eaff;
        border-radius: 16px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all .2s;
        user-select: none;
        font-weight: 700;
        font-size: 15px;
        color: #2d3436;
    }
    .pilihan-label:hover { border-color: #a29bfe; background: #f0edff; transform: translateY(-2px); box-shadow: 0 6px 18px rgba(108,92,231,.12); }
    .pilihan-label input[type=radio] { display: none; }
    .pilihan-label.selected { border-color: #6c5ce7; background: linear-gradient(135deg,#f3f0ff,#e9e4ff); box-shadow: 0 6px 20px rgba(108,92,231,.2); }

    .opt-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #e8eaff;
        color: #6c5ce7;
        font-family: 'Fredoka One', cursive;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: .2s;
    }
    .pilihan-label.selected .opt-circle {
        background: #6c5ce7;
        color: #fff;
    }

    /* Navigasi bawah */
    .nav-bar {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,.07);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .btn-nav {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 50px;
        font-family: 'Nunito', sans-serif;
        font-weight: 800;
        font-size: 14px;
        cursor: pointer;
        border: none;
        transition: .2s;
    }
    .btn-prev { background: #f0edff; color: #6c5ce7; }
    .btn-prev:hover { background: #e4dfff; transform: translateX(-2px); }
    .btn-next { background: linear-gradient(135deg,#6c5ce7,#a29bfe); color: #fff; box-shadow: 0 4px 16px rgba(108,92,231,.35); }
    .btn-next:hover { transform: translateX(2px); box-shadow: 0 6px 22px rgba(108,92,231,.45); }
    .btn-submit { background: linear-gradient(135deg,#00b894,#00cec9); color: #fff; box-shadow: 0 4px 16px rgba(0,184,148,.35); }
    .btn-submit:hover { transform: scale(1.03); box-shadow: 0 6px 22px rgba(0,184,148,.45); }

    .soal-counter {
        font-size: 13px;
        font-weight: 800;
        color: #6c5ce7;
        text-align: center;
    }
    .soal-counter span { color: #a29bfe; }

    /* Progress bar bawah */
    .progress-bar-wrap {
        height: 6px;
        background: #e8eaff;
        border-radius: 50px;
        margin-top: 12px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg,#6c5ce7,#a29bfe);
        border-radius: 50px;
        transition: width .4s;
    }

    /* ── Modal Selesai ── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(44,36,86,.5);
        backdrop-filter: blur(6px);
        z-index: 999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 28px;
        padding: 40px 32px;
        max-width: 420px;
        width: 100%;
        text-align: center;
        box-shadow: 0 24px 64px rgba(0,0,0,.18);
        animation: modalPop .4s cubic-bezier(.34,1.56,.64,1);
    }
    @keyframes modalPop { from{transform:scale(.7);opacity:0} to{transform:none;opacity:1} }
    .modal-emoji { font-size: 64px; margin-bottom: 12px; animation: bounce 1s infinite; }
    @keyframes bounce { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
    .modal-title { font-family:'Fredoka One',cursive; font-size:26px; color:#2d3436; margin-bottom:6px; }
    .modal-sub { font-size:14px; color:#636e72; font-weight:600; margin-bottom:24px; }

    /* Hasil nilai */
    .nilai-display {
        background: linear-gradient(135deg,#6c5ce7,#a29bfe);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .nilai-num {
        font-family: 'Fredoka One', cursive;
        font-size: 64px;
        color: #fff;
        line-height: 1;
    }
    .nilai-label { font-size: 13px; color: rgba(255,255,255,.8); font-weight: 700; margin-top: 4px; }
    .nilai-detail { font-size: 14px; font-weight: 700; color: #fff; margin-top: 8px; }

    /* ── Loading overlay ── */
    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(44,36,86,.6);
        backdrop-filter: blur(8px);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 16px;
    }
    .loading-overlay.open { display: flex; }
    .loading-spinner {
        width: 56px;
        height: 56px;
        border: 5px solid rgba(255,255,255,.2);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-text { color: #fff; font-weight: 800; font-size: 16px; }
</style>
@endpush

@section('content')
<div class="cbt-wrap">

    {{-- ── TOP BAR ── --}}
    <div class="cbt-topbar">
        <div>
            <div class="title">📝 {{ $tugas->judul }}</div>
            <div class="subtitle">
                {{ $tugas->mataPelajaran->nama ?? '' }}
                &nbsp;·&nbsp;
                {{ $pertanyaan->count() }} Soal
            </div>
        </div>

        <div class="progress-pills" id="pillContainer">
            @foreach($pertanyaan as $i => $p)
            <div class="pill {{ $i === 0 ? 'current' : '' }}" id="pill-{{ $i }}"
                 onclick="goTo({{ $i }})">{{ $i+1 }}</div>
            @endforeach
        </div>

        <div class="timer-box" id="timerBox">
            <div class="time-val" id="timerDisplay">--:--</div>
            <div class="time-label">Waktu</div>
        </div>
    </div>

    {{-- ── BODY ── --}}
    <div class="cbt-body">

        <div style="margin-bottom:15px;">
            <button type="button"
                    class="btn-kembali-tugas"
                    onclick="konfirmasiKembali()">
                    <i class="fas fa-arrow-left"></i>
                Kembali
            </button>
        </div>

        <script>
        function konfirmasiKembali() {
            if (confirm('Yakin ingin kembali? Jawaban yang belum dikumpulkan bisa hilang.')) {
                history.back();
            }
        }
        </script>

        @foreach($pertanyaan as $i => $p)
        <div class="soal-wrapper {{ $i === 0 ? 'active' : '' }}" id="soal-{{ $i }}">
            <div class="soal-card">
                <div class="soal-badge">
                    <i class="fas fa-circle-question"></i>
                    Soal {{ $i + 1 }} dari {{ $pertanyaan->count() }}
                </div>

                <p class="soal-text">{{ $p->soal }}</p>

                @if($p->gambar_soal)
                    @if(str_starts_with($p->gambar_soal, 'http'))
                        {{-- URL langsung dari Picsum --}}
                        <img src="{{ $p->gambar_soal }}" class="soal-img" alt="Gambar soal">
                    @else
                        {{-- Path lokal di storage --}}
                        <img src="{{ asset('storage/' . $p->gambar_soal) }}" class="soal-img" alt="Gambar soal">
                    @endif
                @endif

                <div class="pilihan-grid" id="pilihan-group-{{ $p->id }}">
                    @foreach(['A'=>$p->pilihan_a,'B'=>$p->pilihan_b,'C'=>$p->pilihan_c,'D'=>$p->pilihan_d] as $opt=>$teks)
                    <label class="pilihan-label" id="lbl-{{ $p->id }}-{{ $opt }}"
                           onclick="pilih({{ $p->id }}, '{{ $opt }}', {{ $i }})">
                        <input type="radio" name="jawaban_{{ $p->id }}" value="{{ $opt }}">
                        <span class="opt-circle">{{ $opt }}</span>
                        {{ $teks }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        {{-- ── NAVIGASI ── --}}
        <div class="nav-bar">
            <button class="btn-nav btn-prev" onclick="prevSoal()" id="btnPrev" disabled>
                <i class="fas fa-arrow-left"></i> Sebelumnya
            </button>

            <div>
                <div class="soal-counter">
                    Soal <span id="curNum">1</span> / {{ $pertanyaan->count() }}
                </div>
                <div class="progress-bar-wrap" style="min-width:140px;">
                    <div class="progress-bar-fill" id="progressFill" style="width:{{ round(1/$pertanyaan->count()*100) }}%;"></div>
                </div>
            </div>

            <button class="btn-nav btn-next" onclick="nextSoal()" id="btnNext">
                Selanjutnya <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        {{-- tombol selesai muncul di soal terakhir --}}
        <div style="text-align:center;margin-top:16px;" id="btnSubmitWrap" style="display:none;">
            <button class="btn-nav btn-submit" onclick="openConfirm()" id="btnSubmitBottom" style="display:none;">
                <i class="fas fa-paper-plane"></i> Selesai & Kumpulkan!
            </button>
        </div>

    </div>
</div>

{{-- ── MODAL KONFIRMASI ── --}}
<div class="modal-overlay" id="modalConfirm">
    <div class="modal-box">
        <div class="modal-emoji">🤔</div>
        <div class="modal-title">Sudah yakin?</div>
        <div class="modal-sub">
            Kamu sudah menjawab <strong id="jumlahJawab">0</strong>
            dari <strong>{{ $pertanyaan->count() }}</strong> soal.
        </div>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button class="btn-nav btn-prev" onclick="closeConfirm()" style="padding:12px 24px;">
                Cek Lagi
            </button>
            <button class="btn-nav btn-submit" onclick="submitCBT()" style="padding:12px 24px;">
                Ya, Kumpulkan! 🎉
            </button>
        </div>
    </div>
</div>

{{-- ── MODAL HASIL ── --}}
<div class="modal-overlay" id="modalHasil">
    <div class="modal-box">
        <div class="modal-emoji" id="hasilEmoji">🎉</div>
        <div class="modal-title" id="hasilTitle">Selesai!</div>
        <div class="modal-sub" id="hasilSub">Kerja bagus!</div>
        <div class="nilai-display">
            <div class="nilai-num" id="nilaiNum">—</div>
            <div class="nilai-label">Nilai Kamu</div>
            <div class="nilai-detail" id="nilaiDetail"></div>
        </div>
        <a href="{{ route('siswa.tugas.index') }}" class="btn-nav btn-submit"
           style="display:inline-flex;text-decoration:none;padding:14px 28px;font-size:15px;">
            Kembali ke Daftar Tugas 🏠
        </a>
    </div>
</div>

{{-- ── LOADING ── --}}
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Menghitung nilaimu... ✨</div>
</div>

@push('scripts')
<script>
// ── Data ──────────────────────────────────────────────────────────────────────
const TOTAL     = {{ $pertanyaan->count() }};
const jawaban   = {};   // { soalId: 'A'/'B'/'C'/'D' }
let   current   = 0;

// Timer — ambil durasi dari deadline (maks 90 menit), default 60 menit
@if($tugas->deadline)
    const deadlineMs = {{ now()->diffInSeconds($tugas->deadline, false) * 1000 }};
    let remainSec    = Math.max(0, Math.floor(deadlineMs / 1000));
    // Batas atas 90 menit supaya nggak kelamaan
    if (remainSec > 5400) remainSec = 5400;
@else
    let remainSec = 3600; // default 60 menit
@endif

// ── Timer ─────────────────────────────────────────────────────────────────────
const timerDisplay = document.getElementById('timerDisplay');
const timerBox     = document.getElementById('timerBox');

function formatTime(sec) {
    const m = String(Math.floor(sec / 60)).padStart(2, '0');
    const s = String(sec % 60).padStart(2, '0');
    return `${m}:${s}`;
}

const timerInterval = setInterval(() => {
    remainSec--;
    timerDisplay.textContent = formatTime(remainSec);
    if (remainSec <= 60) timerBox.classList.add('danger');
    if (remainSec <= 0) {
        clearInterval(timerInterval);
        submitCBT();
    }
}, 1000);

timerDisplay.textContent = formatTime(remainSec);

// ── Navigasi ──────────────────────────────────────────────────────────────────
function showSoal(idx) {
    document.querySelectorAll('.soal-wrapper').forEach((el, i) => {
        el.classList.toggle('active', i === idx);
    });
    document.querySelectorAll('.pill').forEach((p, i) => {
        p.classList.toggle('current', i === idx);
    });

    current = idx;
    document.getElementById('curNum').textContent = idx + 1;
    document.getElementById('progressFill').style.width = ((idx + 1) / TOTAL * 100) + '%';

    const btnPrev = document.getElementById('btnPrev');

    if (idx === 0) {
        btnPrev.style.display = 'none';
        btnPrev.disabled = true;
    } else {
        btnPrev.style.display = 'inline-flex';
        btnPrev.disabled = false;
    }

    const isLast = idx === TOTAL - 1;
    document.getElementById('btnNext').style.display    = isLast ? 'none' : '';
    document.getElementById('btnSubmitBottom').style.display = isLast ? 'inline-flex' : 'none';
    document.getElementById('btnSubmitWrap').style.display   = isLast ? 'block' : 'none';
}

function nextSoal() { if (current < TOTAL - 1) showSoal(current + 1); }
function prevSoal() { if (current > 0) showSoal(current - 1); }
function goTo(idx)  { showSoal(idx); }

// ── Pilih jawaban ─────────────────────────────────────────────────────────────
function pilih(soalId, opt, soalIdx) {
    jawaban[soalId] = opt;

    // Update tampilan pilihan
    document.querySelectorAll(`#pilihan-group-${soalId} .pilihan-label`).forEach(lbl => {
        lbl.classList.remove('selected');
    });
    document.getElementById(`lbl-${soalId}-${opt}`).classList.add('selected');

    // Update pill
    const pill = document.getElementById(`pill-${soalIdx}`);
    pill.classList.add('answered');
}

// ── Submit ────────────────────────────────────────────────────────────────────
function openConfirm() {
    document.getElementById('jumlahJawab').textContent = Object.keys(jawaban).length;
    document.getElementById('modalConfirm').classList.add('open');
}
function closeConfirm() {
    document.getElementById('modalConfirm').classList.remove('open');
}

async function submitCBT() {
    clearInterval(timerInterval);
    closeConfirm();
    document.getElementById('loadingOverlay').classList.add('open');

    const body = new URLSearchParams();
    body.append('_token', '{{ csrf_token() }}');
    for (const [id, opt] of Object.entries(jawaban)) {
        body.append(`jawaban_${id}`, opt);
    }

    try {
        const res  = await fetch('{{ route("siswa.tugas.simpanCBT", $tugas) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString(),
        });
        const data = await res.json();

        document.getElementById('loadingOverlay').classList.remove('open');

        // Tampilkan hasil
        const nilai = data.nilai;
        const benar = data.benar;

        let emoji, title, sub;
        if (nilai >= 90)      { emoji = '🏆'; title = 'Luar Biasa!';     sub = 'Kamu hebat sekali!'; }
        else if (nilai >= 75) { emoji = '🌟'; title = 'Bagus Banget!';   sub = 'Terus semangat ya!'; }
        else if (nilai >= 60) { emoji = '😊'; title = 'Cukup Bagus!';    sub = 'Belajar lagi ya!'; }
        else                  { emoji = '💪'; title = 'Tetap Semangat!'; sub = 'Jangan menyerah!'; }

        document.getElementById('hasilEmoji').textContent  = emoji;
        document.getElementById('hasilTitle').textContent  = title;
        document.getElementById('hasilSub').textContent    = sub;
        document.getElementById('nilaiNum').textContent    = nilai;
        document.getElementById('nilaiDetail').textContent = `${benar} benar dari ${TOTAL} soal`;

        document.getElementById('modalHasil').classList.add('open');

    } catch (e) {
        document.getElementById('loadingOverlay').classList.remove('open');
        alert('Terjadi kesalahan. Coba lagi ya!');
    }
}

// Init
showSoal(0);
</script>
@endpush

@endsection