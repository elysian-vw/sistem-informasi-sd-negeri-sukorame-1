@extends('layouts.app')
@section('title', 'Kelola Komite Sekolah')

@section('content')
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">Kelola Komite Sekolah</h1>
        <p class="page-subtitle">Daftar pengurus komite sekolah</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.komite.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Anggota
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
                    <th>Nama Lengkap</th>
                    <th>Jabatan</th>
                    <th>Unsur</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Urutan</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($komite as $idx => $k)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><div class="font-bold">{{ $k->nama }}</div></td>
                    <td>{{ $k->jabatan }}</td>
                    <td>{{ $k->unsur }}</td>
                    <td>{{ $k->telepon ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $k->aktif ? 'badge-success' : 'badge-secondary' }}">
                            {{ $k->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>{{ $k->urutan }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.komite.edit', $k->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.komite.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8">Belum ada data komite sekolah.</td>
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
.badge-success { background: #f0fdf4; color: #15803d; }
.badge-secondary { background: #f9fafb; color: #6b7280; }
</style>
@endsection
