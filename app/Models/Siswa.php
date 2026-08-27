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

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Siswa memiliki satu kelas.
     */
    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'kelas_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DATA HALAMAN
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil data siswa beserta kelas.
     */
    public static function dataHalaman()
    {
        return [
            'siswa' => self::with('kelas')
                ->join(
                    'kelas',
                    'siswa.kelas_id',
                    '=',
                    'kelas.id'
                )
                ->select('siswa.*')
                ->orderBy('kelas.nama_kelas')
                ->orderBy('siswa.nama_siswa')
                ->get(),

            'kelas' => Kelas::orderBy('nama_kelas')
                ->get(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH SISWA
    |--------------------------------------------------------------------------
    */

    public static function tambah(array $data)
    {
        return self::create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | UBAH SISWA
    |--------------------------------------------------------------------------
    */

    public static function ubah($id, array $data)
    {
        $siswa = self::findOrFail($id);

        $siswa->update($data);

        return $siswa->fresh('kelas');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS SISWA
    |--------------------------------------------------------------------------
    */

    public static function hapus($id)
    {
        $siswa = self::findOrFail($id);

        return $siswa->delete();
    }
}