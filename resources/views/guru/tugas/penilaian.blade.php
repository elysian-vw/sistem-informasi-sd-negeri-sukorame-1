@extends('layouts.guru')
@section('title', 'Penilaian — ' . $tugas->judul)

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════
   PENILAIAN — shared vars (inherits layout vars)
═══════════════════════════════════════════════════════════ */
.penilaian-wrap { --purple:#7c3aed; --purple-light:#f5f3ff; }

/* ── Tabs ── */
.tab-bar {
    display:flex;
    gap:0;
    background:var(--bg);
    border:1px solid var(--border);
    border-radius:12px;
    padding:4px;
    margin-bottom:20px;
    width:fit-content;
}
.tab-btn {
    padding:8px 20px;
    border-radius:9px;
    border:none;
    background:transparent;
    font-size:13px;
    font-weight:700;
    color:var(--text-secondary);
    cursor:pointer;
    transition:.2s;
    display:flex;
    align-items:center;
    gap:7px;
    white-space:nowrap;
}
.tab-btn.active {
    background:#fff;
    color:var(--primary);
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}
.tab-btn.active.cbt-tab { color:var(--purple); }

/* ── Rekap soal CBT ── */
.soal-rekap-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:14px;
    margin-bottom:20px;
}
.soal-rekap-card {
    background:#fff;
    border:1.5px solid var(--border);
    border-radius:14px;
    padding:16px;
    position:relative;
    overflow:hidden;
}
.soal-rekap-card::before {
    content:'';
    position:absolute;
    left:0;top:0;bottom:0;
    width:4px;
    background:linear-gradient(180deg,var(--primary),var(--secondary,#818cf8));
    border-radius:4px 0 0 4px;
}
.soal-num-badge {
    font-size:11px;
    font-weight:800;
    color:var(--primary);
    background:var(--primary-light);
    padding:2px 10px;
    border-radius:50px;
    display:inline-block;
    margin-bottom:8px;
}
.soal-text-preview {
    font-size:13px;
    font-weight:600;
    color:var(--text-primary);
    margin-bottom:10px;
    line-height:1.4;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
    overflow:hidden;
}
.jawaban-bar { display:flex; flex-direction:column; gap:4px; }
.jawaban-row {
    display:flex;
    align-items:center;
    gap:8px;
    font-size:12px;
    font-weight:700;
}
.jawaban-opt-badge {
    width:22px;height:22px;
    border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:800;
    flex-shrink:0;
    background:var(--bg);
    color:var(--text-secondary);
}
.jawaban-opt-badge.correct { background:#dcfce7;color:#16a34a; }
.jawaban-bar-fill-wrap {
    flex:1;
    background:var(--bg);
    border-radius:50px;
    height:7px;
    overflow:hidden;
}
.jawaban-bar-fill {
    height:100%;
    border-radius:50px;
    background:var(--border);
    transition:width .5s .1s;
}
.jawaban-bar-fill.correct { background:linear-gradient(90deg,#22c55e,#4ade80); }
.jawaban-count {
    font-size:11px;
    font-weight:800;
    color:var(--text-muted);
    min-width:28px;
    text-align:right;
}
.benar-pct {
    position:absolute;
    top:14px;right:14px;
    font-size:22px;
    font-weight:900;
    color:var(--primary);
    line-height:1;
}
.benar-pct small { font-size:11px;font-weight:700;color:var(--text-muted);display:block;text-align:right; }

/* ── Tabel penilaian ── */
.nilai-input {
    text-align:center;
    font-weight:700;
    font-size:14px;
}
.grade-badge { font-weight:900;font-size:10px; }

/* Warna baris CBT —  sudah otomatis, jadi read-only feel */
tr.cbt-row td { background:#fafafa; }
tr.cbt-row:hover td { background:#f5f3ff; }

/* Auto-nilai badge */
.auto-badge {
    display:inline-flex;align-items:center;gap:4px;
    font-size:10px;font-weight:800;
    background:#ede9fe;color:var(--purple);
    padding:2px 8px;border-radius:50px;
}

/* Nilai besar di CBT */
.nilai-cbt-display {
    text-align:center;
    font-size:20px;
    font-weight:900;
    color:var(--primary);
    padding:4px 0;
}
.nilai-cbt-display.a { color:#16a34a; }
.nilai-cbt-display.b { color:#2563eb; }
.nilai-cbt-display.c { color:#d97706; }
.nilai-cbt-display.d, .nilai-cbt-display.e { color:#dc2626; }

/* ── Export btn ── */
.btn-export {
    display:inline-flex;align-items:center;gap:6px;
    background:#f0fdf4;color:#16a34a;
    border:1.5px solid #bbf7d0;
    padding:7px 16px;border-radius:8px;
    font-size:12px;font-weight:700;
    cursor:pointer;text-decoration:none;
    transition:.15s;
}
.btn-export:hover { background:#dcfce7;color:#15803d; }

/* ── Panel bulk ── */
#panelBulk {
    padding:12px 20px;
    background:var(--primary-light);
    border-bottom:1px solid var(--border);
}

/* ── Distribusi nilai (CBT) ── */
.distribusi-wrap {
    display:flex;
    gap:8px;
    align-items:flex-end;
    height:80px;
    padding:0 4px;
}
.dist-bar-group { display:flex;flex-direction:column;align-items:center;gap:4px;flex:1; }
.dist-bar {
    width:100%;
    border-radius:6px 6px 0 0;
    background:linear-gradient(180deg,var(--primary),var(--secondary,#818cf8));
    transition:height .5s;
    min-height:3px;
}
.dist-label { font-size:10px;font-weight:800;color:var(--text-muted); }
.dist-count  { font-size:11px;font-weight:900;color:var(--primary); }
</style>
@endpush

@section('content')
<div class="penilaian-wrap">

{{-- ══ HEADER ══════════════════════════════════════════════════════════════ --}}
<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('guru.tugas.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-title">Penilaian Tugas</h1>
            <p class="page-subtitle">
                <a href="{{ route('guru.tugas.index') }}">Tugas</a>
                &nbsp;/&nbsp; {{ Str::limit($tugas->judul, 45) }}
                &nbsp;·&nbsp; {{ $tugas->kelas->nama_kelas ?? '-' }}
                &nbsp;·&nbsp;
                @if($tugas->tipe === 'cbt')
                    <span style="color:var(--purple);font-weight:700;"><i class="fas fa-list-check"></i> CBT</span>
                @else
                    <span style="color:var(--primary);font-weight:700;"><i class="fas fa-file-arrow-up"></i> Upload</span>
                @endif
            </p>
        </div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        @if($tugas->tipe === 'cbt')
            <a href="{{ route('guru.tugas.soal', $tugas) }}"
               style="background:var(--purple-light);color:var(--purple);border:1.5px solid #ddd6fe;"
               class="btn-export">
                <i class="fas fa-circle-question"></i> Kelola Soal
            </a>
        @endif
        <button class="btn-export" onclick="exportCSV()">
            <i class="fas fa-file-csv"></i> Export CSV
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert-success-toast"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- ══ STATISTIK ════════════════════════════════════════════════════════════ --}}
@php
    $totalSiswa   = $siswaKelas->count();
    $sudahKumpul  = $pengumpulan->count();
    $belumKumpul  = $totalSiswa - $sudahKumpul;
    $sudahDinilai = $pengumpulan->whereNotNull('nilai')->count();
    $pctKumpul    = $totalSiswa > 0 ? round(($sudahKumpul / $totalSiswa) * 100) : 0;

    // Statistik nilai
    $nilaiList    = $pengumpulan->whereNotNull('nilai')->pluck('nilai');
    $rataRata     = $nilaiList->count() ? round($nilaiList->avg(), 1) : null;
    $nilaiTertinggi = $nilaiList->count() ? $nilaiList->max() : null;
    $nilaiTerendah  = $nilaiList->count() ? $nilaiList->min() : null;

    // Distribusi A–E
    $distrib = ['A'=>0,'B'=>0,'C'=>0,'D'=>0,'E'=>0];
    foreach ($nilaiList as $n) {
        if ($n >= 90)      $distrib['A']++;
        elseif ($n >= 80)  $distrib['B']++;
        elseif ($n >= 70)  $distrib['C']++;
        elseif ($n >= 60)  $distrib['D']++;
        else               $distrib['E']++;
    }
    $distribMax = max(1, max($distrib));
@endphp

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:16px;">
    <div class="stat-card stat-blue">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div><span class="stat-number">{{ $totalSiswa }}</span><span class="stat-label">Total Siswa</span></div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div><span class="stat-number">{{ $sudahKumpul }}</span><span class="stat-label">Sudah {{ $tugas->isCbt() ? 'Mengerjakan' : 'Kumpul' }}</span></div>
    </div>
    <div class="stat-card stat-orange">
        <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
        <div><span class="stat-number">{{ $belumKumpul }}</span><span class="stat-label">Belum {{ $tugas->isCbt() ? 'Mengerjakan' : 'Kumpul' }}</span></div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-icon"><i class="fas fa-star"></i></div>
        <div><span class="stat-number">{{ $sudahDinilai }}</span><span class="stat-label">Sudah Dinilai</span></div>
    </div>
</div>

{{-- Baris kedua: statistik nilai + progress + distribusi --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:20px;">

    {{-- Progress pengumpulan --}}
    <div class="content-card" style="margin-bottom:0;">
        <div class="card-body" style="padding:16px 18px;">
            <div style="font-size:12px;font-weight:700;color:var(--text-secondary);margin-bottom:8px;">
                <i class="fas fa-chart-line" style="color:var(--primary);margin-right:4px;"></i>
                Progress Pengumpulan
            </div>
            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:6px;">
                <span style="font-size:28px;font-weight:900;color:var(--primary);">{{ $pctKumpul }}%</span>
                <span style="font-size:12px;color:var(--text-muted);font-weight:700;">{{ $sudahKumpul }}/{{ $totalSiswa }}</span>
            </div>
            <div style="background:var(--bg);border-radius:100px;height:8px;overflow:hidden;">
                <div style="width:{{ $pctKumpul }}%;background:linear-gradient(90deg,var(--primary),var(--secondary,#818cf8));height:100%;border-radius:100px;transition:width .4s;"></div>
            </div>
            @if($tugas->deadline)
            <div style="font-size:11px;margin-top:8px;color:{{ now()->gt($tugas->deadline) ? 'var(--danger)':'var(--text-muted)' }};">
                <i class="fas fa-clock" style="margin-right:3px;"></i>
                Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}
                @if(now()->gt($tugas->deadline)) &nbsp;<strong>· Sudah lewat</strong>@endif
            </div>
            @endif
        </div>
    </div>

    {{-- Rata-rata nilai --}}
    <div class="content-card" style="margin-bottom:0;">
        <div class="card-body" style="padding:16px 18px;">
            <div style="font-size:12px;font-weight:700;color:var(--text-secondary);margin-bottom:8px;">
                <i class="fas fa-calculator" style="color:var(--primary);margin-right:4px;"></i>
                Statistik Nilai
            </div>
            @if($rataRata !== null)
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;text-align:center;">
                <div>
                    <div style="font-size:22px;font-weight:900;color:var(--primary);">{{ $rataRata }}</div>
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);">Rata-rata</div>
                </div>
                <div>
                    <div style="font-size:22px;font-weight:900;color:#16a34a;">{{ $nilaiTertinggi }}</div>
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);">Tertinggi</div>
                </div>
                <div>
                    <div style="font-size:22px;font-weight:900;color:#dc2626;">{{ $nilaiTerendah }}</div>
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);">Terendah</div>
                </div>
            </div>
            @else
            <div style="text-align:center;padding:12px 0;color:var(--text-muted);font-size:13px;">
                <i class="fas fa-minus-circle"></i> Belum ada nilai
            </div>
            @endif
        </div>
    </div>

    {{-- Distribusi nilai --}}
    <div class="content-card" style="margin-bottom:0;">
        <div class="card-body" style="padding:16px 18px;">
            <div style="font-size:12px;font-weight:700;color:var(--text-secondary);margin-bottom:10px;">
                <i class="fas fa-chart-bar" style="color:var(--primary);margin-right:4px;"></i>
                Distribusi Nilai
            </div>
            <div class="distribusi-wrap">
                @foreach($distrib as $grade => $cnt)
                <div class="dist-bar-group">
                    <div class="dist-count">{{ $cnt > 0 ? $cnt : '' }}</div>
                    <div class="dist-bar" style="height:{{ round($cnt/$distribMax*60) }}px;"
                         title="Grade {{ $grade }}: {{ $cnt }} siswa"></div>
                    <div class="dist-label">{{ $grade }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ══ REKAP SOAL (khusus CBT) ════════════════════════════════════════════ --}}
@if($tugas->isCbt() && isset($rekapSoal) && $rekapSoal->count())
<div class="content-card" style="margin-bottom:20px;">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <h3>
            <i class="fas fa-chart-pie" style="color:var(--purple);margin-right:8px;"></i>
            Rekap Per Soal
            <span style="font-size:12px;font-weight:600;color:var(--text-muted);margin-left:6px;">
                — Persentase siswa yang menjawab benar
            </span>
        </h3>
        <span class="auto-badge"><i class="fas fa-wand-magic-sparkles"></i> Otomatis dari CBT</span>
    </div>
    <div class="card-body">
        <div class="soal-rekap-grid">
            @foreach($rekapSoal as $i => $rs)
            @php
                $total    = array_sum($rs['distribusi']);
                $benarCnt = $rs['distribusi'][$rs['jawaban_benar']] ?? 0;
                $pctBenar = $total > 0 ? round($benarCnt / $total * 100) : 0;
            @endphp
            <div class="soal-rekap-card">
                <div class="benar-pct">
                    {{ $pctBenar }}%
                    <small>Benar</small>
                </div>
                <div class="soal-num-badge">Soal {{ $i + 1 }}</div>
                <p class="soal-text-preview">{{ $rs['soal'] }}</p>
                <div class="jawaban-bar">
                    @foreach(['A','B','C','D'] as $opt)
                    @php
                        $cnt = $rs['distribusi'][$opt] ?? 0;
                        $pct = $total > 0 ? round($cnt / $total * 100) : 0;
                        $isCorrect = $opt === $rs['jawaban_benar'];
                    @endphp
                    <div class="jawaban-row">
                        <div class="jawaban-opt-badge {{ $isCorrect ? 'correct' : '' }}">{{ $opt }}</div>
                        <div class="jawaban-bar-fill-wrap">
                            <div class="jawaban-bar-fill {{ $isCorrect ? 'correct' : '' }}"
                                 style="width:{{ $pct }}%"></div>
                        </div>
                        <div class="jawaban-count">{{ $cnt }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ══ TABEL PENILAIAN ═════════════════════════════════════════════════════ --}}
@if($tugas->isCbt())
    {{-- CBT: nilai otomatis, guru bisa lihat detail jawaban & beri feedback --}}
    <form action="{{ route('guru.tugas.simpan-nilai', $tugas) }}" method="POST" id="formPenilaian">
    @csrf

    <div class="content-card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <h3>
                <i class="fas fa-list-check" style="color:var(--purple);margin-right:8px;"></i>
                Hasil CBT Siswa
            </h3>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <span class="auto-badge"><i class="fas fa-wand-magic-sparkles"></i> Nilai dihitung otomatis</span>
                <select id="filterStatus" class="filter-select" style="font-size:12px;" onchange="filterTable()">
                    <option value="all">Semua Siswa</option>
                    <option value="kumpul">Sudah Mengerjakan</option>
                    <option value="belum">Belum Mengerjakan</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="tabelPenilaian">
                <thead>
                    <tr>
                        <th style="width:36px;">#</th>
                        <th>Nama Siswa</th>
                        <th style="text-align:center;width:130px;">Status</th>
                        <th style="width:130px;">Waktu Selesai</th>
                        <th style="text-align:center;width:90px;">Benar</th>
                        <th style="text-align:center;width:90px;">Nilai</th>
                        <th style="text-align:center;width:70px;">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaKelas as $i => $siswa)
                    @php
                        $kumpul    = $pengumpulan[$siswa->id] ?? null;
                        $sdKumpul  = $kumpul !== null;
                        $nilai     = $kumpul?->nilai;
                        $grade     = null;
                        $gradeColor= '#6b7280';
                        if ($nilai !== null) {
                            if ($nilai >= 90)      { $grade='A'; $gradeColor='#16a34a'; }
                            elseif ($nilai >= 80)  { $grade='B'; $gradeColor='#2563eb'; }
                            elseif ($nilai >= 70)  { $grade='C'; $gradeColor='#d97706'; }
                            elseif ($nilai >= 60)  { $grade='D'; $gradeColor='#dc2626'; }
                            else                   { $grade='E'; $gradeColor='#6b7280'; }
                        }
                        $statusBadge = match($kumpul?->status ?? 'belum') {
                            'tepat_waktu' => ['class'=>'badge-green',  'icon'=>'fa-check',        'label'=>'Tepat Waktu'],
                            'terlambat'   => ['class'=>'badge-orange', 'icon'=>'fa-clock',        'label'=>'Terlambat'],
                            default       => ['class'=>'badge-light',  'icon'=>'fa-minus-circle', 'label'=>'Belum Kerjakan'],
                        };
                        // Hitung jawaban benar dari data CBT (jika ada)
                        $jumlahSoal = $tugas->pertanyaans->count();
                        $jumlahBenar = $nilai !== null && $jumlahSoal > 0
                            ? round($nilai / 100 * $jumlahSoal)
                            : null;
                    @endphp
                    <tr class="cbt-row"
                        data-kumpul="{{ $sdKumpul ? '1':'0' }}"
                        data-nilai="{{ $nilai !== null ? '1':'0' }}">

                        <td style="color:var(--text-muted);font-size:12px;">{{ $i+1 }}</td>

                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="{{ $sdKumpul
                                    ? 'background:var(--purple-light);color:var(--purple);'
                                    : 'background:var(--bg);color:var(--text-muted);' }}">
                                    {{ strtoupper(substr($siswa->nama_lengkap ?? $siswa->nama ?? '?',0,2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;">{{ $siswa->nama_lengkap ?? $siswa->nama }}</div>
                                    @isset($siswa->nis)<div style="font-size:11px;color:var(--text-muted);">{{ $siswa->nis }}</div>@endisset
                                </div>
                            </div>
                        </td>

                        <td style="text-align:center;">
                            <span class="badge {{ $statusBadge['class'] }}">
                                <i class="fas {{ $statusBadge['icon'] }}" style="font-size:10px;margin-right:2px;"></i>
                                {{ $statusBadge['label'] }}
                            </span>
                        </td>

                        <td style="font-size:12px;color:var(--text-secondary);">
                            {{ $kumpul?->dikumpulkan_at?->format('d M Y, H:i') ?? '—' }}
                        </td>

                        <td style="text-align:center;font-size:13px;font-weight:700;color:var(--text-secondary);">
                            @if($jumlahBenar !== null)
                                <span style="color:var(--primary);">{{ $jumlahBenar }}</span>
                                <span style="color:var(--text-muted);">/{{ $jumlahSoal }}</span>
                            @else —
                            @endif
                        </td>

                        <td>
                            @if($nilai !== null)
                                <div class="nilai-cbt-display {{ strtolower($grade ?? '') }}">{{ $nilai }}</div>
                            @else
                                <div style="text-align:center;color:var(--text-muted);font-size:13px;">—</div>
                            @endif
                        </td>

                        <td style="text-align:center;">
                            @if($grade)
                                <span style="font-size:18px;font-weight:900;color:{{ $gradeColor }};">{{ $grade }}</span>
                            @else
                                <span style="color:var(--text-muted);">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer" style="justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <span style="font-size:12px;color:var(--text-muted);">
                <i class="fas fa-info-circle" style="margin-right:4px;"></i>
                Nilai CBT dihitung otomatis berdasarkan jawaban siswa.
            </span>
            <div style="display:flex;gap:10px;">
                <a href="{{ route('guru.tugas.index') }}" class="btn-primary-action">
                    Kembali
                </a>
            </div>
        </div>
    </div>
    </form>

@else
    {{-- UPLOAD: penilaian manual oleh guru --}}
    <form action="{{ route('guru.tugas.simpan-nilai', $tugas) }}" method="POST" id="formPenilaian">
    @csrf

    <div class="content-card">
        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <h3>
                <i class="fas fa-list-check" style="color:var(--primary);margin-right:8px;"></i>
                Daftar Pengumpulan Siswa
            </h3>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <select id="filterStatus" class="filter-select" style="font-size:12px;" onchange="filterTable()">
                    <option value="all">Semua Siswa</option>
                    <option value="kumpul">Sudah Kumpul</option>
                    <option value="belum">Belum Kumpul</option>
                    <option value="belum-nilai">Belum Dinilai</option>
                </select>
                <button type="button" class="btn btn-sm"
                        style="background:var(--bg);border:1px solid var(--border);"
                        onclick="toggleBulk()">
                    <i class="fas fa-wand-magic-sparkles"></i> Isi Seragam
                </button>
                <button type="submit" class="btn btn-primary btn-sm" id="btnSimpanTop">
                    <i class="fas fa-save"></i> Simpan Nilai
                </button>
            </div>
        </div>

        {{-- Panel bulk --}}
        <div id="panelBulk" style="display:none;">
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <span style="font-size:13px;font-weight:600;color:var(--primary);">
                    <i class="fas fa-wand-magic-sparkles"></i>
                    Isi nilai seragam untuk yang sudah kumpul:
                </span>
                <input type="number" id="inputBulk" min="0" max="100" step="0.5"
                       class="form-control" style="width:90px;text-align:center;" placeholder="0–100">
                <button type="button" class="btn btn-primary btn-sm" onclick="terapkanBulk()">Terapkan</button>
                <button type="button" class="btn btn-sm"
                        style="background:white;border:1px solid var(--border);"
                        onclick="toggleBulk()">Batal</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="data-table" id="tabelPenilaian">
                <thead>
                    <tr>
                        <th style="width:36px;">#</th>
                        <th>Nama Siswa</th>
                        <th style="text-align:center;width:130px;">Status</th>
                        <th style="width:130px;">Waktu Kumpul</th>
                        <th style="text-align:center;width:80px;">File</th>
                        <th style="width:150px;">Nilai <span style="color:var(--danger);font-size:10px;">0–100</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaKelas as $i => $siswa)
                    @php
                        $kumpul    = $pengumpulan[$siswa->id] ?? null;
                        $sdKumpul  = $kumpul !== null;
                        $sdNilai   = $sdKumpul && $kumpul->nilai !== null;
                        $statusBadge = match($kumpul?->status ?? 'belum') {
                            'tepat_waktu' => ['class'=>'badge-green',  'icon'=>'fa-check',        'label'=>'Tepat Waktu'],
                            'terlambat'   => ['class'=>'badge-orange', 'icon'=>'fa-clock',        'label'=>'Terlambat'],
                            default       => ['class'=>'badge-light',  'icon'=>'fa-minus-circle', 'label'=>'Belum Kumpul'],
                        };
                    @endphp
                    <tr data-kumpul="{{ $sdKumpul ? '1':'0' }}"
                        data-nilai="{{ $sdNilai ? '1':'0' }}"
                        style="{{ $sdNilai ? 'background:#f0fdf4;':'' }}">

                        <td style="color:var(--text-muted);font-size:12px;">{{ $i+1 }}</td>

                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="{{ $sdKumpul
                                    ? 'background:var(--primary-light);color:var(--primary);'
                                    : 'background:var(--bg);color:var(--text-muted);' }}">
                                    {{ strtoupper(substr($siswa->nama_lengkap ?? $siswa->nama ?? '?',0,2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:13px;">{{ $siswa->nama_lengkap ?? $siswa->nama }}</div>
                                    @isset($siswa->nis)<div style="font-size:11px;color:var(--text-muted);">{{ $siswa->nis }}</div>@endisset
                                </div>
                            </div>
                        </td>

                        <td style="text-align:center;">
                            <span class="badge {{ $statusBadge['class'] }}">
                                <i class="fas {{ $statusBadge['icon'] }}" style="font-size:10px;margin-right:2px;"></i>
                                {{ $statusBadge['label'] }}
                            </span>
                        </td>

                        <td style="font-size:12px;color:var(--text-secondary);">
                            {{ $kumpul?->dikumpulkan_at?->format('d M Y, H:i') ?? '—' }}
                        </td>

                        <td style="text-align:center;">
                            @if($kumpul?->file)
                                <a href="{{ asset('storage/'.$kumpul->file) }}" target="_blank"
                                   class="btn-view" title="Unduh file">
                                    <i class="fas fa-download"></i>
                                </a>
                            @elseif($kumpul?->catatan)
                                <span title="{{ $kumpul->catatan }}"
                                      style="cursor:help;color:var(--text-muted);font-size:12px;">
                                    <i class="fas fa-comment-dots"></i>
                                </span>
                            @else
                                <span style="color:var(--text-muted);font-size:12px;">—</span>
                            @endif
                        </td>

                        <td>
                            @if($sdKumpul)
                                <div style="position:relative;">
                                    <input type="number"
                                           name="nilai[{{ $siswa->id }}]"
                                           value="{{ old('nilai.'.$siswa->id, $kumpul->nilai) }}"
                                           min="0" max="100" step="0.5"
                                           class="form-control nilai-input"
                                           style="text-align:center;padding-right:30px;"
                                           oninput="updateGrade(this)">
                                    <span class="grade-badge"
                                          style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                                                 font-size:10px;font-weight:800;pointer-events:none;"></span>
                                </div>
                            @else
                                <span style="color:var(--text-muted);font-size:12px;display:block;text-align:center;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer" style="justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <span style="font-size:12px;color:var(--text-muted);">
                <i class="fas fa-info-circle" style="margin-right:4px;"></i>
                Nilai hanya bisa diisi untuk siswa yang sudah mengumpulkan tugas.
            </span>
            <div style="display:flex;gap:10px;">
                <a href="{{ route('guru.tugas.index') }}" class="btn btn-primary">
                    Kembali
                </a>
                <button type="submit" class="btn-primary-action" id="btnSimpan">
                    <i class="fas fa-save"></i> Simpan Semua Nilai
                </button>
            </div>
        </div>
    </div>
    </form>
@endif

</div>{{-- /penilaian-wrap --}}

@push('scripts')
<script>
// ── Grade helper (untuk tipe upload) ─────────────────────────────────────────
const GRADES = [
    { min:90, label:'A', color:'#16a34a' },
    { min:80, label:'B', color:'#2563eb' },
    { min:70, label:'C', color:'#d97706' },
    { min:60, label:'D', color:'#dc2626' },
    { min: 0, label:'E', color:'#6b7280' },
];

function getGrade(val) {
    if (val === '' || val === null || isNaN(val)) return null;
    return GRADES.find(g => parseFloat(val) >= g.min) || GRADES[GRADES.length-1];
}

function updateGrade(input) {
    const g    = getGrade(input.value);
    const span = input.parentElement.querySelector('.grade-badge');
    if (span) { span.textContent = g ? g.label:''; span.style.color = g ? g.color:''; }
    input.closest('tr').style.background = input.value ? '#f0fdf4' : '';
}

document.querySelectorAll('.nilai-input').forEach(updateGrade);

// ── Filter tabel ──────────────────────────────────────────────────────────────
function filterTable() {
    const f = document.getElementById('filterStatus').value;
    document.querySelectorAll('#tabelPenilaian tbody tr').forEach(row => {
        const k = row.dataset.kumpul === '1';
        const n = row.dataset.nilai  === '1';
        const show = f==='all' ? true
                   : f==='kumpul' ? k
                   : f==='belum'  ? !k
                   : f==='belum-nilai' ? (k && !n)
                   : true;
        row.style.display = show ? '' : 'none';
    });
}

// ── Bulk nilai (upload only) ──────────────────────────────────────────────────
function toggleBulk() {
    const el = document.getElementById('panelBulk');
    if (!el) return;
    const show = el.style.display === 'none';
    el.style.display = show ? 'block' : 'none';
    if (show) document.getElementById('inputBulk').focus();
}

function terapkanBulk() {
    const val = document.getElementById('inputBulk').value;
    if (!val || val < 0 || val > 100) return;
    document.querySelectorAll('.nilai-input').forEach(input => {
        if (!input.value) { input.value = val; updateGrade(input); }
    });
    toggleBulk();
}

// ── Loading state ─────────────────────────────────────────────────────────────
document.getElementById('formPenilaian')?.addEventListener('submit', () => {
    ['btnSimpan','btnSimpanTop'].forEach(id => {
        const b = document.getElementById(id);
        if (b) { b.disabled=true; b.innerHTML='<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; }
    });
});

// ── Export CSV ────────────────────────────────────────────────────────────────
function exportCSV() {
    const isCbt = {{ $tugas->isCbt() ? 'true':'false' }};
    const rows  = [isCbt
        ? ['No','Nama Siswa','NIS','Status','Waktu Selesai','Jawaban Benar','Nilai','Grade']
        : ['No','Nama Siswa','NIS','Status','Waktu Kumpul','Nilai','Grade']
    ];

    document.querySelectorAll('#tabelPenilaian tbody tr').forEach((row, i) => {
        const cells = row.querySelectorAll('td');
        if (!cells.length) return;
        const nama   = cells[1]?.querySelector('[style*="font-weight:600"]')?.textContent?.trim() ?? '';
        const nis    = cells[1]?.querySelector('[style*="font-size:11px"]')?.textContent?.trim() ?? '';
        const status = cells[2]?.textContent?.trim() ?? '';

        if (isCbt) {
            const waktu  = cells[3]?.textContent?.trim() ?? '';
            const benar  = cells[4]?.textContent?.trim() ?? '';
            const nilai  = cells[5]?.textContent?.trim() ?? '';
            const grade  = cells[6]?.textContent?.trim() ?? '';
            rows.push([i+1, nama, nis, status, waktu, benar, nilai, grade]);
        } else {
            const waktu    = cells[3]?.textContent?.trim() ?? '';
            const nilaiEl  = cells[5]?.querySelector('input');
            const nilai    = nilaiEl ? nilaiEl.value : (cells[5]?.textContent?.trim() ?? '');
            const grade    = cells[5]?.querySelector('.grade-badge')?.textContent?.trim() ?? '';
            rows.push([i+1, nama, nis, status, waktu, nilai, grade]);
        }
    });

    const csv  = rows.map(r => r.map(c => `"${String(c).replace(/"/g,'""')}"`).join(',')).join('\n');
    const blob = new Blob(['\uFEFF'+csv], { type:'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `penilaian_{{ Str::slug($tugas->judul) }}_{{ now()->format('Ymd') }}.csv`;
    a.click();
    URL.revokeObjectURL(url);
}
</script>
@endpush

@endsection