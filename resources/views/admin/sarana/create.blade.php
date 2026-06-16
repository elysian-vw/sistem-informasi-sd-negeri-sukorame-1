@extends('layouts.app')
@section('title', 'Tambah Sarana Prasarana')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Sarana Baru</h1>
    <p class="page-subtitle">Tambah data sarana atau prasarana sekolah</p>
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
    <form action="{{ route('admin.sarana.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-4">
            <label class="form-label">Nama Sarana <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required placeholder="Contoh: Gedung Kelas, Laboratorium Komputer">
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan detail sarana tersebut...">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-4">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" min="1" required>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                <select name="kondisi" class="form-control" required>
                    <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', 0) }}">
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Foto Sarana</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
            <small class="text-gray-500">Maksimal 2MB. Format JPG, PNG.</small>
        </div>

        <div class="form-actions mt-6">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.sarana.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
