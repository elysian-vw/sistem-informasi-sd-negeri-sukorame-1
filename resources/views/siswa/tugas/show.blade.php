@extends('layouts.siswa')
@section('title', $tugas->judul)

@section('content')
<style>
    /* Style tetap sama dengan yang kamu punya, sudah bagus (tapi jangan sombong) */
    .detail-container { max-width: 800px; margin: 0 auto; padding: 20px; font-family: 'Nunito', sans-serif; color: #1e293b; }
    .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .btn-back { background: #fff; border: 2px solid #E5E7EB; padding: 10px 60px; border-radius: 15px; font-weight: 800; color: #4b5563; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 0 #E5E7EB; }
    .btn-back:hover { transform: translateY(-2px); box-shadow: 0 6px 0 #E5E7EB; background: #F9FAFB; }
    .btn-back:active { transform: translateY(2px); box-shadow: 0 0 0 #E5E7EB; }
    .hero-card { background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); border-radius: 30px; padding: 35px; position: relative; overflow: hidden; margin-bottom: 25px; border: 2px solid #C7D2FE; }
    .type-tag { background: #fff; color: #6366F1; padding: 6px 18px; border-radius: 99px; font-weight: 800; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .hero-title { font-size: 30px; font-weight: 900; color: #1e1b4b; margin: 20px 0 15px; }
    .pill { background: rgba(255,255,255,0.7); padding: 8px 18px; border-radius: 15px; font-size: 14px; font-weight: 700; color: #475569; border: 1px solid #fff; }
    .section-box { background: #fff; border: 2px solid #E5E7EB; border-radius: 25px; padding: 25px; margin-bottom: 25px; }
    .section-title { font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; letter-spacing: 0.5px; }
    .instruksi-content { background: #F8FAFC; padding: 20px; border-radius: 18px; border: 1px dashed #cbd5e1; line-height: 1.7; color: #334155; font-weight: 600; font-size: 15px; }
    .option-btn { display: flex; align-items: center; gap: 15px; background: #fff; border: 2px solid #E5E7EB; padding: 20px; border-radius: 22px; margin-bottom: 15px; cursor: pointer; transition: 0.2s; width: 100%; text-align: left; box-shadow: 0 4px 0 #E5E7EB; }
    .option-btn:hover { border-color: #6366F1; transform: translateY(-3px); box-shadow: 0 7px 0 #E5E7EB; }
    .option-label { width: 45px; height: 45px; background: #F1F5F9; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #475569; font-size: 18px; }
    .btn-submit { background: #6366F1; color: #fff; border: none; padding: 18px; border-radius: 20px; font-weight: 800; font-size: 16px; cursor: pointer; width: 100%; transition: 0.3s; box-shadow: 0 6px 0 #4338CA; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; }
</style>

<div class="detail-container">
    @php
        $mapel = $tugas->mataPelajaran->nama ?? 'Umum';
        $isCbt = $tugas->isCbt();
    @endphp

    <div class="header-nav">
        <a href="{{ route('siswa.tugas.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div class="pill" style="background: #F5F3FF; color: #7C3AED; border-color: #DDD6FE;">
            {{ $mapel }}
        </div>
    </div>

    {{-- Info Card Utama --}}
    <div class="hero-card">
        <span class="type-tag">{{ $isCbt ? '📝 Ujian Online (CBT)' : '📑 Tugas Upload' }}</span>
        <h1 class="hero-title">{{ $tugas->judul }}</h1>
        <div class="info-pills">
            <div class="pill">🧑‍🏫 {{ $tugas->guru->user->name ?? 'Guru' }}</div>
            <div class="pill">📅 Deadline: {{ $tugas->deadline ? $tugas->deadline->isoFormat('D MMMM Y, HH:mm') : 'Tanpa Batas' }}</div>
        </div>
    </div>

    @if($isCbt)
        {{-- Logika Tampilan Preview CBT --}}
        <div class="section-box">
            <div class="section-title">📝 Informasi Ujian</div>
            <p style="font-weight: 600; color: #4b5563;">Jumlah Soal: {{ $tugas->pertanyaans->count() }} butir</p>
            <div class="instruksi-content">
                Pastikan koneksi internet stabil sebelum memulai. Kamu tidak bisa mengulang ujian jika sudah menekan tombol selesai.
            </div>
        </div>

        @if($pengumpulan)
            <div class="section-box" style="border-color: #10B981; background: #F0FDF4;">
                <h3 style="color: #065F46;">✅ Kamu sudah mengerjakan</h3>
                <p>Nilai kamu: <strong>{{ $pengumpulan->nilai }}</strong></p>
            </div>
        @else
            <a href="{{ route('siswa.tugas.kerjakan', $tugas) }}" class="btn-submit">
                Mulai Kerjakan Sekarang! <i class="fas fa-play"></i>
            </a>
        @endif

    @else
        {{-- Logika Tampilan Upload --}}
        <div class="section-box">
            <div class="section-title">📌 Instruksi</div>
            <div class="instruksi-content">{!! nl2br(e($tugas->deskripsi)) !!}</div>
        </div>

        @if($tugas->file)
            <div class="section-box">
                <div class="section-title">📎 Lampiran</div>
                <a href="{{ asset('storage/'.$tugas->file) }}" target="_blank" style="text-decoration: none;">
                    <div style="display: flex; align-items: center; justify-content: space-between; background: #F1F5F9; padding: 15px; border-radius: 15px;">
                        <span style="font-weight: 800; color: #1e293b;">📄 Download Materi</span>
                        <i class="fas fa-download"></i>
                    </div>
                </a>
            </div>
        @endif

        @if($pengumpulan)
            <div class="section-box" style="border-color: #3B82F6;">
                <div class="section-title" style="color: #3B82F6;">✅ Sudah Dikumpulkan</div>
                <p>Dikirim pada: {{ $pengumpulan->dikumpulkan_at->isoFormat('D MMMM Y, HH:mm') }}</p>
            </div>
        @else
            <form action="{{ route('siswa.tugas.kumpulkan', $tugas) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="section-box">
                    <div class="section-title">📤 Upload Jawaban</div>
                    <input type="file" name="file" required style="width: 100%; padding: 10px; border: 2px dashed #CBD5E1; border-radius: 10px;">
                </div>
                <button type="submit" class="btn-submit">Kirim Jawaban <i class="fas fa-paper-plane"></i></button>
            </form>
        @endif
    @endif
</div>
@endsection