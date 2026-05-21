@extends('layouts.guru')
@section('title', 'Buat Tugas Baru')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Buat Tugas Baru</h1>
        <p class="page-subtitle"><a href="{{ route('guru.tugas.index') }}" style="color:var(--primary);">Tugas</a> / Buat Baru</p>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger"><i class="fas fa-circle-exclamation"></i>
    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
</div>
@endif

<div class="content-card" style="max-width:800px;">
    <div class="card-header">
        <h3><i class="fas fa-file-pen" style="color:var(--primary);margin-right:8px;"></i>Form Tugas</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('guru.tugas.store') }}" method="POST" enctype="multipart/form-data" id="formTugas">
            @csrf

            {{-- ── TIPE TUGAS ── --}}
            <div class="form-group">
                <label style="font-weight:600;margin-bottom:10px;display:block;">
                    Tipe Tugas <span style="color:var(--danger);">*</span>
                </label>
                <div style="display:flex;gap:12px;">
                    <label class="tipe-card" id="cardUpload" onclick="setTipe('upload')"
                           style="flex:1;border:2px solid var(--primary);border-radius:10px;padding:14px 18px;cursor:pointer;display:flex;align-items:center;gap:12px;transition:.2s;">
                        <input type="radio" name="tipe" value="upload" checked style="display:none;">
                        <div style="width:40px;height:40px;border-radius:8px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-file-arrow-up" style="color:var(--primary);font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;">Upload File</div>
                            <div style="font-size:12px;color:var(--text-muted);">Siswa mengumpulkan file</div>
                        </div>
                    </label>
                    <label class="tipe-card" id="cardCbt" onclick="setTipe('cbt')"
                           style="flex:1;border:2px solid var(--border);border-radius:10px;padding:14px 18px;cursor:pointer;display:flex;align-items:center;gap:12px;transition:.2s;">
                        <input type="radio" name="tipe" value="cbt" style="display:none;">
                        <div style="width:40px;height:40px;border-radius:8px;background:#f3e5f5;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-list-check" style="color:var(--purple);font-size:18px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;">CBT (Pilihan Ganda)</div>
                            <div style="font-size:12px;color:var(--text-muted);">Ujian online, dinilai otomatis</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- ── INFO UMUM ── --}}
            <div class="form-group">
                <label>Judul Tugas <span style="color:var(--danger);">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="form-control"
                       placeholder="Contoh: Latihan Soal Perkalian">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                {{-- Kelas: lock jika 1 kelas, dropdown jika mulok --}}
                <div class="form-group">
                    <label>Kelas <span style="color:var(--danger);">*</span></label>
                    @if($kelas->count() === 1)
                        <select name="kelas_id" class="form-control"
                                style="background-color:#f8f9fa;cursor:not-allowed;pointer-events:none;">
                            <option value="{{ $kelas->first()->id }}" selected>
                                Kelas {{ $kelas->first()->nama_kelas }} (Kelas Anda)
                            </option>
                        </select>
                        <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block;">
                            * Terkunci pada kelas yang Anda ajar.
                        </small>
                    @else
                        <select name="kelas_id" class="form-control">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" @selected(old('kelas_id') == $k->id)>
                                    Kelas {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <div style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="form-group">
                    <label>Mata Pelajaran <span style="color:var(--danger);">*</span></label>
                    <select name="mata_pelajaran_id" class="form-control">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}" @selected(old('mata_pelajaran_id') == $m->id)>
                                {{ $m->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran_id')
                        <div style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi / Petunjuk Tugas</label>
                <textarea name="deskripsi" rows="4" class="form-control"
                          placeholder="Tuliskan petunjuk pengerjaan tugas...">{{ old('deskripsi') }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label>Deadline</label>
                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="aktif" @selected(old('status','aktif') == 'aktif')>Aktif</option>
                        <option value="draft" @selected(old('status') == 'draft')>Draft</option>
                    </select>
                </div>
            </div>

            {{-- ── LAMPIRAN (hanya untuk tipe upload) ── --}}
            <div id="sectionUpload">
                <div class="form-group">
                    <label>Lampiran File (opsional)</label>
                    <input type="file" name="file" class="form-control">
                    <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block;">
                        PDF, DOC, DOCX, ZIP. Maks 5 MB.
                    </small>
                </div>
            </div>

            {{-- ── SEKSI SOAL CBT ── --}}
            <div id="sectionCbt" style="display:none;">
                <div style="border-top:2px dashed var(--border);padding-top:20px;margin-top:8px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                        <h4 style="margin:0;font-size:15px;font-weight:700;">
                            <i class="fas fa-list-check" style="color:var(--purple);margin-right:6px;"></i>
                            Daftar Soal Pilihan Ganda
                        </h4>
                        <button type="button" class="btn btn-sm"
                                style="background:var(--purple);color:#fff;border:none;"
                                onclick="tambahSoal()">
                            <i class="fas fa-plus"></i> Tambah Soal
                        </button>
                    </div>

                    <div id="containerSoal">
                        {{-- Render ulang soal lama saat validasi gagal (old input) --}}
                        @if(old('soal'))
                            @foreach(old('soal') as $i => $s)
                            <div class="soal-item" style="border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:14px;position:relative;background:var(--bg);">
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                                    <span style="font-weight:700;font-size:13px;color:var(--purple);">
                                        <i class="fas fa-circle-question"></i> Soal #<span class="soal-num">{{ $i + 1 }}</span>
                                    </span>
                                    <button type="button" onclick="hapusSoal(this)"
                                            style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:14px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <div class="form-group">
                                    <label style="font-size:12px;">Pertanyaan <span style="color:var(--danger);">*</span></label>
                                    <textarea name="soal[{{ $i }}][soal]" rows="2" class="form-control" required>{{ $s['soal'] ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label style="font-size:12px;">Gambar Soal (opsional)</label>
                                    <input type="file" name="soal[{{ $i }}][gambar_soal]"
                                           class="form-control" accept="image/*"
                                           onchange="previewGambar(this)">
                                    <img src="" alt="Preview"
                                         style="display:none;margin-top:8px;max-height:160px;border-radius:6px;border:1px solid var(--border);">
                                </div>

                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                                    @foreach(['a','b','c','d'] as $opt)
                                    <div class="form-group" style="margin-bottom:8px;">
                                        <label style="font-size:12px;">
                                            Pilihan {{ strtoupper($opt) }} <span style="color:var(--danger);">*</span>
                                        </label>
                                        <input type="text" name="soal[{{ $i }}][pilihan_{{ $opt }}]"
                                               value="{{ $s['pilihan_'.$opt] ?? '' }}"
                                               class="form-control" required>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="form-group" style="margin-bottom:0;">
                                    <label style="font-size:12px;">Jawaban Benar <span style="color:var(--danger);">*</span></label>
                                    <select name="soal[{{ $i }}][jawaban_benar]" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach(['A','B','C','D'] as $opt)
                                            <option value="{{ $opt }}" @selected(($s['jawaban_benar'] ?? '') === $opt)>
                                                {{ $opt }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>

                    <div id="emptyState"
                         style="{{ old('soal') ? 'display:none;' : '' }}text-align:center;padding:30px;color:var(--text-muted);border:2px dashed var(--border);border-radius:10px;">
                        <i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;opacity:.5;"></i>
                        <span>Belum ada soal. Klik <strong>+ Tambah Soal</strong> untuk mulai.</span>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Tugas
                </button>
                <a href="{{ route('guru.tugas.index') }}" class="btn"
                   style="background:var(--bg);color:var(--text-secondary);border:1px solid var(--border);">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let soalCount = {{ old('soal') ? count(old('soal')) : 0 }};

// ── Template soal dibuat via JS murni (bukan <template> Blade) ────────────
function buatSoalHTML(idx) {
    return `
    <div class="soal-item" style="border:1px solid var(--border);border-radius:10px;padding:16px;margin-bottom:14px;position:relative;background:var(--bg);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <span style="font-weight:700;font-size:13px;color:var(--purple);">
                <i class="fas fa-circle-question"></i> Soal #<span class="soal-num">${idx + 1}</span>
            </span>
            <button type="button" onclick="hapusSoal(this)"
                    style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:14px;" title="Hapus soal">
                <i class="fas fa-trash"></i>
            </button>
        </div>

        <div class="form-group">
            <label style="font-size:12px;">Pertanyaan <span style="color:var(--danger);">*</span></label>
            <textarea name="soal[${idx}][soal]" rows="2" class="form-control"
                      placeholder="Tulis pertanyaan di sini..." required></textarea>
        </div>

        <div class="form-group">
            <label style="font-size:12px;">Gambar Soal (opsional)</label>
            <input type="file" name="soal[${idx}][gambar_soal]"
                   class="form-control" accept="image/*"
                   onchange="previewGambar(this)">
            <img src="" alt="Preview"
                 style="display:none;margin-top:8px;max-height:160px;border-radius:6px;border:1px solid var(--border);">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            ${['a','b','c','d'].map(opt => `
            <div class="form-group" style="margin-bottom:8px;">
                <label style="font-size:12px;">
                    Pilihan ${opt.toUpperCase()} <span style="color:var(--danger);">*</span>
                </label>
                <input type="text" name="soal[${idx}][pilihan_${opt}]"
                       class="form-control" placeholder="Pilihan ${opt.toUpperCase()}" required>
            </div>`).join('')}
        </div>

        <div class="form-group" style="margin-bottom:0;">
            <label style="font-size:12px;">Jawaban Benar <span style="color:var(--danger);">*</span></label>
            <select name="soal[${idx}][jawaban_benar]" class="form-control" required>
                <option value="">-- Pilih --</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
        </div>
    </div>`;
}

function tambahSoal() {
    const container = document.getElementById('containerSoal');
    const idx       = soalCount++;
    const div       = document.createElement('div');
    div.innerHTML   = buatSoalHTML(idx);
    container.appendChild(div.firstElementChild);
    document.getElementById('emptyState').style.display = 'none';
    renumberSoal();
}

function hapusSoal(btn) {
    btn.closest('.soal-item').remove();
    renumberSoal();
    if (document.querySelectorAll('.soal-item').length === 0) {
        document.getElementById('emptyState').style.display = '';
    }
}

function renumberSoal() {
    document.querySelectorAll('.soal-item').forEach((item, i) => {
        const num = item.querySelector('.soal-num');
        if (num) num.textContent = i + 1;
    });
}

// ── Preview gambar soal ───────────────────────────────────────────────────
function previewGambar(input) {
    const img = input.nextElementSibling;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            img.src           = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        img.src           = '';
        img.style.display = 'none';
    }
}

// ── Toggle tipe tugas ─────────────────────────────────────────────────────
function setTipe(tipe) {
    const cardUpload = document.getElementById('cardUpload');
    const cardCbt    = document.getElementById('cardCbt');
    const secUpload  = document.getElementById('sectionUpload');
    const secCbt     = document.getElementById('sectionCbt');

    if (tipe === 'upload') {
        cardUpload.style.borderColor = 'var(--primary)';
        cardCbt.style.borderColor    = 'var(--border)';
        secUpload.style.display      = '';
        secCbt.style.display         = 'none';
        document.querySelector('input[name="tipe"][value="upload"]').checked = true;
    } else {
        cardCbt.style.borderColor    = 'var(--purple)';
        cardUpload.style.borderColor = 'var(--border)';
        secUpload.style.display      = 'none';
        secCbt.style.display         = '';
        document.querySelector('input[name="tipe"][value="cbt"]').checked = true;
    }
}

// ── Inisiasi saat load ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const oldTipe = '{{ old("tipe", "upload") }}';
    if (oldTipe === 'cbt') {
        setTipe('cbt');
        document.getElementById('emptyState').style.display = 'none';
    }
});
</script>
@endpush

@endsection