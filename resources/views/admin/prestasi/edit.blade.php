@extends('layouts.app')
@section('title', 'Edit Prestasi')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Prestasi</h1>
    <p class="page-subtitle">Ubah data capaian prestasi</p>
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
    <form action="{{ route('admin.prestasi.update', $prestasi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
            <label class="form-label">Siswa Pelaksana</label>
            <select name="siswa_id" class="form-control">
                <option value="">-- Atas Nama Sekolah --</option>
                @foreach($siswa as $s)
                    <option value="{{ $s->id }}" {{ old('siswa_id', $prestasi->siswa_id) == $s->id ? 'selected' : '' }}>{{ $s->user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Nama Lomba / Capaian <span class="text-danger">*</span></label>
            <input type="text" name="nama_lomba" class="form-control" value="{{ old('nama_lomba', $prestasi->nama_lomba) }}" required>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
            <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara', $prestasi->penyelenggara) }}" required>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-4">
                <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                <select name="tingkat" class="form-control" required>
                    @foreach(['sekolah','kecamatan','kota','provinsi','nasional','internasional'] as $t)
                        <option value="{{ $t }}" {{ old('tingkat', $prestasi->tingkat) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Capaian / Juara <span class="text-danger">*</span></label>
                <select name="juara" class="form-control" required>
                    @foreach(['1','2','3','harapan_1','harapan_2','harapan_3','finalis','peserta'] as $j)
                        <option value="{{ $j }}" {{ old('juara', $prestasi->juara) == $j ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($j)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $prestasi->tanggal) }}" required>
            </div>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-6">
                <label class="form-label">Bidang</label>
                <input type="text" name="bidang" class="form-control" value="{{ old('bidang', $prestasi->bidang) }}">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">Foto Dokumentasi</label>
                @if($prestasi->foto)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $prestasi->foto) }}" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $prestasi->keterangan) }}</textarea>
        </div>

        <div class="form-group mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" class="form-checkbox" {{ old('is_published', $prestasi->is_published) ? 'checked' : '' }}>
                <span class="ml-2">Tampilkan di Website</span>
            </label>
        </div>

        <div class="form-actions mt-6">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.prestasi.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
