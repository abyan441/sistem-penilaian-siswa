@extends('layouts.app')

@section('title', 'Dashboard | Cyber Olympus E-Raport System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
@endpush

@section('content')

    {{-- =====================================================
         JUDUL DASHBOARD
         ===================================================== --}}
    <section
        class="frame-12"
        aria-labelledby="dashboard-title"
    >

        <div class="frame-7">

            <h1
                class="text-wrapper-5"
                id="dashboard-title"
            >
                Dashboard
            </h1>

            <p class="p">
                Selamat datang di Aplikasi E-Raport Cyber Olympus
            </p>

        </div>

    </section>


    {{-- =====================================================
         CARDS RINGKASAN DATA
         ===================================================== --}}
    <section
        class="frame-13"
        aria-label="Ringkasan data sekolah"
    >

        {{-- JUMLAH SISWA --}}
        <article
            class="frame-14"
            id="data-siswa"
        >

            <span
                class="fluent-mdl"
                aria-hidden="true"
            >
                <img
                    class="vector-10"
                    src="{{ asset('gambar/jumlah_siswa.png') }}"
                    alt=""
                >
            </span>

            <div class="frame-15">

                <h2 class="text-wrapper-10">
                    Jumlah Siswa
                </h2>

                <p class="text-wrapper-11">
                    542
                </p>

            </div>

        </article>


        {{-- JUMLAH GURU --}}
        <article
            class="frame-14"
            id="data-guru"
        >

            <span
                class="radix-icons-people"
                aria-hidden="true"
            >
                <img
                    class="vector-11"
                    src="{{ asset('gambar/jumlah_guru.png') }}"
                    alt=""
                >
            </span>

            <div class="frame-15">

                <h2 class="text-wrapper-10">
                    Jumlah Guru
                </h2>

                <p class="text-wrapper-11">
                    42
                </p>

            </div>

        </article>


        {{-- JUMLAH KELAS --}}
        <article
            class="frame-14"
            id="data-kelas"
        >

            <span
                class="group-wrapper"
                aria-hidden="true"
            >
                <img
                    class="vector-12"
                    src="{{ asset('gambar/jumlah_kelas.png') }}"
                    alt=""
                >
            </span>

            <div class="frame-15">

                <h2 class="text-wrapper-10">
                    Jumlah Kelas
                </h2>

                <p class="text-wrapper-11">
                    18
                </p>

            </div>

        </article>

    </section>


    {{-- =====================================================
         GRAFIK
         ===================================================== --}}
    <section
        class="frame-16"
        aria-labelledby="chart-title"
    >

        <figure class="frame-17 student-chart-card">

            <figcaption
                class="text-wrapper-12"
                id="chart-title"
            >
                Perkembangan Rata-rata Nilai Siswa
            </figcaption>

            <div
                class="student-chart"
                role="img"
                aria-label="Grafik perkembangan rata-rata nilai siswa dari tahun ajaran 2025/2026 sampai 2034/2035"
            >

                <svg
                    class="student-chart-svg"
                    viewBox="0 0 1100 360"
                    preserveAspectRatio="none"
                    aria-hidden="true"
                >

                    {{-- GRID HORIZONTAL --}}
                    <g class="chart-grid">

                        <line
                            x1="60"
                            y1="25"
                            x2="1050"
                            y2="25"
                        ></line>

                        <line
                            x1="60"
                            y1="95"
                            x2="1050"
                            y2="95"
                        ></line>

                        <line
                            x1="60"
                            y1="165"
                            x2="1050"
                            y2="165"
                        ></line>

                        <line
                            x1="60"
                            y1="235"
                            x2="1050"
                            y2="235"
                        ></line>

                        <line
                            x1="60"
                            y1="305"
                            x2="1050"
                            y2="305"
                        ></line>

                    </g>


                    {{-- GRID VERTICAL --}}
                    <g class="chart-grid">

                        <line x1="60" y1="25" x2="60" y2="305"></line>
                        <line x1="170" y1="25" x2="170" y2="305"></line>
                        <line x1="280" y1="25" x2="280" y2="305"></line>
                        <line x1="390" y1="25" x2="390" y2="305"></line>
                        <line x1="500" y1="25" x2="500" y2="305"></line>
                        <line x1="610" y1="25" x2="610" y2="305"></line>
                        <line x1="720" y1="25" x2="720" y2="305"></line>
                        <line x1="830" y1="25" x2="830" y2="305"></line>
                        <line x1="940" y1="25" x2="940" y2="305"></line>
                        <line x1="1050" y1="25" x2="1050" y2="305"></line>

                    </g>


                    {{-- LABEL SUMBU Y --}}
                    <g class="chart-y-labels">

                        <text x="43" y="310">0</text>
                        <text x="43" y="240">25</text>
                        <text x="43" y="170">50</text>
                        <text x="43" y="100">75</text>
                        <text x="43" y="30">100</text>

                    </g>


                    {{-- AREA CHART --}}
                    <path
                        class="chart-area"
                        d="
                            M 60 297
                            L 170 288
                            L 280 123
                            L 390 165
                            L 500 59
                            L 610 188
                            L 720 176
                            L 830 36
                            L 940 210
                            L 1050 214
                            L 1050 305
                            L 60 305
                            Z
                        "
                    ></path>


                    {{-- GARIS UTAMA --}}
                    <polyline
                        class="chart-line"
                        points="
                            60,297
                            170,288
                            280,123
                            390,165
                            500,59
                            610,188
                            720,176
                            830,36
                            940,210
                            1050,214
                        "
                    ></polyline>


                    {{-- TITIK DATA --}}
                    <g class="chart-points">

                        <circle cx="60" cy="297" r="5"></circle>
                        <circle cx="170" cy="288" r="5"></circle>
                        <circle cx="280" cy="123" r="5"></circle>
                        <circle cx="390" cy="165" r="5"></circle>
                        <circle cx="500" cy="59" r="5"></circle>
                        <circle cx="610" cy="188" r="5"></circle>
                        <circle cx="720" cy="176" r="5"></circle>
                        <circle cx="830" cy="36" r="5"></circle>
                        <circle cx="940" cy="210" r="5"></circle>
                        <circle cx="1050" cy="214" r="5"></circle>

                    </g>


                    {{-- LABEL TAHUN --}}
                    <g class="chart-x-labels">

                        <text x="60" y="345">2025/2026</text>
                        <text x="170" y="345">2026/2027</text>
                        <text x="280" y="345">2027/2028</text>
                        <text x="390" y="345">2028/2029</text>
                        <text x="500" y="345">2029/2030</text>
                        <text x="610" y="345">2030/2031</text>
                        <text x="720" y="345">2031/2032</text>
                        <text x="830" y="345">2032/2033</text>
                        <text x="940" y="345">2033/2034</text>
                        <text x="1050" y="345">2034/2035</text>

                    </g>

                </svg>

            </div>

        </figure>

    </section>


    {{-- =====================================================
         AKTIVITAS TERBARU
         ===================================================== --}}
    <section
        class="frame-18"
        aria-labelledby="activity-title"
    >

        <h2
            class="text-wrapper-12"
            id="activity-title"
        >
            Aktivitas Terbaru
        </h2>


        <div
            class="frame-19"
            role="table"
            aria-label="Daftar aktivitas terbaru"
        >

            {{-- HEADER TABLE --}}
            <div
                class="frame-20"
                role="row"
            >

                <div
                    class="text-wrapper-20"
                    role="columnheader"
                >
                    Aksi
                </div>

                <div
                    class="text-wrapper-20"
                    role="columnheader"
                >
                    Detail
                </div>

                <div
                    class="text-wrapper-20"
                    role="columnheader"
                >
                    Pengguna
                </div>

                <div
                    class="text-wrapper-20"
                    role="columnheader"
                >
                    Waktu
                </div>

            </div>


            {{-- DATA AKTIVITAS --}}
            <div
                class="frame-21"
                role="rowgroup"
            >

                {{-- AKTIVITAS 1 --}}
                <div
                    class="text-wrapper-21"
                    role="cell"
                >
                    Input Nilai
                </div>

                <div
                    class="text-wrapper-22"
                    role="cell"
                >
                    Matematika - Kelas 6A
                </div>

                <div
                    class="text-wrapper-23"
                    role="cell"
                >
                    Adit Kebugaran
                </div>

                <div
                    class="text-wrapper-24"
                    role="cell"
                >
                    5 menit lalu
                </div>


                {{-- AKTIVITAS 2 --}}
                <div
                    class="text-wrapper-25"
                    role="cell"
                >
                    Cetak Raport
                </div>

                <div
                    class="text-wrapper-26"
                    role="cell"
                >
                    Ahmad Fauzi - Kelas 5B
                </div>

                <div
                    class="text-wrapper-27"
                    role="cell"
                >
                    Dimas Ikwani
                </div>

                <div
                    class="text-wrapper-28"
                    role="cell"
                >
                    30 menit lalu
                </div>


                {{-- AKTIVITAS 3 --}}
                <div
                    class="text-wrapper-29"
                    role="cell"
                >
                    Tambah Siswa
                </div>

                <div
                    class="text-wrapper-30"
                    role="cell"
                >
                    Siswa baru Kelas 1A
                </div>

                <div
                    class="text-wrapper-31"
                    role="cell"
                >
                    Gus Nanang
                </div>

                <div
                    class="text-wrapper-32"
                    role="cell"
                >
                    1 jam lalu
                </div>


                {{-- AKTIVITAS 4 --}}
                <div
                    class="text-wrapper-33"
                    role="cell"
                >
                    Tambah Guru
                </div>

                <div
                    class="text-wrapper-34"
                    role="cell"
                >
                    Guru Baru Bahasa Inggris
                </div>

                <div
                    class="text-wrapper-35"
                    role="cell"
                >
                    By U
                </div>

                <div
                    class="text-wrapper-36"
                    role="cell"
                >
                    1 jam 30 menit lalu
                </div>


                {{-- AKTIVITAS 5 --}}
                <div
                    class="text-wrapper-37"
                    role="cell"
                >
                    Update Data
                </div>

                <div
                    class="text-wrapper-38"
                    role="cell"
                >
                    Data Guru - PaK Budi
                </div>

                <div
                    class="text-wrapper-39"
                    role="cell"
                >
                    Adam Jombang
                </div>

                <div
                    class="text-wrapper-40"
                    role="cell"
                >
                    2 jam lalu
                </div>


                {{-- AKTIVITAS 6 --}}
                <div
                    class="text-wrapper-41"
                    role="cell"
                >
                    Input Nilai
                </div>

                <div
                    class="text-wrapper-42"
                    role="cell"
                >
                    Bahasa Indonesia - Kelas 4C
                </div>

                <div
                    class="text-wrapper-43"
                    role="cell"
                >
                    Fuang Parker
                </div>

                <div
                    class="text-wrapper-44"
                    role="cell"
                >
                    1 hari lalu
                </div>

            </div>

        </div>

    </section>

@endsection