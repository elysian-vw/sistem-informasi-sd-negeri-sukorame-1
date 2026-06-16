<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
        'file_path',
        'url_video',
        'thumbnail',
        'kategori',
        'tanggal',
        'is_published',
        'urutan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_published' => 'boolean',
        'urutan' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
