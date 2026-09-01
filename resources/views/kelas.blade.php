@extends('layouts.app')

@section('title', 'Data Kelas | Cyber Olympus E-Raport System')

@push('styles') <link rel="stylesheet" href="{{ asset('css/pages/kelas.css') }}">
@endpush

@section('content')

<main
    aria-labelledby="page-title"
    class="kelas-content"
    id="data-kelas"
>


{{-- =====================================================
     HEADER
====================================================== --}}
<header class="k-div">

    <div class="k-div-2">
        <h1
            aria-label="Data Kelas"
            class="k-text-wrapper"
            id="page-title"
        >
            Data Kelas
        </h1>

        <p class="k-page-subtitle">
            Kelola data kelas dan wali kelas.
        </p>
    </div>



    {{-- FILTER TAHUN AJARAN --}}
    <div class="kelas-header-actions">
        <div class="kelas-filter-wrap">
            <label for="kelas-year-filter" class="kelas-filter-label">Tahun Ajaran</label>
            <div class="kelas-filter-control">
                <svg aria-hidden="true" viewBox="0 0 24 24" class="kelas-filter-icon"><path d="M7 2v3M17 2v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <select id="kelas-year-filter" aria-label="Filter kelas berdasarkan tahun ajaran">
                    <option value="all">Semua Tahun Ajaran</option>
                    @foreach($kelas->pluck('tahun_ajaran')->filter()->unique()->sort()->values() as $tahunAjaran)
                        <option value="{{ $tahunAjaran }}">{{ $tahunAjaran }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    {{-- TAMBAH KELAS HANYA UNTUK ADMIN --}}
    @if (auth()->user()?->role === 'admin')
        <button
            aria-label="Tambah kelas baru"
            class="k-button-tambah-siswa"
            id="kelas-add-button"
            type="button"
        >
            <span
                aria-hidden="true"
                class="k-ic-round-plus"
            >
                <svg
                    aria-hidden="true"
                    class="icon-svg k-vector"
                    focusable="false"
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
            </span>

            <span class="k-tambah-siswa">
                Tambah Kelas
            </span>
        </button>
    @endif
    </div>

</header>


{{-- =====================================================
     RINGKASAN
====================================================== --}}
<section
    aria-label="Ringkasan data kelas"
    class="k-div-3"
>

    {{-- TOTAL KELAS --}}
    <article class="k-div-4">

        <div class="k-div-5">
            <p class="k-text-wrapper-3">
                Total Kelas
            </p>

            <p class="k-text-wrapper-4">
                {{ $ringkasan['total_kelas'] }}
            </p>
        </div>

        <span
            aria-hidden="true"
            class="k-radix-icons-people summary-icon-box summary-icon-total-kelas"
        >
            <svg
                aria-hidden="true"
                class="summary-icon-svg summary-icon-group"
                focusable="false"
                viewBox="0 0 24 20"
            >
                <circle
                    cx="9"
                    cy="7.5"
                    r="3"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                ></circle>

                <path
                    d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                ></path>

                <path
                    d="M16 5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                ></path>
            </svg>
        </span>

    </article>


    {{-- TOTAL SISWA --}}
    <article class="k-div-4">

        <div class="k-div-5">

            <p class="k-text-wrapper-3">
                Total Siswa
            </p>

            <p class="k-text-wrapper-4">
                {{ $ringkasan['total_siswa'] }}
            </p>

        </div>

        <span
            aria-hidden="true"
            class="k-griddy-icons-user-box summary-icon-box summary-icon-total-siswa"
        >
            <svg
                aria-hidden="true"
                class="summary-icon-svg summary-icon-single"
                focusable="false"
                viewBox="0 0 20 20"
            >
                <circle
                    cx="10"
                    cy="7.5"
                    r="3.5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                ></circle>

                <path
                    d="M3 20c0-4 3.1-7 7-7s7 3 7 7"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                ></path>
            </svg>
        </span>

    </article>


    {{-- RATA-RATA --}}
    <article class="k-div-4">

        <div class="k-div-5">

            <p class="k-text-wrapper-3">
                Rata rata perkelas
            </p>

            <p class="k-text-wrapper-4">
                {{ $ringkasan['rata_rata'] }}
            </p>

        </div>

        <span
            aria-hidden="true"
            class="k-vector-wrapper summary-icon-box summary-icon-average"
        >
            <svg
                aria-hidden="true"
                class="summary-icon-svg summary-icon-group"
                focusable="false"
                viewBox="0 0 24 20"
            >
                <circle
                    cx="9"
                    cy="7.5"
                    r="3"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                ></circle>

                <path
                    d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                ></path>

                <path
                    d="M16 5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                ></path>
            </svg>
        </span>

    </article>

</section>


{{-- =====================================================
     DATA KELAS
====================================================== --}}

@php
    $kelasPerTingkat = $kelas->groupBy(function ($item) {
        return preg_replace('/[^0-9]/', '', $item->nama_kelas);
    });
@endphp

@forelse($kelasPerTingkat as $tingkat => $daftarKelas)

    <section
        aria-labelledby="tingkat-{{ $tingkat }}"
        class="k-div-6"
    >

        <h2
            class="k-text-wrapper-5"
            id="tingkat-{{ $tingkat }}"
        >
            Tingkat {{ $tingkat }}
        </h2>

        <div class="k-div-7">

            @foreach($daftarKelas as $item)

                <article
                    aria-labelledby="kelas-{{ $item->id }}"
                    class="k-div-8"
                    data-kelas-id="{{ $item->id }}"
                    data-kelas-name="{{ $item->nama_kelas }}"
                    data-siswa="{{ $item->siswa_count }}"
                    data-tahun="{{ $item->tahun_ajaran }}"
                    data-wali-id="{{ $item->wali_kelas_id }}"
                    data-wali="{{ $item->waliKelas?->nama_lengkap ?? '-' }}"
                >

                    <div class="k-div-9">

                        <div class="k-div-10">

                            <div class="k-div-11">

                                {{-- NAMA + TAHUN --}}
                                <div class="k-div-12">

                                    <h3
                                        class="k-text-wrapper-5"
                                        id="kelas-{{ $item->id }}"
                                    >
                                        Kelas {{ $item->nama_kelas }}
                                    </h3>

                                    <p class="k-text-wrapper-2">
                                        Tahun Ajaran {{ $item->tahun_ajaran }}
                                    </p>

                                </div>


                                {{-- WALI --}}
                                <div class="k-div-13">

                                    <svg
                                        aria-hidden="true"
                                        class="icon-svg k-img-2"
                                        focusable="false"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            cx="12"
                                            cy="8"
                                            r="3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        ></circle>

                                        <path
                                            d="M5 21c0-4 3.1-7 7-7s7 3 7 7"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        ></path>
                                    </svg>

                                    <div class="k-div-2">

                                        <p class="k-text-wrapper-6">
                                            Wali Kelas
                                        </p>

                                        <p class="k-text-wrapper-2">
                                            {{ $item->waliKelas?->nama_lengkap ?? '-' }}
                                        </p>

                                    </div>

                                </div>


                                {{-- JUMLAH SISWA --}}
                                <div class="k-div-13">

                                    <svg
                                        aria-hidden="true"
                                        class="icon-svg k-img-2"
                                        focusable="false"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            cx="9"
                                            cy="8"
                                            r="3"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        ></circle>

                                        <path
                                            d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        ></path>

                                        <path
                                            d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        ></path>
                                    </svg>

                                    <div class="k-div-2">

                                        <p class="k-text-wrapper-6">
                                            Jumlah Siswa
                                        </p>

                                        <p class="k-text-wrapper-2">
                                            {{ $item->siswa_count }} Siswa
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- BADGE TINGKAT --}}
                            <span
                                aria-label="Tingkat {{ $tingkat }}"
                                class="k-div-wrapper"
                            >
                                <span class="k-text-wrapper-7">
                                    {{ $tingkat }}
                                </span>
                            </span>

                        </div>

                        <span
                            aria-hidden="true"
                            class="k-line"
                        ></span>

                    </div>


                    {{-- AKSI --}}
                    <nav
                        aria-label="Aksi Kelas {{ $item->nama_kelas }}"
                        class="k-div-14"
                    >

                        {{-- DETAIL BOLEH DILIHAT SEMUA ROLE --}}
                        <button
                            aria-label="Lihat detail Kelas {{ $item->nama_kelas }}"
                            class="k-text-wrapper-8 kelas-detail-btn"
                            data-id="{{ $item->id }}"
                            type="button"
                        >
                            Detail
                        </button>

                        {{-- EDIT HANYA UNTUK ADMIN --}}
                        @if (auth()->user()?->role === 'admin')
                            <button
                                aria-label="Edit Kelas {{ $item->nama_kelas }}"
                                class="k-text-wrapper-9 kelas-edit-btn"
                                type="button"
                            >
                                Edit
                            </button>
                        @endif

                    </nav>

                </article>

            @endforeach

        </div>

    </section>

@empty

    <section class="k-div-6">
        <h2 class="k-text-wrapper-5">
            Data Kelas
        </h2>

        <div class="kelas-empty-state">
            Belum ada data kelas.
        </div>
    </section>

@endforelse


</main>

{{-- =========================================================
MODAL TAMBAH / EDIT
========================================================= --}}

<div
    class="kelas-modal"
    hidden
    id="kelas-modal"
>


<div
    class="kelas-modal-backdrop"
    data-kelas-modal-close
></div>

<section
    aria-labelledby="kelas-modal-title"
    aria-modal="true"
    class="kelas-modal-dialog"
    role="dialog"
>

    <div class="kelas-modal-header">

        <div>

            <h2 id="kelas-modal-title">
                Tambah Data Kelas
            </h2>

            <p id="kelas-modal-desc">
                Lengkapi data kelas yang akan ditambahkan.
            </p>

        </div>

        <button
            aria-label="Tutup modal"
            class="kelas-modal-close"
            id="kelas-modal-close"
            type="button"
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
                ></path>
            </svg>
        </button>

    </div>


    <form
        class="kelas-form"
        id="kelas-form"
    >

        @csrf

        <div class="kelas-form-group">

            <label for="kelas-name">
                Nama Kelas
            </label>

            <input
                autocomplete="off"
                id="kelas-name"
                maxlength="5"
                name="nama_kelas"
                placeholder="Contoh: 3B"
                required
                type="text"
            >

            <small class="kelas-form-help">
                Maksimal 5 karakter, misalnya 1A, 2B, atau 3C.
            </small>

        </div>


        <div class="kelas-form-group">

            <label for="kelas-year">
                Tahun Ajaran
            </label>

            <input
                autocomplete="off"
                id="kelas-year"
                maxlength="9"
                name="tahun_ajaran"
                placeholder="Contoh: 2026/2027"
                pattern="\d{4}/\d{4}"
                required
                type="text"
            >

            <small class="kelas-form-help">
                Format: YYYY/YYYY.
            </small>

        </div>


        <div class="kelas-form-group">

            <label for="kelas-wali">
                Wali Kelas
            </label>

            <div class="kelas-select-wrap">

                <select
                    id="kelas-wali"
                    name="wali_kelas_id"
                    required
                >

                    <option value="">
                        Pilih wali kelas
                    </option>

                    @foreach($guru as $guruItem)

                        <option value="{{ $guruItem->id }}">
                            {{ $guruItem->nama_lengkap }}
                        </option>

                    @endforeach

                </select>

            </div>

            <small class="kelas-form-help">
                Wali kelas berasal dari guru aktif.
            </small>

        </div>


        <div class="kelas-form-actions">

            <button
                class="kelas-form-cancel"
                id="kelas-form-cancel"
                type="button"
            >
                Batal
            </button>

            <button
                class="kelas-form-delete"
                id="kelas-form-delete-btn"
                type="button"
                hidden
            >
                Hapus Data Kelas
            </button>

            <button
                class="kelas-form-submit"
                id="kelas-form-submit-btn"
                type="submit"
            >
                Simpan Data Kelas
            </button>

        </div>

    </form>

</section>


</div>

{{-- =========================================================
MODAL DETAIL
========================================================= --}}

<div
    class="kelas-modal"
    hidden
    id="kelas-detail-modal"
>


<div
    class="kelas-modal-backdrop"
    data-detail-modal-close
></div>

<section
    aria-labelledby="kelas-detail-title"
    aria-modal="true"
    class="kelas-modal-dialog kelas-detail-dialog"
    role="dialog"
>

    <div class="kelas-modal-header">

        <div>

            <h2 id="kelas-detail-title">
                Detail Kelas
            </h2>

            <p>
                Informasi kelas dan daftar siswa.
            </p>

        </div>

        <button
            aria-label="Tutup detail kelas"
            class="kelas-modal-close"
            id="kelas-detail-close"
            type="button"
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
                ></path>
            </svg>
        </button>

    </div>


    <div class="kelas-detail-summary">

        <div class="kelas-detail-info">
            <span>Nama Kelas</span>
            <strong id="detail-nama-kelas">-</strong>
        </div>

        <div class="kelas-detail-info">
            <span>Tahun Ajaran</span>
            <strong id="detail-tahun">-</strong>
        </div>

        <div class="kelas-detail-info">
            <span>Wali Kelas</span>
            <strong id="detail-wali">-</strong>
        </div>

        <div class="kelas-detail-info">
            <span>Jumlah Siswa</span>
            <strong id="detail-jumlah-siswa">0 Siswa</strong>
        </div>

    </div>


    <div class="kelas-student-section">

        <div class="kelas-student-heading">

            <h3>
                Daftar Siswa
            </h3>

            <span id="detail-student-count">
                0 siswa
            </span>

        </div>


        <div class="kelas-student-scroll">

            <table class="kelas-student-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                    </tr>
                </thead>

                <tbody id="detail-student-body"></tbody>

            </table>

            <div
                class="kelas-no-student"
                id="kelas-no-student"
                hidden
            >
                Belum ada siswa pada kelas ini.
            </div>

        </div>

    </div>

</section>


</div>

{{-- =========================================================
MODAL DELETE
========================================================= --}}

<div
    class="kelas-modal"
    hidden
    id="kelas-delete-modal"
>


<div
    class="kelas-modal-backdrop"
    data-delete-modal-close
></div>

<section
    aria-labelledby="kelas-delete-title"
    aria-modal="true"
    class="kelas-modal-dialog kelas-delete-dialog"
    role="dialog"
>

    <div
        class="kelas-delete-icon"
        aria-hidden="true"
    >
        <svg viewBox="0 0 24 24">
            <path
                d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13M10 11v6M14 11v6"
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
            ></path>
        </svg>
    </div>

    <h2
        class="kelas-delete-title"
        id="kelas-delete-title"
    >
        Hapus Data Kelas?
    </h2>

    <p
        class="kelas-delete-text"
        id="kelas-delete-text"
    >
        Anda akan menghapus data kelas.
    </p>

    <p
        class="kelas-delete-warning"
        id="kelas-delete-warning"
    ></p>

    <div class="kelas-form-actions">

        <button
            class="kelas-form-cancel"
            id="kelas-delete-cancel"
            type="button"
        >
            Batal
        </button>

        <button
            class="kelas-form-delete-confirm"
            id="kelas-delete-confirm"
            type="button"
        >
            Ya, Hapus
        </button>

    </div>

</section>


</div>

@endsection

@push('scripts') <script src="{{ asset('js/kelas.js') }}"></script>
@endpush



