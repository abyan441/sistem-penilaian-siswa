<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    public $timestamps = false;

    protected $fillable = [
        'kode_mapel',
        'nama_pelajaran',
        'kkm',
    ];

    protected $casts = [
        'kkm' => 'integer',
    ];

    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class, 'mapel_id');
    }

    public function scopeUrutNama(Builder $query): Builder
    {
        return $query
            ->orderBy('nama_pelajaran')
            ->orderBy('id');
    }

    public static function dataHalaman(): array
    {
        return [
            'mataPelajaran' => self::semua(),
            'totalMapel' => self::total(),
            'rataRataKkm' => self::rataRataKkm(),
            'kkmTerendah' => self::kkmTerendah(),
        ];
    }

    public static function semua()
    {
        return self::query()
            ->urutNama()
            ->get();
    }

    public static function total(): int
    {
        return self::query()->count();
    }

    public static function rataRataKkm(): ?float
    {
        $rataRata = self::query()->avg('kkm');

        return $rataRata === null ? null : round((float) $rataRata, 2);
    }

    public static function kkmTerendah(): ?int
    {
        $minimum = self::query()->min('kkm');

        return $minimum === null ? null : (int) $minimum;
    }

    protected static function validasiData(array $data, ?int $excludeId = null): array
    {
        $kodeMapel = trim((string) ($data['kode_mapel'] ?? ''));
        $namaPelajaran = trim((string) ($data['nama_pelajaran'] ?? ''));
        $kkm = $data['kkm'] ?? null;

        if ($kodeMapel === '') {
            throw ValidationException::withMessages([
                'kode_mapel' => 'Kode mata pelajaran wajib diisi.',
            ]);
        }

        if (mb_strlen($kodeMapel) > 5) {
            throw ValidationException::withMessages([
                'kode_mapel' => 'Kode mata pelajaran maksimal 5 karakter.',
            ]);
        }

        if ($namaPelajaran === '') {
            throw ValidationException::withMessages([
                'nama_pelajaran' => 'Nama mata pelajaran wajib diisi.',
            ]);
        }

        if (mb_strlen($namaPelajaran) > 45) {
            throw ValidationException::withMessages([
                'nama_pelajaran' => 'Nama mata pelajaran maksimal 45 karakter.',
            ]);
        }

        if (!is_numeric($kkm) || (int) $kkm < 0 || (int) $kkm > 100) {
            throw ValidationException::withMessages([
                'kkm' => 'KKM harus berupa angka antara 0 sampai 100.',
            ]);
        }

        $kodeMapelTersimpan = strtoupper($kodeMapel);

        $duplicate = self::query()
            ->whereRaw('UPPER(kode_mapel) = ?', [$kodeMapelTersimpan]);

        if ($excludeId !== null) {
            $duplicate->where('id', '!=', $excludeId);
        }

        if ($duplicate->exists()) {
            throw ValidationException::withMessages([
                'kode_mapel' => 'Kode mata pelajaran sudah digunakan.',
            ]);
        }

        return [
            'kode_mapel' => $kodeMapelTersimpan,
            'nama_pelajaran' => $namaPelajaran,
            'kkm' => (int) $kkm,
        ];
    }

    public static function tambah(array $data): self
    {
        $validated = self::validasiData($data);

        return self::query()->create($validated);
    }

    public static function ubah(int $id, array $data): self
    {
        $mapel = self::query()->findOrFail($id);
        $validated = self::validasiData($data, $mapel->id);

        $mapel->update($validated);

        return $mapel->refresh();
    }

    public static function hapus(int $id): void
    {
        $mapel = self::query()->withCount('guruMapel')->findOrFail($id);

        if ($mapel->guru_mapel_count > 0) {
            throw new RuntimeException(
                'Mata pelajaran tidak dapat dihapus karena masih digunakan oleh data guru mata pelajaran.'
            );
        }

        $mapel->delete();
    }
}
