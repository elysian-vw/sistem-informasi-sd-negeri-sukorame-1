@extends('layouts.guru')
@section('title', 'Absensi Kelas')

@section('content')
<div class="page-header">
    <h1 class="page-title">Absensi Harian</h1>
    <p class="page-subtitle">Kelola kehadiran siswa Kelas {{ $kelas->nama_kelas }}</p>
</div>

{{-- Filter Tanggal --}}
<div class="content-card" style="margin-bottom:20px; padding:20px;">
    <form action="{{ route('guru.absensi.index') }}" method="GET" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
        <div style="flex:1; min-width:200px;">
            <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:8px;">Pilih Tanggal Absensi</label>
            <div style="position:relative;">
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" style="width:100%;" onchange="this.form.submit()">
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-secondary" style="padding:10px 16px;">
                <i class="fas fa-sync-alt"></i> Muat Ulang
            </button>
        </div>
    </form>
</div>

@if($absensiHariIni->count() > 0)
    <div class="alert alert-info" style="margin-bottom:20px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:12px 16px; border-radius:8px; display:flex; align-items:center; gap:10px; font-size:14px;">
        <i class="fas fa-info-circle"></i>
        <span>Data absensi tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong> sudah ada. Anda dapat mengeditnya di bawah.</span>
    </div>
@endif

{{-- Form Input Absensi --}}
<div class="content-card">
    <div class="card-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:16px 20px; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="margin:0; font-size:16px;">
            <i class="fas fa-clipboard-list" style="color:var(--primary); margin-right:8px;"></i> 
            Daftar Siswa
        </h3>
        <span class="badge badge-light" style="font-size:12px; font-weight:600;">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</span>
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
                        <th style="padding:12px 16px; text-align:center; font-size:13px; color:#475569; width:300px;">Kehadiran</th>
                        <th style="padding:12px 16px; text-align:left; font-size:13px; color:#475569;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaKelas as $index => $siswa)
                        @php 
                            $absen = $absensiHariIni->get($siswa->id); 
                            $status = $absen ? $absen->status : 'hadir'; 
                        @endphp
                        <tr style="border-bottom:1px solid #e2e8f0;">
                            <td style="padding:12px 16px; color:#64748b; text-align:center;">{{ $index + 1 }}</td>
                            <td style="padding:12px 16px;">
                                <div style="font-weight:600; color:#1e293b;">{{ $siswa->nama_lengkap }}</div>
                                <div style="font-size:11px; color:#94a3b8;">NISN: {{ $siswa->nisn ?? '-' }}</div>
                            </td>
                            <td style="padding:12px 16px;">
                                <div class="radio-group" style="display:flex; gap:8px; justify-content:center;">
                                    @foreach(['hadir' => 'H', 'sakit' => 'S', 'izin' => 'I', 'alpha' => 'A'] as $val => $label)
                                        <label class="radio-mini status-{{ $val }}" title="{{ ucfirst($val) }}">
                                            <input type="radio" name="status[{{ $siswa->id }}]" value="{{ $val }}" {{ $status == $val ? 'checked' : '' }} required>
                                            <span>{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                            <td style="padding:12px 16px;">
                                <input type="text" name="keterangan[{{ $siswa->id }}]" value="{{ $absen->keterangan ?? '' }}" class="form-control" placeholder="..." style="width:100%; padding:6px 10px; font-size:12px;">
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
        <div style="padding:20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end;">
            <button type="submit" class="btn btn-primary" style="padding:10px 24px; font-weight:700;">
                <i class="fas fa-save" style="margin-right:8px;"></i> {{ $absensiHariIni->count() > 0 ? 'Perbarui Absensi' : 'Simpan Absensi' }}
            </button>
        </div>
        @endif
    </form>
</div>

{{-- Rekap Histori Detail di Bawah Form --}}
<div class="content-card" style="margin-top:24px;">
    <div class="card-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:16px 20px;">
        <h3 style="margin:0; font-size:15px; font-weight:700;">
            <i class="fas fa-table" style="color:var(--primary); margin-right:8px;"></i> 
            Rekap Riwayat Absensi Terakhir
        </h3>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table" style="width:100%; border-collapse:collapse;">
            <thead style="background:#f1f5f9;">
                <tr>
                    <th style="padding:12px 16px; text-align:left; font-size:12px; color:#475569;">Hari, Tanggal</th>
                    <th style="padding:12px 16px; text-align:center; font-size:12px; color:#475569;">Hadir</th>
                    <th style="padding:12px 16px; text-align:center; font-size:12px; color:#475569;">Sakit</th>
                    <th style="padding:12px 16px; text-align:center; font-size:12px; color:#475569;">Izin</th>
                    <th style="padding:12px 16px; text-align:center; font-size:12px; color:#475569;">Alpha</th>
                    <th style="padding:12px 16px; text-align:center; font-size:12px; color:#475569;">Total Siswa</th>
                    <th style="padding:12px 16px; text-align:center; font-size:12px; color:#475569;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatAbsensi as $r)
                <tr style="border-bottom:1px solid #f1f5f9;">
                    <td style="padding:12px 16px; font-size:13.5px;">
                        <strong>{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('l') }}</strong>, 
                        {{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d F Y') }}
                    </td>
                    <td style="padding:12px 16px; text-align:center;"><span class="count-badge count-hadir">{{ $r->hadir }}</span></td>
                    <td style="padding:12px 16px; text-align:center;"><span class="count-badge count-sakit">{{ $r->sakit }}</span></td>
                    <td style="padding:12px 16px; text-align:center;"><span class="count-badge count-izin">{{ $r->izin }}</span></td>
                    <td style="padding:12px 16px; text-align:center;"><span class="count-badge count-alpha">{{ $r->alpha }}</span></td>
                    <td style="padding:12px 16px; text-align:center; font-weight:600; color:#475569;">
                        {{ $r->hadir + $r->sakit + $r->izin + $r->alpha }}
                    </td>
                    <td style="padding:12px 16px; text-align:center;">
                        <a href="{{ route('guru.absensi.index', ['tanggal' => $r->tanggal]) }}" class="btn btn-sm" 
                           style="background:#eff6ff; color:#2563eb; font-size:12px; font-weight:600; padding:4px 10px; border-radius:6px; text-decoration:none; border:1px solid #dbeafe;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:30px; color:#94a3b8; font-size:13.5px;">Belum ada data riwayat absensi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .count-badge {
        display: inline-block; min-width: 24px; padding: 2px 8px; border-radius: 6px; font-size: 12px; font-weight: 700;
    }
    .count-hadir { background: #dcfce7; color: #15803d; }
    .count-sakit { background: #fef9c3; color: #a16207; }
    .count-izin  { background: #e0f2fe; color: #0369a1; }
    .count-alpha { background: #fee2e2; color: #b91c1c; }

    .form-control { border:1px solid #cbd5e1; border-radius:6px; background:#fff; transition:all 0.2s; }
    .form-control:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
    
    /* Radio Mini */
    .radio-mini {
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        border-radius: 8px; border: 1px solid #e2e8f0; background: #fff; cursor: pointer;
        font-size: 13px; font-weight: 700; transition: all 0.2s; position: relative;
    }
    .radio-mini input { position: absolute; opacity: 0; cursor: pointer; }
    .radio-mini span { color: #64748b; }
    
    .status-hadir:hover { border-color: #22c55e; background: #f0fdf4; }
    .status-hadir:has(input:checked) { background: #22c55e; border-color: #22c55e; }
    .status-hadir:has(input:checked) span { color: #fff; }
    
    .status-sakit:hover { border-color: #eab308; background: #fefce8; }
    .status-sakit:has(input:checked) { background: #eab308; border-color: #eab308; }
    .status-sakit:has(input:checked) span { color: #fff; }
    
    .status-izin:hover { border-color: #3b82f6; background: #eff6ff; }
    .status-izin:has(input:checked) { background: #3b82f6; border-color: #3b82f6; }
    .status-izin:has(input:checked) span { color: #fff; }
    
    .status-alpha:hover { border-color: #ef4444; background: #fef2f2; }
    .status-alpha:has(input:checked) { background: #ef4444; border-color: #ef4444; }
    .status-alpha:has(input:checked) span { color: #fff; }
</style>
@endsection