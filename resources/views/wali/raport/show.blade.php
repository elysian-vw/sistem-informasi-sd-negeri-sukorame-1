{{-- resources/views/wali/raport/show.blade.php --}}
@extends('layouts.wali')
@section('title', 'Detail Raport')

@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1 class="page-title">Detail Raport</h1>
        <p class="page-subtitle">{{ $raport->siswa->nama_lengkap ?? 'Siswa' }} — Semester {{ $raport->semester }} {{ $raport->tahun_ajaran }}</p>
    </div>
    
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('wali.raport.index') }}" class="btn" style="background:var(--bg); border:1px solid var(--border); text-decoration: none; padding: 10px 16px; border-radius: 8px; color: var(--text-primary); font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('wali.raport.ekspor', $raport->id) }}" class="btn" style="background: #f59e0b; color: white; border: none; text-decoration: none; padding: 10px 16px; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.2);">
            <i class="fas fa-file-pdf"></i> Unduh e-Raport
        </a>
    </div>
</div>

{{-- Info siswa --}}
<div class="content-card" style="margin-bottom:24px;">
    <div class="card-body" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
        <div><div style="font-size:12px;color:var(--text-muted);">Nama</div><div style="font-weight:700;">{{ $raport->siswa->nama_lengkap ?? '-' }}</div></div>
        <div><div style="font-size:12px;color:var(--text-muted);">NISN</div><div style="font-weight:700;">{{ $raport->siswa->nisn ?? '-' }}</div></div>
        <div><div style="font-size:12px;color:var(--text-muted);">Kelas</div><div style="font-weight:700;">{{ $raport->siswa->kelas->nama_kelas ?? '-' }}</div></div>
        <div><div style="font-size:12px;color:var(--text-muted);">Semester</div><div style="font-weight:700;">{{ strtoupper($raport->semester) }}</div></div>
        <div><div style="font-size:12px;color:var(--text-muted);">Tahun Ajaran</div><div style="font-weight:700;">{{ $raport->tahun_ajaran }}</div></div>
    </div>
</div>

{{-- Rekap Absensi --}}
<h3 style="font-size: 15px; font-weight: 700; margin-bottom: 15px; color: var(--text-primary);">Ketidakhadiran Semester {{ strtoupper($raport->semester) }}</h3>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    @php
        $abs = [
            ['lbl'=>'Sakit', 'val'=>$absensi['sakit'] ?? 0, 'icon'=>'fa-procedures', 'bg'=>'#fef3c7', 'text'=>'#d97706'],
            ['lbl'=>'Izin', 'val'=>$absensi['izin'] ?? 0, 'icon'=>'fa-envelope-open-text', 'bg'=>'#e0e7ff', 'text'=>'#4338ca'],
            ['lbl'=>'Alpha', 'val'=>$absensi['alpha'] ?? 0, 'icon'=>'fa-user-times', 'bg'=>'#fee2e2', 'text'=>'#dc2626'],
        ];
    @endphp
    @foreach($abs as $a)
    <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #f3f4f6; display: flex; align-items: center; gap: 16px;">
        <div style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: {{ $a['bg'] }}; color: {{ $a['text'] }};">
            <i class="fas {{ $a['icon'] }}"></i>
        </div>
        <div>
            <h4 style="margin: 0; font-size: 11px; color: var(--text-muted); text-transform: uppercase;">{{ $a['lbl'] }}</h4>
            <p style="margin: 0; font-size: 20px; font-weight: 800;">{{ $a['val'] }} <span style="font-size: 12px; font-weight: 500;">Hari</span></p>
        </div>
    </div>
    @endforeach
</div>

{{-- Tabel nilai --}}
<div class="content-card" style="margin-bottom:24px;">
    <div class="card-header">
        <h3><i class="fas fa-table" style="color:var(--primary);margin-right:8px;"></i>Nilai Mata Pelajaran</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th style="text-align:center;">Tugas</th>
                    <th style="text-align:center;">UTS</th>
                    <th style="text-align:center;">UAS</th>
                    <th style="text-align:center;">Nilai Akhir</th>
                    <th style="text-align:center;">KKM</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilai as $n)
                @php 
                    $kkm = $n->mataPelajaran->kkm ?? 75;
                    $lulus = $n->nilai_akhir >= $kkm;
                    // MENGAMBIL NAMA MAPEL DENGAN FALLBACK
                    $namaMapel = $n->mataPelajaran->nama_mapel ?? ($n->mataPelajaran->nama ?? 'Mapel Tanpa Nama');
                @endphp
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $namaMapel }}</td>
                    <td style="text-align:center;">{{ $n->nilai_tugas ?? '0' }}</td>
                    <td style="text-align:center;">{{ $n->nilai_uts ?? '0' }}</td>
                    <td style="text-align:center;">{{ $n->nilai_uas ?? '0' }}</td>
                    <td style="text-align:center;font-weight:800; color:var(--primary);">{{ $n->nilai_akhir ?? '0' }}</td>
                    <td style="text-align:center;">{{ $kkm }}</td>
                    <td style="text-align:center;">
                        <span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; {{ $lulus ? 'background:#dcfce7; color:#15803d;' : 'background:#fee2e2; color:#dc2626;' }}">
                            {{ $lulus ? 'Tuntas' : 'Belum Tuntas' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted);">Belum ada data nilai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($raport->catatan_wali_kelas)
<div class="content-card">
    <div class="card-header" style="background: #eff6ff; border-bottom: 1px solid #bfdbfe;">
        <h3 style="color: #1e3a8a;"><i class="fas fa-comment-dots" style="color: #3b82f6; margin-right:8px;"></i>Catatan Wali Kelas</h3>
    </div>
    <div class="card-body">
        <p style="color:var(--text-primary);line-height:1.7;margin:0; font-style: italic;">"{{ $raport->catatan_wali_kelas }}"</p>
    </div>
</div>
@endif
@endsection