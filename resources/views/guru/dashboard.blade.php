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
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3><i class="fas fa-chart-bar" style="color:var(--primary);margin-right:8px;"></i>Grafik Kehadiran</h3>
            <div class="filter-group" style="display:flex; gap:8px;">
                <select id="filterStatus" class="filter-select">
                    <option value="hadir" selected>Hadir</option>
                    <option value="sakit">Sakit</option>
                    <option value="izin">Izin</option>
                    <option value="alpha">Alpha</option>
                </select>
                <select id="filterPeriode" class="filter-select">
                    <option value="minggu" selected>7 Hari</option>
                    <option value="bulan">30 Hari</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div style="height: 250px;">
                <canvas id="absensiChart"></canvas>
            </div>
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

<style>
.badge-blue { background: #e0f2fe; color: #0369a1; }

.filter-select {
    padding: 4px 8px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-size: 12px;
    outline: none;
    cursor: pointer;
    background: #fff;
}
.filter-select:focus { border-color: var(--primary); }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let absensiChart;

    async function updateChart() {
        const status = document.getElementById('filterStatus').value;
        const periode = document.getElementById('filterPeriode').value;

        const response = await fetch(`{{ route('guru.chart-data') }}?status=${status}&periode=${periode}`);
        const data = await response.json();

        const ctx = document.getElementById('absensiChart').getContext('2d');

        if (absensiChart) { absensiChart.destroy(); }

        const colors = {
            hadir: 'rgba(34, 197, 94, 0.75)',
            sakit: 'rgba(234, 179, 8, 0.75)',
            izin:  'rgba(59, 130, 246, 0.75)',
            alpha: 'rgba(239, 68, 68, 0.75)'
        };

        absensiChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: `Jumlah Siswa ${status.charAt(0).toUpperCase() + status.slice(1)}`,
                    data: data.values,
                    backgroundColor: colors[status] || 'rgba(26,115,232,0.75)',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1 },
                        title: { display: true, text: 'Jumlah Siswa', font: { size: 10 } }
                    } 
                }
            }
        });
    }

    document.getElementById('filterStatus').addEventListener('change', updateChart);
    document.getElementById('filterPeriode').addEventListener('change', updateChart);

    document.addEventListener('DOMContentLoaded', updateChart);
</script>
@endpush

