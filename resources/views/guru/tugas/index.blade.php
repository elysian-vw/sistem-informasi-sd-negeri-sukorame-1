@extends('layouts.guru')
@section('title', 'Manajemen Tugas')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Tugas</h1>
        <p class="page-subtitle">Buat dan kelola tugas untuk siswa</p>
    </div>
    <a href="{{ route('guru.tugas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Buat Tugas
    </a>
</div>

<div class="content-card">
    <div class="table-toolbar">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%;">
            <select name="kelas_id" class="form-control" style="width:auto;">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <select name="status" class="form-control" style="width:auto;">
                <option value="">Semua Status</option>
                <option value="aktif"   @selected(request('status')=='aktif')>Aktif</option>
                <option value="selesai" @selected(request('status')=='selesai')>Selesai</option>
                <option value="draft"   @selected(request('status')=='draft')>Draft</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('guru.tugas.index') }}" class="btn btn-sm" style="background:var(--bg);color:var(--text-secondary);border:1px solid var(--border);">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Judul Tugas</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Deadline</th>
                    <th>Terkumpul</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tugas as $t)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $t->judul }}</div>
                        @if($t->deskripsi)
                        <div style="font-size:12px;color:var(--text-muted);">{{ Str::limit($t->deskripsi, 50) }}</div>
                        @endif
                    </td>
                    <td>{{ $t->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $t->mataPelajaran->nama ?? '-' }}</td>
                    <td>
                        @if($t->deadline)
                            <span style="color:{{ $t->isTerlambat() ? 'var(--danger)' : 'var(--text-primary)' }};font-size:13px;">
                                {{ $t->deadline->format('d/m/Y H:i') }}
                            </span>
                        @else
                            <span style="color:var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td><span class="badge badge-blue">{{ $t->pengumpulan->count() }} siswa</span></td>
                    <td>
                        @php $colors = ['aktif'=>'badge-green','selesai'=>'badge-light','draft'=>'badge-light']; @endphp
                        <span class="badge {{ $colors[$t->status] ?? 'badge-light' }}">{{ ucfirst($t->status) }}</span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('guru.tugas.penilaian', $t) }}"
                               class="btn-icon"
                               style="background:#f3e5f5;color:var(--purple);"
                               title="Penilaian">
                                <i class="fas fa-star"></i>
                            </a>

                            @if($t->pengumpulan->count() > 0)
                                <a href="{{ route('guru.tugas.show', $t) }}"
                                   class="btn-icon"
                                   style="background:#e0f2fe;color:#0284c7;"
                                   title="Ada {{ $t->pengumpulan->count() }} siswa sudah mengumpulkan — tidak bisa diedit">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @else
                                <a href="{{ route('guru.tugas.edit', $t) }}"
                                   class="btn-icon"
                                   title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                            @endif

                            <form action="{{ route('guru.tugas.destroy', $t) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn-icon btn-delete"
                                        data-confirm="Hapus tugas ini?"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-row">
                        <i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                        Belum ada tugas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">{{ $tugas->links() }}</div>
</div>

@endsection