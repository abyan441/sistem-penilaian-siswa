<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use App\Models\GuruMapel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Integration test untuk memverifikasi bahwa arsitektur MVC yang baru
 * tetap menjaga business logic di model layer dan controller hanya
 * bertugas sebagai orchestrator/penghubung.
 */
class ArchitectureIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createAllTables();
    }

    private function createAllTables(): void
    {
        Schema::dropIfExists('users');
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

        Schema::dropIfExists('mata_pelajaran');
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 5)->nullable();
            $table->string('nama_pelajaran', 45)->nullable();
            $table->integer('kkm')->nullable();
        });

        Schema::dropIfExists('kelas');
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 10)->nullable();
            $table->string('tahun_ajaran', 9)->nullable();
            $table->unsignedBigInteger('wali_kelas_id')->nullable();
        });

        Schema::dropIfExists('siswa');
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 15)->nullable();
            $table->string('nama_siswa', 40)->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable();
        });

        Schema::dropIfExists('guru_mapel');
        Schema::create('guru_mapel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guru_id')->nullable();
            $table->unsignedBigInteger('mapel_id')->nullable();
        });

        Schema::dropIfExists('nilai');
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id')->nullable();
            $table->unsignedBigInteger('guru_mapel_id')->nullable();
            $table->string('tahun_ajaran', 9)->nullable();
            $table->integer('semester')->nullable();
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
        });
    }

    /**
     * Test: Guru tidak bisa login jika status tidak aktif.
     */
    public function test_guru_tidak_aktif_tidak_bisa_login(): void
    {
        User::query()->create([
            'name' => 'Guru Aktif',
            'username' => 'guru_aktif',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Aktif',
            'email' => 'guru_aktif@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '1001',
        ]);

        User::query()->create([
            'name' => 'Guru Tidak Aktif',
            'username' => 'guru_tidak_aktif',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Tidak Aktif',
            'email' => 'guru_tidak_aktif@test.com',
            'role' => 'guru',
            'status' => 'tidak_aktif',
            'nip' => '1002',
        ]);

        $this->assertNotNull(User::loginBoleh('guru_aktif@test.com', 'password123'));
        $this->assertNull(User::loginBoleh('guru_tidak_aktif@test.com', 'password123'));
    }

    public function test_akun_nonaktif_dikeluarkan_pada_request_berikutnya(): void
    {
        $user = User::query()->create([
            'name' => 'Guru Sesi',
            'username' => 'guru_sesi',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Sesi',
            'email' => 'guru_sesi@test.com',
            'role' => 'guru',
            'status' => 'aktif',
        ]);

        $user->update(['status' => 'tidak_aktif']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_akun_nonaktif_ditolak_pada_endpoint_login(): void
    {
        User::query()->create([
            'name' => 'Guru Nonaktif',
            'username' => 'guru_nonaktif',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Nonaktif',
            'email' => 'guru_nonaktif@test.com',
            'role' => 'guru',
            'status' => 'tidak_aktif',
        ]);

        $response = $this->post(route('login.process'), [
            'email' => 'guru_nonaktif@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect()
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_kepala_sekolah_tidak_melihat_kolom_aksi_pengguna(): void
    {
        $kepalaSekolah = User::query()->create([
            'name' => 'Kepala Sekolah',
            'username' => 'kepala_sekolah',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Kepala Sekolah',
            'email' => 'kepala@test.com',
            'role' => 'kepala_sekolah',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($kepalaSekolah)->get(route('pengguna'));

        $response->assertOk()
            ->assertDontSee('>Aksi<', false)
            ->assertDontSee('open-user-form', false)
            ->assertSee('Daftar Pengguna');
    }

    /**
     * Test: Mata pelajaran tidak bisa disimpan jika data tidak valid.
     */
    public function test_mata_pelajaran_validation(): void
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        MataPelajaran::tambah([
            'kode_mapel' => '',
            'nama_pelajaran' => 'Matematika',
            'kkm' => 75,
        ]);
    }

    /**
     * Test: Admin tidak bisa menyimpan nilai.
     */
    public function test_admin_tidak_bisa_simpan_nilai(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Nilai::pastikanDapatMengelolaNilai('admin');
    }

    /**
     * Test: Guru bisa menyimpan nilai tetapi hanya untuk mata pelajaran yang ditugaskan.
     */
    public function test_guru_tidak_bisa_input_nilai_mapel_tidak_ditugaskan(): void
    {
        $guru = User::query()->create([
            'name' => 'Guru',
            'username' => 'guru1',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru',
            'email' => 'guru1@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '2001',
        ]);

        $mapel = MataPelajaran::query()->create([
            'kode_mapel' => 'MAT',
            'nama_pelajaran' => 'Matematika',
            'kkm' => 75,
        ]);

        $this->expectException(\InvalidArgumentException::class);
        Nilai::pastikanGuruMapel($guru->id, $mapel->id);
    }

    /**
     * Test: Raport hanya bisa diakses oleh guru wali kelas.
     */
    public function test_hanya_wali_kelas_yang_akses_raport(): void
    {
        $guruWali = User::query()->create([
            'name' => 'Guru Wali',
            'username' => 'guru_wali1',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Wali',
            'email' => 'guru_wali1@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '3001',
        ]);

        $guruBiasa = User::query()->create([
            'name' => 'Guru Biasa',
            'username' => 'guru_biasa',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Biasa',
            'email' => 'guru_biasa@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '3002',
        ]);

        Kelas::query()->create([
            'nama_kelas' => '7A',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $guruWali->id,
        ]);

        $this->assertTrue(Kelas::dapatAksesRaport($guruWali->id));
        $this->assertFalse(Kelas::dapatAksesRaport($guruBiasa->id));
    }

    /**
     * Test: Email tidak bisa diubah ke email yang sudah dipakai.
     */
    public function test_email_tidak_bisa_dipakai_orang_lain(): void
    {
        $user1 = User::query()->create([
            'name' => 'User 1',
            'username' => 'user1',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'User 1',
            'email' => 'user1@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '4001',
        ]);

        User::query()->create([
            'name' => 'User 2',
            'username' => 'user2',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'User 2',
            'email' => 'user2@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '4002',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        User::ubahEmailDiri($user1, 'user2@test.com', 'user2@test.com', 'password123');
    }

    public function test_password_lama_salah_ditolak_endpoint_ubah_password(): void
    {
        $user = User::query()->create([
            'name' => 'Akun Test',
            'username' => 'akun_test',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Akun Test',
            'email' => 'akun_test@test.com',
            'role' => 'guru',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('akun.password.update'), [
            'current_password' => 'password-salah',
            'new_password' => 'passwordBaru123',
            'new_password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertRedirect()->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    public function test_konfirmasi_password_baru_berbeda_ditolak_endpoint(): void
    {
        $user = User::query()->create([
            'name' => 'Akun Test',
            'username' => 'akun_test',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Akun Test',
            'email' => 'akun_test@test.com',
            'role' => 'guru',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->put(route('akun.password.update'), [
            'current_password' => 'password123',
            'new_password' => 'passwordBaru123',
            'new_password_confirmation' => 'passwordBerbeda123',
        ]);

        $response->assertRedirect()->assertSessionHasErrors('new_password');
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    /**
     * Test: Nilai akhir dihitung dengan bobot yang benar (30%, 30%, 40%).
     */
    public function test_nilai_akhir_dihitung_dengan_benar(): void
    {
        $actual = Nilai::hitungNilaiAkhir(80, 75, 90);
        $this->assertEquals(82.5, $actual);
    }

    /**
     * Test: Predikat nilai ditentukan berdasarkan nilai akhir.
     */
    public function test_predikat_nilai_ditentukan_benar(): void
    {
        $this->assertEquals('A', Nilai::predikat(95));
        $this->assertEquals('B', Nilai::predikat(85));
        $this->assertEquals('C', Nilai::predikat(75));
        $this->assertEquals('D', Nilai::predikat(65));
    }

    /**
     * Test: Guru tidak bisa ubah role jika masih jadi wali kelas.
     */
    public function test_guru_wali_tidak_bisa_ubah_role(): void
    {
        $guru = User::query()->create([
            'name' => 'Guru Wali',
            'username' => 'guru_wali2',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Wali',
            'email' => 'guru_wali2@test.com',
            'role' => 'guru',
            'status' => 'aktif',
            'nip' => '5001',
        ]);

        Kelas::query()->create([
            'nama_kelas' => '8A',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $guru->id,
        ]);

        $this->expectException(\RuntimeException::class);
        User::perbaruiPengguna($guru->id, [
            'username' => 'guru_wali_baru',
            'nama_lengkap' => 'Guru Wali',
            'email' => 'guru_wali2@test.com',
            'role' => 'admin',
            'status' => 'aktif',
        ]);
    }

    public function test_penugasan_guru_yang_sudah_memiliki_nilai_tidak_bisa_dihapus(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'username' => 'admin_hapus',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Admin',
            'email' => 'admin_hapus@test.com',
            'role' => 'admin',
            'status' => 'aktif',
        ]);
        $guru = User::query()->create([
            'name' => 'Guru Nilai',
            'username' => 'guru_nilai',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Nilai',
            'email' => 'guru_nilai@test.com',
            'role' => 'guru',
            'status' => 'aktif',
        ]);
        $mapel = MataPelajaran::query()->create([
            'kode_mapel' => 'MAT',
            'nama_pelajaran' => 'Matematika',
            'kkm' => 75,
        ]);
        $siswa = Siswa::query()->create([
            'nisn' => '123456789012345',
            'nama_siswa' => 'Siswa Nilai',
            'jenis_kelamin' => 'L',
        ]);
        $guruMapel = GuruMapel::query()->create([
            'guru_id' => $guru->id,
            'mapel_id' => $mapel->id,
        ]);
        $nilai = Nilai::query()->create([
            'siswa_id' => $siswa->id,
            'guru_mapel_id' => $guruMapel->id,
            'semester' => 1,
            'nilai_tugas' => 80,
            'nilai_uts' => 80,
            'nilai_uas' => 80,
            'nilai_akhir' => 80,
        ]);

        $response = $this->actingAs($admin)->delete(route('guru.destroy', $guruMapel->id));

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Penugasan guru mata pelajaran tidak dapat dihapus karena sudah memiliki data nilai.');
        $this->assertDatabaseHas('guru_mapel', ['id' => $guruMapel->id]);

        $deleteGradeResponse = $this->actingAs($admin)->delete(route('input-nilai.destroy', $nilai->id));

        $deleteGradeResponse->assertOk()
            ->assertJsonPath('success', true);
        $this->assertDatabaseMissing('nilai', ['id' => $nilai->id]);

        $deleteAssignmentResponse = $this->actingAs($admin)->delete(route('guru.destroy', $guruMapel->id));

        $deleteAssignmentResponse->assertOk()
            ->assertJsonPath('success', true);
        $this->assertDatabaseMissing('guru_mapel', ['id' => $guruMapel->id]);
    }

    public function test_penugasan_guru_bisa_dihapus_setelah_nilai_dihapus(): void
    {
        $guru = User::query()->create([
            'name' => 'Guru Hapus',
            'username' => 'guru_hapus',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Hapus',
            'email' => 'guru_hapus@test.com',
            'role' => 'guru',
            'status' => 'aktif',
        ]);
        $mapel = MataPelajaran::query()->create([
            'kode_mapel' => 'IPA',
            'nama_pelajaran' => 'Ilmu Pengetahuan Alam',
            'kkm' => 75,
        ]);
        $siswa = Siswa::query()->create([
            'nisn' => '543210987654321',
            'nama_siswa' => 'Siswa Hapus',
            'jenis_kelamin' => 'P',
        ]);
        $guruMapel = GuruMapel::query()->create([
            'guru_id' => $guru->id,
            'mapel_id' => $mapel->id,
        ]);
        $nilai = Nilai::query()->create([
            'siswa_id' => $siswa->id,
            'guru_mapel_id' => $guruMapel->id,
            'semester' => 1,
            'nilai_tugas' => 80,
            'nilai_uts' => 80,
            'nilai_uas' => 80,
            'nilai_akhir' => 80,
        ]);

        $nilai->delete();

        $this->assertTrue(GuruMapel::hapus($guruMapel->id));
        $this->assertDatabaseMissing('guru_mapel', ['id' => $guruMapel->id]);
    }

    public function test_nama_kelas_yang_sama_boleh_dibuat_pada_tahun_ajaran_berbeda(): void
    {
        $guruPertama = User::query()->create([
            'name' => 'Wali Pertama',
            'username' => 'wali_pertama',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Wali Pertama',
            'email' => 'wali_pertama@test.com',
            'role' => 'guru',
            'status' => 'aktif',
        ]);
        $guruKedua = User::query()->create([
            'name' => 'Wali Kedua',
            'username' => 'wali_kedua',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Wali Kedua',
            'email' => 'wali_kedua@test.com',
            'role' => 'guru',
            'status' => 'aktif',
        ]);

        Kelas::tambahKelas([
            'nama_kelas' => '7A',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $guruPertama->id,
        ]);
        $kelasBerikutnya = Kelas::tambahKelas([
            'nama_kelas' => '7A',
            'tahun_ajaran' => '2027/2028',
            'wali_kelas_id' => $guruKedua->id,
        ]);

        $this->assertSame('7A', $kelasBerikutnya->nama_kelas);
        $this->assertDatabaseCount('kelas', 2);
    }

    public function test_grafik_dashboard_memakai_snapshot_tahun_nilai(): void
    {
        $guru = User::query()->create([
            'name' => 'Guru Grafik',
            'username' => 'guru_grafik',
            'password' => Hash::make('password123'),
            'nama_lengkap' => 'Guru Grafik',
            'email' => 'guru_grafik@test.com',
            'role' => 'guru',
            'status' => 'aktif',
        ]);
        $mapel = MataPelajaran::query()->create([
            'kode_mapel' => 'MAT',
            'nama_pelajaran' => 'Matematika',
            'kkm' => 75,
        ]);
        $kelas = Kelas::query()->create([
            'nama_kelas' => '7A',
            'tahun_ajaran' => '2027/2028',
            'wali_kelas_id' => $guru->id,
        ]);
        $siswa = Siswa::query()->create([
            'nisn' => '111111111111111',
            'nama_siswa' => 'Siswa Grafik',
            'jenis_kelamin' => 'L',
            'kelas_id' => $kelas->id,
        ]);
        $guruMapel = GuruMapel::query()->create([
            'guru_id' => $guru->id,
            'mapel_id' => $mapel->id,
        ]);
        Nilai::query()->create([
            'siswa_id' => $siswa->id,
            'guru_mapel_id' => $guruMapel->id,
            'tahun_ajaran' => '2026/2027',
            'semester' => 1,
            'nilai_tugas' => 80,
            'nilai_uts' => 80,
            'nilai_uas' => 80,
            'nilai_akhir' => 80,
        ]);

        $grafik = Nilai::perkembanganDashboard();

        $this->assertSame(['2026/2027'], $grafik['labels']);
        $this->assertSame([80.0], $grafik['values']);
    }
}
