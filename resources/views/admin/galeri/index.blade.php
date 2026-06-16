@extends('layouts.app')
@section('title', 'Kelola Galeri')

@section('content')
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">Kelola Galeri</h1>
        <p class="page-subtitle">Daftar dokumentasi foto dan video sekolah</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Galeri
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
                    <th style="width: 150px;">Media</th>
                    <th>Judul & Deskripsi</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($galeries as $idx => $g)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        @if($g->tipe == 'foto')
                            @if($g->file_path)
                                <img src="{{ asset('storage/' . $g->file_path) }}" class="media-preview" alt="Foto">
                            @else
                                <div class="media-placeholder">
                                    <i class="fas fa-image"></i>
                                    <span>No Image</span>
                                </div>
                            @endif
                        @else
                            <div class="video-preview">
                                <i class="fas fa-play-circle"></i> Video
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="font-bold">{{ $g->judul }}</div>
                        <div class="text-xs text-gray-500 line-clamp-2">{{ $g->deskripsi }}</div>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $g->kategori }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $g->tipe == 'foto' ? 'badge-primary' : 'badge-warning' }}">
                            {{ ucfirst($g->tipe) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $g->is_published ? 'badge-success' : 'badge-secondary' }}">
                            {{ $g->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="table-actions">
                            <a href="{{ route('admin.galeri.edit', $g->id) }}" class="btn-icon btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.galeri.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus item galeri ini?')" style="display:inline;">
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
                    <td colspan="7" class="text-center py-8">Belum ada data galeri.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.media-preview {
    width: 120px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}
.media-placeholder {
    width: 120px;
    height: 80px;
    background: #f3f4f6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #9ca3af;
    font-size: 10px;
    gap: 4px;
    border: 1px dashed #d1d5db;
}
.media-placeholder i { font-size: 20px; }
.video-preview {
    width: 120px;
    height: 80px;
    background: #f3f4f6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #4b5563;
    font-size: 12px;
    gap: 4px;
}
.video-preview i { font-size: 20px; }
.badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
}
.badge-info { background: #eff6ff; color: #1d4ed8; }
.badge-primary { background: #eef2ff; color: #4338ca; }
.badge-warning { background: #fffbeb; color: #d97706; }
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
