@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Berita</h1>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Berita
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
                            <th>Status</th>
                            <th>Penulis</th>
                            <th>Tgl Tayang</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($berita as $b)
                        <tr>
                            <td>{{ $loop->iteration + ($berita->firstItem() - 1) }}</td>
                            <td>{{ $b->judul }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($b->kategori) }}</span></td>
                            <td>
                                <span class="badge badge-{{ $b->status == 'published' ? 'success' : ($b->status == 'draft' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($b->status) }}
                                </span>
                            </td>
                            <td>{{ $b->creator->name ?? '-' }}</td>
                            <td>{{ $b->published_at ? $b->published_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.berita.edit', $b->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.berita.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                            <td colspan="7" class="text-center">Belwm ada data berita.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $berita->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
