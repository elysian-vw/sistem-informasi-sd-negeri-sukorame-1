@extends('layouts.app')
@section('title', 'Pengumuman')

@section('content')
<div class="page-header">
    <h1 class="page-title">Pengumuman</h1>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Stat Cards --}}
<div class="stat-row">
    <div class="stat-mini">
        <span class="stat-mini-label">Total Pengumuman</span>
        <span class="stat-mini-value">{{ $total }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Aktif / Tayang</span>
        <span class="stat-mini-value">{{ $totalAktif }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Draft / Belum Tayang</span>
        <span class="stat-mini-value">{{ $totalDraft }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Arsip</span>
        <span class="stat-mini-value">{{ $totalArsip }}</span>
    </div>
</div>

{{-- Filter Toolbar --}}
<form method="GET" action="{{ route('admin.pengumuman.index') }}">
    <input type="hidden" name="tab" value="{{ $tab }}">
    <div class="toolbar">
        <div class="toolbar-filters">
            <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari judul...">

            <select name="kategori" class="form-control">
                <option value="">Kategori</option>
                @foreach($kategoriList as $k)
                    <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>

            <select name="untuk" class="form-control">
                <option value="">Target</option>
                <option value="semua" {{ request('untuk') == 'semua' ? 'selected' : '' }}>Semua</option>
                <option value="guru" {{ request('untuk') == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="siswa" {{ request('untuk') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="wali_murid" {{ request('untuk') == 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
            </select>

            <select name="status" class="form-control">
                <option value="">Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="arsip" {{ request('status') == 'arsip' ? 'selected' : '' }}>Arsip</option>
            </select>

            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
            @if(request('cari') || request('kategori') || request('untuk') || request('status'))
                <a href="{{ route('admin.pengumuman.index', ['tab' => $tab]) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Main Grid --}}
<div class="main-grid">
    {{-- Kolom Kiri: Daftar --}}
    <div class="content-card">
        <div class="card-header-plain">
            <span class="card-title">Daftar Pengumuman</span>
        </div>

        {{-- Sub Tab --}}
        <div class="sub-tab">
            @foreach(['semua' => 'Semua', 'aktif' => 'Aktif', 'draft' => 'Draft', 'arsip' => 'Arsip'] as $key => $label)
                <a href="{{ route('admin.pengumuman.index', array_merge(request()->except('tab', 'page'), ['tab' => $key])) }}"
                   class="sub-tab-btn {{ $tab === $key ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="pengumuman-list">
            @forelse($pengumuman as $p)
                <div class="pengumuman-item">
                    <div class="pengumuman-main">
                        <div class="pengumuman-meta">
                            <span class="badge {{ $p->status === 'aktif' ? 'badge-success' : ($p->status === 'draft' ? 'badge-warning' : 'badge-gray') }}">
                                {{ ucfirst($p->status) }}
                            </span>
                            @if($p->kategori)
                                <span class="badge badge-blue">{{ $p->kategori }}</span>
                            @endif
                            <span class="badge badge-light">{{ ucfirst($p->untuk) }}</span>
                        </div>
                        <h4 class="pengumuman-judul">{{ $p->judul }}</h4>
                        <p class="pengumuman-preview">{{ Str::limit($p->isi, 100) }}</p>
                        <span class="pengumuman-time">
                            <i class="fas fa-user"></i> {{ $p->user?->name }} &middot;
                            {{ $p->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <div class="text-center">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary border-0" title="Edit Data"
                                onclick="openEdit({{ json_encode($p) }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.pengumuman.destroy', $p->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="empty-state">Belum ada pengumuman.</p>
            @endforelse
        </div>

        @if($pengumuman->hasPages())
            <div class="pagination-wrap">{{ $pengumuman->links() }}</div>
        @endif
    </div>

    {{-- Kolom Kanan: Form --}}
    <div class="content-card form-card">
        <div class="form-card-header" id="form-header">
            <h3 id="form-title">Buat Pengumuman Baru</h3>
        </div>
        <form id="form-pengumuman" method="POST" action="{{ route('admin.pengumuman.store') }}">
            @csrf
            <span id="method-field"></span>

            <div class="form-group">
                <label class="form-label">Judul <span class="required">*</span></label>
                <input type="text" name="judul" id="f-judul" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Isi Pengumuman <span class="required">*</span></label>
                <textarea name="isi" id="f-isi" class="form-control" rows="5" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Target <span class="required">*</span></label>
                    <select name="untuk" id="f-untuk" class="form-control" required>
                        <option value="semua">Semua</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                        <option value="wali_murid">Wali Murid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" id="f-kategori" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k }}">{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status <span class="required">*</span></label>
                <select name="status" id="f-status" class="form-control" required>
                    <option value="aktif">Aktif (Tayang)</option>
                    <option value="draft">Draft (Belum Tayang)</option>
                    <option value="arsip">Arsip</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-times"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.stat-row { display:flex; gap:16px; margin-bottom:20px; flex-wrap:wrap; }
.stat-mini { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:16px 24px; min-width:140px; display:flex; flex-direction:column; gap:4px; flex:1; }
.stat-mini-label { font-size:12px; color:#6b7280; font-weight:500; }
.stat-mini-value { font-size:26px; font-weight:700; color:#111827; }

.toolbar { padding:0 0 16px; }
.toolbar-filters { display:flex; gap:8px; flex-wrap:wrap; align-items:center; }
.toolbar-filters .form-control { height:36px; font-size:13px; }
.toolbar-filters input { min-width:180px; }
.toolbar-filters select { min-width:120px; }

.main-grid { display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start; }
@media(max-width:900px) { .main-grid { grid-template-columns:1fr; } }

.card-header-plain { padding:16px 20px; border-bottom:1px solid #f3f4f6; }
.card-title { font-size:14px; font-weight:600; color:#111827; }

/* Sub Tab */
.sub-tab { display:flex; gap:4px; padding:12px 20px; border-bottom:1px solid #f3f4f6; }
.sub-tab-btn { padding:5px 14px; border-radius:20px; font-size:12.5px; font-weight:500; text-decoration:none; color:#6b7280; background:#f3f4f6; transition:all .15s; }
.sub-tab-btn.active { background:#2563eb; color:#fff; }
.sub-tab-btn:hover:not(.active) { background:#e5e7eb; }

/* Pengumuman List */
.pengumuman-list { padding:8px 0; }
.pengumuman-item { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; padding:16px 20px; border-bottom:1px solid #f3f4f6; transition:background .15s; }
.pengumuman-item:hover { background:#f9fafb; }
.pengumuman-main { flex:1; }
.pengumuman-meta { display:flex; gap:6px; flex-wrap:wrap; margin-bottom:6px; }
.pengumuman-judul { font-size:14px; font-weight:600; color:#111827; margin:0 0 4px; }
.pengumuman-preview { font-size:13px; color:#6b7280; margin:0 0 6px; line-height:1.5; }
.pengumuman-time { font-size:12px; color:#9ca3af; }
.pengumuman-actions { display:flex; gap:6px; flex-shrink:0; }

.badge { display:inline-flex; padding:2px 9px; border-radius:20px; font-size:11px; font-weight:500; }
.badge-success { background:#dcfce7; color:#15803d; }
.badge-warning { background:#fef9c3; color:#854d0e; }
.badge-gray    { background:#f3f4f6; color:#6b7280; }
.badge-blue    { background:#dbeafe; color:#1d4ed8; }
.badge-light   { background:#f3f4f6; color:#374151; }

.empty-state { text-align:center; color:#9ca3af; padding:40px; font-size:13.5px; }
.pagination-wrap { padding:16px 20px; border-top:1px solid #f3f4f6; }

/* Form Card */
.form-card { padding:0; }
.form-card-header { padding:16px 20px; border-bottom:1px solid #f3f4f6; }
.form-card-header h3 { font-size:14px; font-weight:600; color:#111827; margin:0; }
.form-card-header.edit-mode { background:#fffbeb; }
.form-card-header.edit-mode h3 { color:#92400e; }
.form-card form { padding:20px; }

.form-row { display:flex; gap:12px; }
.form-row .form-group { flex:1; }
.form-group { margin-bottom:14px; }
.form-label { display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:5px; }
.required { color:#ef4444; }
.form-control { width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13.5px; color:#111827; background:#fff; box-sizing:border-box; transition:border-color .2s,box-shadow .2s; }
.form-control:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
textarea.form-control { resize:vertical; }
.form-actions { display:flex; gap:8px; justify-content:flex-end; margin-top:8px; }

.alert { padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
.alert-success { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
</style>

<script>
function openEdit(p) {
    document.getElementById('form-title').textContent = 'Edit Pengumuman';
    document.getElementById('form-header').classList.add('edit-mode');
    document.getElementById('form-pengumuman').action = `/admin/pengumuman/${p.id}`;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

    document.getElementById('f-judul').value    = p.judul ?? '';
    document.getElementById('f-isi').value      = p.isi ?? '';
    document.getElementById('f-untuk').value    = p.untuk ?? 'semua';
    document.getElementById('f-kategori').value = p.kategori ?? '';
    document.getElementById('f-status').value   = p.status ?? 'aktif';

    document.querySelector('.form-card').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('form-title').textContent = 'Buat Pengumuman Baru';
    document.getElementById('form-header').classList.remove('edit-mode');
    document.getElementById('form-pengumuman').action = '{{ route("admin.pengumuman.store") }}';
    document.getElementById('method-field').innerHTML = '';
    document.getElementById('form-pengumuman').reset();
}
</script>
@endsection