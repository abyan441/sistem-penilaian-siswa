<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Masuk Guru</title>

    {{-- CSS global --}}
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleguide.css') }}">

    {{-- CSS khusus login --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<main class="halaman-login">

    {{-- =========================================================
         BACKGROUND / ILUSTRASI
         ========================================================= --}}
    <section
        class="background"
        aria-label="Ilustrasi guru"
        style="background-image: url('{{ asset('gambar/green_background.jpg') }}');"
    >

        <div class="rectangle" aria-hidden="true"></div>

        <div class="frame">
            <img
                class="portrait-female"
                src="{{ asset('gambar/female_teacher.png') }}"
                alt="Guru perempuan memegang buku catatan"
            >
        </div>

    </section>


    {{-- =========================================================
         FORM LOGIN
         ========================================================= --}}
    <section
        class="login-form"
        aria-labelledby="login-title"
    >

        <header class="div">

            <h1
                id="login-title"
                class="text-wrapper"
            >
                Selamat Datang kembali, Bapak/Ibu Guru!
            </h1>

            <p
                id="login-description"
                class="p"
            >
                Mari rekam pencapaian terbaik siswa hari ini dengan mudah dan cepat.
            </p>

        </header>


        {{-- =====================================================
             PESAN ERROR LOGIN
             ===================================================== --}}
        @if ($errors->any())
            <div class="login-error" role="alert">

                <strong>Login gagal</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif


        {{-- =====================================================
             FORM
             ===================================================== --}}
        <form
            action="{{ route('login.process') }}"
            method="POST"
            aria-describedby="login-description"
        >

            @csrf

            <div class="frame-2">

                {{-- EMAIL --}}
                <div class="input-login">

                    <div class="state-default-type">

                        <img
                            class="vector"
                            src="{{ asset('gambar/gmail_icon.png') }}"
                            alt=""
                            aria-hidden="true"
                        >

                        <input
                            id="email"
                            class="email"
                            name="email"
                            type="email"
                            placeholder="Email Aktif"
                            autocomplete="email"
                            inputmode="email"
                            aria-label="Email aktif"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >

                    </div>

                </div>


                {{-- PASSWORD --}}
                <div class="input-login">

                    <div class="state-default-type">

                        <span
                            class="mdi-password-outline"
                            aria-hidden="true"
                        >
                            <img
                                class="img"
                                src="{{ asset('gambar/password_vektor.png') }}"
                                alt=""
                            >
                        </span>

                        <input
                            id="password"
                            class="password"
                            name="password"
                            type="password"
                            placeholder="Kata Sandi Anda"
                            autocomplete="current-password"
                            aria-label="Kata sandi Anda"
                            required
                        >

                    </div>

                </div>

            </div>


            {{-- BUTTON --}}
            <button
                class="button-dashboard"
                type="submit"
            >
                <span class="dashboard">
                    Masuk
                </span>
            </button>

        </form>

    </section>

</main>

</body>
</html>