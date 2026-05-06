<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    // Kasih tahu Laravel kalau nama tabelnya ini, bukan 'jadwal_pelajarans'
    protected $table = 'jadwal_pelajaran';

    protected $fillable = [
        'kelas_id', 'guru_id', 'mata_pelajaran_id', 'hari', 'jam_ke', 
        'waktu_mulai', 'waktu_selesai', 'ruangan', 'semester', 
        'tahun_ajaran', 'is_aktif'
    ];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id'); }
}