@extends('layouts.siswa')
@section('title', $tugas->judul)

@section('content')
<style>
    /* Global Styles */
    .detail-container { max-width: 800px; margin: 0 auto; padding: 20px; font-family: 'Nunito', sans-serif; color: #1e293b; }
    .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    
    .btn-back { 
        background: #fff; border: 2px solid #E5E7EB; padding: 10px 60px; border-radius: 15px; 
        font-weight: 800; color: #4b5563; text-decoration: none; display: flex; align-items: center; gap: 8px; 
        transition: 0.2s; box-shadow: 0 4px 0 #E5E7EB;
    }
    .btn-back:hover { transform: translateY(-2px); box-shadow: 0 6px 0 #E5E7EB; background: #F9FAFB; }
    .btn-back:active { transform: translateY(2px); box-shadow: 0 0 0 #E5E7EB; }

    /* Layout Tugas Upload */
    .hero-card { 
        background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); border-radius: 30px; 
        padding: 35px; position: relative; overflow: hidden; margin-bottom: 25px; border: 2px solid #C7D2FE; 
    }
    .type-tag { background: #fff; color: #6366F1; padding: 6px 18px; border-radius: 99px; font-weight: 800; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .hero-title { font-size: 30px; font-weight: 900; color: #1e1b4b; margin: 20px 0 15px; }
    .info-pills { display: flex; flex-wrap: wrap; gap: 12px; }
    .pill { background: rgba(255,255,255,0.7); padding: 8px 18px; border-radius: 15px; font-size: 14px; font-weight: 700; color: #475569; border: 1px solid #fff; }

    .deadline-alert { background: #FFFBEB; border: 2px solid #FDE68A; border-radius: 25px; padding: 20px 25px; display: flex; align-items: center; gap: 18px; margin-bottom: 25px; }
    .deadline-text b { color: #92400E; display: block; font-size: 16px; margin-bottom: 2px; }
    .deadline-text span { color: #B45309; font-size: 14px; font-weight: 600; }

    .section-box { background: #fff; border: 2px solid #E5E7EB; border-radius: 25px; padding: 25px; margin-bottom: 25px; }
    .section-title { font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; letter-spacing: 0.5px; }
    
    .instruksi-content { background: #F8FAFC; padding: 20px; border-radius: 18px; border: 1px dashed #cbd5e1; line-height: 1.7; color: #334155; font-weight: 600; font-size: 15px; }
    
    /* Upload Area Style */
    .upload-container {
        border: 3px dashed #C7D2FE; border-radius: 25px; background-color: #F8FAFC;
        padding: 45px 20px; text-align: center; cursor: pointer; position: relative; transition: all 0.3s ease;
    }
    .upload-container:hover { background-color: #EFF6FF; border-color: #3B82F6; transform: scale(1.01); }
    .hidden-input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; z-index: 2; }
    .upload-icon { font-size: 55px; margin-bottom: 15px; display: block; }
    .upload-text { font-size: 18px; font-weight: 800; color: #1e293b; display: block; }
    .upload-hint { font-size: 13px; color: #64748b; margin-top: 8px; display: block; }

    /* CBT Style */
    .timer-box { background: #FEF3C7; color: #92400E; padding: 10px 20px; border-radius: 15px; font-weight: 800; font-size: 20px; border: 2px solid #FDE68A; box-shadow: 0 4px 0 #FDE68A; }
    .progress-wrapper { background: #E2E8F0; height: 12px; border-radius: 99px; margin: 15px 0; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #6366F1, #818CF8); border-radius: 99px; transition: width 0.5s ease; }
    
    .option-btn { 
        display: flex; align-items: center; gap: 15px; background: #fff; border: 2px solid #E5E7EB; 
        padding: 20px; border-radius: 22px; margin-bottom: 15px; cursor: pointer; transition: 0.2s; 
        width: 100%; text-align: left; box-shadow: 0 4px 0 #E5E7EB;
    }
    .option-btn:hover { border-color: #6366F1; transform: translateY(-3px); box-shadow: 0 7px 0 #E5E7EB; }
    .option-btn:active { transform: translateY(2px); box-shadow: 0 0 0 #E5E7EB; }
    .option-label { width: 45px; height: 45px; background: #F1F5F9; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #475569; font-size: 18px; }

    .btn-submit {
        background: #6366F1; color: #fff; border: none; padding: 18px; border-radius: 20px;
        font-weight: 800; font-size: 16px; cursor: pointer; width: 100%; transition: 0.3s;
        box-shadow: 0 6px 0 #4338CA; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-submit:hover { background: #4F46E5; transform: translateY(-2px); box-shadow: 0 8px 0 #4338CA; }
    .btn-submit:active { transform: translateY(4px); box-shadow: 0 0 0 #4338CA; }

    .btn-secondary { background: #F1F5F9; color: #64748B; box-shadow: 0 6px 0 #CBD5E1; }
</style>

<div class="detail-container">

    @php
        $mapel = $tugas->mataPelajaran->nama ?? 'Umum';
        $isCbt = ($tugas->tipe === 'cbt');
    @endphp

    {{-- HEADER NAVIGATION --}}
    <div class="header-nav">
        <a href="{{ route('siswa.tugas.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <div class="pill" style="background: #F5F3FF; color: #7C3AED; border-color: #DDD6FE;">
                @if(str_contains(strtolower($mapel), 'matematika')) 🔢 
                @elseif(str_contains(strtolower($mapel), 'ipa')) 🌱 
                @else 📖 @endif
                {{ $mapel }}
            </div>
            @if($isCbt)
                <div class="timer-box">⏱️ 13:02</div>
            @endif
        </div>
    </div>

    @if($isCbt)
        {{-- TAMPILAN CBT (SOAL) --}}
        <div class="section-box" style="border: none; background: transparent; padding: 0;">
            <div style="display: flex; justify-content: space-between; font-weight: 800; color: #64748B; margin-bottom: 5px;">
                <span>Soal 1 dari 5</span>
                <span>20%</span>
            </div>
            <div class="progress-wrapper">
                <div class="progress-fill" style="width: 20%;"></div>
            </div>
        </div>

        <div class="section-box">
            <div style="color: #6366F1; font-weight: 900; font-size: 14px; margin-bottom: 10px;">PERTANYAAN 1</div>
            <h2 style="font-size: 22px; font-weight: 800; line-height: 1.5; color: #1e293b;">
                Ibu membeli 24 buah apel. Kemudian Ibu memberikan 9 apel kepada tetangga. Berapa buah apel yang tersisa?
            </h2>
            
            <div style="margin-top: 30px;">
                @foreach(['A' => '13', 'B' => '14', 'C' => '15', 'D' => '16'] as $key => $val)
                <button class="option-btn">
                    <div class="option-label">{{ $key }}</div>
                    <span style="font-weight: 700; font-size: 17px; color: #334155;">{{ $val }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
            <button class="btn-submit btn-secondary" style="box-shadow: 0 6px 0 #CBD5E1;" disabled>👈 Sebelumnya</button>
            <button class="btn-submit">Selanjutnya 👉</button>
        </div>

    @else
        {{-- TAMPILAN UPLOAD TUGAS --}}
        <div class="hero-card">
            <span class="type-tag">📑 Tugas Upload</span>
            <h1 class="hero-title">{{ $tugas->judul }}</h1>
            <div class="info-pills">
                <div class="pill">🧑‍🏫 {{ $tugas->guru->user->name ?? 'Guru' }}</div>
                <div class="pill">📅 {{ $tugas->created_at->isoFormat('D MMMM Y') }}</div>
            </div>
        </div>

        @if($tugas->deadline)
        <div class="deadline-alert">
            <div style="font-size: 35px;">⏰</div>
            <div class="deadline-text">
                <b>Batas Waktu: {{ $tugas->deadline->isoFormat('dddd, D MMMM Y') }}</b>
                <span>Sisa waktu: {{ now()->diffForHumans($tugas->deadline, ['parts' => 1]) }} lagi!</span>
            </div>
        </div>
        @endif

        <div class="section-box">
            <div class="section-title">📌 Instruksi Tugas</div>
            <div class="instruksi-content">
                {!! nl2br(e($tugas->deskripsi)) !!}
            </div>
        </div>

        @if($tugas->file)
        <div class="section-box">
            <div class="section-title">📎 Lampiran dari Guru</div>
            <a href="{{ asset('storage/'.$tugas->file) }}" target="_blank" style="text-decoration: none;">
                <div style="display: flex; align-items: center; justify-content: space-between; background: #F1F5F9; padding: 15px 20px; border-radius: 15px; border: 2px solid #E2E8F0;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span style="font-size: 24px;">📄</span>
                        <div>
                            <div style="font-weight: 800; color: #1e293b;">Lihat Materi Tugas</div>
                            <div style="font-size: 12px; color: #64748b;">Klik untuk membuka file</div>
                        </div>
                    </div>
                    <span style="color: #6366F1;"><i class="fas fa-external-link-alt"></i></span>
                </div>
            </a>
        </div>
        @endif

        {{-- FORM PENGUMPULAN --}}
        <form action="{{ route('siswa.tugas.kumpulkan', $tugas) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="section-box">
                <div class="section-title">📤 Upload Jawabanmu</div>
                
                <div class="upload-container">
                    <input type="file" name="file" id="fileInput" class="hidden-input" required onchange="handleFileSelect()">
                    <span class="upload-icon">📸</span>
                    <span class="upload-text" id="fileStatus">Klik di sini untuk pilih foto atau file</span>
                    <span class="upload-hint">Format: JPG, PNG, PDF (Maks 10MB)</span>
                </div>
            </div>

            <div class="section-box">
                <div class="section-title">💬 Catatan Tambahan (Opsional)</div>
                <textarea name="catatan" rows="3" 
                    style="width: 100%; border: 2px solid #E5E7EB; border-radius: 15px; padding: 15px; font-family: inherit; font-weight: 600; resize: vertical;" 
                    placeholder="Tulis pesan untuk guru di sini..."></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Kumpulkan Tugasku!
            </button>
        </form>
    @endif

</div>

<script>
    function handleFileSelect() {
        const input = document.getElementById('fileInput');
        const status = document.getElementById('fileStatus');
        if (input.files.length > 0) {
            status.innerHTML = "✅ File terpilih: <br><span style='color:#3B82F6'>" + input.files[0].name + "</span>";
        }
    }
</script>
@endsection