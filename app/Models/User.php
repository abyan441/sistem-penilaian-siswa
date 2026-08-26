<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Nama tabel database.
     */
    protected $table = 'users';

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'email',
        'role',
        'status',
        'nip',
    ];

    /**
     * Kolom yang disembunyikan ketika model diserialisasi.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Menggunakan username sebagai identifier autentikasi.
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * Relasi ke data guru-mata pelajaran.
     */
    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class, 'guru_id');
    }

    /**
     * Relasi ke kelas yang diwalikan.
     */
    public function waliKelas()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }
}