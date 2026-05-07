@extends('layouts.guru')
@section('title', 'Buat Tugas Baru')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Buat Tugas Baru</h1>
        <p class="page-subtitle"><a href="{{ route('guru.tugas.index') }}" style="color:var(--primary);">Tugas</a> / Buat Baru</p>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger"><i class="fas fa-circle-exclamation"></i>
    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
</div>
@endif

<div class="content-card" style="max-width:760px;">
    <div class="card-header">
        <h3><i class="fas fa-file-pen" style="color:var(--primary);margin-right:8px;"></i>Form Tugas</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('guru.tugas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Judul Tugas <span style="color:var(--danger);">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="form-control" placeholder="Contoh: Latihan Soal Perkalian">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                {{-- BAGIAN KELAS YANG SUDAH DIKUNCI --}}
                <div class="form-group">
                    <label>Kelas <span style="color:var(--danger);">*</span></label>
                    <select name="kelas_id" class="form-control" style="background-color: #f8f9fa; cursor: not-allowed; pointer-events: none;" readonly>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" selected>Kelas {{ $k->nama_kelas }} (Kelas Anda)</option>
                        @endforeach
                    </select>
                    <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block;">
                        * Terkunci pada kelas yang Anda ajar.
                    </small>
                </div>
                
                <div class="form-group">
                    <label>Mata Pelajaran <span style="color:var(--danger);">*</span></label>
                    <select name="mata_pelajaran_id" class="form-control">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}" @selected(old('mata_pelajaran_id')==$m->id)>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi / Petunjuk Tugas</label>
                <textarea name="deskripsi" rows="5" class="form-control" placeholder="Tuliskan petunjuk pengerjaan tugas...">{{ old('deskripsi') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label>Deadline</label>
                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="aktif"  @selected(old('status','aktif')=='aktif')>Aktif (langsung publikasi)</option>
                        <option value="draft"  @selected(old('status')=='draft')>Draft (simpan dulu)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Lampiran File (opsional)</label>
                <input type="file" name="file" class="form-control">
                <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block;">Maksimal 10 MB. (PDF, Word, Excel, PPT, dll)</small>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Tugas</button>
                <a href="{{ route('guru.tugas.index') }}" class="btn" style="background:var(--bg);color:var(--text-secondary);border:1px solid var(--border);">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection