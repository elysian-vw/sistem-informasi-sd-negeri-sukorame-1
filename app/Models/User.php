<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'foto', 'no_hp'];
    protected $hidden = ['password', 'remember_token'];

    public function siswa() { return $this->hasOne(Siswa::class); }
    public function guru() { return $this->hasOne(Guru::class); }
    public function pengumuman() { return $this->hasMany(Pengumuman::class); }

    public function isAdmin() { return $this->role === 'admin'; }
    public function isGuru() { return $this->role === 'guru'; }
    public function isWaliMurid() { return $this->role === 'wali_murid'; }
    public function isSiswa() { return $this->role === 'siswa'; }
    public function isKepalaSekolah() { return $this->role === 'kepala_sekolah'; }
}