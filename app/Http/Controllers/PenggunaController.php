<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenggunaRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends ApiController
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

        return $this->successResponse($pengguna, 'Pengguna berhasil ditambahkan.', 201);
    }

    public function show(int $user): JsonResponse
    {
        $pengguna = User::penggunaById($user);

        return $this->successResponse($pengguna);
    }

    public function update(PenggunaRequest $request, int $user): JsonResponse
    {
        try {
            $pengguna = User::perbaruiPengguna(
                $user,
                $request->validated()
            );

            return $this->successResponse($pengguna, 'Pengguna berhasil diperbarui.');
        } catch (\RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage());
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

            return $this->successResponse($pengguna, $pengguna->status === 'aktif'
                    ? 'Akun berhasil diaktifkan.'
                    : 'Akun berhasil dinonaktifkan.');
        } catch (\RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy(int $user): JsonResponse
    {
        try {
            User::hapusPengguna($user);

            return $this->successResponse(null, 'Pengguna berhasil dihapus.');
        } catch (\RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
