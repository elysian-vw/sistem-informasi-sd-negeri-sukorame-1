@extends('layouts.app')
@section('title', 'Tambah Prestasi')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Prestasi Baru</h1>
    <p class="page-subtitle">Tambah data capaian prestasi baru</p>
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
    <form action="{{ route('admin.prestasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-4">
            <label class="form-label">Siswa Pelaksana</label>
            <select name="siswa_id" class="form-control">
                <option value="">-- Atas Nama Sekolah --</option>
                @foreach($siswa as $s)
                    <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>{{ $s->user->name }} (Kelas {{ $s->kelas->nama_kelas ?? '' }})</option>
                @endforeach
            </select>
            <small class="text-gray-500">Pilih jika prestasi diraih perorangan/kelompok siswa. Kosongkan jika prestasi lembaga.</small>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Nama Lomba / Capaian <span class="text-danger">*</span></label>
            <input type="text" name="nama_lomba" class="form-control" value="{{ old('nama_lomba') }}" required placeholder="Contoh: Juara 1 Olimpiade Matematika">
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
            <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}" required placeholder="Contoh: Dinas Pendidikan Kota Kediri">
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-4">
                <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                <select name="tingkat" class="form-control" required>
                    <option value="sekolah">Sekolah</option>
                    <option value="kecamatan">Kecamatan</option>
                    <option value="kota">Kota/Kabupaten</option>
                    <option value="provinsi">Provinsi</option>
                    <option value="nasional">Nasional</option>
                    <option value="internasional">Internasional</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Capaian / Juara <span class="text-danger">*</span></label>
                <select name="juara" class="form-control" required>
                    <option value="1">Juara 1</option>
                    <option value="2">Juara 2</option>
                    <option value="3">Juara 3</option>
                    <option value="harapan_1">Harapan 1</option>
                    <option value="harapan_2">Harapan 2</option>
                    <option value="harapan_3">Harapan 3</option>
                    <option value="finalis">Finalis</option>
                    <option value="peserta">Peserta</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
            </div>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-6">
                <label class="form-label">Bidang</label>
                <input type="text" name="bidang" class="form-control" value="{{ old('bidang') }}" placeholder="Contoh: Olahraga, Seni, Akademik">
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">Foto Dokumentasi</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-gray-500">Maksimal 2MB.</small>
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
        </div>

        <div class="form-group mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" class="form-checkbox" {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                <span class="ml-2">Tampilkan di Website</span>
            </label>
        </div>

        <div class="form-actions mt-6">
            <input type="hidden" name="is_published" value="0"> {{-- Fallback for unchecked checkbox --}}
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.prestasi.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
// Fix for checkbox when not checked
document.querySelector('form').addEventListener('submit', function() {
    if(!document.querySelector('input[name="is_published"]').checked) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'is_published';
        input.value = '0';
        this.appendChild(input);
    }
});
</script>
@endsection
