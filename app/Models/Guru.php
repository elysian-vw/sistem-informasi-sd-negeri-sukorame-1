<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nip',
        'mata_pelajaran',
        'jabatan',
        'foto',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function isMapelMulok(): bool
    {
        return MataPelajaran::where('guru_id', $this->id)
            ->where('is_mulok', true)
            ->exists();
    }
}