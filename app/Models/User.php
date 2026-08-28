<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'email',
        'role',
        'status',
        'nip',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * =====================================================
     * AUTENTIKASI
     * =====================================================
     */

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * =====================================================
     * RELASI
     * =====================================================
     */

    public function guruMapel()
    {
        return $this->hasMany(
            GuruMapel::class,
            'guru_id'
        );
    }

    public function waliKelas()
    {
        return $this->hasOne(
            Kelas::class,
            'wali_kelas_id'
        );
    }

    /**
     * =====================================================
     * DATA GURU
     * =====================================================
     */

    public static function semuaGuru()
    {
        return self::query()
            ->where('role', 'guru')
            ->orderBy('nama_lengkap', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public static function guruById($id)
    {
        return self::query()
            ->where('role', 'guru')
            ->findOrFail($id);
    }

    /**
     * =====================================================
     * ROLE
     * =====================================================
     */

    public static function daftarRole(): array
    {
        return [
            'admin',
            'kepala_sekolah',
            'guru',
        ];
    }

    /**
     * Role yang boleh dibuat melalui halaman pengguna.
     *
     * Admin sengaja tidak dimasukkan.
     */
    public static function daftarRoleTambah(): array
    {
        return [
            'kepala_sekolah',
            'guru',
        ];
    }

    /**
     * =====================================================
     * STATUS
     * =====================================================
     */

    public static function daftarStatus(): array
    {
        return [
            'aktif',
            'tidak_aktif',
        ];
    }

    /**
     * =====================================================
     * SEMUA PENGGUNA
     * =====================================================
     */

    public static function semuaPengguna()
    {
        return self::query()
            ->orderByRaw("\n                CASE role\n                    WHEN 'admin' THEN 1\n                    WHEN 'kepala_sekolah' THEN 2\n                    WHEN 'guru' THEN 3\n                    ELSE 4\n                END\n            ")
            ->orderBy('nama_lengkap', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * =====================================================
     * PENGGUNA BY ID
     * =====================================================
     */

    public static function penggunaById($id): self
    {
        return self::query()
            ->findOrFail($id);
    }

    /**
     * =====================================================
     * STATISTIK
     * =====================================================
     */

    public static function totalPengguna(): int
    {
        return self::query()->count();
    }

    public static function totalAdmin(): int
    {
        return self::query()
            ->where('role', 'admin')
            ->count();
    }

    public static function totalKepalaSekolah(): int
    {
        return self::query()
            ->where('role', 'kepala_sekolah')
            ->count();
    }

    public static function totalGuru(): int
    {
        return self::query()
            ->where('role', 'guru')
            ->count();
    }

    public static function totalAktif(): int
    {
        return self::query()
            ->where('status', 'aktif')
            ->count();
    }

    public static function totalTidakAktif(): int
    {
        return self::query()
            ->where('status', 'tidak_aktif')
            ->count();
    }

    public static function statistikPengguna(): array
    {
        return [
            'totalPengguna' => self::totalPengguna(),
            'totalAdmin' => self::totalAdmin(),
            'totalKepalaSekolah' => self::totalKepalaSekolah(),
            'totalGuru' => self::totalGuru(),
            'totalAktif' => self::totalAktif(),
            'totalTidakAktif' => self::totalTidakAktif(),
        ];
    }

    /**
     * =====================================================
     * MEMBUAT PENGGUNA
     * =====================================================
     */

    public static function buatPengguna(array $data): self
    {
        return self::query()->create([
            'username' => $data['username'],
            'password' => $data['password'],
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'role' => $data['role'],
            'status' => $data['status'] ?? 'aktif',
            'nip' => $data['nip'] ?? null,
        ]);
    }

    /**
     * =====================================================
     * MEMPERBARUI PENGGUNA
     * =====================================================
     */

    public static function perbaruiPengguna(
        int $id,
        array $data
    ): self {
        $pengguna = self::penggunaById($id);

        $pengguna->username = $data['username'];

        $pengguna->nama_lengkap =
            $data['nama_lengkap'];

        $pengguna->email =
            $data['email'];

        $pengguna->role =
            $data['role'];

        $pengguna->status =
            $data['status'] ?? 'aktif';

        $pengguna->nip =
            $data['nip'] ?? null;

        /*
         * Password hanya diubah jika user
         * memasukkan password baru.
         */
        if (
            isset($data['password']) &&
            $data['password'] !== ''
        ) {
            $pengguna->password =
                $data['password'];
        }

        $pengguna->save();

        return $pengguna->fresh();
    }

    /**
     * =====================================================
     * MENGUBAH STATUS PENGGUNA
     * =====================================================
     */

    public static function ubahStatus(
        int $id,
        string $status
    ): self {
        if (
            !in_array(
                $status,
                self::daftarStatus(),
                true
            )
        ) {
            throw new \InvalidArgumentException(
                'Status pengguna tidak valid.'
            );
        }

        $pengguna = self::penggunaById($id);

        /*
         * Jangan sampai user yang sedang login
         * menonaktifkan akun dirinya sendiri.
         */
        if (
            auth()->check() &&
            (int) auth()->id() === (int) $pengguna->id &&
            $status === 'tidak_aktif'
        ) {
            throw new \RuntimeException(
                'Akun yang sedang digunakan tidak dapat dinonaktifkan.'
            );
        }

        $pengguna->status = $status;

        $pengguna->save();

        return $pengguna->fresh();
    }

    /**
     * =====================================================
     * MENGHAPUS PENGGUNA
     * =====================================================
     */

    public static function hapusPengguna(int $id): bool
    {
        $pengguna = self::penggunaById($id);

        if (
            auth()->check() &&
            (int) auth()->id() === (int) $pengguna->id
        ) {
            throw new \RuntimeException(
                'Pengguna yang sedang login tidak dapat dihapus.'
            );
        }

        return (bool) $pengguna->delete();
    }
}