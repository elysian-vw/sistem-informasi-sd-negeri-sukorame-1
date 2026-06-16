@extends('layouts.app')
@section('title', 'Data Siswa')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Data Siswa</h1>
        <p class="page-subtitle">Kelola data seluruh siswa</p>
    </div>
    <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Siswa
    </a>
</div>

<div class="content-card">
    <div class="table-toolbar">
        <form method="GET" action="{{ route('admin.siswa.index') }}" style="display: flex; gap: 12px; width: 100%; flex-wrap: wrap;">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama / NISN..." class="form-control">
            </div>
            <div style="width: 220px;">
                <select name="kelas_id" onchange="this.form.submit()" class="form-control">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            Kelas {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">Cari</button>
                @if(request('cari') || request('kelas_id'))
                    <a href="{{ route('admin.siswa.index') }}" class="btn" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569;">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswa as $i => $s)
                <tr>
                    <td>{{ $siswa->firstItem() + $i }}</td>
                    <td><span class="badge badge-light">{{ $s->nisn }}</span></td>
                    <td>
                        <div class="user-cell">
                            <div class="avatar">{{ substr($s->nama_lengkap, 0, 1) }}</div>
                            <div style="font-weight: 600;">{{ $s->nama_lengkap }}</div>
                        </div>
                    </td>
                    <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                    <td>
                        @if($s->jenis_kelamin === 'L')
                            <span class="badge badge-blue">Laki-laki</span>
                        @else
                            <span class="badge badge-pink">Perempuan</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.siswa.edit', $s) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                    <td colspan="6" class="empty-row">Tidak ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrapper" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px;">
        <div style="font-size: 13px; color: var(--text-secondary);">
            Menampilkan {{ $siswa->firstItem() ?? 0 }} - {{ $siswa->lastItem() ?? 0 }} dari total {{ $siswa->total() }} data
        </div>
        <div>
            {{ $siswa->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endsection