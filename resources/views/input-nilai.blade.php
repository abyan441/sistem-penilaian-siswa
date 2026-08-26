@extends('layouts.app')

@section('title', 'Input Nilai | Cyber Olympus E-Raport System')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/input-nilai.css') }}">
@endpush

@section('content')

<form class="nilai-page" id="grade-form">

    {{-- =====================================================
         HEADER HALAMAN
         ===================================================== --}}
    <header class="nilai-page-header">

        <div class="nilai-page-title">

            <h1>Input Nilai</h1>

            <p>
                Input nilai siswa per mata pelajaran
            </p>

        </div>

        <button
            class="button-tambah-siswa"
            type="submit"
        >

            <svg
                class="ci-save"
                viewBox="0 0 24 24"
                aria-hidden="true"
                focusable="false"
            >
                <path
                    d="M5 3.5h11.5L20 7v13.5H5z"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linejoin="round"
                />

                <path
                    d="M8 3.5v6h8v-6M8 20.5v-5h8v5"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linejoin="round"
                />

                <path
                    d="M17 3.5v4h2.2"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linejoin="round"
                />
            </svg>

            <span>
                Simpan Nilai
            </span>

        </button>

    </header>

    <div
        class="nilai-toast"
        id="nilai-toast"
        role="status"
        aria-live="polite"
        aria-atomic="true"
        hidden
    >

        <span class="nilai-toast-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
                <path d="m5 12.5 4.5 4.5L19 7.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>

        <span class="nilai-toast-copy">
            <strong>Nilai berhasil disimpan</strong>
            <span>Perubahan nilai siswa sudah diperbarui.</span>
        </span>

        <button
            class="nilai-toast-close"
            type="button"
            aria-label="Tutup pemberitahuan"
        >
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="m7 7 10 10M17 7 7 17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
        </button>

    </div>


    {{-- =====================================================
         FILTER NILAI
         ===================================================== --}}
    <section
        class="nilai-filter-wrapper"
        aria-label="Filter nilai"
    >

        <div class="nilai-filter-card">

            <div class="nilai-filter-grid">

                {{-- KELAS --}}
                <label class="nilai-filter-item">

                    <span class="nilai-filter-label">
                        Pilih Kelas
                    </span>

                    <select
                        class="dropdown-items"
                        name="classroom"
                    >
                        <option
                            value="6a"
                            selected
                        >
                            Kelas 6A
                        </option>
                    </select>

                </label>


                {{-- SEMESTER --}}
                <label class="nilai-filter-item">

                    <span class="nilai-filter-label">
                        Semester
                    </span>

                    <select
                        class="dropdown-items"
                        name="semester"
                    >
                        <option
                            value="1"
                            selected
                        >
                            Semester 1 (Ganjil)
                        </option>
                    </select>

                </label>


                {{-- MATA PELAJARAN --}}
                <label class="nilai-filter-item">

                    <span class="nilai-filter-label">
                        Mata Pelajaran
                    </span>

                    <select
                        class="dropdown-items"
                        name="subject"
                    >
                        <option
                            value="matematika"
                            selected
                        >
                            Matematika
                        </option>
                    </select>

                </label>


                {{-- TAHUN AJARAN --}}
                <label class="nilai-filter-item">

                    <span class="nilai-filter-label">
                        Tahun Ajaran
                    </span>

                    <select
                        class="dropdown-items"
                        name="academic-year"
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


            {{-- INFO BOBOT NILAI --}}
            <aside
                class="info-bobot-nilai-wrapper"
                aria-label="Informasi bobot nilai"
            >

                <p class="info-bobot-nilai">

                    <strong>
                        Info:
                    </strong>

                    <span>
                        Bobot Nilai - Tugas (30%), UTS (30%), UAS (40%)
                    </span>

                </p>

            </aside>

        </div>

    </section>


    {{-- =====================================================
         TABEL NILAI SISWA
         ===================================================== --}}
    <section
        class="nilai-table-wrapper"
        aria-label="Daftar nilai siswa"
    >

        {{-- HEADER TABEL --}}
        <div
            class="nilai-table-header"
            role="row"
        >

            <span role="columnheader">
                No
            </span>

            <span role="columnheader">
                Nama Siswa
            </span>

            <span role="columnheader">
                Tugas (30%)
            </span>

            <span role="columnheader">
                UTS (30%)
            </span>

            <span role="columnheader">
                UAS (40%)
            </span>

            <span role="columnheader">
                Nilai Akhir
            </span>

            <span role="columnheader">
                Predikat
            </span>

        </div>


        {{-- ISI TABEL --}}
        <div
            class="nilai-table-body"
            aria-label="Nilai siswa"
            role="grid"
        >

            {{-- =================================================
                 BONDET
                 ================================================= --}}
            <div class="nilai-row">

                <span
                    class="nilai-no"
                    role="rowheader"
                >
                    1
                </span>

                <span
                    class="nilai-student-name"
                    role="gridcell"
                >
                    Bondet
                </span>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai Tugas Bondet"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="bondet-task"
                        step="1"
                        type="number"
                        value="85"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UTS Bondet"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="bondet-uts"
                        step="1"
                        type="number"
                        value="80"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UAS Bondet"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="bondet-uas"
                        step="1"
                        type="number"
                        value="88"
                    >

                </div>

                <output
                    class="nilai-akhir"
                    aria-label="Nilai akhir Bondet"
                    data-student="bondet"
                >
                    85
                </output>

                <span
                    class="predikat-badge predikat-b"
                    data-predicate="bondet"
                >
                    <span>B</span>
                </span>

            </div>


            {{-- =================================================
                 DINA
                 ================================================= --}}
            <div class="nilai-row">

                <span
                    class="nilai-no"
                    role="rowheader"
                >
                    2
                </span>

                <span
                    class="nilai-student-name"
                    role="gridcell"
                >
                    Dina
                </span>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai Tugas Dina"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="dina-task"
                        step="1"
                        type="number"
                        value="90"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UTS Dina"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="dina-uts"
                        step="1"
                        type="number"
                        value="85"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UAS Dina"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="dina-uas"
                        step="1"
                        type="number"
                        value="92"
                    >

                </div>

                <output
                    class="nilai-akhir"
                    aria-label="Nilai akhir Dina"
                    data-student="dina"
                >
                    89
                </output>

                <span
                    class="predikat-badge predikat-b"
                    data-predicate="dina"
                >
                    <span>B</span>
                </span>

            </div>


            {{-- =================================================
                 DODO
                 ================================================= --}}
            <div class="nilai-row">

                <span
                    class="nilai-no"
                    role="rowheader"
                >
                    3
                </span>

                <span
                    class="nilai-student-name"
                    role="gridcell"
                >
                    Dodo
                </span>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai Tugas Dodo"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="dodo-task"
                        step="1"
                        type="number"
                        value="78"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UTS Dodo"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="dodo-uts"
                        step="1"
                        type="number"
                        value="75"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UAS Dodo"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="dodo-uas"
                        step="1"
                        type="number"
                        value="80"
                    >

                </div>

                <output
                    class="nilai-akhir"
                    aria-label="Nilai akhir Dodo"
                    data-student="dodo"
                >
                    78
                </output>

                <span
                    class="predikat-badge predikat-c"
                    data-predicate="dodo"
                >
                    <span>C</span>
                </span>

            </div>


            {{-- =================================================
                 EREH
                 ================================================= --}}
            <div class="nilai-row">

                <span
                    class="nilai-no"
                    role="rowheader"
                >
                    4
                </span>

                <span
                    class="nilai-student-name"
                    role="gridcell"
                >
                    Ereh
                </span>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai Tugas Ereh"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="ereh-task"
                        step="1"
                        type="number"
                        value="88"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UTS Ereh"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="ereh-uts"
                        step="1"
                        type="number"
                        value="82"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UAS Ereh"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="ereh-uas"
                        step="1"
                        type="number"
                        value="85"
                    >

                </div>

                <output
                    class="nilai-akhir"
                    aria-label="Nilai akhir Ereh"
                    data-student="ereh"
                >
                    85
                </output>

                <span
                    class="predikat-badge predikat-b"
                    data-predicate="ereh"
                >
                    <span>B</span>
                </span>

            </div>


            {{-- =================================================
                 RINA
                 ================================================= --}}
            <div class="nilai-row">

                <span
                    class="nilai-no"
                    role="rowheader"
                >
                    5
                </span>

                <span
                    class="nilai-student-name"
                    role="gridcell"
                >
                    Rina
                </span>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai Tugas Rina"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="rina-task"
                        step="1"
                        type="number"
                        value="75"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UTS Rina"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="rina-uts"
                        step="1"
                        type="number"
                        value="78"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UAS Rina"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="rina-uas"
                        step="1"
                        type="number"
                        value="82"
                    >

                </div>

                <output
                    class="nilai-akhir"
                    aria-label="Nilai akhir Rina"
                    data-student="rina"
                >
                    79
                </output>

                <span
                    class="predikat-badge predikat-c"
                    data-predicate="rina"
                >
                    <span>C</span>
                </span>

            </div>


            {{-- =================================================
                 MOANA
                 ================================================= --}}
            <div class="nilai-row">

                <span
                    class="nilai-no"
                    role="rowheader"
                >
                    6
                </span>

                <span
                    class="nilai-student-name"
                    role="gridcell"
                >
                    Moana
                </span>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai Tugas Moana"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="moana-task"
                        step="1"
                        type="number"
                        value="92"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UTS Moana"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="moana-uts"
                        step="1"
                        type="number"
                        value="88"
                    >

                </div>

                <div class="nilai-input-wrapper">

                    <input
                        aria-label="Nilai UAS Moana"
                        class="nilai-input"
                        inputmode="numeric"
                        max="100"
                        min="0"
                        name="moana-uas"
                        step="1"
                        type="number"
                        value="90"
                    >

                </div>

                <output
                    class="nilai-akhir"
                    aria-label="Nilai akhir Moana"
                    data-student="moana"
                >
                    90
                </output>

                <span
                    class="predikat-badge predikat-a"
                    data-predicate="moana"
                >
                    <span>A</span>
                </span>

            </div>

        </div>

    </section>

</form>

@endsection

@push('scripts')
<script src="{{ asset('js/input-nilai.js') }}"></script>
@endpush