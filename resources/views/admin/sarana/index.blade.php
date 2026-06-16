@extends('layouts.app')
@section('title', 'Kelola Sarana Prasarana')

@section('content')
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">Kelola Sarana Prasarana</h1>
        <p class="page-subtitle">Daftar sarana dan prasarana sekolah</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.sarana.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Sarana
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
                    <th style="width: 150px;">Foto</th>
                    <th>Nama Sarana</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Urutan</th>
                    <th style="width: 150px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sarana as $idx => $s)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        @if($s->foto)
                            <img src="{{ asset('storage/' . $s->foto) }}" class="media-preview" alt="Foto Sarana">
                        @else
                            <div class="no-photo">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="font-bold">{{ $s->nama }}</div>
                        <div class="text-xs text-gray-500 line-clamp-2">{{ $s->deskripsi }}</div>
                    </td>
                    <td>{{ $s->jumlah }}</td>
                    <td>
                        <span class="badge {{ $s->kondisi == 'Baik' ? 'badge-success' : ($s->kondisi == 'Rusak Ringan' ? 'badge-warning' : 'badge-danger') }}">
                            {{ $s->kondisi }}
                        </span>
                    </td>
                    <td>{{ $s->urutan }}</td>
                    <td class="text-center">
                        <div class="table-actions">
                            <a href="{{ route('admin.sarana.edit', $s->id) }}" class="btn-icon btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.sarana.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus data sarana ini?')" style="display:inline;">
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
                    <td colspan="7" class="text-center py-8">Belum ada data sarana prasarana.</td>
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
.no-photo {
    width: 120px;
    height: 80px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #9ca3af;
    font-size: 24px;
}
.badge {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
}
.badge-success { background: #f0fdf4; color: #15803d; }
.badge-warning { background: #fffbeb; color: #d97706; }
.badge-danger { background: #fef2f2; color: #dc2626; }

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
