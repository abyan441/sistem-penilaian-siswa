<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use RuntimeException;

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

    public static function dataHalaman(): array
    {
        return [
            'guruMapel' => self::semua(),
            'daftarGuru' => self::daftarGuru(),
            'daftarMataPelajaran' => self::daftarMataPelajaran(),
        ];
    }

    public static function semua()
    {
        return self::with(['guru', 'mataPelajaran'])
            ->orderBy('id')
            ->get();
    }

    public static function daftarGuru()
    {
        return User::where('role', 'guru')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->orderBy('id')
            ->get();
    }

    public static function daftarMataPelajaran()
    {
        return MataPelajaran::orderBy('nama_pelajaran')
            ->orderBy('id')
            ->get();
    }

    public static function pastikanGuruValid(int $guruId): bool
    {
        return User::where('id', $guruId)
            ->where('role', 'guru')
            ->where('status', 'aktif')
            ->exists();
    }

    public static function pastikanMataPelajaranValid(int $mapelId): bool
    {
        return MataPelajaran::where('id', $mapelId)->exists();
    }

    public static function sudahTerdaftar(
        int $guruId,
        int $mapelId,
        ?int $excludeId = null
    ): bool {
        $query = self::where('guru_id', $guruId)
            ->where('mapel_id', $mapelId);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    protected static function validasiBisnis(array $data, ?int $excludeId = null): void
    {
        $guruId = filter_var($data['guru_id'] ?? null, FILTER_VALIDATE_INT);
        $mapelId = filter_var($data['mapel_id'] ?? null, FILTER_VALIDATE_INT);

        if ($guruId === false || $guruId === null) {
            throw ValidationException::withMessages([
                'guru_id' => 'Guru wajib dipilih dengan benar.',
            ]);
        }

        if ($mapelId === false || $mapelId === null) {
            throw ValidationException::withMessages([
                'mapel_id' => 'Mata pelajaran wajib dipilih dengan benar.',
            ]);
        }

        if (!self::pastikanGuruValid((int) $guruId)) {
            throw ValidationException::withMessages([
                'guru_id' => 'Guru yang dipilih tidak ditemukan atau sudah tidak aktif.',
            ]);
        }

        if (!self::pastikanMataPelajaranValid((int) $mapelId)) {
            throw ValidationException::withMessages([
                'mapel_id' => 'Mata pelajaran yang dipilih tidak ditemukan.',
            ]);
        }

        if (self::sudahTerdaftar((int) $guruId, (int) $mapelId, $excludeId)) {
            throw ValidationException::withMessages([
                'mapel_id' => 'Guru tersebut sudah terdaftar pada mata pelajaran yang dipilih.',
            ]);
        }
    }

    public static function tambah(array $data): self
    {
        self::validasiBisnis($data);

        return self::create([
            'guru_id' => $data['guru_id'],
            'mapel_id' => $data['mapel_id'],
        ]);
    }

    public static function ubah(int $id, array $data): self
    {
        $guruMapel = self::findOrFail($id);

        self::validasiBisnis($data, $id);

        $guruMapel->update([
            'guru_id' => $data['guru_id'],
            'mapel_id' => $data['mapel_id'],
        ]);

        return $guruMapel->fresh();
    }

    public static function hapus(int $id): bool
    {
        $guruMapel = self::query()
            ->withCount('nilai')
            ->findOrFail($id);

        if ($guruMapel->nilai_count > 0) {
            throw new RuntimeException(
                'Penugasan guru mata pelajaran tidak dapat dihapus karena sudah memiliki data nilai.'
            );
        }

        return (bool) $guruMapel->delete();
    }
}
