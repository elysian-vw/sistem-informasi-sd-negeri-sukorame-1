@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard Admin</h1>
    <p class="page-subtitle">Selamat datang, {{ auth()->user()->name }}!</p>
</div>

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card stat-blue">
        <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
        <div class="stat-info">
            <span class="stat-number">{{ $data['total_siswa'] }}</span>
            <span class="stat-label">Total Siswa</span>
        </div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="stat-info">
            <span class="stat-number">{{ $data['total_guru'] }}</span>
            <span class="stat-label">Total Guru</span>
        </div>
    </div>
    <div class="stat-card stat-orange">
        <div class="stat-icon"><i class="fas fa-door-open"></i></div>
        <div class="stat-info">
            <span class="stat-number">{{ $data['total_kelas'] }}</span>
            <span class="stat-label">Total Kelas</span>
        </div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
        <div class="stat-info">
            <span class="stat-number">{{ $data['siswa_belum_aktif'] }}</span>
            <span class="stat-label">Siswa Belum Aktif</span>
        </div>
    </div>
</div>

{{-- Row 1: Grafik + Pengumuman --}}
<div class="dashboard-grid">
    <div class="content-card">
        <div class="card-header">
            <div>
                <h3>Grafik Absensi Siswa</h3>
                <span class="card-subtitle">Data kehadiran harian</span>
            </div>
            <div class="filter-container" style="display: flex; gap: 8px;">
                <select id="filterStatus" class="filter-select">
                    <option value="hadir" selected>Hadir</option>
                    <option value="sakit">Sakit</option>
                    <option value="izin">Izin</option>
                    <option value="alpha">Alpha</option>
                </select>
                <select id="filterPeriode" class="filter-select">
                    <option value="hari">Hari Ini</option>
                    <option value="minggu" selected>7 Hari Terakhir</option>
                    <option value="bulan">30 Hari Terakhir</option>
                    <option value="semester">6 Bulan Terakhir</option>
                </select>
                <select id="filterKelas" class="filter-select">
                    <option value="">Semua Kelas</option>
                    @foreach(range(1, 6) as $k)
                        <option value="{{ $k }}">Kelas {{ $k }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            <div style="height: 300px;"> {{-- Kasih tinggi fixed biar nggak lari --}}
                <canvas id="absensiChart"></canvas>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3>Pengumuman Terbaru</h3>
            <a href="{{ route('admin.pengumuman.index') }}" class="card-link">Lihat semua</a>
        </div>
        <div class="card-body">
            @forelse($data['pengumuman_terbaru'] as $i => $p)
                <div class="list-item">
                    <span class="list-number">{{ $i + 1 }}</span>
                    <div class="list-content">
                        <p class="list-title">{{ $p->judul }}</p>
                        <span class="list-meta">{{ $p->created_at->diffForHumans() }} · {{ $p->untuk }}</span>
                    </div>
                </div>
            @empty
                <p class="empty-state">Belum ada pengumuman.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Row 2: Siswa Terbaru + Aktivitas --}}
<div class="dashboard-grid">
    <div class="content-card">
        <div class="card-header">
            <h3>Siswa Ditambahkan</h3>
            <a href="{{ route('admin.siswa.index') }}" class="card-link">Lihat semua</a>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['siswa_terbaru'] as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div class="user-mini">
                                    <div class="avatar-xs">{{ strtoupper(substr($s->nama_lengkap, 0, 1)) }}</div>
                                    {{ $s->nama_lengkap }}
                                </div>
                            </td>
                            <td>{{ $s->kelas?->nama_kelas ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $s->user_id ? 'badge-success' : 'badge-warning' }}">
                                    {{ $s->user_id ? 'Aktif' : 'Belum Aktif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">Belum ada siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3>Aktivitas Terbaru</h3>
        </div>
        <div class="card-body">
            @forelse($data['aktivitas'] as $i => $a)
                <div class="list-item">
                    <span class="list-number">{{ $i + 1 }}</span>
                    <div class="list-content">
                        <p class="list-title">{{ $a['pesan'] }}</p>
                        <span class="list-meta">{{ $a['waktu'] }}</span>
                    </div>
                </div>
            @empty
                <p class="empty-state">Belum ada aktivitas.</p>
            @endforelse
        </div>
    </div>
</div>

<style>
.filter-select {
    padding: 5px 10px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    font-size: 12px;
    outline: none;
    cursor: pointer;
}
.filter-select:focus { border-color: #2563eb; }

.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    margin-bottom: 20px;
}
@media(max-width: 900px) { .dashboard-grid { grid-template-columns: 1fr; } }

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
}
.card-header h3 { font-size: 14px; font-weight: 600; color: #111827; margin: 0; }
.card-subtitle { font-size: 12px; color: #9ca3af; }
.card-link { font-size: 12.5px; color: #2563eb; text-decoration: none; font-weight: 500; }
.card-link:hover { text-decoration: underline; }
.card-body { padding: 16px 20px; }

/* List items (pengumuman & aktivitas) */
.list-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
}
.list-item:last-child { border-bottom: none; }
.list-number {
    width: 22px; height: 22px; border-radius: 50%;
    background: #eff6ff; color: #2563eb;
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
}
.list-content { flex: 1; }
.list-title { font-size: 13.5px; font-weight: 500; color: #111827; margin: 0 0 2px; }
.list-meta { font-size: 12px; color: #9ca3af; }

/* Table */
.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.data-table th { background: #f9fafb; padding: 10px 16px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
.data-table td { padding: 11px 16px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; color: #374151; }
.data-table tbody tr:hover { background: #f9fafb; }

.user-mini { display: flex; align-items: center; gap: 8px; }
.avatar-xs {
    width: 26px; height: 26px; border-radius: 50%;
    background: #dbeafe; color: #1d4ed8;
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}

.badge { display: inline-flex; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 500; }
.badge-success { background: #dcfce7; color: #15803d; }
.badge-warning { background: #fef9c3; color: #854d0e; }
.empty-state { text-align: center; color: #9ca3af; padding: 24px; font-size: 13.5px; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Pake satu nama variabel aja yang konsisten!
let absensiChart; 

async function loadChart() {
    const status = document.getElementById('filterStatus').value;
    const periode = document.getElementById('filterPeriode').value;
    const kelas = document.getElementById('filterKelas').value;
    
    // Kirim status ke Controller
    const response = await fetch(`{{ route('admin.chart-data') }}?status=${status}&periode=${periode}&kelas_id=${kelas}`);
    const data = await response.json();

    const ctx = document.getElementById('absensiChart').getContext('2d');
    
    // Hancurkan chart lama biar nggak tumpang tindih
    if (absensiChart) { absensiChart.destroy(); }

    const colors = {
        hadir: '#3b82f6',
        sakit: '#fbbf24',
        izin:  '#10b981',
        alpha: '#ef4444'
    };

    absensiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: `Jumlah Siswa ${status.charAt(0).toUpperCase() + status.slice(1)}`,
                data: data.values,
                backgroundColor: colors[status] || '#3b82f6',
                borderRadius: 6,
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1 },
                    title: { display: true, text: 'Jumlah Siswa' } 
                },
                x: {
                    title: { display: true, text: 'Tanggal' }
                }
            }
        }
    });
}

// Pastikan manggil fungsi yang ADA (loadChart)
document.getElementById('filterStatus').addEventListener('change', loadChart);
document.getElementById('filterPeriode').addEventListener('change', loadChart);
document.getElementById('filterKelas').addEventListener('change', loadChart);

// Jalankan pertama kali
document.addEventListener('DOMContentLoaded', loadChart);
</script>
@endsection