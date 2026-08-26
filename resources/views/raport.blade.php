@extends('layouts.app')

@section('title', 'Raport | Cyber Olympus E-Raport System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/raport.css') }}">
@endpush

@section('content')

<section class="raport-page" id="raport" aria-labelledby="raport-title">

    {{-- =====================================================
         JUDUL HALAMAN
         ===================================================== --}}
    <header class="raport-heading">

        <div class="raport-heading-copy">

            <h1 id="raport-title">
                Cetak Raport
            </h1>

            <p>
                Preview dan cetak raport siswa
            </p>

        </div>

    </header>


    {{-- =====================================================
         FILTER RAPORT
         ===================================================== --}}
    <section
        class="raport-filter-card"
        aria-label="Filter raport"
    >

        <form
            class="raport-filter-form"
            id="raport-filter-form"
        >

            <div class="raport-filter-fields">

                {{-- PILIH SISWA --}}
                <label class="raport-field">

                    <span>
                        Pilih Siswa
                    </span>

                    <select
                        name="siswa"
                        id="raport-siswa"
                        aria-label="Pilih Siswa"
                    >

                        <option value="">
                            -- Pilih Siswa --
                        </option>

                        <option value="123456789-ahmad-fauzi">
                            Ahmad Fauzi
                        </option>

                        <option value="123456789-dimpels">
                            Dimpels
                        </option>

                        <option value="123456789-aceng">
                            Aceng
                        </option>

                        <option value="123456789-ayahab">
                            Ayahab
                        </option>

                        <option value="123456789-racil">
                            Racil
                        </option>

                    </select>

                </label>


                {{-- SEMESTER --}}
                <label class="raport-field">

                    <span>
                        Semester
                    </span>

                    <select
                        name="semester"
                        id="raport-semester"
                        aria-label="Semester"
                    >

                        <option
                            value="1"
                            selected
                        >
                            Semester 1 (Ganjil)
                        </option>

                        <option value="2">
                            Semester 2 (Genap)
                        </option>

                    </select>

                </label>


                {{-- TAHUN AJARAN --}}
                <label class="raport-field">

                    <span>
                        Tahun Ajaran
                    </span>

                    <select
                        name="tahun-ajaran"
                        id="raport-tahun-ajaran"
                        aria-label="Tahun Ajaran"
                    >

                        <option
                            value="2025-2026"
                            selected
                        >
                            2025/2026
                        </option>

                    </select>

                </label>

            </div>


            {{-- =================================================
                 TOMBOL AKSI
                 ================================================= --}}
            <div class="raport-filter-actions">

                <button
                    class="raport-preview-button"
                    type="submit"
                    id="raport-preview-button"
                >

                    <svg
                        class="raport-button-icon"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                        focusable="false"
                    >

                        <path
                            d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                        ></path>

                        <circle
                            cx="12"
                            cy="12"
                            r="2.7"
                        ></circle>

                    </svg>

                    <span>
                        Preview Raport
                    </span>

                </button>


                <button
                    class="raport-pdf-button"
                    type="button"
                    id="raport-pdf-button"
                >

                    <svg
                        class="raport-button-icon"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                        focusable="false"
                    >

                        <path
                            d="M12 3v11"
                        ></path>

                        <path
                            d="m7.5 10.5 4.5 4.5 4.5-4.5"
                        ></path>

                        <path
                            d="M5 20h14"
                        ></path>

                    </svg>

                    <span>
                        Cetak PDF
                    </span>

                </button>

            </div>

        </form>

    </section>


    {{-- =====================================================
         SEARCH SISWA
         ===================================================== --}}
    <section
        class="raport-search-card"
        aria-label="Pencarian siswa"
    >

        <label class="raport-search-box">

            <input
                class="raport-search-input"
                id="raport-search-input"
                type="search"
                name="cari-siswa"
                placeholder="Cari siswa berdasarkan NISN, Nama atau Kelas..."
                autocomplete="off"
            >

            <svg
                class="raport-search-icon"
                viewBox="0 0 24 24"
                aria-hidden="true"
                focusable="false"
            >

                <circle
                    cx="11"
                    cy="11"
                    r="6.5"
                ></circle>

                <path
                    d="M16 16L21 21"
                ></path>

            </svg>

        </label>

    </section>


    {{-- =====================================================
         TABEL DAFTAR SISWA
         ===================================================== --}}
    <section
        class="raport-table-card"
        aria-label="Daftar siswa"
    >

        {{-- HEADER TABEL --}}
        <div
            class="raport-table-head"
            role="row"
        >

            <div role="columnheader">
                No
            </div>

            <div role="columnheader">
                NISN
            </div>

            <div role="columnheader">
                Nama Siswa
            </div>

            <div role="columnheader">
                Kelas
            </div>

            <div role="columnheader">
                Semester
            </div>

            <div role="columnheader">
                Aksi
            </div>

        </div>


        {{-- BODY TABEL --}}
        <div
            class="raport-table-body"
            id="raport-table-body"
            role="rowgroup"
        >

            {{-- =================================================
                 AHMAD FAUZI
                 ================================================= --}}
            <div
                class="raport-table-row"
                role="row"
                data-student="123456789 Ahmad Fauzi 6A Semester 1"
            >

                <div role="gridcell">
                    1
                </div>

                <div role="gridcell">
                    123456789
                </div>

                <div role="gridcell">
                    Ahmad Fauzi
                </div>

                <div role="gridcell">

                    <span class="raport-class-badge">
                        6A
                    </span>

                </div>

                <div role="gridcell">
                    Semester 1
                </div>

                <div
                    class="raport-actions"
                    role="gridcell"
                >

                    <button
                        type="button"
                        class="raport-action-preview"
                        aria-label="Preview raport Ahmad Fauzi"
                        data-student-id="123456789-ahmad-fauzi"
                        data-student-name="Ahmad Fauzi"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path
                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                            ></path>

                            <circle
                                cx="12"
                                cy="12"
                                r="2.7"
                            ></circle>

                        </svg>

                    </button>


                    <button
                        type="button"
                        class="raport-action-download"
                        aria-label="Unduh raport Ahmad Fauzi"
                        data-student-name="Ahmad Fauzi"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path
                                d="M12 3v11"
                            ></path>

                            <path
                                d="m7.5 10.5 4.5 4.5 4.5-4.5"
                            ></path>

                            <path
                                d="M5 20h14"
                            ></path>

                        </svg>

                    </button>

                </div>

            </div>


            {{-- =================================================
                 DIMPELS
                 ================================================= --}}
            <div
                class="raport-table-row"
                role="row"
                data-student="123456789 Dimpels 5C Semester 1"
            >

                <div role="gridcell">
                    2
                </div>

                <div role="gridcell">
                    123456789
                </div>

                <div role="gridcell">
                    Dimpels
                </div>

                <div role="gridcell">

                    <span class="raport-class-badge">
                        5C
                    </span>

                </div>

                <div role="gridcell">
                    Semester 1
                </div>

                <div
                    class="raport-actions"
                    role="gridcell"
                >

                    <button
                        type="button"
                        class="raport-action-preview"
                        aria-label="Preview raport Dimpels"
                        data-student-id="123456789-dimpels"
                        data-student-name="Dimpels"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path
                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                            ></path>

                            <circle
                                cx="12"
                                cy="12"
                                r="2.7"
                            ></circle>

                        </svg>

                    </button>

                    <button
                        type="button"
                        class="raport-action-download"
                        aria-label="Unduh raport Dimpels"
                        data-student-name="Dimpels"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path d="M12 3v11"></path>

                            <path
                                d="m7.5 10.5 4.5 4.5 4.5-4.5"
                            ></path>

                            <path d="M5 20h14"></path>

                        </svg>

                    </button>

                </div>

            </div>


            {{-- =================================================
                 ACENG
                 ================================================= --}}
            <div
                class="raport-table-row"
                role="row"
                data-student="123456789 Aceng 4A Semester 1"
            >

                <div role="gridcell">
                    3
                </div>

                <div role="gridcell">
                    123456789
                </div>

                <div role="gridcell">
                    Aceng
                </div>

                <div role="gridcell">

                    <span class="raport-class-badge">
                        4A
                    </span>

                </div>

                <div role="gridcell">
                    Semester 1
                </div>

                <div
                    class="raport-actions"
                    role="gridcell"
                >

                    <button
                        type="button"
                        class="raport-action-preview"
                        aria-label="Preview raport Aceng"
                        data-student-id="123456789-aceng"
                        data-student-name="Aceng"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path
                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                            ></path>

                            <circle
                                cx="12"
                                cy="12"
                                r="2.7"
                            ></circle>

                        </svg>

                    </button>

                    <button
                        type="button"
                        class="raport-action-download"
                        aria-label="Unduh raport Aceng"
                        data-student-name="Aceng"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path d="M12 3v11"></path>

                            <path
                                d="m7.5 10.5 4.5 4.5 4.5-4.5"
                            ></path>

                            <path d="M5 20h14"></path>

                        </svg>

                    </button>

                </div>

            </div>


            {{-- =================================================
                 AYAHAB
                 ================================================= --}}
            <div
                class="raport-table-row"
                role="row"
                data-student="123456789 Ayahab 4B Semester 1"
            >

                <div role="gridcell">
                    4
                </div>

                <div role="gridcell">
                    123456789
                </div>

                <div role="gridcell">
                    Ayahab
                </div>

                <div role="gridcell">

                    <span class="raport-class-badge">
                        4B
                    </span>

                </div>

                <div role="gridcell">
                    Semester 1
                </div>

                <div
                    class="raport-actions"
                    role="gridcell"
                >

                    <button
                        type="button"
                        class="raport-action-preview"
                        aria-label="Preview raport Ayahab"
                        data-student-id="123456789-ayahab"
                        data-student-name="Ayahab"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path
                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                            ></path>

                            <circle
                                cx="12"
                                cy="12"
                                r="2.7"
                            ></circle>

                        </svg>

                    </button>

                    <button
                        type="button"
                        class="raport-action-download"
                        aria-label="Unduh raport Ayahab"
                        data-student-name="Ayahab"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path d="M12 3v11"></path>

                            <path
                                d="m7.5 10.5 4.5 4.5 4.5-4.5"
                            ></path>

                            <path d="M5 20h14"></path>

                        </svg>

                    </button>

                </div>

            </div>


            {{-- =================================================
                 RACIL
                 ================================================= --}}
            <div
                class="raport-table-row"
                role="row"
                data-student="123456789 Racil 3C Semester 1"
            >

                <div role="gridcell">
                    6
                </div>

                <div role="gridcell">
                    123456789
                </div>

                <div role="gridcell">
                    Racil
                </div>

                <div role="gridcell">

                    <span class="raport-class-badge">
                        3C
                    </span>

                </div>

                <div role="gridcell">
                    Semester 1
                </div>

                <div
                    class="raport-actions"
                    role="gridcell"
                >

                    <button
                        type="button"
                        class="raport-action-preview"
                        aria-label="Preview raport Racil"
                        data-student-id="123456789-racil"
                        data-student-name="Racil"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path
                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                            ></path>

                            <circle
                                cx="12"
                                cy="12"
                                r="2.7"
                            ></circle>

                        </svg>

                    </button>

                    <button
                        type="button"
                        class="raport-action-download"
                        aria-label="Unduh raport Racil"
                        data-student-name="Racil"
                    >

                        <svg
                            class="raport-button-icon"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                            focusable="false"
                        >

                            <path d="M12 3v11"></path>

                            <path
                                d="m7.5 10.5 4.5 4.5 4.5-4.5"
                            ></path>

                            <path d="M5 20h14"></path>

                        </svg>

                    </button>

                </div>

            </div>

        </div>

    </section>

</section>

@endsection


@push('scripts')
    <script src="{{ asset('js/raport.js') }}"></script>
@endpush