@extends('layouts.guru')
@section('title', 'Tambah Materi')

@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('guru.materi.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-title">Tambah Materi</h1>
            <p class="page-subtitle">Unggah atau bagikan materi pembelajaran</p>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

    {{-- Form Utama --}}
    <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="content-card">
            <div class="card-header">
                <h3><i class="fas fa-book" style="color:var(--primary);margin-right:8px;"></i>Informasi Materi</h3>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

                {{-- Judul --}}
                <div class="form-group">
                    <label class="form-label">Judul Materi <span class="required">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}"
                           class="form-input @error('judul') is-invalid @enderror"
                           placeholder="Contoh: Pengenalan Bilangan Bulat">
                    @error('judul') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="form-input @error('deskripsi') is-invalid @enderror"
                              placeholder="Penjelasan singkat tentang materi ini...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                {{-- Mata Pelajaran & Kelas --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Mata Pelajaran <span class="required">*</span></label>
                        <select name="mata_pelajaran_id"
                                class="form-input @error('mata_pelajaran_id') is-invalid @enderror">
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($mapel as $mp)
                                <option value="{{ $mp->id }}" {{ old('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>
                                    {{ $mp->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('mata_pelajaran_id') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Kelas <span class="required">*</span></label>
                        @if($kelas->count() === 1)
                            {{-- Guru punya kelas tetap — lock --}}
                            <select name="kelas_id"
                                    class="form-input"
                                    style="background-color:#f8f9fa;cursor:not-allowed;pointer-events:none;">
                                <option value="{{ $kelas->first()->id }}" selected>
                                    Kelas {{ $kelas->first()->nama_kelas }} (Kelas Anda)
                                </option>
                            </select>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                                * Terkunci pada kelas yang Anda ajar.
                            </div>
                        @else
                            {{-- Guru mulok — bebas pilih --}}
                            <select name="kelas_id"
                                    class="form-input @error('kelas_id') is-invalid @enderror">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        Kelas {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <div class="form-error" style="color:red;font-size:12px;margin-top:4px;">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>
                </div>

                {{-- Kategori & Deadline --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px; background:#f8fafc; padding:16px; border-radius:8px; border:1px solid #e2e8f0;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="tipe" id="tipeKategori" class="form-input @error('tipe') is-invalid @enderror">
                            <option value="materi" {{ old('tipe') == 'materi' ? 'selected' : '' }}>Materi Pembelajaran</option>
                            <option value="tugas" {{ old('tipe') == 'tugas' ? 'selected' : '' }}>Tugas Siswa</option>
                            <option value="uts" {{ old('tipe') == 'uts' ? 'selected' : '' }}>UTS</option>
                            <option value="uas" {{ old('tipe') == 'uas' ? 'selected' : '' }}>UAS</option>
                        </select>
                        @error('tipe') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group" id="deadlineBox" style="display:none; margin-bottom: 0;">
                        <label class="form-label">Batas Waktu (Deadline)</label>
                        <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" class="form-input">
                    </div>
                </div>

                {{-- Tipe Media --}}
                <div class="form-group">
                    <label class="form-label">Format Media <span class="required">*</span></label>
                    <div class="tipe-selector">
                        @foreach(['file' => ['label'=>'File Dokumen','icon'=>'fa-file-alt'], 'link' => ['label'=>'Tautan (Link)','icon'=>'fa-link']] as $val => $cfg)
                        <label class="tipe-option {{ old('format_media', 'file') == $val ? 'active' : '' }}" for="format_{{ $val }}">
                            <input type="radio" name="format_media" id="format_{{ $val }}" value="{{ $val }}"
                                   {{ old('format_media', 'file') == $val ? 'checked' : '' }}
                                   onchange="toggleTipeInput(this)">
                            <i class="fas {{ $cfg['icon'] }}"></i>
                            <span>{{ $cfg['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('format_media') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                {{-- Input File --}}
                <div id="input-file" class="form-group">
                    <label class="form-label">Upload File</label>
                    <div class="file-drop-area">
                        <i class="fas fa-cloud-upload-alt" style="font-size:28px;color:var(--primary);opacity:.6;margin-bottom:8px;"></i>
                        <div style="font-size:13px;color:var(--text-muted);margin-bottom:10px;">
                            Seret file ke sini atau klik untuk memilih
                        </div>
                        <input type="file" name="file" id="fileInput"
                               class="@error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip"
                               onchange="showFileName(this)">
                        <div id="file-name" style="margin-top:8px;font-size:12px;color:var(--primary);font-weight:600;"></div>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">PDF, Word, PowerPoint, Excel, ZIP — maks. 20 MB</div>
                    </div>
                    @error('file') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                {{-- Input Link --}}
                <div id="input-link" class="form-group" style="display:none;">
                    <label class="form-label">URL Tautan</label>
                    <div style="position:relative;">
                        <i class="fas fa-link" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                        <input type="url" name="link_video" value="{{ old('link_video') }}"
                               class="form-input @error('link_video') is-invalid @enderror"
                               style="padding-left:36px;"
                               placeholder="https://...">
                    </div>
                    @error('link_video') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="card-footer" style="display:flex;gap:10px;padding:16px 20px;border-top:1px solid var(--border);">
                <button type="submit" class="btn-primary-action">
                    <i class="fas fa-save"></i> Simpan Materi
                </button>
                <a href="{{ route('guru.materi.index') }}" class="btn-secondary-action">Batal</a>
            </div>
        </div>
    </form>

    {{-- Panduan --}}
    <div class="content-card" style="position:sticky;top:20px;">
        <div class="card-header">
            <h3><i class="fas fa-info-circle" style="color:var(--primary);margin-right:8px;"></i>Panduan</h3>
        </div>
        <div class="card-body">
            <div class="guide-item">
                <div class="guide-icon" style="background:#e8f0fe;color:var(--primary);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <div style="font-weight:600;font-size:13px;margin-bottom:2px;">File Dokumen</div>
                    <div style="font-size:12px;color:var(--text-muted);">Unggah PDF, Word, PowerPoint, atau ZIP.</div>
                </div>
            </div>
            <div class="guide-item">
                <div class="guide-icon" style="background:#fce8e6;color:var(--danger);">
                    <i class="fas fa-link"></i>
                </div>
                <div>
                    <div style="font-weight:600;font-size:13px;margin-bottom:2px;">Tautan (Link)</div>
                    <div style="font-size:12px;color:var(--text-muted);">Tautkan Google Drive, Docs, atau web lain.</div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function toggleTipeInput(radio) {
    const tipe = radio.value;
    const fileDiv = document.getElementById('input-file');
    const linkDiv = document.getElementById('input-link');

    fileDiv.style.display = tipe === 'file' ? 'block' : 'none';
    linkDiv.style.display = tipe === 'link' ? 'block' : 'none';

    document.querySelectorAll('.tipe-option').forEach(l => l.classList.remove('active'));
    radio.closest('.tipe-option').classList.add('active');
}

function showFileName(input) {
    const display = document.getElementById('file-name');
    display.textContent = input.files[0] ? '📎 ' + input.files[0].name : '';
}

document.getElementById('tipeKategori').addEventListener('change', function() {
    document.getElementById('deadlineBox').style.display = this.value === 'materi' ? 'none' : 'block';
});

document.addEventListener('DOMContentLoaded', function() {
    const checkedMedia = document.querySelector('input[name="format_media"]:checked');
    if (checkedMedia) toggleTipeInput(checkedMedia);
    
    if (document.getElementById('tipeKategori').value !== 'materi') {
        document.getElementById('deadlineBox').style.display = 'block';
    }
});
</script>
@endpush
@endsection