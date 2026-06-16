@extends('layouts.public')

@section('title', 'Info Dinas Pendidikan — SDN Sukorame 1')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }

    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 45%, #1d4ed8 80%, #3b82f6 100%);
        position: relative; overflow: hidden;
    }
    .hero-pattern {
        position: absolute; inset: 0; opacity: .05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 28px 28px;
    }

    /* ── DINAS CARD ── */
    .dinas-card {
        background: white; border-radius: 20px;
        border: 1.5px solid #e0e7ff; overflow: hidden;
        transition: all .25s; display: flex; flex-direction: column;
    }
    .dinas-card:hover {
        border-color: #bfdbfe;
        box-shadow: 0 12px 32px rgba(37,99,235,.1);
        transform: translateY(-3px);
    }
    .dinas-card-header {
        padding: 20px 24px 16px;
        border-bottom: 1px solid #f8fafc;
        display: flex; align-items: flex-start; gap: 14px;
    }
    .dinas-icon {
        width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .dinas-card-body { padding: 18px 24px; flex: 1; }
    .dinas-card-footer {
        padding: 12px 24px;
        background: #fafafa; border-top: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: between;
        gap: 8px;
    }

    /* ── TIPE BADGE ── */
    .tipe-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 10px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
        padding: 3px 10px; border-radius: 999px;
    }
    .tipe-kebijakan    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .tipe-program      { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .tipe-beasiswa     { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .tipe-pelatihan    { background: #fdf4ff; color: #9333ea; border: 1px solid #e9d5ff; }
    .tipe-administrasi { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }

    /* ── LINK CARD ── */
    .link-card {
        display: flex; align-items: center; gap-12px; padding: 14px 18px;
        background: white; border-radius: 14px; border: 1.5px solid #e0e7ff;
        text-decoration: none; transition: all .2s; gap: 12px;
    }
    .link-card:hover { border-color: #bfdbfe; box-shadow: 0 4px 12px rgba(37,99,235,.08); }
    .link-icon {
        width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
    }

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

    /* Sidebar sticky widget */
    .sidebar-sticky { position: sticky; top: 72px; }

    /* Section label */
    .section-label {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        color: #2563eb; margin-bottom: 16px;
    }
    .section-label::before { content: ''; width: 6px; height: 6px; background: #2563eb; border-radius: 50%; }

    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

@php
$infoItems = collect($infoItems ?? [])->map(function ($item) {
    return is_object($item) ? (array) $item : $item;
});

$infoLinks = collect($infoLinks ?? [])->map(function ($item) {
    return is_object($item) ? (array) $item : $item;
});

$tipeMap = [
    'kebijakan'    => ['label'=>'Kebijakan',    'cls'=>'tipe-kebijakan'],
    'program'      => ['label'=>'Program',      'cls'=>'tipe-program'],
    'beasiswa'     => ['label'=>'Beasiswa',     'cls'=>'tipe-beasiswa'],
    'pelatihan'    => ['label'=>'Pelatihan',    'cls'=>'tipe-pelatihan'],
    'administrasi' => ['label'=>'Administrasi', 'cls'=>'tipe-administrasi'],
];

if ($infoLinks->isEmpty()) {
    $infoLinks = collect([
        ['nama'=>'Kemendikbudristek',        'url'=>'https://kemdikbud.go.id',            'ico'=>'fa-landmark'],
        ['nama'=>'DAPODIK',                  'url'=>'https://dapo.kemdikbud.go.id',       'ico'=>'fa-database'],
        ['nama'=>'Program Indonesia Pintar', 'url'=>'https://pip.kemdikbud.go.id',        'ico'=>'fa-graduation-cap'],
        ['nama'=>'SIMPKB (GTK)',             'url'=>'https://simpkb.id',                  'ico'=>'fa-chalkboard-teacher'],
        ['nama'=>'BOS Online',               'url'=>'https://bos.kemdikbud.go.id',        'ico'=>'fa-file-invoice-dollar'],
        ['nama'=>'Kurikulum Merdeka',        'url'=>'https://kurikulum.kemdikbud.go.id',  'ico'=>'fa-book-open'],
    ]);
}
@endphp

{{-- HERO --}}
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div style="position:absolute;width:400px;height:400px;right:-100px;top:-100px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 70%);pointer-events:none;"></div>

    <div class="max-w-5xl mx-auto px-6 relative z-10">
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Info Dinas Pendidikan</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-blue-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-blue-300 rounded-full"></span> Informasi Resmi
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Info<br>
                    <span style="color:#BFDBFE;">Dinas Pendidikan</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-lg" style="font-size:1rem;">
                    Informasi terkini seputar kebijakan, program, beasiswa, dan regulasi
                    dari Dinas Pendidikan Kota Kediri &amp; Kemendikbudristek.
                </p>
            </div>
            <div class="flex gap-3 flex-shrink-0">
                @php $stats = [
                    ['val'=> $infoItems->count(),                            'lbl'=>'Total Info', 'ico'=>'fa-university'],
                    ['val'=> $infoItems->where('tipe','beasiswa')->count(), 'lbl'=>'Beasiswa',  'ico'=>'fa-graduation-cap'],
                ]; @endphp
                @foreach ($stats as $s)
                <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center w-28 backdrop-blur-sm">
                    <i class="fa {{ $s['ico'] }} text-blue-300 text-base mb-2 block"></i>
                    <p class="text-white font-black text-xl leading-none">{{ $s['val'] }}</p>
                    <p class="text-white/55 text-[10px] mt-1">{{ $s['lbl'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<main class="bg-gray-50">
<section class="py-16">
    <div class="max-w-5xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Konten Utama --}}
            <div class="lg:col-span-2">

                {{-- Filter Tipe --}}
                <div class="flex flex-wrap gap-2 mb-8 fade-up">
                    @php $allTipes = ['','kebijakan','program','beasiswa','pelatihan','administrasi']; @endphp
                    @foreach($allTipes as $t)
                    @php $info = $tipeMap[$t] ?? ['label'=>'Semua','cls'=>'tipe-kebijakan']; @endphp
                    <button onclick="filterTipe('{{ $t }}')"
                        data-tipe="{{ $t }}"
                        class="tipe-badge {{ $info['cls'] }} cursor-pointer transition-all filter-btn {{ $t === '' ? 'ring-2 ring-offset-1 ring-current' : '' }}">
                        {{ $t === '' ? 'Semua' : $info['label'] }}
                    </button>
                    @endforeach
                </div>

                {{-- Cards --}}
                <div class="space-y-5" id="dinas-list">
                    @foreach($infoItems as $info)
                    @php
                        $tp = $tipeMap[$info['tipe'] ?? 'kebijakan'] ?? $tipeMap['kebijakan'];
                        $tanggal = isset($info['tanggal']) ? \Carbon\Carbon::parse($info['tanggal'])->locale('id')->isoFormat('D MMMM Y') : '-';
                        $info = array_merge([
                            'judul' => '-',
                            'ringkasan' => '-',
                            'detail' => '-',
                            'sumber' => 'Sumber tidak tersedia',
                            'url' => '#',
                            'warna' => '#2563eb',
                            'ico' => 'fa-info-circle',
                        ], $info);
                    @endphp
                    <div class="dinas-card fade-up" data-tipe="{{ $info['tipe'] ?? '' }}">
                        <div class="dinas-card-header">
                            <div class="dinas-icon" style="background: linear-gradient(135deg, {{ $info['warna'] }}22, {{ $info['warna'] }}44);">
                                <i class="fa {{ $info['ico'] }}" style="color: {{ $info['warna'] }}; font-size: 1.2rem;"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                    <span class="tipe-badge {{ $tp['cls'] }}">{{ $tp['label'] }}</span>
                                    <span class="text-[10px] text-gray-400">
                                        <i class="fa fa-calendar text-[9px] mr-1"></i>{{ $tanggal }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-gray-900 text-[15px] leading-snug">{{ $info['judul'] }}</h3>
                            </div>
                        </div>
                        <div class="dinas-card-body">
                            <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ $info['ringkasan'] }}</p>

                            {{-- Accordion detail --}}
                            <div class="selengkapnya" style="display:none;">
                                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-4">
                                    <p class="text-blue-900 text-xs leading-relaxed">{{ $info['detail'] }}</p>
                                </div>
                            </div>

                            <button onclick="toggleDetail(this)" class="text-blue-600 text-xs font-bold hover:text-blue-700 flex items-center gap-1.5 transition-all">
                                <i class="fa fa-chevron-down text-[9px] toggle-ico"></i>
                                <span class="toggle-lbl">Baca Selengkapnya</span>
                            </button>
                        </div>
                        <div class="dinas-card-footer">
                            <div class="flex items-center gap-2 text-[11px] text-gray-400 flex-1">
                                <i class="fa fa-university text-[10px]"></i>
                                <span>{{ $info['sumber'] }}</span>
                            </div>
                            @if(!empty($info['url']) && $info['url'] !== '#')
                            <a href="{{ $info['url'] }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-100 text-[11px] font-bold px-3 py-1.5 rounded-lg transition-colors ml-auto">
                                Kunjungi <i class="fa fa-external-link-alt text-[9px]"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Empty state --}}
                <div id="empty-state" class="text-center py-16 hidden">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fa fa-search text-blue-300 text-xl"></i>
                    </div>
                    <p class="text-gray-500 font-semibold text-sm">Tidak ada info untuk kategori ini.</p>
                </div>

            </div>

            {{-- Sidebar --}}
            <aside>
                <div class="sidebar-sticky space-y-5">

                    {{-- Kontak Dinas --}}
                    <div class="bg-white rounded-2xl border border-e0e7ff p-5 fade-up" style="border-color:#e0e7ff;">
                        <p class="section-label">Kontak Resmi</p>
                        <div class="space-y-3 mt-3">
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa fa-university text-blue-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-xs">Dinas Pendidikan Kota Kediri</p>
                                    <p class="text-[11px] text-gray-400">Jl. Basuki Rahmat No. 1, Kediri</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa fa-phone text-blue-500 text-xs"></i>
                                </div>
                                <span>(0354) 771555</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa fa-envelope text-blue-500 text-xs"></i>
                                </div>
                                <span>disdik@kedirikota.go.id</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fa fa-clock text-blue-500 text-xs"></i>
                                </div>
                                <span>Senin–Jumat, 07.30–16.00 WIB</span>
                            </div>
                        </div>
                    </div>

                    {{-- Link Resmi --}}
                    <div class="bg-white rounded-2xl border p-5 fade-up" style="border-color:#e0e7ff;">
                        <p class="section-label">Tautan Resmi</p>
                        <div class="space-y-2 mt-3">
                            @foreach($infoLinks as $link)
                            <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="link-card">
                                <div class="link-icon">
                                    <i class="fa {{ $link['ico'] }} text-white/80 text-xs"></i>
                                </div>
                                <span class="text-xs font-semibold text-gray-700 flex-1">{{ $link['nama'] }}</span>
                                <i class="fa fa-external-link-alt text-gray-300 text-[10px]"></i>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Disclaimer --}}
                    <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5 fade-up">
                        <div class="flex items-start gap-3">
                            <i class="fa fa-info-circle text-blue-400 mt-0.5"></i>
                            <div>
                                <p class="text-xs font-bold text-blue-800 mb-1">Catatan</p>
                                <p class="text-[11px] text-blue-700 leading-relaxed">
                                    Informasi di halaman ini bersumber dari Dinas Pendidikan Kota Kediri dan Kemendikbudristek. Untuk informasi lebih lengkap, kunjungi tautan resmi atau hubungi langsung pihak sekolah.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>
        </div>
    </div>
</section>
</main>

@endsection

@push('scripts')
<script>
function filterTipe(tipe) {
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('ring-2','ring-offset-1','ring-current'));
    document.querySelector(`.filter-btn[data-tipe="${tipe}"]`).classList.add('ring-2','ring-offset-1','ring-current');

    let visible = 0;
    document.querySelectorAll('#dinas-list .dinas-card').forEach(card => {
        const show = !tipe || card.dataset.tipe === tipe;
        card.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    document.getElementById('empty-state').classList.toggle('hidden', visible > 0);
}

function toggleDetail(btn) {
    const card   = btn.closest('.dinas-card');
    const detail = card.querySelector('.selengkapnya');
    const lbl    = btn.querySelector('.toggle-lbl');
    const ico    = btn.querySelector('.toggle-ico');
    const open   = detail.style.display === 'none' || detail.style.display === '';
    detail.style.display = open ? 'block' : 'none';
    lbl.textContent = open ? 'Sembunyikan' : 'Baca Selengkapnya';
    ico.style.transform = open ? 'rotate(180deg)' : '';
}

document.addEventListener('DOMContentLoaded', function () {
    const obs = new IntersectionObserver(entries => {
        entries.forEach((e, i) => {
            if (e.isIntersecting) {
                setTimeout(() => e.target.classList.add('visible'), i * 80);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.08 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
});
</script>
@endpush