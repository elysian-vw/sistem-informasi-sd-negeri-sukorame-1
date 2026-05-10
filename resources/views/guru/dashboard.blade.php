@extends('layouts.guru')
@section('title', 'Dashboard Guru')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard Guru</h1>
        <p class="page-subtitle">Selamat datang, {{ auth()->user()->name }}! &nbsp;·&nbsp; {{ now()->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card stat-green">
        <div class="stat-icon"><i class="fas fa-user-check"></i></div>
        <div>
            <span class="stat-number">{{ $absensiHariIni['hadir'] ?? 0 }}</span>
            <span class="stat-label">Hadir Hari Ini</span>
        </div>
    </div>
    <div class="stat-card stat-orange">
        <div class="stat-icon"><i class="fas fa-user-times"></i></div>
        <div>
            <span class="stat-number">{{ $absensiHariIni['alpha'] ?? 0 }}</span>
            <span class="stat-label">Alpha Hari Ini</span>
        </div>
    </div>
    <div class="stat-card stat-blue">
        <div class="stat-icon"><i class="fas fa-file-pen"></i></div>
        <div>
            <span class="stat-number">{{ $tugasAktif->count() }}</span>
            <span class="stat-label">Tugas Aktif</span>
        </div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-icon"><i class="fas fa-book-open"></i></div>
        <div>
            <span class="stat-number">{{ $materiTerbaru->count() }}</span>
            <span class="stat-label">Total Materi</span>
        </div>
    </div>
</div>

{{-- ganti @if dengan selalu tampil --}}
<div class="content-card" style="margin-bottom:24px;">
    <div class="card-header">
        <h3><i class="fas fa-calendar-day" style="color:var(--primary);margin-right:8px;"></i>Jadwal Hari Ini</h3>
        <a href="{{ route('guru.jadwal.index') }}" style="font-size:12px;color:var(--primary);font-weight:600;">Lihat semua</a>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px;">Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Waktu</th>
                    <th>Ruangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwalHariIni as $j)
                <tr>
                    <td style="text-align:center;font-weight:700;">{{ $j->jam_ke }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $j->mataPelajaran->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $j->mataPelajaran->jenis ?? '' }}</div>
                    </td>
                    <td style="font-size:13px;white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }}
                    </td>
                    <td style="font-size:13px;">{{ $j->ruangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-row">
                        <i class="fas fa-calendar-check" style="display:block;font-size:20px;margin-bottom:6px;opacity:.4;"></i>
                        Tidak ada jadwal hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="content-grid" style="margin-bottom:24px;">
    {{-- Grafik Absensi --}}
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-chart-bar" style="color:var(--primary);margin-right:8px;"></i>Kehadiran 7 Hari Terakhir</h3>
            <span style="font-size:12px;color:var(--text-muted);">7 hari terakhir</span>
        </div>
        <div class="card-body">
            <canvas id="absensiChart" height="120"></canvas>
        </div>
    </div>

    {{-- Pengumuman --}}
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-bullhorn" style="color:var(--orange);margin-right:8px;"></i>Pengumuman</h3>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse ($pengumuman as $p)
            <div class="announcement-item" style="padding:12px 20px;">
                <div class="ann-icon"><i class="fas fa-megaphone"></i></div>
                <div>
                    <div class="ann-title">{{ $p->judul }}</div>
                    <div class="ann-meta">{{ $p->created_at->diffForHumans() }} &nbsp;·&nbsp; {{ $p->kategori ?? 'Umum' }}</div>
                </div>
            </div>
            @empty
            <div class="empty-state">Tidak ada pengumuman.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="content-grid">
    {{-- Tugas Aktif --}}
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-tasks" style="color:var(--primary);margin-right:8px;"></i>Tugas Aktif</h3>
            <a href="{{ route('guru.tugas.index') }}" style="font-size:12px;color:var(--primary);font-weight:600;">Lihat semua</a>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kelas</th>
                        <th>Terkumpul</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tugasAktif as $t)
                    <tr>
                        <td>
                            <a href="{{ route('guru.tugas.show', $t) }}" style="font-weight:600;color:var(--primary);">{{ $t->judul }}</a>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $t->mataPelajaran->nama ?? '-' }}</div>
                        </td>
                        <td>{{ $t->kelas->nama_kelas ?? '-' }}</td>
                        <td><span class="badge badge-blue">{{ $t->pengumpulan->count() }} siswa</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="empty-row">Belum ada tugas aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Diskusi Terbaru --}}
    <div class="content-card">
        <div class="card-header">
            <h3><i class="fas fa-comments" style="color:var(--secondary);margin-right:8px;"></i>Diskusi Terbaru</h3>
            <a href="{{ route('guru.forum.index') }}" style="font-size:12px;color:var(--primary);font-weight:600;">Lihat semua</a>
        </div>
        <div class="card-body" style="padding:0;">
            @forelse ($diskusiTerbaru as $d)
            <a href="{{ route('guru.forum.show', $d) }}" class="announcement-item" style="padding:12px 20px;display:flex;gap:12px;text-decoration:none;">
                <div class="ann-icon" style="background:#e6f4ea;color:var(--secondary);"><i class="fas fa-comments"></i></div>
                <div>
                    <div class="ann-title">{{ $d->judul }}</div>
                    <div class="ann-meta">{{ $d->user->name }} &nbsp;·&nbsp; {{ $d->komentar->count() }} komentar &nbsp;·&nbsp; {{ $d->created_at->diffForHumans() }}</div>
                </div>
            </a>
            @empty
            <div class="empty-state">Belum ada diskusi.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('absensiChart'), {
        type: 'bar',
        data: {
            labels: @json($absensiLabels),
            datasets: [{
                label: 'Hadir',
                data: @json($absensiData),
                backgroundColor: 'rgba(26,115,232,0.75)',
                borderRadius: 6,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
@endpush
