@extends('layouts.guru')
@section('title', 'Penilaian Rapor')

@section('content')
<div class="page-header">
    <h1 class="page-title">Penilaian Rapor (Akhir)</h1>
    <p class="page-subtitle">Pilih Mata Pelajaran untuk Kelas {{ $kelas->nama_kelas }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(250px, 1fr));gap:20px;">
    @foreach($mapel as $m)
    <div class="content-card" style="text-align:center; padding:30px 20px;">
        <div style="width:60px; height:60px; background:#eff6ff; color:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto 16px;">
            <i class="fas fa-book-open"></i>
        </div>
        <h3 style="margin:0 0 8px 0; font-size:18px;">{{ $m->nama }}</h3>
        <p style="color:var(--text-muted); font-size:13px; margin-bottom:20px;">Input nilai Tugas, UTS, dan UAS</p>
        
        <a href="{{ route('guru.nilai.input', $m->id) }}" class="btn btn-primary" style="width:100%;">
            <i class="fas fa-edit"></i> Input Nilai
        </a>
    </div>
    @endforeach
</div>
@endsection