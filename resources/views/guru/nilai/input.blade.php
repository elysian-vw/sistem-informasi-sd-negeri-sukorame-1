@extends('layouts.guru')
@section('title', 'Input Nilai')

@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('guru.nilai.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="page-title">Input Nilai: {{ $mapel->nama_mapel ?? $mapel->nama }}</h1>
            <p class="page-subtitle">Kelas {{ $kelas->nama_kelas }} | Tahun Ajaran {{ $tahunAjaran }}</p>
        </div>
    </div>
</div>

{{-- SECTION BARU: Tombol Ekspor & Impor --}}
<div class="content-card" style="margin-bottom: 20px; border-left: 4px solid var(--primary);">
    <div class="card-body" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h3 style="margin:0 0 5px 0; font-size: 14px;">Manajemen Data Excel</h3>
            <p style="margin:0; font-size: 12px; color: #64748b;">Gunakan Excel untuk input nilai massal.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="{{ route('guru.nilai.export', $mapel->id) }}" class="btn btn-primary" style="background: #0ea5e9; border: none; padding: 8px 16px;">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            
            <form action="{{ route('guru.nilai.import', $mapel->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 5px;">
                @csrf
                <input type="file" name="file" accept=".xlsx, .xls" required style="max-width: 200px; font-size: 12px;">
                <button type="submit" class="btn btn-success" style="background: #16a34a; border: none; padding: 8px 16px;">
                    <i class="fas fa-upload"></i> Import
                </button>
            </form>
        </div>
    </div>
</div>

<div class="content-card">
    <form action="{{ route('guru.nilai.store', $mapel->id) }}" method="POST">
        @csrf
        <input type="hidden" name="semester" value="{{ $semesterAktif }}">
        <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">

        <div style="overflow-x:auto;">
            <table class="data-table" style="width:100%; border-collapse:collapse;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th style="padding:12px; text-align:left;">No</th>
                        <th style="padding:12px; text-align:left;">Nama Siswa</th>
                        <th style="padding:12px; text-align:center;">Nilai Tugas</th>
                        <th style="padding:12px; text-align:center;">Nilai UTS</th>
                        <th style="padding:12px; text-align:center;">Nilai UAS</th>
                        <th style="padding:12px; text-align:center;">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaKelas as $index => $siswa)
                        @php $n = $nilaiExisting->get($siswa->id); @endphp
                        <tr>
                            <td style="padding:12px;">{{ $index + 1 }}</td>
                            <td style="padding:12px; font-weight:500;">{{ $siswa->nama_lengkap }}</td>
                            <td style="padding:12px; text-align:center;">
                                <input type="number" name="nilai_tugas[{{ $siswa->id }}]" value="{{ $n->nilai_tugas ?? '' }}" min="0" max="100" class="form-control" style="width:70px; text-align:center;">
                            </td>
                            <td style="padding:12px; text-align:center;">
                                <input type="number" name="nilai_uts[{{ $siswa->id }}]" value="{{ $n->nilai_uts ?? '' }}" min="0" max="100" class="form-control" style="width:70px; text-align:center;">
                            </td>
                            <td style="padding:12px; text-align:center;">
                                <input type="number" name="nilai_uas[{{ $siswa->id }}]" value="{{ $n->nilai_uas ?? '' }}" min="0" max="100" class="form-control" style="width:70px; text-align:center;">
                            </td>
                            <td style="padding:12px; text-align:center; font-weight:bold; color:var(--primary);">
                                {{ $n->nilai_akhir ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:20px; text-align:right; border-top:1px solid #e2e8f0; margin-top:20px;">
            <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                <i class="fas fa-save"></i> Simpan Semua Nilai
            </button>
        </div>
    </form>
</div>
@endsection