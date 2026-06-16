@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Kegiatan</h1>
        <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form action="{{ route('admin.kegiatan.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Judul Kegiatan</label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: Rapat Pleno Komite Sekolah" required>
                            @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}" required>
                                    @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Tanggal Selesai (Opsional)</label>
                                    <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}">
                                    @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Waktu Mulai</label>
                                    <input type="time" name="waktu_mulai" class="form-control @error('waktu_mulai') is-invalid @enderror" value="{{ old('waktu_mulai') }}">
                                    @error('waktu_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Waktu Selesai</label>
                                    <input type="time" name="waktu_selesai" class="form-control @error('waktu_selesai') is-invalid @enderror" value="{{ old('waktu_selesai') }}">
                                    @error('waktu_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Tempat</label>
                            <input type="text" name="tempat" class="form-control @error('tempat') is-invalid @enderror" value="{{ old('tempat') }}" placeholder="Contoh: Ruang Aula Utama">
                            @error('tempat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Kategori</label>
                                    <select name="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        <option value="upacara">Upacara</option>
                                        <option value="penilaian">Penilaian/Ujian</option>
                                        <option value="perpisahan">Perpisahan</option>
                                        <option value="libur">Libur</option>
                                        <option value="ppdb">PPDB</option>
                                        <option value="ekskul">Ekskul</option>
                                        <option value="rapat">Rapat</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label font-weight-bold">Sasaran</label>
                                    <select name="sasaran" class="form-control @error('sasaran') is-invalid @enderror" required>
                                        <option value="semua">Semua</option>
                                        <option value="guru">Guru</option>
                                        <option value="siswa">Siswa</option>
                                        <option value="wali_murid">Wali Murid</option>
                                    </select>
                                    @error('sasaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label font-weight-bold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Tuliskan detail kegiatan di sini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.kegiatan.index') }}" class="btn btn-light mr-2">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save mr-1"></i> Simpan Kegiatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
