document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       ELEMENT DATA GURU
       ===================================================== */

    const guruAddButton = document.querySelector(".guru-add-button");

    const guruModal = document.querySelector("#guru-modal");

    const guruModalTitle = document.querySelector("#guru-modal-title");

    const guruModalDesc = document.querySelector("#guru-modal-desc");

    const guruModalClose = document.querySelector("#guru-modal-close");

    const guruFormCancel = document.querySelector("#guru-form-cancel");

    const guruFormSubmitBtn = document.querySelector("#guru-form-submit-btn");

    const guruForm = document.querySelector("#guru-form");

    const guruTableBody = document.querySelector(".guru-table-body");

    const nipInput = document.querySelector("#guru-nip");

    const nameSelect = document.querySelector("#guru-name");

    const mapelSelect = document.querySelector("#guru-mapel");

    let activeEditingRow = null;

    /* =====================================================
       MODAL TAMBAH / EDIT
       ===================================================== */

    function openGuruModal(isEdit = false) {
        if (!guruModal) {
            return;
        }

        if (isEdit) {
            guruModalTitle.textContent = "Edit Data Guru";

            guruModalDesc.textContent = "Ubah rincian data guru di bawah ini.";

            guruFormSubmitBtn.textContent = "Simpan Perubahan";
        } else {
            guruModalTitle.textContent = "Tambah Data Guru";

            guruModalDesc.textContent =
                "Lengkapi data guru yang akan ditambahkan.";

            guruFormSubmitBtn.textContent = "Simpan Data Guru";

            guruForm.reset();

            activeEditingRow = null;
        }

        guruModal.hidden = false;

        document.body.classList.add("guru-modal-open");
    }

    function closeGuruModal() {
        if (!guruModal) {
            return;
        }

        guruModal.hidden = true;

        document.body.classList.remove("guru-modal-open");

        if (guruForm) {
            guruForm.reset();
        }

        activeEditingRow = null;
    }

    if (guruAddButton) {
        guruAddButton.addEventListener("click", function () {
            openGuruModal(false);
        });
    }

    if (guruModalClose) {
        guruModalClose.addEventListener("click", closeGuruModal);
    }

    if (guruFormCancel) {
        guruFormCancel.addEventListener("click", closeGuruModal);
    }

    if (guruModal) {
        guruModal
            .querySelectorAll("[data-guru-modal-close]")
            .forEach(function (element) {
                element.addEventListener("click", closeGuruModal);
            });
    }

    /* =====================================================
       MODAL HAPUS
       ===================================================== */

    const deleteModal = document.querySelector("#delete-modal");

    const deleteCancelBtn = document.querySelector("#delete-form-cancel");

    const deleteConfirmBtn = document.querySelector("#delete-form-confirm-btn");

    const deleteTeacherNameText = document.querySelector(
        "#delete-teacher-name",
    );

    let activeDeletingRow = null;

    function openDeleteModal(row, teacherName) {
        activeDeletingRow = row;

        if (deleteTeacherNameText) {
            deleteTeacherNameText.textContent = teacherName;
        }

        if (deleteModal) {
            deleteModal.hidden = false;

            document.body.classList.add("guru-modal-open");
        }
    }

    function closeDeleteModal() {
        if (!deleteModal) {
            return;
        }

        deleteModal.hidden = true;

        document.body.classList.remove("guru-modal-open");

        activeDeletingRow = null;
    }

    if (deleteCancelBtn) {
        deleteCancelBtn.addEventListener("click", closeDeleteModal);
    }

    if (deleteModal) {
        deleteModal
            .querySelectorAll("[data-delete-modal-close]")
            .forEach(function (element) {
                element.addEventListener("click", closeDeleteModal);
            });
    }

    if (deleteConfirmBtn) {
        deleteConfirmBtn.addEventListener("click", function () {
            if (activeDeletingRow) {
                const teacherName =
                    activeDeletingRow
                        .querySelector(".cell-nama")
                        ?.textContent.trim() || "Guru";

                activeDeletingRow.remove();

                updateRowNumbers();

                closeDeleteModal();

                showAppToast(
                    "Data " + teacherName + " berhasil dihapus.",
                    "success",
                );
            }
        });
    }

    /* =====================================================
       EDIT & DELETE TABEL
       ===================================================== */

    if (guruTableBody) {
        guruTableBody.addEventListener("click", function (event) {
            const editBtn = event.target.closest(".edit-btn");

            const deleteBtn = event.target.closest(".delete-btn");

            /* EDIT */

            if (editBtn) {
                const row = editBtn.closest(".guru-table-row");

                if (!row) {
                    return;
                }

                activeEditingRow = row;

                const currentNip = row.querySelector(".cell-nip")
                    ? row.querySelector(".cell-nip").textContent.trim()
                    : "";

                const currentName = row.querySelector(".cell-nama")
                    ? row.querySelector(".cell-nama").textContent.trim()
                    : "";

                const currentMapel = row.querySelector(".cell-mapel")
                    ? row.querySelector(".cell-mapel").textContent.trim()
                    : "";

                nipInput.value = currentNip;

                nameSelect.value = currentName;

                mapelSelect.value = currentMapel;

                openGuruModal(true);
            }

            /* DELETE */

            if (deleteBtn) {
                const row = deleteBtn.closest(".guru-table-row");

                if (!row) {
                    return;
                }

                const currentName = row.querySelector(".cell-nama")
                    ? row.querySelector(".cell-nama").textContent.trim()
                    : "guru ini";

                openDeleteModal(row, currentName);
            }
        });
    }

    /* =====================================================
       NOMOR TABEL
       ===================================================== */

    function updateRowNumbers() {
        if (!guruTableBody) {
            return;
        }

        const rows = guruTableBody.querySelectorAll(".guru-table-row");

        rows.forEach(function (row, index) {
            const cellNo = row.querySelector(".cell-no");

            if (cellNo) {
                cellNo.textContent = index + 1;
            }
        });
    }

    /* =====================================================
       SUBMIT TAMBAH / EDIT
       ===================================================== */

    if (guruForm) {
        guruForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const nip = nipInput.value.trim();

            const name = nameSelect.value;

            const mapel = mapelSelect.value;

            if (!nip || !name || !mapel) {
                showAppToast("NIP, nama guru, dan mata pelajaran wajib diisi.");
                return;
            }

            const wasEditing = Boolean(activeEditingRow);

            /* EDIT */

            if (activeEditingRow) {
                activeEditingRow.querySelector(".cell-nip").textContent = nip;

                activeEditingRow.querySelector(".cell-nama").textContent = name;

                activeEditingRow.querySelector(".cell-mapel").textContent =
                    mapel;

                const editBtn = activeEditingRow.querySelector(".edit-btn");

                const deleteBtn = activeEditingRow.querySelector(".delete-btn");

                if (editBtn) {
                    editBtn.setAttribute("aria-label", "Edit " + name);
                }

                if (deleteBtn) {
                    deleteBtn.setAttribute("aria-label", "Hapus " + name);
                }
            } else {
                /* TAMBAH */
                const currentRows =
                    guruTableBody.querySelectorAll(".guru-table-row").length;

                const row = document.createElement("div");

                row.className = "guru-table-row";

                row.setAttribute("role", "row");

                row.innerHTML = `
                        <div
                            role="cell"
                            class="cell-no"
                        >
                            ${currentRows + 1}
                        </div>

                        <div
                            role="cell"
                            class="cell-nip"
                        >
                            ${escapeHtml(nip)}
                        </div>

                        <div
                            role="cell"
                            class="cell-nama"
                        >
                            ${escapeHtml(name)}
                        </div>

                        <div
                            role="cell"
                            class="cell-mapel"
                        >
                            ${escapeHtml(mapel)}
                        </div>

                        <div
                            class="guru-actions"
                            role="cell"
                        >

                            <button
                                type="button"
                                aria-label="Edit ${escapeHtml(name)}"
                                class="edit-btn"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linejoin="round"
                                    />

                                    <path
                                        d="M14.5 7.5l2 2"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    />

                                </svg>

                            </button>


                            <button
                                type="button"
                                aria-label="Hapus ${escapeHtml(name)}"
                                class="delete-btn"
                            >

                                <svg
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >

                                    <path
                                        d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />

                                </svg>

                            </button>

                        </div>
                    `;

                guruTableBody.appendChild(row);
            }

            closeGuruModal();

            showAppToast(
                wasEditing
                    ? "Data guru berhasil diperbarui."
                    : "Data guru berhasil ditambahkan.",
                "success",
            );
        });
    }

    /* =====================================================
       SEARCH
       ===================================================== */

    const searchForm = document.querySelector("#guru-search-form");

    const searchInput = document.querySelector("#teacher-search");

    if (searchForm && searchInput && guruTableBody) {
        searchForm.addEventListener("submit", function (event) {
            event.preventDefault();
        });

        searchInput.addEventListener("input", function () {
            const keyword = searchInput.value.trim().toLowerCase();

            const rows = guruTableBody.querySelectorAll(".guru-table-row");

            rows.forEach(function (row) {
                const rowText = row.textContent.toLowerCase();

                row.style.display = rowText.includes(keyword) ? "" : "none";
            });
        });
    }

    /* =====================================================
       ESCAPE
       ===================================================== */

    document.addEventListener("keydown", function (event) {
        if (event.key !== "Escape") {
            return;
        }

        if (guruModal && !guruModal.hidden) {
            closeGuruModal();
        }

        if (deleteModal && !deleteModal.hidden) {
            closeDeleteModal();
        }
    });

    /* =====================================================
       ESCAPE HTML
       ===================================================== */

    function escapeHtml(value) {
        const div = document.createElement("div");

        div.textContent = value;

        return div.innerHTML;
    }
});
