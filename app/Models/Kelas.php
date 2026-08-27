<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use RuntimeException;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    public $timestamps = false;

    protected $fillable = [
        'nama_kelas',
        'tahun_ajaran',
        'wali_kelas_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi ke guru yang menjadi wali kelas.
     */
    public function waliKelas()
    {
        return $this->belongsTo(
            User::class,
            'wali_kelas_id'
        );
    }

    /**
     * Relasi ke siswa yang berada di kelas.
     */
    public function siswa()
    {
        return $this->hasMany(
            Siswa::class,
            'kelas_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil seluruh data kelas beserta wali kelas
     * dan jumlah siswa.
     */
    public static function semuaKelas()
    {
        return static::query()
            ->with('waliKelas')
            ->withCount('siswa')
            ->orderBy('tahun_ajaran')
            ->orderBy('nama_kelas')
            ->get();
    }

    /**
     * Mengambil satu kelas berdasarkan ID.
     */
    public static function kelasById($id)
    {
        return static::query()
            ->with('waliKelas')
            ->withCount('siswa')
            ->findOrFail($id);
    }

    /**
     * Mengambil seluruh guru aktif yang dapat menjadi wali kelas.
     */
    public static function semuaWaliKelas()
    {
        return User::query()
            ->where('role', 'guru')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | KELAS WALI KELAS
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil kelas yang menjadi tanggung jawab
     * seorang wali kelas berdasarkan user ID.
     *
     * Fungsi ini akan digunakan untuk membatasi
     * akses input nilai.
     */
    public static function kelasWali($waliKelasId)
    {
        return static::query()
            ->where('wali_kelas_id', $waliKelasId)
            ->with([
                'waliKelas',
                'siswa' => function ($query) {
                    $query
                        ->orderBy('nama_siswa')
                        ->orderBy('nisn');
                },
            ])
            ->withCount('siswa')
            ->first();
    }

    /**
     * Mengambil kelas wali kelas dan memastikan
     * kelas tersebut memang dimiliki oleh user.
     *
     * Jika tidak ditemukan, akan menghasilkan 404.
     */
    public static function kelasWaliOrFail($waliKelasId)
    {
        return static::query()
            ->where('wali_kelas_id', $waliKelasId)
            ->with([
                'waliKelas',
                'siswa' => function ($query) {
                    $query
                        ->orderBy('nama_siswa')
                        ->orderBy('nisn');
                },
            ])
            ->withCount('siswa')
            ->firstOrFail();
    }

    /**
     * Mengecek apakah suatu kelas merupakan kelas
     * yang diwalikan oleh user tertentu.
     */
    public static function adalahKelasWali($kelasId, $waliKelasId): bool
    {
        return static::query()
            ->where('id', $kelasId)
            ->where('wali_kelas_id', $waliKelasId)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION & BUSINESS RULE
    |--------------------------------------------------------------------------
    */

    /**
     * Memvalidasi dan menyiapkan data kelas.
     *
     * Seluruh aturan bisnis kelas ditempatkan di sini
     * agar Controller hanya bertugas sebagai penghubung.
     */
    protected static function validasiData(array $data, $id = null)
    {
        $namaKelas = strtoupper(
            trim($data['nama_kelas'] ?? '')
        );

        $tahunAjaran = trim(
            $data['tahun_ajaran'] ?? ''
        );

        $waliKelasId = $data['wali_kelas_id'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Nama kelas
        |--------------------------------------------------------------------------
        */

        if ($namaKelas === '') {
            throw new InvalidArgumentException(
                'Nama kelas wajib diisi.'
            );
        }

        if (!preg_match('/^\d[A-Z]$/', $namaKelas)) {
            throw new InvalidArgumentException(
                'Nama kelas harus menggunakan format seperti 1A, 2B, atau 3C.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tahun ajaran
        |--------------------------------------------------------------------------
        */

        if ($tahunAjaran === '') {
            throw new InvalidArgumentException(
                'Tahun ajaran wajib diisi.'
            );
        }

        if (!preg_match('/^\d{4}\/\d{4}$/', $tahunAjaran)) {
            throw new InvalidArgumentException(
                'Tahun ajaran harus menggunakan format YYYY/YYYY, contoh 2026/2027.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Wali kelas
        |--------------------------------------------------------------------------
        */

        if (
            $waliKelasId === null ||
            $waliKelasId === '' ||
            !is_numeric($waliKelasId)
        ) {
            throw new InvalidArgumentException(
                'Wali kelas wajib dipilih.'
            );
        }

        $waliKelasId = (int) $waliKelasId;

        $waliKelas = User::query()
            ->where('id', $waliKelasId)
            ->where('role', 'guru')
            ->where('status', 'aktif')
            ->exists();

        if (!$waliKelas) {
            throw new InvalidArgumentException(
                'Guru yang dipilih tidak dapat menjadi wali kelas.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Nama kelas tidak boleh sama
        |--------------------------------------------------------------------------
        */

        $queryNama = static::query()
            ->whereRaw(
                'UPPER(nama_kelas) = ?',
                [$namaKelas]
            );

        if ($id !== null) {
            $queryNama->where('id', '!=', $id);
        }

        if ($queryNama->exists()) {
            throw new InvalidArgumentException(
                'Nama kelas sudah digunakan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Satu guru hanya boleh menjadi wali satu kelas
        |--------------------------------------------------------------------------
        */

        $queryWali = static::query()
            ->where('wali_kelas_id', $waliKelasId);

        if ($id !== null) {
            $queryWali->where('id', '!=', $id);
        }

        if ($queryWali->exists()) {
            throw new InvalidArgumentException(
                'Guru tersebut sudah menjadi wali kelas lain.'
            );
        }

        return [
            'nama_kelas' => $namaKelas,
            'tahun_ajaran' => $tahunAjaran,
            'wali_kelas_id' => $waliKelasId,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Menambahkan kelas baru.
     */
    public static function tambahKelas(array $data)
    {
        $data = static::validasiData($data);

        return static::query()
            ->create($data)
            ->load('waliKelas')
            ->loadCount('siswa');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Memperbarui data kelas.
     */
    public static function ubahKelas($id, array $data)
    {
        $kelas = static::query()
            ->findOrFail($id);

        $data = static::validasiData(
            $data,
            $kelas->id
        );

        $kelas->update($data);

        return $kelas
            ->fresh([
                'waliKelas',
            ])
            ->loadCount('siswa');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    /**
     * Menghapus kelas.
     *
     * Kelas yang masih memiliki siswa tidak boleh dihapus.
     */
    public static function hapusKelas($id)
    {
        $kelas = static::query()
            ->withCount('siswa')
            ->findOrFail($id);

        if ($kelas->siswa_count > 0) {
            throw new RuntimeException(
                'Kelas tidak dapat dihapus karena masih memiliki siswa.'
            );
        }

        return $kelas->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil detail kelas beserta daftar siswa.
     */
    public static function detailKelas($id)
    {
        return static::query()
            ->with([
                'waliKelas',
                'siswa' => function ($query) {
                    $query
                        ->orderBy('nama_siswa')
                        ->orderBy('nisn');
                },
            ])
            ->withCount('siswa')
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil ringkasan data kelas.
     */
    public static function ringkasan()
    {
        $totalKelas = static::query()->count();

        $totalSiswa = Siswa::query()->count();

        $rataRata = $totalKelas > 0
            ? (int) round(
                $totalSiswa / $totalKelas
            )
            : 0;

        return [
            'total_kelas' => $totalKelas,
            'total_siswa' => $totalSiswa,
            'rata_rata' => $rataRata,
        ];
    }
}