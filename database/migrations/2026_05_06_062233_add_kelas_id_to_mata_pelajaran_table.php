<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            // Tambahin kolom kelas_id biar nyambung ke tabel kelas
            $table->foreignId('kelas_id')
                ->after('guru_id') 
                ->nullable() 
                ->constrained('kelas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};
