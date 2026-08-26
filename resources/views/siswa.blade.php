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
    aria-labelledby="siswa-page-title"
    class="siswa-content"
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
                aria-hidden="true"
                viewBox="0 0 24 24"
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
        aria-label="Pencarian siswa"
        class="siswa-search-card"
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
                autocomplete="off"
                id="student-search"
                name="search"
                placeholder="Cari siswa berdasarkan NISN, Nama Siswa atau Kelas..."
                type="search"
            >


            <button
                aria-label="Cari siswa"
                type="submit"
            >

                <svg
                    aria-hidden="true"
                    viewBox="0 0 24 24"
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
         TABLE
         ===================================================== --}}

    <section
        aria-label="Daftar siswa"
        class="siswa-table-card"
    >

        <div class="siswa-table-scroll">

            <div
                aria-label="Data siswa"
                class="siswa-table"
                role="table"
            >


                {{-- ===============================
                     TABLE HEADER
                     =============================== --}}

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


                {{-- ===============================
                     TABLE BODY
                     =============================== --}}

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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                                    aria-hidden="true"
                                    viewBox="0 0 24 24"
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
                    aria-hidden="true"
                    viewBox="0 0 24 24"
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

            <div class="siswa-form-group">

                <label for="siswa-nisn">
                    NISN
                </label>

                <input
                    autocomplete="off"
                    id="siswa-nisn"
                    inputmode="numeric"
                    maxlength="15"
                    name="nisn"
                    placeholder="Masukkan NISN"
                    required
                    type="text"
                >

                <small class="siswa-form-help">
                    NISN harus unik dan sesuai data siswa.
                </small>

            </div>


            <div class="siswa-form-group">

                <label for="siswa-name">
                    Nama Siswa
                </label>

                <input
                    autocomplete="off"
                    id="siswa-name"
                    maxlength="40"
                    name="namaSiswa"
                    placeholder="Masukkan nama siswa"
                    required
                    type="text"
                >

            </div>


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
                            disabled
                            selected
                            value=""
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
                            disabled
                            selected
                            value=""
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
                aria-hidden="true"
                viewBox="0 0 24 24"
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

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ELEMENT
       ===================================================== */

    const addButton =
        document.querySelector('#siswa-add-button');

    const modal =
        document.querySelector('#siswa-modal');

    const deleteModal =
        document.querySelector('#delete-modal');

    const form =
        document.querySelector('#siswa-form');

    const closeButton =
        document.querySelector('#siswa-modal-close');

    const cancelButton =
        document.querySelector('#siswa-form-cancel');

    const submitButton =
        document.querySelector('#siswa-form-submit-btn');

    const deleteCancelButton =
        document.querySelector('#delete-form-cancel');

    const deleteConfirmButton =
        document.querySelector('#delete-form-confirm-btn');

    const tableBody =
        document.querySelector('.siswa-table-body');

    const searchForm =
        document.querySelector('#siswa-search-form');

    const searchInput =
        document.querySelector('#student-search');

    const nisnInput =
        document.querySelector('#siswa-nisn');

    const nameInput =
        document.querySelector('#siswa-name');

    const jkInput =
        document.querySelector('#siswa-jk');

    const kelasInput =
        document.querySelector('#siswa-kelas');

    const modalTitle =
        document.querySelector('#siswa-modal-title');

    const modalDescription =
        document.querySelector('#siswa-modal-desc');

    const deleteStudentName =
        document.querySelector('#delete-student-name');


    let editingRow = null;

    let deletingRow = null;


    /* =====================================================
       MODAL TAMBAH / EDIT
       ===================================================== */

    function openStudentModal(edit = false) {

        if (!modal) {
            return;
        }


        modalTitle.textContent =
            edit
                ? 'Edit Data Siswa'
                : 'Tambah Data Siswa';


        modalDescription.textContent =
            edit
                ? 'Ubah rincian data siswa di bawah ini.'
                : 'Lengkapi data siswa yang akan ditambahkan.';


        submitButton.textContent =
            edit
                ? 'Simpan Perubahan'
                : 'Simpan Data Siswa';


        modal.hidden = false;

        document.body.classList.add(
            'siswa-modal-open'
        );

    }


    function closeStudentModal() {

        if (!modal) {
            return;
        }


        modal.hidden = true;

        document.body.classList.remove(
            'siswa-modal-open'
        );


        if (form) {
            form.reset();
        }


        editingRow = null;

    }


    /* =====================================================
       TAMBAH SISWA
       ===================================================== */

    if (addButton) {

        addButton.addEventListener(
            'click',
            function () {

                if (form) {
                    form.reset();
                }

                editingRow = null;

                openStudentModal(false);

                setTimeout(
                    function () {

                        if (nisnInput) {
                            nisnInput.focus();
                        }

                    },
                    100
                );

            }
        );

    }


    /* =====================================================
       CLOSE MODAL
       ===================================================== */

    if (closeButton) {

        closeButton.addEventListener(
            'click',
            closeStudentModal
        );

    }


    if (cancelButton) {

        cancelButton.addEventListener(
            'click',
            closeStudentModal
        );

    }


    if (modal) {

        modal
            .querySelectorAll(
                '[data-siswa-modal-close]'
            )
            .forEach(
                function (element) {

                    element.addEventListener(
                        'click',
                        closeStudentModal
                    );

                }
            );

    }


    /* =====================================================
       KELAS
       ===================================================== */

    function getSelectedClassName() {

        if (!kelasInput) {
            return '';
        }


        const selectedOption =
            kelasInput.options[
                kelasInput.selectedIndex
            ];


        if (!selectedOption) {
            return '';
        }


        return (
            selectedOption.textContent
                .split(' — ')[0]
                .trim()
        );

    }


    /* =====================================================
       UPDATE NOMOR
       ===================================================== */

    function updateNumbers() {

        if (!tableBody) {
            return;
        }


        tableBody
            .querySelectorAll(
                '.siswa-table-row'
            )
            .forEach(
                function (row, index) {

                    const number =
                        row.querySelector(
                            '.cell-no'
                        );

                    if (number) {
                        number.textContent =
                            index + 1;
                    }

                }
            );

    }


    /* =====================================================
       EDIT / DELETE TABLE
       ===================================================== */

    if (tableBody) {

        tableBody.addEventListener(
            'click',
            function (event) {

                const editButton =
                    event.target.closest(
                        '.edit-btn'
                    );

                const deleteButton =
                    event.target.closest(
                        '.delete-btn'
                    );


                /* =========================================
                   EDIT
                   ========================================= */

                if (editButton) {

                    editingRow =
                        editButton.closest(
                            '.siswa-table-row'
                        );


                    if (!editingRow) {
                        return;
                    }


                    const nisn =
                        editingRow.querySelector(
                            '.cell-nisn'
                        );

                    const name =
                        editingRow.querySelector(
                            '.cell-nama'
                        );

                    const jk =
                        editingRow.querySelector(
                            '.cell-jk'
                        );


                    if (nisnInput) {
                        nisnInput.value =
                            nisn.textContent.trim();
                    }


                    if (nameInput) {
                        nameInput.value =
                            name.textContent.trim();
                    }


                    if (jkInput) {
                        jkInput.value =
                            jk.textContent.trim();
                    }


                    if (kelasInput) {
                        kelasInput.value =
                            editingRow.dataset.kelasId || '';
                    }


                    openStudentModal(true);

                    return;

                }


                /* =========================================
                   DELETE
                   ========================================= */

                if (deleteButton) {

                    deletingRow =
                        deleteButton.closest(
                            '.siswa-table-row'
                        );


                    if (!deletingRow) {
                        return;
                    }


                    const studentName =
                        deletingRow.querySelector(
                            '.cell-nama'
                        );


                    if (deleteStudentName) {

                        deleteStudentName.textContent =
                            studentName
                                .textContent
                                .trim();

                    }


                    deleteModal.hidden = false;

                    document.body.classList.add(
                        'siswa-modal-open'
                    );

                }

            }
        );

    }


    /* =====================================================
       SIMPAN TAMBAH / EDIT
       ===================================================== */

    if (form) {

        form.addEventListener(
            'submit',
            function (event) {

                event.preventDefault();


                const nisn =
                    nisnInput.value.trim();

                const name =
                    nameInput.value.trim();

                const jk =
                    jkInput.value;

                const kelas =
                    kelasInput.value;


                if (
                    !nisn ||
                    !name ||
                    !jk ||
                    !kelas
                ) {
                    showAppToast('NISN, nama, jenis kelamin, dan kelas wajib diisi.');
                    return;
                }


                const className =
                    getSelectedClassName();


                /* =========================================
                   EDIT
                   ========================================= */

                if (editingRow) {

                    editingRow.querySelector(
                        '.cell-nisn'
                    ).textContent = nisn;


                    editingRow.querySelector(
                        '.cell-nama'
                    ).textContent = name;


                    editingRow.querySelector(
                        '.cell-jk'
                    ).textContent = jk;


                    editingRow.querySelector(
                        '.cell-kelas'
                    ).textContent = className;


                    editingRow.dataset.kelasId =
                        kelas;


                    editingRow
                        .querySelector('.edit-btn')
                        .setAttribute(
                            'aria-label',
                            'Edit ' + name
                        );


                    editingRow
                        .querySelector('.delete-btn')
                        .setAttribute(
                            'aria-label',
                            'Hapus ' + name
                        );


                    closeStudentModal();

                    showAppToast('Data siswa berhasil diperbarui.', 'success');

                    return;

                }


                /* =========================================
                   TAMBAH
                   ========================================= */

                const row =
                    document.createElement('div');


                row.className =
                    'siswa-table-row';


                row.setAttribute(
                    'role',
                    'row'
                );


                row.dataset.kelasId =
                    kelas;


                const number =
                    tableBody.querySelectorAll(
                        '.siswa-table-row'
                    ).length + 1;


                row.innerHTML = `

                    <div
                        class="cell-no"
                        role="cell"
                    >
                        ${number}
                    </div>

                    <div
                        class="cell-nisn"
                        role="cell"
                    >
                        ${nisn}
                    </div>

                    <div
                        class="cell-nama"
                        role="cell"
                    >
                        ${name}
                    </div>

                    <div
                        class="cell-jk"
                        role="cell"
                    >
                        ${jk}
                    </div>

                    <div
                        class="cell-kelas"
                        role="cell"
                    >
                        ${className}
                    </div>

                    <div
                        class="siswa-actions"
                        role="cell"
                    >

                        <button
                            type="button"
                            class="edit-btn"
                            aria-label="Edit ${name}"
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
                            type="button"
                            class="delete-btn"
                            aria-label="Hapus ${name}"
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

                `;


                tableBody.appendChild(row);


                closeStudentModal();

                showAppToast('Data siswa berhasil ditambahkan.', 'success');

            }
        );

    }


    /* =====================================================
       DELETE MODAL
       ===================================================== */

    function closeDeleteModal() {

        if (!deleteModal) {
            return;
        }


        deleteModal.hidden = true;

        document.body.classList.remove(
            'siswa-modal-open'
        );


        deletingRow = null;

    }


    if (deleteCancelButton) {

        deleteCancelButton.addEventListener(
            'click',
            closeDeleteModal
        );

    }


    if (deleteModal) {

        deleteModal
            .querySelectorAll(
                '[data-delete-modal-close]'
            )
            .forEach(
                function (element) {

                    element.addEventListener(
                        'click',
                        closeDeleteModal
                    );

                }
            );

    }


    if (deleteConfirmButton) {

        deleteConfirmButton.addEventListener(
            'click',
            function () {

                if (deletingRow) {

                    deletingRow.remove();

                    updateNumbers();

                }


                closeDeleteModal();

                showAppToast('Data siswa berhasil dihapus.', 'success');

            }
        );

    }


    /* =====================================================
       SEARCH
       ===================================================== */

    if (searchForm) {

        searchForm.addEventListener(
            'submit',
            function (event) {

                event.preventDefault();

            }
        );

    }


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                const keyword =
                    searchInput.value
                        .trim()
                        .toLowerCase();


                const rows =
                    tableBody.querySelectorAll(
                        '.siswa-table-row'
                    );


                rows.forEach(
                    function (row) {

                        const text =
                            row.textContent
                                .toLowerCase();


                        row.style.display =
                            text.includes(keyword)
                                ? ''
                                : 'none';

                    }
                );


                /*
                 * Nomor tetap mengikuti data asli.
                 * Tidak mengubah nomor ketika search.
                 */

            }
        );

    }


    /* =====================================================
       ESCAPE
       ===================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (event.key !== 'Escape') {
                return;
            }


            if (
                modal &&
                !modal.hidden
            ) {
                closeStudentModal();
            }


            if (
                deleteModal &&
                !deleteModal.hidden
            ) {
                closeDeleteModal();
            }

        }
    );

});

</script>

@endpush