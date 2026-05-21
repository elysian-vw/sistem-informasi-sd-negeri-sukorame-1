<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode',
        'nama',
        'is_mulok',
        'jenis',
        'tingkat',
        'kkm',
        'guru_id',
        'kelas_id',
        'status',
    ];

    protected $casts = [
        'is_mulok' => 'boolean',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}