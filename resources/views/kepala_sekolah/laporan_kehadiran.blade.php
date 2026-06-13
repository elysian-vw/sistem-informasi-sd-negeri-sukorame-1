@extends('layouts.kepala_sekolah')
@section('title', 'Laporan Kehadiran Bulanan')

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin:0; font-size: 24px; font-weight: 700;">Rekap Kehadiran Bulanan</h1>
        <p class="page-subtitle" style="margin:0; color: var(--text-muted); font-size:14px;">Laporan tingkat kehadiran kedisiplinan siswa per kelas.</p>
    </div>

    <form method="GET" action="{{ route('kepala_sekolah.laporan.kehadiran') }}" style="display: flex; gap: 10px; background: #fff; padding: 10px 16px; border-radius: 8px; border: 1px solid var(--border);">
        <select name="bulan" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 13px; outline:none; cursor:pointer;">
            @foreach($namaBulan as $key => $bulan)
                <option value="{{ $key }}" {{ $bulanDipilih == $key ? 'selected' : '' }}>{{ $bulan }}</option>
            @endforeach
        </select>

        <select name="tahun" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 13px; outline:none; cursor:pointer;">
            @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                <option value="{{ $y }}" {{ $tahunDipilih == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>
</div>

{{-- WIDGET RINGKASAN GLOBAL SEKOLAH --}}
<div style="display:flex; gap:16px; margin-bottom:24px; flex-wrap:wrap;">
    <div class="stat-card stat-blue" style="flex:1; min-width:180px;">
        <div class="stat-icon"><i class="fas fa-percentage"></i></div>
        <div>
            <span class="stat-number">{{ $rataRataHadir }}</span>
            <span class="stat-label">Rata-Rata Hadir</span>
        </div>
    </div>
    <div class="stat-card stat-green" style="flex:1; min-width:180px;">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div>
            <span class="stat-number">{{ $globalHadir }}</span>
            <span class="stat-label">Total Hadir (Log)</span>
        </div>
    </div>
    <div class="stat-card stat-purple" style="flex:1; min-width:180px;">
        <div class="stat-icon"><i class="fas fa-info-circle"></i></div>
        <div>
            <span class="stat-number">{{ $globalIzin + $globalSakit }}</span>
            <span class="stat-label">Total Izin & Sakit</span>
        </div>
    </div>
    <div class="stat-card stat-orange" style="flex:1; min-width:180px;">
        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <span class="stat-number">{{ $globalAlpha }}</span>
            <span class="stat-label">Total Alfa</span>
        </div>
    </div>
</div>

{{-- TABEL DATA UTAMA PER KELAS --}}
<div class="content-card">
    <div class="card-header" style="padding: 16px 20px; border-bottom: 1px solid var(--border);">
        <h3 style="margin:0; font-size:15px; font-weight:700;">
            <i class="fas fa-table" style="color:var(--primary); margin-right:8px;"></i>
            Data Persentase Bulanan: {{ $namaBulan[$bulanDipilih] ?? '' }} {{ $tahunDipilih }}
        </h3>
    </div>
    <div class="card-body" style="padding:0;">
        <table style="width:100%; border-collapse:collapse; text-align:left;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:1px solid var(--border);">
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; width:60px; text-align:center;">No</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600;">Nama Kelas</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center;">Total Siswa</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center; color:#22c55e;">Hadir</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center; color:#3b82f6;">Izin</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center; color:#a855f7;">Sakit</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center; color:#ef4444;">Alfa</th>
                    <th style="padding:14px 20px; font-size:13px; color:var(--text-muted); font-weight:600; text-align:center;">Rasio Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapBulanan as $index => $rekap)
                    <tr style="border-bottom:1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px; font-size:14px; text-align:center; color:var(--text-muted);">{{ $index + 1 }}</td>
                        <td style="padding:14px 20px; font-size:14px; font-weight:600;">Kelas {{ $rekap->nama_kelas }}</td>
                        <td style="padding:14px 20px; font-size:14px; text-align:center;">{{ $rekap->total_siswa }} Anak</td>
                        <td style="padding:14px 20px; font-size:14px; text-align:center; font-weight:500;">{{ $rekap->hadir }}</td>
                        <td style="padding:14px 20px; font-size:14px; text-align:center; font-weight:500;">{{ $rekap->izin }}</td>
                        <td style="padding:14px 20px; font-size:14px; text-align:center; font-weight:500;">{{ $rekap->sakit }}</td>
                        <td style="padding:14px 20px; font-size:14px; text-align:center; font-weight:500; color:{{ $rekap->alpha > 0 ? '#ef4444' : 'inherit' }}">{{ $rekap->alpha }}</td>
                        <td style="padding:14px 20px; font-size:14px; text-align:center;">
                            <span style="background:{{ intval($rekap->persentase) >= 90 ? '#dcfce7' : '#ffedd5' }}; color:{{ intval($rekap->persentase) >= 90 ? '#15803d' : '#c2410c' }}; padding:4px 10px; border-radius:6px; font-size:13px; font-weight:700;">
                                {{ $rekap->persentase }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);">
                            <i class="fas fa-folder-open" style="font-size:24px; display:block; margin-bottom:8px;"></i>
                            Tidak ada rekapitulasi data absensi ditemukan untuk bulan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection