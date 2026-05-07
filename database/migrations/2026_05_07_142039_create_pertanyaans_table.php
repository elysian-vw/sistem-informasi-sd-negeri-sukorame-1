<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pertanyaans', function (Blueprint $table) {
            $table->id();
            // Hubungkan ke tabel tugas yang barusan kamu kasih liat
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            
            $table->text('soal'); // Isi pertanyaan
            $table->string('gambar_soal')->nullable(); // Penting buat anak SD (visual!)
            
            // Pilihan jawaban
            $table->string('pilihan_a');
            $table->string('pilihan_b');
            $table->string('pilihan_c');
            $table->string('pilihan_d');
            
            // Kunci jawaban
            $table->char('jawaban_benar', 1); // Isinya: A, B, C, atau D
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaans');
    }
};
