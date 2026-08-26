<aside class="frame" aria-label="Navigasi utama">

    {{-- =====================================================
         MOBILE MENU TOGGLE
         ===================================================== --}}
    <button
        class="mobile-menu-toggle"
        type="button"
        aria-label="Buka menu navigasi"
        aria-expanded="false"
        aria-controls="mobile-navigation"
    >
        <span></span>
        <span></span>
        <span></span>
    </button>


    {{-- =====================================================
         LOGO + NAMA APLIKASI
         ===================================================== --}}
    <div class="div">

        <div
            class="hugeicons-school"
            aria-hidden="true"
        >
            <img
                class="school-icon-img"
                src="{{ asset('gambar/school_icon.png') }}"
                alt="Logo Sekolah"
            >
        </div>

        <div class="frame-2">

            <p class="text-wrapper">
                Cyber Olympus
            </p>

            <p class="text-wrapper-2">
                E-Raport System
            </p>

        </div>

    </div>


    {{-- =====================================================
         GARIS SIDEBAR
         ===================================================== --}}
    <div
        class="line"
        aria-hidden="true"
    ></div>


    {{-- =====================================================
         NAVIGASI
         ===================================================== --}}
    <nav
        class="frame-3"
        id="mobile-navigation"
        aria-label="Menu dashboard"
    >

        {{-- =================================================
             DASHBOARD
             ================================================= --}}
        <a
            class="{{ request()->routeIs('dashboard') ? 'button-dashboard' : 'div-2' }}"
            href="{{ route('dashboard') }}"
            @if(request()->routeIs('dashboard'))
                aria-current="page"
            @endif
        >

            <img
                class="img-2"
                src="{{ asset('gambar/dashboard_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-4">
                Dashboard
            </span>

        </a>


        {{-- =================================================
             DATA GURU
             ================================================= --}}
        <a
            class="{{ request()->routeIs('guru') || request()->is('data-guru') ? 'button-dashboard' : 'div-2' }}"
            href="{{ route('guru') }}"
            @if(request()->routeIs('guru') || request()->is('data-guru'))
                aria-current="page"
            @endif
        >

            <img
                class="img-2"
                src="{{ asset('gambar/guru_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-4">
                Data Guru
            </span>

        </a>


        {{-- =================================================
             DATA SISWA
             ================================================= --}}
        <a
            class="{{ request()->routeIs('siswa') || request()->is('data-siswa') ? 'button-dashboard' : 'div-2' }}"
            href="{{ route('siswa') }}"
            @if(request()->routeIs('siswa') || request()->is('data-siswa'))
                aria-current="page"
            @endif
        >

            <img
                class="img-2"
                src="{{ asset('gambar/siswa_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-4">
                Data Siswa
            </span>

        </a>


        {{-- =================================================
             DATA KELAS
             ================================================= --}}
        <a
            class="{{ request()->routeIs('kelas') || request()->is('data-kelas') ? 'button-dashboard' : 'div-2' }}"
            href="{{ route('kelas') }}"
            @if(request()->routeIs('kelas') || request()->is('data-kelas'))
                aria-current="page"
            @endif
        >

            <img
                class="img-2"
                src="{{ asset('gambar/kelas_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-4">
                Data Kelas
            </span>

        </a>


        {{-- =================================================
             MATA PELAJARAN
             ================================================= --}}
        <a
            class="{{ request()->routeIs('mapel') || request()->is('mata-pelajaran') ? 'button-dashboard' : 'div-2' }}"
            href="{{ route('mapel') }}"
            @if(request()->routeIs('mapel') || request()->is('mata-pelajaran'))
                aria-current="page"
            @endif
        >

            <img
                class="img-2"
                src="{{ asset('gambar/mapel_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-3">
                Mata Pelajaran
            </span>

        </a>


        {{-- =================================================
             INPUT NILAI
             ================================================= --}}
        <a
            class="{{ request()->routeIs('input-nilai') || request()->is('input-nilai') ? 'button-dashboard' : 'div-2' }}"
            href="{{ route('input-nilai') }}"
            @if(request()->routeIs('input-nilai') || request()->is('input-nilai'))
                aria-current="page"
            @endif
        >

            <img
                class="img-2"
                src="{{ asset('gambar/nilai_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-4">
                Input Nilai
            </span>

        </a>


        {{-- =================================================
            RAPORT
            ================================================= --}}
        <a
            class="{{ request()->routeIs('raport') || request()->is('raport') ? 'button-dashboard' : 'div-2' }}"
            href="{{ route('raport') }}"
            @if(request()->routeIs('raport') || request()->is('raport'))
                aria-current="page"
            @endif
        >

            <img
                class="img-2"
                src="{{ asset('gambar/raport_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-4">
                Raport
            </span>

        </a>


        {{-- =================================================
             PENGGUNA
             ================================================= --}}
        <a
            class="{{ request()->routeIs('pengguna') || request()->is('pengguna') ? 'button-dashboard' : 'div-2' }}"
            href="{{ route('pengguna') }}"
            @if(request()->routeIs('pengguna') || request()->is('pengguna'))
                aria-current="page"
            @endif
        >
            <img
                class="img-2"
                src="{{ asset('gambar/pengguna_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-4">
                Pengguna
            </span>
        </a>


        {{-- =================================================
             LOGOUT
             ================================================= --}}
        <button
            class="button-logout"
            type="button"
        >

            <img
                class="img-2"
                src="{{ asset('gambar/logout_icon.png') }}"
                alt=""
            >

            <span class="text-wrapper-4">
                Logout
            </span>

        </button>

    </nav>

</aside>