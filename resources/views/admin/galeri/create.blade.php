@extends('layouts.app')
@section('title', 'Tambah Galeri')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Galeri Baru</h1>
    <p class="page-subtitle">Tambah dokumentasi foto atau video baru</p>
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
    <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-4">
            <label class="form-label">Tipe Galeri <span class="text-danger">*</span></label>
            <div class="flex gap-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="tipe" value="foto" class="form-radio" {{ old('tipe', 'foto') == 'foto' ? 'checked' : '' }} onchange="toggleTipe('foto')">
                    <span class="ml-2">Foto</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="tipe" value="video" class="form-radio" {{ old('tipe') == 'video' ? 'checked' : '' }} onchange="toggleTipe('video')">
                    <span class="ml-2">Video</span>
                </label>
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Judul <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-6">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-control">
                    <option value="kegiatan">Kegiatan</option>
                    <option value="pembelajaran">Pembelajaran</option>
                    <option value="prestasi">Prestasi</option>
                    <option value="nasional">Hari Nasional</option>
                    <option value="ppdb">PPDB</option>
                    <option value="ekstra">Ekstrakurikuler</option>
                    <option value="profil">Profil Sekolah</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}">
            </div>
        </div>

        <div id="section-foto" class="{{ old('tipe', 'foto') == 'foto' ? '' : 'd-none' }}">
            <div class="form-group mb-4">
                <label class="form-label">File Foto <span class="text-danger">*</span></label>
                <input type="file" name="file_path" class="form-control" accept="image/*">
                <small class="text-gray-500">Maksimal 5MB. Format JPG, PNG.</small>
            </div>
        </div>

        <div id="section-video" class="{{ old('tipe') == 'video' ? '' : 'd-none' }}">
            <div class="form-group mb-4">
                <label class="form-label">URL Video (YouTube) <span class="text-danger">*</span></label>
                <input type="url" name="url_video" class="form-control" value="{{ old('url_video') }}" placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <div class="form-group mb-4">
                <label class="form-label">Thumbnail Video</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                <small class="text-gray-500">Opsional. Jika dikosongkan akan menggunakan thumbnail YouTube.</small>
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" class="form-checkbox" {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                <span class="ml-2">Tampilkan di Website</span>
            </label>
        </div>

        <div class="form-actions mt-6">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function toggleTipe(tipe) {
    if (tipe === 'foto') {
        document.getElementById('section-foto').classList.remove('d-none');
        document.getElementById('section-video').classList.add('d-none');
    } else {
        document.getElementById('section-foto').classList.add('d-none');
        document.getElementById('section-video').classList.remove('d-none');
    }
}
</script>

<style>
.d-none { display: none; }
.form-radio, .form-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
</style>
@endsection
