@extends('layouts.public')

@section('title', 'Agenda Kegiatan — SDN Sukorame 1')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }

    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 45%, #2563eb 85%, #60a5fa 100%);
        position: relative; overflow: hidden;
    }
    .hero-pattern {
        position: absolute; inset: 0; opacity: .05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 28px 28px;
    }

    /* ── TIMELINE ── */
    .timeline { position: relative; }
    .timeline::before {
        content: ''; position: absolute; left: 100px; top: 0; bottom: 0; width: 2px;
        background: linear-gradient(to bottom, #dbeafe, #bfdbfe, #93c5fd, #60a5fa);
    }
    @media (max-width: 640px) {
        .timeline::before { left: 24px; }
    }

    .timeline-item { display: flex; gap: 0; margin-bottom: 28px; position: relative; }

    .timeline-date {
        width: 100px; flex-shrink: 0; padding-right: 28px; padding-top: 20px;
        text-align: right;
    }
    .timeline-date .day   { font-size: 2rem; font-weight: 900; color: #1d4ed8; line-height: 1; }
    .timeline-date .month { font-size: 11px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: .06em; }
    .timeline-date .year  { font-size: 10px; color: #93c5fd; font-weight: 600; }

    .timeline-dot {
        width: 14px; height: 14px; border-radius: 50%; flex-shrink: 0;
        border: 3px solid white; position: relative; top: 24px; z-index: 2; margin: 0 -6px;
    }

    .timeline-card {
        flex: 1; background: white; border-radius: 20px;
        border: 1.5px solid #e0e7ff; padding: 20px 24px; margin-left: 24px;
        transition: all .25s; position: relative;
    }
    .timeline-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 8px 28px rgba(37,99,235,.1);
        transform: translateX(4px);
    }
    .timeline-card::before {
        content: ''; position: absolute; left: -8px; top: 22px;
        width: 0; height: 0;
        border-top: 7px solid transparent;
        border-bottom: 7px solid transparent;
        border-right: 8px solid #e0e7ff;
    }

    @media (max-width: 640px) {
        .timeline-date { width: 48px; padding-right: 12px; }
        .timeline-date .day { font-size: 1.3rem; }
        .timeline-date .month, .timeline-date .year { display: none; }
    }

    /* ── JENIS BADGE ── */
    .jenis-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 10px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
        padding: 3px 10px; border-radius: 999px;
    }
    .jenis-akademik   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .jenis-olahraga   { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .jenis-seni       { background: #fdf4ff; color: #9333ea; border: 1px solid #e9d5ff; }
    .jenis-nasional   { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }
    .jenis-ekstra     { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .jenis-rapat      { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

    /* Nav info bar */
    .info-nav-bar {
        background: white; border-bottom: 1px solid #e0e7ff;
        position: sticky; top: 0; z-index: 30;
    }
    .info-nav-link {
        display: inline-flex; align-items: center; gap: 6px; padding: 14px 20px;
        font-size: 13px; font-weight: 600; color: #6b7280; border-bottom: 2px solid transparent;
        text-decoration: none; transition: all .2s;
    }
    .info-nav-link:hover { color: #2563eb; }
    .info-nav-link.active { color: #2563eb; border-bottom-color: #2563eb; }

    /* Month header */
    .month-header {
        display: flex; align-items: center; gap-12px; margin-bottom: 20px; margin-top: 40px;
    }
    .month-header:first-child { margin-top: 0; }
    .month-pill {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: white; font-size: 12px; font-weight: 800;
        padding: 6px 18px; border-radius: 999px; letter-spacing: .04em;
        text-transform: uppercase;
    }
    .month-line { flex: 1; height: 1px; background: linear-gradient(90deg, #bfdbfe, transparent); }

    /* Summary stats */
    .stat-card {
        background: white; border-radius: 20px; border: 1.5px solid #e0e7ff;
        padding: 20px; text-align: center; transition: all .22s;
    }
    .stat-card:hover { border-color: #93c5fd; box-shadow: 0 6px 20px rgba(37,99,235,.08); }

    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

@php
$agendaItems = collect($agendaItems ?? []);
$agendaData = $agendaItems->map(function ($item) {
    $tanggalMulai = $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai) : null;
    $tanggalSelesai = $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai) : null;

    return [
        'judul'     => $item->judul,
        'tanggal'   => $tanggalMulai,
        'selesai'   => $tanggalSelesai,
        'jenis'     => $item->kategori,
        'deskripsi' => $item->deskripsi ?: 'Tidak ada deskripsi tambahan.',
        'lokasi'    => $item->tempat ?: 'SDN Sukorame 1',
        'warna'     => match($item->kategori) {
            'upacara'   => '#1d4ed8',
            'penilaian' => '#2563eb',
            'perpisahan'=> '#e11d48',
            'libur'     => '#f59e0b',
            'ppdb'      => '#10b981',
            'ekskul'    => '#9333ea',
            'rapat'     => '#475569',
            default     => '#64748b',
        },
    ];
})->filter(fn($item) => $item['tanggal'])->values();

$grouped  = $agendaData->groupBy(fn($a) => $a['tanggal']->format('Y-m'));
$jenisMap = [
    'upacara'    => ['label'=>'Upacara',          'cls'=>'jenis-akademik',  'ico'=>'fa-flag'],
    'penilaian'  => ['label'=>'Penilaian',        'cls'=>'jenis-akademik',  'ico'=>'fa-book'],
    'perpisahan' => ['label'=>'Perpisahan',       'cls'=>'jenis-nasional',  'ico'=>'fa-users'],
    'libur'      => ['label'=>'Libur',            'cls'=>'jenis-olahraga',  'ico'=>'fa-sun'],
    'ppdb'       => ['label'=>'PPDB',             'cls'=>'jenis-ekstra',    'ico'=>'fa-user-plus'],
    'ekskul'     => ['label'=>'Ekstrakurikuler',  'cls'=>'jenis-ekstra',    'ico'=>'fa-star'],
    'rapat'      => ['label'=>'Rapat',            'cls'=>'jenis-rapat',     'ico'=>'fa-users'],
    'lainnya'    => ['label'=>'Lainnya',          'cls'=>'jenis-ekstra',    'ico'=>'fa-info-circle'],
];
@endphp

{{-- HERO --}}
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div style="position:absolute;width:400px;height:400px;right:-100px;top:-100px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 70%);pointer-events:none;"></div>

    <div class="max-w-5xl mx-auto px-6 relative z-10">
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Agenda Kegiatan</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-blue-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-blue-300 rounded-full"></span> Kalender Sekolah
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Agenda<br>
                    <span style="color:#DBEAFE;">Kegiatan Sekolah</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-lg" style="font-size:1rem;">
                    Jadwal kegiatan, acara resmi, dan agenda penting
                    SD Negeri Sukorame 1 Kota Kediri sepanjang tahun pelajaran.
                </p>
            </div>
            <div class="flex gap-3 flex-shrink-0">
                @php $stats = [
                    ['val'=> $agendaData->count(), 'lbl'=>'Total Agenda', 'ico'=>'fa-calendar-alt', 'color'=>'text-blue-300'],
                    ['val'=> $agendaData->where('tanggal', '>=', now())->count(), 'lbl'=>'Akan Datang', 'ico'=>'fa-clock', 'color'=>'text-blue-300'],
                ]; @endphp
                @foreach ($stats as $s)
                <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center w-28 backdrop-blur-sm">
                    <i class="fa {{ $s['ico'] }} {{ $s['color'] }} text-base mb-2 block"></i>
                    <p class="text-white font-black text-xl leading-none">{{ $s['val'] }}</p>
                    <p class="text-white/55 text-[10px] mt-1">{{ $s['lbl'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<main class="bg-gray-50">
<section class="py-20">
    <div class="max-w-5xl mx-auto px-6">

        {{-- Summary stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-14 fade-up">
            @foreach([
                ['Upacara',           $agendaData->where('jenis','upacara')->count(),    'jenis-akademik', 'fa-flag'],
                ['Penilaian',         $agendaData->where('jenis','penilaian')->count(),  'jenis-akademik', 'fa-book'],
                ['Ekstrakurikuler',   $agendaData->where('jenis','ekskul')->count(),      'jenis-ekstra',   'fa-star'],
                ['PPDB',              $agendaData->where('jenis','ppdb')->count(),        'jenis-ekstra',   'fa-user-plus'],
            ] as [$lbl, $count, $cls, $ico])
            <div class="stat-card fade-up">
                <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2 {{ $cls }}" style="width:44px;height:44px;">
                    <i class="fa {{ $ico }}"></i>
                </div>
                <p class="text-2xl font-black text-gray-800">{{ $count }}</p>
                <p class="text-xs text-gray-400 font-semibold">{{ $lbl }}</p>
            </div>
            @endforeach
        </div>

        {{-- Timeline --}}
        <div class="timeline">
            @foreach($grouped->sortKeys() as $yearMonth => $items)
            @php $dt = \Carbon\Carbon::createFromFormat('Y-m', $yearMonth); @endphp
            <div class="month-header fade-up" style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                <span class="month-pill">{{ $dt->locale('id')->isoFormat('MMMM Y') }}</span>
                <div class="month-line"></div>
            </div>

            @foreach($items->sortBy(fn($a)=>$a['tanggal']) as $agenda)
            @php
                $j   = $jenisMap[$agenda['jenis']] ?? $jenisMap['akademik'];
                $past = $agenda['tanggal']->isPast() && (!$agenda['selesai'] || $agenda['selesai']->isPast());
            @endphp
            <div class="timeline-item fade-up">
                <div class="timeline-date" style="opacity: {{ $past ? '.45' : '1' }}">
                    <div class="day">{{ $agenda['tanggal']->format('d') }}</div>
                    <div class="month">{{ $agenda['tanggal']->locale('id')->isoFormat('MMM') }}</div>
                    <div class="year">{{ $agenda['tanggal']->format('Y') }}</div>
                </div>
                <div class="timeline-dot" style="background: {{ $past ? '#d1d5db' : $agenda['warna'] }};"></div>
                <div class="timeline-card" style="opacity: {{ $past ? '.65' : '1' }}">
                    <div class="flex flex-wrap items-start justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="jenis-badge {{ $j['cls'] }}">
                                <i class="fa {{ $j['ico'] }}"></i> {{ $j['label'] }}
                            </span>
                            @if(!$past)
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> Akan Datang
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-400 border border-gray-100 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                Selesai
                            </span>
                            @endif
                        </div>
                        @if($agenda['selesai'])
                        <span class="text-[10px] text-gray-400 font-semibold bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                            s.d. {{ $agenda['selesai']->locale('id')->isoFormat('D MMM Y') }}
                        </span>
                        @endif
                    </div>
                    <h3 class="font-bold text-gray-900 text-[15px] mb-2 leading-snug">{{ $agenda['judul'] }}</h3>
                    <p class="text-gray-500 text-xs leading-relaxed mb-3">{{ $agenda['deskripsi'] }}</p>
                    <div class="flex items-center gap-1.5 text-gray-400 text-[11px]">
                        <i class="fa fa-map-marker-alt text-[10px]"></i>
                        <span>{{ $agenda['lokasi'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach

            @endforeach
        </div>

        {{-- CTA --}}
        <div class="mt-16 text-center py-12 bg-white rounded-3xl border-2 border-dashed border-blue-100 fade-up">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa fa-calendar-plus text-blue-400 text-2xl"></i>
            </div>
            <p class="text-gray-600 font-semibold text-sm mb-1">Ada agenda yang ingin ditambahkan?</p>
            <p class="text-gray-400 text-xs mb-4">Hubungi administrasi sekolah atau kirim melalui formulir online.</p>
            <a href="{{ route('home') }}#kontak" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-6 py-3 rounded-xl transition-colors">
                <i class="fa fa-envelope"></i> Hubungi Sekolah
            </a>
        </div>

    </div>
</section>
</main>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const obs = new IntersectionObserver(entries => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('visible'), i * 60);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
});
</script>
@endpush