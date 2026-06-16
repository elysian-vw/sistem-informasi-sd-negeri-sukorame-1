<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = 'sekolah';

    // Tabel sekolah saat ini belum memiliki kolom data.
    // Perlu update migration untuk menambah kolom berikut.
    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'status_sekolah',
        'jenjang',
        'tahun_berdiri',
        'telepon',
        'email',
        'website',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        'foto_kepsek',
        'sambutan_kepsek',
        'logo',
        'nama_singkat',
        'slogan',
        'akreditasi',
        'tahun_akreditasi',
        'nomor_sk_akreditasi',
        'nilai_akreditasi',
        'latitude',
        'longitude',
        'facebook',
        'instagram',
        'youtube',
        'tahun_ajaran_aktif',
        'semester_aktif',
    ];
}