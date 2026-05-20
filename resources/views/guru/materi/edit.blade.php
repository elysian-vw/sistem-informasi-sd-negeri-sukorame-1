@extends('layouts.guru')

@section('title', 'Edit Materi')

@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('guru.materi.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="page-title">Edit Materi</h1>
            <p class="page-subtitle">{{ $materi->judul }}</p>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

    <form action="{{ route('guru.materi.update', $materi) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="content-card">
            <div class="card-header">
                <h3><i class="fas fa-pencil-alt" style="color:var(--orange);margin-right:8px;"></i>Edit Informasi Materi</h3>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:18px;">

                {{-- Judul --}}
                <div class="form-group">
                    <label class="form-label">Judul Materi <span class="required">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $materi->judul) }}"
                           class="form-input @error('judul') is-invalid @enderror">
                    @error('judul') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="form-input @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
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
                                <option value="{{ $mp->id }}"
                                    {{ old('mata_pelajaran_id', $materi->mata_pelajaran_id) == $mp->id ? 'selected' : '' }}>
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
                                    <option value="{{ $k->id }}"
                                        {{ old('kelas_id', $materi->kelas_id) == $k->id ? 'selected' : '' }}>
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
                            <option value="materi" {{ old('tipe', $materi->tipe) == 'materi' ? 'selected' : '' }}>Materi Pembelajaran</option>
                            <option value="tugas" {{ old('tipe', $materi->tipe) == 'tugas' ? 'selected' : '' }}>Tugas Siswa</option>
                            <option value="uts" {{ old('tipe', $materi->tipe) == 'uts' ? 'selected' : '' }}>UTS</option>
                            <option value="uas" {{ old('tipe', $materi->tipe) == 'uas' ? 'selected' : '' }}>UAS</option>
                        </select>
                        @error('tipe') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group" id="deadlineBox" style="display:none; margin-bottom: 0;">
                        <label class="form-label">Batas Waktu (Deadline)</label>
                        <input type="datetime-local" name="deadline" value="{{ old('deadline', $materi->deadline ? $materi->deadline->format('Y-m-d\TH:i') : '') }}" class="form-input">
                    </div>
                </div>

                {{-- Format Media --}}
                <div class="form-group">
                    <label class="form-label">Format Media <span class="required">*</span></label>
                    <div class="tipe-selector">
                        @php
                            // Tentukan format media saat ini berdasarkan kolom mana yang terisi di DB
                            $formatSekarang = $materi->file ? 'file' : 'link';
                        @endphp
                        @foreach(['file' => ['label'=>'File Dokumen','icon'=>'fa-file-alt'], 'link' => ['label'=>'Tautan (Link)','icon'=>'fa-link']] as $val => $cfg)
                        <label class="tipe-option {{ old('format_media', $formatSekarang) == $val ? 'active' : '' }}" for="format_{{ $val }}">
                            <input type="radio" name="format_media" id="format_{{ $val }}" value="{{ $val }}"
                                   {{ old('format_media', $formatSekarang) == $val ? 'checked' : '' }}
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
                    <label class="form-label">Ganti File</label>
                    @if($materi->file)
                    <div class="current-file-info" style="display:flex; align-items:center; gap:8px; background:#f1f5f9; padding:8px 12px; border-radius:6px; margin-bottom:8px;">
                        <i class="fas fa-file-alt" style="color:var(--primary);"></i>
                        <span style="font-size:13px;">File saat ini: <strong>{{ basename($materi->file) }}</strong></span>
                        <a href="{{ Storage::url($materi->file) }}" target="_blank" class="btn-action btn-view" style="margin-left:auto; color:var(--primary);">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    @endif
                    <div class="file-drop-area">
                        <i class="fas fa-cloud-upload-alt" style="font-size:24px;color:var(--primary);opacity:.6;margin-bottom:6px;"></i>
                        <div style="font-size:13px;color:var(--text-muted);margin-bottom:8px;">Pilih file baru (kosongkan jika tidak ingin mengganti)</div>
                        <input type="file" name="file"
                               class="@error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip"
                               onchange="showFileName(this)">
                        <div id="file-name" style="margin-top:6px;font-size:12px;color:var(--primary);font-weight:600;"></div>
                    </div>
                    @error('file') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

                {{-- Input Link --}}
                <div id="input-link" class="form-group" style="display:none;">
                    <label class="form-label">URL Tautan</label>
                    <div style="position:relative;">
                        <i class="fas fa-link" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                        <input type="url" name="link_video" value="{{ old('link_video', $materi->link_video) }}"
                               class="form-input @error('link_video') is-invalid @enderror"
                               style="padding-left:36px;"
                               placeholder="https://...">
                    </div>
                    @error('link_video') <div class="form-error" style="color:red; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="card-footer" style="display:flex;gap:10px;padding:16px 20px;border-top:1px solid var(--border);">
                <button type="submit" class="btn-primary-action" style="background:var(--orange,#f9ab00); border-color:var(--orange,#f9ab00);">
                    <i class="fas fa-save"></i> Perbarui Materi
                </button>
                <a href="{{ route('guru.materi.index') }}" class="btn-secondary-action">Batal</a>
            </div>
        </div>
    </form>

    {{-- Info Singkat --}}
    <div class="content-card" style="position:sticky;top:20px;">
        <div class="card-header">
            <h3><i class="fas fa-history" style="color:var(--text-muted);margin-right:8px;"></i>Riwayat</h3>
        </div>
        <div class="card-body">
            <dl style="display:grid;grid-template-columns:auto 1fr;gap:8px 12px;font-size:13px;margin:0;">
                <dt style="color:var(--text-muted);">Dibuat</dt>
                <dd style="margin:0;font-weight:600;">{{ $materi->created_at->format('d M Y, H:i') }}</dd>
                <dt style="color:var(--text-muted);">Diperbarui</dt>
                <dd style="margin:0;font-weight:600;">{{ $materi->updated_at->format('d M Y, H:i') }}</dd>
                <dt style="color:var(--text-muted);">Mata Pelajaran</dt>
                <dd style="margin:0;">{{ $materi->mataPelajaran->nama ?? '-' }}</dd>
                <dt style="color:var(--text-muted);">Kelas</dt>
                <dd style="margin:0;">{{ $materi->kelas->nama_kelas ?? '-' }}</dd>
            </dl>
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
    const checked = document.querySelector('input[name="format_media"]:checked');
    if (checked) toggleTipeInput(checked);

    if (document.getElementById('tipeKategori').value !== 'materi') {
        document.getElementById('deadlineBox').style.display = 'block';
    }
});
</script>
@endpush

@endsection     