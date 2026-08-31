<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa', Siswa::dataHalaman());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nisn' => ['required', 'string', 'max:15', 'unique:siswa,nisn'],
            'nama_siswa' => ['required', 'string', 'max:40'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
        ], $this->validationMessages());

        $siswa = Siswa::tambah($data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil ditambahkan.',
            'data' => $this->formatData($siswa),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nisn' => ['required', 'string', 'max:15', 'unique:siswa,nisn,' . $id],
            'nama_siswa' => ['required', 'string', 'max:40'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
        ], $this->validationMessages());

        $siswa = Siswa::ubah($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diperbarui.',
            'data' => $this->formatData($siswa),
        ]);
    }

    public function destroy($id)
    {
        try {
            Siswa::hapus($id);

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus.',
            ]);
        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    private function validationMessages(): array
    {
        return [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.string' => 'NISN harus berupa teks.',
            'nisn.max' => 'NISN maksimal 15 karakter.',
            'nisn.unique' => 'NISN sudah digunakan oleh siswa lain.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'nama_siswa.string' => 'Nama siswa harus berupa teks.',
            'nama_siswa.max' => 'Nama siswa maksimal 40 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid. Pilih L atau P.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.integer' => 'Data kelas tidak valid.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak ditemukan.',
        ];
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
