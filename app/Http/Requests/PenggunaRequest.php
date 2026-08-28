<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PenggunaRequest extends FormRequest
{
    /**
     * Menentukan apakah request diperbolehkan.
     */
    public function authorize(): bool
    {
        return auth()->check()
            && in_array(
                auth()->user()->role,
                ['admin', 'kepala_sekolah'],
                true
            );
    }

    /**
     * Aturan validasi tambah dan ubah pengguna.
     */
    public function rules(): array
    {
        $user = $this->route('user');

        $userModel = $user instanceof User
            ? $user
            : ($user ? User::find($user) : null);

        $userId = $userModel?->id;

        /*
         * Role:
         *
         * CREATE:
         * - kepala_sekolah
         * - guru
         *
         * UPDATE:
         * - role pengguna yang sudah ada boleh dipertahankan
         * - admin tidak boleh diberikan kepada pengguna lain
         */
        $allowedRoles = [
            'kepala_sekolah',
            'guru',
        ];

        if (
            $this->isMethod('put') ||
            $this->isMethod('patch')
        ) {
            if ($userModel?->role === 'admin') {
                $allowedRoles[] = 'admin';
            }
        }

        $rules = [
            'username' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users', 'username')
                    ->ignore($userId),
            ],

            'nama_lengkap' => [
                'required',
                'string',
                'max:40',
            ],

            'email' => [
                'required',
                'email',
                'max:30',
                Rule::unique('users', 'email')
                    ->ignore($userId),
            ],

            'role' => [
                'required',
                Rule::in($allowedRoles),
            ],

            'status' => [
                'required',
                Rule::in(User::daftarStatus()),
            ],

            'nip' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'nip')
                    ->ignore($userId),
            ],
        ];

        /*
         * Password:
         *
         * CREATE = wajib
         * UPDATE = opsional
         */
        if ($this->isMethod('post')) {
            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'max:60',
            ];
        } else {
            $rules['password'] = [
                'nullable',
                'string',
                'min:8',
                'max:60',
            ];
        }

        return $rules;
    }

    /**
     * Validasi tambahan.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->route('user');

            $userModel = $user instanceof User
                ? $user
                : ($user ? User::find($user) : null);

            $userId = $userModel?->id;

            /*
             * =====================================================
             * ADMIN
             * =====================================================
             *
             * Admin hanya boleh dibuat melalui Tinker.
             *
             * Pada UPDATE:
             * role admin hanya boleh dipertahankan oleh
             * akun yang memang sudah menjadi admin.
             */
            if (
                $this->isMethod('post') &&
                $this->input('role') === 'admin'
            ) {
                $validator->errors()->add(
                    'role',
                    'Administrator hanya dapat dibuat melalui Tinker.'
                );
            }

            if (
                ($this->isMethod('put') || $this->isMethod('patch')) &&
                $this->input('role') === 'admin' &&
                $userModel?->role !== 'admin'
            ) {
                $validator->errors()->add(
                    'role',
                    'Role Administrator hanya dapat dipertahankan oleh akun Administrator.'
                );
            }

            /*
             * =====================================================
             * KEPALA SEKOLAH
             * =====================================================
             *
             * Hanya boleh ada satu kepala sekolah.
             */
            if (
                $this->input('role') === 'kepala_sekolah'
            ) {
                $query = User::query()
                    ->where('role', 'kepala_sekolah');

                if ($userId) {
                    $query->where('id', '!=', $userId);
                }

                if ($query->exists()) {
                    $validator->errors()->add(
                        'role',
                        'Akun Kepala Sekolah sudah tersedia. Hanya boleh ada satu Kepala Sekolah.'
                    );
                }
            }
        });
    }

    /**
     * Pesan validasi Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username maksimal 15 karakter.',
            'username.unique' => 'Username sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 60 karakter.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 40 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 30 karakter.',
            'email.unique' => 'Email sudah digunakan.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',

            'status.required' => 'Status pengguna wajib dipilih.',
            'status.in' => 'Status pengguna tidak valid.',

            'nip.string' => 'NIP harus berupa teks.',
            'nip.max' => 'NIP maksimal 20 karakter.',
            'nip.unique' => 'NIP sudah digunakan.',
        ];
    }

    /**
     * Normalisasi data.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => trim((string) $this->username),

            'nama_lengkap' => trim(
                (string) $this->nama_lengkap
            ),

            'email' => trim(
                (string) $this->email
            ),

            'nip' => $this->filled('nip')
                ? trim((string) $this->nip)
                : null,
        ]);
    }
}