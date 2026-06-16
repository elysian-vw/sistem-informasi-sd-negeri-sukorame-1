<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keunggulan', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 100)->default('fa-star');        // Font Awesome class, contoh: fa-award
            $table->string('title', 200);                           // Judul kartu
            $table->text('description')->nullable();                // Deskripsi singkat
            $table->string('color', 50)->default('blue');           // Nama warna Tailwind: blue, indigo, purple, amber, pink, teal, green, red
            $table->tinyInteger('urutan')->default(0);              // Urutan tampil
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        // Seed data default agar halaman tidak kosong saat pertama dijalankan
        DB::table('keunggulan')->insert([
            ['icon' => 'fa-award',      'title' => 'Sekolah Unggulan',    'description' => 'Terbaik di Kota Kediri',             'color' => 'blue',   'urutan' => 1, 'is_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['icon' => 'fa-user-tie',   'title' => 'Pendidik Berkualitas', 'description' => 'Guru berpengalaman & tersertifikasi', 'color' => 'indigo', 'urutan' => 2, 'is_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['icon' => 'fa-shield-alt', 'title' => 'Berintegritas',        'description' => 'Transparan & akuntabel',              'color' => 'purple', 'urutan' => 3, 'is_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['icon' => 'fa-trophy',     'title' => 'Siswa Berprestasi',    'description' => 'Juara lokal & nasional',              'color' => 'amber',  'urutan' => 4, 'is_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['icon' => 'fa-heart',      'title' => 'Berkarakter',          'description' => 'Berbudi pekerti luhur',               'color' => 'pink',   'urutan' => 5, 'is_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['icon' => 'fa-building',   'title' => 'Fasilitas Lengkap',    'description' => 'Sarana modern & nyaman',              'color' => 'teal',   'urutan' => 6, 'is_aktif' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('keunggulan');
    }
};