<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['name', 'username', 'password', 'nama_lengkap', 'email', 'role', 'status', 'nip'];
    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class, 'guru_id');
    }

    public function waliKelas()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    public static function semuaGuru()
    {
        return self::query()->where('role', 'guru')->orderBy('nama_lengkap')->orderBy('id')->get();
    }

    public static function guruById($id)
    {
        return self::query()->where('role', 'guru')->findOrFail($id);
    }

    public static function daftarRole(): array
    {
        return ['admin', 'kepala_sekolah', 'guru'];
    }

    public static function daftarRoleLogin(): array
    {
        return ['admin', 'kepala_sekolah', 'guru'];
    }

    public static function daftarRoleTambah(): array
    {
        return ['kepala_sekolah', 'guru'];
    }

    public static function daftarStatus(): array
    {
        return ['aktif', 'tidak_aktif'];
    }

    public static function semuaPengguna()
    {
        return self::query()
            ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'kepala_sekolah' THEN 2 WHEN 'guru' THEN 3 ELSE 4 END")
            ->orderBy('nama_lengkap')
            ->orderBy('id')
            ->get();
    }

    public static function penggunaById($id): self
    {
        return self::query()->findOrFail($id);
    }

    private static function countByConditions(array $conditions = []): int
    {
        $query = self::query();
        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }
        return $query->count();
    }

    public static function totalPengguna(): int
    {
        return self::countByConditions();
    }

    public static function totalAdmin(): int
    {
        return self::countByConditions(['role' => 'admin']);
    }

    public static function totalKepalaSekolah(): int
    {
        return self::countByConditions(['role' => 'kepala_sekolah']);
    }

    public static function totalGuru(): int
    {
        return self::countByConditions(['role' => 'guru']);
    }

    public static function totalAktif(): int
    {
        return self::countByConditions(['status' => 'aktif']);
    }

    public static function totalTidakAktif(): int
    {
        return self::countByConditions(['status' => 'tidak_aktif']);
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

    public static function guruAktif()
    {
        return self::query()
            ->where('role', 'guru')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->orderBy('id')
            ->get();
    }

    public static function totalGuruAktif(): int
    {
        return self::countByConditions(['role' => 'guru', 'status' => 'aktif']);
    }

    public static function kepalaSekolahAktif(): ?self
    {
        return self::query()->where('role', 'kepala_sekolah')->where('status', 'aktif')->orderBy('nama_lengkap')->first();
    }

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

    public static function perbaruiPengguna(int $id, array $data): self
    {
        $pengguna = self::penggunaById($id);
        $roleBaru = $data['role'];

        if ($pengguna->role !== $roleBaru) {
            if ($pengguna->guruMapel()->exists()) {
                throw new \RuntimeException(
                    'Role pengguna tidak dapat diubah karena masih memiliki penugasan mata pelajaran.'
                );
            }

            if ($pengguna->waliKelas()->exists()) {
                throw new \RuntimeException(
                    'Role pengguna tidak dapat diubah karena masih menjadi wali kelas.'
                );
            }
        }

        $pengguna->username = $data['username'];
        $pengguna->nama_lengkap = $data['nama_lengkap'];
        $pengguna->email = $data['email'];
        $pengguna->role = $roleBaru;
        $pengguna->status = $data['status'] ?? 'aktif';
        $pengguna->nip = $data['nip'] ?? null;

        if (isset($data['password']) && $data['password'] !== '') {
            $pengguna->password = $data['password'];
        }

        $pengguna->save();

        return $pengguna->fresh();
    }

    public static function loginBoleh(string $email, string $password): ?self
    {
        $user = self::query()
            ->where('email', $email)
            ->whereIn('role', self::daftarRoleLogin())
            ->where('status', 'aktif')
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public static function ubahEmail(self $user, string $email): self
    {
        $user->email = $email;
        $user->save();

        return $user->fresh();
    }

    public static function ubahEmailDiri(self $user, string $emailBaru, string $konfirmasiEmail, string $passwordSaatIni): self
    {
        if (trim($emailBaru) === '') {
            throw new \InvalidArgumentException('Email baru wajib diisi.');
        }

        if (!filter_var($emailBaru, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Format email baru tidak valid.');
        }

        if (mb_strlen($emailBaru) > 30) {
            throw new \InvalidArgumentException('Email baru maksimal 30 karakter.');
        }

        if (trim($emailBaru) !== trim($konfirmasiEmail)) {
            throw new \InvalidArgumentException('Konfirmasi email tidak sama dengan email baru.');
        }

        if (!Hash::check($passwordSaatIni, $user->password)) {
            throw new \InvalidArgumentException('Password saat ini salah.');
        }

        $exists = self::query()
            ->where('email', $emailBaru)
            ->whereKeyNot($user->getKey())
            ->exists();

        if ($exists) {
            throw new \InvalidArgumentException('Email tersebut sudah digunakan oleh pengguna lain.');
        }

        return self::ubahEmail($user, $emailBaru);
    }

    public static function ubahPassword(self $user, string $password): self
    {
        $user->password = $password;
        $user->save();

        return $user->fresh();
    }

    public static function ubahPasswordDiri(self $user, string $passwordSaatIni, string $passwordBaru): self
    {
        if (!Hash::check($passwordSaatIni, $user->password)) {
            throw new \InvalidArgumentException('Password saat ini salah.');
        }

        if (strlen($passwordBaru) < 8) {
            throw new \InvalidArgumentException('Password baru minimal 8 karakter.');
        }

        return self::ubahPassword($user, $passwordBaru);
    }

    public static function ubahStatus(int $id, string $status): self
    {
        if (!in_array($status, self::daftarStatus(), true)) {
            throw new \InvalidArgumentException('Status pengguna tidak valid.');
        }

        $pengguna = self::penggunaById($id);

        if (auth()->check() && (int) auth()->id() === (int) $pengguna->id && $status === 'tidak_aktif') {
            throw new \RuntimeException('Akun yang sedang digunakan tidak dapat dinonaktifkan.');
        }

        $pengguna->status = $status;
        $pengguna->save();

        return $pengguna->fresh();
    }

    public static function hapusPengguna(int $id): bool
    {
        $pengguna = self::penggunaById($id);

        if (auth()->check() && (int) auth()->id() === (int) $pengguna->id) {
            throw new \RuntimeException('Pengguna yang sedang login tidak dapat dihapus.');
        }

        if ($pengguna->guruMapel()->exists()) {
            throw new \RuntimeException(
                'Pengguna tidak dapat dihapus karena masih memiliki penugasan mata pelajaran. Hapus penugasannya terlebih dahulu.'
            );
        }

        if ($pengguna->waliKelas()->exists()) {
            throw new \RuntimeException(
                'Pengguna tidak dapat dihapus karena masih menjadi wali kelas. Ubah wali kelas terlebih dahulu.'
            );
        }

        return (bool) $pengguna->delete();
    }
}
