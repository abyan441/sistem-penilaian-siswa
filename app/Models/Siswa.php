<?php

namespace App\Models;

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

    public static function dataHalaman(): array
    {
        return [
            'siswa' => self::query()
                ->with('kelas')
                ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                ->select('siswa.*')
                ->orderBy('kelas.nama_kelas')
                ->orderBy('siswa.nama_siswa')
                ->get(),
            'kelas' => Kelas::query()
                ->orderBy('nama_kelas')
                ->get(),
        ];
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
