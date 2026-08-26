<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model.
     */
    protected $table = 'kelas';

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'nama_kelas',
        'tahun_ajaran',
        'wali_kelas_id',
    ];

    /**
     * Relasi ke wali kelas.
     */
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    /**
     * Relasi ke siswa yang berada di kelas ini.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}