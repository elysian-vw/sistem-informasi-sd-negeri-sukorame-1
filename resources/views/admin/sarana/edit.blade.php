@extends('layouts.app')
@section('title', 'Edit Sarana Prasarana')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Sarana</h1>
    <p class="page-subtitle">Ubah data sarana atau prasarana sekolah</p>
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
    <form action="{{ route('admin.sarana.update', $sarana->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
            <label class="form-label">Nama Sarana <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $sarana->nama) }}" required>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $sarana->deskripsi) }}</textarea>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-4">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $sarana->jumlah) }}" min="1" required>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                <select name="kondisi" class="form-control" required>
                    <option value="Baik" {{ old('kondisi', $sarana->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ old('kondisi', $sarana->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ old('kondisi', $sarana->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $sarana->urutan) }}">
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Foto Sarana</label>
            @if($sarana->foto)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $sarana->foto) }}" class="img-thumbnail" style="max-height: 150px;">
                </div>
            @endif
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-gray-500">Kosongkan jika tidak ingin mengubah foto. Maksimal 2MB.</small>
        </div>

        <div class="form-actions mt-6">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.sarana.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
