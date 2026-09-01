<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends ApiController
{
    public function index()
    {
        return view('siswa', Siswa::dataHalaman());
    }

    public function store(Request $request)
    {
        $siswa = Siswa::tambah($request->only([
            'nisn',
            'nama_siswa',
            'jenis_kelamin',
            'kelas_id',
        ]));

        return $this->successResponse(
            $this->formatData($siswa),
            'Data siswa berhasil ditambahkan.',
            201
        );
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::ubah($id, $request->only([
            'nisn',
            'nama_siswa',
            'jenis_kelamin',
            'kelas_id',
        ]));

        return $this->successResponse(
            $this->formatData($siswa),
            'Data siswa berhasil diperbarui.'
        );
    }

    public function destroy($id)
    {
        try {
            Siswa::hapus($id);
            return $this->successResponse(
                null,
                'Data siswa berhasil dihapus.'
            );
        } catch (\RuntimeException $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    private function formatData(Siswa $siswa)
    {
        return [
            'id' => $siswa->id,
            'nisn' => $siswa->nisn,
            'nama_siswa' => $siswa->nama_siswa,
            'jenis_kelamin' => $siswa->jenis_kelamin,
            'kelas_id' => $siswa->kelas_id,
            'kelas' => $siswa->kelas?->nama_kelas ?? '-',
        ];
    }
}
