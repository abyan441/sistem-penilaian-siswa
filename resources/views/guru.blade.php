    @extends('layouts.app')

    @section('title', 'Data Guru | Cyber Olympus E-Raport System')

    @section('content')

    <section
        class="guru-content"
        aria-labelledby="guru-page-title"
    >

        {{-- =====================================================
            HEADER CONTENT
            ===================================================== --}}
        <div class="guru-heading">

            <div class="guru-heading-text">

                <h1 id="guru-page-title">
                    Data Guru
                </h1>

                <p>
                    Kelola data guru Cyber Olympus
                </p>

            </div>


            <button
                class="guru-add-button"
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
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                </svg>

                <span>
                    Tambah Guru
                </span>

            </button>

        </div>



        {{-- =====================================================
            SEARCH
            ===================================================== --}}
        <section
            class="guru-search-card"
            aria-label="Pencarian guru"
        >

            <form
                class="guru-search-form"
                id="guru-search-form"
                role="search"
            >

                <label
                    class="sr-only"
                    for="teacher-search"
                >
                    Cari guru
                </label>


                <input
                    id="teacher-search"
                    type="search"
                    name="search"
                    placeholder="Cari guru berdasarkan NIP, Nama atau Mata Pelajaran..."
                    autocomplete="off"
                >


                <button
                    type="submit"
                    aria-label="Cari guru"
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
                            stroke-width="2"
                            stroke-linecap="round"
                        />

                    </svg>

                </button>

            </form>

        </section>



        {{-- =====================================================
            TABLE DATA GURU
            ===================================================== --}}
        <section
            class="guru-table-card"
            aria-label="Daftar guru"
        >

            <div class="guru-table-scroll">

                <div
                    class="guru-table"
                    role="table"
                    aria-label="Data guru"
                >

                    {{-- HEADER --}}
                    <div
                        class="guru-table-header"
                        role="row"
                    >

                        <div role="columnheader">
                            No
                        </div>

                        <div role="columnheader">
                            NIP
                        </div>

                        <div role="columnheader">
                            Nama Guru
                        </div>

                        <div role="columnheader">
                            Mata Pelajaran
                        </div>

                        <div role="columnheader">
                            Aksi
                        </div>

                    </div>


                    {{-- BODY --}}
                    <div
                        class="guru-table-body"
                        role="rowgroup"
                    >

                        {{-- DATA 1 --}}
                        <div
                            class="guru-table-row"
                            role="row"
                        >

                            <div
                                role="cell"
                                class="cell-no"
                            >
                                1
                            </div>

                            <div
                                role="cell"
                                class="cell-nip"
                            >
                                123456789
                            </div>

                            <div
                                role="cell"
                                class="cell-nama"
                            >
                                Adit Kebugaran
                            </div>

                            <div
                                role="cell"
                                class="cell-mapel"
                            >
                                Seni Budaya
                            </div>

                            <div
                                class="guru-actions"
                                role="cell"
                            >

                                <button
                                    type="button"
                                    aria-label="Edit Adit Kebugaran"
                                    class="edit-btn"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >

                                        <path
                                            d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linejoin="round"
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
                                    aria-label="Hapus Adit Kebugaran"
                                    class="delete-btn"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >

                                        <path
                                            d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />

                                    </svg>

                                </button>

                            </div>

                        </div>


                        {{-- DATA 2 --}}
                        <div
                            class="guru-table-row"
                            role="row"
                        >

                            <div
                                role="cell"
                                class="cell-no"
                            >
                                2
                            </div>

                            <div
                                role="cell"
                                class="cell-nip"
                            >
                                123456789
                            </div>

                            <div
                                role="cell"
                                class="cell-nama"
                            >
                                Dimas Ikwani
                            </div>

                            <div
                                role="cell"
                                class="cell-mapel"
                            >
                                Pendidikan Kewarganegaraan
                            </div>

                            <div
                                class="guru-actions"
                                role="cell"
                            >

                                <button
                                    type="button"
                                    aria-label="Edit Dimas Ikwani"
                                    class="edit-btn"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >

                                        <path
                                            d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linejoin="round"
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
                                    aria-label="Hapus Dimas Ikwani"
                                    class="delete-btn"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >

                                        <path
                                            d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
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
        MODAL TAMBAH / EDIT GURU
        ========================================================= --}}
    <div
        class="guru-modal"
        id="guru-modal"
        hidden
    >

        <div
            class="guru-modal-backdrop"
            data-guru-modal-close
        ></div>


        <section
            class="guru-modal-dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="guru-modal-title"
        >

            <div class="guru-modal-header">

                <div>

                    <h2 id="guru-modal-title">
                        Tambah Data Guru
                    </h2>

                    <p id="guru-modal-desc">
                        Lengkapi data guru yang akan ditambahkan.
                    </p>

                </div>


                <button
                    class="guru-modal-close"
                    id="guru-modal-close"
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
                            stroke-width="2"
                            stroke-linecap="round"
                        />

                    </svg>

                </button>

            </div>


            <form
                class="guru-form"
                id="guru-form"
            >

                <div class="guru-form-group">

                    <label for="guru-nip">
                        NIP
                    </label>

                    <input
                        id="guru-nip"
                        name="nip"
                        type="text"
                        inputmode="numeric"
                        maxlength="30"
                        placeholder="Masukkan NIP"
                        autocomplete="off"
                        required
                    >

                </div>


                <div class="guru-form-group">

                    <label for="guru-name">
                        Nama Guru
                    </label>

                    <div class="guru-select-wrap">

                        <select
                            id="guru-name"
                            name="namaGuru"
                            required
                        >

                            <option
                                value=""
                                selected
                                disabled
                            >
                                Pilih nama guru
                            </option>

                            <option value="Adit Kebugaran">
                                Adit Kebugaran — Guru
                            </option>

                            <option value="Dimas Ikwani">
                                Dimas Ikwani — Guru
                            </option>

                            <option value="Gus Nanang">
                                Gus Nanang — Guru
                            </option>

                            <option value="By U">
                                By U — Guru
                            </option>

                            <option value="Adam Jombang">
                                Adam Jombang — Kepala Sekolah
                            </option>

                            <option value="Fuang Parker">
                                Fuang Parker — Guru
                            </option>

                        </select>

                    </div>

                    <small class="guru-form-help">
                        Pilih nama guru dari daftar user.
                    </small>

                </div>


                <div class="guru-form-group">

                    <label for="guru-mapel">
                        Mata Pelajaran
                    </label>

                    <div class="guru-select-wrap">

                        <select
                            id="guru-mapel"
                            name="mataPelajaran"
                            required
                        >

                            <option
                                value=""
                                selected
                                disabled
                            >
                                Pilih mata pelajaran
                            </option>

                            <option value="Matematika">
                                Matematika
                            </option>

                            <option value="Bahasa Indonesia">
                                Bahasa Indonesia
                            </option>

                            <option value="Bahasa Inggris">
                                Bahasa Inggris
                            </option>

                            <option value="Ilmu Pengetahuan Alam">
                                Ilmu Pengetahuan Alam
                            </option>

                            <option value="Pendidikan Kewarganegaraan">
                                Pendidikan Kewarganegaraan
                            </option>

                            <option value="Pendidikan Agama Islam">
                                Pendidikan Agama Islam
                            </option>

                            <option value="Seni Budaya">
                                Seni Budaya
                            </option>

                            <option value="Pendidikan Jasmani">
                                Pendidikan Jasmani
                            </option>

                        </select>

                    </div>

                </div>


                <div class="guru-form-actions">

                    <button
                        class="guru-form-cancel"
                        id="guru-form-cancel"
                        type="button"
                    >
                        Batal
                    </button>

                    <button
                        class="guru-form-submit"
                        id="guru-form-submit-btn"
                        type="submit"
                    >
                        Simpan Data Guru
                    </button>

                </div>

            </form>

        </section>

    </div>



    {{-- =========================================================
        MODAL KONFIRMASI HAPUS
        ========================================================= --}}
    <div
        class="guru-modal"
        id="delete-modal"
        hidden
    >

        <div
            class="guru-modal-backdrop"
            data-delete-modal-close
        ></div>


        <section
            class="guru-modal-dialog delete-modal-dialog"
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
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                </svg>

            </div>


            <h2
                class="delete-modal-title"
                id="delete-modal-title"
            >
                Hapus Data Guru
            </h2>


            <p class="delete-modal-text">

                Apakah Anda yakin ingin menghapus data guru

                <strong id="delete-teacher-name"></strong>?

                Tindakan ini tidak dapat dibatalkan.

            </p>


            <div class="guru-form-actions delete-actions">

                <button
                    class="guru-form-cancel"
                    id="delete-form-cancel"
                    type="button"
                >
                    Batal
                </button>


                <button
                    class="guru-form-delete-confirm"
                    id="delete-form-confirm-btn"
                    type="button"
                >
                    Ya, Hapus Data
                </button>

            </div>

        </section>

    </div>

    @endsection



    @push('styles')

    <link
        rel="stylesheet"
        href="{{ asset('css/pages/guru.css') }}"
    >

    @endpush



    @push('scripts')

    <script
        src="{{ asset('js/guru.js') }}"
    ></script>

    @endpush