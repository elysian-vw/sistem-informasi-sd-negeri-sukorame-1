<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = [
        'judul', 
        'deskripsi', 
        'file', 
        'link_video', 
        'mata_pelajaran_id', 
        'guru_id', 
        'kelas_id', 
        'tipe', 
        'deadline'
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // app/Models/Materi.php

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
