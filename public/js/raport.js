document.addEventListener("DOMContentLoaded", function () {
    const filterForm = document.getElementById("raport-filter-form");
    const studentSelect = document.getElementById("raport-siswa");
    const semesterSelect = document.getElementById("raport-semester");
    const academicYearSelect = document.getElementById("raport-tahun-ajaran");
    const pdfButton = document.getElementById("raport-pdf-button");
    const searchInput = document.getElementById("raport-search-input");
    const tableBody = document.getElementById("raport-table-body");

    function getFilterData() {
        return {
            siswa: studentSelect ? studentSelect.value : "",
            semester: semesterSelect ? semesterSelect.value : "1",
            tahunAjaran: academicYearSelect ? academicYearSelect.value : "",
        };
    }

    function showMessage(message, type) {
        if (typeof showAppToast === "function") {
            showAppToast(message, type || "error");
            return;
        }

        window.alert(message);
    }

    /* =====================================================
       FILTER TAHUN AJARAN
       ===================================================== */

    if (academicYearSelect && semesterSelect) {
        academicYearSelect.addEventListener("change", function () {
            const tahunAjaran = academicYearSelect.value;
            const semester = semesterSelect.value || "1";

            if (!tahunAjaran) {
                return;
            }

            const url = new URL(window.location.href);
            url.searchParams.set("tahun_ajaran", tahunAjaran);
            url.searchParams.set("semester", semester);
            window.location.href = url.toString();
        });
    }

    /* =====================================================
       PREVIEW RAPORT DARI FILTER
       ===================================================== */

    if (filterForm) {
        filterForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const data = getFilterData();

            if (!data.siswa) {
                showMessage("Silakan pilih siswa terlebih dahulu.");
                if (studentSelect) {
                    studentSelect.focus();
                }
                return;
            }

            const url = new URL(
                "/raport/" + encodeURIComponent(data.siswa) + "/preview",
                window.location.origin,
            );

            url.searchParams.set("semester", data.semester);
            url.searchParams.set("tahun_ajaran", data.tahunAjaran);

            window.location.href = url.toString();
        });
    }

    /* =====================================================
       PENCARIAN SISWA
       ===================================================== */

    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const keyword = searchInput.value.trim().toLowerCase();
            const rows = tableBody.querySelectorAll(".raport-table-row");

            rows.forEach(function (row) {
                const searchableText = (
                    row.dataset.student || row.textContent || ""
                ).toLowerCase();

                row.style.display =
                    keyword === "" || searchableText.includes(keyword)
                        ? ""
                        : "none";
            });
        });
    }

    /* =====================================================
       PREVIEW PER BARIS
       ===================================================== */

    document
        .querySelectorAll(".raport-action-preview")
        .forEach(function (button) {
            button.addEventListener("click", function () {
                const studentId = button.dataset.studentId;
                const data = getFilterData();

                if (!studentId) {
                    showMessage("Data siswa tidak ditemukan.");
                    return;
                }

                const url = new URL(
                    "/raport/" + encodeURIComponent(studentId) + "/preview",
                    window.location.origin,
                );

                url.searchParams.set("semester", data.semester);
                url.searchParams.set("tahun_ajaran", data.tahunAjaran);

                window.location.href = url.toString();
            });
        });

    /* =====================================================
       CETAK PDF
       ===================================================== */

    if (pdfButton) {
        pdfButton.addEventListener("click", function () {
            const data = getFilterData();

            if (!data.siswa) {
                showMessage("Silakan pilih siswa terlebih dahulu.");
                if (studentSelect) {
                    studentSelect.focus();
                }
                return;
            }

            const url = new URL(
                "/raport/" + encodeURIComponent(data.siswa) + "/preview",
                window.location.origin,
            );

            url.searchParams.set("semester", data.semester);
            url.searchParams.set("tahun_ajaran", data.tahunAjaran);
            url.searchParams.set("print", "1");

            window.open(url.toString(), "_blank");
        });
    }
});
