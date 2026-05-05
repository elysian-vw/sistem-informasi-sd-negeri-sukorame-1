<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');  // contoh: "1A", "2B"
            $table->integer('tingkat');    // 1-6
            $table->string('rombel')->nullable();
            $table->foreignId('wali_kelas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('kapasitas')->default(30);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};