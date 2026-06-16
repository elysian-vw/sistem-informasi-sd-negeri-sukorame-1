@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Kegiatan</h1>
        <a href="{{ route('admin.kegiatan.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Kegiatan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Sasaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan as $k)
                        <tr>
                            <td>{{ $loop->iteration + ($kegiatan->firstItem() - 1) }}</td>
                            <td>{{ $k->judul }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($k->kategori) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal_mulai)->format('d/m/Y') }}</td>
                            <td>{{ $k->tanggal_selesai ? \Carbon\Carbon::parse($k->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                            <td><span class="badge badge-secondary">{{ ucfirst($k->sasaran) }}</span></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.kegiatan.edit', $k->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.kegiatan.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                            <td colspan="7" class="text-center">Belum ada data kegiatan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $kegiatan->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
