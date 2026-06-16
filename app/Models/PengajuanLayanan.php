<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanLayanan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_layanan';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'jenis',
        'keperluan',
        'keterangan',
        'lampiran',
        'no_pengajuan',
        'status',
        'catatan_admin',
        'file_hasil',
        'diproses_oleh',
        'selesai_at',
    ];

    protected $casts = [
        'selesai_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}
