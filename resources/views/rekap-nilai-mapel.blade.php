@extends('layouts.app')

@section('title', 'Rekap Nilai Mapel | Cyber Olympus E-Raport System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/rekap-nilai-mapel.css') }}">
@endpush

@section('content')
<section class="rekap-mapel-page" id="rekap-mapel" aria-labelledby="rekap-mapel-title">
    <header class="rekap-mapel-heading">
        <div class="rekap-mapel-heading-copy">
            <h1 id="rekap-mapel-title">Rekap Nilai Mapel</h1>
            <p>Preview dan cetak rekap nilai mata pelajaran siswa</p>
        </div>
    </header>

    <section class="rekap-mapel-filter-card" aria-label="Filter rekap nilai mata pelajaran">
        <form class="rekap-mapel-filter-form" id="rekap-mapel-filter-form">
            <div class="rekap-mapel-filter-fields">
                <label class="rekap-mapel-field">
                    <span>Pilih Mata Pelajaran</span>
                    <select name="mapel_id" id="rekap-mapel-mapel" aria-label="Pilih Mata Pelajaran">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($mataPelajaran as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_pelajaran }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="rekap-mapel-field">
                    <span>Semester</span>
                    <select name="semester" id="rekap-mapel-semester" aria-label="Semester">
                        <option value="1" @selected($semester === 1)>Semester 1 (Ganjil)</option>
                        <option value="2" @selected($semester === 2)>Semester 2 (Genap)</option>
                    </select>
                </label>

                <label class="rekap-mapel-field">
                    <span>Tahun Ajaran</span>
                    <select name="tahun_ajaran" id="rekap-mapel-tahun-ajaran" aria-label="Tahun Ajaran">
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

            <div class="rekap-mapel-filter-actions">
                <button class="rekap-mapel-preview-button" type="submit" id="rekap-mapel-preview-button">
                    <svg class="rekap-mapel-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                        <circle cx="12" cy="12" r="2.7"></circle>
                    </svg>
                    <span>Preview Rekap</span>
                </button>

                <button class="rekap-mapel-pdf-button" type="button" id="rekap-mapel-pdf-button">
                    <svg class="rekap-mapel-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path d="M12 3v11"></path>
                        <path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path>
                        <path d="M5 20h14"></path>
                    </svg>
                    <span>Cetak PDF</span>
                </button>
            </div>
        </form>
    </section>

    <section class="rekap-mapel-table-card" aria-label="Daftar rekap nilai mata pelajaran">
        <div class="rekap-mapel-table-head" role="row">
            <div role="columnheader">No</div>
            <div role="columnheader">Mata Pelajaran</div>
            <div role="columnheader">Kelas</div>
            <div role="columnheader">Semester</div>
            <div role="columnheader">Aksi</div>
        </div>

        <div class="rekap-mapel-table-body" id="rekap-mapel-table-body" role="rowgroup">
            <div class="rekap-mapel-table-row rekap-mapel-empty-row" role="row">
                <div role="gridcell" style="grid-column: 1 / -1; text-align: center;">
                    Pilih mata pelajaran, semester, dan tahun ajaran untuk menampilkan data.
                </div>
            </div>
        </div>
    </section>
</section>
@endsection

@push('scripts')
    <script src="{{ asset('js/rekap-nilai-mapel.js') }}"></script>
@endpush
