{{-- =========================================================
     HEADER
     ========================================================= --}}
<header class="frame-5">
    <div class="frame-6">
        <span class="fluent" aria-hidden="true">
            <img class="vector-8" src="{{ asset('gambar/header_icon.png') }}" alt="">
        </span>
        <div class="frame-7">
            <p class="text-wrapper-5">Aplikasi E-Raport Cyber Olympus</p>
            <p class="text-wrapper-6">Sistem Manajemen Raport Digital</p>
        </div>
    </div>

    <div class="frame-8">
        @guest
            <a href="{{ route('login') }}" class="frame-10 login-header-button" aria-label="Login">
                <span class="text-wrapper-7">Login</span>
            </a>
        @else
            @php
                $user = auth()->user();
                $roleLabel = match ($user->role) {
                    'guru' => 'Guru',
                    'admin' => 'Administrator',
                    'kepala_sekolah' => 'Kepala Sekolah',
                    default => ucfirst($user->role),
                };
                $initials = collect(preg_split('/\s+/', trim($user->nama_lengkap)))
                    ->filter()
                    ->map(fn ($name) => strtoupper(substr($name, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp

            <div class="frame-9">
                <div class="div-wrapper">
                    <p class="text-wrapper-7">{{ $user->nama_lengkap }}</p>
                </div>
                <p class="text-wrapper-8">{{ $roleLabel }}</p>
            </div>

            <button class="frame-10" type="button"
                aria-label="Buka menu akun {{ $user->nama_lengkap }}"
                aria-expanded="false" aria-controls="account-dropdown">
                <span class="frame-wrapper">
                    <span class="frame-11" aria-hidden="true">
                        <span class="ellipse-wrapper"><span class="ellipse"></span></span>
                        <span class="text-wrapper-9">{{ $initials ?: 'US' }}</span>
                    </span>
                </span>
                <span class="mingcute-down-fill" aria-hidden="true">
                    <img class="vector-9" src="{{ asset('gambar/dropdown_icon.png') }}" alt="">
                </span>
            </button>

            <div class="account-dropdown" id="account-dropdown" hidden>
                <button class="account-menu-item account-settings-toggle" type="button"
                    aria-expanded="false" aria-controls="account-settings-submenu">
                    <span>Pengaturan Akun</span>
                    <span class="account-menu-chevron" aria-hidden="true">›</span>
                </button>

                <div class="account-settings-submenu" id="account-settings-submenu" hidden>
                    <button class="account-submenu-item" type="button" data-open-account-modal="email">
                        Ubah Email
                    </button>
                    <button class="account-submenu-item" type="button" data-open-account-modal="password">
                        Ubah Password
                    </button>
                </div>

                <div class="account-menu-divider" aria-hidden="true"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="account-menu-item account-logout" type="submit">Logout</button>
                </form>
            </div>
        @endguest
    </div>
</header>

@auth
    {{-- =====================================================
         MODAL UBAH EMAIL
         ===================================================== --}}
    <div class="account-modal" id="change-email-modal" role="dialog" aria-modal="true"
        aria-labelledby="change-email-title" aria-describedby="change-email-description"
        @if ($errors->hasAny(['new_email', 'confirm_email', 'password'])) @else hidden @endif>
        <div class="account-modal-overlay" data-close-account-modal></div>
        <div class="account-modal-dialog">
            <div class="account-modal-header">
                <div class="account-modal-heading">
                    <h2 class="account-modal-title" id="change-email-title">Ubah Email</h2>
                    <p class="account-modal-description" id="change-email-description">
                        Perbarui alamat email yang digunakan untuk akun Anda.
                    </p>
                </div>
                <button class="account-modal-close" type="button" aria-label="Tutup form ubah email"
                    data-close-account-modal><span aria-hidden="true">×</span></button>
            </div>

            <div class="account-modal-body">
                @if ($errors->hasAny(['new_email', 'confirm_email', 'password']))
                    <div class="account-form-alert" role="alert">
                        {{ $errors->first('new_email') ?: $errors->first('confirm_email') ?: $errors->first('password') }}
                    </div>
                @endif
                <form class="account-modal-form" id="change-email-form"
                    action="{{ route('akun.email.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="account-form-group">
                        <label class="account-form-label" for="current-email">Email Saat Ini</label>
                        <input class="account-form-input" type="email" id="current-email" name="current_email"
                            value="{{ auth()->user()->email }}" readonly>
                    </div>

                    <div class="account-form-group">
                        <label class="account-form-label" for="new-email">Email Baru <span class="account-required">*</span></label>
                        <input class="account-form-input" type="email" id="new-email" name="new_email"
                            placeholder="Masukkan email baru" autocomplete="email" required>
                        <p class="account-form-helper">Gunakan alamat email yang masih aktif dan dapat Anda akses.</p>
                    </div>

                    <div class="account-form-group">
                        <label class="account-form-label" for="confirm-email">Konfirmasi Email Baru <span class="account-required">*</span></label>
                        <input class="account-form-input" type="email" id="confirm-email" name="confirm_email"
                            placeholder="Masukkan kembali email baru" autocomplete="email" required>
                    </div>

                    <div class="account-form-group">
                        <label class="account-form-label" for="email-password">Password Saat Ini <span class="account-required">*</span></label>
                        <div class="account-password-wrapper">
                            <input class="account-form-input" type="password" id="email-password" name="password"
                                placeholder="Masukkan password saat ini" autocomplete="current-password" required>
                            <button class="account-password-toggle" type="button" aria-label="Tampilkan password"
                                data-password-toggle="email-password"><span aria-hidden="true">👁</span></button>
                        </div>
                    </div>

                    <div class="account-modal-actions">
                        <button class="account-btn account-btn-secondary" type="button" data-close-account-modal>Batal</button>
                        <button class="account-btn account-btn-primary" type="submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =====================================================
         MODAL UBAH PASSWORD
         ===================================================== --}}
    <div class="account-modal" id="change-password-modal" role="dialog" aria-modal="true"
        aria-labelledby="change-password-title" aria-describedby="change-password-description"
        @if ($errors->hasAny(['current_password', 'new_password'])) @else hidden @endif>
        <div class="account-modal-overlay" data-close-account-modal></div>
        <div class="account-modal-dialog">
            <div class="account-modal-header">
                <div class="account-modal-heading">
                    <h2 class="account-modal-title" id="change-password-title">Ubah Password</h2>
                    <p class="account-modal-description" id="change-password-description">
                        Perbarui password akun Anda untuk menjaga keamanan akun.
                    </p>
                </div>
                <button class="account-modal-close" type="button" aria-label="Tutup form ubah password"
                    data-close-account-modal><span aria-hidden="true">×</span></button>
            </div>

            <div class="account-modal-body">
                @if ($errors->hasAny(['current_password', 'new_password']))
                    <div class="account-form-alert" role="alert">
                        {{ $errors->first('current_password') ?: $errors->first('new_password') }}
                    </div>
                @endif
                <form class="account-modal-form" id="change-password-form"
                    action="{{ route('akun.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="account-form-group">
                        <label class="account-form-label" for="current-password">Password Saat Ini <span class="account-required">*</span></label>
                        <div class="account-password-wrapper">
                            <input class="account-form-input" type="password" id="current-password" name="current_password"
                                placeholder="Masukkan password saat ini" autocomplete="current-password" required>
                            <button class="account-password-toggle" type="button" aria-label="Tampilkan password"
                                data-password-toggle="current-password"><span aria-hidden="true">👁</span></button>
                        </div>
                    </div>

                    <div class="account-form-group">
                        <label class="account-form-label" for="new-password">Password Baru <span class="account-required">*</span></label>
                        <div class="account-password-wrapper">
                            <input class="account-form-input" type="password" id="new-password" name="new_password"
                                placeholder="Masukkan password baru" autocomplete="new-password" required>
                            <button class="account-password-toggle" type="button" aria-label="Tampilkan password"
                                data-password-toggle="new-password"><span aria-hidden="true">👁</span></button>
                        </div>
                        <p class="account-form-helper">Gunakan password yang kuat dan mudah Anda ingat.</p>
                    </div>

                    <div class="account-form-group">
                        <label class="account-form-label" for="confirm-password">Konfirmasi Password Baru <span class="account-required">*</span></label>
                        <div class="account-password-wrapper">
                            <input class="account-form-input" type="password" id="confirm-password"
                                name="new_password_confirmation" placeholder="Masukkan kembali password baru"
                                autocomplete="new-password" required>
                            <button class="account-password-toggle" type="button" aria-label="Tampilkan password"
                                data-password-toggle="confirm-password"><span aria-hidden="true">👁</span></button>
                        </div>
                    </div>

                    <div class="account-modal-actions">
                        <button class="account-btn account-btn-secondary" type="button" data-close-account-modal>Batal</button>
                        <button class="account-btn account-btn-primary" type="submit">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endauth
