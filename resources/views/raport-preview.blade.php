<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Preview Raport - {{ $siswa->nama_siswa }} | Cyber Olympus E-Raport System</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/styleguide.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pages/raport-preview.css') }}" />
</head>
<body>
<main class="dashboard">
    <aside class="frame" aria-label="Navigasi utama">
        <button class="mobile-menu-toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobile-navigation"><span></span><span></span><span></span></button>
        <div class="div"><div class="hugeicons-school" aria-hidden="true"><img class="school-icon-img" src="{{ asset('images/logo-cyber-olympus.png') }}" alt="Logo Sekolah" /></div><div class="frame-2"><p class="text-wrapper">Cyber Olympus</p><p class="text-wrapper-2">E-Raport System</p></div></div>
        <div class="line" aria-hidden="true"></div>
        <nav class="frame-3" id="mobile-navigation" aria-label="Menu dashboard">
            <a class="div-2" href="{{ url('/') }}"><span class="img-2">▦</span><span class="text-wrapper-4">Dashboard</span></a>
            <a class="div-2" href="{{ url('/guru') }}"><span class="img-2">♙</span><span class="text-wrapper-4">Data Guru</span></a>
            <a class="div-2" href="{{ url('/siswa') }}"><span class="img-2">♙</span><span class="text-wrapper-4">Data Siswa</span></a>
            <a class="div-2" href="{{ url('/kelas') }}"><span class="img-2">▣</span><span class="text-wrapper-4">Data Kelas</span></a>
            <a class="div-2" href="{{ url('/mata-pelajaran') }}"><span class="img-2">▤</span><span class="text-wrapper-4">Mata Pelajaran</span></a>
            <a class="div-2" href="{{ url('/input-nilai') }}"><span class="img-2">✎</span><span class="text-wrapper-4">Input Nilai</span></a>
            <a class="button-dashboard" href="{{ route('raport') }}" aria-current="page"><span class="img-2">▧</span><span class="text-wrapper-3">Raport</span></a>
            @if (auth()->user()->role === 'admin')<a class="div-2" href="{{ url('/pengguna') }}"><span class="img-2">♙</span><span class="text-wrapper-4">Pengguna</span></a>@endif
            <form method="POST" action="{{ route('logout') }}" class="logout-form">@csrf<button class="button-logout" type="submit"><span class="img-2">⇥</span><span class="text-wrapper-4">Logout</span></button></form>
        </nav>
    </aside>

    <section class="frame-4">
        <header class="frame-5">
            <div class="frame-6"><span class="fluent" aria-hidden="true">◉</span><div class="frame-7"><p class="text-wrapper-5">Aplikasi E-Raport Cyber Olympus</p><p class="text-wrapper-6">Sistem Manajemen Raport Digital</p></div></div>
            <div class="frame-8"><div class="frame-9"><div class="div-wrapper"><p class="text-wrapper-7">{{ auth()->user()->nama_lengkap ?? 'Pengguna' }}</p></div><p class="text-wrapper-8">{{ auth()->user()->role === 'kepala_sekolah' ? 'Kepala Sekolah' : ucfirst(auth()->user()->role ?? '') }}</p></div><button class="frame-10" type="button" aria-label="Menu akun"><span class="frame-wrapper"><span class="frame-11"><span class="ellipse-wrapper"><span class="ellipse"></span></span><span class="text-wrapper-9">{{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 2)) }}</span></span></span><span class="mingcute-down-fill">⌄</span></button></div>
        </header>
        <div class="line" aria-hidden="true"></div>

        <main class="print-content" id="raport">
            <div class="print-toolbar"><button class="back-button" id="back-button" type="button"><span aria-hidden="true">←</span><span>Kembali</span></button><button class="download-button" id="download-pdf-button" type="button"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v11"></path><path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path><path d="M5 20h14"></path></svg><span>Download PDF</span></button></div>

            <div class="report-scroll-container">
                <article class="report-paper" id="report-paper">
                    <header class="report-header">
                        <h2>LAPORAN HASIL BELAJAR SISWA</h2><h3>(RAPORT)</h3><p class="report-school-name">Cyber Olympus</p>
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
                                    @php $nilaiAkhir=(float)$item->nilai_akhir; $predikat=$nilaiAkhir>=90?'A':($nilaiAkhir>=80?'B':($nilaiAkhir>=70?'C':'D')); $deskripsi=['A'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','D'=>'Kurang'][$predikat]; @endphp
                                    <tr><td>{{ $index + 1 }}</td><td>{{ $item->guruMapel?->mataPelajaran?->nama_pelajaran ?? 'Mata pelajaran tidak ditemukan' }}</td><td>{{ number_format($nilaiAkhir, 2, ',', '.') }}</td><td>{{ $predikat }}</td><td>{{ $deskripsi }}</td></tr>
                                @empty
                                    <tr><td colspan="5">Belum terdapat nilai untuk siswa ini pada semester yang dipilih.</td></tr>
                                @endforelse
                                    <tr class="average-row"><td colspan="2">Rata-rata Nilai</td><td>{{ number_format($rataRata, 2, ',', '.') }}</td><td></td><td></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="predicate-box"><p class="predicate-title">Keterangan Predikat</p><div class="predicate-list"><span>A = 90 - 100 (Sangat Baik)</span><span>B = 80 - 89 (Baik)</span><span>C = 70 - 79 (Cukup)</span><span>D &lt; 70 (Kurang)</span></div></div>
                    </section>

                    <section class="report-section note-section"><h3 class="report-section-title">Catatan Wali Kelas</h3><div class="teacher-note">{{ $waliKelas ? 'Raport siswa telah diperiksa oleh wali kelas.' : 'Belum ada wali kelas yang terdaftar untuk kelas ini.' }}</div></section>
                    <section class="signature-section">
                        <div class="signature-column"><p class="signature-title">Orang Tua/Wali</p><div class="signature-space"></div><p class="signature-line signature-parent-line">(____________________)</p></div>
                        <div class="signature-column"><p class="signature-title">Wali Kelas</p><div class="signature-space"></div><p class="signature-line signature-name">{{ $waliKelas?->nama_lengkap ?? '-' }}</p></div>
                        <div class="signature-column"><p class="signature-title">Kepala Sekolah</p><div class="signature-space"></div><p class="signature-line signature-name">{{ $kepalaSekolah?->nama_lengkap ?? '-' }}</p><p class="signature-nip">{{ $kepalaSekolah?->nip ? 'NIP. '.$kepalaSekolah->nip : '' }}</p></div>
                    </section>
                </article>
            </div>
        </main>
    </section>
</main>
<script src="{{ asset('js/raport-preview.js') }}"></script>
</body>
</html>
