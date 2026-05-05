<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            // Kita cek satu per satu apakah kolom sudah ada di tabel 'sekolah'
            // Jika belum ada, baru kita tambahkan.
            
            if (!Schema::hasColumn('sekolah', 'npsn')) {
                $table->string('npsn', 20)->nullable()->after('id');
            }

            if (!Schema::hasColumn('sekolah', 'nama_sekolah')) {
                $table->string('nama_sekolah')->nullable()->after('npsn');
            }

            if (!Schema::hasColumn('sekolah', 'alamat')) {
                $table->text('alamat')->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'kelurahan')) {
                $table->string('kelurahan', 100)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'kecamatan')) {
                $table->string('kecamatan', 100)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'kota')) {
                $table->string('kota', 100)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'provinsi')) {
                $table->string('provinsi', 100)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'kode_pos')) {
                $table->string('kode_pos', 10)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'status_sekolah')) {
                $table->enum('status_sekolah', ['Negeri', 'Swasta'])->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'jenjang')) {
                $table->enum('jenjang', ['SD', 'SMP', 'SMA', 'SMK'])->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'tahun_berdiri')) {
                $table->year('tahun_berdiri')->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'telepon')) {
                $table->string('telepon', 20)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'email')) {
                $table->string('email', 100)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'website')) {
                $table->string('website')->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'nama_kepala_sekolah')) {
                $table->string('nama_kepala_sekolah')->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'nip_kepala_sekolah')) {
                $table->string('nip_kepala_sekolah', 30)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'logo')) {
                $table->string('logo')->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'nama_singkat')) {
                $table->string('nama_singkat', 50)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'slogan')) {
                $table->string('slogan')->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'akreditasi')) {
                $table->enum('akreditasi', ['A', 'B', 'C', 'Belum Terakreditasi'])->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'tahun_akreditasi')) {
                $table->year('tahun_akreditasi')->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'nomor_sk_akreditasi')) {
                $table->string('nomor_sk_akreditasi', 100)->nullable();
            }

            if (!Schema::hasColumn('sekolah', 'nilai_akreditasi')) {
                $table->decimal('nilai_akreditasi', 5, 2)->nullable();
            }
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $columns = [
                'npsn', 'nama_sekolah', 'alamat', 'kelurahan', 'kecamatan',
                'kota', 'provinsi', 'kode_pos', 'status_sekolah', 'jenjang',
                'tahun_berdiri', 'telepon', 'email', 'website',
                'nama_kepala_sekolah', 'nip_kepala_sekolah', 'logo',
                'nama_singkat', 'slogan', 'akreditasi', 'tahun_akreditasi',
                'nomor_sk_akreditasi', 'nilai_akreditasi',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('sekolah', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};