@extends('layouts.guru')
@section('title', 'Penilaian Tugas')

@section('content')
<div class="page-header">
    <h1 class="page-title">Penilaian Tugas</h1>
    <p class="page-subtitle">Pilih tugas untuk dinilai</p>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Judul Tugas</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Deadline</th>
                    <th style="text-align:center;">Terkumpul</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($daftarTugas as $i => $t)
                @php
                    $total    = $t->kelas?->siswa()->count() ?? 0;
                    $kumpul   = $t->pengumpulan->count();
                    $dinilai  = $t->pengumpulan->whereNotNull('nilai')->count();
                @endphp
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;">{{ $daftarTugas->firstItem() + $i }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $t->judul }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">
                            {{ $t->tipe === 'cbt' ? '📋 CBT' : '📁 Upload' }}
                        </div>
                    </td>
                    <td style="font-size:13px;">{{ $t->mataPelajaran->nama ?? '—' }}</td>
                    <td style="font-size:13px;">{{ $t->kelas->nama_kelas ?? '—' }}</td>
                    <td style="font-size:12px;color:{{ now()->gt($t->deadline) ? 'var(--danger)':'var(--text-secondary)' }};">
                        {{ $t->deadline?->format('d M Y') ?? '—' }}
                    </td>
                    <td style="text-align:center;">
                        <div style="font-size:13px;font-weight:700;color:var(--primary);">{{ $kumpul }}/{{ $total }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $dinilai }} dinilai</div>
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('guru.tugas.penilaian', $t) }}" class="btn-primary-action" style="font-size:12px;">
                            <i class="fas fa-marker"></i> Nilai
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted);">
                    Belum ada tugas aktif.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($daftarTugas->hasPages())
    <div class="card-footer">{{ $daftarTugas->links() }}</div>
    @endif
</div>
@endsection