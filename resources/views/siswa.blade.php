@extends('layouts.app')

@section('title', 'Data Siswa | Cyber Olympus E-Raport System')

@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/pages/siswa.css') }}"
>

@endpush

@section('content')

<section
    id="data-siswa"
    class="siswa-content"
    aria-labelledby="siswa-page-title"
>

    {{-- =====================================================
         HEADING
         ===================================================== --}}
    <div class="siswa-heading">

        <div class="siswa-heading-text">

            <h1 id="siswa-page-title">
                Data Siswa
            </h1>

            <p>
                Kelola data siswa Cyber Olympus
            </p>

        </div>


        <button
            class="siswa-add-button"
            id="siswa-add-button"
            type="button"
        >

            <svg
                viewBox="0 0 24 24"
                aria-hidden="true"
            >

                <path
                    d="M12 5v14M5 12h14"
                    fill="none"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-width="2"
                />

            </svg>

            <span>
                Tambah Siswa
            </span>

        </button>

    </div>


    {{-- =====================================================
         SEARCH
         ===================================================== --}}
    <section
        class="siswa-search-card"
        aria-label="Pencarian siswa"
    >

        <form
            class="siswa-search-form"
            id="siswa-search-form"
            role="search"
        >

            <label
                class="sr-only"
                for="student-search"
            >
                Cari siswa
            </label>


            <input
                id="student-search"
                name="search"
                type="search"
                placeholder="Cari siswa berdasarkan NISN, Nama Siswa atau Kelas..."
                autocomplete="off"
            >


            <button
                type="submit"
                aria-label="Cari siswa"
            >

                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >

                    <circle
                        cx="11"
                        cy="11"
                        r="6.5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    />

                    <path
                        d="M16 16l4 4"
                        fill="none"
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-width="2"
                    />

                </svg>

            </button>

        </form>

    </section>


    {{-- =====================================================
         TABLE DATA SISWA
         ===================================================== --}}
    <section
        class="siswa-table-card"
        aria-label="Daftar siswa"
    >

        <div class="siswa-table-scroll">

            <div
                class="siswa-table"
                role="table"
                aria-label="Data siswa"
            >

                {{-- =================================================
                     TABLE HEADER
                     ================================================= --}}
                <div
                    class="siswa-table-header"
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
                        Jenis Kelamin
                    </div>

                    <div role="columnheader">
                        Kelas
                    </div>

                    <div role="columnheader">
                        Aksi
                    </div>

                </div>


                {{-- =================================================
                     TABLE BODY
                     ================================================= --}}
                <div
                    class="siswa-table-body"
                    role="rowgroup"
                >

                    {{-- Adit Pratama --}}
                    <div
                        class="siswa-table-row"
                        data-kelas-id="1"
                        role="row"
                    >

                        <div
                            class="cell-no"
                            role="cell"
                        >
                            1
                        </div>

                        <div
                            class="cell-nisn"
                            role="cell"
                        >
                            0012345678
                        </div>

                        <div
                            class="cell-nama"
                            role="cell"
                        >
                            Adit Pratama
                        </div>

                        <div
                            class="cell-jk"
                            role="cell"
                        >
                            L
                        </div>

                        <div
                            class="cell-kelas"
                            role="cell"
                        >
                            7A
                        </div>

                        <div
                            class="siswa-actions"
                            role="cell"
                        >

                            <button
                                class="edit-btn"
                                type="button"
                                aria-label="Edit Adit Pratama"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                    <path
                                        d="M14.5 7.5l2 2"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>


                            <button
                                class="delete-btn"
                                type="button"
                                aria-label="Hapus Adit Pratama"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>


                    {{-- Dimas Ikwani --}}
                    <div
                        class="siswa-table-row"
                        data-kelas-id="1"
                        role="row"
                    >

                        <div class="cell-no" role="cell">
                            2
                        </div>

                        <div class="cell-nisn" role="cell">
                            0012345679
                        </div>

                        <div class="cell-nama" role="cell">
                            Dimas Ikwani
                        </div>

                        <div class="cell-jk" role="cell">
                            L
                        </div>

                        <div class="cell-kelas" role="cell">
                            7A
                        </div>

                        <div class="siswa-actions" role="cell">

                            <button
                                class="edit-btn"
                                type="button"
                                aria-label="Edit Dimas Ikwani"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                    <path
                                        d="M14.5 7.5l2 2"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>


                            <button
                                class="delete-btn"
                                type="button"
                                aria-label="Hapus Dimas Ikwani"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>


                    {{-- Siti Aisyah --}}
                    <div
                        class="siswa-table-row"
                        data-kelas-id="2"
                        role="row"
                    >

                        <div class="cell-no" role="cell">
                            3
                        </div>

                        <div class="cell-nisn" role="cell">
                            0012345680
                        </div>

                        <div class="cell-nama" role="cell">
                            Siti Aisyah
                        </div>

                        <div class="cell-jk" role="cell">
                            P
                        </div>

                        <div class="cell-kelas" role="cell">
                            7B
                        </div>

                        <div class="siswa-actions" role="cell">

                            <button
                                class="edit-btn"
                                type="button"
                                aria-label="Edit Siti Aisyah"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                    <path
                                        d="M14.5 7.5l2 2"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>


                            <button
                                class="delete-btn"
                                type="button"
                                aria-label="Hapus Siti Aisyah"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>


                    {{-- Nabila Putri --}}
                    <div
                        class="siswa-table-row"
                        data-kelas-id="2"
                        role="row"
                    >

                        <div class="cell-no" role="cell">
                            4
                        </div>

                        <div class="cell-nisn" role="cell">
                            0012345681
                        </div>

                        <div class="cell-nama" role="cell">
                            Nabila Putri
                        </div>

                        <div class="cell-jk" role="cell">
                            P
                        </div>

                        <div class="cell-kelas" role="cell">
                            7B
                        </div>

                        <div class="siswa-actions" role="cell">

                            <button
                                class="edit-btn"
                                type="button"
                                aria-label="Edit Nabila Putri"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                    <path
                                        d="M14.5 7.5l2 2"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>


                            <button
                                class="delete-btn"
                                type="button"
                                aria-label="Hapus Nabila Putri"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>


                    {{-- Fajar Ramadhan --}}
                    <div
                        class="siswa-table-row"
                        data-kelas-id="3"
                        role="row"
                    >

                        <div class="cell-no" role="cell">
                            5
                        </div>

                        <div class="cell-nisn" role="cell">
                            0012345682
                        </div>

                        <div class="cell-nama" role="cell">
                            Fajar Ramadhan
                        </div>

                        <div class="cell-jk" role="cell">
                            L
                        </div>

                        <div class="cell-kelas" role="cell">
                            8A
                        </div>

                        <div class="siswa-actions" role="cell">

                            <button
                                class="edit-btn"
                                type="button"
                                aria-label="Edit Fajar Ramadhan"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                    <path
                                        d="M14.5 7.5l2 2"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>


                            <button
                                class="delete-btn"
                                type="button"
                                aria-label="Hapus Fajar Ramadhan"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>


                    {{-- Aulia Rahma --}}
                    <div
                        class="siswa-table-row"
                        data-kelas-id="4"
                        role="row"
                    >

                        <div class="cell-no" role="cell">
                            6
                        </div>

                        <div class="cell-nisn" role="cell">
                            0012345683
                        </div>

                        <div class="cell-nama" role="cell">
                            Aulia Rahma
                        </div>

                        <div class="cell-jk" role="cell">
                            P
                        </div>

                        <div class="cell-kelas" role="cell">
                            8B
                        </div>

                        <div class="siswa-actions" role="cell">

                            <button
                                class="edit-btn"
                                type="button"
                                aria-label="Edit Aulia Rahma"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                    <path
                                        d="M14.5 7.5l2 2"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>


                            <button
                                class="delete-btn"
                                type="button"
                                aria-label="Hapus Aulia Rahma"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</section>


{{-- =========================================================
     MODAL TAMBAH / EDIT SISWA
     ========================================================= --}}
<div
    class="siswa-modal"
    id="siswa-modal"
    hidden
>

    <div
        class="siswa-modal-backdrop"
        data-siswa-modal-close
    ></div>


    <section
        class="siswa-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="siswa-modal-title"
    >

        <div class="siswa-modal-header">

            <div>

                <h2 id="siswa-modal-title">
                    Tambah Data Siswa
                </h2>

                <p id="siswa-modal-desc">
                    Lengkapi data siswa yang akan ditambahkan.
                </p>

            </div>


            <button
                class="siswa-modal-close"
                id="siswa-modal-close"
                type="button"
                aria-label="Tutup modal"
            >

                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >

                    <path
                        d="M6 6l12 12M18 6L6 18"
                        fill="none"
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-width="2"
                    />

                </svg>

            </button>

        </div>


        <form
            class="siswa-form"
            id="siswa-form"
        >

            {{-- NISN --}}
            <div class="siswa-form-group">

                <label for="siswa-nisn">
                    NISN
                </label>

                <input
                    id="siswa-nisn"
                    name="nisn"
                    type="text"
                    inputmode="numeric"
                    maxlength="15"
                    placeholder="Masukkan NISN"
                    autocomplete="off"
                    required
                >

                <small class="siswa-form-help">
                    NISN harus unik dan sesuai data siswa.
                </small>

            </div>


            {{-- NAMA --}}
            <div class="siswa-form-group">

                <label for="siswa-name">
                    Nama Siswa
                </label>

                <input
                    id="siswa-name"
                    name="namaSiswa"
                    type="text"
                    maxlength="40"
                    placeholder="Masukkan nama siswa"
                    autocomplete="off"
                    required
                >

            </div>


            {{-- JENIS KELAMIN --}}
            <div class="siswa-form-group">

                <label for="siswa-jk">
                    Jenis Kelamin
                </label>

                <div class="siswa-select-wrap">

                    <select
                        id="siswa-jk"
                        name="jenisKelamin"
                        required
                    >

                        <option
                            value=""
                            disabled
                            selected
                        >
                            Pilih jenis kelamin
                        </option>

                        <option value="L">
                            Laki-laki
                        </option>

                        <option value="P">
                            Perempuan
                        </option>

                    </select>

                </div>

            </div>


            {{-- KELAS --}}
            <div class="siswa-form-group">

                <label for="siswa-kelas">
                    Kelas
                </label>

                <div class="siswa-select-wrap">

                    <select
                        id="siswa-kelas"
                        name="kelasId"
                        required
                    >

                        <option
                            value=""
                            disabled
                            selected
                        >
                            Pilih kelas
                        </option>

                        <option value="1">
                            7A — Tahun Ajaran 2026/2027
                        </option>

                        <option value="2">
                            7B — Tahun Ajaran 2026/2027
                        </option>

                        <option value="3">
                            8A — Tahun Ajaran 2026/2027
                        </option>

                        <option value="4">
                            8B — Tahun Ajaran 2026/2027
                        </option>

                    </select>

                </div>

                <small class="siswa-form-help">
                    Kelas dipilih dari data kelas yang tersedia.
                </small>

            </div>


            {{-- FORM ACTION --}}
            <div class="siswa-form-actions">

                <button
                    class="siswa-form-cancel"
                    id="siswa-form-cancel"
                    type="button"
                >
                    Batal
                </button>


                <button
                    class="siswa-form-submit"
                    id="siswa-form-submit-btn"
                    type="submit"
                >
                    Simpan Data Siswa
                </button>

            </div>

        </form>

    </section>

</div>


{{-- =========================================================
     MODAL KONFIRMASI HAPUS
     ========================================================= --}}
<div
    class="siswa-modal"
    id="delete-modal"
    hidden
>

    <div
        class="siswa-modal-backdrop"
        data-delete-modal-close
    ></div>


    <section
        class="siswa-modal-dialog delete-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="delete-modal-title"
    >

        <div class="delete-modal-icon">

            <svg
                viewBox="0 0 24 24"
                aria-hidden="true"
            >

                <path
                    d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                    fill="none"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                />

            </svg>

        </div>


        <h2
            class="delete-modal-title"
            id="delete-modal-title"
        >
            Hapus Data Siswa
        </h2>


        <p class="delete-modal-text">

            Apakah Anda yakin ingin menghapus data siswa

            <strong id="delete-student-name"></strong>?

            Tindakan ini tidak dapat dibatalkan.

        </p>


        <div class="siswa-form-actions delete-actions">

            <button
                class="siswa-form-cancel"
                id="delete-form-cancel"
                type="button"
            >
                Batal
            </button>


            <button
                class="siswa-form-delete-confirm"
                id="delete-form-confirm-btn"
                type="button"
            >
                Ya, Hapus Data
            </button>

        </div>

    </section>

</div>

@endsection


@push('scripts')

<script
    src="{{ asset('js/siswa.js') }}"
></script>

@endpush