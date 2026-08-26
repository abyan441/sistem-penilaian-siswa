<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     */
    protected $table = 'mata_pelajaran';

    /**
     * Primary key.
     */
    protected $primaryKey = 'id';

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'kode_mapel',
        'nama_pelajaran',
        'kkm',
    ];

    /**
     * Tabel tidak menggunakan created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Relasi ke guru_mapel.
     */
    public function guruMapel()
    {
        return $this->hasMany(GuruMapel::class, 'mapel_id');
    }

    /**
     * =====================================================
     * DATA HALAMAN
     * =====================================================
     *
     * Mengambil seluruh data yang dibutuhkan halaman
     * Mata Pelajaran.
     *
     * Pengurutan mata pelajaran berdasarkan nama A-Z.
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

    /**
     * =====================================================
     * MENGAMBIL SELURUH MATA PELAJARAN
     * =====================================================
     *
     * Diurutkan berdasarkan nama mata pelajaran A-Z.
     *
     * Jika terdapat nama yang sama, data dengan ID
     * yang lebih kecil akan ditampilkan terlebih dahulu.
     */
    public static function semua()
    {
        return self::orderBy('nama_pelajaran', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * =====================================================
     * TOTAL MATA PELAJARAN
     * =====================================================
     */
    public static function total()
    {
        return self::count();
    }

    /**
     * =====================================================
     * RATA-RATA KKM
     * =====================================================
     */
    public static function rataRataKkm()
    {
        return self::avg('kkm');
    }

    /**
     * =====================================================
     * KKM TERENDAH
     * =====================================================
     */
    public static function kkmTerendah()
    {
        return self::min('kkm');
    }

    /**
     * =====================================================
     * TAMBAH MATA PELAJARAN
     * =====================================================
     */
    public static function tambah($data)
    {
        return self::create([
            'kode_mapel' => strtoupper(trim($data['kode_mapel'])),
            'nama_pelajaran' => trim($data['nama_pelajaran']),
            'kkm' => $data['kkm'],
        ]);
    }

    /**
     * =====================================================
     * UBAH MATA PELAJARAN
     * =====================================================
     */
    public static function ubah($id, $data)
    {
        $mapel = self::findOrFail($id);

        $mapel->update([
            'kode_mapel' => strtoupper(trim($data['kode_mapel'])),
            'nama_pelajaran' => trim($data['nama_pelajaran']),
            'kkm' => $data['kkm'],
        ]);

        return $mapel;
    }

    /**
     * =====================================================
     * HAPUS MATA PELAJARAN
     * =====================================================
     */
    public static function hapus($id)
    {
        $mapel = self::findOrFail($id);

        return $mapel->delete();
    }
}