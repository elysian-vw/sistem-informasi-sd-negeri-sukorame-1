<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'judul',
        'deskripsi',
        'file',
        'mata_pelajaran_id',
        'kelas_id',
        'guru_id',
        'deadline',
        'status',
        'tipe',          // ← tambahan: 'upload' | 'cbt'
    ];

    protected $casts = [
        'deadline'          => 'datetime',
        'guru_id'           => 'integer', // ← tambah
        'kelas_id'          => 'integer', // ← tambah
        'mata_pelajaran_id' => 'integer', // ← tambah
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    public function pengumpulanSiswa($siswaId)
    {
        return $this->pengumpulan()->where('siswa_id', $siswaId)->first();
    }

    public function isTerlambat(): bool
    {
        return $this->deadline && now()->gt($this->deadline);
    }

    public function pertanyaans()
    {
        return $this->hasMany(Pertanyaan::class);
    }

    // Helper: apakah ini tugas CBT?
    public function isCbt(): bool
    {
        return $this->tipe === 'cbt';
    }
}