@extends('layouts.guru')
@section('title', 'Kelola Soal — ' . $tugas->judul)

@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('guru.tugas.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="page-title">Kelola Soal CBT</h1>
            <p class="page-subtitle">
                <a href="{{ route('guru.tugas.index') }}">Tugas</a>
                &nbsp;/&nbsp; {{ Str::limit($tugas->judul, 40) }}
                &nbsp;·&nbsp; {{ $tugas->kelas->nama_kelas ?? '-' }}
            </p>
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('guru.tugas.penilaian', $tugas) }}"
           class="btn btn-sm" style="background:#f3e5f5;color:var(--purple);border:1px solid #e1bee7;">
            <i class="fas fa-star"></i> Lihat Hasil
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert-success-toast"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
<div class="alert alert-danger"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
@endif

{{-- ── STAT ── --}}
<div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
    <div class="stat-card stat-purple" style="flex:1;min-width:140px;">
        <div class="stat-icon"><i class="fas fa-circle-question"></i></div>
        <div>
            <span class="stat-number">{{ $pertanyaans->count() }}</span>
            <span class="stat-label">Total Soal</span>
        </div>
    </div>
    <div class="stat-card stat-blue" style="flex:1;min-width:140px;">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div>
            <span class="stat-number">{{ $tugas->pengumpulan->count() }}</span>
            <span class="stat-label">Sudah Mengerjakan</span>
        </div>
    </div>
    <div class="stat-card stat-green" style="flex:1;min-width:140px;">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div>
            <span class="stat-number">{{ $tugas->deadline ? $tugas->deadline->format('d/m/Y') : '—' }}</span>
            <span class="stat-label">Deadline</span>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;">

    {{-- ── DAFTAR SOAL ── --}}
    <div>
        <div class="content-card">
            <div class="card-header">
                <h3><i class="fas fa-list-check" style="color:var(--purple);margin-right:8px;"></i>Daftar Soal</h3>
            </div>

            @forelse($pertanyaans as $i => $p)
            <div class="soal-card" id="soal-{{ $p->id }}"
                 style="border-bottom:1px solid var(--border);padding:16px 20px;position:relative;">

                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <div style="flex:1;">
                        <div style="font-size:12px;font-weight:700;color:var(--purple);margin-bottom:6px;">
                            Soal {{ $i + 1 }}
                        </div>
                        <p style="margin:0 0 8px;font-size:14px;font-weight:500;">{{ $p->soal }}</p>

                        @if($p->gambar_soal)
                        <img src="{{ asset('storage/' . $p->gambar_soal) }}"
                             style="max-width:260px;border-radius:8px;border:1px solid var(--border);margin-bottom:10px;">
                        @endif

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;font-size:13px;">
                            @foreach(['a','b','c','d'] as $opt)
                            <div style="display:flex;align-items:center;gap:6px;padding:5px 8px;border-radius:6px;
                                        background:{{ $p->jawaban_benar === strtoupper($opt) ? 'var(--success-light, #dcfce7)' : 'var(--bg)' }};
                                        border:1px solid {{ $p->jawaban_benar === strtoupper($opt) ? 'var(--success, #16a34a)' : 'var(--border)' }};">
                                <span style="width:20px;height:20px;border-radius:50%;font-size:11px;font-weight:700;
                                             display:flex;align-items:center;justify-content:center;flex-shrink:0;
                                             background:{{ $p->jawaban_benar === strtoupper($opt) ? 'var(--success, #16a34a)' : 'var(--border)' }};
                                             color:{{ $p->jawaban_benar === strtoupper($opt) ? '#fff' : 'var(--text-secondary)' }};">
                                    {{ strtoupper($opt) }}
                                </span>
                                {{ $p->{'pilihan_' . $opt} }}
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
                        <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="openEdit({{ $p->id }})">
                            <i class="fas fa-pen"></i>
                        </button>
                        <form action="{{ route('guru.tugas.soal.destroy', [$tugas, $p]) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                    onclick="if(confirm('Hapus soal ini?')) this.closest('form').submit()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Form edit inline (tersembunyi) --}}
                <div id="editForm-{{ $p->id }}" style="display:none;margin-top:14px;border-top:1px solid var(--border);padding-top:14px;">
                    <form action="{{ route('guru.tugas.soal.update', [$tugas, $p]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label style="font-size:12px;">Pertanyaan</label>
                            <textarea name="soal" rows="2" class="form-control" required>{{ $p->soal }}</textarea>
                        </div>
                        <div class="form-group">
                            <label style="font-size:12px;">Ganti Gambar (opsional)</label>
                            <input type="file" name="gambar_soal" class="form-control" accept="image/*">
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            @foreach(['a','b','c','d'] as $opt)
                            <div class="form-group" style="margin-bottom:6px;">
                                <label style="font-size:12px;">Pilihan {{ strtoupper($opt) }}</label>
                                <input type="text" name="pilihan_{{ $opt }}"
                                       value="{{ $p->{'pilihan_' . $opt} }}"
                                       class="form-control" required>
                            </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label style="font-size:12px;">Jawaban Benar</label>
                            <select name="jawaban_benar" class="form-control" required>
                                @foreach(['A','B','C','D'] as $opt)
                                <option value="{{ $opt }}" @selected($p->jawaban_benar===$opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display:flex;gap:8px;">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                            <button type="button" class="btn btn-sm" onclick="closeEdit({{ $p->id }})"
                                    style="background:var(--bg);border:1px solid var(--border);">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:40px;color:var(--text-muted);">
                <i class="fas fa-inbox" style="font-size:32px;display:block;margin-bottom:8px;opacity:.5;"></i>
                Belum ada soal. Tambahkan dari panel kanan.
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── FORM TAMBAH SOAL ── --}}
    <div style="position:sticky;top:20px;">
        <div class="content-card">
            <div class="card-header">
                <h3><i class="fas fa-plus" style="color:var(--purple);margin-right:8px;"></i>Tambah Soal Baru</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('guru.tugas.soal.store', $tugas) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label style="font-size:12px;">Pertanyaan <span style="color:var(--danger);">*</span></label>
                        <textarea name="soal" rows="3" class="form-control"
                                  placeholder="Tulis pertanyaan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label style="font-size:12px;">Gambar Soal (opsional)</label>
                        <input type="file" name="gambar_soal" class="form-control" accept="image/*">
                    </div>

                    @foreach(['a','b','c','d'] as $opt)
                    <div class="form-group" style="margin-bottom:8px;">
                        <label style="font-size:12px;">Pilihan {{ strtoupper($opt) }} <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="pilihan_{{ $opt }}" class="form-control"
                               placeholder="Isi pilihan {{ strtoupper($opt) }}" required>
                    </div>
                    @endforeach

                    <div class="form-group">
                        <label style="font-size:12px;">Jawaban Benar <span style="color:var(--danger);">*</span></label>
                        <select name="jawaban_benar" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;background:var(--purple);border-color:var(--purple);">
                        <i class="fas fa-plus"></i> Tambah Soal
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function openEdit(id) {
    document.querySelectorAll('[id^="editForm-"]').forEach(el => el.style.display = 'none');
    document.getElementById('editForm-' + id).style.display = '';
    document.getElementById('soal-' + id).scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
function closeEdit(id) {
    document.getElementById('editForm-' + id).style.display = 'none';
}
</script>
@endpush

@endsection