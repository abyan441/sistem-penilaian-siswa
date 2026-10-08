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


    function initCustomDropdown(select, prefix) {
        if (!select || select.dataset.customized === "true") return;
        select.dataset.customized = "true";
        const wrapper = document.createElement("div");
        wrapper.className = prefix + "-custom-select";
        const trigger = document.createElement("button");
        trigger.type = "button";
        trigger.className = prefix + "-custom-select-trigger";
        trigger.setAttribute("aria-haspopup", "listbox");
        trigger.setAttribute("aria-expanded", "false");
        const label = document.createElement("span");
        const arrow = document.createElement("span");
        arrow.className = prefix + "-custom-select-arrow";
        trigger.append(label, arrow);
        const menu = document.createElement("div");
        menu.className = prefix + "-custom-select-menu";
        menu.setAttribute("role", "listbox");
        const sync = function() {
            const selected = select.options[select.selectedIndex];
            label.textContent = selected ? selected.textContent.trim() : "";
            menu.querySelectorAll("." + prefix + "-custom-select-option").forEach(function(option) {
                const active = option.dataset.value === select.value;
                option.classList.toggle("is-selected", active);
                option.setAttribute("aria-selected", active ? "true" : "false");
            });
        };
        Array.from(select.options).forEach(function(option) {
            const item = document.createElement("button");
            item.type = "button";
            item.className = prefix + "-custom-select-option";
            item.dataset.value = option.value;
            item.textContent = option.textContent.trim();
            item.setAttribute("role", "option");
            item.addEventListener("click", function() {
                if (select.value !== option.value) {
                    select.value = option.value;
                    select.dispatchEvent(new Event("change", { bubbles: true }));
                }
                wrapper.classList.remove("is-open");
                trigger.setAttribute("aria-expanded", "false");
                sync();
            });
            menu.appendChild(item);
        });
        select.classList.add(prefix + "-custom-select-native");
        select.parentNode.insertBefore(wrapper, select);
        wrapper.append(trigger, menu, select);
        trigger.addEventListener("click", function() {
            const open = wrapper.classList.toggle("is-open");
            trigger.setAttribute("aria-expanded", open ? "true" : "false");
        });
        select.addEventListener("change", sync);
        document.addEventListener("click", function(event) {
            if (!wrapper.contains(event.target)) {
                wrapper.classList.remove("is-open");
                trigger.setAttribute("aria-expanded", "false");
            }
        });
        sync();
    }


    initCustomDropdown(mapelSelect, "rekap-mapel");
    initCustomDropdown(semesterSelect, "rekap-mapel");
    initCustomDropdown(academicYearSelect, "rekap-mapel");

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
            </div>`;
    }

    function setEmpty(message) {
        if (!tableBody) return;
        tableBody.innerHTML = `
            <div class="rekap-mapel-table-row rekap-mapel-empty-row" role="row">
                <div role="gridcell" style="grid-column: 1 / -1; text-align: center;">
                    ${escapeHtml(message)}
                </div>
            </div>`;
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
                    <div role="gridcell"><span class="rekap-mapel-class-badge">${escapeHtml(item.kelas)}</span></div>
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
                            <button type="button" title="Unduh rekap" aria-label="Unduh rekap kelas ${escapeHtml(item.kelas)}" data-action="print" data-kelas-id="${escapeHtml(item.kelas_id)}">
                                <svg class="rekap-mapel-button-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path d="M12 3v11"></path><path d="m7.5 10.5 4.5 4.5 4.5-4.5"></path><path d="M5 20h14"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>`;
        }).join("");
    }

    function createPreviewUrl(kelasId, printMode = false) {
        const data = getFilterData();

        if (!kelasId || !data.mapel || !data.tahunAjaran || !previewEndpoint) {
            return null;
        }

        const url = new URL(
            previewEndpoint.replace(/\/0\/preview$/, "/" + encodeURIComponent(kelasId) + "/preview"),
            window.location.origin
        );

        url.searchParams.set("mapel_id", data.mapel);
        url.searchParams.set("semester", data.semester);
        url.searchParams.set("tahun_ajaran", data.tahunAjaran);

        if (printMode) {
            url.searchParams.set("print", "1");
        }

        return url.toString();
    }

    function validatePrintData() {
        const data = getFilterData();

        if (!data.mapel) {
            showMessage("Silakan pilih mata pelajaran terlebih dahulu.");
            mapelSelect?.focus();
            return false;
        }

        if (!data.tahunAjaran) {
            showMessage("Silakan pilih tahun ajaran terlebih dahulu.");
            academicYearSelect?.focus();
            return false;
        }

        return true;
    }

    async function loadData() {
        const data = getFilterData();

        if (!data.mapel) { setEmpty("Pilih mata pelajaran untuk menampilkan data."); return; }
        if (!data.tahunAjaran) { setEmpty("Belum ada tahun ajaran yang tersedia."); return; }
        if (!endpoint) { showMessage("Endpoint data rekap nilai mapel belum tersedia."); return; }

        setLoading();

        const params = new URLSearchParams({
            mapel_id: data.mapel,
            semester: data.semester,
            tahun_ajaran: data.tahunAjaran,
        });

        try {
            const response = await fetch(`${endpoint}?${params.toString()}`, {
                method: "GET",
                headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
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
    semesterSelect?.addEventListener("change", function () { if (mapelSelect?.value) loadData(); });
    academicYearSelect?.addEventListener("change", function () { if (mapelSelect?.value) loadData(); });

    filterForm?.addEventListener("submit", function (event) {
        event.preventDefault();
        loadData();
    });

    tableBody?.addEventListener("click", function (event) {
        const button = event.target.closest("button[data-action]");
        if (!button) return;

        const kelasId = button.dataset.kelasId;
        if (!kelasId) { showMessage("Data kelas tidak ditemukan."); return; }

        if (!validatePrintData()) return;

        const url = createPreviewUrl(kelasId, button.dataset.action === "print");

        if (!url) {
            showMessage("Data rekap tidak lengkap.");
            return;
        }

        if (button.dataset.action === "preview") {
            window.location.href = url;
            return;
        }

        window.location.href = url;
    });

    pdfButton?.addEventListener("click", function () {
        if (!validatePrintData()) return;

        const firstPrintButton = tableBody?.querySelector("button[data-action='print']");

        if (!firstPrintButton) {
            showMessage("Belum ada kelas yang dapat dicetak.");
            return;
        }

        const url = createPreviewUrl(firstPrintButton.dataset.kelasId, true);

        if (!url) {
            showMessage("Data rekap tidak lengkap.");
            return;
        }

        window.location.href = url;
    });

    previewButton?.addEventListener("click", loadData);
});