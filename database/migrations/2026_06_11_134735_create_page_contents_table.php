<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // Contoh: 'ppdb-alur', 'visi-misi', 'layanan-surat'
            $table->string('title')->nullable(); // Judul untuk referensi admin
            $table->longText('content'); // Konten utama (bisa HTML/Editor)
            $table->string('image')->nullable(); // Path gambar jika ada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};