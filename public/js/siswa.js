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
       CSRF TOKEN
       ===================================================== */

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    /* =====================================================
       TOAST
       ===================================================== */

    function showToast(message, type = "success") {
        if (typeof showAppToast === "function") {
            showAppToast(message, type);
            return;
        }

        alert(message);
    }

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
       CLOSE MODAL TAMBAH / EDIT
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
       MEMBUAT ROW SISWA BARU
       ===================================================== */

    function createStudentRow(data) {
        const row = document.createElement("div");

        row.className = "siswa-table-row";

        row.setAttribute("role", "row");

        /*
         * ID DATABASE SISWA
         *
         * Harus menggunakan data-siswa-id
         * agar sama dengan row yang berasal dari Blade.
         */
        row.dataset.siswaId = data.id;

        row.dataset.kelasId = data.kelas_id;

        row.innerHTML = `
            <div
                class="cell-no"
                role="cell"
            >
                -
            </div>

            <div
                class="cell-nisn"
                role="cell"
            >
                ${escapeHtml(data.nisn)}
            </div>

            <div
                class="cell-nama"
                role="cell"
            >
                ${escapeHtml(data.nama_siswa)}
            </div>

            <div
                class="cell-jk"
                role="cell"
            >
                ${escapeHtml(data.jenis_kelamin)}
            </div>

            <div
                class="cell-kelas"
                role="cell"
            >
                ${escapeHtml(data.kelas)}
            </div>

            <div
                class="siswa-actions"
                role="cell"
            >

                <button
                    type="button"
                    class="edit-btn"
                    aria-label="Edit ${escapeHtml(data.nama_siswa)}"
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
                    aria-label="Hapus ${escapeHtml(data.nama_siswa)}"
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

        return row;
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

                /*
                 * Pastikan row mempunyai ID database.
                 */
                const siswaId = deletingRow.dataset.siswaId;

                if (!siswaId) {
                    console.error(
                        "ID siswa tidak ditemukan pada row:",
                        deletingRow,
                    );

                    showToast("ID siswa tidak ditemukan.", "error");

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
        form.addEventListener("submit", async function (event) {
            event.preventDefault();

            const nisn = nisnInput ? nisnInput.value.trim() : "";
            const name = nameInput ? nameInput.value.trim() : "";
            const jk = jkInput ? jkInput.value : "";
            const kelas = kelasInput ? kelasInput.value : "";

            if (!nisn || !name || !jk || !kelas) {
                showToast(
                    "NISN, nama, jenis kelamin, dan kelas wajib diisi.",
                    "error",
                );

                return;
            }

            const payload = {
                nisn: nisn,
                nama_siswa: name,
                jenis_kelamin: jk,
                kelas_id: kelas,
            };

            try {
                let url = "/siswa";
                let method = "POST";

                /* =================================================
                   EDIT
                   ================================================= */

                if (editingRow) {
                    /*
                     * AMBIL ID DARI data-siswa-id
                     */
                    const siswaId = editingRow.dataset.siswaId;

                    if (!siswaId) {
                        showToast("ID siswa tidak ditemukan.", "error");

                        return;
                    }

                    url = `/siswa/${siswaId}`;

                    method = "PUT";
                }

                const response = await fetch(url, {
                    method: method,

                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },

                    body: JSON.stringify(payload),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || "Terjadi kesalahan.");
                }

                /* =================================================
                   EDIT BERHASIL
                   ================================================= */

                if (editingRow) {
                    /*
                     * Pastikan ID database tetap tersimpan.
                     */
                    editingRow.dataset.siswaId = result.data.id;

                    editingRow.dataset.kelasId = result.data.kelas_id;

                    const nisnCell = editingRow.querySelector(".cell-nisn");

                    const nameCell = editingRow.querySelector(".cell-nama");

                    const jkCell = editingRow.querySelector(".cell-jk");

                    const kelasCell = editingRow.querySelector(".cell-kelas");

                    if (nisnCell) {
                        nisnCell.textContent = result.data.nisn;
                    }

                    if (nameCell) {
                        nameCell.textContent = result.data.nama_siswa;
                    }

                    if (jkCell) {
                        jkCell.textContent = result.data.jenis_kelamin;
                    }

                    if (kelasCell) {
                        kelasCell.textContent = result.data.kelas;
                    }

                    const editButton = editingRow.querySelector(".edit-btn");

                    const deleteButton =
                        editingRow.querySelector(".delete-btn");

                    if (editButton) {
                        editButton.setAttribute(
                            "aria-label",
                            "Edit " + result.data.nama_siswa,
                        );
                    }

                    if (deleteButton) {
                        deleteButton.setAttribute(
                            "aria-label",
                            "Hapus " + result.data.nama_siswa,
                        );
                    }

                    closeStudentModal();

                    showToast(result.message, "success");

                    return;
                }

                /* =================================================
                   TAMBAH BERHASIL
                   ================================================= */

                if (!tableBody) {
                    return;
                }

                /*
                 * Jika sebelumnya tabel kosong,
                 * hapus row kosong.
                 */
                const emptyRow = tableBody.querySelector(".siswa-empty-row");

                if (emptyRow) {
                    emptyRow.remove();
                }

                const newRow = createStudentRow(result.data);

                tableBody.appendChild(newRow);

                updateNumbers();

                closeStudentModal();

                showToast(result.message, "success");
            } catch (error) {
                console.error("Siswa error:", error);

                showToast(
                    error.message ||
                        "Terjadi kesalahan saat menyimpan data siswa.",
                    "error",
                );
            }
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

    /* =====================================================
       DELETE DATABASE
       ===================================================== */

    if (deleteConfirmButton) {
        deleteConfirmButton.addEventListener("click", async function () {
            if (!deletingRow) {
                return;
            }

            /*
             * AMBIL ID DATABASE DARI data-siswa-id
             *
             * Blade:
             * data-siswa-id="{{ $item->id }}"
             *
             * JavaScript:
             * dataset.siswaId
             */
            const siswaId = deletingRow.dataset.siswaId;

            if (!siswaId) {
                console.error(
                    "ID siswa tidak ditemukan pada row:",
                    deletingRow,
                );

                showToast("ID siswa tidak ditemukan.", "error");

                return;
            }

            try {
                deleteConfirmButton.disabled = true;

                const response = await fetch(`/siswa/${siswaId}`, {
                    method: "DELETE",

                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(
                        result.message || "Gagal menghapus data siswa.",
                    );
                }

                /*
                 * Hapus row dari tabel hanya
                 * setelah database berhasil dihapus.
                 */
                deletingRow.remove();

                updateNumbers();

                closeDeleteModal();

                showToast(result.message, "success");
            } catch (error) {
                console.error("Delete siswa error:", error);

                showToast(
                    error.message || "Gagal menghapus data siswa.",
                    "error",
                );
            } finally {
                deleteConfirmButton.disabled = false;
            }
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
       ESCAPE / TUTUP MODAL DENGAN ESC
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

        div.textContent = value ?? "";

        return div.innerHTML;
    }
});
