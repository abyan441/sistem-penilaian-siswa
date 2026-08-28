<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenggunaRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Pengguna.
     */
    public function index()
    {
        return view('pengguna', [
            'pengguna' => User::semuaPengguna(),

            'statistik' =>
                User::statistikPengguna(),
        ]);
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(
        PenggunaRequest $request
    ): JsonResponse {
        $pengguna = User::buatPengguna(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' =>
                'Pengguna berhasil ditambahkan.',
            'data' => $pengguna,
        ], 201);
    }

    /**
     * Menampilkan detail pengguna.
     *
     * Password tidak ikut dikirim karena
     * sudah berada di $hidden pada model User.
     */
    public function show(int $user): JsonResponse
    {
        $pengguna =
            User::penggunaById($user);

        return response()->json([
            'success' => true,
            'data' => $pengguna,
        ]);
    }

    /**
     * Memperbarui pengguna.
     */
    public function update(
        PenggunaRequest $request,
        int $user
    ): JsonResponse {
        $pengguna =
            User::perbaruiPengguna(
                $user,
                $request->validated()
            );

        return response()->json([
            'success' => true,
            'message' =>
                'Pengguna berhasil diperbarui.',
            'data' => $pengguna,
        ]);
    }

    /**
     * Mengubah status aktif / tidak aktif.
     */
    public function updateStatus(
        Request $request,
        int $user
    ): JsonResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(
                    User::daftarStatus()
                ),
            ],
        ], [
            'status.required' =>
                'Status wajib dipilih.',

            'status.in' =>
                'Status pengguna tidak valid.',
        ]);

        $pengguna = User::ubahStatus(
            $user,
            $validated['status']
        );

        return response()->json([
            'success' => true,
            'message' =>
                $pengguna->status === 'aktif'
                    ? 'Akun berhasil diaktifkan.'
                    : 'Akun berhasil dinonaktifkan.',
            'data' => $pengguna,
        ]);
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(int $user): JsonResponse
    {
        User::hapusPengguna($user);

        return response()->json([
            'success' => true,
            'message' =>
                'Pengguna berhasil dihapus.',
        ]);
    }
}