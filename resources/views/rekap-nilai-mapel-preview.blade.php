@extends('layouts.app')

@section('title', 'Preview Rekap Nilai Mapel | Cyber Olympus E-Raport System')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/rekap-nilai-mapel-preview.css') }}">
@endpush

@section('content')
<main class="report-preview-page rekap-mapel-preview-page print-content" id="rekap-mapel-preview">
<div class="print-toolbar">
<button class="back-button" id="back-button" type="button"><span aria-hidden="true">←</span><span>Kembali</span></button>
<button class="download-button" id="download-pdf-button" type="button"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v11"></path><path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path><path d="M5 20h14"></path></svg><span>Cetak PDF</span></button>
</div>
<div class="report-scroll-container">
<article class="report-paper" id="report-paper">
<header class="report-header">
<h2>REKAP NILAI MATA PELAJARAN</h2>
<p class="report-school-name">Cyber Olympus</p>
<p class="report-school-year">{{ $mapel->nama_pelajaran }} - Kelas {{ $kelas->nama_kelas }} - Semester {{ $semester == 1 ? '1 (Ganjil)' : '2 (Genap)' }}</p>
</header>
<section class="report-section">
<h3 class="report-section-title">Informasi Rekap</h3>
<div class="identity-grid">
<div class="identity-column"><div class="identity-row"><span>Mata Pelajaran</span><strong>{{ $mapel->nama_pelajaran }}</strong></div><div class="identity-row"><span>Kelas</span><strong>{{ $kelas->nama_kelas }}</strong></div></div>
<div class="identity-column"><div class="identity-row"><span>Semester</span><strong>{{ $semester == 1 ? '1 (Ganjil)' : '2 (Genap)' }}</strong></div><div class="identity-row"><span>Tahun Ajaran</span><strong>{{ $tahunAjaran }}</strong></div></div>
</div>
</section>
<section class="report-section score-section">
<h3 class="report-section-title">Daftar Nilai Siswa</h3>
<div class="score-table-wrapper">
<table class="score-table rekap-mapel-score-table">
<thead><tr><th>No</th><th>NISN</th><th>Nama Siswa</th><th>Nilai Tugas</th><th>Nilai UTS</th><th>Nilai UAS</th><th>Nilai Akhir</th><th>Predikat</th><th>Catatan Guru</th></tr></thead>
<tbody>
@forelse ($data as $item)
<tr>
<td>{{ $item['nomor'] }}</td><td>{{ $item['nisn'] }}</td><td>{{ $item['nama_siswa'] }}</td>
<td>{{ $item['nilai_tugas'] !== null ? number_format($item['nilai_tugas'], 2, ',', '.') : '-' }}</td>
<td>{{ $item['nilai_uts'] !== null ? number_format($item['nilai_uts'], 2, ',', '.') : '-' }}</td>
<td>{{ $item['nilai_uas'] !== null ? number_format($item['nilai_uas'], 2, ',', '.') : '-' }}</td>
<td>{{ $item['nilai_akhir'] !== null ? number_format($item['nilai_akhir'], 2, ',', '.') : '-' }}</td>
<td>{{ $item['predikat'] }}</td><td>{{ filled($item['catatan_guru']) ? $item['catatan_guru'] : '-' }}</td>
</tr>
@empty
<tr><td colspan="9">Belum terdapat siswa pada kelas ini.</td></tr>
@endforelse
</tbody>
</table>
</div>
</section>
</article>
</div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('js/raport-preview.js') }}"></script>
@endpush
