document.addEventListener("DOMContentLoaded", function () {
    const filterForm = document.getElementById("rekap-mapel-filter-form");
    const mapelSelect = document.getElementById("rekap-mapel-mapel");
    const semesterSelect = document.getElementById("rekap-mapel-semester");
    const academicYearSelect = document.getElementById("rekap-mapel-tahun-ajaran");
    const pdfButton = document.getElementById("rekap-mapel-pdf-button");

    function getFilterData() {
        return {
            mapel: mapelSelect?.value || "",
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

        window.location.href = url.toString();
    }

    academicYearSelect?.addEventListener("change", reloadWithFilter);
    semesterSelect?.addEventListener("change", reloadWithFilter);

    filterForm?.addEventListener("submit", function (event) {
        event.preventDefault();

        const data = getFilterData();

        if (!data.mapel) {
            showMessage("Silakan pilih mata pelajaran terlebih dahulu.");
            mapelSelect?.focus();
            return;
        }

        if (!data.tahunAjaran) {
            showMessage("Silakan pilih tahun ajaran terlebih dahulu.");
            academicYearSelect?.focus();
            return;
        }

        showMessage(
            "Fungsi preview rekap nilai mapel akan dikembangkan pada tahap berikutnya.",
            "info"
        );
    });

    pdfButton?.addEventListener("click", function () {
        showMessage(
            "Fungsi cetak PDF rekap nilai mapel akan dikembangkan pada tahap berikutnya.",
            "info"
        );
    });
});
