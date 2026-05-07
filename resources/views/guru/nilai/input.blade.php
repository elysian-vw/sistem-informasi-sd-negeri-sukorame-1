@extends('layouts.guru')
@section('title', 'Input Nilai')

@section('content')
<div class="page-header">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="{{ route('guru.nilai.index') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h1 class="page-title">Input Nilai: {{ $mapel->nama }}</h1>
            <p class="page-subtitle">Kelas {{ $kelas->nama_kelas }} | Tahun Ajaran {{ $tahunAjaran }}</p>
        </div>
    </div>
</div>

<div class="content-card">
    <form action="{{ route('guru.nilai.store', $mapel->id) }}" method="POST">
        @csrf
        
        {{-- Data Sembunyi untuk Periode --}}
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
                        <th style="padding:12px; text-align:center;">Nilai Akhir (Sistem)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaKelas as $index => $siswa)
                        @php
                            $n = $nilaiExisting->get($siswa->id);
                        @endphp
                        <tr>
                            <td style="padding:12px;">{{ $index + 1 }}</td>
                            <td style="padding:12px; font-weight:500;">{{ $siswa->nama_lengkap }}</td>
                            
                            {{-- Input Nilai Tugas --}}
                            <td style="padding:12px; text-align:center;">
                                <input type="number" name="nilai_tugas[{{ $siswa->id }}]" value="{{ $n->nilai_tugas ?? '' }}" min="0" max="100" class="form-control" style="width:80px; text-align:center;" placeholder="0">
                            </td>
                            
                            {{-- Input Nilai UTS --}}
                            <td style="padding:12px; text-align:center;">
                                <input type="number" name="nilai_uts[{{ $siswa->id }}]" value="{{ $n->nilai_uts ?? '' }}" min="0" max="100" class="form-control" style="width:80px; text-align:center;" placeholder="0">
                            </td>
                            
                            {{-- Input Nilai UAS --}}
                            <td style="padding:12px; text-align:center;">
                                <input type="number" name="nilai_uas[{{ $siswa->id }}]" value="{{ $n->nilai_uas ?? '' }}" min="0" max="100" class="form-control" style="width:80px; text-align:center;" placeholder="0">
                            </td>

                            {{-- Display Nilai Akhir Otomatis --}}
                            <td style="padding:12px; text-align:center; font-weight:bold; color:var(--primary);">
                                {{ $n->nilai_akhir ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:20px; text-align:right; border-top:1px solid #e2e8f0; background:#f8fafc; margin-top:20px;">
            <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                <i class="fas fa-save"></i> Simpan Nilai Rapor
            </button>
        </div>
    </form>
</div>
@endsection