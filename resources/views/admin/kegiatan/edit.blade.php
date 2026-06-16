@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Kegiatan</h1>
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="{{ route('admin.kegiatan.update', $kegiatan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Judul Kegiatan</label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $kegiatan->judul) }}" required>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->format('Y-m-d') : '') }}" required>
                                    @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Tanggal Selesai (Opsional)</label>
                                    <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $kegiatan->tanggal_selesai ? $kegiatan->tanggal_selesai->format('Y-m-d') : '') }}">
                                    @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Waktu Mulai</label>
                                    <input type="time" name="waktu_mulai" class="form-control @error('waktu_mulai') is-invalid @enderror" value="{{ old('waktu_mulai', $kegiatan->waktu_mulai) }}">
                                    @error('waktu_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Waktu Selesai</label>
                                    <input type="time" name="waktu_selesai" class="form-control @error('waktu_selesai') is-invalid @enderror" value="{{ old('waktu_selesai', $kegiatan->waktu_selesai) }}">
                                    @error('waktu_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Tempat</label>
                            <input type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror" value="{{ old('tempat', $kegiatan->tempat) }}">
                            @error('tempat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Kategori</label>
                                    <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                                        <option value="upacara" {{ old('kategori', $kegiatan->kategori) == 'upacara' ? 'selected' : '' }}>Upacara</option>
                                        <option value="penilaian" {{ old('kategori', $kegiatan->kategori) == 'penilaian' ? 'selected' : '' }}>Penilaian/Ujian</option>
                                        <option value="perpisahan" {{ old('kategori', $kegiatan->kategori) == 'perpisahan' ? 'selected' : '' }}>Perpisahan</option>
                                        <option value="libur" {{ old('kategori', $kegiatan->kategori) == 'libur' ? 'selected' : '' }}>Libur</option>
                                        <option value="ppdb" {{ old('kategori', $kegiatan->kategori) == 'ppdb' ? 'selected' : '' }}>PPDB</option>
                                        <option value="ekskul" {{ old('kategori', $kegiatan->kategori) == 'ekskul' ? 'selected' : '' }}>Ekskul</option>
                                        <option value="rapat" {{ old('kategori', $kegiatan->kategori) == 'rapat' ? 'selected' : '' }}>Rapat</option>
                                        <option value="lainnya" {{ old('kategori', $kegiatan->kategori) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Sasaran</label>
                                    <select name="sasaran" class="form-control @error('sasaran') is-invalid @enderror" required>
                                        <option value="semua" {{ old('sasaran', $kegiatan->sasaran) == 'semua' ? 'selected' : '' }}>Semua</option>
                                        <option value="guru" {{ old('sasaran', $kegiatan->sasaran) == 'guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="siswa" {{ old('sasaran', $kegiatan->sasaran) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                        <option value="wali_murid" {{ old('sasaran', $kegiatan->sasaran) == 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
                                    </select>
                                    @error('sasaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi', $kegiatan->deskripsi) }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Perbarui Kegiatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
