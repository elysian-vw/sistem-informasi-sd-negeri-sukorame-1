@extends('layouts.app')
@section('title', 'Mata Pelajaran')

@section('content')
<div class="page-header">
    <h1 class="page-title">Mata Pelajaran</h1>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Stat Cards --}}
<div class="stat-row">
    <div class="stat-mini">
        <span class="stat-mini-label">Total Mata Pelajaran</span>
        <span class="stat-mini-value">{{ $totalMapel }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Mapel Wajib</span>
        <span class="stat-mini-value">{{ $mapelWajib }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Muatan Lokal</span>
        <span class="stat-mini-value">{{ $muatanLokal }}</span>
    </div>
</div>

<div class="main-grid">
    {{-- Kolom Kiri: Tabel --}}
    <div class="content-card">
        {{-- Toolbar Filter --}}
        <div class="toolbar">
            <form method="GET" action="{{ route('admin.mata-pelajaran.index') }}" class="toolbar-filters">
                <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama / kode...">

                <select name="jenis" class="form-control">
                    <option value="">Jenis Mapel</option>
                    <option value="wajib" {{ request('jenis') == 'wajib' ? 'selected' : '' }}>Wajib</option>
                    <option value="mulok" {{ request('jenis') == 'mulok' ? 'selected' : '' }}>Muatan Lokal</option>
                </select>

                <select name="tingkat" class="form-control">
                    <option value="">Tingkat Kelas</option>
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ request('tingkat') == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                    @endfor
                </select>

                <select name="status" class="form-control">
                    <option value="">Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>

                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request('cari') || request('jenis') || request('tingkat') || request('status'))
                    <a href="{{ route('admin.mata-pelajaran.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>

        {{-- Tabel --}}
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mapel</th>
                        <th>Jenis</th>
                        <th>Kelas</th>
                        <th>KKM</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mapel as $i => $m)
                        <tr>
                            <td>{{ $mapel->firstItem() + $i }}</td>
                            <td>
                                <div class="mapel-info">
                                    <span class="mapel-kode">{{ $m->kode }}</span>
                                    <span class="mapel-nama">{{ $m->nama }}</span>
                                    @if($m->guru)
                                        <span class="mapel-guru"><i class="fas fa-chalkboard-teacher"></i> {{ $m->guru->user?->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $m->jenis === 'wajib' ? 'badge-blue' : 'badge-orange' }}">
                                    {{ $m->jenis === 'wajib' ? 'Wajib' : 'Mulok' }}
                                </span>
                            </td>
                            <td>Kelas {{ $m->tingkat }}</td>
                            <td>{{ $m->kkm }}</td>
                            <td>
                                <span class="badge {{ $m->status === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                    {{ $m->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary border-0" title="Edit Data"
                                        onclick="openModalEdit({{ json_encode($m) }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.mata-pelajaran.destroy', $m->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">Belum ada data mata pelajaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mapel->hasPages())
            <div class="pagination-wrap">{{ $mapel->links() }}</div>
        @endif
    </div>

    {{-- Kolom Kanan: Form Tambah --}}
    <div class="content-card form-card">
        <div class="form-card-header">
            <h3 id="form-title">Tambah Mata Pelajaran</h3>
        </div>
        <form id="form-mapel" method="POST" action="{{ route('admin.mata-pelajaran.store') }}">
            @csrf
            <span id="method-field"></span>

            <div class="form-group">
                <label class="form-label">Kode <span class="required">*</span></label>
                <input type="text" name="kode" id="f-kode" class="form-control" placeholder="cth: MTK1, BIN2" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Mata Pelajaran <span class="required">*</span></label>
                <input type="text" name="nama" id="f-nama" class="form-control" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Jenis <span class="required">*</span></label>
                    <select name="jenis" id="f-jenis" class="form-control" required>
                        <option value="wajib">Wajib</option>
                        <option value="mulok">Muatan Lokal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tingkat Kelas <span class="required">*</span></label>
                    <select name="tingkat" id="f-tingkat" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}">Kelas {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">KKM <span class="required">*</span></label>
                    <input type="number" name="kkm" id="f-kkm" class="form-control" value="70" min="0" max="100" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" id="f-status" class="form-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pengampu (Guru)</label>
                <select name="guru_id" id="f-guru" class="form-control">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guruList as $g)
                        <option value="{{ $g->id }}">{{ $g->user?->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="btn-reset" onclick="resetForm()">
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
/* Layout */
.main-grid { display:grid; grid-template-columns: 1fr 320px; gap:20px; align-items:start; }
@media(max-width:900px) { .main-grid { grid-template-columns:1fr; } }

/* Stat */
.stat-row { display:flex; gap:16px; margin-bottom:20px; flex-wrap:wrap; }
.stat-mini { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:16px 24px; min-width:160px; display:flex; flex-direction:column; gap:4px; flex:1; }
.stat-mini-label { font-size:12.5px; color:#6b7280; font-weight:500; }
.stat-mini-value { font-size:26px; font-weight:700; color:#111827; }

/* Toolbar */
.toolbar { padding:16px 20px; border-bottom:1px solid #f3f4f6; }
.toolbar-filters { display:flex; gap:8px; flex-wrap:wrap; align-items:center; }
.toolbar-filters .form-control { height:36px; font-size:13px; }
.toolbar-filters input { min-width:160px; }
.toolbar-filters select { min-width:130px; }

/* Table */
.table-wrapper { overflow-x:auto; }
.data-table { width:100%; border-collapse:collapse; font-size:13.5px; }
.data-table th { background:#f9fafb; padding:11px 16px; text-align:left; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#6b7280; border-bottom:1px solid #e5e7eb; white-space:nowrap; }
.data-table td { padding:12px 16px; border-bottom:1px solid #f3f4f6; vertical-align:middle; color:#374151; }
.data-table tbody tr:hover { background:#f9fafb; }

.mapel-info { display:flex; flex-direction:column; gap:2px; }
.mapel-kode { font-size:11px; font-weight:600; color:#9ca3af; text-transform:uppercase; }
.mapel-nama { font-weight:500; color:#111827; }
.mapel-guru { font-size:12px; color:#6b7280; }

.badge { display:inline-flex; padding:3px 10px; border-radius:20px; font-size:11.5px; font-weight:500; }
.badge-success { background:#dcfce7; color:#15803d; }
.badge-danger  { background:#fee2e2; color:#b91c1c; }
.badge-blue    { background:#dbeafe; color:#1d4ed8; }
.badge-orange  { background:#ffedd5; color:#c2410c; }

.empty-state { text-align:center; color:#9ca3af; padding:40px; }
.pagination-wrap { padding:16px 20px; border-top:1px solid #f3f4f6; }

/* Form Card */
.form-card { padding:0; }
.form-card-header { padding:16px 20px; border-bottom:1px solid #f3f4f6; }
.form-card-header h3 { font-size:14px; font-weight:600; color:#111827; margin:0; }
.form-card form { padding:20px; }

.form-row { display:flex; gap:12px; }
.form-row .form-group { flex:1; }
.form-group { margin-bottom:14px; }
.form-label { display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:5px; }
.required { color:#ef4444; }
.form-control { width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13.5px; color:#111827; background:#fff; box-sizing:border-box; transition:border-color .2s,box-shadow .2s; }
.form-control:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
.form-actions { display:flex; gap:8px; justify-content:flex-end; margin-top:8px; }

.alert { padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
.alert-success { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }

/* Edit mode indicator */
.form-card-header.edit-mode { background:#fffbeb; }
.form-card-header.edit-mode h3 { color:#92400e; }
</style>

<script>
function openModalEdit(mapel) {
    document.getElementById('form-title').textContent = 'Edit Mata Pelajaran';
    document.getElementById('form-mapel').action = `/admin/mata-pelajaran/${mapel.id}`;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.querySelector('.form-card-header').classList.add('edit-mode');

    document.getElementById('f-kode').value    = mapel.kode ?? '';
    document.getElementById('f-nama').value    = mapel.nama ?? '';
    document.getElementById('f-jenis').value   = mapel.jenis ?? 'wajib';
    document.getElementById('f-tingkat').value = mapel.tingkat ?? '';
    document.getElementById('f-kkm').value     = mapel.kkm ?? 70;
    document.getElementById('f-status').value  = mapel.status ?? 'aktif';
    document.getElementById('f-guru').value    = mapel.guru_id ?? '';

    // Scroll ke form
    document.querySelector('.form-card').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('form-title').textContent = 'Tambah Mata Pelajaran';
    document.getElementById('form-mapel').action = '{{ route("admin.mata-pelajaran.store") }}';
    document.getElementById('method-field').innerHTML = '';
    document.querySelector('.form-card-header').classList.remove('edit-mode');
    document.getElementById('form-mapel').reset();
    document.getElementById('f-kkm').value = 70;
}
</script>
@endsection