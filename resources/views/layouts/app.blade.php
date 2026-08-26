<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield(
            'title',
            'Dashboard | Cyber Olympus E-Raport System'
        )
    </title>


    {{-- =====================================================
         GLOBAL CSS
         ===================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/globals.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/styleguide.css') }}"
    >


    {{-- =====================================================
         LAYOUT CSS
         ===================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/layouts/sidebar.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/layouts/header.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/layouts/account-modal.css') }}"
    >


    {{-- =====================================================
         BASE LAYOUT
         ===================================================== --}}

    <style>

        /* =================================================
           HTML & BODY
           ================================================= */

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;

            font-family:
                var(
                    --paragraph-p16-regular-font-family
                );
        }


        /* =================================================
           LAYOUT UTAMA
           ================================================= */

        .dashboard {
            background-color: var(--primarypr-10);

            width: 100%;
            height: 100vh;

            display: flex;

            overflow: hidden;
        }


        /* =================================================
           AREA KONTEN UTAMA
           ================================================= */

        .dashboard .frame-4 {
            display: flex;

            flex: 1 1 auto;

            width: auto;
            min-width: 0;

            height: 100vh;

            flex-direction: column;
            align-items: flex-start;

            gap: 10px;

            padding:
                0 0 40px 0;

            overflow-y: auto;
            overflow-x: hidden;

            box-sizing: border-box;
        }


        /* =================================================
           PADDING KONTEN

           Sama dengan Dashboard:
           Desktop  : 24px
           Tablet   : 18px
           HP       : 12px
           HP kecil : 10px
           ================================================= */

        .dashboard .frame-4 > *:not(.frame-5) {
            width: 100%;

            padding-left: 24px;
            padding-right: 24px;

            box-sizing: border-box;
        }


        /* =================================================
           TABLET
           769px - 1100px
           ================================================= */

        @media (min-width: 769px) and (max-width: 1100px) {

            .dashboard .frame-4 {
                width: auto;

                min-width: 0;

                flex:
                    1 1 auto;

                height: 100vh;

                padding-bottom: 32px;

                gap: 12px;
            }


            .dashboard .frame-4 > *:not(.frame-5) {
                padding-left: 18px;
                padding-right: 18px;
            }

        }


        /* =================================================
           MOBILE
           <= 768px

           Ini bagian penting yang sebelumnya hanya ada
           di dashboard.css.
           ================================================= */

        @media (max-width: 768px) {

            html,
            body {
                width: 100%;
                height: 100%;

                overflow: hidden;
            }


            .dashboard {
                width: 100%;

                height: 100dvh;
                min-height: 100dvh;

                display: flex;

                flex-direction: column;

                overflow: hidden;
            }


            .dashboard .frame-4 {
                width: 100%;

                min-width: 0;

                height: auto;
                min-height: 0;

                flex:
                    1 1 auto;

                overflow-y: auto;
                overflow-x: hidden;

                padding:
                    0 0 24px;

                gap: 10px;
            }


            .dashboard .frame-4 > *:not(.frame-5) {
                width: 100%;

                padding-left: 12px;
                padding-right: 12px;

                box-sizing: border-box;
            }

        }


        /* =================================================
           EXTRA SMALL PHONE
           <= 480px
           ================================================= */

        @media (max-width: 480px) {

            .dashboard .frame-4 {
                padding-bottom: 20px;
            }


            .dashboard .frame-4 > *:not(.frame-5) {
                padding-left: 10px;
                padding-right: 10px;
            }

        }

    </style>


    {{-- =====================================================
         CSS KHUSUS HALAMAN
         ===================================================== --}}

    @stack('styles')

</head>


<body>


    {{-- =====================================================
         LAYOUT UTAMA
         ===================================================== --}}

    <main class="dashboard">


        {{-- =================================================
             SIDEBAR
             ================================================= --}}

        @include('layouts.sidebar')


        {{-- =================================================
             AREA UTAMA
             ================================================= --}}

        <section class="frame-4">


            {{-- =================================================
                 HEADER
                 ================================================= --}}

            @include('layouts.header')


            {{-- =================================================
                 GARIS PEMBATAS
                 ================================================= --}}

            <div
                class="line"
                aria-hidden="true"
            ></div>


            {{-- =================================================
                 KONTEN HALAMAN
                 ================================================= --}}

            @yield('content')


        </section>

    </main>


    {{-- =====================================================
         JAVASCRIPT LAYOUT
         ===================================================== --}}

    <script
        src="{{ asset('js/sidebar.js') }}"
    ></script>

    <script
        src="{{ asset('js/header.js') }}"
    ></script>

    <script
        src="{{ asset('js/account-modal.js') }}"
    ></script>

    <script
        src="{{ asset('js/notifications.js') }}"
    ></script>


    {{-- =====================================================
         JAVASCRIPT KHUSUS HALAMAN
         ===================================================== --}}

    @stack('scripts')


</body>

</html>