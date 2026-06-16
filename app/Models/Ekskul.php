<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;

    protected $table = 'ekskul';

    protected $fillable = [
        'nama',
        'deskripsi',
        'hari',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'pembina_id',
        'kuota',
        'foto',
        'is_aktif',
    ];

    public function pembina()
    {
        return $this->belongsTo(Guru::class, 'pembina_id');
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'ekskul_siswa')
                    ->withPivot('tahun_ajaran', 'status')
                    ->withTimestamps();
    }
}
