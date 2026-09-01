<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class Siswa extends Model
{
    protected $table = 'siswa';

    public $timestamps = false;

    protected $fillable = [
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'kelas_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }

    public function scopeTahunAjaran(Builder $query, string $tahunAjaran): Builder
    {
        return $query->whereHas('kelas', function (Builder $kelas) use ($tahunAjaran) {
            $kelas->where('tahun_ajaran', $tahunAjaran);
        });
    }

    public function scopeUrutNama(Builder $query): Builder
    {
        return $query
            ->orderBy('nama_siswa')
            ->orderBy('nisn');
    }

    public static function dataHalaman(): array
    {
        return [
            'siswa' => self::query()
                ->with('kelas')
                ->orderBy(
                    Kelas::query()
                        ->select('nama_kelas')
                        ->whereColumn('kelas.id', 'siswa.kelas_id')
                )
                ->orderBy('nama_siswa')
                ->get(),
            'kelas' => Kelas::query()
                ->orderBy('nama_kelas')
                ->get(),
        ];
    }

    public static function berdasarkanTahunAjaran(string $tahunAjaran)
    {
        return self::query()
            ->with('kelas')
            ->tahunAjaran($tahunAjaran)
            ->urutNama()
            ->get();
    }

    public static function untukRaport(int $id): self
    {
        return self::query()
            ->with(['kelas.waliKelas'])
            ->findOrFail($id);
    }

    public static function dataRaport(string $tahunAjaran): array
    {
        return self::berdasarkanTahunAjaran($tahunAjaran)
            ->map(fn (self $siswa) => [
                'id' => $siswa->id,
                'nisn' => $siswa->nisn,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas_id' => $siswa->kelas_id,
                'nama_kelas' => $siswa->kelas?->nama_kelas,
                'tahun_ajaran' => $siswa->kelas?->tahun_ajaran,
            ])
            ->values()
            ->all();
    }

    public static function total(): int
    {
        return self::query()->count();
    }

    protected static function validasiData(array $data, ?int $excludeId = null): array
    {
        $nisn = trim((string) ($data['nisn'] ?? ''));
        $namaSiswa = trim((string) ($data['nama_siswa'] ?? ''));
        $jenisKelamin = $data['jenis_kelamin'] ?? null;
        $kelasId = $data['kelas_id'] ?? null;

        if ($nisn === '') {
            throw ValidationException::withMessages([
                'nisn' => 'NISN wajib diisi.',
            ]);
        }

        if (mb_strlen($nisn) > 15) {
            throw ValidationException::withMessages([
                'nisn' => 'NISN maksimal 15 karakter.',
            ]);
        }

        if ($namaSiswa === '') {
            throw ValidationException::withMessages([
                'nama_siswa' => 'Nama siswa wajib diisi.',
            ]);
        }

        if (mb_strlen($namaSiswa) > 40) {
            throw ValidationException::withMessages([
                'nama_siswa' => 'Nama siswa maksimal 40 karakter.',
            ]);
        }

        if (!in_array($jenisKelamin, ['L', 'P'], true)) {
            throw ValidationException::withMessages([
                'jenis_kelamin' => 'Jenis kelamin tidak valid. Pilih L atau P.',
            ]);
        }

        if (!is_numeric($kelasId) || !Kelas::query()->whereKey((int) $kelasId)->exists()) {
            throw ValidationException::withMessages([
                'kelas_id' => 'Kelas yang dipilih tidak ditemukan.',
            ]);
        }

        $kelasId = (int) $kelasId;

        // NISN boleh digunakan kembali pada tahun ajaran berikutnya,
        // karena record siswa dibuat ulang untuk kelas/tahun ajaran baru.
        // Yang tidak boleh adalah NISN yang sama pada kelas yang sama.
        $duplicate = self::query()
            ->where('nisn', $nisn)
            ->where('kelas_id', $kelasId);

        if ($excludeId !== null) {
            $duplicate->where('id', '!=', $excludeId);
        }

        if ($duplicate->exists()) {
            throw ValidationException::withMessages([
                'nisn' => 'NISN sudah digunakan pada kelas tersebut.',
            ]);
        }

        return [
            'nisn' => $nisn,
            'nama_siswa' => $namaSiswa,
            'jenis_kelamin' => $jenisKelamin,
            'kelas_id' => $kelasId,
        ];
    }

    public static function tambah(array $data): self
    {
        $validated = self::validasiData($data);

        return self::query()->create($validated);
    }

    public static function ubah(int $id, array $data): self
    {
        $siswa = self::query()->findOrFail($id);
        $validated = self::validasiData($data, $siswa->id);

        $siswa->update($validated);

        return $siswa->fresh('kelas');
    }

    public static function hapus(int $id): bool
    {
        $siswa = self::query()
            ->withCount('nilai')
            ->findOrFail($id);

        if ($siswa->nilai_count > 0) {
            throw new RuntimeException(
                'Siswa tidak dapat dihapus karena masih memiliki data nilai.'
            );
        }

        return (bool) $siswa->delete();
    }
}
