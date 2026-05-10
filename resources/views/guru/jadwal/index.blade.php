@extends('layouts.guru')
@section('title', 'Jadwal Pelajaran')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Jadwal Pelajaran</h1>
        <p class="page-subtitle">Jadwal kelas Anda — {{ now()->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
</div>

@php
$warna = [
    'senin'   => ['bg'=>'#e3f2fd','color'=>'#1565c0','border'=>'#90caf9'],
    'selasa'  => ['bg'=>'#f3e5f5','color'=>'#6a1b9a','border'=>'#ce93d8'],
    'rabu'    => ['bg'=>'#e8f5e9','color'=>'#2e7d32','border'=>'#a5d6a7'],
    'kamis'   => ['bg'=>'#fff3e0','color'=>'#e65100','border'=>'#ffcc80'],
    'jumat'   => ['bg'=>'#fce4ec','color'=>'#880e4f','border'=>'#f48fb1'],
    'sabtu'   => ['bg'=>'#f1f8e9','color'=>'#33691e','border'=>'#c5e1a5'],
];
$labelHari = [
    'senin'=>'Senin','selasa'=>'Selasa','rabu'=>'Rabu',
    'kamis'=>'Kamis','jumat'=>'Jumat','sabtu'=>'Sabtu',
];
@endphp

@if($jadwalTerurut->isEmpty())
    <div class="content-card">
        <div style="text-align:center;padding:40px;color:var(--text-muted);">
            <i class="fas fa-calendar-xmark" style="font-size:36px;display:block;margin-bottom:12px;opacity:.4;"></i>
            Belum ada jadwal untuk kelas Anda.
        </div>
    </div>
@else
    @foreach($jadwalTerurut as $hari => $items)
    @php $w = $warna[$hari] ?? ['bg'=>'#f5f5f5','color'=>'#333','border'=>'#ddd']; @endphp
    <div class="content-card" style="margin-bottom:20px;{{ $hari === $hariIni ? 'border:2px solid var(--primary);' : '' }}">
        <div class="card-header" style="background:{{ $w['bg'] }};border-bottom:1px solid {{ $w['border'] }};">
            <h3 style="color:{{ $w['color'] }};margin:0;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-calendar-day"></i>
                {{ $labelHari[$hari] ?? ucfirst($hari) }}
                @if($hari === $hariIni)
                    <span class="badge badge-blue" style="font-size:11px;">Hari Ini</span>
                @endif
            </h3>
            <span style="font-size:12px;color:{{ $w['color'] }};opacity:.8;">{{ $items->count() }} sesi</span>
        </div>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Waktu</th>
                        <th>Ruangan</th>
                        <th>Semester</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items->sortBy('jam_ke') as $j)
                    <tr style="{{ $hari === $hariIni ? 'background:rgba(26,115,232,0.03);' : '' }}">
                        <td style="text-align:center;">
                            <span style="width:28px;height:28px;border-radius:50%;background:{{ $w['bg'] }};color:{{ $w['color'] }};display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;">
                                {{ $j->jam_ke }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $j->mataPelajaran->nama ?? '-' }}</div>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $j->mataPelajaran->jenis ?? '' }}</div>
                        </td>
                        <td style="font-size:13px;">{{ $j->guru->user->name ?? '-' }}</td>
                        <td style="font-size:13px;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }}
                        </td>
                        <td style="font-size:13px;">{{ $j->ruangan ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $j->semester == '1' ? 'badge-blue' : 'badge-green' }}">
                                Sem {{ $j->semester }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
@endif

@endsection