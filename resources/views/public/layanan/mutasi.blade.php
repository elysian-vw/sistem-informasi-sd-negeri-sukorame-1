@extends('layouts.public')

@section('title', $pageTitle ?? 'Layanan Mutasi Siswa — ' . ($sekolah->nama_sekolah ?? 'SDN Sukorame 1'))

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }
    .page-hero { background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 45%, #1d4ed8 75%, #3b82f6 100%); position: relative; overflow: hidden; }
    .hero-pattern { position: absolute; inset: 0; opacity: .05; background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px; }
    .sec-label { display: inline-flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe; padding: 4px 14px; border-radius: 999px; margin-bottom: 12px; }
    .sec-label::before { content: ''; width: 6px; height: 6px; background: #1d4ed8; border-radius: 50%; }
    .section-divider { height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent); }
    .info-card { background: white; border-radius: 20px; border: 1px solid #f1f5f9; transition: all .3s; }
    .info-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(29,78,216,.1); border-color: #bfdbfe; }
    .step-number { width: 44px; height: 44px; border-radius: 50%; background: #1d4ed8; color: white; font-family: 'Playfair Display', serif; font-weight: 900; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 16px rgba(29,78,216,.3); }
    .cta-band { background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 50%, #1d4ed8 100%); position: relative; overflow: hidden; }
    .cta-band::before { content: ''; position: absolute; inset: 0; opacity: .04; background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px; }
    .fade-up { opacity: 0; transform: translateY(18px); transition: opacity .45s ease, transform .45s ease; }
    .fade-up.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')
<div class="page-hero py-20">
    <div class="hero-pattern"></div>
    <div class="max-w-5xl mx-auto px-6 relative z-10">
        <nav class="flex items-center gap-2 text-white/50 text-xs mb-8">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Layanan</span>
            <i class="fa fa-chevron-right text-[9px]"></i>
            <span class="text-white/80">Mutasi Siswa</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-amber-300 rounded-full"></span> Layanan Sekolah
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Layanan<br><span style="color:#FDE68A;">Mutasi Siswa</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-xl">
                    Layanan mutasi masuk dan keluar bagi siswa {{ $sekolah->nama_sekolah ?? 'SDN Sukorame 1' }}. Proses dilakukan secara administratif dengan persyaratan yang jelas dan transparan.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-3 flex-shrink-0">
                @foreach([['fa-sign-in-alt','text-green-300','Mutasi Masuk'],['fa-sign-out-alt','text-amber-300','Mutasi Keluar'],['5','text-blue-300','Hari Kerja'],['GRATIS','text-white','Tanpa Biaya']] as $s)
                <div class="bg-white/10 border border-white/20 rounded-2xl px-5 py-4 text-center">
                    <i class="fa {{ $s[0] }} {{ $s[1] }} text-xl mb-2 block"></i>
                    <p class="font-display text-white font-black text-lg leading-none">{{ $s[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<main class="bg-gray-50">
    @if($content && $content->content)
    <section class="py-16 bg-white border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-6">
            <div class="prose max-w-none text-gray-600">
                {!! $content->content !!}
            </div>
        </div>
    </section>
    @endif

    {{-- JENIS MUTASI --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Jenis Layanan</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Mutasi Masuk & Keluar</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto">{{ $sekolah->nama_sekolah ?? 'SDN Sukorame 1' }} melayani dua jenis mutasi siswa. Pilih sesuai kebutuhan Anda.</p>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
                @php
                $jenis = [
                    ['icon'=>'fa-sign-in-alt','bg'=>'bg-green-600','title'=>'Mutasi Masuk','desc'=>'Siswa dari sekolah lain yang ingin pindah ke ' . ($sekolah->nama_sekolah ?? 'SDN Sukorame 1') . '. Penerimaan disesuaikan dengan ketersediaan kursi di kelas tujuan.','syarat'=>['Surat permohonan pindah dari orang tua/wali','Surat keterangan pindah dari sekolah asal','Raport asli semester terakhir','Akta kelahiran (fotokopi)','Kartu Keluarga (fotokopi)','Pas foto 3×4 sebanyak 4 lembar'],'ket'=>'Proses: 3–5 hari kerja setelah berkas lengkap'],
                    ['icon'=>'fa-sign-out-alt','bg'=>'bg-amber-500','title'=>'Mutasi Keluar','desc'=>'Siswa ' . ($sekolah->nama_sekolah ?? 'SDN Sukorame 1') . ' yang akan pindah ke sekolah lain. Surat keterangan pindah diterbitkan setelah semua administrasi diselesaikan.','syarat'=>['Surat permohonan pindah dari orang tua/wali','Mengembalikan buku-buku perpustakaan','Menyelesaikan seluruh kewajiban administrasi','Surat keterangan tujuan sekolah baru'],'ket'=>'Proses: 2–3 hari kerja setelah berkas lengkap'],
                ];
                @endphp
                @foreach($jenis as $j)
                <div class="info-card p-7 fade-up">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-12 h-12 {{ $j['bg'] }} rounded-2xl flex items-center justify-center">
                            <i class="fa {{ $j['icon'] }} text-white text-lg"></i>
                        </div>
                        <h3 class="font-black text-gray-900 text-lg">{{ $j['title'] }}</h3>
                    </div>
                    <p class="text-gray-500 text-sm mb-5 leading-relaxed">{{ $j['desc'] }}</p>
                    <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Persyaratan:</p>
                    <ul class="space-y-2 mb-5">
                        @foreach($j['syarat'] as $s)
                        <li class="flex items-start gap-2 text-xs text-gray-600"><i class="fa fa-check-circle text-blue-500 mt-0.5 flex-shrink-0"></i>{{ $s }}</li>
                        @endforeach
                    </ul>
                    <div class="bg-blue-50 rounded-xl px-4 py-2.5 text-xs text-blue-800 font-semibold"><i class="fa fa-clock mr-1.5 text-blue-500"></i>{{ $j['ket'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ALUR --}}
    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Prosedur</div>
                <h2 class="font-display text-3xl font-black text-gray-900 mb-3">Alur Pengajuan Mutasi</h2>
            </div>
            @php
            $steps = [
                ['Datang ke sekolah membawa berkas persyaratan lengkap pada jam kerja (08.00–13.00 WIB).'],
                ['Petugas TU memverifikasi kelengkapan dan keabsahan berkas yang diserahkan.'],
                ['Kepala sekolah mempertimbangkan dan memutuskan persetujuan mutasi.'],
                ['Surat keterangan pindah / surat persetujuan diterbitkan dan ditandatangani.'],
                ['Orang tua/wali mengambil surat dan berkas yang diperlukan.'],
            ];
            @endphp
            <div class="space-y-5">
                @foreach($steps as $idx => $step)
                <div class="flex gap-4 fade-up" style="transition-delay:{{ $idx*60 }}ms">
                    <div class="step-number flex-shrink-0">{{ $idx+1 }}</div>
                    <div class="info-card flex-1 p-4 flex items-center">
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $step[0] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- KONTAK --}}
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach([['fa-phone','bg-blue-50','text-blue-600','Telepon TU','(0354) 123456'],['fa-clock','bg-amber-50','text-amber-600','Jam Layanan','Sen–Jum, 08.00–13.00'],['fa-location-dot','bg-red-50','text-red-500','Lokasi','Ruang TU, Lantai 1']] as $k)
                <div class="info-card p-5 flex items-center gap-4 fade-up">
                    <div class="w-11 h-11 {{ $k[1] }} rounded-2xl flex items-center justify-center flex-shrink-0"><i class="fa {{ $k[0] }} {{ $k[2] }} text-lg"></i></div>
                    <div><p class="text-gray-400 text-[11px] font-semibold uppercase tracking-wider">{{ $k[3] }}</p><p class="text-gray-900 font-bold text-sm">{{ $k[4] }}</p></div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
    const obs=new IntersectionObserver(entries=>{entries.forEach((e,i)=>{if(e.isIntersecting){setTimeout(()=>e.target.classList.add('visible'),i*80);obs.unobserve(e.target);}});},{threshold:0.1});
    document.querySelectorAll('.fade-up').forEach(el=>obs.observe(el));
});
</script>
@endpush