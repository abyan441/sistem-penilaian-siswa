<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class Nilai extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $table = 'nilai';
    public $timestamps = false;

    protected $fillable = [
        'siswa_id',
        'guru_mapel_id',
        'semester',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
    ];

    protected function casts(): array
    {
        return [
            'siswa_id' => 'integer',
            'guru_mapel_id' => 'integer',
            'semester' => 'integer',
            'nilai_tugas' => 'decimal:2',
            'nilai_uts' => 'decimal:2',
            'nilai_uas' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function guruMapel()
    {
        return $this->belongsTo(GuruMapel::class, 'guru_mapel_id');
    }

    public function getMataPelajaranAttribute()
    {
        return $this->guruMapel?->mataPelajaran;
    }

    public static function resolveSemester($semester): int
    {
        $semester = (int) $semester;
        return in_array($semester, [1, 2], true) ? $semester : 1;
    }

    public static function bolehMengelolaNilai(?string $role): bool
    {
        return !in_array($role, ['admin', 'kepala_sekolah'], true);
    }

    public static function readOnlyUntukRole(?string $role): bool
    {
        return in_array($role, ['admin', 'kepala_sekolah'], true);
    }

    public static function pastikanDapatMengelolaNilai(?string $role): void
    {
        if (!static::bolehMengelolaNilai($role)) {
            throw new InvalidArgumentException('Akun ini hanya dapat melihat nilai dan tidak dapat mengubah atau menyimpan nilai.');
        }
    }

    public static function untukRaport(int $siswaId, int $semester): Collection
    {
        self::pastikanSemester($semester);

        return self::query()
            ->with(['guruMapel.mataPelajaran'])
            ->where('siswa_id', $siswaId)
            ->where('semester', $semester)
            ->get()
            ->sortBy(fn ($item) => mb_strtolower($item->guruMapel?->mataPelajaran?->nama_pelajaran ?? ''))
            ->values();
    }

    public static function rataRataRaport(Collection $nilai): float
    {
        return $nilai->isNotEmpty()
            ? round($nilai->avg(fn ($item) => (float) $item->nilai_akhir), 2)
            : 0;
    }

    public static function predikat($nilaiAkhir): string
    {
        $nilaiAkhir = (float) $nilaiAkhir;
        if ($nilaiAkhir >= 90) return 'A';
        if ($nilaiAkhir >= 80) return 'B';
        if ($nilaiAkhir >= 70) return 'C';
        return 'D';
    }

    public static function deskripsiPredikat($nilaiAkhir): string
    {
        return match (self::predikat($nilaiAkhir)) {
            'A' => 'Sangat Baik',
            'B' => 'Baik',
            'C' => 'Cukup',
            default => 'Kurang',
        };
    }

    public static function dataRaportSiswa(int $siswaId, int $semester): Collection
    {
        return self::untukRaport($siswaId, $semester)->map(function ($item) {
            $item->predikat = self::predikat($item->nilai_akhir);
            $item->deskripsi_predikat = self::deskripsiPredikat($item->nilai_akhir);
            return $item;
        });
    }

    public static function kelasWaliGuru($guruId, $firstOnly = false)
    {
        $query = Kelas::query()
            ->where('wali_kelas_id', $guruId)
            ->orderByDesc('tahun_ajaran')
            ->orderBy('nama_kelas');

        return $firstOnly ? $query->first() : $query->get();
    }

    public static function pastikanKelasWaliGuru($guruId)
    {
        $kelas = self::kelasWaliGuru($guruId, true);
        if (!$kelas) {
            throw new InvalidArgumentException('Anda belum memiliki kelas yang diampu sebagai wali kelas.');
        }
        return $kelas;
    }

    public static function kelasAdmin($kelasId)
    {
        if (!$kelasId) throw new InvalidArgumentException('Silakan pilih kelas terlebih dahulu.');
        $kelas = Kelas::query()->find($kelasId);
        if (!$kelas) throw new InvalidArgumentException('Kelas tidak ditemukan.');
        return $kelas;
    }

    public static function semuaKelas()
    {
        return Kelas::query()->orderByDesc('tahun_ajaran')->orderBy('nama_kelas')->get();
    }

    public static function daftarSiswa($kelasId): Collection
    {
        return Siswa::query()->where('kelas_id', $kelasId)->orderBy('nama_siswa')->orderBy('nisn')->get();
    }

    public static function semuaSiswa(): Collection
    {
        return Siswa::query()->with('kelas')->orderBy('kelas_id')->orderBy('nama_siswa')->orderBy('nisn')->get();
    }

    public static function pastikanSiswaValid(Collection $siswaIds): void
    {
        if ($siswaIds->isEmpty()) throw new InvalidArgumentException('Data siswa tidak boleh kosong.');
        if ($siswaIds->count() !== $siswaIds->unique()->count()) throw new InvalidArgumentException('Terdapat data siswa yang duplikat.');
        if (Siswa::query()->whereIn('id', $siswaIds)->count() !== $siswaIds->count()) {
            throw new InvalidArgumentException('Terdapat siswa yang tidak ditemukan.');
        }
    }

    public static function semuaMataPelajaran()
    {
        return MataPelajaran::query()->orderBy('nama_pelajaran')->orderBy('id')->get();
    }

    public static function mataPelajaranGuru(int $guruId)
    {
        return GuruMapel::query()
            ->with('mataPelajaran')
            ->where('guru_id', $guruId)
            ->whereHas('mataPelajaran')
            ->orderBy('id')
            ->get();
    }

    public static function dataHalaman(int $guruId): array
    {
        $penugasan = static::mataPelajaranGuru($guruId);
        return [
            'kelas' => null,
            'kelasOptions' => static::semuaKelas(),
            'mataPelajaran' => $penugasan->pluck('mataPelajaran')->filter()->unique('id')->values(),
        ];
    }

    public static function dataHalamanAdmin(): array
    {
        $kelasOptions = static::semuaKelas();
        return [
            'kelas' => $kelasOptions->first(),
            'kelasOptions' => $kelasOptions,
            'tahunAjaranOptions' => $kelasOptions->pluck('tahun_ajaran')->unique()->values(),
            'mataPelajaran' => static::semuaMataPelajaran(),
        ];
    }

    public static function guruMapelGuruMapel($guruId, $mapelId)
    {
        return GuruMapel::query()->where('guru_id', $guruId)->where('mapel_id', $mapelId)->first();
    }

    public static function pastikanGuruMapel(int $guruId, int $mapelId): GuruMapel
    {
        $guruMapel = static::guruMapelGuruMapel($guruId, $mapelId);
        if (!$guruMapel) throw new InvalidArgumentException('Anda tidak memiliki penugasan untuk mata pelajaran tersebut.');
        return $guruMapel;
    }

    public static function pastikanSemester($semester): void
    {
        if (!in_array((string) $semester, ['1', '2'], true)) throw new InvalidArgumentException('Semester hanya boleh 1 atau 2.');
    }

    public static function validasiNilai($nilai, $namaNilai): float
    {
        if (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) throw new InvalidArgumentException("{$namaNilai} harus berada di antara 0 sampai 100.");
        return round((float) $nilai, 2);
    }

    public static function hitungNilaiAkhir($nilaiTugas, $nilaiUts, $nilaiUas): float
    {
        return round(
            static::validasiNilai($nilaiTugas, 'Nilai tugas') * 0.30
            + static::validasiNilai($nilaiUts, 'Nilai UTS') * 0.30
            + static::validasiNilai($nilaiUas, 'Nilai UAS') * 0.40,
            2
        );
    }

    private static function formatSiswaDenganNilai(Collection $siswa, Collection $nilai): Collection
    {
        return $siswa->values()->map(function ($siswa, $index) use ($nilai) {
            $nilaiSiswa = $nilai->get($siswa->id);
            $tugas = $nilaiSiswa ? (float) $nilaiSiswa->nilai_tugas : 0;
            $uts = $nilaiSiswa ? (float) $nilaiSiswa->nilai_uts : 0;
            $uas = $nilaiSiswa ? (float) $nilaiSiswa->nilai_uas : 0;
            $akhir = $nilaiSiswa && $nilaiSiswa->nilai_akhir !== null ? (float) $nilaiSiswa->nilai_akhir : static::hitungNilaiAkhir($tugas, $uts, $uas);

            return [
                'id' => $siswa->id,
                'nisn' => $siswa->nisn,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas_id' => $siswa->kelas_id,
                'kelas' => $siswa->kelas?->nama_kelas,
                'tahun_ajaran' => $siswa->kelas?->tahun_ajaran,
                'nomor' => $index + 1,
                'nilai_id' => $nilaiSiswa?->id,
                'nilai_tugas' => $tugas,
                'nilai_uts' => $uts,
                'nilai_uas' => $uas,
                'nilai_akhir' => $akhir,
                'predikat' => static::predikat($akhir),
            ];
        });
    }

    public static function dataNilai($guruId, $semester, $mapelId, $kelasId = null, $tahunAjaran = null): array
    {
        static::pastikanSemester($semester);
        $guruMapel = static::pastikanGuruMapel((int) $guruId, (int) $mapelId);

        $query = Siswa::query()->with('kelas');
        if ($kelasId) $query->where('kelas_id', $kelasId);
        if ($tahunAjaran) $query->whereHas('kelas', fn ($q) => $q->where('tahun_ajaran', $tahunAjaran));

        $siswa = $query->orderBy('kelas_id')->orderBy('nama_siswa')->orderBy('nisn')->get();
        $nilai = static::query()
            ->where('guru_mapel_id', $guruMapel->id)
            ->where('semester', $semester)
            ->whereIn('siswa_id', $siswa->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        $kelasName = 'Semua Kelas';
        if ($kelasId) {
            try {
                $kelas = Kelas::findOrFail($kelasId);
                $kelasName = $kelas->nama_kelas;
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                throw new InvalidArgumentException("Kelas dengan ID {$kelasId} tidak ditemukan.");
            }
        }

        return [
            'kelas_id' => $kelasId ? (int) $kelasId : null,
            'kelas' => $kelasName,
            'tahun_ajaran' => $tahunAjaran ?: 'Semua Tahun Ajaran',
            'semester' => (int) $semester,
            'mapel_id' => (int) $mapelId,
            'guru_mapel_id' => (int) $guruMapel->id,
            'siswa' => static::formatSiswaDenganNilai($siswa, $nilai)->all(),
        ];
    }

    public static function dataNilaiAdmin($kelasId, $tahunAjaran, $semester, $mapelId): array
    {
        static::pastikanSemester($semester);
        if (!$mapelId || !MataPelajaran::query()->find($mapelId)) throw new InvalidArgumentException('Mata pelajaran tidak ditemukan.');

        $query = Siswa::query()->with('kelas');
        if ($kelasId) $query->where('kelas_id', $kelasId);
        if ($tahunAjaran) $query->whereHas('kelas', fn ($q) => $q->where('tahun_ajaran', $tahunAjaran));
        $siswa = $query->orderBy('kelas_id')->orderBy('nama_siswa')->orderBy('nisn')->get();

        $guruMapelIds = GuruMapel::query()->where('mapel_id', $mapelId)->pluck('id');
        $nilai = static::query()
            ->where('semester', $semester)
            ->whereIn('guru_mapel_id', $guruMapelIds)
            ->whereIn('siswa_id', $siswa->pluck('id'))
            ->orderBy('id')
            ->get()
            ->keyBy('siswa_id');

        $kelasName = 'Semua Kelas';
        if ($kelasId) {
            try {
                $kelas = Kelas::findOrFail($kelasId);
                $kelasName = $kelas->nama_kelas;
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                throw new InvalidArgumentException("Kelas dengan ID {$kelasId} tidak ditemukan.");
            }
        }

        return [
            'kelas_id' => $kelasId ? (int) $kelasId : null,
            'kelas' => $kelasName,
            'tahun_ajaran' => $tahunAjaran ?: 'Semua Tahun Ajaran',
            'semester' => (int) $semester,
            'mapel_id' => (int) $mapelId,
            'guru_mapel_id' => null,
            'siswa' => static::formatSiswaDenganNilai($siswa, $nilai)->all(),
        ];
    }

    public static function simpanNilai($guruId, $semester, $mapelId, array $dataNilai): void
    {
        static::pastikanSemester($semester);
        $guruMapel = static::pastikanGuruMapel((int) $guruId, (int) $mapelId);
        if (empty($dataNilai)) throw new InvalidArgumentException('Data nilai tidak boleh kosong.');
        static::pastikanSiswaValid(collect($dataNilai)->pluck('siswa_id'));

        DB::transaction(function () use ($dataNilai, $guruMapel, $semester) {
            foreach ($dataNilai as $data) {
                $tugas = static::validasiNilai($data['nilai_tugas'], 'Nilai tugas');
                $uts = static::validasiNilai($data['nilai_uts'], 'Nilai UTS');
                $uas = static::validasiNilai($data['nilai_uas'], 'Nilai UAS');

                static::updateOrCreate(
                    [
                        'siswa_id' => $data['siswa_id'],
                        'guru_mapel_id' => $guruMapel->id,
                        'semester' => $semester,
                    ],
                    [
                        'nilai_tugas' => $tugas,
                        'nilai_uts' => $uts,
                        'nilai_uas' => $uas,
                        'nilai_akhir' => static::hitungNilaiAkhir($tugas, $uts, $uas),
                    ]
                );
            }
        });
    }

    public static function hapus(int $id): bool
    {
        $nilai = static::query()->findOrFail($id);

        return (bool) $nilai->delete();
    }

    public static function perkembanganDashboard(): array
    {
        $data = static::query()
            ->join('siswa', 'siswa.id', '=', 'nilai.siswa_id')
            ->join('kelas', 'kelas.id', '=', 'siswa.kelas_id')
            ->whereNotNull('nilai.nilai_akhir')
            ->select('kelas.tahun_ajaran', DB::raw('AVG(nilai.nilai_akhir) as rata_rata'))
            ->groupBy('kelas.tahun_ajaran')
            ->orderBy('kelas.tahun_ajaran')
            ->get();

        return [
            'labels' => $data->pluck('tahun_ajaran')->values()->all(),
            'values' => $data->map(fn ($item) => round((float) $item->rata_rata, 2))->values()->all(),
        ];
    }

    public static function aktivitasTerbaru(int $limit = 6): Collection
    {
        return static::query()
            ->with(['siswa.kelas', 'guruMapel.guru', 'guruMapel.mataPelajaran'])
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
