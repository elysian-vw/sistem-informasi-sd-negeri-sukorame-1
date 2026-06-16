@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Ekstrakurikuler</h1>
        <a href="{{ route('admin.ekskul.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Ekskul
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
                            <th>Foto</th>
                            <th>Nama Ekskul</th>
                            <th>Pembina</th>
                            <th>Hari & Waktu</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ekskul as $e)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($e->foto)
                                    <img src="{{ asset('storage/' . $e->foto) }}" alt="{{ $e->nama }}" style="height: 40px; width: 40px; object-fit: cover;" class="rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 40px; width: 40px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $e->nama }}</td>
                            <td>{{ $e->pembina->user->name ?? '-' }}</td>
                            <td>{{ $e->hari }}, {{ $e->waktu_mulai }} - {{ $e->waktu_selesai }}</td>
                            <td>
                                <span class="badge badge-{{ $e->is_aktif ? 'success' : 'danger' }}">
                                    {{ $e->is_aktif ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.ekskul.edit', $e->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.ekskul.destroy', $e->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                            <td colspan="7" class="text-center">Belum ada data ekstrakurikuler.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
