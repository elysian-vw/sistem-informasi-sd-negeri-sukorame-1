<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';

    protected $fillable = [
        'siswa_id',
        'nama_lomba',
        'penyelenggara',
        'tingkat',
        'juara',
        'tanggal',
        'bidang',
        'foto',
        'keterangan',
        'is_published',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
