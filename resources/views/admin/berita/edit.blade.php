@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Berita</h1>
        <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group mb-3">
                            <label class="form-label">Judul Berita</label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $berita->judul) }}" required>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Isi Berita</label>
                            <textarea name="isi" id="editor" class="form-control @error('isi') is-invalid @enderror" rows="10">{{ old('isi', $berita->isi) }}</textarea>
                            @error('isi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                                <option value="berita" {{ old('kategori', $berita->kategori) == 'berita' ? 'selected' : '' }}>Berita</option>
                                <option value="pengumuman" {{ old('kategori', $berita->kategori) == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                <option value="info_dinas" {{ old('kategori', $berita->kategori) == 'info_dinas' ? 'selected' : '' }}>Info Dinas</option>
                                <option value="prestasi" {{ old('kategori', $berita->kategori) == 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                            </select>
                            @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $berita->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $berita->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $berita->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Thumbnail</label>
                            @if($berita->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $berita->thumbnail) }}" alt="Thumbnail" class="img-fluid rounded" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
                            <small class="text-muted">Format: jpg, jpeg, png. Max: 2MB. Biarkan kosong jika tidak ingin mengubah.</small>
                            @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Tanggal Terbit (Opsional)</label>
                            <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary w-100">Perbarui Berita</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>
@endpush
