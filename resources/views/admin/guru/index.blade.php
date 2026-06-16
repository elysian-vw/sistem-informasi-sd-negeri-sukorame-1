@extends('layouts.app')
@section('title', 'Kelola Data Guru')

@section('content')
<div class="page-header">
    <h1 class="page-title">Kelas & Kelola Data Guru</h1>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Stat Cards --}}
<div class="stat-row">
    <div class="stat-mini">
        <span class="stat-mini-label">Total Guru</span>
        <span class="stat-mini-value">{{ $totalGuru }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Guru Aktif</span>
        <span class="stat-mini-value">{{ $guruAktif }}</span>
    </div>
</div>

<div class="content-card">
    {{-- Toolbar --}}
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.guru.index') }}" class="toolbar-filters">
            <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari nama / NIP...">

            <select name="status" class="form-control">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>

            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>

        <div class="toolbar-actions">
            <button class="btn btn-secondary" onclick="document.getElementById('modal-import').classList.add('show')">
                <i class="fas fa-file-import"></i> Import
            </button>
            <a href="{{ route('admin.guru.export') }}" class="btn btn-secondary">
                <i class="fas fa-file-export"></i> Export
            </a>
            <button class="btn btn-primary" onclick="openModalTambah()">
                <i class="fas fa-plus"></i> Tambah Guru
            </button>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Kelas Tugas</th>
                    <th>Mata Pelajaran</th>
                    <th>No HP</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guru as $g)
                    <tr>
                        <td>{{ $g->nip ?? '-' }}</td>
                        <td>
                            <div class="guru-info">
                                @if($g->user?->foto)
                                    <img src="{{ Storage::url($g->user->foto) }}" class="guru-avatar">
                                @else
                                    <div class="guru-avatar-placeholder">
                                        {{ strtoupper(substr($g->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="guru-name">{{ $g->user?->name ?? '-' }}</div>
                                    <div class="guru-email">{{ $g->user?->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $g->jabatan ?? '-' }}</td>
                        <td>
                            @if($g->kelas_id)
                                <span class="badge badge-success" style="background:#e0f2fe; color:#0284c7;">
                                    Kelas {{ $g->kelas->nama_kelas ?? '-' }}
                                </span>
                            @else
                                <span class="badge badge-danger">Belum diset</span>
                            @endif
                        </td>
                        <td>{{ $g->mata_pelajaran ?? '-' }}</td>
                        <td>{{ $g->user?->no_hp ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $g->status === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                {{ $g->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary border-0" title="Edit Data"
                                    onclick="openModalEdit({{ json_encode([
                                        'id'             => $g->id,
                                        'name'           => $g->user?->name,
                                        'email'          => $g->user?->email,
                                        'no_hp'          => $g->user?->no_hp,
                                        'nip'            => $g->nip,
                                        'kelas_id'       => $g->kelas_id,
                                        'mata_pelajaran' => $g->mata_pelajaran,
                                        'jabatan'        => $g->jabatan,
                                        'status'         => $g->status,
                                    ]) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Hapus data {{ $g->user?->name }}?')">
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
                        <td colspan="7" class="empty-state">Belum ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($guru->hasPages())
        <div class="pagination-wrap">{{ $guru->links() }}</div>
    @endif
</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div class="modal-overlay" id="modal-guru">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Guru</h3>
            <button class="modal-close" onclick="closeModal('modal-guru')">&times;</button>
        </div>
        <form id="form-guru" method="POST" enctype="multipart/form-data">
            @csrf
            <span id="method-field"></span>

            <div class="modal-body">
                <div class="form-section-title">Data Akun</div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="name" id="f-name" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" id="f-email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No HP</label>
                        <input type="text" name="no_hp" id="f-nohp" class="form-control">
                    </div>
                </div>
                <div class="form-group" id="pass-group">
                    <label class="form-label">Password <span class="required" id="pass-req">*</span></label>
                    <input type="password" name="password" id="f-password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    <small class="form-hint" id="pass-hint" style="display:none">Kosongkan jika tidak ingin mengubah password</small>
                </div>

                <div class="form-section-title" style="margin-top:16px">Data Kepegawaian & Penugasan</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" id="f-nip" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tugas Kelas <span class="required">*</span></label>
                        <select name="kelas_id" id="f-kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">Kelas {{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                            @endforeach
                        </select>
                        <small class="form-hint">Agar guru bisa mengupload materi ke kelas ini.</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="mata_pelajaran" id="f-mapel" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach($mapelList as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jabatan Struktural</label>
                        <select name="jabatan" id="f-jabatan" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach($jabatanList as $j)
                                <option value="{{ $j }}">{{ $j }}</option>
                            @endforeach
                        </select>
                        <small class="form-hint">Muncul di Bagan Struktur Organisasi.</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" id="f-status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="form-group"></div>
                </div>
                
                {{-- Keterangan Upload Foto --}}
                <div class="form-group">
                    <label class="form-label">Upload Foto</label>
                    <input type="file" name="foto" class="form-control" accept="image/png, image/jpeg, image/jpg">
                    <small class="form-hint" style="color:#6b7280; margin-top:6px; display:block;">
                        <i class="fas fa-info-circle"></i> Format yang diizinkan: JPG, JPEG, PNG. Maksimal ukuran 2MB.
                    </small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-guru')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL IMPORT ===== --}}
<div class="modal-overlay" id="modal-import">
    <div class="modal-box" style="max-width:420px">
        <div class="modal-header">
            <h3>Import Data Guru</h3>
            <button class="modal-close" onclick="closeModal('modal-import')">&times;</button>
        </div>
        <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                {{-- Keterangan Upload CSV --}}
                <div style="background:#f3f4f6; padding:12px; border-radius:8px; margin-bottom:16px;">
                    <p style="font-size:13px; color:#374151; margin-bottom:8px; font-weight:600;">
                        <i class="fas fa-file-csv" style="color:#2563eb;"></i> Format File CSV:
                    </p>
                    <ul style="font-size:12.5px; color:#6b7280; padding-left:20px; margin-bottom:0;">
                        <li>Kolom berurutan: <strong>Nama, Email, NIP, Mata Pelajaran</strong></li>
                        <li>Pastikan email belum pernah terdaftar</li>
                        <li>Password default setelah import adalah: <code>12345678</code></li>
                        <li><span style="color:#dc2626;">Penting:</span> <strong>Tugas Kelas</strong> harus diset manual dengan mengedit profil guru setelah proses import berhasil.</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih File CSV</label>
                    <input type="file" name="file" class="form-control" accept=".csv" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-import')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Import Data</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Stat Row */
.stat-row { display:flex; gap:16px; margin-bottom:20px; flex-wrap:wrap; }
.stat-mini { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:16px 24px; min-width:160px; display:flex; flex-direction:column; gap:4px; }
.stat-mini-label { font-size:12.5px; color:#6b7280; font-weight:500; }
.stat-mini-value { font-size:26px; font-weight:700; color:#111827; }

/* Toolbar */
.toolbar { display:flex; align-items:center; gap:10px; padding:16px 20px; border-bottom:1px solid #f3f4f6; flex-wrap:wrap; }
.toolbar-filters { display:flex; gap:8px; flex:1; flex-wrap:wrap; }
.toolbar-filters .form-control { height:36px; font-size:13px; }
.toolbar-filters input { min-width:200px; }
.toolbar-filters select { min-width:130px; }
.toolbar-actions { display:flex; gap:8px; }

/* Table */
.table-wrapper { overflow-x:auto; }
.data-table { width:100%; border-collapse:collapse; font-size:13.5px; }
.data-table th { background:#f9fafb; padding:11px 16px; text-align:left; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#6b7280; border-bottom:1px solid #e5e7eb; white-space:nowrap; }
.data-table td { padding:12px 16px; border-bottom:1px solid #f3f4f6; vertical-align:middle; color:#374151; }
.data-table tbody tr:hover { background:#f9fafb; }

.guru-info { display:flex; align-items:center; gap:10px; }
.guru-avatar { width:36px; height:36px; border-radius:50%; object-fit:cover; }
.guru-avatar-placeholder { width:36px; height:36px; border-radius:50%; background:#d1fae5; color:#065f46; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; flex-shrink:0; }
.guru-name { font-weight:500; color:#111827; font-size:13.5px; }
.guru-email { font-size:12px; color:#9ca3af; }

.badge { display:inline-flex; padding:3px 10px; border-radius:20px; font-size:11.5px; font-weight:500; }
.badge-success { background:#dcfce7; color:#15803d; }
.badge-danger  { background:#fee2e2; color:#b91c1c; }

.empty-state { text-align:center; color:#9ca3af; padding:40px; }
.pagination-wrap { padding:16px 20px; border-top:1px solid #f3f4f6; }

/* Modal */
.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; align-items:center; justify-content:center; padding:20px; }
.modal-overlay.show { display:flex; }
.modal-box { background:#fff; border-radius:12px; width:100%; max-width:580px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); animation:modalIn .2s ease; }
@keyframes modalIn { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
.modal-header { display:flex; align-items:center; justify-content:space-between; padding:18px 24px; border-bottom:1px solid #f3f4f6; }
.modal-header h3 { font-size:15px; font-weight:600; color:#111827; margin:0; }
.modal-close { background:none; border:none; font-size:20px; color:#9ca3af; cursor:pointer; line-height:1; }
.modal-close:hover { color:#374151; }
.modal-body { padding:20px 24px; }
.modal-footer { display:flex; justify-content:flex-end; gap:8px; padding:16px 24px; border-top:1px solid #f3f4f6; }

/* Form */
.form-section-title { font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#9ca3af; margin-bottom:14px; }
.form-row { display:flex; gap:14px; flex-wrap:wrap; }
.form-row .form-group { flex:1; min-width:160px; }
.form-group { margin-bottom:16px; }
.form-label { display:block; font-size:13.5px; font-weight:500; color:#374151; margin-bottom:6px; }
.required { color:#ef4444; }
.form-control { width:100%; padding:9px 13px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#fff; box-sizing:border-box; transition:border-color .2s,box-shadow .2s; }
.form-control:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
.alert { padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
.alert-success { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
</style>

<script>
function openModalTambah() {
    document.getElementById('modal-title').textContent = 'Tambah Guru';
    const form = document.getElementById('form-guru');
    form.action = '{{ route("admin.guru.store") }}';
    document.getElementById('method-field').innerHTML = '';
    document.getElementById('pass-hint').style.display = 'none';
    document.getElementById('pass-req').style.display = 'inline';
    form.reset();
    document.getElementById('f-kelas').value = ''; 
    document.getElementById('modal-guru').classList.add('show');
}

function openModalEdit(guru) {
    document.getElementById('modal-title').textContent = 'Edit Guru';
    const form = document.getElementById('form-guru');
    form.action = `/admin/guru/${guru.id}`;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('pass-hint').style.display = 'block';
    document.getElementById('pass-req').style.display = 'none';

    document.getElementById('f-name').value    = guru.name ?? '';
    document.getElementById('f-email').value   = guru.email ?? '';
    document.getElementById('f-nohp').value    = guru.no_hp ?? '';
    document.getElementById('f-nip').value     = guru.nip ?? '';
    document.getElementById('f-kelas').value   = guru.kelas_id ?? ''; 
    document.getElementById('f-status').value  = guru.status ?? 'aktif';
    document.getElementById('f-mapel').value   = guru.mata_pelajaran ?? '';
    document.getElementById('f-jabatan').value = guru.jabatan ?? '';
    document.getElementById('f-password').value = '';

    document.getElementById('modal-guru').classList.add('show');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('show');
    });
});
</script>
@endsection