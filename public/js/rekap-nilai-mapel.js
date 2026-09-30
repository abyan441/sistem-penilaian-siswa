document.addEventListener("DOMContentLoaded", function () {
    const page = document.getElementById("rekap-mapel");
    const filterForm = document.getElementById("rekap-mapel-filter-form");
    const mapelSelect = document.getElementById("rekap-mapel-mapel");
    const semesterSelect = document.getElementById("rekap-mapel-semester");
    const academicYearSelect = document.getElementById("rekap-mapel-tahun-ajaran");
    const tableBody = document.getElementById("rekap-mapel-table-body");
    const pdfButton = document.getElementById("rekap-mapel-pdf-button");
    const previewButton = document.getElementById("rekap-mapel-preview-button");

    const endpoint = page?.dataset.endpoint || "";
    const previewEndpoint = page?.dataset.previewEndpoint || "";

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

    function escapeHtml(value) {
        return String(value ?? "")
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");
    }

    function setLoading() {
        if (!tableBody) return;

        tableBody.innerHTML = `
            <div class="rekap-mapel-table-row rekap-mapel-empty-row" role="row">
                <div role="gridcell" style="grid-column: 1 / -1; text-align: center;">
                    Memuat data rekap...
                </div>
            </div>
        `;
    }

    function setEmpty(message) {
        if (!tableBody) return;

        tableBody.innerHTML = `
            <div class="rekap-mapel-table-row rekap-mapel-empty-row" role="row">
                <div role="gridcell" style="grid-column: 1 / -1; text-align: center;">
                    ${escapeHtml(message)}
                </div>
            </div>
        `;
    }

    function renderRows(rows) {
        if (!tableBody) return;

        if (!Array.isArray(rows) || rows.length === 0) {
            setEmpty("Belum ada kelas pada tahun ajaran yang dipilih.");
            return;
        }

        tableBody.innerHTML = rows.map(function (item) {
            const semesterLabel = Number(item.semester) === 2
                ? "Semester 2 (Genap)"
                : "Semester 1 (Ganjil)";

            return `
                <div class="rekap-mapel-table-row" role="row">
                    <div role="gridcell">${escapeHtml(item.nomor)}</div>
                    <div role="gridcell">${escapeHtml(item.mata_pelajaran)}</div>
                    <div role="gridcell">
                        <span class="rekap-mapel-class-badge">${escapeHtml(item.kelas)}</span>
                    </div>
                    <div role="gridcell">${escapeHtml(semesterLabel)}</div>
                    <div role="gridcell">${escapeHtml(item.tahun_ajaran)}</div>
                    <div role="gridcell">
                        <div class="rekap-mapel-actions">
                            <button type="button" title="Preview rekap" aria-label="Preview rekap kelas ${escapeHtml(item.kelas)}" data-action="preview" data-kelas-id="${escapeHtml(item.kelas_id)}">
                                <svg class="rekap-mapel-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                                    <circle cx="12" cy="12" r="2.7"></circle>
                                </svg>
                            </button>
                            <button type="button" title="Cetak rekap" aria-label="Cetak rekap kelas ${escapeHtml(item.kelas)}" data-action="print" data-kelas-id="${escapeHtml(item.kelas_id)}">
                                <svg class="rekap-mapel-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path d="M12 3v11"></path>
                                    <path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path>
                                    <path d="M5 20h14"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join("");
    }

    async function loadData() {
        const data = getFilterData();

        if (!data.mapel) {
            setEmpty("Pilih mata pelajaran untuk menampilkan data.");
            return;
        }

        if (!data.tahunAjaran) {
            setEmpty("Belum ada tahun ajaran yang tersedia.");
            return;
        }

        if (!endpoint) {
            showMessage("Endpoint data rekap nilai mapel belum tersedia.");
            return;
        }

        setLoading();

        const params = new URLSearchParams({
            mapel_id: data.mapel,
            semester: data.semester,
            tahun_ajaran: data.tahunAjaran,
        });

        try {
            const response = await fetch(`${endpoint}?${params.toString()}`, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            const result = await response.json();

            if (!response.ok || result.success !== true) {
                throw new Error(result.message || "Data rekap gagal dimuat.");
            }

            renderRows(result.data || []);
        } catch (error) {
            setEmpty(error.message || "Data rekap gagal dimuat.");
            showMessage(error.message || "Data rekap gagal dimuat.");
        }
    }

    mapelSelect?.addEventListener("change", loadData);
    semesterSelect?.addEventListener("change", function () {
        if (mapelSelect?.value) {
            loadData();
        }
    });
    academicYearSelect?.addEventListener("change", function () {
        if (mapelSelect?.value) {
            loadData();
        }
    });

    filterForm?.addEventListener("submit", function (event) {
        event.preventDefault();
        loadData();
    });

    tableBody?.addEventListener("click", function (event) {
        const button = event.target.closest("button[data-action]");

        if (!button) return;

        const action = button.dataset.action;

        if (action === "preview") {
            const data = getFilterData();
            const kelasId = button.dataset.kelasId;
            if (!kelasId) { showMessage("Data kelas tidak ditemukan."); return; }
            if (!data.mapel || !data.tahunAjaran) { showMessage("Mata pelajaran dan tahun ajaran wajib dipilih."); return; }
            if (!previewEndpoint) { showMessage("Endpoint preview rekap nilai mapel belum tersedia."); return; }
            const url = new URL(previewEndpoint.replace(/\\/0\\/preview$/, "/" + encodeURIComponent(kelasId) + "/preview"), window.location.origin);
            url.searchParams.set("mapel_id", data.mapel);
            url.searchParams.set("semester", data.semester);
            url.searchParams.set("tahun_ajaran", data.tahunAjaran);
            window.location.href = url.toString();
        }

        if (action === "print") {
            showMessage("Cetak PDF rekap per kelas akan dikembangkan pada tahap berikutnya.", "info");
        }
    });

    pdfButton?.addEventListener("click", function () {
        showMessage("Fungsi cetak PDF rekap nilai mapel akan dikembangkan pada tahap berikutnya.", "info");
    });

    previewButton?.addEventListener("click", function () {
        loadData();
    });
});
