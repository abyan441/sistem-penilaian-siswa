<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function index()
    {
        return view('login');
    }

    /**
     * Memproses login user.
     *
     * User yang dapat login:
     * - guru
     * - admin
     * - kepala_sekolah
     *
     * Semua user harus memiliki status aktif.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        /*
         * Pastikan akun memiliki role yang diperbolehkan
         * dan statusnya aktif.
         */
        $allowedRoles = [
            'guru',
            'admin',
            'kepala_sekolah',
        ];

        $user = \App\Models\User::query()
            ->where('email', $credentials['email'])
            ->whereIn('role', $allowedRoles)
            ->where('status', 'aktif')
            ->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Akun tidak ditemukan, role tidak memiliki akses, atau akun tidak aktif.',
                ])
                ->withInput($request->only('email'));
        }

        /*
         * Coba autentikasi berdasarkan email,
         * password, role, dan status aktif.
         */
        if (!Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => $user->role,
            'status' => 'aktif',
        ])) {
            return back()
                ->withErrors([
                    'email' => 'Email atau kata sandi salah.',
                ])
                ->withInput($request->only('email'));
        }

        /*
         * Regenerasi session setelah login berhasil
         * untuk mencegah session fixation.
         */
        $request->session()->regenerate();

        /*
         * Setelah login berhasil, arahkan ke halaman
         * yang sebelumnya ingin diakses atau dashboard.
         */
        return redirect()->intended(
            route('dashboard')
        );
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard');
    }
}
