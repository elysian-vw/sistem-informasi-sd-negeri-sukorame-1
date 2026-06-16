<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;

// ─── ADMIN ───────────────────────────────────────────────────────────────────
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboard,
    SiswaController,
    GuruController,
    KelasController,
    MataPelajaranController,
    PengumumanController,
    SekolahController,
    JadwalController,
    PageContentController,
    GaleriController as AdminGaleri,
    SaranaController as AdminSarana,
    KomiteController as AdminKomite,
    PrestasiController as AdminPrestasi,
    BeritaController as AdminBerita,
    KegiatanController as AdminKegiatan,
    EkskulController as AdminEkskul,
    KeunggulanController as AdminKeunggulan,
};

// ─── GURU ────────────────────────────────────────────────────────────────────
use App\Http\Controllers\Guru\{
    DashboardController as GuruDashboard,
    AbsensiController,
    NilaiController,
    MateriController,
    MbgController,
    TugasController as GuruTugas,
    ForumController as GuruForum
};

// ─── WALI MURID ──────────────────────────────────────────────────────────────
use App\Http\Controllers\Wali\{
    DashboardController as WaliDashboard,
    RaportWaliController,
    KehadiranWaliController,
    TugasWaliController,
    PengumumanWaliController
};

// ─── SISWA ───────────────────────────────────────────────────────────────────
use App\Http\Controllers\Siswa\{
    DashboardController as SiswaDashboard,
    ElearningController,
    RaportSiswaController,
    MateriController as SiswaMateri,
    TugasController as SiswaTugas,
    AbsensiController as SiswaAbsensi
};

// ─── KEPALA SEKOLAH ──────────────────────────────────────────────────────────
use App\Http\Controllers\KepalaSekolah\DashboardController as KepalaSekolahDashboard;

// ─── PUBLIC CONTROLLERS ───────────────────────────────────────────────────────
use App\Http\Controllers\Public\ProfilController;
use App\Http\Controllers\Public\AkademikController;
use App\Http\Controllers\Public\BeritaController;
use App\Http\Controllers\Public\GaleriController;
use App\Http\Controllers\Public\PpdbController;
use App\Http\Controllers\Public\LayananController;

// ═══════════════════════════════════════════════════════════════════════════════
// PUBLIC
// ═══════════════════════════════════════════════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Profil ────────────────────────────────────────────────────────────────────
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/visi-misi',  [ProfilController::class, 'visiMisi'])->name('visi-misi');
    Route::get('/sejarah',    [ProfilController::class, 'sejarah'])->name('sejarah');
    Route::get('/struktur',   [ProfilController::class, 'struktur'])->name('struktur');
    Route::get('/komite',     [ProfilController::class, 'komite'])->name('komite');
    Route::get('/guru',       [ProfilController::class, 'guru'])->name('guru');
    Route::get('/sarana',     [ProfilController::class, 'sarana'])->name('sarana');
    Route::get('/akreditasi', [ProfilController::class, 'akreditasi'])->name('akreditasi');
    Route::get('/prestasi',   [ProfilController::class, 'prestasi'])->name('prestasi');
});

// ── Akademik ──────────────────────────────────────────────────────────────────
Route::prefix('akademik')->name('akademik.')->group(function () {
    Route::get('/kurikulum',       [AkademikController::class, 'kurikulum'])->name('kurikulum');
    Route::get('/kalender',        [AkademikController::class, 'kalender'])->name('kalender');
    Route::get('/karakter',        [AkademikController::class, 'karakter'])->name('karakter');
    Route::get('/ekstrakurikuler', [AkademikController::class, 'ekstrakurikuler'])->name('ekstrakurikuler');
    Route::get('/literasi',        [AkademikController::class, 'literasi'])->name('literasi');
});

// ── Berita ────────────────────────────────────────────────────────────────────
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/',           [BeritaController::class, 'index'])->name('index');
    Route::get('/pengumuman', [BeritaController::class, 'pengumuman'])->name('pengumuman');
    Route::get('/agenda',     [BeritaController::class, 'agenda'])->name('agenda');
    Route::get('/info-dinas', [BeritaController::class, 'infoDinas'])->name('info-dinas');
    Route::get('/{id}',       [BeritaController::class, 'show'])->name('show');
});

// ── Galeri ────────────────────────────────────────────────────────────────────
Route::prefix('galeri')->name('galeri.')->group(function () {
    Route::get('/foto',  [GaleriController::class, 'foto'])->name('foto');
    Route::get('/video', [GaleriController::class, 'video'])->name('video');
});

// ── PPDB ──────────────────────────────────────────────────────────────────────
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/informasi', [PpdbController::class, 'info'])->name('info');
    Route::get('/syarat',    [PpdbController::class, 'syarat'])->name('syarat');
    Route::get('/jadwal',    [PpdbController::class, 'jadwal'])->name('jadwal');
    Route::get('/alur',      [PpdbController::class, 'alur'])->name('alur');
});

// ── Layanan ───────────────────────────────────────────────────────────────────
Route::prefix('layanan')->name('layanan.')->group(function () {
    Route::get('/mutasi',  [LayananController::class, 'mutasi'])->name('mutasi');
    Route::get('/surat',   [LayananController::class, 'surat'])->name('surat');
    Route::get('/izin',    [LayananController::class, 'izin'])->name('izin');
    Route::get('/nisn',    [LayananController::class, 'nisn'])->name('nisn');
    Route::get('/pip',     [LayananController::class, 'pip'])->name('pip');
    Route::get('/unduhan', [LayananController::class, 'unduhan'])->name('unduhan');
    Route::get('/alumni',  [LayananController::class, 'alumni'])->name('alumni');
});

// ═══════════════════════════════════════════════════════════════════════════════
// AUTH
// ═══════════════════════════════════════════════════════════════════════════════

// Load auth routes (register/login/password/email verification)
require __DIR__ . '/auth.php';

Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Generic dashboard route used by tests/scaffolding — redirects authenticated users
Route::middleware('auth')->get('/dashboard', function () {
    // If user has admin role, redirect to admin dashboard; otherwise go to home
    $user = auth()->user();
    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->name('dashboard');

// Profile routes (view, update, delete)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Password Reset ────────────────────────────────────────────────────────────
Route::get('/forgot-password',        [LoginController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password',       [LoginController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',        [LoginController::class, 'resetPassword'])->name('password.store');

// ═══════════════════════════════════════════════════════════════════════════════
// ADMIN
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::get('/dashboard/chart-data', [AdminDashboard::class, 'getChartData'])->name('chart-data');

    // ── KELOLA KONTEN HALAMAN DINAMIS (Pusat Edit Akademik, PPDB, Layanan) ──
    Route::get('/content', [PageContentController::class, 'index'])->name('content.index');
    Route::get('/content/{slug}/edit', [PageContentController::class, 'edit'])->name('content.edit');
    Route::post('/content/{slug}/update', [PageContentController::class, 'update'])->name('content.update');
    Route::get('/content/create', [PageContentController::class, 'create'])->name('content.create');

    Route::post('/content/upload-image', [PageContentController::class, 'uploadImage'])->name('content.upload');
    Route::post('/content/store', [PageContentController::class, 'store'])->name('content.store');

    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('pengumuman', PengumumanController::class);

    Route::get('/guru',           [GuruController::class, 'index'])->name('guru.index');
    Route::post('/guru',          [GuruController::class, 'store'])->name('guru.store');
    Route::put('/guru/{guru}',    [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/guru/{guru}', [GuruController::class, 'destroy'])->name('guru.destroy');
    Route::get('/guru/export',    [GuruController::class, 'export'])->name('guru.export');
    Route::post('/guru/import',   [GuruController::class, 'import'])->name('guru.import');

    Route::resource('kelas', KelasController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/mata-pelajaran',                    [MataPelajaranController::class, 'index'])->name('mata-pelajaran.index');
    Route::post('/mata-pelajaran',                   [MataPelajaranController::class, 'store'])->name('mata-pelajaran.store');
    Route::put('/mata-pelajaran/{mata_pelajaran}',   [MataPelajaranController::class, 'update'])->name('mata-pelajaran.update');
    Route::delete('/mata-pelajaran/{mata_pelajaran}', [MataPelajaranController::class, 'destroy'])->name('mata-pelajaran.destroy');

    Route::resource('pengumuman', PengumumanController::class);
    Route::resource('galeri', AdminGaleri::class);
    Route::resource('sarana', AdminSarana::class);
    Route::resource('komite', AdminKomite::class);
    Route::resource('prestasi', AdminPrestasi::class);
    Route::resource('berita', AdminBerita::class);
    Route::resource('kegiatan', AdminKegiatan::class);
    Route::resource('ekskul', AdminEkskul::class);

     Route::resource('keunggulan', AdminKeunggulan::class)
         ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('/sekolah', [SekolahController::class, 'index'])->name('sekolah');
    Route::put('/sekolah', [SekolahController::class, 'update'])->name('sekolah.update');

    // 1. Letakkan rute spesifik DI ATAS resource
    Route::get('jadwal/export', [JadwalController::class, 'export'])->name('jadwal.export');
    Route::get('jadwal/template', [JadwalController::class, 'template'])->name('jadwal.template');
    Route::post('jadwal/import', [JadwalController::class, 'import'])->name('jadwal.import');

    // 2. Gunakan ->only agar Laravel tidak membuat method yang tidak ada (seperti show)
    Route::resource('jadwal', JadwalController::class)->names([
        'index' => 'jadwal.index',
        'store' => 'jadwal.store',
        'destroy' => 'jadwal.destroy',
    ])->only(['index', 'store', 'destroy']); // Batasi hanya method yang Ndoro punya
});

// ═══════════════════════════════════════════════════════════════════════════════
// GURU
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
    Route::get('/chart-data', [GuruDashboard::class, 'getChartData'])->name('chart-data');

    Route::resource('absensi', AbsensiController::class);

    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/input/{mapel}', [NilaiController::class, 'input'])->name('nilai.input');
    Route::post('/nilai/store/{mapel}', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('/nilai/export/{mapel}', [NilaiController::class, 'export'])->name('nilai.export');
Route::post('/nilai/import/{mapel}', [NilaiController::class, 'import'])->name('nilai.import');

    Route::get('/jadwal', [App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal.index');
    Route::resource('materi', MateriController::class);
    Route::get('/mbg', [MbgController::class, 'index'])->name('mbg');

    Route::resource('tugas', GuruTugas::class)->parameters(['tugas' => 'tugas']);
    Route::get('/tugas/{tugas}/penilaian',     [GuruTugas::class, 'penilaian'])->name('tugas.penilaian');
    Route::post('/tugas/{tugas}/simpan-nilai', [GuruTugas::class, 'simpanNilai'])->name('tugas.simpan-nilai');
    Route::get('/penilaian-tugas', [GuruTugas::class, 'indexPenilaian'])->name('tugas.penilaian.index');

    Route::get('/tugas/{tugas}/soal',                        [GuruTugas::class, 'soal'])->name('tugas.soal');
    Route::post('/tugas/{tugas}/soal',                       [GuruTugas::class, 'storeSoal'])->name('tugas.soal.store');
    Route::put('/tugas/{tugas}/soal/{pertanyaan}',           [GuruTugas::class, 'updateSoal'])->name('tugas.soal.update');
    Route::delete('/tugas/{tugas}/soal/{pertanyaan}',        [GuruTugas::class, 'destroySoal'])->name('tugas.soal.destroy');

    Route::get('/forum',                        [GuruForum::class, 'index'])->name('forum.index');
    Route::post('/forum',                       [GuruForum::class, 'store'])->name('forum.store');
    Route::get('/forum/{forum}',                [GuruForum::class, 'show'])->name('forum.show');
    Route::delete('/forum/{forum}',             [GuruForum::class, 'destroy'])->name('forum.destroy');
    Route::post('/forum/{forum}/komentar',      [GuruForum::class, 'komentar'])->name('forum.komentar');
    Route::delete('/forum/komentar/{komentar}', [GuruForum::class, 'hapusKomentar'])->name('forum.komentar.hapus');
    Route::patch('/forum/{forum}/pin',          [GuruForum::class, 'togglePin'])->name('forum.pin');
});

// ═══════════════════════════════════════════════════════════════════════════════
// WALI MURID
// ═══════════════════════════════════════════════════════════════════════════════

// ═══════════════════════════════════════════════════════════════════════════════
// WALI MURID
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('wali')->name('wali.')->middleware(['auth', 'role:wali_murid'])->group(function () {
    Route::get('/dashboard', [WaliDashboard::class, 'index'])->name('dashboard');

    // ── Raport ──
    Route::get('/raport', [RaportWaliController::class, 'index'])->name('raport.index');
    Route::get('/raport/{raport}', [RaportWaliController::class, 'show'])->name('raport.show');
    // Cukup tulis 'raport.ekspor' saja karena otomatis ditambah 'wali.' dari grup
    Route::get('/raport/{raport}/ekspor', [RaportWaliController::class, 'eksporPdf'])->name('raport.ekspor'); 

    // ── Kehadiran & Tugas ──
    Route::get('/kehadiran', [KehadiranWaliController::class, 'index'])->name('kehadiran');
    Route::get('/tugas', [TugasWaliController::class, 'index'])->name('tugas');

    // ── Pengumuman ──
    Route::get('/pengumuman', [PengumumanWaliController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/{pengumuman}', [PengumumanWaliController::class, 'show'])->name('pengumuman.show');
});

// ═══════════════════════════════════════════════════════════════════════════════
// SISWA
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/tugas',                     [SiswaTugas::class, 'index'])->name('tugas.index');
    Route::get('/tugas/{tugas}',             [SiswaTugas::class, 'show'])->name('tugas.show');
    Route::post('/tugas/{tugas}/kumpulkan',  [SiswaTugas::class, 'kumpulkan'])->name('tugas.kumpulkan');
    Route::get('/tugas/{tugas}/kerjakan',    [SiswaTugas::class, 'kerjakan'])->name('tugas.kerjakan');
    Route::post('/tugas/{tugas}/simpan-cbt', [SiswaTugas::class, 'simpanCBT'])->name('tugas.simpanCBT');
    
    Route::get('/materi',          [SiswaMateri::class, 'index'])->name('materi.index');
    Route::get('/materi/{materi}', [SiswaMateri::class, 'show'])->name('materi.show');

});

// ═══════════════════════════════════════════════════════════════════════════════
// KEPALA SEKOLAH
// ═══════════════════════════════════════════════════════════════════════════════

Route::prefix('kepala-sekolah')->name('kepala_sekolah.')->middleware(['auth', 'role:kepala_sekolah'])->group(function () {
    Route::get('/dashboard', [KepalaSekolahDashboard::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [KepalaSekolahDashboard::class, 'getChartData'])->name('chart-data'); // Tambahan route grafik kepsek
    Route::get('/laporan-kehadiran', [KepalaSekolahDashboard::class, 'laporanKehadiran'])->name('laporan.kehadiran');
    Route::get('/laporan-nilai', [KepalaSekolahDashboard::class, 'laporanNilai'])->name('laporan.nilai');
});