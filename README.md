# SIMAS — Sistem Informasi Manajemen Sekolah
### SD Negeri Sukorame 1 Kediri

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Kotlin](https://img.shields.io/badge/Android-Kotlin-7F52FF?style=flat&logo=kotlin&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

Sistem informasi sekolah berbasis web (Laravel 11) dan mobile (Android/Kotlin) untuk SDN Sukorame 1 Kediri. Dibangun sebagai proyek mata kuliah **PSI** dan **Mobile Programming**, Program Studi D3 Manajemen Informatika — PSDKU Polinema Kampus Kediri.

---

## Daftar Isi

- [Fitur](#fitur)
- [Tech Stack](#tech-stack)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Kontribusi](#kontribusi)
- [Tim Pengembang](#tim-pengembang)

---

## Fitur

### 🌐 Web (Laravel)

---

#### 👥 Publik (Tanpa Login)

Halaman yang dapat diakses oleh siapa saja tanpa perlu akun.

**Profil Sekolah**
- Visi, misi, dan tujuan sekolah
- Sejarah berdirinya SDN Sukorame 1
- Struktur organisasi sekolah
- Profil komite sekolah
- Daftar guru & karyawan beserta jabatan
- Data sarana dan prasarana
- Status akreditasi sekolah
- Prestasi siswa & sekolah

**Akademik**
- Informasi kurikulum yang digunakan
- Kalender akademik tahunan
- Program karakter & pembiasaan
- Kegiatan ekstrakurikuler
- Program literasi sekolah

**Berita & Informasi**
- Artikel berita terbaru sekolah
- Pengumuman resmi
- Agenda & kegiatan mendatang
- Info dinas dari Dinas Pendidikan

**Galeri**
- Foto dokumentasi kegiatan sekolah
- Video kegiatan & profil sekolah

**PPDB (Penerimaan Peserta Didik Baru)**
- Informasi umum PPDB
- Persyaratan pendaftaran
- Jadwal tahapan PPDB
- Alur & prosedur pendaftaran

**Layanan Administrasi**
- Informasi mutasi siswa
- Pengajuan surat keterangan
- Prosedur izin siswa
- Pencarian NISN
- Informasi Program Indonesia Pintar (PIP)
- Unduhan formulir & dokumen
- Informasi alumni

---

#### 🔧 Admin

Panel pengelolaan data utama sekolah, hanya dapat diakses oleh administrator.

**Dashboard**
- Ringkasan statistik: jumlah siswa, guru, kelas aktif, dan pengumuman terbaru
- Grafik interaktif berbasis Chart.js (tren data per bulan/tahun)

**Manajemen Data Master**
- **Siswa** — tambah, edit, hapus data siswa; dilengkapi fitur CRUD lengkap
- **Guru** — kelola data guru termasuk export Excel dan import massal
- **Kelas** — buat dan atur kelas, assign wali kelas
- **Mata Pelajaran** — kelola daftar mapel dan assign guru pengampu

**Jadwal Pelajaran**
- Buat dan atur jadwal pelajaran per kelas
- Import jadwal massal via file CSV
- Export jadwal ke CSV
- Download template CSV untuk kemudahan pengisian

**Pengumuman**
- Buat, edit, dan hapus pengumuman resmi sekolah
- Pengumuman tampil di halaman publik dan dashboard semua role

**Pengaturan Sekolah**
- Edit identitas sekolah (nama, alamat, logo, NPSN, dll.)

---

#### 👨‍🏫 Guru

Panel untuk guru dalam mengelola kegiatan pembelajaran di kelasnya.

**Dashboard**
- Ringkasan aktivitas: jumlah tugas aktif, materi terpublikasi, siswa belum mengumpulkan, dan absensi hari ini

**Materi Pelajaran**
- Upload dan kelola materi pembelajaran (file PDF, DOC, atau teks)
- Atur status materi: aktif atau draft
- Materi otomatis tampil ke siswa sesuai kelas & mata pelajaran guru

**Kelola Tugas**
- Buat tugas dengan dua tipe:
  - **Upload** — siswa mengumpulkan file jawaban (PDF, DOC, ZIP, dll.)
  - **CBT (Computer Based Test)** — siswa mengerjakan soal pilihan ganda langsung di platform
- Atur deadline, status (aktif/draft), dan lampiran pendukung
- Tugas terkunci otomatis ke kelas & mata pelajaran yang diampu guru

**Manajemen Soal CBT**
- Tambah, edit, hapus soal pilihan ganda (A–D) per tugas CBT
- Mendukung gambar ilustrasi per soal
- Tentukan kunci jawaban benar

**Penilaian Tugas**
- Lihat daftar semua tugas aktif untuk dinilai
- Halaman penilaian per tugas menampilkan:
  - Statistik pengumpulan (total siswa, sudah kumpul, belum kumpul, sudah dinilai)
  - Grafik distribusi nilai (Grade A–E)
  - Rekap per soal CBT (persentase siswa menjawab benar per opsi)
- Untuk tugas **Upload**: input nilai 0–100 manual per siswa, dengan grade otomatis
- Untuk tugas **CBT**: nilai dihitung otomatis berdasarkan jawaban benar, guru hanya menambahkan feedback
- Fitur isi nilai seragam (bulk) untuk tugas Upload
- Filter tampilan: semua / sudah kumpul / belum kumpul / belum dinilai
- Export hasil penilaian ke file CSV

**E-Raport**
- Input nilai per mata pelajaran untuk seluruh siswa di kelas
- Nilai tersimpan dan dapat dilihat oleh siswa & wali murid

**Absensi Siswa**
- Catat kehadiran siswa harian (Hadir / Izin / Sakit / Alpa)
- Riwayat absensi tersimpan dan dapat dipantau wali murid

**Forum Diskusi**
- Buat topik diskusi untuk siswa di kelasnya
- Balas dan moderasi komentar
- Fitur pin topik penting
- Hapus topik atau komentar yang tidak relevan

**Program MBG (Makan Bergizi Gratis)**
- Halaman informasi dan pencatatan program MBG sekolah

---

#### 👨‍👩‍👦 Wali Murid

Panel pemantauan perkembangan anak, dapat diakses oleh orang tua/wali siswa.

**Dashboard**
- Ringkasan kondisi terkini anak: kehadiran bulan ini, nilai terakhir, tugas yang belum dikumpulkan

**Raport**
- Lihat nilai rapor anak per mata pelajaran dan per semester
- Ditampilkan dengan rata-rata dan keterangan grade

**Kehadiran**
- Rekap absensi anak: jumlah hadir, izin, sakit, dan alpa
- Riwayat kehadiran per tanggal

**Tugas Anak**
- Lihat daftar tugas yang diberikan guru kepada anak
- Status pengumpulan dan nilai tugas yang sudah dinilai

**Pengumuman**
- Baca pengumuman resmi dari sekolah
- Detail isi pengumuman beserta tanggal terbit

---

#### 🎓 Siswa

Panel e-learning untuk siswa dalam mengakses dan mengerjakan pembelajaran.

**Dashboard**
- Ringkasan: tugas yang belum dikumpulkan, materi terbaru, dan nilai terakhir

**Materi Pelajaran**
- Akses daftar materi yang diunggah guru sesuai kelas & mata pelajaran
- Unduh file materi atau baca langsung di browser

**Tugas**
- Lihat daftar semua tugas aktif beserta deadline dan status pengumpulan
- **Tugas Upload** — unggah file jawaban (PDF, DOC, ZIP, dll.) langsung dari halaman tugas
- **Tugas CBT** — kerjakan soal pilihan ganda langsung di platform, jawaban tersimpan otomatis saat submit
- Lihat nilai & feedback dari guru setelah tugas dinilai

**E-Raport**
- Lihat nilai rapor per semester yang diinput guru
- Tampil lengkap per mata pelajaran beserta grade

**Absensi**
- Rekap kehadiran pribadi per bulan
- Detail status per tanggal (Hadir / Izin / Sakit / Alpa)

---

### 📱 Mobile (Android / Kotlin)

Aplikasi pendamping berbasis Android untuk guru, menggunakan SQLite sebagai penyimpanan lokal.

**Autentikasi**
- Login dengan NIP dan password
- Sesi tersimpan lokal, tidak perlu login ulang setiap buka aplikasi

**Dashboard Guru**
- Tampilan grid 6 menu utama: Materi, Tugas, Rapor, Absensi, Daftar Siswa, Profil
- Menampilkan nama guru dan kelas yang diampu

**Kelola Materi**
- Lihat daftar materi yang sudah dibuat
- Tambah materi baru dengan judul, deskripsi, dan mata pelajaran
- Data tersimpan lokal di SQLite

**Kelola Tugas**
- Lihat daftar tugas per kelas
- Buat tugas baru dengan judul, deskripsi, dan deadline

**Input Nilai Rapor**
- Pilih siswa dari daftar kelas
- Input nilai per mata pelajaran
- Data tersimpan lokal dan dapat disinkronkan

**Daftar Siswa**
- Lihat daftar siswa di kelas yang diampu
- Informasi nama, NIS, dan foto siswa

---

## Tech Stack

| Layer         | Teknologi                                 |
|---------------|-------------------------------------------|
| Backend       | Laravel 12, PHP 8.2+                      |
| Frontend      | Blade, Tailwind CSS, Vite JS, Chart.js    |
| Database      | MySQL 8.0                                 |
| Mobile        | Kotlin, Android SDK, SQLite               |
| Auth          | Laravel Breeze (custom multi-role)        |
| Storage       | Laravel Storage (public disk)             |
| Dev Server    | Laragon (Windows)                         |

---

## Persyaratan Sistem

- PHP       >= 8.2
- Composer  >= 2.x
- Node.js   >= 18.x & NPM
- MySQL     >= 8.0
- Laragon / XAMPP / Valet
- Android Studio (untuk modul mobile)

---


## Kontribusi

1. Fork repository ini
2. Buat branch fitur: `git checkout -b fitur/nama-fitur`
3. Commit: `git commit -m "feat: deskripsi singkat"`
4. Push: `git push origin fitur/nama-fitur`
5. Buat Pull Request ke branch `main`

Konvensi commit: `feat:` / `fix:` / `refactor:` / `docs:` / `style:`

---

## Tim Pengembang

Dikembangkan oleh mahasiswa D3 Manajemen Informatika  
**PSDKU Polinema Kampus Kediri** — Semester 4

| Nama                      | NIM          | Peran                    |
|---------------------------|--------------|--------------------------|
| Ahmad Ubaidillah Tsani    | 243107030095 | Full-stack Web & Android |
| Berliana Damayanti Riyadi | 243107030075 | Dokumentasi              |
| Erisa Desmanaya           | 243107030086 | Dokumentasi              |
| M. Jayraka Ilham Saputra  | 243107030126 | -                        |
| Noor Achmad Razaq R       | 243107030100 | Full-stack Web & DB Dev  |
| Wahyu dwi ramadani        | 243107030124 | -                        |

---

## Lisensi

Proyek ini dibuat untuk keperluan akademik.  
© 2024–2025 Tim SIMAS — PSDKU Polinema Kampus Kediri
