<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    /**
     * Memperbarui email akun yang sedang login.
     */
    public function updateEmail(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'new_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'confirm_email' => [
                'required',
                'same:new_email',
            ],
            'password' => [
                'required',
                'current_password:web',
            ],
        ], [
            'new_email.required' => 'Email baru wajib diisi.',
            'new_email.email' => 'Format email baru tidak valid.',
            'new_email.max' => 'Email baru terlalu panjang.',
            'new_email.unique' => 'Email tersebut sudah digunakan oleh pengguna lain.',
            'confirm_email.required' => 'Konfirmasi email wajib diisi.',
            'confirm_email.same' => 'Konfirmasi email tidak sama dengan email baru.',
            'password.required' => 'Password saat ini wajib diisi.',
            'password.current_password' => 'Password saat ini salah.',
        ]);

        User::ubahEmail($user, $validated['new_email']);

        return back()->with('account_success', 'Email berhasil diperbarui.');
    }

    /**
     * Memperbarui password akun yang sedang login.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password:web',
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini salah.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak sama.',
        ]);

        User::ubahPassword($request->user(), $validated['new_password']);

        return back()->with('account_success', 'Password berhasil diperbarui.');
    }
}
