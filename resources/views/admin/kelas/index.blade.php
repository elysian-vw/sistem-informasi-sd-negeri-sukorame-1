@extends('layouts.app')
@section('title', 'Kelas & Rombel')

@section('content')
<div class="page-header">
    <h1 class="page-title">Kelas & Rombel</h1>
</div>

@if(session('success'))
    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

{{-- Stat Cards --}}
<div class="stat-row">
    <div class="stat-mini">
        <span class="stat-mini-label">Total Rombel</span>
        <span class="stat-mini-value">{{ $totalRombel }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Total Siswa Terdaftar</span>
        <span class="stat-mini-value">{{ $totalSiswa }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Kelas Aktif</span>
        <span class="stat-mini-value">{{ $kelasAktif }}</span>
    </div>
    <div class="stat-mini">
        <span class="stat-mini-label">Wali Kelas</span>
        <span class="stat-mini-value">{{ $totalWaliKelas }}</span>
    </div>
</div>

<div class="content-card">
    {{-- Toolbar --}}
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.kelas.index') }}" class="toolbar-filters">
            <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Cari kelas / ruang...">

            <select name="tingkat" class="form-control">
                <option value="">Filter Tingkat</option>
                @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ request('tingkat') == $i ? 'selected' : '' }}>Tingkat {{ $i }}</option>
                @endfor
            </select>

            <select name="status" class="form-control">
                <option value="">Tahun Ajaran / Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak_aktif" {{ request('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>

            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>

        <div class="toolbar-actions">
            <button class="btn btn-primary" onclick="openModalTambah()">
                <i class="fas fa-plus"></i> Tambah Kelas
            </button>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Rombel</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th>Ruang Kelas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $i => $k)
                    <tr>
                        <td>{{ $kelas->firstItem() + $i }}</td>
                        <td>
                            <div class="kelas-info">
                                <div class="kelas-badge">{{ $k->tingkat }}</div>
                                <div>
                                    <div class="kelas-nama">Kelas {{ $k->nama_kelas }}</div>
                                    @if($k->rombel)
                                        <div class="kelas-rombel">Rombel {{ $k->rombel }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $k->waliKelas?->name ?? '-' }}</td>
                        <td>
                            <span class="siswa-count">
                                {{ $k->siswa->count() }}
                                <span class="siswa-cap">/ {{ $k->kapasitas }}</span>
                            </span>
                        </td>
                        <td>{{ $k->ruang_kelas ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $k->status === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                                {{ $k->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary border-0" title="Edit Data"
                                    onclick="openModalEdit({{ json_encode($k) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="d-inline"
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
                        <td colspan="7" class="empty-state">Belum ada data kelas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($kelas->hasPages())
        <div class="pagination-wrap">{{ $kelas->links() }}</div>
    @endif
</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div class="modal-overlay" id="modal-kelas">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Kelas</h3>
            <button class="modal-close" onclick="closeModal('modal-kelas')">&times;</button>
        </div>
        <form id="form-kelas" method="POST">
            @csrf
            <span id="method-field"></span>

            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Kelas <span class="required">*</span></label>
                        <input type="text" name="nama_kelas" id="f-nama" class="form-control" placeholder="cth: 1A, 2B" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tingkat <span class="required">*</span></label>
                        <select name="tingkat" id="f-tingkat" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">Tingkat {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Rombel</label>
                        <input type="text" name="rombel" id="f-rombel" class="form-control" placeholder="cth: A, B">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ruang Kelas</label>
                        <input type="text" name="ruang_kelas" id="f-ruang" class="form-control" placeholder="cth: Ruang 3">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kapasitas <span class="required">*</span></label>
                        <input type="number" name="kapasitas" id="f-kapasitas" class="form-control" value="30" min="1" max="60" required>
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
                    <label class="form-label">Wali Kelas</label>
                    <select name="wali_kelas_id" id="f-wali" class="form-control">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-kelas')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<style>
.stat-row { display:flex; gap:16px; margin-bottom:20px; flex-wrap:wrap; }
.stat-mini { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:16px 24px; min-width:160px; display:flex; flex-direction:column; gap:4px; flex:1; }
.stat-mini-label { font-size:12.5px; color:#6b7280; font-weight:500; }
.stat-mini-value { font-size:26px; font-weight:700; color:#111827; }

.toolbar { display:flex; align-items:center; gap:10px; padding:16px 20px; border-bottom:1px solid #f3f4f6; flex-wrap:wrap; }
.toolbar-filters { display:flex; gap:8px; flex:1; flex-wrap:wrap; }
.toolbar-filters .form-control { height:36px; font-size:13px; }
.toolbar-filters input { min-width:180px; }
.toolbar-filters select { min-width:140px; }
.toolbar-actions { display:flex; gap:8px; }

.table-wrapper { overflow-x:auto; }
.data-table { width:100%; border-collapse:collapse; font-size:13.5px; }
.data-table th { background:#f9fafb; padding:11px 16px; text-align:left; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#6b7280; border-bottom:1px solid #e5e7eb; white-space:nowrap; }
.data-table td { padding:12px 16px; border-bottom:1px solid #f3f4f6; vertical-align:middle; color:#374151; }
.data-table tbody tr:hover { background:#f9fafb; }

.kelas-info { display:flex; align-items:center; gap:10px; }
.kelas-badge { width:32px; height:32px; border-radius:8px; background:#eff6ff; color:#1d4ed8; font-size:13px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.kelas-nama { font-weight:600; color:#111827; font-size:13.5px; }
.kelas-rombel { font-size:12px; color:#9ca3af; }

.siswa-count { font-weight:600; color:#111827; }
.siswa-cap { font-weight:400; color:#9ca3af; font-size:12px; }

.badge { display:inline-flex; padding:3px 10px; border-radius:20px; font-size:11.5px; font-weight:500; }
.badge-success { background:#dcfce7; color:#15803d; }
.badge-danger  { background:#fee2e2; color:#b91c1c; }

.empty-state { text-align:center; color:#9ca3af; padding:40px; }
.pagination-wrap { padding:16px 20px; border-top:1px solid #f3f4f6; }

.modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; align-items:center; justify-content:center; padding:20px; }
.modal-overlay.show { display:flex; }
.modal-box { background:#fff; border-radius:12px; width:100%; max-width:520px; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); animation:modalIn .2s ease; }
@keyframes modalIn { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }
.modal-header { display:flex; align-items:center; justify-content:space-between; padding:18px 24px; border-bottom:1px solid #f3f4f6; }
.modal-header h3 { font-size:15px; font-weight:600; color:#111827; margin:0; }
.modal-close { background:none; border:none; font-size:20px; color:#9ca3af; cursor:pointer; }
.modal-close:hover { color:#374151; }
.modal-body { padding:20px 24px; }
.modal-footer { display:flex; justify-content:flex-end; gap:8px; padding:16px 24px; border-top:1px solid #f3f4f6; }

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
    document.getElementById('modal-title').textContent = 'Tambah Kelas';
    const form = document.getElementById('form-kelas');
    form.action = '{{ route("admin.kelas.store") }}';
    document.getElementById('method-field').innerHTML = '';
    form.reset();
    document.getElementById('f-kapasitas').value = 30;
    document.getElementById('modal-kelas').classList.add('show');
}

function openModalEdit(kelas) {
    document.getElementById('modal-title').textContent = 'Edit Kelas';
    const form = document.getElementById('form-kelas');
    form.action = `/admin/kelas/${kelas.id}`;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';

    document.getElementById('f-nama').value     = kelas.nama_kelas ?? '';
    document.getElementById('f-tingkat').value  = kelas.tingkat ?? '';
    document.getElementById('f-rombel').value   = kelas.rombel ?? '';
    document.getElementById('f-ruang').value    = kelas.ruang_kelas ?? '';
    document.getElementById('f-kapasitas').value= kelas.kapasitas ?? 30;
    document.getElementById('f-status').value   = kelas.status ?? 'aktif';
    document.getElementById('f-wali').value     = kelas.wali_kelas_id ?? '';

    document.getElementById('modal-kelas').classList.add('show');
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