@extends('layouts.app')

@section('title', 'Dashboard | Cyber Olympus E-Raport System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/dashboard.css') }}">
@endpush

@section('content')
    <section class="frame-12" aria-labelledby="dashboard-title">
        <div class="frame-7">
            <h1 class="text-wrapper-5" id="dashboard-title">Dashboard</h1>
            <p class="p">Selamat datang di Aplikasi E-Raport Cyber Olympus</p>
        </div>
    </section>

    <section class="frame-13" aria-label="Ringkasan data sekolah">
        <article class="frame-14" id="data-siswa">
            <span class="fluent-mdl" aria-hidden="true">
                <img class="vector-10" src="{{ asset('gambar/jumlah_siswa.png') }}" alt="">
            </span>
            <div class="frame-15">
                <h2 class="text-wrapper-10">Jumlah Siswa</h2>
                <p class="text-wrapper-11">{{ number_format($totalSiswa, 0, ',', '.') }}</p>
            </div>
        </article>

        <article class="frame-14" id="data-guru">
            <span class="radix-icons-people" aria-hidden="true">
                <img class="vector-11" src="{{ asset('gambar/jumlah_guru.png') }}" alt="">
            </span>
            <div class="frame-15">
                <h2 class="text-wrapper-10">Jumlah Guru</h2>
                <p class="text-wrapper-11">{{ number_format($totalGuru, 0, ',', '.') }}</p>
            </div>
        </article>

        <article class="frame-14" id="data-kelas">
            <span class="group-wrapper" aria-hidden="true">
                <img class="vector-12" src="{{ asset('gambar/jumlah_kelas.png') }}" alt="">
            </span>
            <div class="frame-15">
                <h2 class="text-wrapper-10">Jumlah Kelas</h2>
                <p class="text-wrapper-11">{{ number_format($totalKelas, 0, ',', '.') }}</p>
            </div>
        </article>
    </section>

    <section class="frame-16" aria-labelledby="chart-title">
        <figure class="frame-17 student-chart-card">
            <figcaption class="text-wrapper-12" id="chart-title">
                Perkembangan Rata-rata Nilai Siswa
            </figcaption>

            <div class="student-chart" role="img" aria-label="Grafik perkembangan rata-rata nilai siswa berdasarkan tahun ajaran">
                @php
                    $chartCount = count($chartLabels);
                    $chartPoints = [];
                    $chartPolyline = '';

                    if ($chartCount > 0) {
                        foreach ($chartValues as $index => $value) {
                            $x = $chartCount === 1
                                ? 555
                                : 60 + ($index * (990 / ($chartCount - 1)));
                            $y = 305 - (max(0, min(100, (float) $value)) * 2.8);
                            $chartPoints[] = [
                                'x' => round($x, 2),
                                'y' => round($y, 2),
                            ];
                            $chartPolyline .= ($index === 0 ? '' : ' ') . round($x, 2) . ',' . round($y, 2);
                        }
                    }
                @endphp

                <svg class="student-chart-svg" viewBox="0 0 1100 360" preserveAspectRatio="none" aria-hidden="true">
                    <g class="chart-grid">
                        <line x1="60" y1="25" x2="1050" y2="25"></line>
                        <line x1="60" y1="95" x2="1050" y2="95"></line>
                        <line x1="60" y1="165" x2="1050" y2="165"></line>
                        <line x1="60" y1="235" x2="1050" y2="235"></line>
                        <line x1="60" y1="305" x2="1050" y2="305"></line>
                        @foreach ($chartPoints as $point)
                            <line x1="{{ $point['x'] }}" y1="25" x2="{{ $point['x'] }}" y2="305"></line>
                        @endforeach
                    </g>

                    <g class="chart-y-labels">
                        <text x="43" y="310">0</text>
                        <text x="43" y="240">25</text>
                        <text x="43" y="170">50</text>
                        <text x="43" y="100">75</text>
                        <text x="43" y="30">100</text>
                    </g>

                    @if ($chartCount > 0)
                        <polyline class="chart-line" points="{{ $chartPolyline }}"></polyline>
                        <g class="chart-points">
                            @foreach ($chartPoints as $point)
                                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="5"></circle>
                            @endforeach
                        </g>
                    @endif

                    <g class="chart-x-labels">
                        @foreach ($chartLabels as $index => $label)
                            @php
                                $x = $chartCount === 1
                                    ? 555
                                    : 60 + ($index * (990 / ($chartCount - 1)));
                            @endphp
                            <text x="{{ round($x, 2) }}" y="345">{{ $label }}</text>
                        @endforeach
                    </g>
                </svg>

                @if ($chartCount === 0)
                    <p class="dashboard-empty-state">Belum terdapat data nilai yang dapat ditampilkan.</p>
                @endif
            </div>
        </figure>
    </section>

    <section class="frame-18" aria-labelledby="activity-title">
        <h2 class="text-wrapper-12" id="activity-title">Aktivitas Terbaru</h2>

        <div class="frame-19" role="table" aria-label="Daftar data nilai terbaru">
            <div class="frame-20" role="row">
                <div class="text-wrapper-20" role="columnheader">Aksi</div>
                <div class="text-wrapper-20" role="columnheader">Detail</div>
                <div class="text-wrapper-20" role="columnheader">Pengguna</div>
                <div class="text-wrapper-20" role="columnheader">Keterangan</div>
            </div>

            <div class="frame-21" role="rowgroup">
                @forelse ($aktivitas as $item)
                    <div class="text-wrapper-21" role="cell">Input Nilai</div>
                    <div class="text-wrapper-22" role="cell">
                        {{ $item->guruMapel?->mataPelajaran?->nama_pelajaran ?? 'Mata pelajaran tidak ditemukan' }}
                        - Kelas {{ $item->siswa?->kelas?->nama_kelas ?? '-' }}
                    </div>
                    <div class="text-wrapper-23" role="cell">
                        {{ $item->guruMapel?->guru?->nama_lengkap ?? 'Pengguna tidak ditemukan' }}
                    </div>
                    <div class="text-wrapper-24" role="cell">
                        Semester {{ $item->semester }} · Nilai akhir {{ number_format((float) $item->nilai_akhir, 2, ',', '.') }}
                    </div>
                @empty
                    <div class="text-wrapper-21" role="cell">-</div>
                    <div class="text-wrapper-22" role="cell">Belum ada data nilai</div>
                    <div class="text-wrapper-23" role="cell">-</div>
                    <div class="text-wrapper-24" role="cell">Belum ada aktivitas</div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
