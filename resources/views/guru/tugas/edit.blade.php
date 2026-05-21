@extends('layouts.guru')
@section('title', 'Edit Tugas')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Tugas</h1>
        <p class="page-subtitle"><a href="{{ route('guru.tugas.index') }}" style="color:var(--primary);">Tugas</a> / Edit</p>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger"><i class="fas fa-circle-exclamation"></i>
    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
</div>
@endif

<div class="content-card" style="max-width:800px;">
    <div class="card-header">
        <h3><i class="fas fa-file-pen" style="color:var(--primary);margin-right:8px;"></i>Form Edit Tugas</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('guru.tugas.update', $tugas) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ── INFO UMUM ── --}}
            <div class="form-group">
                <label>Judul Tugas <span style="color:var(--danger);">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $tugas->judul) }}" class="form-control"
                       placeholder="Contoh: Latihan Soal Perkalian">
                @error('judul')
                    <div style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                {{-- Kelas: lock jika 1 kelas, dropdown jika guru mulok --}}
                <div class="form-group">
                    <label>Kelas <span style="color:var(--danger);">*</span></label>
                    @if($kelas->count() === 1)
                        <select name="kelas_id" class="form-control"
                                style="background-color:#f8f9fa;cursor:not-allowed;pointer-events:none;">
                            <option value="{{ $kelas->first()->id }}" selected>
                                Kelas {{ $kelas->first()->nama_kelas }} (Kelas Anda)
                            </option>
                        </select>
                        <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block;">
                            * Terkunci pada kelas yang Anda ajar.
                        </small>
                    @else
                        <select name="kelas_id" class="form-control">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}"
                                    @selected(old('kelas_id', $tugas->kelas_id) == $k->id)>
                                    Kelas {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="form-group">
                    <label>Mata Pelajaran <span style="color:var(--danger);">*</span></label>
                    <select name="mata_pelajaran_id" class="form-control">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}"
                                @selected(old('mata_pelajaran_id', $tugas->mata_pelajaran_id) == $m->id)>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran_id')
                        <div style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi / Petunjuk Tugas</label>
                <textarea name="deskripsi" rows="4" class="form-control"
                          placeholder="Tuliskan petunjuk pengerjaan tugas...">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label>Deadline</label>
                    <input type="datetime-local" name="deadline"
                           value="{{ old('deadline', $tugas->deadline?->format('Y-m-d\TH:i')) }}"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="aktif" @selected(old('status', $tugas->status) == 'aktif')>Aktif</option>
                        <option value="draft" @selected(old('status', $tugas->status) == 'draft')>Draft</option>
                    </select>
                </div>
            </div>

            {{-- Lampiran hanya tampil jika bukan CBT --}}
            @if(!$tugas->isCbt())
            <div class="form-group">
                <label>Lampiran File (opsional)</label>
                @if($tugas->file)
                    <div style="margin-bottom:8px;padding:8px 12px;background:var(--bg);border:1px solid var(--border);border-radius:8px;font-size:13px;">
                        <i class="fas fa-paperclip" style="color:var(--primary);margin-right:6px;"></i>
                        File saat ini:
                        <a href="{{ Storage::url($tugas->file) }}" target="_blank" style="color:var(--primary);">
                            Lihat File
                        </a>
                        <span style="color:var(--text-muted);margin-left:8px;">(Upload baru untuk mengganti)</span>
                    </div>
                @endif
                <input type="file" name="file" class="form-control">
                <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block;">
                    PDF, DOC, DOCX, ZIP. Maks 5 MB.
                </small>
            </div>
            @endif

            {{-- Info tipe tugas (readonly, tidak bisa diubah) --}}
            <div class="form-group">
                <label>Tipe Tugas</label>
                <div style="padding:10px 14px;background:var(--bg);border:1px solid var(--border);border-radius:8px;font-size:13px;font-weight:600;">
                    @if($tugas->isCbt())
                        <i class="fas fa-list-check" style="color:var(--purple);margin-right:6px;"></i>
                        CBT (Pilihan Ganda)
                        <span style="color:var(--text-muted);font-weight:400;margin-left:6px;">— tipe tidak bisa diubah</span>
                    @else
                        <i class="fas fa-file-arrow-up" style="color:var(--primary);margin-right:6px;"></i>
                        Upload File
                        <span style="color:var(--text-muted);font-weight:400;margin-left:6px;">— tipe tidak bisa diubah</span>
                    @endif
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Tugas
                </button>
                <a href="{{ route('guru.tugas.index') }}" class="btn"
                   style="background:var(--bg);color:var(--text-secondary);border:1px solid var(--border);">
                    Batal
                </a>
                @if($tugas->isCbt())
                <a href="{{ route('guru.tugas.soal', $tugas) }}" class="btn"
                   style="background:#f3e5f5;color:var(--purple);border:1px solid #e1bee7;">
                    <i class="fas fa-list-check"></i> Kelola Soal CBT
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

@endsection