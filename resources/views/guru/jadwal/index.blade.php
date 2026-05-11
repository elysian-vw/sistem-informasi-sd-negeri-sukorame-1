@extends('layouts.guru')
@section('title', 'Jadwal Pelajaran')

@section('content')

@php
$guru      = auth()->user()->guru;
$kelasNama = $guru->kelas->nama_kelas ?? '-';
$hariList  = ['senin','selasa','rabu','kamis','jumat','sabtu'];
$labelHari = [
    'senin'  => 'SENIN',
    'selasa' => 'SELASA',
    'rabu'   => 'RABU',
    'kamis'  => 'KAMIS',
    'jumat'  => 'JUM\'AT',
    'sabtu'  => 'SABTU',
];

// Slot waktu lengkap: jam pelajaran + istirahat
$slots = [
    ['no'=>1,  'waktu'=>'07.00 – 07.35', 'tipe'=>'jp',        'jam_ke'=>1],
    ['no'=>2,  'waktu'=>'07.35 – 08.10', 'tipe'=>'jp',        'jam_ke'=>2],
    ['no'=>3,  'waktu'=>'08.10 – 08.45', 'tipe'=>'jp',        'jam_ke'=>3],
    ['no'=>4,  'waktu'=>'08.45 – 09.15', 'tipe'=>'jp',        'jam_ke'=>4],
    ['no'=>'', 'waktu'=>'09.15 – 09.30', 'tipe'=>'istirahat', 'jam_ke'=>null],
    ['no'=>5,  'waktu'=>'09.30 – 10.05', 'tipe'=>'jp',        'jam_ke'=>5],
    ['no'=>6,  'waktu'=>'10.05 – 10.40', 'tipe'=>'jp',        'jam_ke'=>6],
    ['no'=>'', 'waktu'=>'10.40 – 10.55', 'tipe'=>'istirahat', 'jam_ke'=>null],
    ['no'=>7,  'waktu'=>'10.55 – 11.30', 'tipe'=>'jp',        'jam_ke'=>7],
    ['no'=>8,  'waktu'=>'11.30 – 12.05', 'tipe'=>'jp',        'jam_ke'=>8],
    ['no'=>9,  'waktu'=>'12.05 – 12.40', 'tipe'=>'jp',        'jam_ke'=>9],
];

// Bangun grid [hari][jam_ke] => item
$grid = [];
foreach ($jadwal as $j) {
    $grid[$j->hari][$j->jam_ke] = $j;
}

// Helper: singkat nama mapel (hapus "Kelas X" di belakang)
function singkatMapel($nama) {
    $nama = preg_replace('/\s+Kelas\s+\d+$/i', '', $nama ?? '-');
    $singkatan = [
        'Bahasa Indonesia'         => 'B. Indonesia',
        'Bahasa Inggris'           => 'B. Inggris',
        'Bahasa Daerah'            => 'Mulok',
        'Pendidikan Agama Islam'   => 'Pend. Agama',
        'Pendidikan Pancasila'     => 'Pend. Pancasila',
        'Seni Budaya'              => 'Seni Budaya',
        'Matematika'               => 'Matematika',
        'PJOK'                     => 'PJOK',
        'IPA'                      => 'IPA',
        'IPS'                      => 'IPS',
        'PPKn'                     => 'PPKn',
    ];
    foreach ($singkatan as $panjang => $pendek) {
        if (str_starts_with($nama, $panjang)) return $pendek;
    }
    return \Illuminate\Support\Str::limit($nama, 16);
}
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Jadwal Pelajaran</h1>
        <p class="page-subtitle">Kelas {{ $kelasNama }} &nbsp;·&nbsp; Tahun Pelajaran {{ $tahunAjaran }}</p>
    </div>
</div>

<div class="content-card" style="padding:0;overflow:hidden;">

    {{-- Info header --}}
    <div style="background:var(--primary);color:#fff;padding:14px 20px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div>
            <div style="font-size:10px;opacity:.75;letter-spacing:.5px;text-transform:uppercase;">Kelas</div>
            <div style="font-weight:700;font-size:16px;">{{ $kelasNama }} ({{ ['1'=>'SATU','2'=>'DUA','3'=>'TIGA','4'=>'EMPAT','5'=>'LIMA','6'=>'ENAM'][$kelasNama] ?? $kelasNama }})</div>
        </div>
        <div>
            <div style="font-size:10px;opacity:.75;letter-spacing:.5px;text-transform:uppercase;">Semester</div>
            <div style="font-weight:700;font-size:16px;">{{ $semesterAktif }} ({{ $semesterAktif==='1' ? 'SATU' : 'DUA' }})</div>
        </div>
        <div>
            <div style="font-size:10px;opacity:.75;letter-spacing:.5px;text-transform:uppercase;">Tahun Pelajaran</div>
            <div style="font-weight:700;font-size:16px;">{{ $tahunAjaran }}</div>
        </div>
        <div>
            <div style="font-size:10px;opacity:.75;letter-spacing:.5px;text-transform:uppercase;">Wali Kelas</div>
            <div style="font-weight:700;font-size:16px;">{{ auth()->user()->name }}</div>
        </div>
    </div>

    {{-- Tabel jadwal --}}
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;min-width:700px;font-size:12px;">
            <thead>
                <tr>
                    <th style="padding:10px 8px;text-align:center;border:2px solid #334155;background:#1e293b;color:#fff;width:36px;">NO</th>
                    <th style="padding:10px 8px;text-align:center;border:2px solid #334155;background:#1e293b;color:#fff;width:115px;">HARI/WAKTU</th>
                    @foreach($hariList as $h)
                    <th style="padding:10px 8px;text-align:center;border:2px solid #334155;
                                background:{{ $h === $hariIni ? '#1d4ed8' : '#1e293b' }};
                                color:#fff;">
                        {{ $labelHari[$h] }}
                        @if($h === $hariIni)
                            <div style="font-size:9px;font-weight:400;margin-top:2px;color:#bfdbfe;">● hari ini</div>
                        @endif
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($slots as $idx => $slot)

                @if($slot['tipe'] === 'istirahat')
                {{-- Baris istirahat --}}
                <tr>
                    <td style="padding:5px 8px;text-align:center;border:1px solid #94a3b8;background:#fef3c7;color:#92400e;font-size:10px;font-weight:700;">–</td>
                    <td style="padding:5px 8px;text-align:center;border:1px solid #94a3b8;background:#fef3c7;color:#92400e;font-size:10px;font-style:italic;font-weight:600;">{{ $slot['waktu'] }}</td>
                    <td colspan="6" style="padding:5px;text-align:center;border:1px solid #94a3b8;background:#fef3c7;color:#92400e;font-weight:700;font-size:11px;letter-spacing:2px;">
                        I S T I R A H A T
                    </td>
                </tr>

                @else
                {{-- Baris jam pelajaran --}}
                @php
                    $jamKe  = $slot['jam_ke'];
                    $rowBg  = $idx % 2 === 0 ? '#f8fafc' : '#ffffff';
                @endphp
                <tr style="background:{{ $rowBg }};">
                    <td style="padding:10px 8px;text-align:center;border:1px solid #cbd5e1;font-weight:700;color:var(--primary);font-size:14px;">{{ $slot['no'] }}</td>
                    <td style="padding:10px 8px;text-align:center;border:1px solid #cbd5e1;color:#64748b;white-space:nowrap;font-size:11px;font-style:italic;">{{ $slot['waktu'] }}</td>

                    @foreach($hariList as $h)
                    @php
                        $item    = $grid[$h][$jamKe] ?? null;
                        $isMulok = $item && ($item->mataPelajaran->jenis ?? '') === 'mulok';
                        $isMe    = $item && $item->guru_id === $guru->id;
                        $isHariIni = $h === $hariIni;

                        if ($isMulok)         $cellBg = '#dcfce7';
                        elseif ($isHariIni)   $cellBg = '#eff6ff';
                        else                  $cellBg = 'transparent';
                    @endphp
                    <td style="padding:8px 6px;border:1px solid #cbd5e1;text-align:center;vertical-align:middle;background:{{ $cellBg }};">
                        @if($item)
                        <div style="font-weight:{{ $isMe ? '700' : '600' }};font-size:12px;
                                    color:{{ $isMulok ? '#166534' : ($isMe ? 'var(--primary)' : '#374151') }};
                                    line-height:1.3;">
                            {{ singkatMapel($item->mataPelajaran->nama ?? '-') }}
                        </div>
                        @if($item->guru && $item->guru->user)
                        <div style="font-size:9px;color:#94a3b8;margin-top:3px;">
                            {{ collect(explode(' ', $item->guru->user->name ?? ''))->take(2)->implode(' ') }}
                        </div>
                        @endif
                        @else
                        <span style="color:#e2e8f0;font-size:16px;">·</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Footer legend --}}
    <div style="padding:12px 20px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;background:#f8fafc;">
        <div style="display:flex;gap:16px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#64748b;">
                <div style="width:12px;height:12px;border-radius:2px;background:#dcfce7;border:1px solid #86efac;"></div>
                Muatan Lokal (Mulok)
            </div>
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#64748b;">
                <div style="width:12px;height:12px;border-radius:2px;background:#eff6ff;border:1px solid #93c5fd;"></div>
                Hari Ini
            </div>
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:#64748b;">
                <div style="width:12px;height:12px;border-radius:2px;background:#fef3c7;border:1px solid #fcd34d;"></div>
                Istirahat
            </div>
            <div style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--primary);font-weight:600;">
                Tebal biru = jadwal Anda
            </div>
        </div>
        <div style="font-size:11px;color:#94a3b8;">
            {{ $jadwal->count() }} sesi &nbsp;·&nbsp; Semester {{ $semesterAktif }} &nbsp;·&nbsp; {{ $tahunAjaran }}
        </div>
    </div>
</div>

@endsection