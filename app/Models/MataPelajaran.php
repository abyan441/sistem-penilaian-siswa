<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MataPelajaran extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI MODEL
    |--------------------------------------------------------------------------
    */

    protected $table = 'mata_pelajaran';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'kode_mapel',
        'nama_pelajaran',
        'kkm',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi Mata Pelajaran dengan GuruMapel.
     */
    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class, 'mapel_id');
    }


    /*
    |--------------------------------------------------------------------------
    | DATA HALAMAN
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil seluruh data yang dibutuhkan
     * oleh halaman Mata Pelajaran.
     */
    public static function dataHalaman()
    {
        return [
            'mataPelajaran' => self::semua(),
            'totalMapel' => self::total(),
            'rataRataKkm' => self::rataRataKkm(),
            'kkmTerendah' => self::kkmTerendah(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL DATA
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil seluruh mata pelajaran
     * dengan urutan nama A-Z.
     */
    public static function semua()
    {
        return self::orderBy('nama_pelajaran', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | STATISTIK
    |--------------------------------------------------------------------------
    */

    /**
     * Menghitung jumlah seluruh mata pelajaran.
     */
    public static function total()
    {
        return self::count();
    }


    /**
     * Menghitung rata-rata KKM.
     */
    public static function rataRataKkm()
    {
        return self::avg('kkm');
    }


    /**
     * Mengambil nilai KKM terendah.
     */
    public static function kkmTerendah()
    {
        return self::min('kkm');
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH
    |--------------------------------------------------------------------------
    */

    /**
     * Menambahkan mata pelajaran baru.
     *
     * Proses normalisasi data dilakukan di Model:
     * - kode mata pelajaran menjadi huruf kapital
     * - spasi di awal/akhir dihapus
     */
    public static function tambah(array $data)
    {
        return self::create([
            'kode_mapel' => strtoupper(trim($data['kode_mapel'])),
            'nama_pelajaran' => trim($data['nama_pelajaran']),
            'kkm' => $data['kkm'],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UBAH
    |--------------------------------------------------------------------------
    */

    /**
     * Memperbarui data mata pelajaran berdasarkan ID.
     */
    public static function ubah($id, array $data)
    {
        $mapel = self::find($id);

        if (!$mapel) {
            throw new \Exception(
                'Data mata pelajaran tidak ditemukan.'
            );
        }

        $mapel->update([
            'kode_mapel' => strtoupper(trim($data['kode_mapel'])),
            'nama_pelajaran' => trim($data['nama_pelajaran']),
            'kkm' => $data['kkm'],
        ]);

        return $mapel->fresh();
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    /**
     * Menghapus mata pelajaran berdasarkan ID.
     */
    public static function hapus($id)
    {
        $mapel = self::find($id);

        if (!$mapel) {
            throw new \Exception(
                'Data mata pelajaran tidak ditemukan.'
            );
        }

        return $mapel->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES TAMBAH
    |--------------------------------------------------------------------------
    */

    /**
     * Menangani seluruh proses tambah mata pelajaran.
     *
     * Controller hanya meneruskan Request ke method ini.
     */
    public static function prosesTambah(Request $request)
    {
        $mapel = self::tambah([
            'kode_mapel' => $request->input('kode_mapel'),
            'nama_pelajaran' => $request->input('nama_pelajaran'),
            'kkm' => $request->input('kkm'),
        ]);

        return [
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan.',
            'data' => self::formatData($mapel),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES UBAH
    |--------------------------------------------------------------------------
    */

    /**
     * Menangani seluruh proses update mata pelajaran.
     */
    public static function prosesUbah($id, Request $request)
    {
        $mapel = self::ubah($id, [
            'kode_mapel' => $request->input('kode_mapel'),
            'nama_pelajaran' => $request->input('nama_pelajaran'),
            'kkm' => $request->input('kkm'),
        ]);

        return [
            'success' => true,
            'message' => 'Mata pelajaran berhasil diperbarui.',
            'data' => self::formatData($mapel),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PROSES HAPUS
    |--------------------------------------------------------------------------
    */

    /**
     * Menangani seluruh proses hapus mata pelajaran.
     */
    public static function prosesHapus($id)
    {
        self::hapus($id);

        return [
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus.',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT DATA
    |--------------------------------------------------------------------------
    */

    /**
     * Menentukan data yang dikirim kembali
     * ke JavaScript setelah proses CRUD.
     */
    private static function formatData(self $mapel)
    {
        return [
            'id' => $mapel->id,
            'kode_mapel' => $mapel->kode_mapel,
            'nama_pelajaran' => $mapel->nama_pelajaran,
            'kkm' => $mapel->kkm,
        ];
    }
}