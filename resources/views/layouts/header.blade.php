{{-- =========================================================
     HEADER
     ========================================================= --}}

<header class="frame-5">


    {{-- =====================================================
         IDENTITAS APLIKASI
         ===================================================== --}}
    <div class="frame-6">

        <span
            class="fluent"
            aria-hidden="true"
        >

            <img
                class="vector-8"
                src="{{ asset('gambar/header_icon.png') }}"
                alt=""
            >

        </span>


        <div class="frame-7">

            <p class="text-wrapper-5">
                Aplikasi E-Raport Cyber Olympus
            </p>

            <p class="text-wrapper-6">
                Sistem Manajemen Raport Digital
            </p>

        </div>

    </div>



    {{-- =====================================================
         INFORMASI USER
         ===================================================== --}}
    <div class="frame-8">


        <div class="frame-9">

            <div class="div-wrapper">

                <p class="text-wrapper-7">
                    Admin Sekolah
                </p>

            </div>

            <p class="text-wrapper-8">
                Administrator
            </p>

        </div>



        {{-- =================================================
             TOMBOL PROFILE
             ================================================= --}}
        <button
            class="frame-10"
            type="button"
            aria-label="Buka menu akun Admin Sekolah"
            aria-expanded="false"
            aria-controls="account-dropdown"
        >

            <span class="frame-wrapper">

                <span
                    class="frame-11"
                    aria-hidden="true"
                >

                    <span class="ellipse-wrapper">

                        <span class="ellipse"></span>

                    </span>


                    <span class="text-wrapper-9">
                        AS
                    </span>

                </span>

            </span>


            <span
                class="mingcute-down-fill"
                aria-hidden="true"
            >

                <img
                    class="vector-9"
                    src="{{ asset('gambar/dropdown_icon.png') }}"
                    alt=""
                >

            </span>

        </button>



        {{-- =================================================
             DROPDOWN PROFILE
             ================================================= --}}
        <div
            class="account-dropdown"
            id="account-dropdown"
            hidden
        >


            {{-- =================================================
                 PENGATURAN AKUN
                 ================================================= --}}
            <button
                class="account-menu-item account-settings-toggle"
                type="button"
                aria-expanded="false"
                aria-controls="account-settings-submenu"
            >

                <span>
                    Pengaturan Akun
                </span>


                <span
                    class="account-menu-chevron"
                    aria-hidden="true"
                >
                    ›
                </span>

            </button>



            {{-- =================================================
                 SUBMENU PENGATURAN AKUN
                 ================================================= --}}
            <div
                class="account-settings-submenu"
                id="account-settings-submenu"
                hidden
            >

                <button
                    class="account-submenu-item"
                    type="button"
                    data-open-account-modal="email"
                >
                    Ubah Email
                </button>


                <button
                    class="account-submenu-item"
                    type="button"
                    data-open-account-modal="password"
                >
                    Ubah Password
                </button>

            </div>



            {{-- =================================================
                 PEMBATAS
                 ================================================= --}}
            <div
                class="account-menu-divider"
                aria-hidden="true"
            ></div>



            {{-- =================================================
                 LOGOUT
                 ================================================= --}}
            <button
                class="account-menu-item account-logout"
                type="button"
            >
                Logout
            </button>

        </div>

    </div>

</header>



{{-- =========================================================
     MODAL UBAH EMAIL
     ========================================================= --}}

<div
    class="account-modal"
    id="change-email-modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="change-email-title"
    aria-describedby="change-email-description"
    hidden
>


    {{-- OVERLAY --}}
    <div
        class="account-modal-overlay"
        data-close-account-modal
    ></div>



    {{-- DIALOG --}}
    <div class="account-modal-dialog">


        {{-- =================================================
             HEADER MODAL
             ================================================= --}}
        <div class="account-modal-header">

            <div class="account-modal-heading">

                <h2
                    class="account-modal-title"
                    id="change-email-title"
                >
                    Ubah Email
                </h2>


                <p
                    class="account-modal-description"
                    id="change-email-description"
                >
                    Perbarui alamat email yang digunakan untuk akun Anda.
                </p>

            </div>


            <button
                class="account-modal-close"
                type="button"
                aria-label="Tutup form ubah email"
                data-close-account-modal
            >
                <span aria-hidden="true">
                    ×
                </span>
            </button>

        </div>



        {{-- =================================================
             BODY MODAL
             ================================================= --}}
        <div class="account-modal-body">

            <form
                class="account-modal-form"
                id="change-email-form"
                action="#"
                method="POST"
            >

                @csrf


                {{-- EMAIL SAAT INI --}}
                <div class="account-form-group">

                    <label
                        class="account-form-label"
                        for="current-email"
                    >
                        Email Saat Ini
                    </label>


                    <input
                        class="account-form-input"
                        type="email"
                        id="current-email"
                        name="current_email"
                        value="admin@cyberolympus.sch.id"
                        readonly
                    >

                </div>



                {{-- EMAIL BARU --}}
                <div class="account-form-group">

                    <label
                        class="account-form-label"
                        for="new-email"
                    >
                        Email Baru
                        <span class="account-required">*</span>
                    </label>


                    <input
                        class="account-form-input"
                        type="email"
                        id="new-email"
                        name="new_email"
                        placeholder="Masukkan email baru"
                        autocomplete="email"
                        required
                    >


                    <p class="account-form-helper">
                        Gunakan alamat email yang masih aktif dan dapat Anda akses.
                    </p>

                </div>



                {{-- KONFIRMASI EMAIL --}}
                <div class="account-form-group">

                    <label
                        class="account-form-label"
                        for="confirm-email"
                    >
                        Konfirmasi Email Baru
                        <span class="account-required">*</span>
                    </label>


                    <input
                        class="account-form-input"
                        type="email"
                        id="confirm-email"
                        name="confirm_email"
                        placeholder="Masukkan kembali email baru"
                        autocomplete="email"
                        required
                    >

                </div>



                {{-- PASSWORD --}}
                <div class="account-form-group">

                    <label
                        class="account-form-label"
                        for="email-password"
                    >
                        Password Saat Ini
                        <span class="account-required">*</span>
                    </label>


                    <div class="account-password-wrapper">

                        <input
                            class="account-form-input"
                            type="password"
                            id="email-password"
                            name="password"
                            placeholder="Masukkan password saat ini"
                            autocomplete="current-password"
                            required
                        >


                        <button
                            class="account-password-toggle"
                            type="button"
                            aria-label="Tampilkan password"
                            data-password-toggle="email-password"
                        >
                            <span aria-hidden="true">
                                👁
                            </span>
                        </button>

                    </div>

                </div>



                {{-- ACTION --}}
                <div class="account-modal-actions">

                    <button
                        class="account-btn account-btn-secondary"
                        type="button"
                        data-close-account-modal
                    >
                        Batal
                    </button>


                    <button
                        class="account-btn account-btn-primary"
                        type="submit"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =========================================================
     MODAL UBAH PASSWORD
     ========================================================= --}}

<div
    class="account-modal"
    id="change-password-modal"
    role="dialog"
    aria-modal="true"
    aria-labelledby="change-password-title"
    aria-describedby="change-password-description"
    hidden
>


    {{-- OVERLAY --}}
    <div
        class="account-modal-overlay"
        data-close-account-modal
    ></div>



    {{-- DIALOG --}}
    <div class="account-modal-dialog">


        {{-- =================================================
             HEADER MODAL
             ================================================= --}}
        <div class="account-modal-header">

            <div class="account-modal-heading">

                <h2
                    class="account-modal-title"
                    id="change-password-title"
                >
                    Ubah Password
                </h2>


                <p
                    class="account-modal-description"
                    id="change-password-description"
                >
                    Perbarui password akun Anda untuk menjaga keamanan akun.
                </p>

            </div>


            <button
                class="account-modal-close"
                type="button"
                aria-label="Tutup form ubah password"
                data-close-account-modal
            >
                <span aria-hidden="true">
                    ×
                </span>
            </button>

        </div>



        {{-- =================================================
             BODY MODAL
             ================================================= --}}
        <div class="account-modal-body">

            <form
                class="account-modal-form"
                id="change-password-form"
                action="#"
                method="POST"
            >

                @csrf


                {{-- PASSWORD SAAT INI --}}
                <div class="account-form-group">

                    <label
                        class="account-form-label"
                        for="current-password"
                    >
                        Password Saat Ini
                        <span class="account-required">*</span>
                    </label>


                    <div class="account-password-wrapper">

                        <input
                            class="account-form-input"
                            type="password"
                            id="current-password"
                            name="current_password"
                            placeholder="Masukkan password saat ini"
                            autocomplete="current-password"
                            required
                        >


                        <button
                            class="account-password-toggle"
                            type="button"
                            aria-label="Tampilkan password"
                            data-password-toggle="current-password"
                        >
                            <span aria-hidden="true">
                                👁
                            </span>
                        </button>

                    </div>

                </div>



                {{-- PASSWORD BARU --}}
                <div class="account-form-group">

                    <label
                        class="account-form-label"
                        for="new-password"
                    >
                        Password Baru
                        <span class="account-required">*</span>
                    </label>


                    <div class="account-password-wrapper">

                        <input
                            class="account-form-input"
                            type="password"
                            id="new-password"
                            name="new_password"
                            placeholder="Masukkan password baru"
                            autocomplete="new-password"
                            required
                        >


                        <button
                            class="account-password-toggle"
                            type="button"
                            aria-label="Tampilkan password"
                            data-password-toggle="new-password"
                        >
                            <span aria-hidden="true">
                                👁
                            </span>
                        </button>

                    </div>


                    <p class="account-form-helper">
                        Gunakan password yang kuat dan mudah Anda ingat.
                    </p>

                </div>



                {{-- KONFIRMASI PASSWORD --}}
                <div class="account-form-group">

                    <label
                        class="account-form-label"
                        for="confirm-password"
                    >
                        Konfirmasi Password Baru
                        <span class="account-required">*</span>
                    </label>


                    <div class="account-password-wrapper">

                        <input
                            class="account-form-input"
                            type="password"
                            id="confirm-password"
                            name="confirm_password"
                            placeholder="Masukkan kembali password baru"
                            autocomplete="new-password"
                            required
                        >


                        <button
                            class="account-password-toggle"
                            type="button"
                            aria-label="Tampilkan password"
                            data-password-toggle="confirm-password"
                        >
                            <span aria-hidden="true">
                                👁
                            </span>
                        </button>

                    </div>

                </div>



                {{-- ACTION --}}
                <div class="account-modal-actions">

                    <button
                        class="account-btn account-btn-secondary"
                        type="button"
                        data-close-account-modal
                    >
                        Batal
                    </button>


                    <button
                        class="account-btn account-btn-primary"
                        type="submit"
                    >
                        Simpan Password
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>