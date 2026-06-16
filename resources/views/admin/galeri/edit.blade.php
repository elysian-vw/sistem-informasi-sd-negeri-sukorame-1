@extends('layouts.app')
@section('title', 'Edit Galeri')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Galeri</h1>
    <p class="page-subtitle">Ubah dokumentasi foto atau video</p>
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
    <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
            <label class="form-label">Tipe Galeri <span class="text-danger">*</span></label>
            <div class="flex gap-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="tipe" value="foto" class="form-radio" {{ old('tipe', $galeri->tipe) == 'foto' ? 'checked' : '' }} onchange="toggleTipe('foto')">
                    <span class="ml-2">Foto</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="tipe" value="video" class="form-radio" {{ old('tipe', $galeri->tipe) == 'video' ? 'checked' : '' }} onchange="toggleTipe('video')">
                    <span class="ml-2">Video</span>
                </label>
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Judul <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $galeri->judul) }}" required>
        </div>

        <div class="form-group mb-4">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
        </div>

        <div class="form-row mb-4">
            <div class="form-group col-md-6">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-control">
                    @foreach(['kegiatan', 'pembelajaran', 'prestasi', 'nasional', 'ppdb', 'ekstra', 'profil'] as $kat)
                        <option value="{{ $kat }}" {{ old('kategori', $galeri->kategori) == $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $galeri->tanggal ? $galeri->tanggal->format('Y-m-d') : '') }}">
            </div>
        </div>

        <div id="section-foto" class="{{ old('tipe', $galeri->tipe) == 'foto' ? '' : 'd-none' }}">
            <div class="form-group mb-4">
                <label class="form-label">File Foto</label>
                @if($galeri->file_path)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $galeri->file_path) }}" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                @endif
                <input type="file" name="file_path" class="form-control" accept="image/*">
                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah foto. Maksimal 5MB.</small>
            </div>
        </div>

        <div id="section-video" class="{{ old('tipe', $galeri->tipe) == 'video' ? '' : 'd-none' }}">
            <div class="form-group mb-4">
                <label class="form-label">URL Video (YouTube) <span class="text-danger">*</span></label>
                <input type="url" name="url_video" class="form-control" value="{{ old('url_video', $galeri->url_video) }}" placeholder="https://www.youtube.com/watch?v=...">
            </div>
            <div class="form-group mb-4">
                <label class="form-label">Thumbnail Video</label>
                @if($galeri->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $galeri->thumbnail) }}" class="img-thumbnail" style="max-height: 150px;">
                    </div>
                @endif
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                <small class="text-gray-500">Opsional. Kosongkan jika tidak ingin mengubah thumbnail.</small>
            </div>
        </div>

        <div class="form-group mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" class="form-checkbox" {{ old('is_published', $galeri->is_published) ? 'checked' : '' }}>
                <span class="ml-2">Tampilkan di Website</span>
            </label>
        </div>

        <div class="form-actions mt-6">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
.img-thumbnail {
    border: 1px solid #dee2e6;
    border-radius: .25rem;
    max-width: 100%;
    height: auto;
}
</style>
@endsection
