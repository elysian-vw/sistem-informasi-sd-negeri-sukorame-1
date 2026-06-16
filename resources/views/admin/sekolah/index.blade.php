@extends('layouts.app')
@section('title', 'Kelola Data Sekolah')

@section('content')
<div class="page-header">
    <h1 class="page-title">Informasi Data Sekolah</h1>
    <p class="page-subtitle">Kelola informasi dan identitas sekolah</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-card">
    {{-- Tab Navigation --}}
    <div class="tab-nav">
        <button class="tab-btn active" data-tab="info-umum">
            <i class="fas fa-info-circle"></i> Info Umum
        </button>
        <button class="tab-btn" data-tab="kontak">
            <i class="fas fa-phone"></i> Kontak Sekolah
        </button>
        <button class="tab-btn" data-tab="logo">
            <i class="fas fa-image"></i> Logo & Identitas
        </button>
        <button class="tab-btn" data-tab="akreditasi">
            <i class="fas fa-certificate"></i> Akreditasi
        </button>
        <button class="tab-btn" data-tab="sosmed">
            <i class="fab fa-facebook"></i> Media Sosial
        </button>
    </div>

    <form action="{{ route('admin.sekolah.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Tab: Info Umum --}}
        <div class="tab-content active" id="tab-info-umum">
            <div class="form-section-title">Informasi Sekolah</div>

            <div class="form-group">
                <label class="form-label">NPSN</label>
                <input type="text" name="npsn" class="form-control" value="{{ old('npsn', $sekolah->npsn) }}" placeholder="Nomor Pokok Sekolah Nasional">
            </div>

            <div class="form-group">
                <label class="form-label">Nama Sekolah <span class="required">*</span></label>
                <input type="text" name="nama_sekolah" class="form-control" value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}" placeholder="Nama lengkap sekolah" required>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap sekolah">{{ old('alamat', $sekolah->alamat) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $sekolah->kelurahan) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $sekolah->kecamatan) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kota / Kabupaten</label>
                    <input type="text" name="kota" class="form-control" value="{{ old('kota', $sekolah->kota) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Provinsi</label>
                    <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $sekolah->provinsi) }}">
                </div>
                <div class="form-group" style="max-width: 140px;">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="kode_pos" class="form-control" value="{{ old('kode_pos', $sekolah->kode_pos) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status Sekolah</label>
                    <select name="status_sekolah" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="Negeri" {{ old('status_sekolah', $sekolah->status_sekolah) == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                        <option value="Swasta" {{ old('status_sekolah', $sekolah->status_sekolah) == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenjang</label>
                    <select name="jenjang" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="SD" {{ old('jenjang', $sekolah->jenjang) == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ old('jenjang', $sekolah->jenjang) == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ old('jenjang', $sekolah->jenjang) == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="SMK" {{ old('jenjang', $sekolah->jenjang) == 'SMK' ? 'selected' : '' }}>SMK</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Berdiri</label>
                    <input type="text" name="tahun_berdiri" class="form-control" value="{{ old('tahun_berdiri', $sekolah->tahun_berdiri) }}" placeholder="cth: 1985">
                </div>
            </div>
        </div>

        {{-- Tab: Kontak Sekolah --}}
        <div class="tab-content" id="tab-kontak">
            <div class="form-section-title">Kontak & Penanggung Jawab</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $sekolah->telepon) }}" placeholder="cth: 0354-123456">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $sekolah->email) }}" placeholder="email@sekolah.sch.id">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Website</label>
                <input type="url" name="website" class="form-control" value="{{ old('website', $sekolah->website) }}" placeholder="https://sekolah.sch.id">
            </div>

            <div class="form-section-title" style="margin-top: 24px;">Kepala Sekolah</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Kepala Sekolah</label>
                    <input type="text" name="nama_kepala_sekolah" class="form-control" value="{{ old('nama_kepala_sekolah', $sekolah->nama_kepala_sekolah) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">NIP</label>
                    <input type="text" name="nip_kepala_sekolah" class="form-control" value="{{ old('nip_kepala_sekolah', $sekolah->nip_kepala_sekolah) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Kepala Sekolah</label>
                @if($sekolah->foto_kepsek)
                    <div class="logo-preview">
                        <img src="{{ Storage::url($sekolah->foto_kepsek) }}" alt="Foto Kepsek">
                    </div>
                @endif
                <input type="file" name="foto_kepsek" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label class="form-label">Sambutan Kepala Sekolah</label>
                <textarea name="sambutan_kepsek" class="form-control" rows="4">{{ old('sambutan_kepsek', $sekolah->sambutan_kepsek) }}</textarea>
            </div>
        </div>

        {{-- Tab: Logo & Identitas --}}
        <div class="tab-content" id="tab-logo">
            <div class="form-section-title">Identitas Visual</div>

            <div class="form-group">
                <label class="form-label">Logo Sekolah</label>
                @if($sekolah->logo)
                    <div class="logo-preview">
                        <img src="{{ Storage::url($sekolah->logo) }}" alt="Logo Sekolah">
                        <p class="logo-caption">Logo saat ini</p>
                    </div>
                @endif
                <input type="file" name="logo" class="form-control" accept="image/png,image/jpg,image/jpeg">
                <small class="form-hint">Format: JPG, PNG. Maks 2MB.</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Singkat</label>
                    <input type="text" name="nama_singkat" class="form-control" value="{{ old('nama_singkat', $sekolah->nama_singkat) }}" placeholder="cth: SDN Sukorame 1">
                </div>
                <div class="form-group">
                    <label class="form-label">Slogan / Motto</label>
                    <input type="text" name="slogan" class="form-control" value="{{ old('slogan', $sekolah->slogan) }}" placeholder="cth: Cerdas, Berkarakter, Berprestasi">
                </div>
            </div>
        </div>

        {{-- Tab: Akreditasi --}}
        <div class="tab-content" id="tab-akreditasi">
            <div class="form-section-title">Data Akreditasi</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status Akreditasi</label>
                    <select name="akreditasi" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="A" {{ old('akreditasi', $sekolah->akreditasi) == 'A' ? 'selected' : '' }}>A (Unggul)</option>
                        <option value="B" {{ old('akreditasi', $sekolah->akreditasi) == 'B' ? 'selected' : '' }}>B (Baik)</option>
                        <option value="C" {{ old('akreditasi', $sekolah->akreditasi) == 'C' ? 'selected' : '' }}>C (Cukup)</option>
                        <option value="Belum Terakreditasi" {{ old('akreditasi', $sekolah->akreditasi) == 'Belum Terakreditasi' ? 'selected' : '' }}>Belum Terakreditasi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nilai Akreditasi</label>
                    <input type="number" name="nilai_akreditasi" class="form-control" value="{{ old('nilai_akreditasi', $sekolah->nilai_akreditasi) }}" min="0" max="100" placeholder="0 - 100">
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Akreditasi</label>
                    <input type="text" name="tahun_akreditasi" class="form-control" value="{{ old('tahun_akreditasi', $sekolah->tahun_akreditasi) }}" placeholder="cth: 2022">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nomor SK Akreditasi</label>
                <input type="text" name="nomor_sk_akreditasi" class="form-control" value="{{ old('nomor_sk_akreditasi', $sekolah->nomor_sk_akreditasi) }}" placeholder="Nomor surat keputusan akreditasi">
            </div>
        </div>

        {{-- Tab: Media Sosial --}}
        <div class="tab-content" id="tab-sosmed">
            <div class="form-section-title">Media Sosial Sekolah</div>

            <div class="form-group">
                <label class="form-label"><i class="fab fa-facebook"></i> Facebook URL</label>
                <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $sekolah->facebook) }}" placeholder="https://facebook.com/...">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fab fa-instagram"></i> Instagram URL</label>
                <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $sekolah->instagram) }}" placeholder="https://instagram.com/...">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fab fa-youtube"></i> YouTube URL</label>
                <input type="url" name="youtube" class="form-control" value="{{ old('youtube', $sekolah->youtube) }}" placeholder="https://youtube.com/...">
            </div>

            <div class="form-section-title" style="margin-top: 24px;">Tahun Ajaran</div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tahun Ajaran Aktif</label>
                    <input type="text" name="tahun_ajaran_aktif" class="form-control" value="{{ old('tahun_ajaran_aktif', $sekolah->tahun_ajaran_aktif) }}" placeholder="cth: 2024/2025">
                </div>
                <div class="form-group">
                    <label class="form-label">Semester Aktif</label>
                    <select name="semester_aktif" class="form-control">
                        <option value="Ganjil" {{ old('semester_aktif', $sekolah->semester_aktif) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('semester_aktif', $sekolah->semester_aktif) == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Footer Buttons --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<style>
/* Tab Navigation */
.tab-nav {
    display: flex;
    gap: 4px;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 28px;
    padding: 0 24px;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 12px 20px;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    font-size: 13.5px;
    font-weight: 500;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 7px;
}

.tab-btn:hover { color: #374151; }

.tab-btn.active {
    color: #2563eb;
    border-bottom-color: #2563eb;
}

/* Tab Content */
.tab-content {
    display: none;
    padding: 0 24px;
    animation: fadeIn 0.2s ease;
}

.tab-content.active { display: block; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Form Elements */
.form-section-title {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #9ca3af;
    margin-bottom: 16px;
}

.form-row {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.form-row .form-group { flex: 1; min-width: 180px; }

.form-group { margin-bottom: 18px; }

.form-label {
    display: block;
    font-size: 13.5px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
}

.required { color: #ef4444; }

.form-control {
    width: 100%;
    padding: 9px 13px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    color: #111827;
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

textarea.form-control { resize: vertical; }

.form-hint { font-size: 12px; color: #9ca3af; margin-top: 5px; display: block; }

/* Logo Preview */
.logo-preview {
    margin-bottom: 12px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
}

.logo-preview img {
    height: 80px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 6px;
    background: #f9fafb;
    object-fit: contain;
}

.logo-caption { font-size: 12px; color: #9ca3af; margin: 0; }

/* Actions */
.form-actions {
    display: flex;
    gap: 10px;
    padding: 20px 24px 24px;
    border-top: 1px solid #f3f4f6;
    margin-top: 12px;
}

/* Alert */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.alert-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
</style>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.tab;

        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        btn.classList.add('active');
        document.getElementById('tab-' + target).classList.add('active');
    });
});

// Kalau ada validation error, buka tab yang ada errornya
@if($errors->any())
    const fields = @json($errors->keys());
    const tabMap = {
        'info-umum': ['npsn','nama_sekolah','alamat','kelurahan','kecamatan','kota','provinsi','kode_pos','status_sekolah','jenjang','tahun_berdiri'],
        'kontak': ['telepon','email','website','nama_kepala_sekolah','nip_kepala_sekolah'],
        'logo': ['logo','nama_singkat','slogan'],
        'akreditasi': ['akreditasi','nilai_akreditasi','tahun_akreditasi','nomor_sk_akreditasi'],
    };

    for (const [tab, tabFields] of Object.entries(tabMap)) {
        if (fields.some(f => tabFields.includes(f))) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
            break;
        }
    }
@endif
</script>
@endsection