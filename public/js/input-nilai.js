document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    const form = document.getElementById("grade-form");
    const kelasSelect = document.getElementById("kelas-select");
    const tahunSelect = document.getElementById("tahun-ajaran-select");
    const semesterSelect = document.getElementById("semester-select");
    const mapelSelect = document.getElementById("mapel-select");
    const tableBody = document.getElementById("nilai-table-body");
    const toast = document.getElementById("nilai-toast");
    const toastClose = toast ? toast.querySelector(".nilai-toast-close") : null;
    const saveButton = document.getElementById("save-grade-button");

    let toastTimeout;

    const config = window.inputNilaiConfig || {};
    const dataUrl = config.dataUrl || "/input-nilai/data";
    const storeUrl = config.storeUrl || "/input-nilai";
    const csrfToken = config.csrfToken || "";
    const readOnly = Boolean(config.readOnly);
    const isAdmin = Boolean(config.isAdmin);

    function hideToast() {
        if (!toast) return;
        toast.classList.remove("is-visible");
        window.setTimeout(function () {
            toast.hidden = true;
        }, 180);
    }

    function showToast() {
        if (!toast) return;
        window.clearTimeout(toastTimeout);
        toast.hidden = false;
        window.requestAnimationFrame(function () {
            toast.classList.add("is-visible");
        });
        toastTimeout = window.setTimeout(hideToast, 4200);
    }

    if (toastClose) {
        toastClose.addEventListener("click", hideToast);
    }

    function getPredicate(score) {
        if (score >= 90) return "A";
        if (score >= 80) return "B";
        if (score >= 70) return "C";
        return "D";
    }

    function calculateFinalScore(tugas, uts, uas) {
        const nilaiTugas = Number(tugas) || 0;
        const nilaiUts = Number(uts) || 0;
        const nilaiUas = Number(uas) || 0;

        return Math.round(
            (nilaiTugas * 0.3 + nilaiUts * 0.3 + nilaiUas * 0.4) * 100,
        ) / 100;
    }

    function updatePredicateClass(badge, predicate) {
        if (!badge) return;
        badge.classList.remove(
            "predikat-a",
            "predikat-b",
            "predikat-c",
            "predikat-d",
        );
        badge.classList.add("predikat-" + predicate.toLowerCase());
    }

    function normalizeInput(input) {
        if (!input) return 0;

        let value = Number(input.value);

        if (!Number.isFinite(value)) {
            input.value = "0";
            return 0;
        }

        value = Math.min(100, Math.max(0, value));
        value = Math.round(value * 100) / 100;
        input.value = value;

        return value;
    }

    function updateRow(row) {
        if (!row) return;

        const tugasInput = row.querySelector(".nilai-input-tugas");
        const utsInput = row.querySelector(".nilai-input-uts");
        const uasInput = row.querySelector(".nilai-input-uas");
        const output = row.querySelector(".nilai-akhir");
        const badge = row.querySelector(".predikat-badge");
        const badgeText = badge ? badge.querySelector("span") : null;

        const tugas = normalizeInput(tugasInput);
        const uts = normalizeInput(utsInput);
        const uas = normalizeInput(uasInput);
        const score = calculateFinalScore(tugas, uts, uas);
        const predicate = getPredicate(score);

        if (output) {
            output.textContent = score.toFixed(2);
        }

        if (badgeText) {
            badgeText.textContent = predicate;
        }

        updatePredicateClass(badge, predicate);
    }

    function updateAllRows() {
        if (!tableBody) return;
        tableBody.querySelectorAll(".nilai-row").forEach(function (row) {
            updateRow(row);
        });
    }

    function showEmptyState(message) {
        if (!tableBody) return;
        tableBody.innerHTML = "";

        const emptyState = document.createElement("div");
        emptyState.className = "nilai-empty-state";

        const text = document.createElement("span");
        text.textContent = message;

        emptyState.appendChild(text);
        tableBody.appendChild(emptyState);
    }

    function showLoadingState() {
        showEmptyState("Memuat data siswa...");
    }

    function createInputWrapper(type, studentName, value) {
        const wrapper = document.createElement("div");
        wrapper.className = "nilai-input-wrapper";

        const input = document.createElement("input");
        input.className = "nilai-input nilai-input-" + type;
        input.setAttribute(
            "aria-label",
            "Nilai " + type.toUpperCase() + " " + (studentName || ""),
        );
        input.setAttribute("inputmode", "decimal");
        input.setAttribute("max", "100");
        input.setAttribute("min", "0");
        input.setAttribute("step", "0.01");
        input.setAttribute("type", "number");
        input.value = value ?? 0;

        if (readOnly) {
            input.disabled = true;
            input.readOnly = true;
            input.setAttribute("aria-readonly", "true");
        }

        wrapper.appendChild(input);
        return wrapper;
    }

    function renderStudents(students) {
        if (!tableBody) return;

        if (!Array.isArray(students)) {
            showEmptyState("Data siswa tidak tersedia.");
            return;
        }

        if (students.length === 0) {
            showEmptyState("Belum ada siswa pada kelas yang dipilih.");
            return;
        }

        tableBody.innerHTML = "";

        students.forEach(function (student, index) {
            const row = document.createElement("div");
            row.className = "nilai-row";
            row.dataset.studentId = student.id;

            const no = document.createElement("span");
            no.className = "nilai-no";
            no.setAttribute("role", "rowheader");
            no.textContent = index + 1;

            const studentName = document.createElement("span");
            studentName.className = "nilai-student-name";
            studentName.setAttribute("role", "gridcell");
            studentName.textContent = student.nama_siswa || "";

            const tugasWrapper = createInputWrapper(
                "tugas",
                student.nama_siswa,
                student.nilai_tugas,
            );
            const utsWrapper = createInputWrapper(
                "uts",
                student.nama_siswa,
                student.nilai_uts,
            );
            const uasWrapper = createInputWrapper(
                "uas",
                student.nama_siswa,
                student.nilai_uas,
            );

            const output = document.createElement("output");
            output.className = "nilai-akhir";
            output.setAttribute(
                "aria-label",
                "Nilai akhir " + (student.nama_siswa || ""),
            );
            output.textContent = Number(student.nilai_akhir ?? 0).toFixed(2);

            const predicate = String(student.predikat || "D").toUpperCase();
            const badge = document.createElement("span");
            badge.className =
                "predikat-badge predikat-" + predicate.toLowerCase();

            const badgeText = document.createElement("span");
            badgeText.textContent = predicate;
            badge.appendChild(badgeText);

            row.appendChild(no);
            row.appendChild(studentName);
            row.appendChild(tugasWrapper);
            row.appendChild(utsWrapper);
            row.appendChild(uasWrapper);
            row.appendChild(output);
            row.appendChild(badge);

            tableBody.appendChild(row);
            updateRow(row);
        });
    }

    function hasCompleteFilter() {
        if (isAdmin) {
            return Boolean(
                kelasSelect &&
                    kelasSelect.value &&
                    tahunSelect &&
                    tahunSelect.value &&
                    semesterSelect &&
                    semesterSelect.value &&
                    mapelSelect &&
                    mapelSelect.value,
            );
        }

        return Boolean(
            semesterSelect &&
                semesterSelect.value &&
                mapelSelect &&
                mapelSelect.value,
        );
    }

    function getFilterMessage() {
        if (isAdmin) {
            return "Pilih kelas, tahun ajaran, mata pelajaran, dan semester untuk menampilkan nilai.";
        }

        return "Pilih mata pelajaran untuk menampilkan data siswa.";
    }

    function buildDataParams() {
        const params = new URLSearchParams();

        if (semesterSelect && semesterSelect.value) {
            params.append("semester", semesterSelect.value);
        }

        if (mapelSelect && mapelSelect.value) {
            params.append("mapel_id", mapelSelect.value);
        }

        if (isAdmin) {
            if (kelasSelect && kelasSelect.value) {
                params.append("kelas_id", kelasSelect.value);
            }

            if (tahunSelect && tahunSelect.value) {
                params.append("tahun_ajaran", tahunSelect.value);
            }
        }

        return params;
    }

    async function loadData() {
        if (!hasCompleteFilter()) {
            showEmptyState(getFilterMessage());
            return;
        }

        showLoadingState();

        const params = buildDataParams();

        try {
            const response = await fetch(dataUrl + "?" + params.toString(), {
                method: "GET",
                headers: { Accept: "application/json" },
            });

            let result;

            try {
                result = await response.json();
            } catch (jsonError) {
                throw new Error(
                    "Server tidak mengembalikan data JSON yang valid.",
                );
            }

            if (!response.ok) {
                throw new Error(
                    result.message || "Gagal mengambil data nilai siswa.",
                );
            }

            if (!result.success) {
                throw new Error(result.message || "Data nilai siswa tidak tersedia.");
            }

            const siswa =
                result.data && Array.isArray(result.data.siswa)
                    ? result.data.siswa
                    : [];

            renderStudents(siswa);
        } catch (error) {
            showEmptyState(
                error.message || "Terjadi kesalahan saat mengambil data nilai siswa.",
            );
            console.error("Input Nilai - Load Data:", error);
        }
    }

    function prepareAdminClassFilter() {
        if (!isAdmin || !kelasSelect || !tahunSelect) return;

        const allClassOptions = Array.from(kelasSelect.options)
            .filter(function (option) {
                return option.value !== "";
            })
            .map(function (option) {
                return {
                    value: option.value,
                    text: option.textContent.trim(),
                    year: option.dataset.tahunAjaran || "",
                };
            });

        function filterClassesByYear() {
            const selectedYear = tahunSelect.value;
            const currentClass = kelasSelect.value;

            while (kelasSelect.options.length > 1) {
                kelasSelect.remove(1);
            }

            allClassOptions.forEach(function (item) {
                if (!selectedYear || item.year === selectedYear) {
                    const option = document.createElement("option");
                    option.value = item.value;
                    option.textContent = item.text;
                    option.dataset.tahunAjaran = item.year;
                    kelasSelect.appendChild(option);
                }
            });

            const currentStillExists = Array.from(kelasSelect.options).some(
                function (option) {
                    return option.value === currentClass;
                },
            );

            kelasSelect.value = currentStillExists ? currentClass : "";
        }

        function syncYearFromClass() {
            const selected = kelasSelect.options[kelasSelect.selectedIndex];

            if (selected && selected.dataset.tahunAjaran) {
                const year = selected.dataset.tahunAjaran;
                const yearExists = Array.from(tahunSelect.options).some(
                    function (option) {
                        return option.value === year;
                    },
                );

                if (yearExists) {
                    tahunSelect.value = year;
                }
            }
        }

        kelasSelect.addEventListener("change", function () {
            syncYearFromClass();
            loadData();
        });

        tahunSelect.addEventListener("change", function () {
            filterClassesByYear();
            loadData();
        });
    }

    if (semesterSelect) {
        semesterSelect.addEventListener("change", loadData);
    }

    if (mapelSelect) {
        mapelSelect.addEventListener("change", loadData);
    }

    if (tableBody) {
        tableBody.addEventListener("input", function (event) {
            const input = event.target;
            if (!input.classList.contains("nilai-input") || readOnly) return;
            updateRow(input.closest(".nilai-row"));
        });

        tableBody.addEventListener("change", function (event) {
            const input = event.target;
            if (!input.classList.contains("nilai-input") || readOnly) return;
            updateRow(input.closest(".nilai-row"));
        });
    }

    if (form) {
        form.addEventListener("submit", async function (event) {
            event.preventDefault();

            if (readOnly) {
                alert("Akun ini hanya dapat melihat nilai dan tidak dapat mengubah atau menyimpan nilai.");
                return;
            }

            if (!semesterSelect || !semesterSelect.value) {
                alert("Silakan pilih semester terlebih dahulu.");
                return;
            }

            if (!mapelSelect || !mapelSelect.value) {
                alert("Silakan pilih mata pelajaran terlebih dahulu.");
                return;
            }

            if (!tableBody) return;

            const rows = tableBody.querySelectorAll(".nilai-row");

            if (rows.length === 0) {
                alert("Tidak ada siswa yang dapat disimpan.");
                return;
            }

            updateAllRows();

            const nilai = [];

            rows.forEach(function (row) {
                const siswaId = row.dataset.studentId;
                const tugasInput = row.querySelector(".nilai-input-tugas");
                const utsInput = row.querySelector(".nilai-input-uts");
                const uasInput = row.querySelector(".nilai-input-uas");

                if (!siswaId) return;

                nilai.push({
                    siswa_id: Number(siswaId),
                    nilai_tugas: Number(tugasInput?.value) || 0,
                    nilai_uts: Number(utsInput?.value) || 0,
                    nilai_uas: Number(uasInput?.value) || 0,
                });
            });

            if (nilai.length === 0) {
                alert("Tidak ada data nilai siswa yang valid.");
                return;
            }

            const payload = {
                semester: semesterSelect.value,
                mapel_id: Number(mapelSelect.value),
                nilai: nilai,
            };

            const buttonTextElement = saveButton
                ? saveButton.querySelector("span")
                : null;
            const originalText = buttonTextElement
                ? buttonTextElement.textContent
                : "Simpan Nilai";

            if (saveButton) saveButton.disabled = true;
            if (buttonTextElement) buttonTextElement.textContent = "Menyimpan...";

            try {
                const response = await fetch(storeUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify(payload),
                });

                let result;

                try {
                    result = await response.json();
                } catch (jsonError) {
                    throw new Error(
                        "Server tidak mengembalikan respons JSON yang valid.",
                    );
                }

                if (!response.ok) {
                    throw new Error(result.message || "Gagal menyimpan nilai.");
                }

                if (!result.success) {
                    throw new Error(result.message || "Nilai gagal disimpan.");
                }

                showToast();
                await loadData();
            } catch (error) {
                alert(
                    error.message || "Terjadi kesalahan saat menyimpan nilai.",
                );
                console.error("Simpan nilai:", error);
            } finally {
                if (saveButton) saveButton.disabled = false;
                if (buttonTextElement) {
                    buttonTextElement.textContent = originalText;
                }
            }
        });
    }

    prepareAdminClassFilter();
    showEmptyState(getFilterMessage());
});
