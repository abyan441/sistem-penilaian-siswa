document.addEventListener("DOMContentLoaded", function () {
    const filterForm = document.getElementById("raport-filter-form");
    const studentSelect = document.getElementById("raport-siswa");
    const semesterSelect = document.getElementById("raport-semester");
    const academicYearSelect = document.getElementById("raport-tahun-ajaran");
    const pdfButton = document.getElementById("raport-pdf-button");
    const searchInput = document.getElementById("raport-search-input");
    const tableBody = document.getElementById("raport-table-body");


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
\n\n    initCustomDropdown(studentSelect, "raport");\n    initCustomDropdown(semesterSelect, "raport");\n    initCustomDropdown(academicYearSelect, "raport");\n\n    function getFilterData() {
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

                window.location.href = createPreviewUrl(studentId, true);
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

            window.location.href = createPreviewUrl(data.siswa, true);
        });
    }
});
