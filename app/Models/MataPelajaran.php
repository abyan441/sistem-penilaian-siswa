<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
        return $query->orderBy('nama_pelajaran')->orderBy('id');
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
        return self::query()->urutNama()->get();
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

    public static function tambah(array $data): self
    {
        return self::query()->create([
            'kode_mapel' => strtoupper(trim($data['kode_mapel'])),
            'nama_pelajaran' => trim($data['nama_pelajaran']),
            'kkm' => $data['kkm'],
        ]);
    }

    public static function ubah(int $id, array $data): self
    {
        $mapel = self::query()->findOrFail($id);

        $mapel->update([
            'kode_mapel' => strtoupper(trim($data['kode_mapel'])),
            'nama_pelajaran' => trim($data['nama_pelajaran']),
            'kkm' => $data['kkm'],
        ]);

        return $mapel->refresh();
    }

    public static function hapus(int $id): void
    {
        self::query()->findOrFail($id)->delete();
    }
}
