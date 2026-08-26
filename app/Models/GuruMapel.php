<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class GuruMapel extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     */
    protected $table = 'guru_mapel';

    /**
     * Primary key.
     */
    protected $primaryKey = 'id';

    /**
     * Tabel tidak menggunakan created_at dan updated_at.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi melalui mass assignment.
     */
    protected $fillable = [
        'guru_id',
        'mapel_id',
    ];

    /**
     * =====================================================
     * RELASI KE GURU
     * =====================================================
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * =====================================================
     * RELASI KE MATA PELAJARAN
     * =====================================================
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    /**
     * =====================================================
     * RELASI KE NILAI
     * =====================================================
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'guru_mapel_id');
    }

    /**
     * =====================================================
     * DATA HALAMAN
     * =====================================================
     */
    public static function dataHalaman()
    {
        return [
            'guruMapel' => self::semua(),
            'daftarGuru' => self::daftarGuru(),
            'daftarMataPelajaran' => self::daftarMataPelajaran(),
        ];
    }

    /**
     * =====================================================
     * SEMUA DATA GURU + MATA PELAJARAN
     * =====================================================
     */
    public static function semua()
    {
        return self::with([
            'guru',
            'mataPelajaran',
        ])
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * =====================================================
     * DAFTAR GURU
     * =====================================================
     *
     * Hanya user dengan role guru yang ditampilkan.
     */
    public static function daftarGuru()
    {
        return User::where('role', 'guru')
            ->orderBy('nama_lengkap', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * =====================================================
     * DAFTAR MATA PELAJARAN
     * =====================================================
     */
    public static function daftarMataPelajaran()
    {
        return MataPelajaran::orderBy('nama_pelajaran', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * =====================================================
     * VALIDASI GURU
     * =====================================================
     *
     * Memastikan user yang dipilih benar-benar memiliki
     * role sebagai guru.
     */
    public static function pastikanGuruValid($guruId)
    {
        return User::where('id', $guruId)
            ->where('role', 'guru')
            ->exists();
    }

    /**
     * =====================================================
     * CEK DUPLIKASI
     * =====================================================
     *
     * Memastikan guru yang sama tidak terdaftar pada
     * mata pelajaran yang sama lebih dari satu kali.
     *
     * Parameter $excludeId digunakan ketika edit data.
     */
    public static function sudahTerdaftar($guruId, $mapelId, $excludeId = null)
    {
        $query = self::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * =====================================================
     * VALIDASI DATA GURU + MAPEL
     * =====================================================
     */
    protected static function validasiBisnis($data, $excludeId = null)
    {
        if (!self::pastikanGuruValid($data['guru_id'])) {
            throw ValidationException::withMessages([
                'guru_id' => 'User yang dipilih bukan merupakan guru.',
            ]);
        }

        if (self::sudahTerdaftar(
            $data['guru_id'],
            $data['mapel_id'],
            $excludeId
        )) {
            throw ValidationException::withMessages([
                'mapel_id' => 'Guru tersebut sudah terdaftar pada mata pelajaran yang dipilih.',
            ]);
        }
    }

    /**
     * =====================================================
     * TAMBAH GURU MATA PELAJARAN
     * =====================================================
     */
    public static function tambah($data)
    {
        self::validasiBisnis($data);

        return self::create([
            'guru_id' => $data['guru_id'],
            'mapel_id' => $data['mapel_id'],
        ]);
    }

    /**
     * =====================================================
     * UBAH GURU MATA PELAJARAN
     * =====================================================
     */
    public static function ubah($id, $data)
    {
        $guruMapel = self::findOrFail($id);

        self::validasiBisnis($data, $id);

        $guruMapel->update([
            'guru_id' => $data['guru_id'],
            'mapel_id' => $data['mapel_id'],
        ]);

        return $guruMapel;
    }

    /**
     * =====================================================
     * HAPUS GURU MATA PELAJARAN
     * =====================================================
     */
    public static function hapus($id)
    {
        $guruMapel = self::findOrFail($id);

        return $guruMapel->delete();
    }
}