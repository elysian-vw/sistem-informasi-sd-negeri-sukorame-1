@extends('layouts.app')
@section('title', 'Edit Galeri')

@push('styles')
<style>
    .form-container-card {
        padding: 2.5rem !important;
    }
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .img-preview-container {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        background: #f8fafc;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 px-2">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Edit Galeri</h1>
            <p class="text-muted small">Ubah informasi dokumentasi foto atau video.</p>
        </div>
        <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary shadow-sm px-4">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger mx-2 shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card card-custom mb-4">
                <div class="card-body form-container-card">
                    <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-4">
                            <label class="form-label">Tipe Galeri <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4 pt-1">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="tipeFoto" name="tipe" value="foto" class="custom-control-input" {{ old('tipe', $galeri->tipe) == 'foto' ? 'checked' : '' }} onchange="toggleTipe('foto')">
                                    <label class="custom-control-label" for="tipeFoto">Foto</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="tipeVideo" name="tipe" value="video" class="custom-control-input" {{ old('tipe', $galeri->tipe) == 'video' ? 'checked' : '' }} onchange="toggleTipe('video')">
                                    <label class="custom-control-label" for="tipeVideo">Video</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Judul <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control form-control-lg" value="{{ old('judul', $galeri->judul) }}" required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori" class="form-control">
                                        @foreach(['kegiatan', 'pembelajaran', 'prestasi', 'nasional', 'ppdb', 'ekstra', 'profil'] as $kat)
                                            <option value="{{ $kat }}" {{ old('kategori', $galeri->kategori) == $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $galeri->tanggal ? $galeri->tanggal->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>

                        <div id="section-foto" class="{{ old('tipe', $galeri->tipe) == 'foto' ? '' : 'd-none' }}">
                            <div class="form-group mb-4">
                                <label class="form-label">File Foto</label>
                                @if($galeri->file_path)
                                    <div class="mb-3">
                                        <div class="img-preview-container">
                                            <img src="{{ asset('storage/' . $galeri->file_path) }}" class="rounded shadow-sm" style="max-height: 200px;">
                                        </div>
                                        <p class="small text-muted mt-2">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" name="file_path" class="form-control-file border p-2 rounded w-100" accept="image/*">
                                <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengubah foto. Maksimal 5MB.</small>
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
                                    <div class="mb-3">
                                        <div class="img-preview-container">
                                            <img src="{{ asset('storage/' . $galeri->thumbnail) }}" class="rounded shadow-sm" style="max-height: 200px;">
                                        </div>
                                        <p class="small text-muted mt-2">Thumbnail saat ini</p>
                                    </div>
                                @endif
                                <input type="file" name="thumbnail" class="form-control-file border p-2 rounded w-100" accept="image/*">
                                <small class="text-muted d-block mt-1">Opsional. Kosongkan jika tidak ingin mengubah thumbnail.</small>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="isPublished" name="is_published" value="1" {{ old('is_published', $galeri->is_published) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="isPublished">Tampilkan di Website Publik</label>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.galeri.index') }}" class="btn btn-light px-4 mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-2"></i> Perbarui Galeri
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleTipe(tipe) {
    const fotoSection = document.getElementById('section-foto');
    const videoSection = document.getElementById('section-video');
    const fotoInput = fotoSection.querySelector('input[name="file_path"]');
    const videoInput = videoSection.querySelector('input[name="url_video"]');

    if (tipe === 'foto') {
        fotoSection.classList.remove('d-none');
        fotoInput.disabled = false;

        videoSection.classList.add('d-none');
        videoInput.disabled = true;
        videoInput.value = '';
    } else {
        videoSection.classList.remove('d-none');
        videoInput.disabled = false;

        fotoSection.classList.add('d-none');
        fotoInput.disabled = true;
        fotoInput.value = '';
    }
}

// Jalankan saat halaman pertama kali load
document.addEventListener('DOMContentLoaded', function () {
    const checked = document.querySelector('input[name="tipe"]:checked');
    if (checked) toggleTipe(checked.value);
});
</script>
@endsection
