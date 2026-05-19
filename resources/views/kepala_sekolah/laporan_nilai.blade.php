@extends('layouts.kepala_sekolah')
@section('title', 'Laporan Capaian Nilai Siswa')

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0; font-size: 24px; font-weight: 700;">Rekapitulasi Capaian Nilai</h1>
        <p class="page-subtitle" style="margin:0; color: var(--text-muted); font-size:14px;">Laporan rata-rata capaian nilai akademis siswa berdasarkan kelas.</p>
    </div>
</div>

{{-- ── WIDGET STATISTIK NILAI GLOBAL ── --}}
<div style="display:flex; gap:16px; margin-bottom:24px; flex-wrap:wrap;">
    <div class="stat-card stat-blue" style="flex:1; min-width:180px;">
        <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
        <div>
            <span class="stat-number">{{ $rataRataSekolah }}</span>
            <span class="stat-label">Rata-Rata Nilai Sekolah</span>
        </div>
    </div>
    <div class="stat-card stat-green" style="flex:1; min-width:180px;">
        <div class="stat-icon"><i class="fas fa-chalkboard"></i></div>
        <div>
            <span class="stat-number">{{ count($rekapNilai) }}</span>
            <span class="stat-label">Total Kelas Dipantau</span>
        </div>
    </div>
    <div class="stat-card stat-purple" style="flex:1; min-width:180px;">
        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
        <div>
            <span class="stat-number">{{ $totalEvaluasiSekolah }}</span>
            <span class="stat-label">Total Evaluasi / Tugas Aktif</span>
        </div>
    </div>
</div>

{{-- ── TABEL RANKING & REKAPITULASI NILAI KELAS ── --}}
<div class="content-card">
    <div class="card-header" style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin:0; font-size:15px; font-weight:700;">
            <i class="fas fa-chart-line" style="color:var(--primary); margin-right:8px;"></i>
            Data Rata-Rata Capaian per Kelas
        </h3>
        <span style="font-size: 12px; color: var(--text-muted);">Diurutkan dari nilai tertinggi</span>
    </div>
    <div class="card-body" style="padding:0;">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:1px solid var(--border);">
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; width:60px; text-align:center;">Peringkat</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600;">Nama Kelas</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center;">Total Siswa</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center;">Jumlah Evaluasi</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center;">Rata-Rata Kelas</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center;">Predikat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapNilai as $index => $rekap)
                    <tr style="border-bottom:1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        
                        {{-- Icon Piala untuk Top 3 --}}
                        <td style="padding:14px 20px; font-size:14px; text-align:center; font-weight:600;">
                            @if($index == 0)
                                <i class="fas fa-trophy" style="color: #fbbf24; font-size: 18px;"></i>
                            @elseif($index == 1)
                                <i class="fas fa-medal" style="color: #94a3b8; font-size: 18px;"></i>
                            @elseif($index == 2)
                                <i class="fas fa-medal" style="color: #b45309; font-size: 18px;"></i>
                            @else
                                <span style="color: var(--text-muted);">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        
                        <td style="padding:14px 20px; font-size:14px; font-weight:600;">Kelas {{ $rekap->nama_kelas }}</td>
                        <td style="padding:14px 20px; font-size:14px; text-align:center;">{{ $rekap->total_siswa }} Siswa</td>
                        <td style="padding:14px 20px; font-size:14px; text-align:center; color: var(--text-secondary);">{{ $rekap->total_tugas }} Tugas</td>
                        
                        <td style="padding:14px 20px; font-size:16px; text-align:center; font-weight:700; color: {{ $rekap->warna }};">
                            {{ $rekap->rata_rata }}
                        </td>
                        
                        <td style="padding:14px 20px; font-size:14px; text-align:center;">
                            <span style="background: {{ $rekap->bg }}; color: {{ $rekap->warna }}; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:700;">
                                {{ $rekap->predikat }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">
                            <i class="fas fa-folder-open" style="font-size:24px; display:block; margin-bottom:8px;"></i>
                            Tidak ada data kelas dan rekap nilai yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection