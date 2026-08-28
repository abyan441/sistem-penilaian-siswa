<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeTahunAjaran(Builder $query, string $tahunAjaran): Builder
    {
        return $query->whereHas('kelas', function (Builder $kelas) use ($tahunAjaran) {
            $kelas->where('tahun_ajaran', $tahunAjaran);
        });
    }

    public function scopeUrutNama(Builder $query): Builder
    {
        return $query->orderBy('nama_siswa')->orderBy('nisn');
    }

    public static function dataHalaman(): array
    {
        return [
            'siswa' => self::query()
                ->with('kelas')
                ->orderBy(Kelas::query()->select('nama_kelas')->whereColumn('kelas.id', 'siswa.kelas_id'))
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

    public static function tambah(array $data): self
    {
        return self::query()->create($data);
    }

    public static function ubah(int $id, array $data): self
    {
        $siswa = self::query()->findOrFail($id);
        $siswa->update($data);

        return $siswa->fresh('kelas');
    }

    public static function hapus(int $id): bool
    {
        return (bool) self::query()->findOrFail($id)->delete();
    }
}
