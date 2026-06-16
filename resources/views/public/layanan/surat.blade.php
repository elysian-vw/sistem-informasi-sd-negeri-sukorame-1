@extends('layouts.public')

@section('title', $pageTitle ?? 'Layanan Surat — ' . ($sekolah->nama_sekolah ?? 'SDN Sukorame 1'))

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .font-display { font-family: 'Playfair Display', serif; }
    .page-hero { background: linear-gradient(135deg, #0c1445 0%, #1e3a8a 45%, #1d4ed8 75%, #3b82f6 100%); position: relative; overflow: hidden; }
    .hero-pattern { position: absolute; inset: 0; opacity: .05; background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px; }
    .sec-label { display: inline-flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe; padding: 4px 14px; border-radius: 999px; margin-bottom: 12px; }
    .sec-label::before { content: ''; width: 6px; height: 6px; background: #1d4ed8; border-radius: 50%; }
    .section-divider { height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent); }
    .surat-card { background: white; border-radius: 20px; border: 1px solid #f1f5f9; transition: all .3s; position: relative; overflow: hidden; }
    .surat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(29,78,216,.1); border-color: #bfdbfe; }
    .surat-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: #1d4ed8; opacity: 0; transition: opacity .3s; }
    .surat-card:hover::after { opacity: 1; }
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
            <span class="text-white/80">Surat Keterangan</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center gap-10">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-amber-300 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest mb-5">
                    <span class="w-1.5 h-1.5 bg-amber-300 rounded-full"></span> Layanan Sekolah
                </div>
                <h1 class="font-display text-white font-black leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.8rem);">
                    Layanan<br><span style="color:#FDE68A;">Surat Keterangan</span>
                </h1>
                <p class="text-white/70 leading-relaxed max-w-xl">
                    {{ $sekolah->nama_sekolah ?? 'SDN Sukorame 1' }} menyediakan berbagai layanan penerbitan surat keterangan resmi untuk keperluan siswa aktif maupun alumni.
                </p>
                </div>
                <div class="grid grid-cols-2 gap-3 flex-shrink-0">
                @foreach([['6','fa-file-alt','text-blue-300','Jenis Surat'],['3','fa-calendar-day','text-green-300','Hari Proses'],['GRATIS','fa-circle-check','text-amber-300','Tanpa Biaya'],['Resmi','fa-stamp','text-white','Berlegalisir']] as $s)
                <div class="bg-white/10 border border-white/20 rounded-2xl px-5 py-4 text-center">
                    <i class="fa {{ $s[1] }} {{ $s[2] }} text-xl mb-2 block"></i>
                    <p class="font-display text-white font-black text-xl leading-none">{{ $s[0] }}</p>
                    <p class="text-white/60 text-[11px] mt-1">{{ $s[3] }}</p>
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

                {{-- JENIS SURAT --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-14 fade-up">
                <div class="sec-label">Jenis Surat</div>
                <h2 class="font-display text-3xl md:text-4xl font-black text-gray-900 mb-3">Surat yang Dapat Diterbitkan</h2>
                <p class="text-gray-500 text-sm max-w-xl mx-auto">Pilih jenis surat sesuai kebutuhan. Semua surat diterbitkan secara resmi dan ditandatangani kepala sekolah.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @php
                $surats = [
                    ['fa-user-check','bg-blue-50','text-blue-600','Surat Keterangan Aktif Sekolah','Menerangkan bahwa siswa masih aktif terdaftar. Diperlukan untuk keperluan beasiswa, BPJS, dan lain-lain.','Fotokopi raport terakhir + KK','1–2 hari kerja'],
                    ['fa-graduation-cap','bg-green-50','text-green-600','Surat Keterangan Lulus','Diterbitkan bagi siswa kelas VI yang telah menyelesaikan pendidikan di SDN Sukorame 1.','Tidak diperlukan berkas tambahan','1 hari kerja'],
                    ['fa-file-signature','bg-amber-50','text-amber-600','Surat Rekomendasi','Rekomendasi kepala sekolah untuk keperluan beasiswa, perlombaan, atau pendaftaran ke jenjang berikutnya.','Surat permohonan dari orang tua/wali','2–3 hari kerja'],
                    ['fa-hospital','bg-rose-50','text-rose-600','Surat Keterangan untuk Keperluan Rumah Sakit','Menerangkan status aktif siswa untuk administrasi fasilitas kesehatan.','Fotokopi KK + surat keterangan sakit','1 hari kerja'],
                    ['fa-building-columns','bg-purple-50','text-purple-600','Surat Keterangan untuk Bank / Instansi','Surat resmi untuk keperluan pembukaan rekening, pengajuan KIP, atau administrasi instansi lain.','Fotokopi KK + identitas orang tua','1–2 hari kerja'],
                    ['fa-arrow-right-from-bracket','bg-indigo-50','text-indigo-600','Surat Keterangan Pindah Sekolah','Diterbitkan bagi siswa yang akan melanjutkan pendidikan di sekolah lain.','Surat permohonan + identitas siswa','2–3 hari kerja'],
                ];
                @endphp
                @foreach($surats as $idx => $s)
                <div class="surat-card p-6 fade-up" style="transition-delay:{{ $idx*70 }}ms">
                    <div class="w-11 h-11 {{ $s[1] }} rounded-2xl flex items-center justify-center mb-4">
                        <i class="fa {{ $s[0] }} {{ $s[2] }} text-lg"></i>
                    </div>
                    <h3 class="font-black text-gray-900 text-sm mb-2 leading-snug">{{ $s[3] }}</h3>
                    <p class="text-gray-500 text-xs leading-relaxed mb-4">{{ $s[4] }}</p>
                    <div class="border-t border-gray-100 pt-3 space-y-1.5">
                        <div class="flex items-start gap-2 text-xs text-gray-600"><i class="fa fa-folder-open text-gray-400 mt-0.5 flex-shrink-0"></i><span><strong>Syarat:</strong> {{ $s[5] }}</span></div>
                        <div class="flex items-center gap-2 text-xs text-blue-700 font-semibold"><i class="fa fa-clock text-blue-400 flex-shrink-0"></i><span>{{ $s[6] }}</span></div>
                    </div>
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
                <h2 class="font-display text-3xl font-black text-gray-900 mb-3">Cara Mengajukan Surat</h2>
            </div>
            @php
            $steps = [
                ['Datang ke Ruang Tata Usaha pada jam layanan dan sampaikan jenis surat yang dibutuhkan.','fa-door-open'],
                ['Isi buku permohonan surat dan serahkan berkas persyaratan kepada petugas TU.','fa-pen'],
                ['Petugas memproses surat dan menyerahkan ke kepala sekolah untuk ditandatangani.','fa-stamp'],
                ['Ambil surat yang telah selesai sesuai estimasi waktu yang diberikan petugas.','fa-hand-holding'],
            ];
            @endphp
            <div class="space-y-5">
                @foreach($steps as $idx => $step)
                <div class="flex gap-4 fade-up" style="transition-delay:{{ $idx*60 }}ms">
                    <div class="step-number">{{ $idx+1 }}</div>
                    <div class="bg-white rounded-2xl border border-gray-100 flex-1 p-4 flex items-center gap-3 hover:shadow-md hover:border-blue-200 transition-all">
                        <i class="fa {{ $step[1] }} text-blue-400 text-lg flex-shrink-0"></i>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $step[0] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8 bg-amber-50 border border-amber-200 rounded-2xl p-5 fade-up">
                <div class="flex items-start gap-3">
                    <i class="fa fa-lightbulb text-amber-500 mt-0.5"></i>
                    <div>
                        <p class="font-black text-amber-900 text-sm mb-1">Catatan Penting</p>
                        <p class="text-amber-800 text-xs leading-relaxed">Surat yang diambil lebih dari <strong>14 hari</strong> setelah selesai diproses akan dimusnahkan dan harus diajukan kembali. Harap ambil tepat waktu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- KONTAK --}}
    <section class="py-12 bg-white">
        <div class="max-w-5xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-3 gap-5">
            @foreach([['fa-phone','bg-blue-50','text-blue-600','Telepon TU','(0354) 123456'],['fa-clock','bg-amber-50','text-amber-600','Jam Layanan','Sen–Jum, 08.00–13.00'],['fa-location-dot','bg-red-50','text-red-500','Lokasi','Ruang TU, Lantai 1']] as $k)
            <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md hover:border-blue-200 transition-all fade-up">
                <div class="w-11 h-11 {{ $k[1] }} rounded-2xl flex items-center justify-center flex-shrink-0"><i class="fa {{ $k[0] }} {{ $k[2] }} text-lg"></i></div>
                <div><p class="text-gray-400 text-[11px] font-semibold uppercase tracking-wider">{{ $k[3] }}</p><p class="text-gray-900 font-bold text-sm">{{ $k[4] }}</p></div>
            </div>
            @endforeach
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