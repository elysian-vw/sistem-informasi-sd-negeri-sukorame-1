<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raport extends Model
{
    protected $table = 'raport';

    protected $fillable = [
        'siswa_id',
        'semester',
        'tahun_ajaran',
        'catatan_wali_kelas',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id', 'siswa_id')
            ->where('semester', $this->semester)
            ->where('tahun_ajaran', $this->tahun_ajaran);
    }
}