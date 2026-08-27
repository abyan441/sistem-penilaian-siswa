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
     * Memproses login guru.
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
         * Pastikan yang dapat login adalah guru aktif.
         */
        $user = \App\Models\User::query()
            ->where('email', $credentials['email'])
            ->where('role', 'guru')
            ->where('status', 'aktif')
            ->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Akun guru tidak ditemukan atau akun tidak aktif.',
                ])
                ->withInput($request->only('email'));
        }

        /*
         * Coba autentikasi.
         */
        if (!Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => 'guru',
            'status' => 'aktif',
        ])) {
            return back()
                ->withErrors([
                    'email' => 'Email atau kata sandi salah.',
                ])
                ->withInput($request->only('email'));
        }

        /*
         * Regenerasi session untuk keamanan.
         */
        $request->session()->regenerate();

        /*
         * Setelah login berhasil,
         * arahkan ke dashboard.
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