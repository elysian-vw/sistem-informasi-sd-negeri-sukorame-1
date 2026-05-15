{{-- resources/views/wali/raport/index.blade.php --}}
@extends('layouts.wali')
@section('title', 'Raport')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Raport</h1>
        <p class="page-subtitle">Lihat nilai raport anak Anda</p>
    </div>
</div>

{{-- Pilih anak --}}
@if($anakList->count() > 1)
<div class="content-card" style="margin-bottom:16px;">
    <div class="card-body" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <span style="font-size:13px;color:var(--text-secondary);font-weight:600;">Pilih anak:</span>
        @foreach($anakList as $anak)
        <a href="?anak={{ $anak->id }}"
           class="btn btn-sm {{ ($anakUtama?->id == $anak->id) ? 'btn-primary' : '' }}"
           style="{{ ($anakUtama?->id != $anak->id) ? 'background:var(--bg);color:var(--text-secondary);border:1px solid var(--border);' : '' }}">
            {{ $anak->nama_lengkap }}
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- Info anak --}}
@if($anakUtama)
<div class="content-card" style="margin-bottom:24px;">
    <div class="card-body" style="display:flex;align-items:center;gap:16px;">
        <div class="user-avatar" style="width:56px;height:56px;font-size:20px;border-radius:14px;">
            {{ strtoupper(substr($anakUtama->nama_lengkap, 0, 1)) }}
        </div>
        <div>
            <div style="font-size:18px;font-weight:800;color:var(--text-primary);">{{ $anakUtama->nama_lengkap }}</div>
            <div style="font-size:13px;color:var(--text-muted);">
                NISN: {{ $anakUtama->nisn }} &nbsp;·&nbsp; Kelas {{ $anakUtama->kelas->nama_kelas ?? '-' }}
            </div>
        </div>
    </div>
</div>

{{-- Daftar raport --}}
<div class="content-card">
    <div class="card-header">
        <h3><i class="fas fa-book-open" style="color:var(--primary);margin-right:8px;"></i>Daftar Raport</h3>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($raportList as $r)
                <tr>
                    <td>{{ $r->tahun_ajaran }}</td>
                    <td>Semester {{ $r->semester }}</td>
                    <td><span class="badge badge-green">Terbit</span></td>
                    <td>
                        <a href="{{ route('wali.raport.show', $r) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="empty-row">Belum ada raport yang diterbitkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection