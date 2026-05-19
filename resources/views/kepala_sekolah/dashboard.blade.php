@extends('layouts.kepala_sekolah')
@section('title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Selamat Datang, Kepala Sekolah</h1>
        <p class="page-subtitle">Rangkuman data dan monitoring aktivitas akademis hari ini.</p>
    </div>
</div>

{{-- ── PANEL STATISTIK UTAMA ── --}}
<div style="display:flex; gap:16px; margin-bottom:24px; flex-wrap:wrap;">
    <div class="stat-card stat-blue" style="flex:1; min-width:200px;">
        <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
        <div>
            <span class="stat-number">{{ $totalGuru }}</span>
            <span class="stat-label">Total Guru Aktif</span>
        </div>
    </div>
    <div class="stat-card stat-green" style="flex:1; min-width:200px;">
        <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
        <div>
            <span class="stat-number">{{ $totalSiswa }}</span>
            <span class="stat-label">Total Siswa</span>
        </div>
    </div>
    <div class="stat-card stat-purple" style="flex:1; min-width:200px;">
        <div class="stat-icon"><i class="fas fa-school"></i></div>
        <div>
            <span class="stat-number">{{ $totalKelas }}</span>
            <span class="stat-label">Total Kelas</span>
        </div>
    </div>
    <div class="stat-card stat-orange" style="flex:1; min-width:200px;">
        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        <div>
            <span class="stat-number">{{ $persentaseHadir }}</span>
            <span class="stat-label">Kehadiran Hari Ini</span>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 400px; gap:20px; align-items:start;">
    
    {{-- ── KIRI: GRAFIK & MONITORING ── --}}
    <div>
        {{-- PANEL GRAFIK ABSENSI - MENIRU STRUKTUR & FILTER ADMIN --}}
        <div class="content-card" style="margin-bottom: 24px;">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #f3f4f6;">
                <div>
                    <h3 style="margin:0; font-size:14px; font-weight:600;"><i class="fas fa-chart-bar" style="color:var(--primary); margin-right:8px;"></i>Grafik Absensi Siswa</h3>
                    <span style="font-size: 12px; color: #9ca3af;">Data kehadiran harian</span>
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
                        @foreach($kelasBaris as $k)
                            <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body" style="padding: 20px;">
                <div style="height: 300px;">
                    <canvas id="absensiChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Aktivitas Pembelajaran Terakhir --}}
        <div class="content-card">
            <div class="card-header" style="padding: 16px 20px;">
                <h3 style="margin:0; font-size:14px; font-weight:600;"><i class="fas fa-chalkboard-user" style="color:var(--primary); margin-right:8px;"></i>Aktivitas Pembelajaran Terakhir</h3>
            </div>
            <div class="card-body" style="padding:0;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:1px solid var(--border);">
                            <th style="padding:12px 20px; font-size:12px; color: #6b7280; font-weight:600; text-transform:uppercase;">Nama Guru</th>
                            <th style="padding:12px 20px; font-size:12px; color: #6b7280; font-weight:600; text-transform:uppercase;">Mata Pelajaran</th>
                            <th style="padding:12px 20px; font-size:12px; color: #6b7280; font-weight:600; text-transform:uppercase;">Kelas</th>
                            <th style="padding:12px 20px; font-size:12px; color: #6b7280; font-weight:600; text-transform:uppercase;">Aktivitas</th>
                            <th style="padding:12px 20px; font-size:12px; color: #6b7280; font-weight:600; text-transform:uppercase;">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logAktivitas as $log)
                            <tr style="border-bottom:1px solid var(--border);">
                                <td style="padding:14px 20px; font-size:14px; font-weight:600;">{{ $log->guru->name }}</td>
                                <td style="padding:14px 20px; font-size:14px;">{{ $log->mapel }}</td>
                                <td style="padding:14px 20px; font-size:14px;">
                                    <span style="background:#e0f2fe; color:#0369a1; padding:4px 8px; border-radius:4px; font-size:12px; font-weight:600;">
                                        Kelas {{ $log->kelas }}
                                    </span>
                                </td>
                                <td style="padding:14px 20px; font-size:13px; color:var(--text-secondary);">{{ $log->keterangan }}</td>
                                <td style="padding:14px 20px; font-size:12px; color:var(--text-muted);">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:40px; color:var(--text-muted);">
                                    <i class="fas fa-chart-line" style="font-size:28px; display:block; margin-bottom:8px; opacity:.5;"></i>
                                    Belum ada aktivitas pembelajaran baru hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── KANAN: RANGKUMAN REKAPITULASI CEPAT ── --}}
    <div style="display:flex; flex-direction:column; gap:20px;">
        <div class="content-card">
            <div class="card-header" style="padding: 16px 20px;">
                <h3 style="margin:0; font-size:14px; font-weight:600;"><i class="fas fa-bullhorn" style="color:var(--orange); margin-right:8px;"></i>Pengumuman Sekolah</h3>
            </div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:12px; padding: 16px 20px;">
                <div style="border-left:3px solid var(--primary); padding-left:12px;">
                    <div style="font-weight:600; font-size:14px;">Rapat Evaluasi Semester Genap</div>
                    <small style="color:var(--text-muted); font-size:11px;">Diposting oleh Admin · Hari ini</small>
                </div>
                <div style="border-left:3px solid var(--border); padding-left:12px;">
                    <div style="font-weight:600; font-size:14px; color:var(--text-secondary);">Persiapan Akreditasi Sekolah 2026</div>
                    <small style="color:var(--text-muted); font-size:11px;">Diposting oleh Admin · 2 hari lalu</small>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header" style="padding: 16px 20px;">
                <h3 style="margin:0; font-size:14px; font-weight:600;"><i class="fas fa-folder-open" style="color:var(--purple); margin-right:8px;"></i>Laporan & Rekapitulasi</h3>
            </div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:10px; padding: 16px 20px;">
                <a href="{{ route('kepala_sekolah.laporan.kehadiran') }}" style="text-align:left; background:#f8fafc; border:1px solid var(--border); color:var(--text-main); display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-radius:8px; text-decoration:none;">
                    <span><i class="fas fa-file-invoice" style="margin-right:8px; color:var(--blue);"></i> Rekap Kehadiran Bulanan</span>
                    <i class="fas fa-chevron-right" style="font-size:12px; color:var(--text-muted);"></i>
                </a>
                <a href="{{ route('kepala_sekolah.laporan.nilai') }}" style="text-align:left; background:#f8fafc; border:1px solid var(--border); color:var(--text-main); display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-radius:8px; text-decoration:none;">
                    <span><i class="fas fa-graduation-cap" style="margin-right:8px; color:var(--green);"></i> Laporan Capaian Nilai Siswa</span>
                    <i class="fas fa-chevron-right" style="font-size:12px; color:var(--text-muted);"></i>
                </a>
            </div>
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
</style>
@endsection

@push('scripts')
{{-- MEMAKAI LIBRARY SAMA PERSIS SEPERTI MILIK ADMIN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
let absensiChart; 

async function loadChart() {
    const status = document.getElementById('filterStatus').value;
    const periode = document.getElementById('filterPeriode').value;
    const kelas = document.getElementById('filterKelas').value;
    
    // Request AJAX mengarah ke route Kepala Sekolah dengan query parameter
    const response = await fetch(`{{ route('kepala_sekolah.chart-data') }}?status=${status}&periode=${periode}&kelas_id=${kelas}`);
    const data = await response.json();

    const ctx = document.getElementById('absensiChart').getContext('2d');
    
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

// Event listener ganti data otomatis pas dropdown diubah
document.getElementById('filterStatus').addEventListener('change', loadChart);
document.getElementById('filterPeriode').addEventListener('change', loadChart);
document.getElementById('filterKelas').addEventListener('change', loadChart);

// Jalankan pertama kali pas halaman dibuka
document.addEventListener('DOMContentLoaded', loadChart);
</script>
@endpush