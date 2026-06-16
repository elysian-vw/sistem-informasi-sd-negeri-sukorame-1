@extends('layouts.app')
@section('title', 'Kelola Galeri')

@push('styles')
<style>
    .media-container {
        position: relative;
        width: 100px;
        height: 70px;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
    .media-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .media-type-icon {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255,255,255,0.8);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #4a5568;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
    }
    .category-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        background: #edf2f7;
        color: #4a5568;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #718096;
        border-top: none;
    }
    .media-placeholder-alt {
        width: 100%;
        height: 100%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 px-2">
        <div>
            <h1 class="h3 mb-1 text-gray-800 font-weight-bold">Kelola Galeri</h1>
            <p class="text-muted small mb-0">Manajemen dokumentasi foto dan video sekolah untuk website publik.</p>
        </div>
        <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-plus fa-sm mr-2"></i> Tambah Dokumentasi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mx-2 shadow-sm border-0 alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-2 fa-lg"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mx-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="text-center py-3" style="width: 60px;">No</th>
                            <th class="py-3" style="width: 120px;">Media</th>
                            <th class="py-3">Informasi Dokumentasi</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galeries as $idx => $g)
                        <tr>
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <div class="media-container">
                                    @if($g->tipe == 'foto')
                                        @if($g->file_path)
                                            <img src="{{ asset('storage/' . $g->file_path) }}" alt="Preview">
                                        @else
                                            <div class="media-placeholder-alt">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                        <div class="media-type-icon" title="Foto">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                    @else
                                        @if($g->thumbnail)
                                            <img src="{{ asset('storage/' . $g->thumbnail) }}" alt="Preview">
                                        @else
                                            <div class="media-placeholder-alt">
                                                <i class="fas fa-video"></i>
                                            </div>
                                        @endif
                                        <div class="media-type-icon" title="Video">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-gray-800 mb-1">{{ $g->judul }}</div>
                                <div class="small text-muted line-clamp-2" style="max-width: 400px;">
                                    {{ $g->deskripsi ?: 'Tidak ada deskripsi.' }}
                                </div>
                            </td>
                            <td>
                                <span class="category-badge">
                                    {{ str_replace('_', ' ', $g->kategori) }}
                                </span>
                            </td>
                            <td>
                                <div class="small font-weight-500">
                                    {{ $g->tanggal ? $g->tanggal->format('d M Y') : '-' }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($g->is_published)
                                    <span class="badge badge-success status-badge">
                                        <i class="fas fa-globe-asia mr-1"></i> Publik
                                    </span>
                                @else
                                    <span class="badge badge-light text-muted status-badge">
                                        <i class="fas fa-lock mr-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.galeri.edit', $g->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.galeri.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumentasi ini?')">
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
                            <td colspan="7" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/empty-folder.svg" alt="Empty" style="width: 120px;" class="mb-3 opacity-50">
                                <p class="text-muted">Belum ada data dokumentasi yang tersimpan.</p>
                                <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    Mulai Tambah Data
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
