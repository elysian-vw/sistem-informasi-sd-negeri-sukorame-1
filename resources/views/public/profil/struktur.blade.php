@extends('layouts.public')

@section('title', $pageTitle ?? 'Struktur Organisasi — SDN Sukorame 1')

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

    /* ══════════════════════════════
       ORG CHART — BAGAN STRUKTURAL
    ══════════════════════════════ */

    /* Connector lines */
    .org-chart { position: relative; }

    /* Row layout */
    .org-row { display: flex; justify-content: center; align-items: flex-start; gap: 12px; position: relative; }

    /* Vertical line turun dari node ke garis horizontal */
    .org-vline-down {
        width: 2px; background: #bfdbfe;
        margin: 0 auto;
    }
    /* Horizontal bar di atas row children */
    .org-hbar {
        position: relative; display: flex; justify-content: center; align-items: flex-start;
    }
    .org-hbar::before {
        content: ''; position: absolute; top: 0; height: 2px;
        background: #bfdbfe;
        left: calc(50% / var(--children) + 12px);
        right: calc(50% / var(--children) + 12px);
    }

    /* Node card */
    .org-node {
        display: flex; flex-direction: column; align-items: center;
        position: relative; flex: 1; min-width: 0;
    }
    .org-node::before {
        content: ''; display: block; width: 2px; height: 20px;
        background: #bfdbfe; margin: 0 auto;
    }
    .org-node:first-child:last-child::before { height: 0; } /* single child no line */

    /* Card tampilan */
    .org-card {
        background: white; border-radius: 18px; padding: 18px 16px;
        border: 1.5px solid #e0e7ff; text-align: center;
        transition: all .25s; cursor: default;
        position: relative; overflow: hidden; width: 100%;
    }
    .org-card:hover { border-color: #93c5fd; box-shadow: 0 12px 32px rgba(29,78,216,.12); transform: translateY(-3px); }
    .org-card::after {
        content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
        background: #1d4ed8; opacity: 0; transition: opacity .25s;
    }
    .org-card:hover::after { opacity: 1; }

    /* Level warna */
    .org-card.level-0 {
        background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
        border-color: #1d4ed8;
        box-shadow: 0 16px 48px rgba(29,78,216,.35);
    }
    .org-card.level-0::after { display: none; }
    .org-card.level-0:hover { transform: translateY(-4px); box-shadow: 0 24px 56px rgba(29,78,216,.45); }

    .org-card.level-1 {
        background: #eff6ff; border-color: #bfdbfe;
    }
    .org-card.level-1::after { background: #3b82f6; }

    .org-card.level-2 { background: white; border-color: #e0e7ff; }
    .org-card.level-2::after { background: #6366f1; }

    .org-card.level-komite {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-color: #f59e0b;
    }
    .org-card.level-komite::after { background: #d97706; }

    /* Avatar lingkaran */
    .org-avatar {
        width: 52px; height: 52px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 10px; font-size: 18px;
        flex-shrink: 0;
    }
    .org-card.level-0 .org-avatar { background: rgba(255,255,255,.2); }
    .org-card.level-1 .org-avatar { background: #dbeafe; }
    .org-card.level-2 .org-avatar { background: #e0e7ff; }
    .org-card.level-komite .org-avatar { background: rgba(255,255,255,.6); }

    /* Teks */
    .org-name { font-weight: 800; font-size: 12px; line-height: 1.3; }
    .org-card.level-0 .org-name { color: white; font-size: 13px; }
    .org-card.level-1 .org-name { color: #1e3a8a; }
    .org-card.level-2 .org-name { color: #312e81; }
    .org-card.level-komite .org-name { color: #92400e; }

    .org-jabatan {
        font-size: 10px; font-weight: 600; margin-top: 3px;
        padding: 2px 8px; border-radius: 999px;
        display: inline-block;
    }
    .org-card.level-0 .org-jabatan { background: rgba(255,255,255,.2); color: #fde68a; }
    .org-card.level-1 .org-jabatan { background: #dbeafe; color: #1d4ed8; }
    .org-card.level-2 .org-jabatan { background: #e0e7ff; color: #4338ca; }
    .org-card.level-komite .org-jabatan { background: rgba(255,255,255,.5); color: #b45309; }

    .org-nip { font-size: 9px; color: rgba(255,255,255,.6); margin-top: 2px; }
    .org-card:not(.level-0) .org-nip { color: #94a3b8; }

    /* ── DAFTAR STAFF (tabel) ── */
    .staff-row {
        display: flex; align-items: center; gap-14px;
        padding: 14px 18px; border-radius: 16px;
        border: 1px solid #f1f5f9; background: white;
        transition: all .22s; gap: 14px;
    }
    .staff-row:hover { border-color: #bfdbfe; background: #f8fbff; transform: translateX(4px); }
    .staff-avatar-sm {
        width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 15px;
    }

    /* ── CTA BAND ── */
    .cta-band {
        background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 50%, #1d4ed8 100%);
        position: relative; overflow: hidden;
    }
    .cta-band::before {
        content: ''; position: absolute; inset: 0; opacity: .04;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 24px 24px;
    }

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
            <span class="text-white/80">Struktur Organisasi</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-amber-300 rounded-full"></span> Profil Sekolah
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Struktur<br>
                    <span style="color:#FDE68A;">Organisasi</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-lg" style="font-size:1rem;">
                    Susunan kepemimpinan dan tenaga pendidik SD Negeri Sukorame 1 Kota Kediri
                    tahun ajaran <strong class="text-white">{{ date('Y') }}/{{ date('Y') + 1 }}</strong>.
                </p>
            </div>

            {{-- Quick stat --}}
            <div class="flex gap-3 flex-shrink-0">
                @php
                    $hs = [
                        ['val'=> $stats['guru'] ?? '—', 'lbl'=>'Pendidik', 'ico'=>'fa-chalkboard-teacher'],
                        ['val'=> $stats['tendik'] ?? '—','lbl'=>'Tendik',   'ico'=>'fa-user-tie'],
                        ['val'=> $stats['kelas'] ?? '—', 'lbl'=>'Kelas',    'ico'=>'fa-door-open'],
                    ];
                @endphp
                @foreach ($hs as $h)
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
         BAGAN STRUKTUR
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Bagan Organisasi</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Struktur Kepemimpinan</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Susunan organisasi yang memastikan pengelolaan sekolah berjalan efektif, transparan, dan akuntabel.
                </p>
            </div>

            @php
                /*
                 * Data default — ganti dengan query dari DB jika sudah ada model Guru/Pegawai.
                 * Contoh: $kepsek = \App\Models\Guru::where('jabatan','kepala_sekolah')->first();
                 */
                $kepsek   = $kepsek   ?? null;
                $wakasek  = $wakasek  ?? null;
                $komite   = $komite   ?? null;
            @endphp

            {{-- ── LEVEL 0: Kepala Sekolah & Komite (sejajar) ── --}}
            <div class="fade-up">
                <div class="flex flex-col md:flex-row items-start justify-center gap-6 md:gap-16">

                    {{-- Komite Sekolah (kiri) --}}
                    <div class="flex flex-col items-center w-full md:w-52">
                        <div class="org-card level-komite w-full">
                            <div class="org-avatar bg-amber-100">
                                <i class="fa fa-users text-amber-600"></i>
                            </div>
                            <p class="org-name">{{ $komite?->nama ?? 'Komite Sekolah' }}</p>
                            <span class="org-jabatan">Ketua Komite</span>
                        </div>
                        {{-- garis penghubung ke kepsek (horizontal, hanya desktop) --}}
                        <div class="hidden md:block w-full h-0.5 bg-blue-200 mt-auto self-end" style="margin-top:44px; width:50%; margin-left:auto;"></div>
                    </div>

                    {{-- Kepala Sekolah (tengah) --}}
                    <div class="flex flex-col items-center w-full md:w-64">
                        <div class="org-card level-0 w-full">
                            <div class="org-avatar">
                                @if($kepsek?->user?->foto)
                                    <img src="{{ Storage::url($kepsek->user->foto) }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <i class="fa fa-user-tie text-white text-2xl"></i>
                                @endif
                            </div>
                            <p class="org-name">{{ $kepsek?->user?->name ?? ($sekolah?->nama_kepala_sekolah ?? 'Nama Kepala Sekolah') }}</p>
                            <span class="org-jabatan">Kepala Sekolah</span>
                            <p class="org-nip mt-1">NIP. {{ $kepsek?->nip ?? ($sekolah?->nip_kepala_sekolah ?? '—') }}</p>
                        </div>
                        {{-- garis turun --}}
                        <div class="w-0.5 bg-blue-300" style="height:32px;"></div>
                    </div>

                    {{-- Ruang kosong kanan (balance) --}}
                    <div class="hidden md:block w-52"></div>
                </div>
            </div>

            {{-- ── LEVEL 1: Wakasek ── --}}
            <div class="fade-up">
                <div class="flex justify-center">
                    <div class="w-full md:w-64 flex flex-col items-center">
                        <div class="org-card level-1 w-full">
                            <div class="org-avatar">
                                @if($wakasek?->user?->foto)
                                    <img src="{{ Storage::url($wakasek->user->foto) }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <i class="fa fa-user-shield text-blue-600"></i>
                                @endif
                            </div>
                            <p class="org-name">{{ $wakasek?->user?->name ?? 'Wakil Kepala Sekolah' }}</p>
                            <span class="org-jabatan">Waka Sekolah</span>
                            <p class="org-nip mt-1">NIP. {{ $wakasek?->nip ?? '—' }}</p>
                        </div>
                        {{-- garis turun ke TU + Operator --}}
                        <div class="w-0.5 bg-blue-200" style="height:28px;"></div>
                    </div>
                </div>
            </div>

            {{-- ── LEVEL 2: TU, Operator, Bendahara ── --}}
            <div class="fade-up">
                {{-- Garis horizontal --}}
                <div class="flex justify-center mb-0">
                    <div class="w-full max-w-2xl h-0.5 bg-blue-200"></div>
                </div>

                <div class="flex flex-col md:flex-row justify-center gap-4 md:gap-6">
                    @php
                        $level2 = [
                            ['icon'=>'fa-file-alt',    'nama'=> $tu?->user?->name       ?? 'Kepala TU',         'jabatan'=>'Tata Usaha',   'nip'=> $tu?->nip       ?? '—', 'foto' => $tu?->user?->foto],
                            ['icon'=>'fa-laptop',      'nama'=> $operator?->user?->name  ?? 'Operator Sekolah',  'jabatan'=>'Operator Dapodik','nip'=> $operator?->nip ?? '—', 'foto' => $operator?->user?->foto],
                            ['icon'=>'fa-coins',       'nama'=> $bendahara?->user?->name ?? 'Bendahara Sekolah', 'jabatan'=>'Bendahara BOS','nip'=> $bendahara?->nip ?? '—', 'foto' => $bendahara?->user?->foto],
                        ];
                    @endphp
                    @foreach ($level2 as $l2)
                    <div class="flex flex-col items-center flex-1 max-w-xs mx-auto">
                        <div class="w-0.5 bg-blue-200" style="height:24px;"></div>
                        <div class="org-card level-2 w-full">
                            <div class="org-avatar bg-indigo-50">
                                @if($l2['foto'])
                                    <img src="{{ Storage::url($l2['foto']) }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <i class="fa {{ $l2['icon'] }} text-indigo-500"></i>
                                @endif
                            </div>
                            <p class="org-name">{{ $l2['nama'] }}</p>
                            <span class="org-jabatan">{{ $l2['jabatan'] }}</span>
                            <p class="org-nip mt-1">NIP. {{ $l2['nip'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── LEVEL 3: Wali Kelas ── --}}
            <div class="mt-10 fade-up">
                <div class="text-center mb-8">
                    <span class="inline-flex items-center gap-2 bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-full">
                        <i class="fa fa-chalkboard-teacher text-amber-300"></i> Wali Kelas
                    </span>
                </div>

                {{-- Garis dari atas --}}
                <div class="flex justify-center mb-0">
                    <div class="w-0.5 bg-blue-200" style="height:24px;"></div>
                </div>
                <div class="flex justify-center">
                    <div class="w-full max-w-4xl h-0.5 bg-blue-200"></div>
                </div>

                @php
                    /*
                     * Ganti dengan data dari DB:
                     * $waliKelas = \App\Models\Guru::whereNotNull('kelas_id')->with('kelas')->get();
                     */
                    $wali_colors = ['bg-blue-50 text-blue-700','bg-indigo-50 text-indigo-700','bg-violet-50 text-violet-700','bg-purple-50 text-purple-700'];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-0">
                    @forelse ($waliKelas as $idx => $wk)
                    @php $wc = $wali_colors[$idx % count($wali_colors)]; @endphp
                    <div class="flex flex-col items-center">
                        <div class="w-0.5 bg-blue-200" style="height:20px;"></div>
                        <div class="org-card level-2 w-full">
                            <div class="org-avatar bg-blue-50">
                                @if($wk->user?->foto)
                                    <img src="{{ Storage::url($wk->user->foto) }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <i class="fa fa-chalkboard text-blue-500"></i>
                                @endif
                            </div>
                            <span class="inline-block text-[10px] font-black px-2.5 py-1 rounded-lg mb-2 {{ $wc }}">
                                Kelas {{ $wk->kelas->nama_kelas ?? '-' }}
                            </span>
                            <p class="org-name">{{ $wk->user?->name ?? '-' }}</p>
                            <p class="org-nip mt-1">NIP. {{ $wk->nip ?? '-' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-10 text-gray-400">Belum ada data wali kelas.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ══════════════════════════════════════
         GURU MAPEL & TENDIK
    ══════════════════════════════════════ --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">

            {{-- Guru Mata Pelajaran --}}
            <div class="mb-16 fade-up">
                <div class="flex items-end justify-between gap-4 mb-8">
                    <div>
                        <div class="sec-label">Tenaga Pendidik</div>
                        <h2 class="font-display text-2xl md:text-3xl font-black text-gray-900">Guru Mata Pelajaran</h2>
                    </div>
                    <span class="text-xs text-gray-400 font-semibold bg-white border border-gray-200 px-3 py-1.5 rounded-full">
                        TA {{ date('Y') }}/{{ date('Y')+1 }}
                    </span>
                </div>

                <div class="space-y-3">
                    @php
                        /*
                         * Ganti dengan:
                         * $guruMapel = \App\Models\Guru::where('tipe','mapel')->orderBy('nama')->get();
                         */
                        $guru_mapel_default = [
                            ['mapel'=>'PJOK',                 'icon'=>'fa-running',       'color'=>'bg-green-50 text-green-600'],
                            ['mapel'=>'Pendidikan Agama Islam','icon'=>'fa-mosque',        'color'=>'bg-amber-50 text-amber-600'],
                            ['mapel'=>'Seni Budaya & Prakarya','icon'=>'fa-palette',       'color'=>'bg-pink-50 text-pink-600'],
                            ['mapel'=>'Bahasa Inggris (Mulok)','icon'=>'fa-language',      'color'=>'bg-purple-50 text-purple-600'],
                            ['mapel'=>'Komputer (TIK)',        'icon'=>'fa-laptop-code',   'color'=>'bg-blue-50 text-blue-600'],
                            ['mapel'=>'Bimbingan Konseling',   'icon'=>'fa-heart',         'color'=>'bg-rose-50 text-rose-600'],
                        ];
                        $guruMapelList = isset($guruMapel) ? $guruMapel : collect($guru_mapel_default);
                    @endphp
                    @foreach ($guruMapelList as $gm)
                    <div class="staff-row">
                        <div class="staff-avatar-sm {{ is_array($gm) ? $gm['color'] : 'bg-blue-50 text-blue-600' }} rounded-2xl">
                            <i class="fa {{ is_array($gm) ? $gm['icon'] : 'fa-user' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-sm">{{ is_array($gm) ? '—' : $gm->nama }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">{{ is_array($gm) ? $gm['mapel'] : $gm->mapel }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-[10px] font-semibold bg-gray-100 text-gray-500 px-2.5 py-1 rounded-full">
                                {{ is_array($gm) ? 'NIP. —' : 'NIP. '.$gm->nip }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tenaga Kependidikan --}}
            <div class="fade-up">
                <div class="flex items-end justify-between gap-4 mb-8">
                    <div>
                        <div class="sec-label">Tenaga Kependidikan</div>
                        <h2 class="font-display text-2xl md:text-3xl font-black text-gray-900">Staf & Tenaga Pendukung</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse ($tendik as $td)
                    <div class="staff-row">
                        <div class="staff-avatar-sm bg-blue-50 text-blue-600 rounded-2xl overflow-hidden">
                            @if($td->user?->foto)
                                <img src="{{ Storage::url($td->user->foto) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa fa-user"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-sm">{{ $td->user?->name ?? '-' }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">{{ $td->jabatan ?? '-' }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-6 text-gray-400 bg-white rounded-2xl border border-dashed">
                        Belum ada data tenaga kependidikan.
                    </div>
                    @endforelse
                </div>
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
                setTimeout(() => e.target.classList.add('visible'), i * 90);
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
});
</script>
@endpush