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
                {{ !empty($readOnly) ? 'Melihat nilai siswa per kelas dan mata pelajaran' : 'Input nilai siswa per mata pelajaran' }}
            </p>
        </div>

        @if (empty($readOnly))
            <button class="button-tambah-siswa" type="submit" id="save-grade-button">
                <svg class="ci-save" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path d="M5 3.5h11.5L20 7v13.5H5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M8 3.5v6h8v-6M8 20.5v-5h8v5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M17 3.5v4h2.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
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
            <div class="nilai-info-grid">

                {{-- KELAS --}}
                @if (!empty($isAdmin))
                    <label class="nilai-filter-item">
                        <span class="nilai-filter-label">Kelas</span>
                        <select class="dropdown-items" name="kelas_id" id="kelas-select" required>
                            <option value="" selected disabled>Pilih Kelas</option>
                            @foreach ($kelasOptions as $item)
                                <option value="{{ $item->id }}" data-tahun-ajaran="{{ $item->tahun_ajaran }}">
                                    Kelas {{ $item->nama_kelas }} ({{ $item->tahun_ajaran }})
                                </option>
                            @endforeach
                        </select>
                    </label>
                @else
                    <div class="nilai-info-item">
                        <span class="nilai-info-label">Kelas</span>
                        <div class="nilai-info-value" aria-label="Kelas">Kelas {{ $kelas->nama_kelas }}</div>
                    </div>
                @endif

                {{-- SEMESTER --}}
                <label class="nilai-filter-item">
                    <span class="nilai-filter-label">Semester</span>
                    <select class="dropdown-items" name="semester" id="semester-select" required>
                        <option value="1" selected>Semester 1 (Ganjil)</option>
                        <option value="2">Semester 2 (Genap)</option>
                    </select>
                </label>

                {{-- MATA PELAJARAN --}}
                <label class="nilai-filter-item">
                    <span class="nilai-filter-label">Mata Pelajaran</span>
                    <select class="dropdown-items" name="mapel_id" id="mapel-select" required>
                        <option value="" selected disabled>Pilih Mata Pelajaran</option>
                        @foreach ($mataPelajaran as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_pelajaran }}</option>
                        @endforeach
                    </select>
                </label>

                {{-- TAHUN AJARAN --}}
                @if (!empty($isAdmin))
                    <label class="nilai-filter-item">
                        <span class="nilai-filter-label">Tahun Ajaran</span>
                        <select class="dropdown-items" name="tahun_ajaran" id="tahun-ajaran-select" required>
                            <option value="" selected disabled>Pilih Tahun Ajaran</option>
                            @foreach ($tahunAjaranOptions as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </label>
                @else
                    <div class="nilai-info-item">
                        <span class="nilai-info-label">Tahun Ajaran</span>
                        <div class="nilai-info-value" aria-label="Tahun Ajaran">{{ $kelas->tahun_ajaran }}</div>
                    </div>
                @endif
            </div>

            <aside class="info-bobot-nilai-wrapper" aria-label="Informasi bobot nilai">
                <p class="info-bobot-nilai">
                    <strong>Info:</strong>
                    <span>
                        {{ !empty($readOnly) ? 'Mode lihat saja. Admin tidak dapat mengubah atau menyimpan nilai.' : 'Bobot Nilai - Tugas (30%), UTS (30%), UAS (40%)' }}
                    </span>
                </p>
            </aside>
        </div>
    </section>

    <section class="nilai-table-wrapper" aria-label="Daftar nilai siswa">
        <div class="nilai-table-header" role="row">
            <span role="columnheader">No</span>
            <span role="columnheader">Nama Siswa</span>
            <span role="columnheader">Tugas (30%)</span>
            <span role="columnheader">UTS (30%)</span>
            <span role="columnheader">UAS (40%)</span>
            <span role="columnheader">Nilai Akhir</span>
            <span role="columnheader">Predikat</span>
        </div>

        <div class="nilai-table-body" id="nilai-table-body" aria-label="Nilai siswa" role="grid">
            <div class="nilai-empty-state">
                <span>
                    {{ !empty($readOnly) ? 'Pilih kelas, tahun ajaran, mata pelajaran, dan semester untuk menampilkan nilai.' : 'Pilih mata pelajaran untuk menampilkan data siswa.' }}
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
        csrfToken: @json(csrf_token()),
        readOnly: @json((bool) ($readOnly ?? false)),
        isAdmin: @json((bool) ($isAdmin ?? false)),
    };
</script>

<script src="{{ asset('js/input-nilai.js') }}"></script>

@if (!empty($isAdmin))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kelasSelect = document.getElementById('kelas-select');
        const tahunSelect = document.getElementById('tahun-ajaran-select');
        const mapelSelect = document.getElementById('mapel-select');
        const semesterSelect = document.getElementById('semester-select');
        const tableBody = document.getElementById('nilai-table-body');
        const form = document.getElementById('grade-form');

        if (!kelasSelect || !tahunSelect || !tableBody) {
            return;
        }

        const allClassOptions = Array.from(kelasSelect.options)
            .filter(function (option) {
                return option.value !== '';
            })
            .map(function (option) {
                return {
                    value: option.value,
                    text: option.textContent.trim(),
                    year: option.dataset.tahunAjaran || '',
                };
            });

        function filterClassesByYear() {
            const selectedYear = tahunSelect.value;
            const currentClass = kelasSelect.value;

            while (kelasSelect.options.length > 1) {
                kelasSelect.remove(1);
            }

            allClassOptions.forEach(function (item) {
                if (!selectedYear || item.year === selectedYear) {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = item.text;
                    option.dataset.tahunAjaran = item.year;
                    kelasSelect.appendChild(option);
                }
            });

            const currentStillExists = Array.from(kelasSelect.options).some(function (option) {
                return option.value === currentClass;
            });

            kelasSelect.value = currentStillExists ? currentClass : '';
        }

        function syncYearFromClass() {
            const selected = kelasSelect.options[kelasSelect.selectedIndex];

            if (selected && selected.dataset.tahunAjaran) {
                tahunSelect.value = selected.dataset.tahunAjaran;
            }
        }

        const originalFetch = window.fetch.bind(window);

        window.fetch = function (input, init) {
            let url = typeof input === 'string'
                ? input
                : (input && input.url ? input.url : '');

            if (url.includes('/input-nilai/data')) {
                const params = new URLSearchParams();

                if (kelasSelect.value) {
                    params.set('kelas_id', kelasSelect.value);
                }

                if (tahunSelect.value) {
                    params.set('tahun_ajaran', tahunSelect.value);
                }

                const query = params.toString();

                if (query) {
                    url += (url.includes('?') ? '&' : '?') + query;
                }

                if (typeof input === 'string') {
                    input = url;
                } else if (input instanceof Request) {
                    input = new Request(url, input);
                } else {
                    input = url;
                }
            }

            return originalFetch(input, init);
        };

        function disableGradeInputs() {
            tableBody.querySelectorAll('.nilai-input').forEach(function (input) {
                input.disabled = true;
                input.readOnly = true;
                input.setAttribute('aria-readonly', 'true');
            });
        }

        const observer = new MutationObserver(disableGradeInputs);
        observer.observe(tableBody, { childList: true, subtree: true });
        disableGradeInputs();

        function triggerDataReload() {
            if (!semesterSelect || !mapelSelect) {
                return;
            }

            if (!kelasSelect.value || !tahunSelect.value || !mapelSelect.value) {
                return;
            }

            mapelSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }

        kelasSelect.addEventListener('change', function () {
            syncYearFromClass();
            triggerDataReload();
        });

        tahunSelect.addEventListener('change', function () {
            filterClassesByYear();
            triggerDataReload();
        });

        if (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                event.stopImmediatePropagation();
            }, true);
        }
    });
</script>
@endif

@endpush
