@extends('layouts.app')
@section('title', 'Kelola Jadwal Pelajaran')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Jadwal Pelajaran</h1>
        <p class="page-subtitle">Kelola jadwal pelajaran seluruh kelas</p>
    </div>
    <div class="toolbar-actions" style="display:flex;gap:8px;">
        <button class="btn btn-secondary" onclick="openModalImport()">
            <i class="fas fa-file-import"></i> Import
        </button>
        <a href="{{ route('admin.jadwal.export') }}" class="btn btn-secondary">
            <i class="fas fa-file-export"></i> Export
        </a>
        <button class="btn btn-primary" onclick="openModalTambah()">
            <i class="fas fa-plus"></i> Tambah Jadwal
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
@endif

{{-- Stat Cards --}}
<div class="stat-row">
    <div class="stat-mini">
        <span class="stat-mini-label">Total Jadwal</span>
        <span class="stat-mini-value">{{ $jadwals->count() }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Tahun Ajaran</span>
        <span class="stat-mini-value" style="font-size:18px;">2024/2025</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Total Kelas</span>
        <span class="stat-mini-value">{{ $kelas->count() }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Mata Pelajaran</span>
        <span class="stat-mini-value">{{ $mapels->count() }}</span>
    </div>
</div>

<div class="content-card">
    {{-- Toolbar --}}
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.jadwal.index') }}" class="toolbar-filters">
            <select name="filter_hari" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Hari</option>
                @foreach(['senin','selasa','rabu','kamis','jumat','sabtu'] as $h)
                    <option value="{{ $h }}" {{ request('filter_hari') == $h ? 'selected' : '' }}>
                        {{ ucfirst($h) }}
                    </option>
                @endforeach
            </select>
            <select name="filter_kelas" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('filter_kelas') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
            @if(request('filter_hari') || request('filter_kelas'))
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary" style="height:36px;display:inline-flex;align-items:center;gap:6px;font-size:12px;">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="table-wrapper">
        <table class="data-table" id="tabel-jadwal">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Jam Ke</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $i => $j)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><span class="badge badge-blue">{{ ucfirst($j->hari) }}</span></td>
                        <td><strong>{{ $j->jam_ke }}</strong></td>
                        <td>
                            <div class="kelas-info">
                                <div class="kelas-badge">{{ $j->kelas->tingkat ?? '-' }}</div>
                                <span class="kelas-nama">{{ $j->kelas->nama_kelas ?? '-' }}</span>
                            </div>
                        </td>
                        <td>{{ $j->mataPelajaran->nama ?? '-' }}</td>
                        <td>
                            <div class="guru-info">
                                <div class="guru-avatar">
                                    {{ strtoupper(substr($j->guru->user->name ?? '?', 0, 1)) }}
                                </div>
                                <span>{{ $j->guru->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="waktu-chip">
                                <i class="fas fa-clock"></i>
                                {{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary border-0" title="Edit Data"
                                    onclick="openModalEdit({{ json_encode($j) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" class="d-inline"
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
                        <td colspan="8" class="empty-state">
                            <i class="fas fa-calendar-times" style="font-size:28px;color:#d1d5db;display:block;margin-bottom:8px;"></i>
                            Belum ada jadwal yang diatur.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div class="modal-overlay" id="modal-jadwal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Jadwal</h3>
            <button class="modal-close" onclick="closeModal('modal-jadwal')">&times;</button>
        </div>
        <form id="form-jadwal" method="POST">
            @csrf
            <span id="method-field"></span>

            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kelas <span class="required">*</span></label>
                        <select name="kelas_id" id="f-kelas" class="form-control" required>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Hari <span class="required">*</span></label>
                        <select name="hari" id="f-hari" class="form-control" required>
                            @foreach(['senin','selasa','rabu','kamis','jumat','sabtu'] as $h)
                                <option value="{{ $h }}">{{ ucfirst($h) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mata Pelajaran <span class="required">*</span></label>
                    <select name="mata_pelajaran_id" id="f-mapel" class="form-control" required>
                        @foreach($mapels as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Guru Pengajar <span class="required">*</span></label>
                    <select name="guru_id" id="f-guru" class="form-control" required>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id }}">{{ $g->user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jam Ke <span class="required">*</span></label>
                        <input type="number" name="jam_ke" id="f-jamke" class="form-control"
                            placeholder="Contoh: 1" min="1" max="12" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="f-tahun" class="form-control"
                            value="2024/2025" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Semester <span class="required">*</span></label>
                        <select name="semester" id="f-semester" class="form-control" required>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" id="f-mulai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" id="f-selesai" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-jadwal')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>


{{-- ===== MODAL IMPORT ===== --}}
<div class="modal-overlay" id="modal-import">
    <div class="modal-box" style="max-width:460px;">
        <div class="modal-header">
            <h3>Import Jadwal</h3>
            <button class="modal-close" onclick="closeModal('modal-import')">&times;</button>
        </div>
        <form action="{{ route('admin.jadwal.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                {{-- Download Template --}}
                <div class="import-info">
                    <i class="fas fa-info-circle" style="color:var(--primary);font-size:15px;flex-shrink:0;"></i>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:#111827;margin-bottom:2px;">
                            Gunakan template resmi
                        </div>
                        <div style="font-size:12px;color:#6b7280;">
                            Pastikan format file sesuai template agar import berhasil.
                        </div>
                        <a href="{{ route('admin.jadwal.template') }}"
                            class="btn btn-secondary btn-sm" style="margin-top:8px;font-size:12px;">
                            <i class="fas fa-download"></i> Download Template (.xlsx)
                        </a>
                    </div>
                </div>

                {{-- Kolom yang diperlukan --}}
                <div style="margin:16px 0 12px;">
                    <div style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;
                        letter-spacing:.05em;margin-bottom:8px;">Kolom yang diperlukan</div>
                    <div class="col-chips">
                        @foreach(['kelas_id','hari','mata_pelajaran_id','guru_id','jam_ke','waktu_mulai','waktu_selesai','tahun_ajaran'] as $col)
                            <span class="col-chip">{{ $col }}</span>
                        @endforeach
                    </div>
                </div>

                {{-- Drop zone --}}
                <div class="file-drop" id="file-drop-zone"
                    onclick="document.getElementById('f-import-file').click()">
                    <div class="file-drop-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <div class="file-drop-text" id="file-drop-text">
                        Klik atau drag file ke sini
                    </div>
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                        Format: .xlsx, .xls, .csv (maks. 2MB)
                    </div>
                    <input type="file" id="f-import-file" name="file"
                        accept=".xlsx,.xls,.csv" required style="display:none;">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('modal-import')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-file-import"></i> Proses Import
                </button>
            </div>
        </form>
    </div>
</div>


<style>
/* ── STAT ROW ── */
.stat-row {
    display: flex; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;
}
.stat-mini {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 16px 24px; min-width: 160px;
    display: flex; flex-direction: column; gap: 4px; flex: 1;
}
.stat-mini-label { font-size: 12.5px; color: #6b7280; font-weight: 500; }
.stat-mini-value { font-size: 26px; font-weight: 700; color: #111827; }

/* ── PAGE HEADER ── */
.page-header {
    display: flex; justify-content: space-between;
    align-items: flex-start; margin-bottom: 24px;
}
.page-subtitle { color: var(--text-secondary); font-size: 13px; margin-top: 2px; }

/* ── TOOLBAR ── */
.toolbar {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 20px; border-bottom: 1px solid #f3f4f6; flex-wrap: wrap;
}
.toolbar-filters {
    display: flex; gap: 8px; flex: 1; flex-wrap: wrap; align-items: center;
}
.toolbar-filters .form-control { height: 36px; font-size: 13px; }
.toolbar-filters select { min-width: 140px; }

/* ── CONTENT CARD ── */
.content-card {
    background: #fff; border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.04);
    overflow: hidden; margin-bottom: 24px;
}

/* ── TABLE ── */
.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.data-table th {
    background: #f9fafb; padding: 11px 16px; text-align: left;
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .05em; color: #6b7280;
    border-bottom: 1px solid #e5e7eb; white-space: nowrap;
}
.data-table td {
    padding: 12px 16px; border-bottom: 1px solid #f3f4f6;
    vertical-align: middle; color: #374151;
}
.data-table tbody tr:hover td { background: #f8faff; }
.data-table tr:last-child td { border-bottom: none; }

/* ── KELAS INFO ── */
.kelas-info { display: flex; align-items: center; gap: 8px; }
.kelas-badge {
    width: 30px; height: 30px; border-radius: 7px;
    background: #eff6ff; color: #1d4ed8; font-size: 12px;
    font-weight: 700; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.kelas-nama { font-weight: 600; color: #111827; font-size: 13.5px; }

/* ── GURU INFO ── */
.guru-info { display: flex; align-items: center; gap: 8px; }
.guru-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--primary-light, #e8f0fe); color: var(--primary, #1a73e8);
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* ── WAKTU CHIP ── */
.waktu-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: #f0fdf4; color: #166534;
    padding: 3px 9px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}
.waktu-chip i { font-size: 10px; }

/* ── BADGES ── */
.badge {
    display: inline-flex; padding: 3px 10px;
    border-radius: 20px; font-size: 11.5px; font-weight: 600;
}
.badge-blue   { background: #dbeafe; color: #1d4ed8; }
.badge-success{ background: #dcfce7; color: #15803d; }
.badge-danger { background: #fee2e2; color: #b91c1c; }

/* ── BUTTONS ── */
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 16px; border-radius: 8px;
    font-size: 13.5px; font-weight: 600;
    cursor: pointer; border: none; font-family: inherit;
    text-decoration: none; transition: all .2s;
}
.btn-primary { background: var(--primary, #1a73e8); color: white; }
.btn-primary:hover { background: var(--primary-dark, #1557b0); transform: translateY(-1px); }
.btn-secondary {
    background: white; color: #374151;
    border: 1px solid #d1d5db;
}
.btn-secondary:hover { background: #f9fafb; }
.btn-sm { padding: 6px 12px; font-size: 12px; }

/* ── ALERT ── */
.alert {
    padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
    font-size: 14px; display: flex; align-items: center; gap: 8px;
    font-weight: 500;
}
.alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.alert-danger  { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* ── EMPTY STATE ── */
.empty-state {
    text-align: center; color: #9ca3af;
    padding: 40px !important; font-size: 13px;
}

/* ── MODAL ── */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.45); z-index: 1050;
    align-items: center; justify-content: center; padding: 20px;
}
.modal-overlay.show { display: flex; }
.modal-box {
    background: #fff; border-radius: 12px;
    width: 100%; max-width: 520px; max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,.2);
    animation: modalIn .2s ease;
}
@keyframes modalIn {
    from { opacity: 0; transform: translateY(-10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 24px; border-bottom: 1px solid #f3f4f6;
}
.modal-header h3 { font-size: 15px; font-weight: 600; color: #111827; margin: 0; }
.modal-close {
    background: none; border: none; font-size: 20px;
    color: #9ca3af; cursor: pointer; line-height: 1;
}
.modal-close:hover { color: #374151; }
.modal-body { padding: 20px 24px; }
.modal-footer {
    display: flex; justify-content: flex-end; gap: 8px;
    padding: 16px 24px; border-top: 1px solid #f3f4f6;
}

/* ── FORM ── */
.form-row { display: flex; gap: 14px; flex-wrap: wrap; }
.form-row .form-group { flex: 1; min-width: 160px; }
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 13.5px; font-weight: 500; color: #374151; margin-bottom: 6px; }
.required { color: #ef4444; }
.form-control {
    width: 100%; padding: 9px 13px;
    border: 1px solid #d1d5db; border-radius: 8px;
    font-size: 14px; color: #111827; background: #fff;
    box-sizing: border-box; font-family: inherit;
    transition: border-color .2s, box-shadow .2s;
}
.form-control:focus {
    outline: none; border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}

/* ── IMPORT MODAL ── */
.import-info {
    display: flex; gap: 12px; align-items: flex-start;
    background: #eff6ff; border: 1px solid #bfdbfe;
    border-radius: 8px; padding: 14px;
}
.col-chips { display: flex; flex-wrap: wrap; gap: 6px; }
.col-chip {
    background: #f1f5f9; color: #475569;
    padding: 3px 9px; border-radius: 5px;
    font-size: 11.5px; font-family: monospace; font-weight: 600;
    border: 1px solid #e2e8f0;
}
.file-drop {
    border: 2px dashed #d1d5db; border-radius: 10px;
    padding: 28px 20px; text-align: center;
    cursor: pointer; transition: border-color .2s, background .2s;
    background: #fafafa;
}
.file-drop:hover {
    border-color: #2563eb; background: #eff6ff;
}
.file-drop-icon { font-size: 32px; color: #9ca3af; margin-bottom: 8px; }
.file-drop-text { font-size: 13px; font-weight: 600; color: #374151; }
.file-drop.has-file .file-drop-icon { color: #16a34a; }
.file-drop.has-file { border-color: #16a34a; background: #f0fdf4; }
</style>


<script>
function openModalTambah() {
    document.getElementById('modal-title').textContent = 'Tambah Jadwal';
    const form = document.getElementById('form-jadwal');
    form.action = '{{ route("admin.jadwal.store") }}';
    document.getElementById('method-field').innerHTML = '';
    form.reset();
    document.getElementById('modal-jadwal').classList.add('show');
}

function openModalEdit(data) {
    document.getElementById('modal-title').textContent = 'Edit Jadwal';
    const form = document.getElementById('form-jadwal');
    form.action = `/admin/jadwal/${data.id}`;
    document.getElementById('method-field').innerHTML =
        '<input type="hidden" name="_method" value="PUT">';

    document.getElementById('f-kelas').value = data.kelas_id;
    document.getElementById('f-hari').value  = data.hari;
    document.getElementById('f-mapel').value = data.mata_pelajaran_id;
    document.getElementById('f-guru').value  = data.guru_id;
    document.getElementById('f-jamke').value = data.jam_ke;
    document.getElementById('f-mulai').value = data.waktu_mulai
        ? data.waktu_mulai.substring(0, 5) : '';
    document.getElementById('f-selesai').value = data.waktu_selesai
        ? data.waktu_selesai.substring(0, 5) : '';
    document.getElementById('f-tahun').value = data.tahun_ajaran;

    document.getElementById('modal-jadwal').classList.add('show');
    document.getElementById('f-semester').value = data.semester ?? '1';
}

function openModalImport() {
    document.getElementById('modal-import').classList.add('show');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}

// Klik luar modal → tutup
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('show');
    });
});

// File drop zone feedback
const fileInput = document.getElementById('f-import-file');
const dropZone  = document.getElementById('file-drop-zone');
const dropText  = document.getElementById('file-drop-text');

if (fileInput) {
    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            dropText.textContent = this.files[0].name;
            dropZone.classList.add('has-file');
            dropZone.querySelector('.file-drop-icon i').className =
                'fas fa-check-circle';
        }
    });

    // Drag & drop
    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.style.borderColor = '#2563eb';
        dropZone.style.background  = '#eff6ff';
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '';
        dropZone.style.background  = '';
    });
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        const dt = e.dataTransfer;
        if (dt.files.length > 0) {
            fileInput.files = dt.files;
            fileInput.dispatchEvent(new Event('change'));
        }
        dropZone.style.borderColor = '';
        dropZone.style.background  = '';
    });
}
</script>
@endsection