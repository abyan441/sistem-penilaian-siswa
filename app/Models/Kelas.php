<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA HALAMAN
    |--------------------------------------------------------------------------
    */

    public static function dataHalaman()
    {
        return [
            'kelas' => self::with('waliKelas')
                ->withCount('siswa')
                ->orderBy('nama_kelas')
                ->get(),
            'guru' => User::semuaGuru(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    private static function validasi(array $data, $id = null)
    {
        if (!preg_match('/^\d[A-Z]$/', strtoupper(trim($data['nama_kelas'] ?? '')))) {
            throw new \Exception('Nama kelas harus mengikuti format seperti 1A, 2B, atau 3C.');
        }

        if (!preg_match('/^\d{4}\/\d{4}$/', trim($data['tahun_ajaran'] ?? ''))) {
            throw new \Exception('Tahun ajaran harus menggunakan format YYYY/YYYY.');
        }

        if (empty($data['wali_kelas_id'])) {
            throw new \Exception('Wali kelas wajib dipilih.');
        }

        $nama = strtoupper(trim($data['nama_kelas']));

        $query = self::whereRaw('UPPER(nama_kelas) = ?', [$nama]);

        if ($id !== null) {
            $query->where('id', '!=', $id);
        }

        if ($query->exists()) {
            throw new \Exception('Nama kelas sudah digunakan.');
        }

        User::guruById($data['wali_kelas_id']);

        $waliQuery = self::where('wali_kelas_id', $data['wali_kelas_id']);

        if ($id !== null) {
            $waliQuery->where('id', '!=', $id);
        }

        if ($waliQuery->exists()) {
            throw new \Exception('Guru tersebut sudah menjadi wali kelas lain.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    public static function tambah(array $data)
    {
        self::validasi($data);

        $data['nama_kelas'] = strtoupper(trim($data['nama_kelas']));
        $data['tahun_ajaran'] = trim($data['tahun_ajaran']);

        return self::create($data)->load('waliKelas')->loadCount('siswa');
    }

    /*
    |--------------------------------------------------------------------------
    | UBAH
    |--------------------------------------------------------------------------
    */

    public static function ubah($id, array $data)
    {
        $kelas = self::find($id);

        if (!$kelas) {
            throw new \Exception('Data kelas tidak ditemukan.');
        }

        self::validasi($data, $id);

        $data['nama_kelas'] = strtoupper(trim($data['nama_kelas']));
        $data['tahun_ajaran'] = trim($data['tahun_ajaran']);

        $kelas->update($data);

        return $kelas->fresh(['waliKelas'])->loadCount('siswa');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    public static function hapus($id)
    {
        $kelas = self::withCount('siswa')->find($id);

        if (!$kelas) {
            throw new \Exception('Data kelas tidak ditemukan.');
        }

        if ($kelas->siswa_count > 0) {
            throw new \Exception(
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

    public static function detail($id)
    {
        $kelas = self::with(['waliKelas', 'siswa'])
            ->find($id);

        if (!$kelas) {
            throw new \Exception('Data kelas tidak ditemukan.');
        }

        return [
            'id' => $kelas->id,
            'nama_kelas' => $kelas->nama_kelas,
            'tahun_ajaran' => $kelas->tahun_ajaran,
            'wali_kelas' => $kelas->waliKelas->nama_lengkap ?? '-',
            'jumlah_siswa' => $kelas->siswa->count(),
            'siswa' => $kelas->siswa
                ->sortBy('nama_siswa')
                ->values()
                ->map(function ($siswa) {
                    return [
                        'nisn' => $siswa->nisn,
                        'nama_siswa' => $siswa->nama_siswa,
                        'jenis_kelamin' => $siswa->jenis_kelamin,
                    ];
                }),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES
    |--------------------------------------------------------------------------
    */

    public static function prosesTambah(array $data)
    {
        $kelas = self::tambah($data);

        return [
            'success' => true,
            'message' => 'Data kelas berhasil ditambahkan.',
            'data' => self::formatData($kelas),
        ];
    }

    public static function prosesUbah($id, array $data)
    {
        $kelas = self::ubah($id, $data);

        return [
            'success' => true,
            'message' => 'Data kelas berhasil diperbarui.',
            'data' => self::formatData($kelas),
        ];
    }

    public static function prosesHapus($id)
    {
        self::hapus($id);

        return [
            'success' => true,
            'message' => 'Data kelas berhasil dihapus.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT DATA
    |--------------------------------------------------------------------------
    */

    private static function formatData(self $kelas)
    {
        return [
            'id' => $kelas->id,
            'nama_kelas' => $kelas->nama_kelas,
            'tahun_ajaran' => $kelas->tahun_ajaran,
            'wali_kelas_id' => $kelas->wali_kelas_id,
            'wali_kelas' => $kelas->waliKelas->nama_lengkap ?? '-',
            'jumlah_siswa' => $kelas->siswa_count ?? $kelas->siswa()->count(),
        ];
    }
}
