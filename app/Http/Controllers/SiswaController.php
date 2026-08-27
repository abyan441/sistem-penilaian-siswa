<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Menampilkan halaman data siswa.
     */
    public function index()
    {
        return view('siswa', Siswa::dataHalaman());
    }

    /**
     * Menyimpan data siswa baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nisn' => [
                'required',
                'string',
                'max:15',
                'unique:siswa,nisn',
            ],

            'nama_siswa' => [
                'required',
                'string',
                'max:40',
            ],

            'jenis_kelamin' => [
                'required',
                'in:L,P',
            ],

            'kelas_id' => [
                'required',
                'integer',
                'exists:kelas,id',
            ],
        ]);

        $siswa = Siswa::tambah($data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil ditambahkan.',
            'data' => $this->formatData($siswa),
        ]);
    }

    /**
     * Memperbarui data siswa.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nisn' => [
                'required',
                'string',
                'max:15',
                'unique:siswa,nisn,' . $id,
            ],

            'nama_siswa' => [
                'required',
                'string',
                'max:40',
            ],

            'jenis_kelamin' => [
                'required',
                'in:L,P',
            ],

            'kelas_id' => [
                'required',
                'integer',
                'exists:kelas,id',
            ],
        ]);

        $siswa = Siswa::ubah($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diperbarui.',
            'data' => $this->formatData($siswa),
        ]);
    }

    /**
     * Menghapus data siswa.
     */
    public function destroy($id)
    {
        Siswa::hapus($id);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil dihapus.',
        ]);
    }

    /**
     * Menyiapkan data siswa untuk response JSON.
     */
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