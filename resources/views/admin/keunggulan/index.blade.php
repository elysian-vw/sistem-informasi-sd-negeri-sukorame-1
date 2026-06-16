@extends('layouts.app')
@section('title', 'Kelola Keunggulan Sekolah')

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <h1 class="page-title">Keunggulan Sekolah</h1>
    <p class="page-subtitle">Kelola kartu keunggulan yang tampil di halaman beranda.</p>
</div>

@if(session('success'))
    <div class="alert alert-success" style="background:#dcfce7;color:#15803d;padding:14px 20px;border-radius:8px;margin-bottom:20px;border:1px solid #bbf7d0;font-size:13.5px;display:flex;align-items:center;gap:10px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div style="margin-bottom:20px;">
    <a href="{{ route('admin.keunggulan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Keunggulan
    </a>
</div>

<div class="content-card" style="background:white;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden;">
    <table class="data-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                <th style="padding:12px 16px;text-align:center;width:50px;">No</th>
                <th style="padding:12px 16px;text-align:center;width:60px;">Urutan</th>
                <th style="padding:12px 16px;">Icon</th>
                <th style="padding:12px 16px;">Judul</th>
                <th style="padding:12px 16px;">Deskripsi</th>
                <th style="padding:12px 16px;text-align:center;">Warna</th>
                <th style="padding:12px 16px;text-align:center;">Status</th>
                <th class="text-center py-3" style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keunggulan as $i => $k)
            @php
                $colorMap = [
                    'blue'   => 'bg-blue-50 text-blue-600',
                    'indigo' => 'bg-indigo-50 text-indigo-600',
                    'purple' => 'bg-purple-50 text-purple-600',
                    'amber'  => 'bg-amber-50 text-amber-600',
                    'pink'   => 'bg-pink-50 text-pink-600',
                    'teal'   => 'bg-teal-50 text-teal-600',
                    'green'  => 'bg-green-50 text-green-600',
                    'red'    => 'bg-red-50 text-red-600',
                ];
                $cls = $colorMap[$k->color] ?? 'bg-gray-50 text-gray-600';
            @endphp
            <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:12px 16px;text-align:center;color:#6b7280;font-size:13px;">{{ $i + 1 }}</td>
                <td style="padding:12px 16px;text-align:center;font-weight:700;color:#374151;">{{ $k->urutan }}</td>
                <td style="padding:12px 16px;">
                    <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;" class="{{ $cls }}">
                        <i class="fa {{ $k->icon }}"></i>
                    </div>
                    <code style="font-size:11px;color:#9ca3af;">{{ $k->icon }}</code>
                </td>
                <td style="padding:12px 16px;font-weight:600;color:#111827;">{{ $k->title }}</td>
                <td style="padding:12px 16px;color:#6b7280;font-size:13px;">{{ $k->description }}</td>
                <td style="padding:12px 16px;text-align:center;">
                    <span style="padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;" class="{{ $cls }}">
                        {{ ucfirst($k->color) }}
                    </span>
                </td>
                <td style="padding:12px 16px;text-align:center;">
                    @if($k->is_aktif)
                        <span style="background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;">Aktif</span>
                    @else
                        <span style="background:#f3f4f6;color:#6b7280;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;">Nonaktif</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.keunggulan.edit', $k->id) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.keunggulan.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                <td colspan="8" style="padding:40px;text-align:center;color:#9ca3af;">
                    Belum ada data. <a href="{{ route('admin.keunggulan.create') }}">Tambah sekarang</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection