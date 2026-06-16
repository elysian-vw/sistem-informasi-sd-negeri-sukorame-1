@extends('layouts.guru')

@section('title', 'Daftar Materi')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Materi Pembelajaran</h1>
        <p class="page-subtitle">Kelola materi yang Anda ajarkan</p>
    </div>
    <a href="{{ route('guru.materi.create') }}" class="btn-primary-action">
        <i class="fas fa-plus"></i> Tambah Materi
    </a>
</div>

{{-- Filter Bar --}}
<div class="content-card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('guru.materi.index') }}" class="filter-bar">
        <div class="filter-group">
            <label class="filter-label"><i class="fas fa-book"></i> Mata Pelajaran</label>
            <select name="mata_pelajaran_id" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Mapel</option>
                @foreach($mapel as $mp)
                    <option value="{{ $mp->id }}" {{ request('mata_pelajaran_id') == $mp->id ? 'selected' : '' }}>
                        {{ $mp->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- TAMBAHKAN FILTER KELAS DI SINI --}}
        <div class="filter-group">
            <label class="filter-label"><i class="fas fa-school"></i> Kelas</label>
            <select name="kelas_id" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label"><i class="fas fa-tag"></i> Tipe</label>
            <select name="tipe" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="file"  {{ request('tipe') == 'file'  ? 'selected' : '' }}>File Dokumen</option>
                <option value="link"  {{ request('tipe') == 'link'  ? 'selected' : '' }}>Tautan</option>
                <option value="video" {{ request('tipe') == 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>
        
        @if(request()->hasAny(['mata_pelajaran_id','kelas_id','tipe']))
            <a href="{{ route('guru.materi.index') }}" class="btn-reset-filter">
                <i class="fas fa-times"></i> Reset
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="content-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Judul Materi</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Tipe</th>
                    <th>Tanggal</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materi as $item)
                <tr>
                    <td style="color:var(--text-muted);font-size:13px;">{{ $materi->firstItem() + $loop->index }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--text-dark);">{{ $item->judul }}</div>
                        @if($item->deskripsi)
                            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                {{ Str::limit($item->deskripsi, 60) }}
                            </div>
                        @endif
                    </td>
                    <td style="font-size:13px;">{{ $item->mataPelajaran->nama ?? '-' }}</td>
                    <td style="font-size:13px;">{{ $item->kelas->nama_kelas ?? '-' }}</td>
                    <td>
                        @php
                            $tipeConfig = [
                                'file'  => ['icon' => 'fa-file-alt',    'class' => 'badge-blue',   'label' => 'File'],
                                'link'  => ['icon' => 'fa-link',        'class' => 'badge-purple', 'label' => 'Tautan'],
                                'video' => ['icon' => 'fa-play-circle', 'class' => 'badge-orange', 'label' => 'Video'],
                            ];
                            $cfg = $tipeConfig[$item->tipe] ?? ['icon'=>'fa-file','class'=>'badge-blue','label'=>strtoupper($item->tipe)];
                        @endphp
                        <span class="badge {{ $cfg['class'] }}">
                            <i class="fas {{ $cfg['icon'] }}" style="margin-right:4px;"></i>{{ $cfg['label'] }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">
                        {{ $item->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('guru.materi.show', $item) }}" class="btn-action btn-view" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('guru.materi.edit', $item) }}" class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('guru.materi.destroy', $item) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus materi ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-row">
                        <div style="padding:40px 0;text-align:center;">
                            <i class="fas fa-book-open" style="font-size:36px;color:var(--text-muted);opacity:.4;margin-bottom:12px;display:block;"></i>
                            <div style="color:var(--text-muted);margin-bottom:8px;">Belum ada materi.</div>
                            <a href="{{ route('guru.materi.create') }}" style="color:var(--primary);font-weight:600;font-size:13px;">
                                + Tambah sekarang
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($materi->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);">
        {{ $materi->links() }}
    </div>
    @endif
</div>

@endsection