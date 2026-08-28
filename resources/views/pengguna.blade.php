@extends('layouts.app')

@section('title', 'Manajemen Pengguna | Cyber Olympus E-Raport System')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/pages/pengguna.css') }}"
    >
@endpush

@section('content')

@php
    $adaKepalaSekolah = $pengguna
        ->where('role', 'kepala_sekolah')
        ->isNotEmpty();
@endphp

<section
    class="user-management-content"
    id="pengguna"
    aria-labelledby="user-page-title"
>

    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <header class="mp-div">

        <div class="mp-div-2">

            <h1
                id="user-page-title"
                class="mp-text-wrapper"
            >
                Manajemen Pengguna
            </h1>

            <p class="mp-p">
                Kelola pengguna akun sistem E-Raport
            </p>

        </div>

        <button
            class="mp-button-tambah-siswa"
            type="button"
            id="open-user-form"
            aria-label="Tambah pengguna baru"
        >

            <span
                class="mp-ic-round-plus"
                aria-hidden="true"
            >

                <svg
                    class="mp-vector"
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >

                    <path
                        d="M12 5V19M5 12H19"
                        stroke="currentColor"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />

                </svg>

            </span>

            <span class="mp-tambah-siswa">
                Tambah Pengguna
            </span>

        </button>

    </header>


    {{-- =====================================================
         STATISTIK
         ===================================================== --}}

    <section
        class="mp-div-3"
        aria-label="Ringkasan pengguna"
    >

        <article class="mp-div-4">

            <p class="mp-text-wrapper-2">
                Total Pengguna
            </p>

            <p class="mp-text-wrapper-3">
                {{ $statistik['totalPengguna'] }}
            </p>

        </article>

        <article class="mp-div-4">

            <p class="mp-text-wrapper-2">
                Administrator
            </p>

            <p class="mp-text-wrapper-3">
                {{ $statistik['totalAdmin'] }}
            </p>

        </article>

        <article class="mp-div-4">

            <p class="mp-text-wrapper-2">
                Guru
            </p>

            <p class="mp-text-wrapper-3">
                {{ $statistik['totalGuru'] }}
            </p>

        </article>

        <article class="mp-div-4">

            <p class="mp-text-wrapper-2">
                Pengguna Aktif
            </p>

            <p class="mp-text-wrapper-3">
                {{ $statistik['totalAktif'] }}
            </p>

        </article>

    </section>


    {{-- =====================================================
         TABEL
         ===================================================== --}}

    <section
        class="mp-div-5"
        aria-labelledby="user-list-title"
    >

        <h2
            id="user-list-title"
            class="mp-visually-hidden"
        >
            Daftar Pengguna
        </h2>

        <div
            class="mp-user-table"
            role="table"
            aria-label="Daftar pengguna akun sistem E-Raport"
        >

            {{-- HEADER --}}

            <div
                class="mp-navbar mp-user-row mp-user-table-header"
                role="row"
            >

                <div
                    class="mp-text-wrapper-4 mp-user-cell"
                    role="columnheader"
                >
                    No
                </div>

                <div
                    class="mp-text-wrapper-4 mp-user-cell"
                    role="columnheader"
                >
                    Username
                </div>

                <div
                    class="mp-text-wrapper-5 mp-user-cell"
                    role="columnheader"
                >
                    Nama Lengkap
                </div>

                <div
                    class="mp-text-wrapper-4 mp-user-cell"
                    role="columnheader"
                >
                    Email
                </div>

                <div
                    class="mp-text-wrapper-6 mp-user-cell"
                    role="columnheader"
                >
                    Role
                </div>

                <div
                    class="mp-text-wrapper-6 mp-user-cell"
                    role="columnheader"
                >
                    Status
                </div>

                <div
                    class="mp-text-wrapper-6 mp-user-cell"
                    role="columnheader"
                >
                    Aksi
                </div>

            </div>


            {{-- BODY --}}

            <div
                class="mp-div-6 mp-user-table-body"
                role="rowgroup"
            >

                @forelse ($pengguna as $index => $user)

                    <div
                        class="mp-user-row"
                        role="row"
                        data-user-id="{{ $user->id }}"
                    >

                        <div
                            class="mp-user-cell"
                            role="cell"
                        >
                            {{ $index + 1 }}
                        </div>

                        <div
                            class="mp-user-cell"
                            role="cell"
                        >
                            {{ $user->username }}
                        </div>

                        <div
                            class="mp-user-cell"
                            role="cell"
                        >
                            {{ $user->nama_lengkap }}
                        </div>

                        <div
                            class="mp-user-cell"
                            role="cell"
                        >
                            {{ $user->email }}
                        </div>


                        {{-- ROLE --}}

                        <div
                            class="mp-div-wrapper mp-user-cell"
                            role="cell"
                        >

                            @if ($user->role === 'admin')

                                <span class="mp-text-wrapper-11">
                                    Administrator
                                </span>

                            @elseif ($user->role === 'kepala_sekolah')

                                <span class="mp-text-wrapper-17">
                                    Kepala Sekolah
                                </span>

                            @else

                                <span class="mp-text-wrapper-22">
                                    Guru
                                </span>

                            @endif

                        </div>


                        {{-- STATUS --}}

                        <div
                            class="mp-div-wrapper-2 mp-user-cell"
                            role="cell"
                        >

                            <span
                                class="mp-text-wrapper-12"
                                data-status-badge="{{ $user->id }}"
                            >
                                {{ $user->status === 'aktif'
                                    ? 'Aktif'
                                    : 'Tidak Aktif'
                                }}
                            </span>

                        </div>


                        {{-- AKSI --}}

                        <div
                            class="mp-div-7 mp-user-cell"
                            role="cell"
                        >

                            {{-- EDIT --}}

                            <button
                                class="mp-action-button mp-action-edit"
                                type="button"
                                aria-label="Ubah pengguna {{ $user->username }}"
                                data-user-id="{{ $user->id }}"
                                data-username="{{ $user->username }}"
                            >

                                <svg
                                    class="mp-action-icon mp-icon-edit"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >

                                    <path
                                        d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                    />

                                    <path
                                        d="m14.5 6.5 3 3"
                                    />

                                </svg>

                            </button>


                            {{-- AKTIF / NONAKTIF --}}

                            <button
                                class="mp-action-button mp-action-status"
                                type="button"
                                aria-label="{{ $user->status === 'aktif'
                                    ? 'Nonaktifkan akun ' . $user->username
                                    : 'Aktifkan akun ' . $user->username }}"
                                title="{{ $user->status === 'aktif'
                                    ? 'Nonaktifkan akun'
                                    : 'Aktifkan akun' }}"
                                data-user-id="{{ $user->id }}"
                                data-username="{{ $user->username }}"
                                data-status="{{ $user->status }}"
                            >

                                <svg
                                    class="mp-action-icon mp-icon-key {{ $user->status === 'tidak_aktif'
                                        ? 'status-inactive'
                                        : '' }}"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >

                                    <path
                                        d="M21 2l-2 2m-2 2l-2 2m2-2l2 2m-2-2l-6.5 6.5a5.5 5.5 0 1 1-3-3L16.5 2h4.5v4.5z"
                                    />

                                    <circle
                                        cx="7.5"
                                        cy="16.5"
                                        r="1.5"
                                    />

                                </svg>

                            </button>


                            {{-- DELETE --}}

                            <button
                                class="mp-action-button mp-action-delete"
                                type="button"
                                aria-label="Hapus pengguna {{ $user->username }}"
                                data-user-id="{{ $user->id }}"
                                data-username="{{ $user->username }}"
                            >

                                <svg
                                    class="mp-action-icon mp-icon-trash"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >

                                    <polyline
                                        points="3 6 5 6 21 6"
                                    />

                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                    />

                                    <line
                                        x1="10"
                                        y1="11"
                                        x2="10"
                                        y2="17"
                                    />

                                    <line
                                        x1="14"
                                        y1="11"
                                        x2="14"
                                        y2="17"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>

                @empty

                    <div
                        class="mp-user-empty"
                        role="row"
                    >

                        <div
                            class="mp-user-empty-message"
                            role="cell"
                        >
                            Belum ada data pengguna.
                        </div>

                    </div>

                @endforelse

            </div>

        </div>

    </section>

</section>


{{-- =====================================================
     MODAL TAMBAH / EDIT
     ===================================================== --}}

<div
    class="user-form-overlay"
    id="user-form-overlay"
    hidden
>

    <section
        class="user-form-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="user-form-title"
    >

        <div class="user-form-header">

            <div class="user-form-title-group">

                <h2 id="user-form-title">
                    Tambah Pengguna
                </h2>

                <p id="user-form-description">
                    Tambahkan akun pengguna baru ke sistem E-Raport
                </p>

            </div>

            <button
                class="user-form-close"
                id="user-form-close"
                type="button"
                aria-label="Tutup form pengguna"
            >

                <span></span>
                <span></span>

            </button>

        </div>


        <div class="user-form-divider"></div>


        <form
            class="user-form"
            id="user-form"
        >

            @csrf

            <input
                type="hidden"
                id="user-id"
                name="user_id"
                value=""
            >


            <div class="user-form-grid">

                {{-- USERNAME --}}

                <div class="user-form-field">

                    <label for="username">
                        Username
                        <span>*</span>
                    </label>

                    <input
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Masukkan username"
                        maxlength="15"
                        autocomplete="username"
                        required
                    >

                    <small>
                        Maksimal 15 karakter
                    </small>

                </div>


                {{-- PASSWORD --}}

                <div class="user-form-field">

                    <label for="password">

                        Password

                        <span id="password-required">
                            *
                        </span>

                    </label>

                    <div class="user-password-wrap">

                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Masukkan password"
                            maxlength="60"
                            autocomplete="new-password"
                        >

                        <button
                            class="password-toggle"
                            type="button"
                            aria-label="Tampilkan password"
                            data-target="password"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                            >

                                <path
                                    d="M2.5 12s3.5-5.5 9.5-5.5S21.5 12 21.5 12s-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linejoin="round"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="2.5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />

                            </svg>

                        </button>

                    </div>

                    <small id="password-help">
                        Minimal 8 karakter.
                    </small>

                </div>


                {{-- NAMA LENGKAP --}}

                <div class="user-form-field">

                    <label for="nama_lengkap">
                        Nama Lengkap
                        <span>*</span>
                    </label>

                    <input
                        id="nama_lengkap"
                        name="nama_lengkap"
                        type="text"
                        placeholder="Masukkan nama lengkap"
                        maxlength="40"
                        autocomplete="name"
                        required
                    >

                </div>


                {{-- EMAIL --}}

                <div class="user-form-field">

                    <label for="email">
                        Email
                        <span>*</span>
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        placeholder="contoh@email.com"
                        maxlength="30"
                        autocomplete="email"
                        required
                    >

                </div>


                {{-- ROLE --}}

                <div class="user-form-field">

                    <label for="role">
                        Role
                        <span>*</span>
                    </label>

                    <div class="user-select-wrap">

                        <select
                            id="role"
                            name="role"
                            required
                        >

                            <option
                                value=""
                                selected
                                disabled
                            >
                                Pilih role pengguna
                            </option>

                            {{-- ADMIN TIDAK ADA DI SINI --}}

                            @unless ($adaKepalaSekolah)

                                <option value="kepala_sekolah">
                                    Kepala Sekolah
                                </option>

                            @endunless

                            <option value="guru">
                                Guru
                            </option>

                        </select>

                    </div>

                </div>


                {{-- STATUS --}}

                <div class="user-form-field">

                    <label for="status">
                        Status
                        <span>*</span>
                    </label>

                    <div class="user-select-wrap">

                        <select
                            id="status"
                            name="status"
                            required
                        >

                            <option value="aktif">
                                Aktif
                            </option>

                            <option value="tidak_aktif">
                                Tidak Aktif
                            </option>

                        </select>

                    </div>

                </div>


                {{-- NIP --}}

                <div class="user-form-field user-form-field-full">

                    <label for="nip">

                        NIP

                        <span class="optional">
                            (Opsional)
                        </span>

                    </label>

                    <input
                        id="nip"
                        name="nip"
                        type="text"
                        placeholder="Masukkan NIP jika tersedia"
                        maxlength="20"
                        autocomplete="off"
                    >

                    <small>
                        Sesuai rancangan database, NIP boleh dikosongkan.
                    </small>

                </div>

            </div>


            <div class="user-form-footer">

                <p class="user-form-required">

                    <span>*</span>
                    Wajib diisi

                </p>

                <div class="user-form-actions">

                    <button
                        class="user-form-button user-form-cancel"
                        id="user-form-cancel"
                        type="button"
                    >
                        Batal
                    </button>

                    <button
                        class="user-form-button user-form-submit"
                        type="submit"
                    >
                        Simpan Pengguna
                    </button>

                </div>

            </div>

        </form>

    </section>

</div>

@endsection


@push('scripts')

<script src="{{ asset('js/pengguna.js') }}"></script>

@endpush