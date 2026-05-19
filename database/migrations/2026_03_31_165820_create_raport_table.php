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
        Schema::create('raport', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('semester');        // '1' atau '2'
            $table->string('tahun_ajaran');    // '2024/2025'
            $table->text('catatan_wali_kelas')->nullable();
            $table->enum('status', ['draft', 'terbit'])->default('draft');
            $table->timestamps();

            // Kunci unik agar satu siswa tidak punya rangkap raport di semester & tahun ajaran yang sama
            $table->unique(['siswa_id', 'semester', 'tahun_ajaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raport');
    }
};