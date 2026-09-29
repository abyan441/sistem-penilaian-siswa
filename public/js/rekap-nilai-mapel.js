document.addEventListener("DOMContentLoaded", function () {
    const filterForm = document.getElementById("rekap-mapel-filter-form");
    const studentSelect = document.getElementById("rekap-mapel-siswa");
    const semesterSelect = document.getElementById("rekap-mapel-semester");
    const academicYearSelect = document.getElementById("rekap-mapel-tahun-ajaran");
    const pdfButton = document.getElementById("rekap-mapel-pdf-button");
    const searchInput = document.getElementById("rekap-mapel-search-input");
    const tableBody = document.getElementById("rekap-mapel-table-body");

    function getFilterData() {
        return {
            siswa: studentSelect?.value || "",
            semester: semesterSelect?.value || "1",
            tahunAjaran: academicYearSelect?.value || "",
        };
    }

    function showMessage(message, type = "error") {
        if (typeof showAppToast === "function") {
            showAppToast(message, type);
            return;
        }
        window.alert(message);
    }

    function reloadWithFilter() {
        const data = getFilterData();

        if (!data.tahunAjaran) {
            showMessage("Tahun ajaran belum tersedia. Silakan tambahkan data kelas terlebih dahulu.");
            return;
        }

        const url = new URL(window.location.href);
        url.searchParams.set("tahun_ajaran", data.tahunAjaran);
        url.searchParams.set("semester", data.semester);

        if (data.siswa) {
            url.searchParams.set("siswa", data.siswa);
        } else {
            url.searchParams.delete("siswa");
        }

        window.location.href = url.toString();
    }

    academicYearSelect?.addEventListener("change", reloadWithFilter);
    semesterSelect?.addEventListener("change", reloadWithFilter);

    if (filterForm) {
        filterForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const data = getFilterData();

            if (!data.siswa) {
                showMessage("Silakan pilih siswa terlebih dahulu.");
                studentSelect?.focus();
                return;
            }

            if (!data.tahunAjaran) {
                showMessage("Silakan pilih tahun ajaran terlebih dahulu.");
                academicYearSelect?.focus();
                return;
            }

            showMessage("Fungsi preview rekap nilai mapel akan dikembangkan pada tahap berikutnya.", "info");
        });
    }

    pdfButton?.addEventListener("click", function () {
        showMessage("Fungsi cetak PDF rekap nilai mapel akan dikembangkan pada tahap berikutnya.", "info");
    });

    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const keyword = searchInput.value.trim().toLowerCase();
            tableBody.querySelectorAll(".rekap-mapel-table-row:not(.rekap-mapel-empty-row)")
                .forEach(function (row) {
                    const searchableText = (row.dataset.student || row.textContent || "").toLowerCase();
                    row.style.display = keyword === "" || searchableText.includes(keyword) ? "" : "none";
                });
        });
    }

    document.querySelectorAll(".rekap-mapel-action-preview, .rekap-mapel-action-download")
        .forEach(function (button) {
            button.addEventListener("click", function () {
                showMessage("Fungsi aksi rekap nilai mapel akan dikembangkan pada tahap berikutnya.", "info");
            });
        });
});
