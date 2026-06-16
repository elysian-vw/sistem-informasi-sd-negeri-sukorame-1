@extends('layouts.app')
@section('title', 'Kelola Prestasi')

@section('content')
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">Kelola Prestasi</h1>
        <p class="page-subtitle">Daftar capaian prestasi siswa dan sekolah</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.prestasi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Prestasi
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="content-card">
    <div class="table-responsive">
        <table class="table table-prestasi">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nama Lomba / Capaian</th>
                    <th style="width:130px;">Juara</th>
                    <th style="width:110px;">Tingkat</th>
                    <th style="width:120px;">Tanggal</th>
                    <th style="width:90px;">Status</th>
                    <th style="width:90px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestasi as $idx => $p)
                @php
                    $juaraRaw = strtolower($p->juara);
                    $juaraLabel = match($juaraRaw) {
                        '1'         => 'Juara 1',
                        '2'         => 'Juara 2',
                        '3'         => 'Juara 3',
                        'harapan_1' => 'Harapan 1',
                        'harapan_2' => 'Harapan 2',
                        'harapan_3' => 'Harapan 3',
                        'finalis'   => 'Finalis',
                        default     => ucfirst(str_replace('_', ' ', $p->juara)),
                    };
                    $juaraClass = match($juaraRaw) {
                        '1'         => 'badge-juara1',
                        '2'         => 'badge-juara2',
                        '3'         => 'badge-juara3',
                        'harapan_1','harapan_2','harapan_3' => 'badge-harapan',
                        default     => 'badge-lainnya',
                    };
                    $juaraIcon = match($juaraRaw) {
                        '1'         => 'fa-trophy',
                        '2'         => 'fa-medal',
                        '3'         => 'fa-award',
                        default     => 'fa-certificate',
                    };
                    $tingkatClass = match(strtolower($p->tingkat)) {
                        'nasional'      => 'tingkat-nasional',
                        'internasional' => 'tingkat-internasional',
                        'provinsi'      => 'tingkat-provinsi',
                        'kota'          => 'tingkat-kota',
                        default         => 'tingkat-default',
                    };
                @endphp
                <tr>
                    <td class="text-center text-muted fw-semibold">{{ $idx + 1 }}</td>
                    <td>
                        <div class="lomba-nama">{{ $p->nama_lomba }}</div>
                        <div class="lomba-meta">
                            <span><i class="fas fa-user fa-xs"></i>
                                {{ $p->siswa ? $p->siswa->user->name : 'Sekolah' }}
                            </span>
                            <span class="meta-sep">·</span>
                            <span>{{ $p->penyelenggara }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge-juara {{ $juaraClass }}">
                            <i class="fas {{ $juaraIcon }}"></i> {{ $juaraLabel }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-tingkat {{ $tingkatClass }}">{{ ucfirst($p->tingkat) }}</span>
                    </td>
                    <td class="text-muted" style="font-size:13px;">
                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                    </td>
                    <td>
                        <span class="status-badge {{ $p->is_published ? 'status-published' : 'status-draft' }}">
                            <span class="status-dot"></span>
                            {{ $p->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.prestasi.edit', $p->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.prestasi.destroy', $p->id) }}" method="POST" class="d-inline"
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
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-trophy"></i>
                        <p>Belum ada data prestasi.</p>
                        <a href="{{ route('admin.prestasi.create') }}" class="btn btn-primary btn-sm">Tambah Pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* ── TABLE ── */
.table-prestasi { border-collapse: separate; border-spacing: 0; width: 100%; }
.table-prestasi thead tr th {
    background: #f8fafc;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    padding: 10px 14px;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.table-prestasi tbody tr {
    transition: background .15s;
}
.table-prestasi tbody tr:hover { background: #f8fafc; }
.table-prestasi tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.table-prestasi tbody tr:last-child td { border-bottom: none; }

/* ── LOMBA NAME ── */
.lomba-nama { font-weight: 700; font-size: 13.5px; color: #1e293b; line-height: 1.4; }
.lomba-meta { font-size: 11.5px; color: #94a3b8; margin-top: 3px; display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
.meta-sep { color: #cbd5e1; }

/* ── JUARA BADGE ── */
.badge-juara {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11.5px; font-weight: 700;
    padding: 4px 10px; border-radius: 999px;
    white-space: nowrap;
}
.badge-juara1  { background: #fef9c3; color: #92400e; border: 1px solid #fde68a; }
.badge-juara2  { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
.badge-juara3  { background: #fff3ed; color: #9a3412; border: 1px solid #fed7aa; }
.badge-harapan { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge-lainnya { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

/* ── TINGKAT BADGE ── */
.badge-tingkat {
    display: inline-block;
    font-size: 11px; font-weight: 600;
    padding: 3px 9px; border-radius: 6px;
    white-space: nowrap;
}
.tingkat-nasional      { background: #fdf4ff; color: #7e22ce; }
.tingkat-internasional { background: #fdf4ff; color: #581c87; font-weight: 800; }
.tingkat-provinsi      { background: #fff7ed; color: #c2410c; }
.tingkat-kota          { background: #eff6ff; color: #1d4ed8; }
.tingkat-default       { background: #f1f5f9; color: #475569; }

/* ── STATUS ── */
.status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11.5px; font-weight: 600;
    padding: 4px 10px; border-radius: 999px;
}
.status-published { background: #f0fdf4; color: #15803d; }
.status-draft     { background: #f9fafb; color: #6b7280; }
.status-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: currentColor; flex-shrink: 0;
}
/* ── EMPTY STATE ── */
.empty-state {
    text-align: center; padding: 60px 20px !important; color: #94a3b8;
}
.empty-state i { font-size: 2.5rem; color: #e2e8f0; margin-bottom: 12px; display: block; }
.empty-state p { margin-bottom: 16px; font-size: 14px; }
</style>
@endsection