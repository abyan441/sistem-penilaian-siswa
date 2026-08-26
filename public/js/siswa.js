document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       ELEMENT
       ===================================================== */

    const addButton = document.querySelector("#siswa-add-button");

    const modal = document.querySelector("#siswa-modal");

    const deleteModal = document.querySelector("#delete-modal");

    const form = document.querySelector("#siswa-form");

    const closeButton = document.querySelector("#siswa-modal-close");

    const cancelButton = document.querySelector("#siswa-form-cancel");

    const submitButton = document.querySelector("#siswa-form-submit-btn");

    const deleteCancelButton = document.querySelector("#delete-form-cancel");

    const deleteConfirmButton = document.querySelector(
        "#delete-form-confirm-btn",
    );

    const tableBody = document.querySelector(".siswa-table-body");

    const searchForm = document.querySelector("#siswa-search-form");

    const searchInput = document.querySelector("#student-search");

    const nisnInput = document.querySelector("#siswa-nisn");

    const nameInput = document.querySelector("#siswa-name");

    const jkInput = document.querySelector("#siswa-jk");

    const kelasInput = document.querySelector("#siswa-kelas");

    const modalTitle = document.querySelector("#siswa-modal-title");

    const modalDescription = document.querySelector("#siswa-modal-desc");

    const deleteStudentName = document.querySelector("#delete-student-name");

    let editingRow = null;

    let deletingRow = null;

    /* =====================================================
       MODAL TAMBAH / EDIT
       ===================================================== */

    function openStudentModal(edit = false) {
        if (!modal) {
            return;
        }

        if (modalTitle) {
            modalTitle.textContent = edit
                ? "Edit Data Siswa"
                : "Tambah Data Siswa";
        }

        if (modalDescription) {
            modalDescription.textContent = edit
                ? "Ubah rincian data siswa di bawah ini."
                : "Lengkapi data siswa yang akan ditambahkan.";
        }

        if (submitButton) {
            submitButton.textContent = edit
                ? "Simpan Perubahan"
                : "Simpan Data Siswa";
        }

        modal.hidden = false;

        document.body.classList.add("siswa-modal-open");
    }

    function closeStudentModal() {
        if (!modal) {
            return;
        }

        modal.hidden = true;

        document.body.classList.remove("siswa-modal-open");

        if (form) {
            form.reset();
        }

        editingRow = null;
    }

    /* =====================================================
       TAMBAH SISWA
       ===================================================== */

    if (addButton) {
        addButton.addEventListener("click", function () {
            if (form) {
                form.reset();
            }

            editingRow = null;

            openStudentModal(false);

            setTimeout(function () {
                if (nisnInput) {
                    nisnInput.focus();
                }
            }, 100);
        });
    }

    /* =====================================================
       CLOSE MODAL
       ===================================================== */

    if (closeButton) {
        closeButton.addEventListener("click", closeStudentModal);
    }

    if (cancelButton) {
        cancelButton.addEventListener("click", closeStudentModal);
    }

    if (modal) {
        modal
            .querySelectorAll("[data-siswa-modal-close]")
            .forEach(function (element) {
                element.addEventListener("click", closeStudentModal);
            });
    }

    /* =====================================================
       KELAS
       ===================================================== */

    function getSelectedClassName() {
        if (!kelasInput) {
            return "";
        }

        const selectedOption = kelasInput.options[kelasInput.selectedIndex];

        if (!selectedOption) {
            return "";
        }

        return selectedOption.textContent.split(" — ")[0].trim();
    }

    /* =====================================================
       UPDATE NOMOR TABEL
       ===================================================== */

    function updateNumbers() {
        if (!tableBody) {
            return;
        }

        tableBody
            .querySelectorAll(".siswa-table-row")
            .forEach(function (row, index) {
                const number = row.querySelector(".cell-no");

                if (number) {
                    number.textContent = index + 1;
                }
            });
    }

    /* =====================================================
       EDIT / DELETE TABLE
       ===================================================== */

    if (tableBody) {
        tableBody.addEventListener("click", function (event) {
            const editButton = event.target.closest(".edit-btn");

            const deleteButton = event.target.closest(".delete-btn");

            /* =================================================
               EDIT
               ================================================= */

            if (editButton) {
                editingRow = editButton.closest(".siswa-table-row");

                if (!editingRow) {
                    return;
                }

                const nisn = editingRow.querySelector(".cell-nisn");

                const name = editingRow.querySelector(".cell-nama");

                const jk = editingRow.querySelector(".cell-jk");

                if (nisnInput && nisn) {
                    nisnInput.value = nisn.textContent.trim();
                }

                if (nameInput && name) {
                    nameInput.value = name.textContent.trim();
                }

                if (jkInput && jk) {
                    jkInput.value = jk.textContent.trim();
                }

                if (kelasInput) {
                    kelasInput.value = editingRow.dataset.kelasId || "";
                }

                openStudentModal(true);

                return;
            }

            /* =================================================
               DELETE
               ================================================= */

            if (deleteButton) {
                deletingRow = deleteButton.closest(".siswa-table-row");

                if (!deletingRow) {
                    return;
                }

                const studentName = deletingRow.querySelector(".cell-nama");

                if (deleteStudentName && studentName) {
                    deleteStudentName.textContent =
                        studentName.textContent.trim();
                }

                if (deleteModal) {
                    deleteModal.hidden = false;

                    document.body.classList.add("siswa-modal-open");
                }
            }
        });
    }

    /* =====================================================
       SIMPAN TAMBAH / EDIT
       ===================================================== */

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const nisn = nisnInput ? nisnInput.value.trim() : "";

            const name = nameInput ? nameInput.value.trim() : "";

            const jk = jkInput ? jkInput.value : "";

            const kelas = kelasInput ? kelasInput.value : "";

            if (!nisn || !name || !jk || !kelas) {
                showAppToast(
                    "NISN, nama, jenis kelamin, dan kelas wajib diisi.",
                );

                return;
            }

            const className = getSelectedClassName();

            /* =================================================
               EDIT
               ================================================= */

            if (editingRow) {
                const nisnCell = editingRow.querySelector(".cell-nisn");

                const nameCell = editingRow.querySelector(".cell-nama");

                const jkCell = editingRow.querySelector(".cell-jk");

                const kelasCell = editingRow.querySelector(".cell-kelas");

                const editButton = editingRow.querySelector(".edit-btn");

                const deleteButton = editingRow.querySelector(".delete-btn");

                if (nisnCell) {
                    nisnCell.textContent = nisn;
                }

                if (nameCell) {
                    nameCell.textContent = name;
                }

                if (jkCell) {
                    jkCell.textContent = jk;
                }

                if (kelasCell) {
                    kelasCell.textContent = className;
                }

                editingRow.dataset.kelasId = kelas;

                if (editButton) {
                    editButton.setAttribute("aria-label", "Edit " + name);
                }

                if (deleteButton) {
                    deleteButton.setAttribute("aria-label", "Hapus " + name);
                }

                closeStudentModal();

                showAppToast("Data siswa berhasil diperbarui.", "success");

                return;
            }

            /* =================================================
               TAMBAH
               ================================================= */

            if (!tableBody) {
                return;
            }

            const row = document.createElement("div");

            row.className = "siswa-table-row";

            row.setAttribute("role", "row");

            row.dataset.kelasId = kelas;

            const number =
                tableBody.querySelectorAll(".siswa-table-row").length + 1;

            row.innerHTML = `
                <div
                    class="cell-no"
                    role="cell"
                >
                    ${escapeHtml(number)}
                </div>

                <div
                    class="cell-nisn"
                    role="cell"
                >
                    ${escapeHtml(nisn)}
                </div>

                <div
                    class="cell-nama"
                    role="cell"
                >
                    ${escapeHtml(name)}
                </div>

                <div
                    class="cell-jk"
                    role="cell"
                >
                    ${escapeHtml(jk)}
                </div>

                <div
                    class="cell-kelas"
                    role="cell"
                >
                    ${escapeHtml(className)}
                </div>

                <div
                    class="siswa-actions"
                    role="cell"
                >

                    <button
                        type="button"
                        class="edit-btn"
                        aria-label="Edit ${escapeHtml(name)}"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >

                            <path
                                d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z"
                                fill="none"
                                stroke="currentColor"
                                stroke-linejoin="round"
                                stroke-width="2"
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
                        class="delete-btn"
                        aria-label="Hapus ${escapeHtml(name)}"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >

                            <path
                                d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14"
                                fill="none"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                            />

                        </svg>

                    </button>

                </div>
            `;

            tableBody.appendChild(row);

            closeStudentModal();

            showAppToast("Data siswa berhasil ditambahkan.", "success");
        });
    }

    /* =====================================================
       DELETE MODAL
       ===================================================== */

    function closeDeleteModal() {
        if (!deleteModal) {
            return;
        }

        deleteModal.hidden = true;

        document.body.classList.remove("siswa-modal-open");

        deletingRow = null;
    }

    if (deleteCancelButton) {
        deleteCancelButton.addEventListener("click", closeDeleteModal);
    }

    if (deleteModal) {
        deleteModal
            .querySelectorAll("[data-delete-modal-close]")
            .forEach(function (element) {
                element.addEventListener("click", closeDeleteModal);
            });
    }

    if (deleteConfirmButton) {
        deleteConfirmButton.addEventListener("click", function () {
            if (deletingRow) {
                deletingRow.remove();

                updateNumbers();
            }

            closeDeleteModal();

            showAppToast("Data siswa berhasil dihapus.", "success");
        });
    }

    /* =====================================================
       SEARCH
       ===================================================== */

    if (searchForm) {
        searchForm.addEventListener("submit", function (event) {
            event.preventDefault();
        });
    }

    if (searchInput && tableBody) {
        searchInput.addEventListener("input", function () {
            const keyword = searchInput.value.trim().toLowerCase();

            const rows = tableBody.querySelectorAll(".siswa-table-row");

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();

                row.style.display = text.includes(keyword) ? "" : "none";
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

        if (modal && !modal.hidden) {
            closeStudentModal();
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
