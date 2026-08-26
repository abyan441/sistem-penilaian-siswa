<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model.
     */
    protected $table = 'siswa';

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'kelas_id',
    ];

    /**
     * Relasi ke kelas.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke nilai siswa.
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }
}