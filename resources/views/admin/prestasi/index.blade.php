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
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Lomba / Capaian</th>
                    <th>Juara</th>
                    <th>Tingkat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestasi as $idx => $p)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <div class="font-bold">{{ $p->nama_lomba }}</div>
                        <div class="text-xs text-gray-500">Oleh: {{ $p->siswa ? $p->siswa->user->name : 'Sekolah' }}</div>
                        <div class="text-xs text-gray-400">{{ $p->penyelenggara }}</div>
                    </td>
                    <td><span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($p->juara)) }}</span></td>
                    <td>{{ ucfirst($p->tingkat) }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $p->is_published ? 'badge-success' : 'badge-secondary' }}">
                            {{ $p->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="table-actions">
                            <a href="{{ route('admin.prestasi.edit', $p->id) }}" class="btn-icon btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.prestasi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data prestasi ini?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-8">Belum ada data prestasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
}
.badge-info { background: #eff6ff; color: #1d4ed8; }
.badge-success { background: #f0fdf4; color: #15803d; }
.badge-secondary { background: #f9fafb; color: #6b7280; }

.table-actions { display: flex; justify-content: center; gap: 8px; }
.btn-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-edit { background: #eff6ff; color: #2563eb; }
.btn-edit:hover { background: #dbeafe; }
.btn-delete { background: #fef2f2; color: #dc2626; }
.btn-delete:hover { background: #fee2e2; }
</style>
@endsection
