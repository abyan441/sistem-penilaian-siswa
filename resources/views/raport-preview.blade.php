<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <base href="{{ url('/') }}/" />
    <title>Preview Raport - {{ $id }} | Cyber Olympus E-Raport System</title>
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/styleguide.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pages/raport-preview.css') }}" />
</head>

<body>
    <main class="dashboard">
        <!-- SIDEBAR (FIXED) -->
        <aside class="frame" aria-label="Navigasi utama">
            <button class="mobile-menu-toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false"
                aria-controls="mobile-navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="div">
                <!-- Wadah Ikon Sekolah -->
                <div class="hugeicons-school" aria-hidden="true">
                    <img class="school-icon-img" src="gambar/school_icon.png" alt="Logo Sekolah" />
                </div>
                <div class="frame-2">
                    <p class="text-wrapper">Cyber Olympus</p>
                    <p class="text-wrapper-2">E-Raport System</p>
                </div>
            </div>
            <div class="line" aria-hidden="true"></div>
            <nav class="frame-3" id="mobile-navigation" aria-label="Menu dashboard">
                <a class="div-2" href="#dashboard">
                    <img class="img-2" src="gambar/dashboard_icon.png" alt="" />
                    <span class="text-wrapper-4">Dashboard</span>
                </a>
                <a class="div-2" href="#data-guru">
                    <img class="img-2" src="gambar/guru_icon.png" alt="" />
                    <span class="text-wrapper-4">Data Guru</span>
                </a>
                <a class="div-2" href="#data-siswa">
                    <img class="img-2" src="gambar/siswa_icon.png" alt="" />
                    <span class="text-wrapper-4">Data Siswa</span>
                </a>
                <a class="div-2" href="#data-kelas">
                    <img class="img-2" src="gambar/kelas_icon.png" alt="" />
                    <span class="text-wrapper-4">Data Kelas</span>
                </a>
                <a class="div-2" href="#mata-pelajaran">
                    <img class="img-2" src="gambar/mapel_icon.png" alt="" />
                    <span class="text-wrapper-4">Mata Pelajaran</span>
                </a>
                <a class="div-2" href="#input-nilai">
                    <img class="img-2" src="gambar/nilai_icon.png" alt="" />
                    <span class="text-wrapper-4">Input Nilai</span>
                </a>
                <a class="button-dashboard" href="#raport" aria-current="page">
                    <img class="img-2" src="gambar/raport_icon.png" alt="" />
                    <span class="text-wrapper-3">Raport</span>
                </a>
                <a class="div-2" href="#pengguna">
                    <img class="img-2" src="gambar/pengguna_icon.png" alt="" />
                    <span class="text-wrapper-4">Pengguna</span>
                </a>
                <button class="button-logout" type="button">
                    <img class="img-2" src="gambar/logout_icon.png" alt="" />
                    <span class="text-wrapper-4">Logout</span>
                </button>
            </nav>
        </aside>

        <!-- ISIAN WEB (INILAH YANG BISA DISCROLL) -->
        <section class="frame-4" id="dashboard" aria-labelledby="dashboard-title">
            <header class="frame-5">
                <div class="frame-6">
                    <span class="fluent" aria-hidden="true">
                        <img class="vector-8" src="gambar/header_icon.png" alt="" />
                    </span>
                    <div class="frame-7">
                        <p class="text-wrapper-5">Aplikasi E-Raport Cyber Olympus</p>
                        <p class="text-wrapper-6">Sistem Manajemen Raport Digital</p>
                    </div>
                </div>
                <div class="frame-8">
                    <div class="frame-9">
                        <div class="div-wrapper">
                            <p class="text-wrapper-7">Admin Sekolah</p>
                        </div>
                        <p class="text-wrapper-8">Administrator</p>
                    </div>
                    <button class="frame-10" type="button" aria-label="Buka menu akun Admin Sekolah"
                        aria-expanded="false">
                        <span class="frame-wrapper">
                            <span class="frame-11" aria-hidden="true">
                                <span class="ellipse-wrapper">
                                    <span class="ellipse"></span>
                                </span>
                                <span class="text-wrapper-9">AS</span>
                            </span>
                        </span>
                        <span class="mingcute-down-fill" aria-hidden="true">
                            <img class="vector-9" src="gambar/dropdown_icon.png" alt="" />
                        </span>
                    </button>

                    <!-- DROPDOWN PROFIL -->
                    <div class="account-dropdown" id="account-dropdown" hidden>
                        <button class="account-menu-item account-settings-toggle" type="button" aria-expanded="false"
                            aria-controls="account-settings-submenu">
                            <span>Pengaturan Akun</span>
                            <span class="account-menu-chevron" aria-hidden="true">›</span>
                        </button>

                        <div class="account-settings-submenu" id="account-settings-submenu" hidden>
                            <button class="account-submenu-item" type="button">
                                Ubah Email
                            </button>
                            <button class="account-submenu-item" type="button">
                                Ubah Password
                            </button>
                        </div>

                        <div class="account-menu-divider" aria-hidden="true"></div>

                        <button class="account-menu-item account-logout" type="button">
                            Logout
                        </button>
                    </div>
                </div>
            </header>

            <div class="line" aria-hidden="true"></div>



            <main class="print-content" id="raport">

                <!-- TOOLBAR -->

                <div class="print-toolbar">

                    <button class="back-button" id="back-button" type="button">
                        <span aria-hidden="true">
                            ←
                        </span>

                        <span>
                            Kembali
                        </span>
                    </button>


                    <button class="download-button" id="download-pdf-button" type="button">

                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3v11"></path>
                            <path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path>
                            <path d="M5 20h14"></path>
                        </svg>

                        <span>
                            Download PDF
                        </span>

                    </button>

                </div>


                <!-- =================================================
                     RAPORT
                ================================================== -->

                <div class="report-scroll-container">

                    <article class="report-paper" id="report-paper">

                        <!-- HEADER RAPORT -->

                        <header class="report-header">

                            <h2>
                                LAPORAN HASIL BELAJAR SISWA
                            </h2>

                            <h3>
                                (RAPORT)
                            </h3>

                            <p class="report-school-name">
                                Cyber Olympus
                            </p>

                            <p class="report-school-year">
                                Tahun Pelajaran 2025/2026 - Semester 1
                            </p>

                        </header>


                        <!-- IDENTITAS SISWA -->

                        <section class="report-section">

                            <h3 class="report-section-title">
                                Identitas Siswa
                            </h3>


                            <div class="identity-grid">

                                <div class="identity-column">

                                    <div class="identity-row">
                                        <span>
                                            Nama Siswa
                                        </span>

                                        <strong>
                                            Ahmad Fauzi
                                        </strong>
                                    </div>


                                    <div class="identity-row">
                                        <span>
                                            NISN
                                        </span>

                                        <strong>
                                            123456789
                                        </strong>
                                    </div>


                                    <div class="identity-row">
                                        <span>
                                            Kelas
                                        </span>

                                        <strong>
                                            6A
                                        </strong>
                                    </div>


                                    <div class="identity-row">
                                        <span>
                                            Semester
                                        </span>

                                        <strong>
                                            1 (Ganjil)
                                        </strong>
                                    </div>

                                </div>


                                <div class="identity-column">

                                    <div class="identity-row">
                                        <span>
                                            Nama Sekolah
                                        </span>

                                        <strong>
                                            Cyber Olympus
                                        </strong>
                                    </div>


                                    <div class="identity-row">
                                        <span>
                                            Alamat
                                        </span>

                                        <strong>
                                            Yogyakarta
                                        </strong>
                                    </div>


                                    <div class="identity-row">
                                        <span>
                                            Wali Kelas
                                        </span>

                                        <strong>
                                            Adam Jombang
                                        </strong>
                                    </div>


                                    <div class="identity-row">
                                        <span>
                                            Tahun Pelajaran
                                        </span>

                                        <strong>
                                            2025/2026
                                        </strong>
                                    </div>

                                </div>

                            </div>

                        </section>


                        <!-- NILAI -->

                        <section class="report-section score-section">

                            <h3 class="report-section-title">
                                Nilai Mata Pelajaran
                            </h3>


                            <div class="score-table-wrapper">

                                <table class="score-table">

                                    <thead>

                                        <tr>

                                            <th class="number-column">
                                                No
                                            </th>

                                            <th class="subject-column">
                                                Mata Pelajaran
                                            </th>

                                            <th class="score-column">
                                                Nilai
                                            </th>

                                            <th class="predicate-column">
                                                Predikat
                                            </th>

                                            <th class="description-column">
                                                Deskripsi
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        <tr>

                                            <td>
                                                1
                                            </td>

                                            <td>
                                                Pendidikan Agama Islam
                                            </td>

                                            <td>
                                                85
                                            </td>

                                            <td>
                                                B
                                            </td>

                                            <td>
                                                Baik
                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                2
                                            </td>

                                            <td>
                                                Pendidikan Kewarganegaraan
                                            </td>

                                            <td>
                                                88
                                            </td>

                                            <td>
                                                B
                                            </td>

                                            <td>
                                                Baik
                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                3
                                            </td>

                                            <td>
                                                Bahasa Indonesia
                                            </td>

                                            <td>
                                                90
                                            </td>

                                            <td>
                                                A
                                            </td>

                                            <td>
                                                Sangat Baik
                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                4
                                            </td>

                                            <td>
                                                Matematika
                                            </td>

                                            <td>
                                                82
                                            </td>

                                            <td>
                                                B
                                            </td>

                                            <td>
                                                Baik
                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                5
                                            </td>

                                            <td>
                                                Ilmu Pengetahuan Alam
                                            </td>

                                            <td>
                                                86
                                            </td>

                                            <td>
                                                B
                                            </td>

                                            <td>
                                                Baik
                                            </td>

                                        </tr>


                                        <tr>

                                            <td>
                                                6
                                            </td>

                                            <td>
                                                Seni Budaya
                                            </td>

                                            <td>
                                                84
                                            </td>

                                            <td>
                                                B
                                            </td>

                                            <td>
                                                Baik
                                            </td>

                                        </tr>


                                        <tr class="average-row">

                                            <td colspan="2">
                                                Rata-rata Nilai
                                            </td>

                                            <td>
                                                86
                                            </td>

                                            <td></td>

                                            <td></td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>


                            <!-- KETERANGAN PREDIKAT -->

                            <div class="predicate-box">

                                <p class="predicate-title">
                                    Keterangan Predikat
                                </p>


                                <div class="predicate-list">

                                    <span>
                                        A = 90 - 100 (Sangat Baik)
                                    </span>

                                    <span>
                                        B = 80 - 89 (Baik)
                                    </span>

                                    <span>
                                        C = 70 - 79 (Cukup)
                                    </span>

                                    <span>
                                        D &lt; 70 (Kurang)
                                    </span>

                                </div>

                            </div>

                        </section>


                        <!-- CATATAN WALI KELAS -->

                        <section class="report-section note-section">

                            <h3 class="report-section-title">
                                Catatan Wali Kelas
                            </h3>


                            <div class="teacher-note">
                                Saya melihat potensi bakat tersendiri pada diri Ahmad Fauzi, dan sebaiknya potensi bakat
                                tersebut lebih baik tetap dikembangkan dengan bimbingan yang tepat.
                            </div>

                        </section>


                        <!-- TANDA TANGAN -->

                        <section class="signature-section">

                            <div class="signature-column">

                                <p class="signature-title">
                                    Orang Tua/Wali
                                </p>

                                <div class="signature-space"></div>

                                <p class="signature-line signature-parent-line">
                                    (____________________)
                                </p>

                            </div>


                            <div class="signature-column">

                                <p class="signature-title">
                                    Wali Kelas
                                </p>

                                <div class="signature-space"></div>

                                <p class="signature-line signature-name">
                                    Adam Jombang
                                </p>

                            </div>


                            <div class="signature-column">

                                <p class="signature-title">
                                    Kepala Sekolah
                                </p>

                                <div class="signature-space"></div>

                                <p class="signature-line signature-name">
                                    Drs. Saiful Isnan Racil
                                </p>

                                <p class="signature-nip">
                                    NIP. 123456789
                                </p>

                            </div>

                        </section>

                    </article>

                </div>

            </main>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            /* =================================================
               MOBILE SIDEBAR
            ================================================= */

            const menuButton =
                document.querySelector(".mobile-menu-toggle");

            const sidebar =
                document.querySelector(".dashboard .frame");

            const navigation =
                document.querySelector("#mobile-navigation");


            function closeMobileMenu() {

                if (!menuButton || !sidebar) {
                    return;
                }

                sidebar.classList.remove(
                    "mobile-menu-open"
                );

                menuButton.setAttribute(
                    "aria-expanded",
                    "false"
                );

                menuButton.setAttribute(
                    "aria-label",
                    "Buka menu navigasi"
                );
            }


            if (
                menuButton &&
                sidebar &&
                navigation
            ) {

                menuButton.addEventListener(
                    "click",
                    function () {

                        const isOpen =
                            sidebar.classList.toggle(
                                "mobile-menu-open"
                            );

                        menuButton.setAttribute(
                            "aria-expanded",
                            String(isOpen)
                        );

                        menuButton.setAttribute(
                            "aria-label",
                            isOpen ?
                            "Tutup menu navigasi" :
                            "Buka menu navigasi"
                        );

                    }
                );


                navigation
                    .querySelectorAll("a")
                    .forEach(function (link) {

                        link.addEventListener(
                            "click",
                            function () {

                                if (
                                    window.innerWidth <= 768
                                ) {
                                    closeMobileMenu();
                                }

                            }
                        );

                    });

            }


            /* =================================================
               ACCOUNT DROPDOWN
            ================================================= */

            const accountButton =
                document.querySelector(".frame-10");

            const accountDropdown =
                document.querySelector(
                    "#account-dropdown"
                );

            const settingsToggle =
                document.querySelector(
                    ".account-settings-toggle"
                );

            const settingsSubmenu =
                document.querySelector(
                    "#account-settings-submenu"
                );


            function closeAccountDropdown() {

                if (
                    !accountButton ||
                    !accountDropdown
                ) {
                    return;
                }

                accountDropdown.hidden = true;

                accountButton.setAttribute(
                    "aria-expanded",
                    "false"
                );


                if (
                    settingsToggle &&
                    settingsSubmenu
                ) {

                    settingsToggle.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                    settingsSubmenu.hidden = true;

                    settingsToggle.classList.remove(
                        "is-open"
                    );

                }

            }


            function openAccountDropdown() {

                if (
                    !accountButton ||
                    !accountDropdown
                ) {
                    return;
                }

                accountDropdown.hidden = false;

                accountButton.setAttribute(
                    "aria-expanded",
                    "true"
                );

            }


            if (
                accountButton &&
                accountDropdown
            ) {

                accountButton.addEventListener(
                    "click",
                    function (event) {

                        event.stopPropagation();

                        if (
                            accountDropdown.hidden
                        ) {

                            openAccountDropdown();

                        } else {

                            closeAccountDropdown();

                        }

                    }
                );

            }


            /* =================================================
               SUBMENU PENGATURAN AKUN
            ================================================= */

            if (
                settingsToggle &&
                settingsSubmenu
            ) {

                settingsToggle.addEventListener(
                    "click",
                    function (event) {

                        event.stopPropagation();

                        const isOpen = !settingsSubmenu.hidden;

                        settingsSubmenu.hidden =
                            isOpen;

                        settingsToggle.setAttribute(
                            "aria-expanded",
                            String(!isOpen)
                        );

                        settingsToggle.classList.toggle(
                            "is-open",
                            !isOpen
                        );

                    }
                );


                settingsSubmenu
                    .querySelectorAll("button")
                    .forEach(function (button) {

                        button.addEventListener(
                            "click",
                            function (event) {
                                event.stopPropagation();
                            }
                        );

                    });

            }


            /* =================================================
               KLIK DI LUAR DROPDOWN
            ================================================= */

            document.addEventListener(
                "click",
                function (event) {

                    if (
                        accountDropdown &&
                        !accountDropdown.hidden &&
                        !accountDropdown.contains(
                            event.target
                        ) &&
                        !accountButton.contains(
                            event.target
                        )
                    ) {

                        closeAccountDropdown();

                    }

                }
            );


            /* =================================================
               ESCAPE
            ================================================= */

            document.addEventListener(
                "keydown",
                function (event) {

                    if (
                        event.key === "Escape"
                    ) {

                        closeAccountDropdown();

                    }

                }
            );


            /* =================================================
               RESET SIDEBAR SAAT RESIZE
            ================================================= */

            window.addEventListener(
                "resize",
                function () {

                    if (
                        window.innerWidth > 768
                    ) {

                        closeMobileMenu();

                    }

                }
            );


            /* =================================================
               KEMBALI
            ================================================= */

            const backButton =
                document.querySelector(
                    "#back-button"
                );


            if (backButton) {

                backButton.addEventListener(
                    "click",
                    function () {

                        if (
                            window.history.length > 1
                        ) {

                            window.history.back();

                        } else {

                            window.location.href =
                                "{{ route('raport') }}";

                        }

                    }
                );

            }


            /* =================================================
               DOWNLOAD PDF
            ================================================= */

            const downloadButton =
                document.querySelector(
                    "#download-pdf-button"
                );


            if (downloadButton) {

                downloadButton.addEventListener(
                    "click",
                    function () {

                        window.print();

                    }
                );

            }


            /* =================================================
               LOGOUT DUMMY
            ================================================= */

            document
                .querySelectorAll(
                    ".button-logout, .account-logout"
                )
                .forEach(function (button) {

                    button.addEventListener(
                        "click",
                        function () {

                            alert(
                                "Logout masih menggunakan data dummy."
                            );

                        }
                    );

                });

        });
    </script>

</body>


</body>

</html>