@extends('layouts.app')
@section('title', 'Tambah Prestasi')

@section('content')
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">Tambah Prestasi Baru</h1>
        <p class="page-subtitle">Tambah data capaian prestasi baru</p>
    </div>
    <a href="{{ route('admin.prestasi.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left fa-sm"></i> Kembali
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-card">
    <form action="{{ route('admin.prestasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Hidden fallback untuk checkbox --}}
        <input type="hidden" name="is_published" value="0">

        {{-- SECTION: Identitas --}}
        <div class="form-section">
            <div class="form-section-title">Identitas Lomba</div>

            <div class="form-group mb-4">
                <label class="form-label">Siswa Pelaksana</label>
                <select name="siswa_id" class="form-control">
                    <option value="">— Atas Nama Sekolah —</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->user->name }} (Kelas {{ $s->kelas->nama_kelas ?? '-' }})
                        </option>
                    @endforeach
                </select>
                <small class="form-hint">Kosongkan jika prestasi atas nama lembaga/sekolah.</small>
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Nama Lomba / Capaian <span class="text-danger">*</span></label>
                <input type="text" name="nama_lomba" class="form-control" value="{{ old('nama_lomba') }}"
                       required placeholder="Contoh: Olimpiade Matematika Tingkat Kota">
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Penyelenggara <span class="text-danger">*</span></label>
                <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara') }}"
                       required placeholder="Contoh: Dinas Pendidikan Kota Kediri">
            </div>
        </div>

        <div class="form-divider"></div>

        {{-- SECTION: Hasil --}}
        <div class="form-section">
            <div class="form-section-title">Hasil & Waktu</div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tingkat <span class="text-danger">*</span></label>
                        <select name="tingkat" class="form-control" required>
                            <option value="sekolah"       {{ old('tingkat') == 'sekolah'       ? 'selected' : '' }}>Sekolah</option>
                            <option value="kecamatan"     {{ old('tingkat') == 'kecamatan'     ? 'selected' : '' }}>Kecamatan</option>
                            <option value="kota"          {{ old('tingkat') == 'kota'          ? 'selected' : '' }}>Kota / Kabupaten</option>
                            <option value="provinsi"      {{ old('tingkat') == 'provinsi'      ? 'selected' : '' }}>Provinsi</option>
                            <option value="nasional"      {{ old('tingkat') == 'nasional'      ? 'selected' : '' }}>Nasional</option>
                            <option value="internasional" {{ old('tingkat') == 'internasional' ? 'selected' : '' }}>Internasional</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Capaian / Juara <span class="text-danger">*</span></label>
                        <select name="juara" class="form-control" required>
                            <option value="1"         {{ old('juara') == '1'         ? 'selected' : '' }}>Juara 1</option>
                            <option value="2"         {{ old('juara') == '2'         ? 'selected' : '' }}>Juara 2</option>
                            <option value="3"         {{ old('juara') == '3'         ? 'selected' : '' }}>Juara 3</option>
                            <option value="harapan_1" {{ old('juara') == 'harapan_1' ? 'selected' : '' }}>Harapan 1</option>
                            <option value="harapan_2" {{ old('juara') == 'harapan_2' ? 'selected' : '' }}>Harapan 2</option>
                            <option value="harapan_3" {{ old('juara') == 'harapan_3' ? 'selected' : '' }}>Harapan 3</option>
                            <option value="finalis"   {{ old('juara') == 'finalis'   ? 'selected' : '' }}>Finalis</option>
                            <option value="peserta"   {{ old('juara') == 'peserta'   ? 'selected' : '' }}>Peserta</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control"
                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Bidang</label>
                        <input type="text" name="bidang" class="form-control" value="{{ old('bidang') }}"
                               placeholder="Contoh: Akademik, Olahraga, Seni">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Foto Dokumentasi</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="form-hint">Opsional. Maksimal 2MB (JPG, PNG).</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-divider"></div>

        {{-- SECTION: Lainnya --}}
        <div class="form-section">
            <div class="form-section-title">Informasi Tambahan</div>

            <div class="form-group mb-4">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"
                          placeholder="Catatan atau deskripsi tambahan...">{{ old('keterangan') }}</textarea>
            </div>

            <div class="form-group mb-2">
                <label class="form-check-label-custom">
                    <input type="checkbox" name="is_published" value="1" class="form-checkbox"
                           {{ old('is_published', '1') == '1' ? 'checked' : '' }}>
                    <span>Tampilkan di Website Publik</span>
                </label>
            </div>
        </div>

        <div class="form-divider"></div>

        <div class="form-actions">
            <a href="{{ route('admin.prestasi.index') }}" class="btn btn-light px-4">Batal</a>
            <button type="submit" class="btn btn-primary px-5">
                <i class="fas fa-save mr-1"></i> Simpan Prestasi
            </button>
        </div>
    </form>
</div>

<style>
.form-section { padding: 24px 28px; }
.form-section-title {
    font-size: 11px; font-weight: 800; text-transform: uppercase;
    letter-spacing: .08em; color: #94a3b8; margin-bottom: 20px;
}
.form-divider { height: 1px; background: #f1f5f9; }
.form-hint { font-size: 12px; color: #94a3b8; margin-top: 4px; display: block; }
.form-label { font-weight: 600; font-size: 13px; color: #374151; margin-bottom: 6px; display: block; }
.form-actions {
    padding: 20px 28px;
    display: flex; justify-content: flex-end; gap: 10px;
    background: #f8fafc; border-top: 1px solid #f1f5f9;
    border-radius: 0 0 12px 12px;
}
.form-check-label-custom {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 13.5px; color: #374151; cursor: pointer; font-weight: 500;
}
</style>
@endsection