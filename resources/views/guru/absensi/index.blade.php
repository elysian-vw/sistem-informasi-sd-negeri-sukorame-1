@extends('layouts.guru')
@section('title', 'Absensi Kelas')

@section('content')
<div class="page-header">
    <h1 class="page-title">Absensi Harian</h1>
    <p class="page-subtitle">Kelola kehadiran siswa Kelas {{ $kelas->nama_kelas }}</p>
</div>

{{-- Filter Tanggal --}}
<div class="content-card" style="margin-bottom:20px; padding:20px;">
    <form action="{{ route('guru.absensi.index') }}" method="GET" style="display:flex; gap:16px; align-items:flex-end;">
        <div>
            <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:8px;">Pilih Tanggal Absensi</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" style="width:200px;" onchange="this.form.submit()">
        </div>
        <div>
            <button type="submit" class="btn btn-secondary" style="padding:10px 16px;">
                <i class="fas fa-sync-alt"></i> Muat Ulang
            </button>
        </div>
    </form>
</div>

{{-- Form Input Absensi --}}
<div class="content-card">
    <div class="card-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:16px 20px;">
        <h3 style="margin:0; font-size:16px;">
            <i class="fas fa-clipboard-list" style="color:var(--primary); margin-right:8px;"></i> 
            Daftar Siswa ({{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }})
        </h3>
    </div>
    
    <form action="{{ route('guru.absensi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div style="overflow-x:auto;">
            <table class="data-table" style="width:100%; border-collapse:collapse;">
                <thead style="background:#f1f5f9;">
                    <tr>
                        <th style="padding:12px 16px; text-align:left; font-size:13px; color:#475569; width:50px;">No</th>
                        <th style="padding:12px 16px; text-align:left; font-size:13px; color:#475569;">Nama Siswa</th>
                        <th style="padding:12px 16px; text-align:center; font-size:13px; color:#475569; width:350px;">Kehadiran</th>
                        <th style="padding:12px 16px; text-align:left; font-size:13px; color:#475569;">Keterangan (Opsional)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaKelas as $index => $siswa)
                        @php 
                            $absen = $absensiHariIni->get($siswa->id); 
                            
                            // DISINI PENGATURANNYA: 
                            // Default 'hadir' agar guru tidak perlu klik satu-satu untuk siswa yang masuk.
                            $status = $absen ? $absen->status : 'hadir'; 
                        @endphp
                        <tr style="border-bottom:1px solid #e2e8f0;">
                            <td style="padding:12px 16px; color:#64748b;">{{ $index + 1 }}</td>
                            <td style="padding:12px 16px; font-weight:500; color:#1e293b;">
                                {{ $siswa->nama_lengkap }}
                                <div style="font-size:11px; color:#94a3b8; font-weight:normal;">NISN: {{ $siswa->nisn ?? '-' }}</div>
                            </td>
                            <td style="padding:12px 16px;">
                                <div class="radio-group" style="display:flex; gap:12px; justify-content:center;">
                                    <label class="radio-label status-hadir">
                                        <input type="radio" name="status[{{ $siswa->id }}]" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }} required> Hadir
                                    </label>
                                    <label class="radio-label status-sakit">
                                        <input type="radio" name="status[{{ $siswa->id }}]" value="sakit" {{ $status == 'sakit' ? 'checked' : '' }} required> Sakit
                                    </label>
                                    <label class="radio-label status-izin">
                                        <input type="radio" name="status[{{ $siswa->id }}]" value="izin" {{ $status == 'izin' ? 'checked' : '' }} required> Izin
                                    </label>
                                    <label class="radio-label status-alpha">
                                        <input type="radio" name="status[{{ $siswa->id }}]" value="alpha" {{ $status == 'alpha' ? 'checked' : '' }} required> Alpha
                                    </label>
                                </div>
                            </td>
                            <td style="padding:12px 16px;">
                                <input type="text" name="keterangan[{{ $siswa->id }}]" value="{{ $absen->keterangan ?? '' }}" class="form-control" placeholder="Tulis alasan..." style="width:100%; padding:8px 12px; font-size:13px;">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:30px; color:#64748b;">Belum ada siswa yang terdaftar di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswaKelas->count() > 0)
        <div style="padding:20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; position:sticky; bottom:0;">
            <button type="submit" class="btn btn-primary" style="padding:12px 24px; font-size:14px;">
                <i class="fas fa-save" style="margin-right:8px;"></i> Simpan Data Absensi
            </button>
        </div>
        @endif
    </form>
</div>

<style>
    .form-control { border:1px solid #cbd5e1; border-radius:6px; background:#fff; transition:all 0.2s; }
    .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    
    .radio-label {
        display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer; padding:6px 10px; border-radius:6px; border:1px solid #e2e8f0; background:#f8fafc; transition:all 0.2s;
    }
    .radio-label:hover { background:#f1f5f9; }
    
    /* Styling interaktif untuk status */
    .status-hadir:has(input:checked) { background:#dcfce7; border-color:#22c55e; color:#166534; font-weight:600; }
    .status-sakit:has(input:checked) { background:#fef08a; border-color:#eab308; color:#854d0e; font-weight:600; }
    .status-izin:has(input:checked) { background:#e0f2fe; border-color:#3b82f6; color:#1e40af; font-weight:600; }
    .status-alpha:has(input:checked) { background:#fee2e2; border-color:#ef4444; color:#991b1b; font-weight:600; }
    
    input[type="radio"] { cursor:pointer; }
</style>
@endsection