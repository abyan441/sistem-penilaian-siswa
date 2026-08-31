document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    const form = document.getElementById("grade-form");
    const kelasSelect = document.getElementById("kelas-select");
    const tahunSelect = document.getElementById("tahun-ajaran-select");
    const semesterSelect = document.getElementById("semester-select");
    const mapelSelect = document.getElementById("mapel-select");
    const tableBody = document.getElementById("nilai-table-body");
    const toast = document.getElementById("nilai-toast");
    const toastClose = toast?.querySelector(".nilai-toast-close");
    const saveButton = document.getElementById("save-grade-button");

    const config = window.inputNilaiConfig || {};
    const dataUrl = config.dataUrl || "/input-nilai/data";
    const storeUrl = config.storeUrl || "/input-nilai";
    const csrfToken = config.csrfToken || "";
    const readOnly = Boolean(config.readOnly);
    const isAdmin = Boolean(config.isAdmin);
    let toastTimeout;

    function showMessage(message, type = "error") {
        if (typeof showAppToast === "function") {
            showAppToast(message, type);
        } else {
            window.alert(message);
        }
    }

    function hideToast() {
        if (!toast) return;
        toast.classList.remove("is-visible");
        window.setTimeout(() => { toast.hidden = true; }, 180);
    }

    function showToast() {
        if (!toast) return;
        window.clearTimeout(toastTimeout);
        toast.hidden = false;
        window.requestAnimationFrame(() => toast.classList.add("is-visible"));
        toastTimeout = window.setTimeout(hideToast, 4200);
    }

    toastClose?.addEventListener("click", hideToast);

    function predicate(score) {
        if (score >= 90) return "A";
        if (score >= 80) return "B";
        if (score >= 70) return "C";
        return "D";
    }

    function finalScore(tugas, uts, uas) {
        return Math.round((Number(tugas || 0) * 0.3 + Number(uts || 0) * 0.3 + Number(uas || 0) * 0.4) * 100) / 100;
    }

    function normalizeInput(input) {
        if (!input) return 0;
        let value = Number(input.value);
        if (!Number.isFinite(value)) value = 0;
        value = Math.round(Math.min(100, Math.max(0, value)) * 100) / 100;
        input.value = value;
        return value;
    }

    function updateRow(row) {
        if (!row) return;
        const tugas = normalizeInput(row.querySelector(".nilai-input-tugas"));
        const uts = normalizeInput(row.querySelector(".nilai-input-uts"));
        const uas = normalizeInput(row.querySelector(".nilai-input-uas"));
        const score = finalScore(tugas, uts, uas);
        const predikat = predicate(score);
        const output = row.querySelector(".nilai-akhir");
        const badge = row.querySelector(".predikat-badge");
        const badgeText = badge?.querySelector("span");

        if (output) output.textContent = score.toFixed(2);
        if (badgeText) badgeText.textContent = predikat;
        if (badge) {
            badge.classList.remove("predikat-a", "predikat-b", "predikat-c", "predikat-d");
            badge.classList.add("predikat-" + predikat.toLowerCase());
        }
    }

    function updateAllRows() {
        tableBody?.querySelectorAll(".nilai-row").forEach(updateRow);
    }

    function emptyState(message) {
        if (!tableBody) return;
        tableBody.innerHTML = `<div class="nilai-empty-state"><span>${message}</span></div>`;
    }

    function renderStudents(students) {
        if (!tableBody) return;
        if (!Array.isArray(students) || students.length === 0) {
            emptyState("Belum ada siswa pada kelas yang dipilih.");
            return;
        }

        tableBody.innerHTML = "";

        students.forEach((student, index) => {
            const row = document.createElement("div");
            row.className = "nilai-row";
            row.dataset.studentId = student.id;

            const no = document.createElement("span");
            no.className = "nilai-no";
            no.textContent = index + 1;

            const name = document.createElement("span");
            name.className = "nilai-student-name";
            name.textContent = student.nama_siswa || "";

            row.appendChild(no);
            row.appendChild(name);

            ["tugas", "uts", "uas"].forEach((type) => {
                const wrapper = document.createElement("div");
                wrapper.className = "nilai-input-wrapper";
                const input = document.createElement("input");
                input.className = `nilai-input nilai-input-${type}`;
                input.type = "number";
                input.min = "0";
                input.max = "100";
                input.step = "0.01";
                input.inputMode = "decimal";
                input.value = Number(student[`nilai_${type}`] ?? 0);
                input.setAttribute("aria-label", `Nilai ${type.toUpperCase()} ${student.nama_siswa || ""}`);
                if (readOnly) {
                    input.disabled = true;
                    input.readOnly = true;
                }
                wrapper.appendChild(input);
                row.appendChild(wrapper);
            });

            const output = document.createElement("output");
            output.className = "nilai-akhir";
            row.appendChild(output);

            const badge = document.createElement("span");
            badge.className = "predikat-badge";
            const badgeText = document.createElement("span");
            badge.appendChild(badgeText);
            row.appendChild(badge);

            tableBody.appendChild(row);
            updateRow(row);
        });
    }

    function hasCompleteFilter() {
        return Boolean(
            kelasSelect?.value &&
            semesterSelect?.value &&
            mapelSelect?.value &&
            (!isAdmin || tahunSelect?.value)
        );
    }

    function filterMessage() {
        return isAdmin
            ? "Pilih kelas, tahun ajaran, mata pelajaran, dan semester untuk menampilkan nilai."
            : "Pilih kelas, mata pelajaran, dan semester untuk menampilkan siswa.";
    }

    function buildParams() {
        const params = new URLSearchParams();
        params.set("kelas_id", kelasSelect.value);
        params.set("semester", semesterSelect.value);
        params.set("mapel_id", mapelSelect.value);
        if (isAdmin && tahunSelect?.value) params.set("tahun_ajaran", tahunSelect.value);
        return params;
    }

    async function loadData() {
        if (!hasCompleteFilter()) {
            emptyState(filterMessage());
            return;
        }

        emptyState("Memuat data siswa...");

        try {
            const response = await fetch(`${dataUrl}?${buildParams().toString()}`, {
                headers: { Accept: "application/json" },
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || "Gagal mengambil data nilai siswa.");
            }
            renderStudents(result.data?.siswa || []);
        } catch (error) {
            emptyState(error.message || "Terjadi kesalahan saat mengambil data nilai siswa.");
            console.error("Input Nilai - Load Data:", error);
        }
    }

    if (kelasSelect) {
        kelasSelect.addEventListener("change", function () {
            const selected = kelasSelect.options[kelasSelect.selectedIndex];
            if (isAdmin && tahunSelect && selected?.dataset.tahunAjaran) {
                tahunSelect.value = selected.dataset.tahunAjaran;
            }
            loadData();
        });
    }

    if (tahunSelect) {
        tahunSelect.addEventListener("change", function () {
            if (isAdmin && kelasSelect) {
                const selectedYear = tahunSelect.value;
                Array.from(kelasSelect.options).forEach((option) => {
                    if (!option.value) return;
                    option.hidden = Boolean(selectedYear && option.dataset.tahunAjaran !== selectedYear);
                });
                if (kelasSelect.value && kelasSelect.options[kelasSelect.selectedIndex]?.hidden) {
                    kelasSelect.value = "";
                }
            }
            loadData();
        });
    }

    semesterSelect?.addEventListener("change", loadData);
    mapelSelect?.addEventListener("change", loadData);

    tableBody?.addEventListener("input", (event) => {
        if (!readOnly && event.target.classList.contains("nilai-input")) {
            updateRow(event.target.closest(".nilai-row"));
        }
    });

    form?.addEventListener("submit", async function (event) {
        event.preventDefault();

        if (readOnly) {
            showMessage("Akun ini hanya dapat melihat nilai dan tidak dapat menyimpan nilai.");
            return;
        }
        if (!hasCompleteFilter()) {
            showMessage(filterMessage());
            return;
        }

        const rows = tableBody?.querySelectorAll(".nilai-row") || [];
        if (!rows.length) {
            showMessage("Tidak ada siswa yang dapat disimpan.");
            return;
        }

        updateAllRows();
        const nilai = Array.from(rows).map((row) => ({
            siswa_id: Number(row.dataset.studentId),
            nilai_tugas: Number(row.querySelector(".nilai-input-tugas")?.value) || 0,
            nilai_uts: Number(row.querySelector(".nilai-input-uts")?.value) || 0,
            nilai_uas: Number(row.querySelector(".nilai-input-uas")?.value) || 0,
        }));

        if (saveButton) saveButton.disabled = true;
        const buttonText = saveButton?.querySelector("span");
        const originalText = buttonText?.textContent || "Simpan Nilai";
        if (buttonText) buttonText.textContent = "Menyimpan...";

        try {
            const response = await fetch(storeUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    semester: semesterSelect.value,
                    mapel_id: Number(mapelSelect.value),
                    nilai,
                }),
            });

            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || "Nilai gagal disimpan.");
            }

            showToast();
            await loadData();
        } catch (error) {
            showMessage(error.message || "Terjadi kesalahan saat menyimpan nilai.");
            console.error("Input Nilai - Save Data:", error);
        } finally {
            if (saveButton) saveButton.disabled = false;
            if (buttonText) buttonText.textContent = originalText;
        }
    });

    if (isAdmin && kelasSelect && tahunSelect) {
        Array.from(kelasSelect.options).forEach((option) => {
            if (option.value && option.dataset.tahunAjaran === tahunSelect.value) {
                option.hidden = false;
            }
        });
    }
});
