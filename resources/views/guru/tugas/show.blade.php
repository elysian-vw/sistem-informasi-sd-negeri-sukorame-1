@extends('layouts.guru')
@section('title', 'Detail Tugas')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Detail Tugas</h1>
        <p class="page-subtitle"><a href="{{ route('guru.tugas.index') }}" style="color:var(--primary);">Tugas</a> / Detail</p>
    </div>
    <a href="{{ route('guru.tugas.index') }}" class="btn" style="background:var(--bg);color:var(--text-secondary);border:1px solid var(--border);">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="content-card" style="max-width:800px;">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3><i class="fas fa-file-lines" style="color:var(--primary);margin-right:8px;"></i>{{ $tugas->judul }}</h3>
        @php $colors = ['aktif'=>'badge-green','selesai'=>'badge-light','draft'=>'badge-light']; @endphp
        <span class="badge {{ $colors[$tugas->status] ?? 'badge-light' }}">{{ ucfirst($tugas->status) }}</span>
    </div>
    <div class="card-body">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Kelas</div>
                <div style="font-weight:600;">{{ $tugas->kelas->nama_kelas ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Mata Pelajaran</div>
                <div style="font-weight:600;">{{ $tugas->mataPelajaran->nama ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Tipe</div>
                <div style="font-weight:600;">{{ $tugas->tipe === 'cbt' ? 'CBT (Pilihan Ganda)' : 'Upload File' }}</div>
            </div>
            <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">Deadline</div>
                <div style="font-weight:600;color:{{ $tugas->isTerlambat() ? 'var(--danger)' : 'var(--text-primary)' }};">
                    {{ $tugas->deadline?->format('d/m/Y H:i') ?? '-' }}
                    @if($tugas->isTerlambat()) <span style="font-size:12px;">(Sudah lewat)</span> @endif
                </div>
            </div>
        </div>

        @if($tugas->deskripsi)
        <div style="margin-bottom:20px;">
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:6px;">Deskripsi / Petunjuk</div>
            <div style="padding:14px;background:var(--bg);border:1px solid var(--border);border-radius:8px;line-height:1.7;">
                {!! nl2br(e($tugas->deskripsi)) !!}
            </div>
        </div>
        @endif

        @if($tugas->file)
        <div style="margin-bottom:20px;">
            <div style="font-size:12px;color:var(--text-muted);margin-bottom:6px;">Lampiran</div>
            <a href="{{ Storage::url($tugas->file) }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;padding:8px 14px;background:var(--primary-light);color:var(--primary);border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;">
                <i class="fas fa-file-arrow-down"></i> Download Lampiran
            </a>
        </div>
        @endif

        <div style="display:flex;gap:10px;padding-top:16px;border-top:1px solid var(--border);">
            <a href="{{ route('guru.tugas.penilaian', $tugas) }}" class="btn btn-primary">
                <i class="fas fa-star"></i> Lihat Penilaian
            </a>
            @if($tugas->isCbt())
            <a href="{{ route('guru.tugas.soal', $tugas) }}" class="btn"
               style="background:#f3e5f5;color:var(--purple);border:none;">
                <i class="fas fa-list-check"></i> Kelola Soal
            </a>
            @endif
        </div>

    </div>
</div>

@endsection