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
     * Primary key.
     */
    protected $primaryKey = 'id';

    /**
     * Tabel users tidak menggunakan
     * created_at dan updated_at.
     */
    public $timestamps = false;

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
     * =====================================================
     * RELASI KE GURU MAPEL
     * =====================================================
     *
     * Satu user dengan role guru dapat memiliki
     * beberapa mata pelajaran.
     */
    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class, 'guru_id');
    }

    /**
     * =====================================================
     * RELASI KE WALI KELAS
     * =====================================================
     */
    public function waliKelas()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    /**
     * =====================================================
     * DATA GURU
     * =====================================================
     *
     * Mengambil seluruh user yang memiliki role guru.
     *
     * Diurutkan berdasarkan nama lengkap A-Z.
     */
    public static function semuaGuru()
    {
        return self::where('role', 'guru')
            ->orderBy('nama_lengkap', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * =====================================================
     * MENGAMBIL GURU BERDASARKAN ID
     * =====================================================
     */
    public static function guruById($id)
    {
        return self::where('role', 'guru')
            ->findOrFail($id);
    }
}