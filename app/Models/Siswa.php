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
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }


    /*
    |--------------------------------------------------------------------------
    | DATA HALAMAN
    |--------------------------------------------------------------------------
    */

    public static function dataHalaman()
    {
        return [
            'siswa' => self::with('kelas')->get(),
            'kelas' => Kelas::all(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    public static function tambah(array $data)
    {
        return self::create($data);
    }


    /*
    |--------------------------------------------------------------------------
    | UBAH
    |--------------------------------------------------------------------------
    */

    public static function ubah($id, array $data)
    {
        $siswa = self::find($id);

        if (!$siswa) {
            throw new \Exception('Data siswa tidak ditemukan.');
        }

        $siswa->update($data);

        return $siswa->fresh('kelas');
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    public static function hapus($id)
    {
        $siswa = self::find($id);

        if (!$siswa) {
            throw new \Exception('Data siswa tidak ditemukan.');
        }

        return $siswa->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES TAMBAH
    |--------------------------------------------------------------------------
    */

    public static function prosesTambah(array $data)
    {
        $siswa = self::tambah($data);

        $siswa->load('kelas');

        return [
            'success' => true,
            'message' => 'Data siswa berhasil ditambahkan.',
            'data' => self::formatData($siswa),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES UBAH
    |--------------------------------------------------------------------------
    */

    public static function prosesUbah($id, array $data)
    {
        $siswa = self::ubah($id, $data);

        return [
            'success' => true,
            'message' => 'Data siswa berhasil diperbarui.',
            'data' => self::formatData($siswa),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES HAPUS
    |--------------------------------------------------------------------------
    */

    public static function prosesHapus($id)
    {
        self::hapus($id);

        return [
            'success' => true,
            'message' => 'Data siswa berhasil dihapus.',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT DATA
    |--------------------------------------------------------------------------
    */

    private static function formatData(self $siswa)
    {
        return [
            'id' => $siswa->id,
            'nisn' => $siswa->nisn,
            'nama_siswa' => $siswa->nama_siswa,
            'jenis_kelamin' => $siswa->jenis_kelamin,
            'kelas_id' => $siswa->kelas_id,
            'kelas' => $siswa->kelas->nama_kelas ?? '-',
        ];
    }
}