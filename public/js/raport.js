document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       ELEMENT HALAMAN
       ===================================================== */

    const filterForm = document.getElementById("raport-filter-form");

    const studentSelect = document.getElementById("raport-siswa");

    const semesterSelect = document.getElementById("raport-semester");

    const academicYearSelect = document.getElementById("raport-tahun-ajaran");

    const previewButton = document.getElementById("raport-preview-button");

    const pdfButton = document.getElementById("raport-pdf-button");

    const searchInput = document.getElementById("raport-search-input");

    const tableBody = document.getElementById("raport-table-body");

    /* =====================================================
       DATA SISWA
       ===================================================== */

    const studentRows = tableBody
        ? Array.from(tableBody.querySelectorAll(".raport-table-row"))
        : [];

    /* =====================================================
       PENCARIAN SISWA
       ===================================================== */

    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const keyword = searchInput.value.trim().toLowerCase();

            studentRows.forEach(function (row) {
                const searchableText = (
                    row.dataset.student ||
                    row.textContent ||
                    ""
                ).toLowerCase();

                const isMatch =
                    keyword === "" || searchableText.includes(keyword);

                row.style.display = isMatch ? "" : "none";
            });
        });
    }

    /* =====================================================
       AMBIL DATA FILTER
       ===================================================== */

    function getFilterData() {
        return {
            siswa: studentSelect ? studentSelect.value : "",

            semester: semesterSelect ? semesterSelect.value : "1",

            tahunAjaran: academicYearSelect
                ? academicYearSelect.value
                : "2025-2026",
        };
    }

    /* =====================================================
       PREVIEW RAPORT
       ===================================================== */

    if (filterForm) {
        filterForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const data = getFilterData();

            if (!data.siswa) {
                showAppToast("Silakan pilih siswa terlebih dahulu.");

                if (studentSelect) {
                    studentSelect.focus();
                }

                return;
            }

            const selectedOption =
                studentSelect.options[studentSelect.selectedIndex];

            const studentName = selectedOption
                ? selectedOption.textContent.trim()
                : "Siswa";

            /*
             * Pada tahap convert HTML ke Laravel,
             * halaman raport detail belum diberikan.
             *
             * Untuk sementara tampilkan informasi
             * pilihan yang digunakan.
             */

            window.location.href =
                "/raport/" + encodeURIComponent(data.siswa) + "/preview";
        });
    }

    /* =====================================================
       CETAK PDF
       ===================================================== */

    if (pdfButton) {
        pdfButton.addEventListener("click", function () {
            const data = getFilterData();

            if (!data.siswa) {
                showAppToast("Silakan pilih siswa terlebih dahulu.");

                if (studentSelect) {
                    studentSelect.focus();
                }

                return;
            }

            const selectedOption =
                studentSelect.options[studentSelect.selectedIndex];

            const studentName = selectedOption
                ? selectedOption.textContent.trim()
                : "Siswa";

            /*
             * Belum mengirim request ke backend
             * karena route PDF belum tersedia
             * pada HTML sumber.
             */

            showAppToast(
                "Cetak PDF Raport\n\n" +
                    "Siswa: " +
                    studentName +
                    "\n" +
                    "Semester: Semester " +
                    data.semester +
                    "\n" +
                    "Tahun Ajaran: " +
                    data.tahunAjaran,
            );
        });
    }

    /* =====================================================
       PREVIEW RAPORT PER BARIS
       ===================================================== */

    document
        .querySelectorAll(".raport-action-preview")
        .forEach(function (button) {
            button.addEventListener("click", function () {
                const studentId = button.dataset.studentId;

                if (studentId) {
                    window.location.href =
                        "/raport/" + encodeURIComponent(studentId) + "/preview";
                }
            });
        });

    /* =====================================================
       DOWNLOAD RAPORT PER BARIS
       ===================================================== */

    document
        .querySelectorAll(".raport-action-download")
        .forEach(function (button) {
            button.addEventListener("click", function () {
                const studentName = button.dataset.studentName || "Siswa";

                showAppToast(
                    "Raport " + studentName + " siap diunduh.",
                    "success",
                );
            });
        });
});
