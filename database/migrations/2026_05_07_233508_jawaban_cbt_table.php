<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini menyimpan jawaban siswa per soal dalam CBT.
     * Dipakai untuk rekap distribusi jawaban di halaman penilaian guru.
     *
     * Jalankan: php artisan migrate
     */
    public function up(): void
    {
        Schema::create('jawaban_cbt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')
                  ->constrained('tugas')
                  ->onDelete('cascade');
            $table->foreignId('siswa_id')
                  ->constrained('siswa')
                  ->onDelete('cascade');
            $table->foreignId('pertanyaan_id')
                  ->constrained('pertanyaans')
                  ->onDelete('cascade');
            $table->char('jawaban', 1);        // A / B / C / D
            $table->boolean('is_benar')->default(false);
            $table->timestamps();

            // Satu siswa hanya boleh punya 1 jawaban per soal per tugas
            $table->unique(['tugas_id', 'siswa_id', 'pertanyaan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_cbt');
    }
};