<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        $user = User::loginBoleh(
            $credentials['email'],
            $credentials['password']
        );

        if (!$user) {
            $message = 'Akun tidak ditemukan, role tidak memiliki akses, atau akun tidak aktif.';

            if (!User::query()->where('email', $credentials['email'])->exists()) {
                $message = 'Email atau kata sandi salah.';
            }

            return back()
                ->withErrors([
                    'email' => $message,
                ])
                ->withInput($request->only('email'));
        }

        Auth::login($user);

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
