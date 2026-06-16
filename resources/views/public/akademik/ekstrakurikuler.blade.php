@extends('layouts.public')

@section('title', $pageTitle ?? 'Ekstrakurikuler — SDN Sukorame 1')

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }

    .page-hero {
        background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 45%, #1d4ed8 75%, #3b82f6 100%);
        position: relative; overflow: hidden;
    }
    .hero-pattern {
        position: absolute; inset: 0; opacity: .05;
        background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0);
        background-size: 28px 28px;
    }
    .sec-label {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
        color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe;
        padding: 4px 14px; border-radius: 999px; margin-bottom: 12px;
    }
    .sec-label::before { content: ''; width: 6px; height: 6px; background: #1d4ed8; border-radius: 50%; }
    .section-divider { height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent); }

    /* Ekskul Card */
    .ekskul-card {
        background: white; border-radius: 24px;
        border: 1.5px solid #e0e7ff; overflow: hidden;
        transition: all .28s;
    }
    .ekskul-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 16px 40px rgba(29,78,216,.14);
        transform: translateY(-4px);
    }
    .ekskul-card-img {
        height: 120px; display: flex; align-items: center; justify-content: center;
        font-size: 3rem; position: relative; overflow: hidden;
    }
    .ekskul-card-img::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,.15));
    }
    .ekskul-card-body { padding: 18px 20px 20px; }

    /* List Row */
    .ekskul-row {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 14px 16px; border-radius: 14px;
        border: 1px solid #f1f5f9; background: white;
        transition: all .22s; margin-bottom: 8px;
    }
    .ekskul-row:last-child { margin-bottom: 0; }
    .ekskul-row:hover { border-color: #bfdbfe; background: #f8fbff; transform: translateX(4px); }

    /* Prestasi Card */
    .prestasi-card {
        background: white; border-radius: 16px;
        border: 1.5px solid #e0e7ff; padding: 16px;
        transition: all .22s;
    }
    .prestasi-card:hover { border-color: #fcd34d; box-shadow: 0 6px 20px rgba(251,191,36,.2); }

    /* Badge */
    .badge-wajib   { background: #fee2e2; color: #dc2626; }
    .badge-pilihan { background: #f1f5f9; color: #64748b; }
    .badge-unggulan{ background: #fef3c7; color: #d97706; }

    /* Stat mini */
    .stat-mini {
        background: white; border-radius: 16px;
        border: 1.5px solid #e0e7ff; padding: 20px 14px;
        text-align: center; transition: all .22s;
    }
    .stat-mini:hover { border-color: #93c5fd; box-shadow: 0 8px 20px rgba(29,78,216,.1); transform: translateY(-2px); }

    /* Table */
    .jadwal-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .jadwal-table thead th {
        background: #1e3a8a; color: white; font-size: 11px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; padding: 10px 14px;
    }
    .jadwal-table thead th:first-child { border-radius: 12px 0 0 0; }
    .jadwal-table thead th:last-child  { border-radius: 0 12px 0 0; }
    .jadwal-table tbody tr { transition: background .15s; }
    .jadwal-table tbody tr:hover { background: #f0f7ff; }
    .jadwal-table tbody td { padding: 10px 14px; font-size: 12px; border-bottom: 1px solid #f1f5f9; color: #374151; }

    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div style="position:absolute;width:400px;height:400px;right:-100px;top:-100px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.06) 0%,transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;width:240px;height:240px;left:6%;bottom:-70px;border-radius:50%;background:radial-gradient(circle,rgba(217,119,6,.12) 0%,transparent 70%);pointer-events:none;"></div>

    <div class="max-w-5xl mx-auto px-6 relative z-10">
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <a href="#" class="hover:text-white transition-colors">Kesiswaan</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Ekstrakurikuler</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-amber-300 rounded-full"></span> Kesiswaan
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Ekstra<br>
                    <span style="color:#FDE68A;">Kurikuler</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-lg" style="font-size:1rem;">
                    Pengembangan bakat, minat, dan potensi siswa SD Negeri Sukorame 1
                    melalui kegiatan ekstrakurikuler yang terstruktur dan berkualitas.
                </p>
            </div>
            <div class="flex gap-3 flex-shrink-0">
                @php $hs = [
                    ['val'=> $ekskul->count(),  'lbl'=>'Ekskul',      'ico'=>'fa-star'],
                    ['val'=> '12', 'lbl'=>'Pelatih',     'ico'=>'fa-chalkboard-teacher'],
                    ['val'=> '30+','lbl'=>'Prestasi',    'ico'=>'fa-trophy'],
                ]; @endphp
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

    {{-- OVERVIEW --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Gambaran Umum</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Pengembangan Diri Siswa</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed">
                    Kegiatan ekstrakurikuler dirancang untuk mengembangkan potensi siswa secara optimal
                    di luar jam pelajaran, mencakup bidang olahraga, seni, sains, dan kepramukaan.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-16 fade-up">
                @php $ov = [
                    ['icon'=>'fa-star',          'val'=>$ekskul->count(),    'lbl'=>'Jenis Ekskul',  'color'=>'bg-amber-50 text-amber-600'],
                    ['icon'=>'fa-users',          'val'=>'280+', 'lbl'=>'Peserta',       'color'=>'bg-blue-50 text-blue-600'],
                    ['icon'=>'fa-trophy',         'val'=>'30+',  'lbl'=>'Prestasi',      'color'=>'bg-indigo-50 text-indigo-600'],
                    ['icon'=>'fa-medal',          'val'=>'Tk. Kota','lbl'=>'Level Tertinggi','color'=>'bg-green-50 text-green-600'],
                ]; @endphp
                @foreach ($ov as $o)
                <div class="stat-mini fade-up">
                    <div class="w-12 h-12 {{ $o['color'] }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i class="fa {{ $o['icon'] }} text-lg"></i>
                    </div>
                    <p class="font-black text-gray-900 text-2xl leading-tight">{{ $o['val'] }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ $o['lbl'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Ekskul Cards --}}
            @php
                $ekskulList = $ekskul ?? collect([]);
                $iconMap = [
                    'Pramuka' => '🏕️',
                    'Seni Tari' => '💃',
                    'Futsal' => '⚽',
                    'Paduan Suara' => '🎶',
                    'Seni Lukis' => '🎨',
                    'Olimpiade Sains' => '🔬',
                    'Kaligrafi' => '✒️',
                    'Bulu Tangkis' => '🏸',
                    'Robotika' => '🤖',
                    'PMR' => '🚑',
                    'Menggambar' => '🖍️',
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 fade-up">
                @foreach ($ekskulList as $ek)
                <div class="ekskul-card">
                    <div class="ekskul-card-img bg-blue-50">
                        @if($ek->foto)
                            <img src="{{ asset('storage/' . $ek->foto) }}" alt="{{ $ek->nama }}" class="w-full h-full object-cover">
                        @else
                            <span>{{ $iconMap[$ek->nama] ?? '⭐' }}</span>
                        @endif
                    </div>
                    <div class="ekskul-card-body">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <p class="font-black text-gray-900 text-base">{{ $ek->nama }}</p>
                            @php
                                $isWajib = strtolower($ek->nama) == 'pramuka';
                                $badgeCls = $isWajib ? 'badge-wajib' : 'badge-pilihan';
                                $badgeLbl = $isWajib ? 'Wajib' : 'Pilihan';
                            @endphp
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full {{ $badgeCls }} flex-shrink-0">{{ $badgeLbl }}</span>
                        </div>
                        <p class="text-gray-500 text-xs leading-relaxed mb-3">{{ $ek->deskripsi ?: 'Kegiatan pengembangan bakat dan minat siswa.' }}</p>
                        <div class="space-y-1.5 text-xs text-gray-500 border-t border-gray-100 pt-3">
                            <div class="flex items-center gap-2">
                                <i class="fa fa-map-marker-alt text-blue-400 w-3 text-center"></i>
                                <span>{{ $ek->tempat ?: '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa fa-calendar-day text-blue-400 w-3 text-center"></i>
                                <span>{{ $ek->hari }}, {{ \Carbon\Carbon::parse($ek->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ek->waktu_selesai)->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa fa-chalkboard-teacher text-blue-400 w-3 text-center"></i>
                                <span>{{ $ek->pembina->user->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- JADWAL --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="mb-10 fade-up">
                <div class="sec-label">Jadwal Kegiatan</div>
                <h2 class="font-display text-2xl md:text-3xl font-black text-gray-900">Jadwal Ekstrakurikuler</h2>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden fade-up shadow-sm mb-8">
                <table class="jadwal-table">
                    <thead>
                        <tr>
                            <th>Ekstrakurikuler</th>
                            <th>Hari</th>
                            <th>Waktu</th>
                            <th>Tempat</th>
                            <th>Pelatih</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ekskulList as $ek)
                        <tr>
                            <td><span class="font-bold text-gray-900">{{ $ek->nama }}</span></td>
                            <td>{{ $ek->hari }}</td>
                            <td><span class="text-blue-600 font-semibold text-[11px]">{{ \Carbon\Carbon::parse($ek->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ek->waktu_selesai)->format('H:i') }}</span></td>
                            <td>{{ $ek->tempat ?: '-' }}</td>
                            <td>{{ $ek->pembina->user->name ?? '-' }}</td>
                            <td>
                                @php
                                    $isWajib = strtolower($ek->nama) == 'pramuka';
                                    $bc = $isWajib ? 'badge-wajib' : 'badge-pilihan';
                                    $bl = $isWajib ? 'Wajib' : 'Pilihan';
                                @endphp
                                <span class="text-[9px] font-bold px-2 py-0.5 rounded-full {{ $bc }}">{{ $bl }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3 fade-up">
                <i class="fa fa-info-circle text-amber-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-amber-800 text-xs leading-relaxed">
                    Jadwal dapat berubah menyesuaikan kalender akademik dan kegiatan sekolah.
                    Informasi lebih lanjut dapat ditanyakan kepada wali kelas atau bagian kesiswaan.
                    Pendaftaran ekskul dilakukan di awal tahun ajaran.
                </p>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- PRESTASI --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-12 fade-up">
                <div class="sec-label">Pencapaian</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Prestasi Ekstrakurikuler</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto">
                    Berbagai prestasi yang telah diraih siswa SDN Sukorame 1 dalam kegiatan lomba dan kompetisi.
                </p>
            </div>

            @php
                $prestasi = [
                    ['nama'=>'Juara I FLS2N Paduan Suara',          'level'=>'Tingkat Kota',       'tahun'=>'2023', 'icon'=>'fa-microphone',  'color'=>'bg-amber-50 border-amber-200 text-amber-600', 'medal'=>'🥇'],
                    ['nama'=>'Juara I OSN IPA',                     'level'=>'Tingkat Kota Kediri', 'tahun'=>'2023', 'icon'=>'fa-flask',        'color'=>'bg-blue-50 border-blue-200 text-blue-600',   'medal'=>'🥇'],
                    ['nama'=>'Juara I Kaligrafi MTQ Pelajar',       'level'=>'Tingkat Kota',       'tahun'=>'2023', 'icon'=>'fa-pen-fancy',    'color'=>'bg-teal-50 border-teal-200 text-teal-600',   'medal'=>'🥇'],
                    ['nama'=>'Juara II FLS2N Seni Lukis',           'level'=>'Tingkat Kota',       'tahun'=>'2023', 'icon'=>'fa-paint-brush',  'color'=>'bg-purple-50 border-purple-200 text-purple-600','medal'=>'🥈'],
                    ['nama'=>'Juara II Jambore Kwarcab',            'level'=>'Tingkat Kwarcab',    'tahun'=>'2023', 'icon'=>'fa-campground',   'color'=>'bg-amber-50 border-amber-200 text-amber-600', 'medal'=>'🥈'],
                    ['nama'=>'Juara II O2SN Bulu Tangkis',         'level'=>'Tingkat Kecamatan',  'tahun'=>'2023', 'icon'=>'fa-shuttlecock',  'color'=>'bg-cyan-50 border-cyan-200 text-cyan-600',   'medal'=>'🥈'],
                    ['nama'=>'Juara I Lomba Tari Tradisional',      'level'=>'Tingkat Kecamatan',  'tahun'=>'2023', 'icon'=>'fa-music',        'color'=>'bg-pink-50 border-pink-200 text-pink-600',   'medal'=>'🥇'],
                    ['nama'=>'Juara III O2SN Futsal',               'level'=>'Tingkat Kecamatan',  'tahun'=>'2023', 'icon'=>'fa-futbol',       'color'=>'bg-green-50 border-green-200 text-green-600','medal'=>'🥉'],
                    ['nama'=>'Finalis Lomba Robotik SD',            'level'=>'Tingkat Provinsi',   'tahun'=>'2023', 'icon'=>'fa-robot',        'color'=>'bg-orange-50 border-orange-200 text-orange-600','medal'=>'🏅'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 fade-up">
                @foreach ($prestasi as $p)
                <div class="prestasi-card">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl leading-none mt-0.5">{{ $p['medal'] }}</span>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-sm leading-tight mb-1">{{ $p['nama'] }}</p>
                            <p class="text-xs text-gray-500 mb-2">{{ $p['level'] }} · {{ $p['tahun'] }}</p>
                        </div>
                    </div>
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