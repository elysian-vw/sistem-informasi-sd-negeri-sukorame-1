@extends('layouts.app')
@section('title', 'Tambah Anggota Komite')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Anggota Komite</h1>
    <p class="page-subtitle">Tambah pengurus komite sekolah baru</p>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-card p-6">
    <form action="{{ route('admin.komite.store') }}" method="POST">
        @csrf

        <div class="form-group mb-4">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-6">
                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" required placeholder="Contoh: Ketua Komite, Sekretaris">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">Unsur <span class="text-danger">*</span></label>
                <input type="text" name="unsur" class="form-control" value="{{ old('unsur', 'Orang Tua Siswa') }}" required>
            </div>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon') }}">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', 0) }}">
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Status</label>
            <select name="aktif" class="form-control" required>
                <option value="1" {{ old('aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('aktif') == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="form-actions mt-6">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.komite.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
