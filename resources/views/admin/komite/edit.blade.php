@extends('layouts.app')
@section('title', 'Edit Anggota Komite')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Anggota Komite</h1>
    <p class="page-subtitle">Ubah data pengurus komite sekolah</p>
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
    <form action="{{ route('admin.komite.update', $komite->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $komite->nama) }}" required>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-6">
                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $komite->jabatan) }}" required>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">Unsur <span class="text-danger">*</span></label>
                <input type="text" name="unsur" class="form-control" value="{{ old('unsur', $komite->unsur) }}" required>
            </div>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $komite->telepon) }}">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $komite->urutan) }}">
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Status</label>
            <select name="aktif" class="form-control" required>
                <option value="1" {{ old('aktif', $komite->aktif) ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !old('aktif', $komite->aktif) ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="form-actions mt-6">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.komite.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
