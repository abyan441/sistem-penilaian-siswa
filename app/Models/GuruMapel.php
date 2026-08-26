<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model.
     */
    protected $table = 'guru_mapel';

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'guru_id',
        'mapel_id',
    ];

    /**
     * Relasi ke guru yang mengajar.
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Relasi ke mata pelajaran yang diajarkan.
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    /**
     * Relasi ke nilai siswa untuk guru dan mata pelajaran ini.
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'guru_mapel_id');
    }
}