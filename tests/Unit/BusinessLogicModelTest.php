<?php

namespace Tests\Unit;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BusinessLogicModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('username', 15)->nullable();
                $table->string('password')->nullable();
                $table->string('nama_lengkap', 40)->nullable();
                $table->string('email', 30)->nullable();
                $table->string('role', 20)->nullable();
                $table->string('status', 20)->nullable();
                $table->string('nip', 20)->nullable();
            });
        }

        if (!Schema::hasTable('mata_pelajaran')) {
            Schema::create('mata_pelajaran', function (Blueprint $table) {
                $table->id();
                $table->string('kode_mapel', 5)->nullable();
                $table->string('nama_pelajaran', 45)->nullable();
                $table->integer('kkm')->nullable();
            });
        }

        if (!Schema::hasTable('kelas')) {
            Schema::create('kelas', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kelas', 10)->nullable();
                $table->string('tahun_ajaran', 9)->nullable();
                $table->unsignedBigInteger('wali_kelas_id')->nullable();
            });
        }

        if (!Schema::hasTable('siswa')) {
            Schema::create('siswa', function (Blueprint $table) {
                $table->id();
                $table->string('nisn', 15)->nullable();
                $table->string('nama_siswa', 40)->nullable();
                $table->string('jenis_kelamin', 1)->nullable();
                $table->unsignedBigInteger('kelas_id')->nullable();
            });
        }

        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }

        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 15)->nullable()->after('name');
                $table->string('nama_lengkap', 40)->nullable()->after('password');
                $table->string('role', 20)->nullable()->after('nama_lengkap');
                $table->string('status', 20)->nullable()->after('role');
                $table->string('nip', 20)->nullable()->after('status');
            });
        }
    }

    public function test_mata_pelajaran_model_validates_business_rules(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Kode mata pelajaran wajib diisi.');

        MataPelajaran::tambah([
            'kode_mapel' => '',
            'nama_pelajaran' => '',
            'kkm' => 150,
        ]);
    }

    public function test_siswa_model_validates_business_rules(): void
    {
        Kelas::query()->create([
            'nama_kelas' => '1A',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => 1,
        ]);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('NISN wajib diisi.');

        Siswa::tambah([
            'nisn' => '',
            'nama_siswa' => '',
            'jenis_kelamin' => 'X',
            'kelas_id' => 1,
        ]);
    }

    public function test_user_model_enforces_login_rules(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Guru Satu',
                'username' => 'guru1',
                'password' => Hash::make('rahasia123'),
                'nama_lengkap' => 'Guru Satu',
                'email' => 'guru1@test.com',
                'role' => 'guru',
                'status' => 'aktif',
                'nip' => '1234567890',
            ],
            [
                'name' => 'Guru Dua',
                'username' => 'guru2',
                'password' => Hash::make('rahasia123'),
                'nama_lengkap' => 'Guru Dua',
                'email' => 'guru2@test.com',
                'role' => 'guru',
                'status' => 'tidak_aktif',
                'nip' => '1234567891',
            ],
        ]);

        $this->assertNotNull(User::loginBoleh('guru1@test.com', 'rahasia123'));
        $this->assertNull(User::loginBoleh('guru2@test.com', 'rahasia123'));
        $this->assertNull(User::loginBoleh('guru1@test.com', 'salah'));
    }

    public function test_user_model_rejects_duplicate_email_and_invalid_password_update(): void
    {
        $userA = User::query()->create([
            'name' => 'User A',
            'username' => 'usera',
            'password' => Hash::make('oldpass123'),
            'nama_lengkap' => 'User A',
            'email' => 'usera@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '1001',
        ]);

        User::query()->create([
            'name' => 'User B',
            'username' => 'userb',
            'password' => Hash::make('oldpass123'),
            'nama_lengkap' => 'User B',
            'email' => 'userb@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '1002',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Email tersebut sudah digunakan oleh pengguna lain.');

        User::ubahEmailDiri($userA, 'userb@test.com', 'userb@test.com', 'oldpass123');
    }

    public function test_kelas_model_controls_report_access_for_wali_guru(): void
    {
        $guru = User::query()->create([
            'name' => 'Guru Wali',
            'username' => 'waliguru',
            'password' => Hash::make('rahasia123'),
            'nama_lengkap' => 'Guru Wali',
            'email' => 'wali@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '2001',
        ]);

        $nonGuru = User::query()->create([
            'name' => 'Bukan Guru',
            'username' => 'nonguru',
            'password' => Hash::make('rahasia123'),
            'nama_lengkap' => 'Bukan Guru',
            'email' => 'nonguru@test.com',
            'role' => 'guru',
            'status' => 'tidak_aktif',
            'nip' => '2002',
        ]);

        Kelas::query()->create([
            'nama_kelas' => '7A',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $guru->id,
        ]);

        $this->assertTrue(Kelas::dapatAksesRaport($guru->id));
        $this->assertFalse(Kelas::dapatAksesRaport($nonGuru->id));
        $this->assertFalse(Kelas::dapatAksesRaport(999));
    }

    public function test_nilai_model_controls_edit_access_for_roles(): void
    {
        $this->assertTrue(Nilai::bolehMengelolaNilai('guru'));
        $this->assertFalse(Nilai::bolehMengelolaNilai('admin'));
        $this->assertFalse(Nilai::bolehMengelolaNilai('kepala_sekolah'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Akun ini hanya dapat melihat nilai dan tidak dapat mengubah atau menyimpan nilai.');

        Nilai::pastikanDapatMengelolaNilai('admin');
    }
}
