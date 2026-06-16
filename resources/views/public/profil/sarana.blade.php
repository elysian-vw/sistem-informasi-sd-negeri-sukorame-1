@extends('layouts.public')

@section('title', $pageTitle ?? 'Sarana & Prasarana — ' . ($sekolah->nama_sekolah ?? 'SDN Sukorame 1'))

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }

    /* ── PAGE HERO ── */
    .page-hero {
        background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 45%, #1d4ed8 75%, #3b82f6 100%);
        position: relative; overflow: hidden;
    }
    .hero-pattern {
        position: absolute; inset: 0; opacity: .05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 28px 28px;
    }

    /* ── SECTION LABEL ── */
    .sec-label {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe;
        padding: 4px 14px; border-radius: 999px; margin-bottom: 12px;
    }
    .sec-label::before { content: ''; width: 6px; height: 6px; background: #1d4ed8; border-radius: 50%; }

    /* ── DIVIDER ── */
    .section-divider { height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent); }

    /* ── SARANA CARD ── */
    .sarana-card {
        background: white; border-radius: 20px; overflow: hidden;
        border: 1px solid #f1f5f9;
        transition: all .3s; position: relative;
    }
    .sarana-card:hover { transform: translateY(-5px); box-shadow: 0 20px 48px rgba(29,78,216,.11); border-color: #bfdbfe; }
    .sarana-card::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
        background: #1d4ed8; opacity: 0; transition: opacity .3s;
    }
    .sarana-card:hover::after { opacity: 1; }

    .sarana-icon-wrap {
        height: 110px; display: flex; align-items: center; justify-content: center;
        position: relative;
    }

    /* ── STATUS BADGE ── */
    .status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 9px; font-weight: 800; letter-spacing: .06em;
        padding: 3px 10px; border-radius: 999px; text-transform: uppercase;
    }
    .status-baik     { background: #dcfce7; color: #166534; }
    .status-cukup    { background: #fef9c3; color: #854d0e; }
    .status-rusak    { background: #fee2e2; color: #991b1b; }

    /* ── RUANGAN ITEM ── */
    .ruangan-item {
        display: flex; align-items: center; gap-14px;
        padding: 14px 18px; border-radius: 14px;
        border: 1px solid #f1f5f9; background: white;
        transition: all .22s; gap: 14px;
    }
    .ruangan-item:hover { border-color: #bfdbfe; background: #f8fbff; transform: translateX(4px); }

    /* ── STAT BOX ── */
    .stat-box {
        background: white; border-radius: 18px; border: 1px solid #f1f5f9;
        padding: 20px; text-align: center; transition: all .25s;
    }
    .stat-box:hover { border-color: #bfdbfe; box-shadow: 0 8px 24px rgba(29,78,216,.08); transform: translateY(-3px); }

    /* Animasi */
    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════
     PAGE HERO
══════════════════════════════════════ --}}
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div style="position:absolute;width:400px;height:400px;right:-100px;top:-100px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;width:240px;height:240px;left:6%;bottom:-70px;border-radius:50%;background:radial-gradient(circle,rgba(217,119,6,.12) 0%,transparent 70%);pointer-events:none;"></div>

    <div class="max-w-5xl mx-auto px-6 relative z-10">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <a href="#" class="hover:text-white transition-colors">Profil</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Sarana & Prasarana</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-amber-300 rounded-full"></span> Profil Sekolah
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Sarana &amp;<br>
                    <span style="color:#FDE68A;">Prasarana</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-xl" style="font-size:1rem;">
                    Fasilitas pendukung kegiatan belajar mengajar yang terus dikembangkan
                    demi kenyamanan dan kemajuan peserta didik {{ $sekolah->nama_sekolah ?? 'SDN Sukorame 1 Kota Kediri' }}.
                </p>

                {{-- Quick nav --}}
                <div class="flex flex-wrap gap-3 mt-7">
                    @foreach([
                        ['#ruangan',  'fa-door-open',     'Ruangan'],
                        ['#fasilitas','fa-cubes',         'Fasilitas'],
                        ['#sanitasi', 'fa-faucet',        'Sanitasi'],
                    ] as $n)
                    <a href="{{ $n[0] }}" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold px-4 py-2 rounded-full transition-all">
                        <i class="fa {{ $n[1] }} text-amber-300 text-[10px]"></i> {{ $n[2] }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Hero stat --}}
            @php
                $totalRuang = isset($sarana) ? $sarana->count() : 15;
                $hs = [
                    ['val' => $totalRuang, 'lbl' => 'Total Ruang', 'ico' => 'fa-door-open'],
                    ['val' => '6',         'lbl' => 'Kelas',       'ico' => 'fa-chalkboard'],
                    ['val' => 'Baik',      'lbl' => 'Kondisi',     'ico' => 'fa-check-circle'],
                ];
            @endphp
            <div class="flex gap-3 flex-shrink-0">
                @foreach($hs as $h)
                <div class="bg-white/10 border border-white/20 rounded-2xl p-4 text-center w-24 backdrop-blur-sm">
                    <i class="fa {{ $h['ico'] }} text-amber-300 text-base mb-2 block"></i>
                    <p class="text-white font-black text-xl leading-none">{{ $h['val'] }}</p>
                    <p class="text-white/55 text-[10px] mt-1">{{ $h['lbl'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<main class="bg-gray-50">

    {{-- ══════════════════════════════════════
         RINGKASAN KONDISI
    ══════════════════════════════════════ --}}
    <section class="py-14 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 fade-up">
                @php
                    $stats = [
                        ['val'=>'6',  'lbl'=>'Ruang Kelas',      'ico'=>'fa-chalkboard',        'bg'=>'bg-blue-50',   'ico_c'=>'text-blue-600'],
                        ['val'=>'1',  'lbl'=>'Perpustakaan',      'ico'=>'fa-book',               'bg'=>'bg-indigo-50', 'ico_c'=>'text-indigo-600'],
                        ['val'=>'1',  'lbl'=>'Lab Komputer',      'ico'=>'fa-desktop',            'bg'=>'bg-violet-50', 'ico_c'=>'text-violet-600'],
                        ['val'=>'1',  'lbl'=>'Ruang UKS',         'ico'=>'fa-kit-medical',        'bg'=>'bg-green-50',  'ico_c'=>'text-green-600'],
                    ];
                @endphp
                @foreach($stats as $s)
                <div class="stat-box">
                    <div class="w-11 h-11 {{ $s['bg'] }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa {{ $s['ico'] }} {{ $s['ico_c'] }} text-lg"></i>
                    </div>
                    <p class="font-black text-gray-900 text-2xl">{{ $s['val'] }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $s['lbl'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         DAFTAR RUANGAN
    ══════════════════════════════════════ --}}
    <section id="ruangan" class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Ruangan</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Daftar Ruangan</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Inventaris ruangan SDN Sukorame 1 beserta kondisi dan kapasitasnya.
                </p>
            </div>

            @php
                $ruanganDefault = [
                    ['nama'=>'Ruang Kelas I',        'luas'=>'56 m²', 'kapasitas'=>'32 Siswa',  'kondisi'=>'Baik',   'ico'=>'fa-chalkboard',     'bg'=>'bg-blue-50',   'ico_c'=>'text-blue-600'],
                    ['nama'=>'Ruang Kelas II',       'luas'=>'56 m²', 'kapasitas'=>'30 Siswa',  'kondisi'=>'Baik',   'ico'=>'fa-chalkboard',     'bg'=>'bg-blue-50',   'ico_c'=>'text-blue-600'],
                    ['nama'=>'Ruang Kelas III',      'luas'=>'56 m²', 'kapasitas'=>'34 Siswa',  'kondisi'=>'Baik',   'ico'=>'fa-chalkboard',     'bg'=>'bg-blue-50',   'ico_c'=>'text-blue-600'],
                    ['nama'=>'Ruang Kelas IV',       'luas'=>'56 m²', 'kapasitas'=>'33 Siswa',  'kondisi'=>'Baik',   'ico'=>'fa-chalkboard',     'bg'=>'bg-blue-50',   'ico_c'=>'text-blue-600'],
                    ['nama'=>'Ruang Kelas V',        'luas'=>'56 m²', 'kapasitas'=>'32 Siswa',  'kondisi'=>'Baik',   'ico'=>'fa-chalkboard',     'bg'=>'bg-blue-50',   'ico_c'=>'text-blue-600'],
                    ['nama'=>'Ruang Kelas VI',       'luas'=>'56 m²', 'kapasitas'=>'31 Siswa',  'kondisi'=>'Baik',   'ico'=>'fa-chalkboard',     'bg'=>'bg-blue-50',   'ico_c'=>'text-blue-600'],
                    ['nama'=>'Ruang Kepala Sekolah', 'luas'=>'21 m²', 'kapasitas'=>'—',         'kondisi'=>'Baik',   'ico'=>'fa-user-tie',       'bg'=>'bg-amber-50',  'ico_c'=>'text-amber-600'],
                    ['nama'=>'Ruang Guru',           'luas'=>'48 m²', 'kapasitas'=>'15 Guru',   'kondisi'=>'Baik',   'ico'=>'fa-users',          'bg'=>'bg-indigo-50', 'ico_c'=>'text-indigo-600'],
                    ['nama'=>'Perpustakaan',         'luas'=>'63 m²', 'kapasitas'=>'40 Siswa',  'kondisi'=>'Baik',   'ico'=>'fa-book-open',      'bg'=>'bg-green-50',  'ico_c'=>'text-green-600'],
                    ['nama'=>'Lab Komputer',         'luas'=>'56 m²', 'kapasitas'=>'20 Unit',   'kondisi'=>'Cukup',  'ico'=>'fa-desktop',        'bg'=>'bg-violet-50', 'ico_c'=>'text-violet-600'],
                    ['nama'=>'Ruang UKS',            'luas'=>'12 m²', 'kapasitas'=>'4 Tempat',  'kondisi'=>'Baik',   'ico'=>'fa-kit-medical',    'bg'=>'bg-rose-50',   'ico_c'=>'text-rose-600'],
                    ['nama'=>'Mushola',              'luas'=>'36 m²', 'kapasitas'=>'40 Jamaah', 'kondisi'=>'Baik',   'ico'=>'fa-mosque',         'bg'=>'bg-teal-50',   'ico_c'=>'text-teal-600'],
                    ['nama'=>'Gudang',               'luas'=>'18 m²', 'kapasitas'=>'—',         'kondisi'=>'Cukup',  'ico'=>'fa-warehouse',      'bg'=>'bg-slate-50',  'ico_c'=>'text-slate-500'],
                    ['nama'=>'Kantin',               'luas'=>'24 m²', 'kapasitas'=>'30 Orang',  'kondisi'=>'Baik',   'ico'=>'fa-utensils',       'bg'=>'bg-orange-50', 'ico_c'=>'text-orange-600'],
                    ['nama'=>'Toilet Siswa',         'luas'=>'—',     'kapasitas'=>'6 Bilik',   'kondisi'=>'Baik',   'ico'=>'fa-restroom',       'bg'=>'bg-sky-50',    'ico_c'=>'text-sky-600'],
                ];

                $isUsingDb = isset($sarana) && $sarana->count() > 0;
                $ruanganList = $isUsingDb ? $sarana : collect($ruanganDefault);
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($ruanganList as $idx => $r)
                @php
                    $nama      = is_array($r) ? $r['nama']      : ($r->nama      ?? '—');
                    $luas      = is_array($r) ? ($r['luas'] ?? '—')      : '—';
                    $kapasitas = is_array($r) ? ($r['kapasitas'] ?? '—') : ($r->jumlah . ' Unit' ?? '—');
                    $kondisi   = is_array($r) ? $r['kondisi']   : ($r->kondisi   ?? 'Baik');
                    $foto      = is_array($r) ? null             : $r->foto;
                    $ico       = is_array($r) ? $r['ico']       : 'fa-door-open';
                    $bg        = is_array($r) ? $r['bg']        : 'bg-blue-50';
                    $ico_c     = is_array($r) ? $r['ico_c']     : 'text-blue-600';
                    $statusCls = match(strtolower($kondisi)) {
                        'cukup'       => 'status-cukup',
                        'rusak ringan','rusak berat','rusak' => 'status-rusak',
                        default       => 'status-baik',
                    };
                @endphp
                <div class="ruangan-item fade-up" style="transition-delay: {{ ($idx % 6) * 55 }}ms">
                    <div class="w-11 h-11 {{ $bg }} rounded-2xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if(!is_array($r) && $r->foto)
                            <img src="{{ asset('storage/' . $r->foto) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa {{ $ico }} {{ $ico_c }} text-base"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 text-sm">{{ $nama }}</p>
                        <div class="flex items-center gap-3 mt-0.5">
                            @if($luas !== '—')
                            <span class="text-xs text-gray-400"><i class="fa fa-ruler-combined mr-1 text-[9px]"></i>{{ $luas }}</span>
                            @endif
                            @if($kapasitas !== '—')
                            <span class="text-xs text-gray-400"><i class="fa fa-cubes mr-1 text-[9px]"></i>{{ $kapasitas }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="status-badge {{ $statusCls }} flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusCls === 'status-baik' ? 'bg-green-500' : ($statusCls === 'status-cukup' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                        {{ $kondisi }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         FASILITAS PENUNJANG
    ══════════════════════════════════════ --}}
    <section id="fasilitas" class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Fasilitas</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Fasilitas Penunjang</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Kelengkapan sarana penunjang kegiatan belajar mengajar di SDN Sukorame 1.
                </p>
            </div>

            @php
                $fasilitas = [
                    [
                        'icon'=>'fa-wifi', 'color'=>'blue',
                        'title'=>'Jaringan Internet',
                        'desc'=>'Akses WiFi tersedia untuk mendukung pembelajaran berbasis digital di seluruh area sekolah.',
                        'detail'=>'Coverage seluruh area',
                    ],
                    [
                        'icon'=>'fa-tv', 'color'=>'indigo',
                        'title'=>'LCD Proyektor',
                        'desc'=>'Tersedia di tiap ruang kelas untuk mendukung pembelajaran visual dan presentasi interaktif.',
                        'detail'=>'6 unit terpasang',
                    ],
                    [
                        'icon'=>'fa-futbol', 'color'=>'green',
                        'title'=>'Lapangan Olahraga',
                        'desc'=>'Lapangan serbaguna untuk kegiatan pendidikan jasmani, upacara, dan kegiatan ekstrakurikuler.',
                        'detail'=>'Serbaguna & terawat',
                    ],
                    [
                        'icon'=>'fa-solar-panel', 'color'=>'amber',
                        'title'=>'Panel Surya',
                        'desc'=>'Energi ramah lingkungan sebagai sumber listrik pendukung operasional sekolah.',
                        'detail'=>'Energi terbarukan',
                    ],
                    [
                        'icon'=>'fa-shield-halved', 'color'=>'rose',
                        'title'=>'CCTV & Keamanan',
                        'desc'=>'Sistem pengawasan CCTV untuk memastikan keamanan dan keselamatan seluruh warga sekolah.',
                        'detail'=>'24 jam monitoring',
                    ],
                    [
                        'icon'=>'fa-tree', 'color'=>'teal',
                        'title'=>'Taman & Kebun Sekolah',
                        'desc'=>'Area hijau dan taman yang asri untuk mendukung lingkungan belajar yang nyaman dan sehat.',
                        'detail'=>'Lingkungan asri',
                    ],
                ];
                $fc = [
                    'blue'  =>['bg'=>'bg-blue-50',  'ico'=>'text-blue-600',  'ring'=>'border-blue-200',  'dot'=>'bg-blue-500'],
                    'indigo'=>['bg'=>'bg-indigo-50', 'ico'=>'text-indigo-600','ring'=>'border-indigo-200','dot'=>'bg-indigo-500'],
                    'green' =>['bg'=>'bg-green-50',  'ico'=>'text-green-600', 'ring'=>'border-green-200', 'dot'=>'bg-green-500'],
                    'amber' =>['bg'=>'bg-amber-50',  'ico'=>'text-amber-600', 'ring'=>'border-amber-200', 'dot'=>'bg-amber-500'],
                    'rose'  =>['bg'=>'bg-rose-50',   'ico'=>'text-rose-600',  'ring'=>'border-rose-200',  'dot'=>'bg-rose-500'],
                    'teal'  =>['bg'=>'bg-teal-50',   'ico'=>'text-teal-600',  'ring'=>'border-teal-200',  'dot'=>'bg-teal-500'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($fasilitas as $idx => $f)
                @php $fcc = $fc[$f['color']]; @endphp
                <div class="sarana-card fade-up" style="transition-delay: {{ ($idx % 3) * 70 }}ms">
                    <div class="sarana-icon-wrap bg-gradient-to-br {{ str_replace('bg-','from-',$fcc['bg']) }} to-white">
                        <div class="w-14 h-14 {{ $fcc['bg'] }} border {{ $fcc['ring'] }} rounded-2xl flex items-center justify-center shadow-sm">
                            <i class="fa {{ $f['icon'] }} {{ $fcc['ico'] }} text-2xl"></i>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 text-base mb-1">{{ $f['title'] }}</h3>
                        <p class="text-gray-500 text-xs leading-relaxed mb-3">{{ $f['desc'] }}</p>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 {{ $fcc['dot'] }} rounded-full"></span>
                            <span class="text-xs font-semibold text-gray-600">{{ $f['detail'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         SANITASI & KEBERSIHAN
    ══════════════════════════════════════ --}}
    <section id="sanitasi" class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">

            <div class="text-center mb-12 fade-up">
                <div class="sec-label">Sanitasi</div>
                <h2 class="font-display text-3xl font-black text-gray-900 mb-3">Sanitasi & Kebersihan</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto">
                    Standar sanitasi dan kebersihan lingkungan SDN Sukorame 1.
                </p>
            </div>

            @php
                $sanitasi = [
                    ['ico'=>'fa-restroom',   'lbl'=>'Toilet Siswa Putra',  'val'=>'3 Bilik', 'kondisi'=>'Baik'],
                    ['ico'=>'fa-restroom',   'lbl'=>'Toilet Siswa Putri',  'val'=>'3 Bilik', 'kondisi'=>'Baik'],
                    ['ico'=>'fa-toilet',     'lbl'=>'Toilet Guru',         'val'=>'2 Bilik', 'kondisi'=>'Baik'],
                    ['ico'=>'fa-faucet',     'lbl'=>'Sumber Air Bersih',   'val'=>'PDAM',    'kondisi'=>'Baik'],
                    ['ico'=>'fa-trash-can',  'lbl'=>'Tempat Sampah',       'val'=>'Terpilah','kondisi'=>'Baik'],
                    ['ico'=>'fa-hand-sparkles','lbl'=>'Wastafel Cuci Tangan','val'=>'8 Unit','kondisi'=>'Baik'],
                ];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($sanitasi as $idx => $s)
                <div class="ruangan-item fade-up" style="transition-delay: {{ ($idx % 3) * 60 }}ms">
                    <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fa {{ $s['ico'] }} text-sky-600 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-800 text-xs leading-snug">{{ $s['lbl'] }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">{{ $s['val'] }}</p>
                    </div>
                    <span class="status-badge status-baik flex-shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        {{ $s['kondisi'] }}
                    </span>
                </div>
                @endforeach
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
                setTimeout(() => e.target.classList.add('visible'), i * 80);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
});
</script>
@endpush