<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class GuruMapel extends Model
{
    use HasFactory;

    protected $table = 'guru_mapel';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'guru_id',
        'mapel_id',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'guru_mapel_id');
    }

    /**
     * Data yang dibutuhkan halaman data guru.
     */
    public static function dataHalaman(): array
    {
        return [
            'guruMapel' => self::semua(),
            'daftarGuru' => self::daftarGuru(),
            'daftarMataPelajaran' => self::daftarMataPelajaran(),
        ];
    }

    /**
     * Semua penugasan guru mata pelajaran.
     */
    public static function semua()
    {
        return self::with(['guru', 'mataPelajaran'])
            ->orderBy('id')
            ->get();
    }

    /**
     * Daftar user yang memiliki role guru.
     */
    public static function daftarGuru()
    {
        return User::where('role', 'guru')
            ->orderBy('nama_lengkap')
            ->orderBy('id')
            ->get();
    }

    /**
     * Daftar mata pelajaran untuk pilihan form.
     */
    public static function daftarMataPelajaran()
    {
        return MataPelajaran::orderBy('nama_pelajaran')
            ->orderBy('id')
            ->get();
    }

    /**
     * Memastikan user yang dipilih adalah guru.
     */
    public static function pastikanGuruValid(int $guruId): bool
    {
        return User::where('id', $guruId)
            ->where('role', 'guru')
            ->exists();
    }

    /**
     * Memastikan kombinasi guru dan mata pelajaran tidak duplikat.
     */
    public static function sudahTerdaftar(
        int $guruId,
        int $mapelId,
        ?int $excludeId = null
    ): bool {
        $query = self::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId);

        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        return $query->exists();
    }

    /**
     * Validasi aturan bisnis sebelum tambah atau ubah data.
     */
    protected static function validasiBisnis(array $data, ?int $excludeId = null): void
    {
        $guruId = (int) $data['guru_id'];
        $mapelId = (int) $data['mapel_id'];

        if (!self::pastikanGuruValid($guruId)) {
            throw ValidationException::withMessages([
                'guru_id' => 'User yang dipilih bukan merupakan guru.',
            ]);
        }

        if (self::sudahTerdaftar($guruId, $mapelId, $excludeId)) {
            throw ValidationException::withMessages([
                'mapel_id' => 'Guru tersebut sudah terdaftar pada mata pelajaran yang dipilih.',
            ]);
        }
    }

    /**
     * Menambahkan penugasan guru mata pelajaran.
     */
    public static function tambah(array $data): self
    {
        self::validasiBisnis($data);

        return self::create([
            'guru_id' => $data['guru_id'],
            'mapel_id' => $data['mapel_id'],
        ]);
    }

    /**
     * Mengubah penugasan guru mata pelajaran.
     */
    public static function ubah(int $id, array $data): self
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
     * Menghapus penugasan guru mata pelajaran.
     */
    public static function hapus(int $id): bool
    {
        return self::findOrFail($id)->delete();
    }
}
