@extends('layouts.siswa')
@section('title', 'Tugas Saya')

@section('content')

@php
// Mapping warna & ikon berdasarkan nama Mata Pelajaran dari database
$subjectStyles = [
    'matematika'      => ['bg' => '#F5F3FF', 'accent' => '#7C3AED', 'badge' => '#8B5CF6', 'icon' => '🔢'],
    'ipa'               => ['bg' => '#ECFDF5', 'accent' => '#059669', 'badge' => '#10B981', 'icon' => '🌱'],
    'bahasa indonesia'  => ['bg' => '#FFFBEB', 'accent' => '#D97706', 'badge' => '#F59E0B', 'icon' => '📖'],
    'seni budaya'       => ['bg' => '#FFF1F2', 'accent' => '#E11D48', 'badge' => '#F43F5E', 'icon' => '🎨'],
    'bahasa inggris' => [
        'bg' => '#EFF6FF',
        'accent' => '#2563EB',
        'badge' => '#3B82F6',
        'icon' => '📚'
    ],
];

$defaultStyle = ['bg' => '#F9FAFB', 'accent' => '#6B7280', 'badge' => '#9CA3AF', 'icon' => '📝'];

function getStyle(array $map, $nama, array $default): array {
    $nama = strtolower(trim($nama ?? ''));
    foreach ($map as $key => $val) {
        if (str_contains($nama, $key)) return $val;
    }
    return $default;
}
@endphp

<style>
    .tugas-page { padding: 10px 0 50px; font-family: 'Nunito', sans-serif; }
    .page-hero {
        text-align: center;
        margin-bottom: 35px;

        background: linear-gradient(
            135deg,
            #6c5ce7,
            #6c5ce7,
            #bfbbf7
        );

        padding: 35px;
        border-radius: 25px;
    }
    .page-hero h1 {
        font-size: 32px;
        font-weight: 900;
        margin-bottom: 5px;

        background: #ffff;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .page-hero p {
        color: #FFFFFF;
        font-size: 15px;
        font-weight: 500;
    }
    .tugas-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        background: linear-gradient(135deg,#DBEAFE,#BFDBFE);
        color: #1D4ED8;

        padding: 12px 24px;
        border-radius: 25px;
        font-weight: 800;

        border: 2px solid #93C5FD;
        margin-bottom: 30px;

        box-shadow: 0 4px 0 #93C5FD;
    }

    .tugas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .tugas-card {
        background: #fff; border-radius: 25px;
        border: 2px solid #E5E7EB; overflow: hidden;
        display: flex; flex-direction: column;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .tugas-card:hover {
        transform: translateY(-8px);

        border-color: #60A5FA;

        box-shadow:
            0 12px 30px rgba(37,99,235,.15);
    }

    .card-top { 
        padding: 20px; height: 140px; 
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        position: relative;
    }

    .mapel-tag {
        position: absolute; top: 12px; left: 12px;
        padding: 4px 12px; border-radius: 10px;
        font-size: 11px; font-weight: 800; color: #fff;
    }

    .tipe-pill {
        position: absolute; top: 12px; right: 12px;
        background: rgba(255,255,255,0.8);
        padding: 4px 10px; border-radius: 10px;
        font-size: 11px; font-weight: 700; color: #4B5563;
    }

    .illustration { font-size: 55px; margin-top: 15px; }

    .card-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .card-judul { font-size: 17px; font-weight: 800; color: #1F2937; margin-bottom: 10px; line-height: 1.4; }
    
    .guru-row { display: flex; align-items: center; gap: 8px; margin-bottom: 15px; }
    .avatar-mini { width: 28px; height: 28px; border-radius: 50%; display: flex; 
                   align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 11px; }

    .deadline-chip {
        padding: 8px 12px; border-radius: 12px; font-size: 12px; font-weight: 700;
        margin-bottom: 15px; display: flex; align-items: center; gap: 6px;
    }
    .urgent { background: #FEF2F2; color: #B91C1C; }
    .normal { background: #F0FDF4; color: #166534; }

    .btn-main {
        width: 100%; padding: 12px; border-radius: 15px;
        text-align: center; font-weight: 800; text-decoration: none;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all 0.2s; border: 2px solid #E5E7EB; background: #fff; color: #111;
    }
    .btn-main:hover {
        background: #EFF6FF;
        border-color: #60A5FA;
        transform: scale(1.02);
    }
</style>

<div class="tugas-page">
    <div class="page-hero">
        <span>⭐ ☀️ ⭐</span>
        <h1>Tugasku Hari Ini!</h1>
        <p>Ayo semangat kerjakan tugasnya ya!</p>
    </div>

    @if($tugas->total() > 0)
    <div style="text-align:center;">
        <div class="tugas-count-badge">
            📋 Ada <strong>{{ $tugas->total() }}</strong> tugas menunggu kamu!
        </div>
    </div>
    @endif

    <div class="tugas-grid">
        @forelse($tugas as $t)
            @php
                // Mengambil nama mapel dari relasi mata_pelajaran_id
                $namaMapel = $t->mataPelajaran->nama ?? 'Umum';
                $style = getStyle($subjectStyles, $namaMapel, $defaultStyle);
                $namaGuru = $t->guru->user->name ?? 'Guru';
                
                // Cek Tipe berdasarkan migrasi (upload / cbt)
                $isCbt = ($t->tipe === 'cbt');
                
                // Deadline logic
                $isUrgent = $t->deadline && now()->diffInHours($t->deadline, false) <= 24;
            @endphp

            <div class="tugas-card">
                <div class="card-top" style="background-color: {{ $style['bg'] }}">
                    <span class="mapel-tag" style="background: {{ $style['badge'] }}">{{ $namaMapel }}</span>
                    <span class="tipe-pill">
                        {{ $isCbt ? '🎮 Kuis' : '📁 Upload' }}
                    </span>
                    <div class="illustration">{{ $style['icon'] }}</div>
                </div>

                <div class="card-body">
                    <div class="card-judul">{{ $t->judul }}</div>
                    
                    <div class="guru-row">
                        <div class="avatar-mini" style="background: {{ $style['accent'] }}">
                            {{ strtoupper(substr($namaGuru, 0, 1)) }}
                        </div>
                        <span style="color: #6B7280; font-size: 13px;">{{ $namaGuru }}</span>
                    </div>

                    @if($t->deadline)
                        <div class="deadline-chip {{ $isUrgent ? 'urgent' : 'normal' }}">
                            @if($isUrgent)
                                ⚡ Sisa {{ now()->diffForHumans($t->deadline, ['parts' => 1, 'short' => true]) }}
                            @else
                                ⏰ {{ $t->deadline->isoFormat('D MMM, HH:mm') }}
                            @endif
                        </div>
                    @else
                        <div class="deadline-chip normal">✅ Bebas Waktu</div>
                    @endif

                    <a href="{{ route('siswa.tugas.show', $t->id) }}" class="btn-main">
                        {{ $isCbt ? '🎮 Mulai Kuis!' : '📤 Kumpulkan!' }}
                    </a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                <div style="font-size: 60px;">🎉</div>
                <h3 style="font-weight: 800; margin-top: 10px;">Semua tugas sudah beres!</h3>
                <p style="color: #6B7280;">Kamu bisa istirahat sekarang.</p>
            </div>
        @endforelse
    </div>

    @if($tugas->hasPages())
        <div style="margin-top: 30px;">{{ $tugas->links() }}</div>
    @endif
</div>
@endsection
