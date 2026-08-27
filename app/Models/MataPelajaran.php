<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    | CAST
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'kkm' => 'integer',
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
        return $this->hasMany(
            GuruMapel::class,
            'mapel_id'
        );
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
    public static function dataHalaman(): array
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
    | DATA
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil seluruh mata pelajaran
     * berdasarkan nama A-Z.
     */
    public static function semua()
    {
        return self::query()
            ->orderBy('nama_pelajaran', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | STATISTIK
    |--------------------------------------------------------------------------
    */

    /**
     * Menghitung jumlah mata pelajaran.
     */
    public static function total(): int
    {
        return self::query()->count();
    }


    /**
     * Menghitung rata-rata KKM.
     */
    public static function rataRataKkm(): ?float
    {
        return self::query()->avg('kkm');
    }


    /**
     * Mengambil nilai KKM terendah.
     */
    public static function kkmTerendah(): ?int
    {
        return self::query()->min('kkm');
    }
}