<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Memeriksa role user yang sedang login.
     *
     * Contoh:
     *
     * role:guru
     * role:admin
     * role:kepala_sekolah
     * role:admin,kepala_sekolah
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        /*
         * Pastikan user sudah login.
         */
        if (!$request->user()) {
            return redirect()->route('login');
        }

        /*
         * Ambil role user yang sedang login.
         */
        $userRole = strtolower(
            trim($request->user()->role ?? '')
        );

        /*
         * Normalisasi role yang diperbolehkan.
         */
        $allowedRoles = collect($roles)
            ->map(function ($role) {
                return strtolower(trim($role));
            })
            ->filter()
            ->values()
            ->all();

        /*
         * Jika role user tidak sesuai,
         * tolak akses.
         */
        if (!in_array($userRole, $allowedRoles, true)) {

            /*
             * Untuk request AJAX / JSON,
             * kirim response JSON.
             */
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk halaman atau tindakan ini.',
                ], 403);
            }

            /*
             * Untuk request biasa,
             * tampilkan halaman 403.
             */
            abort(
                403,
                'Anda tidak memiliki hak akses untuk halaman atau tindakan ini.'
            );
        }

        /*
         * Role sesuai.
         * Lanjutkan request.
         */
        return $next($request);
    }
}
