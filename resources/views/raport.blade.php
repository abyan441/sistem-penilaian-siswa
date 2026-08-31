@extends('layouts.app')

@section('title', 'Raport | Cyber Olympus E-Raport System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/raport.css') }}">
@endpush

@section('content')
<section class="raport-page" id="raport" aria-labelledby="raport-title">
    <header class="raport-heading">
        <div class="raport-heading-copy">
            <h1 id="raport-title">Cetak Raport</h1>
            <p>Preview dan cetak raport siswa</p>
        </div>
    </header>

    <section class="raport-filter-card" aria-label="Filter raport">
        <form class="raport-filter-form" id="raport-filter-form">
            <div class="raport-filter-fields">
                <label class="raport-field">
                    <span>Pilih Siswa</span>
                    <select name="siswa" id="raport-siswa" aria-label="Pilih Siswa">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach ($siswa as $item)
                            <option
                                value="{{ $item->id }}"
                                data-nisn="{{ $item->nisn }}"
                                data-kelas="{{ $item->kelas?->nama_kelas }}"
                                @selected((string) $siswaTerpilih === (string) $item->id)
                            >
                                {{ $item->nama_siswa }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="raport-field">
                    <span>Semester</span>
                    <select name="semester" id="raport-semester" aria-label="Semester">
                        <option value="1" @selected($semester === 1)>Semester 1 (Ganjil)</option>
                        <option value="2" @selected($semester === 2)>Semester 2 (Genap)</option>
                    </select>
                </label>

                <label class="raport-field">
                    <span>Tahun Ajaran</span>
                    <select name="tahun_ajaran" id="raport-tahun-ajaran" aria-label="Tahun Ajaran">
                        @forelse ($tahunAjaranOptions as $tahun)
                            <option value="{{ $tahun }}" @selected($tahun === $tahunAjaran)>
                                {{ str_replace('-', '/', $tahun) }}
                            </option>
                        @empty
                            <option value="">Belum ada tahun ajaran</option>
                        @endforelse
                    </select>
                </label>
            </div>

            <div class="raport-filter-actions">
                <button class="raport-preview-button" type="submit" id="raport-preview-button">
                    <svg class="raport-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                        <circle cx="12" cy="12" r="2.7"></circle>
                    </svg>
                    <span>Preview Raport</span>
                </button>

                <button class="raport-pdf-button" type="button" id="raport-pdf-button">
                    <svg class="raport-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M12 3v11"></path>
                        <path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path>
                        <path d="M5 20h14"></path>
                    </svg>
                    <span>Cetak PDF</span>
                </button>
            </div>
        </form>
    </section>

    <section class="raport-search-card" aria-label="Pencarian siswa">
        <label class="raport-search-box">
            <input
                class="raport-search-input"
                id="raport-search-input"
                type="search"
                name="cari-siswa"
                placeholder="Cari siswa berdasarkan NISN, Nama atau Kelas..."
                autocomplete="off"
            >
            <svg class="raport-search-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="11" cy="11" r="6.5"></circle>
                <path d="M16 16L21 21"></path>
            </svg>
        </label>
    </section>

    <section class="raport-table-card" aria-label="Daftar siswa">
        <div class="raport-table-head" role="row">
            <div role="columnheader">No</div>
            <div role="columnheader">NISN</div>
            <div role="columnheader">Nama Siswa</div>
            <div role="columnheader">Kelas</div>
            <div role="columnheader">Semester</div>
            <div role="columnheader">Aksi</div>
        </div>

        <div class="raport-table-body" id="raport-table-body" role="rowgroup">
            @forelse ($siswa as $index => $item)
                <div
                    class="raport-table-row"
                    role="row"
                    data-student="{{ $item->nisn }} {{ $item->nama_siswa }} {{ $item->kelas?->nama_kelas }} Semester {{ $semester }}"
                >
                    <div role="gridcell">{{ $index + 1 }}</div>
                    <div role="gridcell">{{ $item->nisn }}</div>
                    <div role="gridcell">{{ $item->nama_siswa }}</div>
                    <div role="gridcell">
                        <span class="raport-class-badge">{{ $item->kelas?->nama_kelas ?? '-' }}</span>
                    </div>
                    <div role="gridcell">Semester {{ $semester }}</div>
                    <div class="raport-actions" role="gridcell">
                        <button
                            type="button"
                            class="raport-action-preview"
                            aria-label="Preview raport {{ $item->nama_siswa }}"
                            data-student-id="{{ $item->id }}"
                            data-student-name="{{ $item->nama_siswa }}"
                        >
                            <svg class="raport-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                                <circle cx="12" cy="12" r="2.7"></circle>
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="raport-action-download"
                            aria-label="Unduh raport {{ $item->nama_siswa }}"
                            data-student-id="{{ $item->id }}"
                            data-student-name="{{ $item->nama_siswa }}"
                        >
                            <svg class="raport-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M12 3v11"></path>
                                <path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path>
                                <path d="M5 20h14"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="raport-table-row raport-empty-row" role="row">
                    <div role="gridcell" style="grid-column: 1 / -1; text-align: center;">
                        Belum ada data siswa untuk tahun ajaran yang dipilih.
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</section>
@endsection

@push('scripts')
    <script src="{{ asset('js/raport.js') }}"></script>
@endpush
