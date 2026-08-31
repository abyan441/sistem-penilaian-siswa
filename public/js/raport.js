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

    function createPreviewUrl(studentId, printMode = false) {
        const data = getFilterData();

        const url = new URL(
            "/raport/" + encodeURIComponent(studentId) + "/preview",
            window.location.origin,
        );

        url.searchParams.set("semester", data.semester);
        url.searchParams.set("tahun_ajaran", data.tahunAjaran);

        if (printMode) {
            url.searchParams.set("print", "1");
        }

        return url.toString();
    }

    if (academicYearSelect) {
        academicYearSelect.addEventListener("change", reloadWithFilter);
    }

    if (semesterSelect) {
        semesterSelect.addEventListener("change", reloadWithFilter);
    }

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

            window.location.href = createPreviewUrl(data.siswa);
        });
    }

    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const keyword = searchInput.value.trim().toLowerCase();
            const rows = tableBody.querySelectorAll(
                ".raport-table-row:not(.raport-empty-row)",
            );

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

    document
        .querySelectorAll(".raport-action-preview")
        .forEach(function (button) {
            button.addEventListener("click", function () {
                const studentId = button.dataset.studentId;

                if (!studentId) {
                    showMessage("Data siswa tidak ditemukan.");
                    return;
                }

                window.location.href = createPreviewUrl(studentId);
            });
        });

    document
        .querySelectorAll(".raport-action-download")
        .forEach(function (button) {
            button.addEventListener("click", function () {
                const studentId = button.dataset.studentId;

                if (!studentId) {
                    showMessage("Data siswa tidak ditemukan.");
                    return;
                }

                window.open(createPreviewUrl(studentId, true), "_blank");
            });
        });

    if (pdfButton) {
        pdfButton.addEventListener("click", function () {
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

            window.open(createPreviewUrl(data.siswa, true), "_blank");
        });
    }
});
