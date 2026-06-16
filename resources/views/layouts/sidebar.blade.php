<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <i class="fas fa-school"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">{{ data_get($sekolah, 'nama_singkat', data_get($sekolah, 'nama_sekolah', config('app.name'))) }}</span>
            <span class="brand-sub">{{ data_get($sekolah, 'kota', 'Kediri') }}</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        @php $role = auth()->user()->role; @endphp

        {{-- ══════════════════ ADMIN ══════════════════ --}}
        @if($role === 'admin')
            <div class="nav-section-title">Menu Admin</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <div class="nav-section-title">Informasi & Berita</div>
            <a href="{{ route('admin.berita.index') }}" class="nav-item {{ request()->routeIs('admin.berita*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i> <span>Berita & Artikel</span>
            </a>
            <a href="{{ route('admin.pengumuman.index') }}" class="nav-item {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i> <span>Pengumuman</span>
            </a>
            <a href="{{ route('admin.kegiatan.index') }}" class="nav-item {{ request()->routeIs('admin.kegiatan*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> <span>Agenda Kegiatan</span>
            </a>
            <a href="{{ route('admin.galeri.index') }}" class="nav-item {{ request()->routeIs('admin.galeri*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> <span>Galeri</span>
            </a>

            <div class="nav-section-title">Kesiswaan & Akademik</div>
            <a href="{{ route('admin.siswa.index') }}" class="nav-item {{ request()->routeIs('admin.siswa*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate"></i> <span>Kelola Siswa</span>
            </a>
            <a href="{{ route('admin.prestasi.index') }}" class="nav-item {{ request()->routeIs('admin.prestasi*') ? 'active' : '' }}">
                <i class="fas fa-medal"></i> <span>Prestasi Siswa</span>
            </a>
            <a href="{{ route('admin.ekskul.index') }}" class="nav-item {{ request()->routeIs('admin.ekskul*') ? 'active' : '' }}">
                <i class="fas fa-basketball-ball"></i> <span>Ekstrakurikuler</span>
            </a>

            <div class="nav-section-title">Data Sekolah</div>
            <a href="{{ route('admin.sekolah') }}" class="nav-item {{ request()->routeIs('admin.sekolah*') ? 'active' : '' }}">
                <i class="fas fa-building-columns"></i> <span>Profil Sekolah</span>
            </a>
            <a href="{{ route('admin.guru.index') }}" class="nav-item {{ request()->routeIs('admin.guru*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i> <span>Guru & Pegawai</span>
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="nav-item {{ request()->routeIs('admin.kelas*') ? 'active' : '' }}">
                <i class="fas fa-door-open"></i> <span>Kelas</span>
            </a>
            <a href="{{ route('admin.mata-pelajaran.index') }}" class="nav-item {{ request()->routeIs('admin.mata-pelajaran*') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i> <span>Mata Pelajaran</span>
            </a>
            <a href="{{ route('admin.jadwal.index') }}" class="nav-item {{ request()->routeIs('admin.jadwal*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> <span>Jadwal Pelajaran</span>
            </a>
            <a href="{{ route('admin.sarana.index') }}" class="nav-item {{ request()->routeIs('admin.sarana*') ? 'active' : '' }}">
                <i class="fas fa-cubes"></i> <span>Sarana Prasarana</span>
            </a>
            <a href="{{ route('admin.komite.index') }}" class="nav-item {{ request()->routeIs('admin.komite*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> <span>Komite</span>
            </a>
            <a href="{{ route('admin.content.index') }}" class="nav-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> <span>Konten Web</span>
            </a>

        {{-- ══════════════════ GURU ══════════════════ --}}
        @elseif($role === 'guru')
            <div class="nav-section-title">Menu Guru</div>
            <a href="{{ route('guru.dashboard') }}" class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('guru.materi.index') }}" class="nav-item {{ request()->routeIs('guru.materi*') ? 'active' : '' }}">
                <i class="fas fa-laptop-code"></i> <span>Kelola Materi Pelajaran</span>
            </a>
            <a href="{{ route('guru.tugas.index') }}" 
            class="nav-item {{ request()->routeIs('guru.tugas.index', 'guru.tugas.create', 'guru.tugas.edit', 'guru.tugas.show') ? 'active' : '' }}">
                <i class="fas fa-file-pen"></i> <span>Kelola Tugas</span>
            </a>

            <a href="{{ route('guru.tugas.penilaian.index') }}" 
            class="nav-item {{ request()->routeIs('guru.tugas.penilaian*', 'guru.tugas.simpan-nilai') ? 'active' : '' }}">
                <i class="fas fa-marker"></i> <span>Penilaian Tugas</span>
            </a>
            <a href="{{ route('guru.nilai.index') }}" class="nav-item {{ request()->routeIs('guru.nilai*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> <span>E-Raport</span>
            </a>
            <a href="{{ route('guru.absensi.index') }}" class="nav-item {{ request()->routeIs('guru.absensi*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-check"></i> <span>Absensi Siswa</span>
            </a>
            <a href="{{ route('guru.jadwal.index') }}" class="nav-item {{ request()->routeIs('guru.jadwal*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i> <span>Jadwal Pelajaran</span>
            </a>
            <a href="{{ route('guru.mbg') }}" class="nav-item {{ request()->routeIs('guru.mbg') ? 'active' : '' }}">
                <i class="fas fa-utensils"></i> <span>Program MBG</span>
            </a>
            <a href="{{ route('guru.forum.index') }}" class="nav-item {{ request()->routeIs('guru.forum*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> <span>Forum Diskusi</span>
            </a>

        {{-- ══════════════════ WALI MURID ══════════════════ --}}
        @elseif($role === 'wali_murid')
            <div class="nav-section-title">Menu Orang Tua</div>
            <a href="{{ route('wali.dashboard') }}" class="nav-item {{ request()->routeIs('wali.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('wali.kehadiran') }}" class="nav-item {{ request()->routeIs('wali.kehadiran') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> <span>Absensi Anak</span>
            </a>
            <a href="{{ route('wali.tugas') }}" class="nav-item {{ request()->routeIs('wali.tugas') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i> <span>Tugas Anak</span>
            </a>
            <a href="{{ route('wali.pengumuman.index') }}" class="nav-item {{ request()->routeIs('wali.pengumuman*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i> <span>Pengumuman</span>
            </a>
            <a href="{{ route('wali.raport.index') }}" class="nav-item {{ request()->routeIs('wali.raport*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> <span>E-Raport</span>
            </a>

        {{-- ══════════════════ SISWA ══════════════════ --}}
        @elseif($role === 'siswa')
            <div class="nav-section-title">Menu Siswa</div>
            <a href="{{ route('siswa.materi.index') }}" class="nav-item {{ request()->routeIs('siswa.materi*') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i> <span>Materi</span>
            </a>
            <a href="{{ route('siswa.tugas.index') }}" class="nav-item {{ request()->routeIs('siswa.tugas*') ? 'active' : '' }}">
                <i class="fas fa-file-pen"></i> <span>Tugas</span>
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>