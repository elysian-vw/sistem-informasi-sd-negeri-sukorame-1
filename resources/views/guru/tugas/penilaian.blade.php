@extends('layouts.guru')
@section('title', 'Penilaian — ' . $tugas->judul)

@section('content')

{{-- ── HEADER ── --}}
<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('guru.tugas.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-title">Penilaian Tugas</h1>
            <p class="page-subtitle">
                <a href="{{ route('guru.tugas.index') }}">Tugas</a>
                &nbsp;/&nbsp; {{ Str::limit($tugas->judul, 50) }}
                &nbsp;·&nbsp; {{ $tugas->kelas->nama_kelas ?? '-' }}
            </p>
        </div>
    </div>
    @if($tugas->file)
        <a href="{{ asset('storage/' . $tugas->file) }}" target="_blank" class="btn-secondary-action">
            <i class="fas fa-paperclip"></i> Lampiran Tugas
        </a>
    @endif
</div>

@if(session('success'))
    <div class="alert-success-toast">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- ── STAT CARDS ── --}}
@php
    $totalSiswa   = $siswaKelas->count();
    $sudahKumpul  = $pengumpulan->count();
    $belumKumpul  = $totalSiswa - $sudahKumpul;
    $sudahDinilai = $pengumpulan->whereNotNull('nilai')->count();
    $pctKumpul    = $totalSiswa > 0 ? ($sudahKumpul / $totalSiswa) * 100 : 0;
@endphp

<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px;">
    <div class="stat-card stat-blue">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div>
            <span class="stat-number">{{ $totalSiswa }}</span>
            <span class="stat-label">Total Siswa</span>
        </div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div>
            <span class="stat-number">{{ $sudahKumpul }}</span>
            <span class="stat-label">Sudah Kumpul</span>
        </div>
    </div>
    <div class="stat-card stat-orange">
        <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <span class="stat-number">{{ $belumKumpul }}</span>
            <span class="stat-label">Belum Kumpul</span>
        </div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-icon"><i class="fas fa-star"></i></div>
        <div>
            <span class="stat-number">{{ $sudahDinilai }}</span>
            <span class="stat-label">Sudah Dinilai</span>
        </div>
    </div>
</div>

{{-- Progress bar --}}
<div class="content-card" style="margin-bottom:20px;">
    <div class="card-body" style="padding:14px 20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
            <span style="font-size:12px;font-weight:600;color:var(--text-secondary);">
                <i class="fas fa-chart-line" style="margin-right:4px;color:var(--primary);"></i>
                Progress Pengumpulan
            </span>
            <span style="font-size:12px;font-weight:700;color:var(--primary);">
                {{ $sudahKumpul }} / {{ $totalSiswa }} siswa &nbsp;({{ round($pctKumpul) }}%)
            </span>
        </div>
        <div style="background:var(--bg);border-radius:100px;height:8px;overflow:hidden;">
            <div style="width:{{ $pctKumpul }}%;background:linear-gradient(90deg,var(--primary),var(--secondary));height:100%;border-radius:100px;transition:width .4s;"></div>
        </div>
        @if($tugas->deadline)
        <div style="font-size:11px;margin-top:6px;color:{{ now()->gt($tugas->deadline) ? 'var(--danger)' : 'var(--text-muted)' }};">
            <i class="fas fa-clock" style="margin-right:3px;"></i>
            Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}
            @if(now()->gt($tugas->deadline))
                &nbsp;<strong>· Sudah lewat</strong>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- ── FORM PENILAIAN ── --}}
<form action="{{ route('guru.tugas.simpan-nilai', $tugas) }}" method="POST" id="formPenilaian">
@csrf

<div class="content-card">

    {{-- Card Header --}}
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
            <button type="button" class="btn btn-sm" style="background:var(--bg);border:1px solid var(--border);"
                    onclick="toggleBulk()">
                <i class="fas fa-wand-magic-sparkles"></i> Isi Seragam
            </button>
            <button type="submit" class="btn btn-primary btn-sm" id="btnSimpanTop">
                <i class="fas fa-save"></i> Simpan Nilai
            </button>
        </div>
    </div>

    {{-- Panel bulk --}}
    <div id="panelBulk" style="display:none;padding:12px 20px;background:var(--primary-light);border-bottom:1px solid var(--border);">
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <span style="font-size:13px;font-weight:600;color:var(--primary);">
                <i class="fas fa-wand-magic-sparkles"></i>
                Isi nilai seragam untuk yang sudah kumpul:
            </span>
            <input type="number" id="inputBulk" min="0" max="100" step="0.5"
                   class="form-control" style="width:90px;text-align:center;" placeholder="0–100">
            <button type="button" class="btn btn-primary btn-sm" onclick="terapkanBulk()">Terapkan</button>
            <button type="button" class="btn btn-sm" style="background:white;border:1px solid var(--border);"
                    onclick="toggleBulk()">Batal</button>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="data-table" id="tabelPenilaian">
            <thead>
                <tr>
                    <th style="width:36px;">#</th>
                    <th>Nama Siswa</th>
                    <th style="text-align:center;width:120px;">Status</th>
                    <th style="width:130px;">Waktu Kumpul</th>
                    <th style="text-align:center;width:80px;">File</th>
                    <th style="width:140px;">Nilai <span style="color:var(--danger);font-size:10px;">0–100</span></th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswaKelas as $i => $siswa)
                @php
                    $kumpul      = $pengumpulan[$siswa->id] ?? null;
                    $sudahKumpul = $kumpul !== null;
                    $sudahNilai  = $sudahKumpul && $kumpul->nilai !== null;

                    $statusBadge = match($kumpul?->status ?? 'belum') {
                        'tepat_waktu' => ['class' => 'badge-green',  'icon' => 'fa-check',        'label' => 'Tepat Waktu'],
                        'terlambat'   => ['class' => 'badge-orange', 'icon' => 'fa-clock',        'label' => 'Terlambat'],
                        default       => ['class' => 'badge-light',  'icon' => 'fa-minus-circle', 'label' => 'Belum Kumpul'],
                    };
                @endphp
                <tr data-kumpul="{{ $sudahKumpul ? '1' : '0' }}"
                    data-nilai="{{ $sudahNilai ? '1' : '0' }}"
                    style="{{ $sudahNilai ? 'background:#f0fdf4;' : '' }}">

                    <td style="color:var(--text-muted);font-size:12px;">{{ $i + 1 }}</td>

                    <td>
                        <div class="user-cell">
                            <div class="avatar"
                                 style="{{ $sudahKumpul
                                     ? 'background:var(--primary-light);color:var(--primary);'
                                     : 'background:var(--bg);color:var(--text-muted);' }}">
                                {{ strtoupper(substr($siswa->nama_lengkap ?? $siswa->nama ?? '?', 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px;">
                                    {{ $siswa->nama_lengkap ?? $siswa->nama }}
                                </div>
                                @isset($siswa->nis)
                                    <div style="font-size:11px;color:var(--text-muted);">{{ $siswa->nis }}</div>
                                @endisset
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
                            <a href="{{ asset('storage/' . $kumpul->file) }}" target="_blank"
                               class="btn-view" title="Unduh file">
                                <i class="fas fa-download"></i>
                            </a>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>

                    <td>
                        @if($sudahKumpul)
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

                    <td>
                        @if($sudahKumpul)
                            <input type="text"
                                   name="feedback[{{ $siswa->id }}]"
                                   value="{{ old('feedback.'.$siswa->id, $kumpul->feedback) }}"
                                   class="form-control"
                                   style="font-size:12px;min-width:130px;"
                                   placeholder="Komentar singkat...">
                        @else
                            <span style="color:var(--text-muted);font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="card-footer" style="justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <span style="font-size:12px;color:var(--text-muted);">
            <i class="fas fa-info-circle" style="margin-right:4px;"></i>
            Nilai hanya bisa diisi untuk siswa yang sudah mengumpulkan tugas.
        </span>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('guru.tugas.index') }}" class="btn-secondary-action">Kembali</a>
            <button type="submit" class="btn-primary-action" id="btnSimpan">
                <i class="fas fa-save"></i> Simpan Semua Nilai
            </button>
        </div>
    </div>

</div>
</form>

@push('scripts')
<script>
/* ─── Grade ─────────────────────────────────────── */
const GRADES = [
    { min: 90, label: 'A', color: '#15803d' },
    { min: 80, label: 'B', color: '#1d4ed8' },
    { min: 70, label: 'C', color: '#b45309' },
    { min: 60, label: 'D', color: '#dc2626' },
    { min:  0, label: 'E', color: '#6b7280' },
];

function getGrade(val) {
    if (val === '' || val === null || isNaN(val)) return null;
    const v = parseFloat(val);
    return GRADES.find(g => v >= g.min) || GRADES[GRADES.length - 1];
}

function updateGrade(input) {
    const g    = getGrade(input.value);
    const span = input.parentElement.querySelector('.grade-badge');
    if (span) {
        span.textContent = g ? g.label : '';
        span.style.color = g ? g.color : '';
    }
    input.closest('tr').style.background = input.value ? '#f0fdf4' : '';
}

/* Init semua saat halaman load */
document.querySelectorAll('.nilai-input').forEach(updateGrade);

/* ─── Filter ─────────────────────────────────────── */
function filterTable() {
    const f = document.getElementById('filterStatus').value;
    document.querySelectorAll('#tabelPenilaian tbody tr').forEach(row => {
        const k = row.dataset.kumpul === '1';
        const n = row.dataset.nilai  === '1';
        const show = f === 'all'        ? true
                   : f === 'kumpul'     ? k
                   : f === 'belum'      ? !k
                   : f === 'belum-nilai'? (k && !n)
                   : true;
        row.style.display = show ? '' : 'none';
    });
}

/* ─── Bulk ───────────────────────────────────────── */
function toggleBulk() {
    const el = document.getElementById('panelBulk');
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

/* ─── Loading state ──────────────────────────────── */
document.getElementById('formPenilaian').addEventListener('submit', () => {
    ['btnSimpan', 'btnSimpanTop'].forEach(id => {
        const b = document.getElementById(id);
        if (b) { b.disabled = true; b.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'; }
    });
});
</script>
@endpush

@endsection