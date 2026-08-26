@extends('layouts.app')

@section('title', 'Mata Pelajaran | Cyber Olympus E-Raport System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/mapel.css') }}">
@endpush

@section('content')

<main
    aria-labelledby="page-title"
    class="mapel-content"
    id="mata-pelajaran"
>

    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <header class="mapel-heading">

        <div class="mapel-heading-text">

            <h1 id="page-title">
                Mata Pelajaran
            </h1>

            <p class="mapel-subtitle">
                Kelola data mata pelajaran dan KKM
            </p>

        </div>


        <button
            aria-label="Tambah mata pelajaran baru"
            class="mapel-add-button"
            id="mapel-add-button"
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
                    stroke-linejoin="round"
                    stroke-width="2"
                ></path>

            </svg>

            <span>
                Tambah Mata Pelajaran
            </span>

        </button>

    </header>


    {{-- =====================================================
         RINGKASAN
         ===================================================== --}}

    <section
        aria-label="Ringkasan mata pelajaran"
        class="mapel-summary"
    >

        {{-- TOTAL --}}

        <article class="mapel-stat-card">

            <div class="mapel-stat-text">

                <p>
                    Total Mata Pelajaran
                </p>

                <strong id="total-mapel">
                    {{ $totalMapel }}
                </strong>

            </div>


            <span class="mapel-stat-icon mapel-icon-book">

                <img
                    src="{{ asset('gambar/total_mata_pelajaran.png') }}"
                    alt=""
                    class="mapel-stat-image"
                >

            </span>

        </article>


        {{-- RATA-RATA KKM --}}

        <article class="mapel-stat-card">

            <div class="mapel-stat-text">

                <p>
                    Rata-rata KKM
                </p>

                <strong id="average-kkm">
                    {{ $rataRataKkm !== null ? round($rataRataKkm) : 0 }}
                </strong>

            </div>


            <span class="mapel-stat-icon mapel-icon-chart">

                <img
                    src="{{ asset('gambar/bar_chart.png') }}"
                    alt=""
                    class="mapel-stat-image"
                >

            </span>

        </article>


        {{-- KKM TERENDAH --}}

        <article class="mapel-stat-card">

            <div class="mapel-stat-text">

                <p>
                    KKM Terendah
                </p>

                <strong id="lowest-kkm">
                    {{ $kkmTerendah !== null ? $kkmTerendah : 0 }}
                </strong>

            </div>


            <span class="mapel-stat-icon mapel-icon-lowest">

                <img
                    src="{{ asset('gambar/book_mark.png') }}"
                    alt=""
                    class="mapel-stat-image"
                >

            </span>

        </article>

    </section>


    {{-- =====================================================
         TABEL
         ===================================================== --}}

    <section
        aria-label="Daftar mata pelajaran"
        class="mapel-table-card"
    >

        <div class="mapel-table-scroll">

            <div
                class="mapel-table"
                role="table"
                aria-label="Data mata pelajaran"
            >

                {{-- HEADER --}}

                <div
                    class="mapel-table-header"
                    role="row"
                >

                    <div role="columnheader">
                        No
                    </div>

                    <div role="columnheader">
                        Kode Mapel
                    </div>

                    <div role="columnheader">
                        Nama Mata Pelajaran
                    </div>

                    <div role="columnheader">
                        KKM
                    </div>

                    <div
                        class="mapel-action-header"
                        role="columnheader"
                    >
                        Aksi
                    </div>

                </div>


                {{-- BODY --}}

                <div
                    class="mapel-table-body"
                    role="rowgroup"
                >

                    @forelse ($mataPelajaran as $index => $mapel)

                        <div
                            class="mapel-table-row"
                            role="row"

                            data-mapel-id="{{ $mapel->id }}"
                            data-kode="{{ $mapel->kode_mapel }}"
                            data-nama="{{ $mapel->nama_pelajaran }}"
                            data-kkm="{{ $mapel->kkm }}"
                        >

                            {{-- NO --}}

                            <div
                                class="cell-no"
                                role="cell"
                            >
                                {{ $index + 1 }}
                            </div>


                            {{-- KODE --}}

                            <div
                                class="cell-kode"
                                role="cell"
                            >
                                {{ $mapel->kode_mapel }}
                            </div>


                            {{-- NAMA --}}

                            <div
                                class="cell-nama"
                                role="cell"
                            >
                                {{ $mapel->nama_pelajaran }}
                            </div>


                            {{-- KKM --}}

                            <div
                                class="cell-kkm"
                                role="cell"
                            >

                                <span
                                    class="kkm-badge {{ $mapel->kkm <= 70 ? 'kkm-low' : 'kkm-high' }}"
                                >
                                    {{ $mapel->kkm }}
                                </span>

                            </div>


                            {{-- AKSI --}}

                            <div
                                class="mapel-actions"
                                role="cell"
                            >

                                <button
                                    class="mapel-edit-btn"
                                    type="button"
                                    aria-label="Edit {{ $mapel->nama_pelajaran }}"
                                    title="Edit"
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
                                        ></path>

                                        <path
                                            d="M14.5 7.5l2 2"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        ></path>

                                    </svg>

                                </button>


                                <button
                                    class="mapel-delete-btn"
                                    type="button"
                                    aria-label="Hapus {{ $mapel->nama_pelajaran }}"
                                    title="Hapus"
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
                                        ></path>

                                    </svg>

                                </button>

                            </div>

                        </div>

                    @empty

                        <div class="mapel-table-row mapel-empty-row">

                            <div class="cell-no" role="cell">
                                -
                            </div>

                            <div class="cell-kode" role="cell">
                                -
                            </div>

                            <div class="cell-nama" role="cell">
                                Belum ada data mata pelajaran
                            </div>

                            <div class="cell-kkm" role="cell">
                                -
                            </div>

                            <div class="mapel-actions" role="cell">
                                -
                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </section>

</main>


{{-- =====================================================
     MODAL TAMBAH
     ===================================================== --}}

<div
    class="mapel-modal"
    id="mapel-modal"
    hidden
>

    <div
        class="mapel-modal-overlay"
        data-mapel-modal-close
    ></div>


    <div
        class="mapel-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="mapel-modal-title"
    >

        <div class="mapel-modal-header">

            <div class="mapel-modal-heading">

                <h2 id="mapel-modal-title">
                    Tambah Mata Pelajaran
                </h2>

                <p>
                    Tambahkan data mata pelajaran baru
                </p>

            </div>


            <button
                class="mapel-modal-close"
                id="mapel-modal-close"
                type="button"
                aria-label="Tutup form"
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
                    ></path>

                </svg>

            </button>

        </div>


        <form
            id="mapel-form"
            class="mapel-form"
        >

            @csrf

            <div class="mapel-form-group">

                <label for="mapel-kode">
                    Kode Mata Pelajaran <span>*</span>
                </label>

                <input
                    type="text"
                    id="mapel-kode"
                    name="kode_mapel"
                    maxlength="5"
                    placeholder="Contoh: MTK"
                    autocomplete="off"
                    required
                >

                <small>
                    Maksimal 5 karakter.
                </small>

            </div>


            <div class="mapel-form-group">

                <label for="mapel-nama">
                    Nama Mata Pelajaran <span>*</span>
                </label>

                <input
                    type="text"
                    id="mapel-nama"
                    name="nama_pelajaran"
                    maxlength="45"
                    placeholder="Contoh: Matematika"
                    autocomplete="off"
                    required
                >

                <small>
                    Maksimal 45 karakter.
                </small>

            </div>


            <div class="mapel-form-group">

                <label for="mapel-kkm">
                    KKM <span>*</span>
                </label>

                <input
                    type="number"
                    id="mapel-kkm"
                    name="kkm"
                    min="0"
                    max="100"
                    value="75"
                    placeholder="75"
                    required
                >

                <small>
                    Nilai KKM berada pada rentang 0-100.
                </small>

            </div>


            <div class="mapel-form-actions">

                <button
                    type="button"
                    class="mapel-form-cancel"
                    id="mapel-form-cancel"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="mapel-form-submit"
                >
                    Tambah Mata Pelajaran
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
     MODAL EDIT
     ===================================================== --}}

<div
    class="mapel-modal"
    id="mapel-edit-modal"
    hidden
>

    <div
        class="mapel-modal-overlay"
        data-mapel-modal-close
    ></div>


    <div
        class="mapel-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="mapel-edit-title"
    >

        <div class="mapel-modal-header">

            <div class="mapel-modal-heading">

                <h2 id="mapel-edit-title">
                    Edit Mata Pelajaran
                </h2>

                <p>
                    Perbarui data mata pelajaran
                </p>

            </div>


            <button
                class="mapel-modal-close"
                id="mapel-edit-close"
                type="button"
                aria-label="Tutup form"
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
                    ></path>

                </svg>

            </button>

        </div>


        <form
            id="mapel-edit-form"
            class="mapel-form"
        >

            @csrf

            <div class="mapel-form-group">

                <label for="mapel-edit-kode">
                    Kode Mata Pelajaran <span>*</span>
                </label>

                <input
                    type="text"
                    id="mapel-edit-kode"
                    maxlength="5"
                    autocomplete="off"
                    required
                >

                <small>
                    Maksimal 5 karakter.
                </small>

            </div>


            <div class="mapel-form-group">

                <label for="mapel-edit-nama">
                    Nama Mata Pelajaran <span>*</span>
                </label>

                <input
                    type="text"
                    id="mapel-edit-nama"
                    maxlength="45"
                    autocomplete="off"
                    required
                >

                <small>
                    Maksimal 45 karakter.
                </small>

            </div>


            <div class="mapel-form-group">

                <label for="mapel-edit-kkm">
                    KKM <span>*</span>
                </label>

                <input
                    type="number"
                    id="mapel-edit-kkm"
                    min="0"
                    max="100"
                    required
                >

                <small>
                    Nilai KKM berada pada rentang 0-100.
                </small>

            </div>


            <div class="mapel-form-actions">

                <button
                    type="button"
                    class="mapel-form-cancel"
                    id="mapel-edit-cancel"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="mapel-form-submit"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =====================================================
     MODAL HAPUS
     ===================================================== --}}

<div
    class="mapel-modal mapel-delete-modal"
    id="mapel-delete-modal"
    hidden
>

    <div
        class="mapel-modal-overlay"
        data-mapel-modal-close
    ></div>


    <div
        class="mapel-delete-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="mapel-delete-title"
    >

        <div
            class="mapel-delete-icon"
            aria-hidden="true"
        >

            <svg viewBox="0 0 24 24">

                <path
                    d="M12 3l9 17H3L12 3z"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linejoin="round"
                ></path>

                <path
                    d="M12 9v5M12 17h.01"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                ></path>

            </svg>

        </div>


        <h2 id="mapel-delete-title">
            Hapus Mata Pelajaran?
        </h2>


        <p>
            Apakah kamu yakin ingin menghapus
            <strong id="mapel-delete-name">
                "Mata Pelajaran"
            </strong>?
        </p>


        <span class="mapel-delete-warning">
            Data yang dihapus tidak dapat dikembalikan.
        </span>


        <div class="mapel-delete-actions">

            <button
                type="button"
                class="mapel-form-cancel"
                id="mapel-delete-cancel"
            >
                Batal
            </button>

            <button
                type="button"
                class="mapel-delete-confirm"
                id="mapel-delete-confirm"
            >
                Hapus
            </button>

        </div>


        <button
            type="button"
            class="mapel-delete-close"
            id="mapel-delete-close"
            aria-label="Tutup"
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
                ></path>

            </svg>

        </button>

    </div>

</div>

@endsection


@push('scripts')

<script src="{{ asset('js/mapel.js') }}"></script>

<script>
    window.mapelConfig = {
        storeUrl: @json(route('mapel.store')),
        updateUrl: @json(url('/mata-pelajaran')),
        csrfToken: @json(csrf_token())
    };
</script>

@endpush