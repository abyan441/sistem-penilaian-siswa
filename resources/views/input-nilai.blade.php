@extends('layouts.app')

@section('title', 'Input Nilai | Cyber Olympus E-Raport System')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/input-nilai.css') }}">
@endpush

@section('content')
<form class="nilai-page" id="grade-form">
    @csrf

    <header class="nilai-page-header">
        <div class="nilai-page-title">
            <h1>Input Nilai</h1>
            <p>
                {{ !empty($readOnly) ? 'Melihat nilai siswa per kelas dan mata pelajaran' : 'Input nilai siswa berdasarkan mata pelajaran yang diampu' }}
            </p>
        </div>

        @if (empty($readOnly))
            <button class="button-tambah-siswa" type="submit" id="save-grade-button">
                <svg class="ci-save" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M5 3.5h11.5L20 7v13.5H5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M8 3.5v6h8v-6M8 20.5v-5h8v5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M17 3.5v4h2.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                </svg>
                <span>Simpan Nilai</span>
            </button>
        @endif
    </header>

    <div class="nilai-toast" id="nilai-toast" role="status" aria-live="polite" aria-atomic="true" hidden>
        <span class="nilai-toast-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
                <path d="m5 12.5 4.5 4.5L19 7.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
        <span class="nilai-toast-copy">
            <strong>Nilai berhasil disimpan</strong>
            <span>Perubahan nilai siswa sudah diperbarui.</span>
        </span>
        <button class="nilai-toast-close" type="button" aria-label="Tutup pemberitahuan">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="m7 7 10 10M17 7 7 17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
            </svg>
        </button>
    </div>

    <section class="nilai-filter-wrapper" aria-label="Informasi dan filter nilai">
        <div class="nilai-filter-card">
            <div class="nilai-info-grid" style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 20px;">

                <label class="nilai-filter-item">
                    <span class="nilai-filter-label">Kelas</span>
                    <select class="dropdown-items" name="kelas_id" id="kelas-select">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasOptions as $item)
                            <option value="{{ $item->id }}">
                                Kelas {{ $item->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="nilai-filter-item">
                    <span class="nilai-filter-label">Semester</span>
                    <select class="dropdown-items" name="semester" id="semester-select" required>
                        <option value="1" selected>Semester 1 (Ganjil)</option>
                        <option value="2">Semester 2 (Genap)</option>
                    </select>
                </label>

                <label class="nilai-filter-item">
                    <span class="nilai-filter-label">Mata Pelajaran</span>
                    <select class="dropdown-items" name="mapel_id" id="mapel-select" required>
                        <option value="" selected disabled>Pilih Mata Pelajaran</option>
                        @foreach ($mataPelajaran as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_pelajaran }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="nilai-filter-item">
                    <span class="nilai-filter-label">Tahun Ajaran</span>
                    <select class="dropdown-items" name="tahun_ajaran" id="tahun-ajaran-select">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach ($kelasOptions->pluck('tahun_ajaran')->unique()->values() as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <aside class="info-bobot-nilai-wrapper" aria-label="Informasi bobot nilai">
                <p class="info-bobot-nilai">
                    <strong>Info:</strong>
                    <span>
                        {{ !empty($readOnly) ? 'Mode lihat saja. Akun ini tidak dapat mengubah atau menyimpan nilai.' : 'Anda hanya dapat menginput nilai untuk mata pelajaran yang diampu. Kelas dan tahun ajaran dapat difilter secara terpisah.' }}
                    </span>
                </p>
            </aside>
        </div>
    </section>

    <section class="nilai-table-wrapper{{ !empty($isAdmin) ? ' nilai-table-admin' : '' }}" aria-label="Daftar nilai siswa">
        <div class="nilai-table-header" role="row">
            <span role="columnheader">No</span>
            <span role="columnheader">Nama Siswa</span>
            <span role="columnheader">Tugas (30%)</span>
            <span role="columnheader">UTS (30%)</span>
            <span role="columnheader">UAS (40%)</span>
            <span role="columnheader">Nilai Akhir</span>
            <span role="columnheader">Predikat</span>
            @if (!empty($isAdmin))
                <span role="columnheader">Aksi</span>
            @endif
        </div>

        <div class="nilai-table-body" id="nilai-table-body" aria-label="Nilai siswa" role="grid">
            <div class="nilai-empty-state">
                <span>
                    {{ !empty($readOnly) ? 'Pilih kelas, tahun ajaran, mata pelajaran, dan semester untuk menampilkan nilai.' : 'Pilih kelas atau tahun ajaran, mata pelajaran, dan semester untuk menampilkan siswa.' }}
                </span>
            </div>
        </div>
    </section>
</form>
@endsection

@push('scripts')
<script>
    window.inputNilaiConfig = {
        dataUrl: @json(route('input-nilai.data')),
        storeUrl: @json(route('input-nilai.store')),
        deleteUrl: @json(url('/input-nilai')),
        csrfToken: @json(csrf_token()),
        readOnly: @json((bool) ($readOnly ?? false)),
        isAdmin: @json((bool) ($isAdmin ?? false)),
    };
</script>
<script src="{{ asset('js/input-nilai.js') }}"></script>
@endpush
