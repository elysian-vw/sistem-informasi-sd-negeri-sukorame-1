@extends('layouts.app')
@section('title', 'Kelola Konten Halaman')

@push('styles')
<style>
    .btn { padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; cursor: pointer; border: none; }
    .btn-primary { background: #2563eb; color: white; }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .btn-edit { background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; }
    .btn-edit:hover { background: #e5e7eb; color: #111827; }
    .badge-slug { background: #f3f4f6; color: #ef4444; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 12px; border: 1px solid #e5e7eb; }
    .alert-success { background: #dcfce7; color: #15803d; padding: 14px 20px; border-radius: 8px; margin-bottom: 24px; font-size: 13.5px; display: flex; align-items: center; gap: 10px; border: 1px solid #bbf7d0; font-weight: 500; }
    
    /* Styling khusus Dropdown */
    .selector-card { background: #eff6ff; border: 1px solid #bfdbfe; padding: 24px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(59,130,246,0.05); }
    .select-group { display: flex; gap: 12px; margin-top: 16px; align-items: center; }
    .select-menu { flex: 1; padding: 12px 16px; border-radius: 8px; border: 2px solid #dbeafe; font-size: 14px; font-family: inherit; color: #1e3a8a; background: white; outline: none; transition: border-color 0.2s; cursor: pointer; }
    .select-menu:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .select-menu optgroup { font-weight: 700; color: #111827; background: #f9fafb; padding: 8px 0; }
    .select-menu option { font-weight: 500; color: #374151; padding: 8px; background: white; }
    
    @media (max-width: 768px) {
        .select-group { flex-direction: column; align-items: stretch; }
    }
</style>
@endpush

@section('content')
<div class="page-header" style="margin-bottom: 24px;">
    <h1 class="page-title">Kelola Konten Website</h1>
    <p class="page-subtitle">Pilih bagian website yang ingin diubah melalui menu dropdown di bawah.</p>
</div>

@if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle" style="font-size: 16px;"></i> {{ session('success') }}
    </div>
@endif

{{-- ── BOX DROPDOWN SELEKTOR UTAMA ── --}}
<div class="selector-card">
    <h3 style="font-size: 16px; font-weight: 800; color: #1e3a8a; margin-top: 0; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
        <i class=></i> Pilih Menu yang Akan Diedit
    </h3>
    <p style="font-size: 13.5px; color: #3b82f6; margin-bottom: 0;">Pilih kategori halaman dari daftar, lalu klik tombol Buka Editor.</p>
    
    <div class="select-group">
        <select id="menuDropdown" class="select-menu">
            <option value="">-- Silakan Pilih Bagian Website --</option>
            
            <optgroup label="HALAMAN BERANDA">
                <option value="home-hero-title">Beranda - Teks Judul Banner Utama (Slide 1)</option>
                <option value="home-hero-subtitle">Beranda - Teks Sub-Judul Banner (Slide 1)</option>
                <option value="home-slide2-judul">Beranda - Judul Banner E-Learning (Slide 2)</option>
                <option value="home-slide2-konten">Beranda - Teks Deskripsi E-Learning (Slide 2)</option>
                <option value="home-slide3-label">Beranda - Label Tahun PPDB (Slide 3, contoh: PPDB 2025/2026)</option>
                <option value="home-slide3-konten">Beranda - Teks Deskripsi PPDB (Slide 3)</option>
                <option value="home-sambutan-nama">Beranda - Nama Kepala Sekolah</option>
                <option value="home-sambutan-konten">Beranda - Isi Sambutan Kepala Sekolah</option>
            </optgroup>
            
            <optgroup label="MENU PROFIL">
                <option value="profil-visi-misi">Profil - Visi Sekolah</option>
                <option value="profil-misi">Profil - Misi Sekolah</option>
                <option value="profil-sejarah">Profil - Sejarah Sekolah</option>
                <option value="profil-struktur">Profil - Struktur Organisasi</option>
                <option value="profil-komite">Profil - Komite Sekolah</option>
                <option value="profil-prestasi">Profil - Prestasi Sekolah</option>
            </optgroup>
            
            <optgroup label="MENU AKADEMIK">
                <option value="akademik-kurikulum">Akademik - Informasi Kurikulum</option>
                <option value="akademik-kalender">Akademik - Kalender Akademik</option>
                <option value="akademik-literasi">Akademik - Pojok Literasi</option>
                <option value="akademik-karakter">Akademik - Pendidikan Karakter</option>
            </optgroup>
            
            <optgroup label="MENU PPDB">
                <option value="ppdb-info">PPDB - Informasi Umum PPDB</option>
                <option value="ppdb-alur">PPDB - Alur Pendaftaran</option>
                <option value="ppdb-syarat">PPDB - Syarat & Ketentuan</option>
                <option value="ppdb-jadwal">PPDB - Jadwal Pendaftaran</option>
            </optgroup>
            
            <optgroup label="MENU LAYANAN">
                <option value="layanan-surat">Layanan - Informasi Surat Menyurat</option>
                <option value="layanan-mutasi">Layanan - Informasi Mutasi Siswa</option>
                <option value="layanan-izin">Layanan - Izin Penelitian / PKL</option>
                <option value="layanan-nisn">Layanan - Cek / Cetak NISN</option>
                <option value="layanan-pip">Layanan - Program Indonesia Pintar (PIP)</option>
                <option value="layanan-unduhan">Layanan - Unduhan Dokumen</option>
                <option value="layanan-alumni">Layanan - Penjaringan Alumni</option>
            </optgroup>
        </select>
        
        <button onclick="bukaEditor()" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px;">
            <i class="fas fa-edit"></i> Buka Editor Konten
        </button>
    </div>
</div>

{{-- ── TABEL RIWAYAT HALAMAN YANG SUDAH DIEDIT ── --}}
<div class="content-card" style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="padding: 16px 20px; border-bottom: 1px solid #f3f4f6; background: #f9fafb;">
        <h3 style="font-size: 14px; font-weight: 700; color: #374151; margin: 0;">Daftar Halaman yang Pernah Diedit</h3>
    </div>
    <div class="table-wrapper">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th>Nama / Judul Internal Sistem</th>
                    <th>URL Kunci (Slug)</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $i => $p)
                    <tr>
                        <td style="text-align: center; color: #6b7280; font-size: 13.5px;">{{ $i + 1 }}</td>
                        <td style="font-weight: 600; color: #111827; font-size: 13.5px;">{{ $p->title }}</td>
                        <td>
                            <span class="badge-slug"><i class="fas fa-link" style="color: #9ca3af; margin-right: 4px; font-size: 10px;"></i>{{ $p->slug }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.content.edit', $p->slug) }}" class="btn btn-sm btn-outline-primary border-0" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty-state" style="text-align: center; padding: 40px; color: #9ca3af;">
                            <div style="width: 60px; height: 60px; background: #f3f4f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
                                <i class="fas fa-folder-open" style="font-size: 24px; color: #d1d5db;"></i>
                            </div>
                            Belum ada halaman yang diedit.<br>
                            Silakan pilih menu dari dropdown di atas untuk memulai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk membuka halaman editor sesuai slug yang dipilih
    function bukaEditor() {
        const slug = document.getElementById('menuDropdown').value;
        
        if (!slug) {
            alert('Hei Ndoro! Silakan pilih bagian website dari dropdown terlebih dahulu.');
            document.getElementById('menuDropdown').focus();
            return;
        }
        
        // Arahkan otomatis ke halaman Edit di Controller
        window.location.href = "{{ url('admin/content') }}/" + slug + "/edit";
    }
</script>
@endpush