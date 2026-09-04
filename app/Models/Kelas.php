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

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public static function semuaKelas()
    {
        return static::query()
            ->with('waliKelas')
            ->withCount('siswa')
            ->orderBy('tahun_ajaran')
            ->orderBy('nama_kelas')
            ->get();
    }

    public static function kelasById($id)
    {
        return static::query()
            ->with('waliKelas')
            ->withCount('siswa')
            ->findOrFail($id);
    }

    public static function semuaWaliKelas()
    {
        return User::guruAktif();
    }

    public static function kelasWali($waliKelasId, $throwIfNotFound = false)
    {
        $query = static::query()
            ->where('wali_kelas_id', $waliKelasId)
            ->with([
                'waliKelas',
                'siswa' => fn ($query) => $query
                    ->orderBy('nama_siswa')
                    ->orderBy('nisn'),
            ])
            ->withCount('siswa');

        return $throwIfNotFound ? $query->firstOrFail() : $query->first();
    }

    public static function adalahKelasWali($kelasId, $waliKelasId): bool
    {
        return static::query()
            ->where('id', $kelasId)
            ->where('wali_kelas_id', $waliKelasId)
            ->exists();
    }

    public static function total(): int
    {
        return static::query()->count();
    }

    public static function tahunAjaranOptions()
    {
        return static::query()
            ->whereNotNull('tahun_ajaran')
            ->where('tahun_ajaran', '!=', '')
            ->select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');
    }

    public static function tahunAjaranWaliGuru(int $guruId)
    {
        return static::query()
            ->where('wali_kelas_id', $guruId)
            ->whereNotNull('tahun_ajaran')
            ->where('tahun_ajaran', '!=', '')
            ->select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');
    }

    public static function resolveTahunAjaran(?string $tahunAjaran): ?string
    {
        if ($tahunAjaran !== null && $tahunAjaran !== '') {
            return $tahunAjaran;
        }

        return static::tahunAjaranOptions()->first();
    }

    public static function dapatAksesRaport(int $guruId): bool
    {
        return static::query()
            ->where('wali_kelas_id', $guruId)
            ->exists();
    }

    public static function kelasWaliGuru(int $guruId, ?string $tahunAjaran = null): ?self
    {
        $query = static::query()
            ->where('wali_kelas_id', $guruId);

        if ($tahunAjaran !== null && $tahunAjaran !== '') {
            $query->where('tahun_ajaran', trim($tahunAjaran));
        }

        return $query
            ->orderByDesc('tahun_ajaran')
            ->first();
    }

    public static function siswaUntukRaport(?string $tahunAjaran)
    {
        if (!$tahunAjaran) {
            return collect();
        }

        return Siswa::berdasarkanTahunAjaran($tahunAjaran);
    }

    protected static function validasiData(array $data, $id = null): array
    {
        $namaKelas = strtoupper(trim($data['nama_kelas'] ?? ''));
        $tahunAjaran = trim($data['tahun_ajaran'] ?? '');
        $waliKelasId = $data['wali_kelas_id'] ?? null;

        if ($namaKelas === '') {
            throw new InvalidArgumentException('Nama kelas wajib diisi.');
        }

        if (!preg_match('/^\d[A-Z]$/', $namaKelas)) {
            throw new InvalidArgumentException(
                'Nama kelas harus menggunakan format seperti 1A, 2B, atau 3C.'
            );
        }

        if ($tahunAjaran === '') {
            throw new InvalidArgumentException('Tahun ajaran wajib diisi.');
        }

        if (!preg_match('/^\d{4}\/\d{4}$/', $tahunAjaran)) {
            throw new InvalidArgumentException(
                'Tahun ajaran harus menggunakan format YYYY/YYYY, contoh 2026/2027.'
            );
        }

        if ($waliKelasId === null || $waliKelasId === '' || !is_numeric($waliKelasId)) {
            throw new InvalidArgumentException('Wali kelas wajib dipilih.');
        }

        $waliKelasId = (int) $waliKelasId;

        $waliValid = User::query()
            ->where('id', $waliKelasId)
            ->where('role', 'guru')
            ->where('status', 'aktif')
            ->exists();

        if (!$waliValid) {
            throw new InvalidArgumentException('Guru yang dipilih tidak dapat menjadi wali kelas.');
        }

        $queryNama = static::query()
            ->whereRaw('UPPER(nama_kelas) = ?', [$namaKelas])
            ->where('tahun_ajaran', $tahunAjaran);

        if ($id !== null) {
            $queryNama->where('id', '!=', $id);
        }

        if ($queryNama->exists()) {
            throw new InvalidArgumentException('Nama kelas sudah digunakan pada tahun ajaran tersebut.');
        }

        $queryWali = static::query()
            ->where('wali_kelas_id', $waliKelasId)
            ->where('tahun_ajaran', $tahunAjaran);

        if ($id !== null) {
            $queryWali->where('id', '!=', $id);
        }

        if ($queryWali->exists()) {
            throw new InvalidArgumentException('Guru tersebut sudah menjadi wali kelas lain pada tahun ajaran tersebut.');
        }

        return [
            'nama_kelas' => $namaKelas,
            'tahun_ajaran' => $tahunAjaran,
            'wali_kelas_id' => $waliKelasId,
        ];
    }

    public static function tambahKelas(array $data)
    {
        return static::query()
            ->create(static::validasiData($data))
            ->load('waliKelas')
            ->loadCount('siswa');
    }

    public static function ubahKelas($id, array $data)
    {
        $kelas = static::query()->findOrFail($id);

        $kelas->update(static::validasiData($data, $kelas->id));

        return $kelas
            ->fresh(['waliKelas'])
            ->loadCount('siswa');
    }

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

    public static function detailKelas($id)
    {
        return static::query()
            ->with([
                'waliKelas',
                'siswa' => fn ($query) => $query
                    ->orderBy('nama_siswa')
                    ->orderBy('nisn'),
            ])
            ->withCount('siswa')
            ->findOrFail($id);
    }

    public static function ringkasan(): array
    {
        $totalKelas = static::total();
        $totalSiswa = Siswa::query()->count();

        return [
            'total_kelas' => $totalKelas,
            'total_siswa' => $totalSiswa,
            'rata_rata' => $totalKelas > 0
                ? (int) round($totalSiswa / $totalKelas)
                : 0,
        ];
    }
}
