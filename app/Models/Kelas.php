<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'rombel',
        'wali_kelas_id',
        'kapasitas',
        'ruang_kelas',
        'status',
    ];

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function mataPelajaran()
    {
        return $this->hasMany(MataPelajaran::class, 'kelas_id');
    }
}