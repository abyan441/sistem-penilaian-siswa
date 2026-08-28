<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class Nilai extends Model
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

    public static function kelasWaliGuru($guruId)
    {
        return Kelas::query()
            ->where('wali_kelas_id', $guruId)
            ->orderBy('tahun_ajaran', 'desc')
            ->orderBy('nama_kelas')
            ->get();
    }

    public static function kelasAktifGuru($guruId)
    {
        return static::kelasWaliGuru($guruId)->first();
    }

    public static function pastikanKelasWaliGuru($guruId)
    {
        $kelas = static::kelasAktifGuru($guruId);

        if (!$kelas) {
            throw new InvalidArgumentException(
                'Anda belum memiliki kelas yang diampu sebagai wali kelas.'
            );
        }

        return $kelas;
    }

    public static function kelasWali($guruId, $kelasId)
    {
        return Kelas::query()
            ->where('id', $kelasId)
            ->where('wali_kelas_id', $guruId)
            ->first();
    }

    public static function pastikanKelasWali($guruId, $kelasId)
    {
        $kelas = static::kelasWali($guruId, $kelasId);

        if (!$kelas) {
            throw new InvalidArgumentException(
                'Anda tidak memiliki akses ke kelas tersebut.'
            );
        }

        return $kelas;
    }

    public static function daftarSiswa($kelasId): Collection
    {
        return Siswa::query()
            ->where('kelas_id', $kelasId)
            ->orderBy('nama_siswa')
            ->orderBy('nisn')
            ->get();
    }

    public static function pastikanSiswaDalamKelas($kelasId, Collection $siswaIds): void
    {
        if ($siswaIds->isEmpty()) {
            throw new InvalidArgumentException('Data siswa tidak boleh kosong.');
        }

        if ($siswaIds->count() !== $siswaIds->unique()->count()) {
            throw new InvalidArgumentException('Terdapat data siswa yang duplikat.');
        }

        $jumlahValid = Siswa::query()
            ->where('kelas_id', $kelasId)
            ->whereIn('id', $siswaIds)
            ->count();

        if ($jumlahValid !== $siswaIds->count()) {
            throw new InvalidArgumentException(
                'Terdapat siswa yang tidak sesuai dengan kelas wali Anda.'
            );
        }
    }

    public static function semuaMataPelajaran()
    {
        return MataPelajaran::query()
            ->orderBy('nama_pelajaran')
            ->orderBy('id')
            ->get();
    }

    public static function guruMapelGuruMapel($guruId, $mapelId)
    {
        return GuruMapel::query()
            ->where('guru_id', $guruId)
            ->where('mapel_id', $mapelId)
            ->first();
    }

    public static function guruMapelUntukInput($guruId, $mapelId)
    {
        $guru = User::query()
            ->where('id', $guruId)
            ->where('role', 'guru')
            ->first();

        if (!$guru) {
            throw new InvalidArgumentException('Akun guru tidak valid.');
        }

        $mataPelajaran = MataPelajaran::query()->find($mapelId);

        if (!$mataPelajaran) {
            throw new InvalidArgumentException('Mata pelajaran tidak ditemukan.');
        }

        return GuruMapel::firstOrCreate([
            'guru_id' => $guruId,
            'mapel_id' => $mapelId,
        ]);
    }

    public static function pastikanSemester($semester): void
    {
        if (!in_array((string) $semester, ['1', '2'], true)) {
            throw new InvalidArgumentException('Semester hanya boleh 1 atau 2.');
        }
    }

    public static function validasiNilai($nilai, $namaNilai): float
    {
        if (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
            throw new InvalidArgumentException(
                "{$namaNilai} harus berada di antara 0 sampai 100."
            );
        }

        return round((float) $nilai, 2);
    }

    public static function hitungNilaiAkhir($nilaiTugas, $nilaiUts, $nilaiUas): float
    {
        $nilaiTugas = static::validasiNilai($nilaiTugas, 'Nilai tugas');
        $nilaiUts = static::validasiNilai($nilaiUts, 'Nilai UTS');
        $nilaiUas = static::validasiNilai($nilaiUas, 'Nilai UAS');

        return round(
            ($nilaiTugas * 0.30) +
            ($nilaiUts * 0.30) +
            ($nilaiUas * 0.40),
            2
        );
    }

    public static function predikat($nilaiAkhir): string
    {
        $nilaiAkhir = (float) $nilaiAkhir;

        if ($nilaiAkhir >= 90) return 'A';
        if ($nilaiAkhir >= 80) return 'B';
        if ($nilaiAkhir >= 70) return 'C';

        return 'D';
    }

    public static function dataHalaman($guruId)
    {
        $kelas = static::pastikanKelasWaliGuru($guruId);

        return [
            'kelas' => $kelas,
            'mataPelajaran' => static::semuaMataPelajaran(),
        ];
    }

    public static function dataNilai($guruId, $semester, $mapelId): array
    {
        $kelas = static::pastikanKelasWaliGuru($guruId);
        static::pastikanSemester($semester);

        $mataPelajaran = MataPelajaran::query()->find($mapelId);

        if (!$mataPelajaran) {
            throw new InvalidArgumentException('Mata pelajaran tidak ditemukan.');
        }

        $siswa = static::daftarSiswa($kelas->id);
        $guruMapel = static::guruMapelGuruMapel($guruId, $mapelId);
        $nilai = collect();

        if ($guruMapel) {
            $nilai = static::query()
                ->where('guru_mapel_id', $guruMapel->id)
                ->where('semester', $semester)
                ->whereIn('siswa_id', $siswa->pluck('id'))
                ->get()
                ->keyBy('siswa_id');
        }

        $dataSiswa = $siswa->values()->map(function ($siswa, $index) use ($nilai) {
            $nilaiSiswa = $nilai->get($siswa->id);
            $tugas = $nilaiSiswa ? (float) $nilaiSiswa->nilai_tugas : 0;
            $uts = $nilaiSiswa ? (float) $nilaiSiswa->nilai_uts : 0;
            $uas = $nilaiSiswa ? (float) $nilaiSiswa->nilai_uas : 0;
            $akhir = $nilaiSiswa && $nilaiSiswa->nilai_akhir !== null
                ? (float) $nilaiSiswa->nilai_akhir
                : static::hitungNilaiAkhir($tugas, $uts, $uas);

            return [
                'id' => $siswa->id,
                'nisn' => $siswa->nisn,
                'nama_siswa' => $siswa->nama_siswa,
                'nomor' => $index + 1,
                'nilai_id' => $nilaiSiswa?->id,
                'nilai_tugas' => $tugas,
                'nilai_uts' => $uts,
                'nilai_uas' => $uas,
                'nilai_akhir' => $akhir,
                'predikat' => static::predikat($akhir),
            ];
        });

        return [
            'kelas_id' => $kelas->id,
            'kelas' => $kelas->nama_kelas,
            'tahun_ajaran' => $kelas->tahun_ajaran,
            'semester' => (int) $semester,
            'mapel_id' => (int) $mapelId,
            'guru_mapel_id' => $guruMapel ? (int) $guruMapel->id : null,
            'siswa' => $dataSiswa->values()->all(),
        ];
    }

    public static function simpanNilai($guruId, $semester, $mapelId, array $dataNilai): void
    {
        $kelas = static::pastikanKelasWaliGuru($guruId);
        static::pastikanSemester($semester);

        if (empty($dataNilai)) {
            throw new InvalidArgumentException('Data nilai tidak boleh kosong.');
        }

        $guruMapel = static::guruMapelUntukInput($guruId, $mapelId);
        $siswaIds = collect($dataNilai)->pluck('siswa_id');

        static::pastikanSiswaDalamKelas($kelas->id, $siswaIds);

        DB::transaction(function () use ($dataNilai, $guruMapel, $semester) {
            foreach ($dataNilai as $data) {
                $nilaiTugas = static::validasiNilai($data['nilai_tugas'], 'Nilai tugas');
                $nilaiUts = static::validasiNilai($data['nilai_uts'], 'Nilai UTS');
                $nilaiUas = static::validasiNilai($data['nilai_uas'], 'Nilai UAS');
                $nilaiAkhir = static::hitungNilaiAkhir($nilaiTugas, $nilaiUts, $nilaiUas);

                static::updateOrCreate(
                    [
                        'siswa_id' => $data['siswa_id'],
                        'guru_mapel_id' => $guruMapel->id,
                        'semester' => $semester,
                    ],
                    [
                        'nilai_tugas' => $nilaiTugas,
                        'nilai_uts' => $nilaiUts,
                        'nilai_uas' => $nilaiUas,
                        'nilai_akhir' => $nilaiAkhir,
                    ]
                );
            }
        });
    }

    /**
     * Data perkembangan rata-rata nilai untuk Dashboard.
     */
    public static function perkembanganDashboard(): array
    {
        $data = static::query()
            ->join('siswa', 'siswa.id', '=', 'nilai.siswa_id')
            ->join('kelas', 'kelas.id', '=', 'siswa.kelas_id')
            ->whereNotNull('nilai.nilai_akhir')
            ->select(
                'kelas.tahun_ajaran',
                DB::raw('AVG(nilai.nilai_akhir) as rata_rata')
            )
            ->groupBy('kelas.tahun_ajaran')
            ->orderBy('kelas.tahun_ajaran')
            ->get();

        return [
            'labels' => $data->pluck('tahun_ajaran')->values()->all(),
            'values' => $data
                ->map(fn ($item) => round((float) $item->rata_rata, 2))
                ->values()
                ->all(),
        ];
    }

    /**
     * Aktivitas nilai terbaru untuk Dashboard.
     */
    public static function aktivitasTerbaru(int $limit = 6): Collection
    {
        return static::query()
            ->with([
                'siswa.kelas',
                'guruMapel.guru',
                'guruMapel.mataPelajaran',
            ])
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
