<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    protected $table = 'pertanyaans';

    protected $fillable = [
        'tugas_id',
        'soal',
        'gambar_soal',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}