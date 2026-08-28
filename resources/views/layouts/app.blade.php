<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield(
            'title',
            'Dashboard | Cyber Olympus E-Raport System'
        )
    </title>


    {{-- GLOBAL CSS --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/globals.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/styleguide.css') }}"
    >


    {{-- LAYOUT CSS --}}

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


    {{-- BASE LAYOUT --}}

    <style>

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


        .dashboard {
            width: 100%;
            height: 100vh;

            margin: 0;
            padding: 0;

            display: flex;

            background-color: var(--primarypr-10);

            overflow: hidden;
        }


        .dashboard .frame-4 {
            flex: 1 1 auto;

            width: auto;
            min-width: 0;
            height: 100vh;

            margin: 0;
            padding: 0 0 40px 0;

            display: flex;
            flex-direction: column;
            align-items: stretch;

            gap: 10px;

            box-sizing: border-box;

            overflow-x: hidden;
            overflow-y: auto;
        }


        .dashboard .frame-4 > *:not(.frame-5) {
            width: 100%;

            box-sizing: border-box;

            padding-left: 24px;
            padding-right: 24px;
        }


        /* =====================================================
           SIDEBAR GLOBAL - POSISI LOGOUT
           =====================================================

           Disamakan dengan sidebar pada Preview Raport.
           Logout selalu terdorong ke bagian paling bawah
           sidebar pada desktop/tablet.
        */

        .dashboard .frame-3 .logout-form {
            margin-top: auto;
        }

        .dashboard .frame-3 .button-logout {
            flex-shrink: 0;
        }


        @media (min-width: 769px) and (max-width: 1100px) {

            .dashboard .frame-4 {
                width: auto;
                min-width: 0;

                height: 100vh;

                padding-bottom: 32px;

                gap: 12px;
            }


            .dashboard .frame-4 > *:not(.frame-5) {
                padding-left: 18px;
                padding-right: 18px;
            }

        }


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

                margin: 0;
                padding: 0;

                display: flex;
                flex-direction: column;

                overflow: hidden;
            }


            .dashboard .frame-4 {
                width: 100%;
                min-width: 0;

                height: auto;
                min-height: 0;

                flex: 1 1 auto;

                margin: 0;
                padding: 0 0 24px 0;

                gap: 10px;

                overflow-x: hidden;
                overflow-y: auto;
            }


            .dashboard .frame-4 > *:not(.frame-5) {
                width: 100%;

                padding-left: 12px;
                padding-right: 12px;

                box-sizing: border-box;
            }

            .dashboard .frame-3 .logout-form {
                margin-top: 0;
            }

        }


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


    @stack('styles')

</head>


<body>

    <main class="dashboard">

        @include('layouts.sidebar')

        <section class="frame-4">

            @include('layouts.header')

            <div
                class="line"
                aria-hidden="true"
            ></div>

            @yield('content')

        </section>

    </main>


    <script src="{{ asset('js/sidebar.js') }}"></script>

    <script src="{{ asset('js/header.js') }}"></script>

    <script src="{{ asset('js/account-modal.js') }}"></script>

    <script src="{{ asset('js/notifications.js') }}"></script>

    @stack('scripts')

</body>

</html>