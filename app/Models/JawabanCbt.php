<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class JawabanCbt extends Model
{
    protected $table = 'jawaban_cbt';
 
    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'pertanyaan_id',
        'jawaban',
        'is_benar',
    ];
 
    protected $casts = [
        'is_benar' => 'boolean',
    ];
 
    public function tugas()      { return $this->belongsTo(Tugas::class); }
    public function siswa()      { return $this->belongsTo(Siswa::class); }
    public function pertanyaan() { return $this->belongsTo(Pertanyaan::class); }
}