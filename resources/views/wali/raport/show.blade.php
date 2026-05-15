{{-- resources/views/wali/raport/show.blade.php --}}
@extends('layouts.wali')
@section('title', 'Detail Raport')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Detail Raport</h1>
        <p class="page-subtitle">{{ $raport->siswa->nama_lengkap }} — Semester {{ $raport->semester }} {{ $raport->tahun_ajaran }}</p>
    </div>
    <a href="{{ route('wali.raport.index') }}" class="btn" style="background:var(--bg);border:1px solid var(--border);">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

{{-- Info siswa --}}
<div class="content-card" style="margin-bottom:24px;">
    <div class="card-body" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;">
        <div><div style="font-size:12px;color:var(--text-muted);">Nama</div><div style="font-weight:700;">{{ $raport->siswa->nama_lengkap }}</div></div>
        <div><div style="font-size:12px;color:var(--text-muted);">NISN</div><div style="font-weight:700;">{{ $raport->siswa->nisn }}</div></div>
        <div><div style="font-size:12px;color:var(--text-muted);">Kelas</div><div style="font-weight:700;">{{ $raport->siswa->kelas->nama_kelas ?? '-' }}</div></div>
        <div><div style="font-size:12px;color:var(--text-muted);">Semester</div><div style="font-weight:700;">{{ $raport->semester }}</div></div>
        <div><div style="font-size:12px;color:var(--text-muted);">Tahun Ajaran</div><div style="font-weight:700;">{{ $raport->tahun_ajaran }}</div></div>
    </div>
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
                    <th style="text-align:center;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilai as $n)
                @php $lulus = $n->nilai_akhir >= ($n->mataPelajaran->kkm ?? 75); @endphp
                <tr>
                    <td>{{ $n->mataPelajaran->nama ?? '-' }}</td>
                    <td style="text-align:center;">{{ $n->nilai_tugas ?? '-' }}</td>
                    <td style="text-align:center;">{{ $n->nilai_uts ?? '-' }}</td>
                    <td style="text-align:center;">{{ $n->nilai_uas ?? '-' }}</td>
                    <td style="text-align:center;font-weight:700;">{{ $n->nilai_akhir ?? '-' }}</td>
                    <td style="text-align:center;">{{ $n->mataPelajaran->kkm ?? 75 }}</td>
                    <td style="text-align:center;">
                        <span class="badge {{ $lulus ? 'badge-green' : 'badge-light' }}">
                            {{ $lulus ? 'Lulus' : 'Belum Lulus' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty-row">Belum ada data nilai.</td></tr>
                @endforelse
            </tbody>
            @if($nilai->count())
            <tfoot>
                <tr style="font-weight:700;background:var(--bg);">
                    <td>Rata-rata</td>
                    <td style="text-align:center;">{{ round($nilai->avg('nilai_tugas'), 1) }}</td>
                    <td style="text-align:center;">{{ round($nilai->avg('nilai_uts'), 1) }}</td>
                    <td style="text-align:center;">{{ round($nilai->avg('nilai_uas'), 1) }}</td>
                    <td style="text-align:center;">{{ round($nilai->avg('nilai_akhir'), 1) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- Catatan wali kelas --}}
@if($raport->catatan_wali_kelas)
<div class="content-card">
    <div class="card-header">
        <h3><i class="fas fa-comment-dots" style="color:var(--orange);margin-right:8px;"></i>Catatan Wali Kelas</h3>
    </div>
    <div class="card-body">
        <p style="color:var(--text-primary);line-height:1.7;margin:0;">{{ $raport->catatan_wali_kelas }}</p>
    </div>
</div>
@endif
@endsection