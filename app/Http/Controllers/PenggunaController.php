<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenggunaRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('pengguna', [
            'pengguna' => User::semuaPengguna(),
            'statistik' => User::statistikPengguna(),
        ]);
    }

    public function store(PenggunaRequest $request): JsonResponse
    {
        $pengguna = User::buatPengguna($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan.',
            'data' => $pengguna,
        ], 201);
    }

    public function show(int $user): JsonResponse
    {
        $pengguna = User::penggunaById($user);

        return response()->json([
            'success' => true,
            'data' => $pengguna,
        ]);
    }

    public function update(PenggunaRequest $request, int $user): JsonResponse
    {
        try {
            $pengguna = User::perbaruiPengguna(
                $user,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diperbarui.',
                'data' => $pengguna,
            ]);
        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function updateStatus(Request $request, int $user): JsonResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(User::daftarStatus()),
            ],
        ], [
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status pengguna tidak valid.',
        ]);

        try {
            $pengguna = User::ubahStatus(
                $user,
                $validated['status']
            );

            return response()->json([
                'success' => true,
                'message' => $pengguna->status === 'aktif'
                    ? 'Akun berhasil diaktifkan.'
                    : 'Akun berhasil dinonaktifkan.',
                'data' => $pengguna,
            ]);
        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function destroy(int $user): JsonResponse
    {
        try {
            User::hapusPengguna($user);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus.',
            ]);
        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
