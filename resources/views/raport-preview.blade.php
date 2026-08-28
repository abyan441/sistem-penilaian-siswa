@extends('layouts.app')

@section('title', 'Preview Raport - ' . ($siswa->nama_siswa ?? 'Siswa') . ' | Cyber Olympus E-Raport System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/raport-preview.css') }}">
    <style>
        .report-preview-page { width: 100%; min-width: 0; box-sizing: border-box; padding: 0 24px 40px; }
        .report-preview-page .print-toolbar { width: 100%; box-sizing: border-box; }
        @media (max-width: 1100px) { .report-preview-page { padding-left: 18px; padding-right: 18px; } }
        @media (max-width: 768px) { .report-preview-page { padding-left: 12px; padding-right: 12px; padding-bottom: 24px; } }
        @media (max-width: 480px) { .report-preview-page { padding-left: 10px; padding-right: 10px; padding-bottom: 20px; } }
    </style>
@endpush

@section('content')
<main class="report-preview-page print-content" id="raport">
    <div class="print-toolbar">
        <button class="back-button" id="back-button" type="button"><span aria-hidden="true">←</span><span>Kembali</span></button>
        <button class="download-button" id="download-pdf-button" type="button">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v11"></path><path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path><path d="M5 20h14"></path></svg>
            <span>Download PDF</span>
        </button>
    </div>

    <div class="report-scroll-container">
        <article class="report-paper" id="report-paper">
            <header class="report-header">
                <h2>LAPORAN HASIL BELAJAR SISWA</h2>
                <h3>(RAPORT)</h3>
                <p class="report-school-name">Cyber Olympus</p>
                <p class="report-school-year">Tahun Pelajaran {{ $tahunAjaran ?? '-' }} - Semester {{ $semester == 1 ? '1 (Ganjil)' : '2 (Genap)' }}</p>
            </header>

            <section class="report-section">
                <h3 class="report-section-title">Identitas Siswa</h3>
                <div class="identity-grid">
                    <div class="identity-column">
                        <div class="identity-row"><span>Nama Siswa</span><strong>{{ $siswa->nama_siswa }}</strong></div>
                        <div class="identity-row"><span>NISN</span><strong>{{ $siswa->nisn }}</strong></div>
                        <div class="identity-row"><span>Kelas</span><strong>{{ $kelas?->nama_kelas ?? '-' }}</strong></div>
                        <div class="identity-row"><span>Semester</span><strong>{{ $semester == 1 ? '1 (Ganjil)' : '2 (Genap)' }}</strong></div>
                    </div>
                    <div class="identity-column">
                        <div class="identity-row"><span>Nama Sekolah</span><strong>Cyber Olympus</strong></div>
                        <div class="identity-row"><span>Alamat</span><strong>Yogyakarta</strong></div>
                        <div class="identity-row"><span>Wali Kelas</span><strong>{{ $waliKelas?->nama_lengkap ?? '-' }}</strong></div>
                        <div class="identity-row"><span>Tahun Pelajaran</span><strong>{{ $tahunAjaran ?? '-' }}</strong></div>
                    </div>
                </div>
            </section>

            <section class="report-section score-section">
                <h3 class="report-section-title">Nilai Mata Pelajaran</h3>
                <div class="score-table-wrapper">
                    <table class="score-table">
                        <thead><tr><th class="number-column">No</th><th class="subject-column">Mata Pelajaran</th><th class="score-column">Nilai</th><th class="predicate-column">Predikat</th><th class="description-column">Deskripsi</th></tr></thead>
                        <tbody>
                            @forelse ($nilai as $index => $item)
                                @php($nilaiAkhir = (float) $item->nilai_akhir)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->guruMapel?->mataPelajaran?->nama_pelajaran ?? 'Mata pelajaran tidak ditemukan' }}</td>
                                    <td>{{ number_format($nilaiAkhir, 2, ',', '.') }}</td>
                                    <td>{{ $item->predikat }}</td>
                                    <td>{{ $item->deskripsi_predikat }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5">Belum terdapat nilai untuk siswa ini pada semester yang dipilih.</td></tr>
                            @endforelse
                            <tr class="average-row"><td colspan="2">Rata-rata Nilai</td><td>{{ number_format($rataRata, 2, ',', '.') }}</td><td></td><td></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="predicate-box">
                    <p class="predicate-title">Keterangan Predikat</p>
                    <div class="predicate-list"><span>A = 90 - 100 (Sangat Baik)</span><span>B = 80 - 89 (Baik)</span><span>C = 70 - 79 (Cukup)</span><span>D &lt; 70 (Kurang)</span></div>
                </div>
            </section>

            <section class="report-section note-section">
                <h3 class="report-section-title">Catatan Wali Kelas</h3>
                <div class="teacher-note">{{ $waliKelas ? 'Raport siswa telah diperiksa oleh wali kelas.' : 'Belum ada wali kelas yang terdaftar untuk kelas ini.' }}</div>
            </section>

            <section class="signature-section">
                <div class="signature-column"><p class="signature-title">Orang Tua/Wali</p><div class="signature-space"></div><p class="signature-line signature-parent-line">(____________________)</p></div>
                <div class="signature-column"><p class="signature-title">Wali Kelas</p><div class="signature-space"></div><p class="signature-line signature-name">{{ $waliKelas?->nama_lengkap ?? '-' }}</p></div>
                <div class="signature-column"><p class="signature-title">Kepala Sekolah</p><div class="signature-space"></div><p class="signature-line signature-name">{{ $kepalaSekolah?->nama_lengkap ?? '-' }}</p><p class="signature-nip">{{ $kepalaSekolah?->nip ? 'NIP. '.$kepalaSekolah->nip : '' }}</p></div>
            </section>
        </article>
    </div>
</main>
@endsection

@push('scripts')
    <script src="{{ asset('js/raport-preview.js') }}"></script>
@endpush
