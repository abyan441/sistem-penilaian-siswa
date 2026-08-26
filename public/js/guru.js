document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       ELEMENT DATA GURU
       ===================================================== */

    const guruAddButton = document.querySelector("#guru-add-button");

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
       CSRF TOKEN
       ===================================================== */

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    /* =====================================================
       HELPER REQUEST
       ===================================================== */

    async function sendRequest(url, options = {}) {
        const defaultHeaders = {
            Accept: "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest",
        };

        options.headers = {
            ...defaultHeaders,
            ...(options.headers || {}),
        };

        const response = await fetch(url, options);

        let result = {};

        try {
            result = await response.json();
        } catch (error) {
            result = {};
        }

        if (!response.ok) {
            throw new Error(result.message || "Terjadi kesalahan pada server.");
        }

        return result;
    }

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

            nipInput.value = "";

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

        if (nipInput) {
            nipInput.value = "";
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
       NIP OTOMATIS DARI GURU
       ===================================================== */

    if (nameSelect && nipInput) {
        nameSelect.addEventListener("change", function () {
            const selectedOption = nameSelect.options[nameSelect.selectedIndex];

            const nip = selectedOption?.dataset?.nip || "";

            nipInput.value = nip;
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

    /* =====================================================
       DELETE DATABASE
       ===================================================== */

    if (deleteConfirmBtn) {
        deleteConfirmBtn.addEventListener("click", async function () {
            if (!activeDeletingRow) {
                return;
            }

            const row = activeDeletingRow;

            const id = row.dataset.guruMapelId;

            const teacherName =
                row.querySelector(".cell-nama")?.textContent.trim() || "Guru";

            if (!id) {
                showAppToast("ID data guru tidak ditemukan.");

                return;
            }

            deleteConfirmBtn.disabled = true;

            try {
                await sendRequest(`/guru/${id}`, {
                    method: "DELETE",
                });

                /*
                 * Hapus baris dari tampilan
                 * setelah database berhasil.
                 */
                row.remove();

                updateRowNumbers();

                closeDeleteModal();

                showAppToast(
                    `Data ${teacherName} berhasil dihapus.`,
                    "success",
                );
            } catch (error) {
                showAppToast(error.message || "Data guru gagal dihapus.");
            } finally {
                deleteConfirmBtn.disabled = false;
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

            /* =================================================
                   EDIT
                   ================================================= */

            if (editBtn) {
                const row = editBtn.closest(".guru-table-row");

                if (!row) {
                    return;
                }

                activeEditingRow = row;

                const guruId = row.dataset.guruId || "";

                const mapelId = row.dataset.mapelId || "";

                const currentNip =
                    row.dataset.nip ||
                    row.querySelector(".cell-nip")?.textContent.trim() ||
                    "";

                nipInput.value = currentNip;

                nameSelect.value = guruId;

                mapelSelect.value = mapelId;

                openGuruModal(true);
            }

            /* =================================================
                   DELETE
                   ================================================= */

            if (deleteBtn) {
                const row = deleteBtn.closest(".guru-table-row");

                if (!row) {
                    return;
                }

                const currentName =
                    row.dataset.nama ||
                    row.querySelector(".cell-nama")?.textContent.trim() ||
                    "guru ini";

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

        const rows = guruTableBody.querySelectorAll(
            ".guru-table-row:not(.guru-table-empty)",
        );

        rows.forEach(function (row, index) {
            const cellNo = row.querySelector(".cell-no");

            if (cellNo) {
                cellNo.textContent = index + 1;
            }
        });
    }

    /* =====================================================
       MEMPERBARUI BARIS TABEL
       ===================================================== */

    function updateTableRow(row, data) {
        if (!row || !data) {
            return;
        }

        /*
         * Update data-* attributes.
         */
        row.dataset.guruMapelId = data.id;
        row.dataset.guruId = data.guru_id;
        row.dataset.mapelId = data.mapel_id;
        row.dataset.nip = data.nip || "";
        row.dataset.nama = data.nama || "";
        row.dataset.mapel = data.mapel || "";

        /*
         * Update tampilan tabel.
         */
        const nipCell = row.querySelector(".cell-nip");

        const namaCell = row.querySelector(".cell-nama");

        const mapelCell = row.querySelector(".cell-mapel");

        if (nipCell) {
            nipCell.textContent = data.nip || "-";
        }

        if (namaCell) {
            namaCell.textContent = data.nama || "-";
        }

        if (mapelCell) {
            mapelCell.textContent = data.mapel || "-";
        }

        /*
         * Update accessibility label.
         */
        const editBtn = row.querySelector(".edit-btn");

        const deleteBtn = row.querySelector(".delete-btn");

        if (editBtn) {
            editBtn.setAttribute("aria-label", `Edit ${data.nama}`);
        }

        if (deleteBtn) {
            deleteBtn.setAttribute("aria-label", `Hapus ${data.nama}`);
        }
    }

    /* =====================================================
       MEMBUAT BARIS BARU
       ===================================================== */

    function createTableRow(data) {
        const row = document.createElement("div");

        row.className = "guru-table-row";

        row.setAttribute("role", "row");

        row.dataset.guruMapelId = data.id;

        row.dataset.guruId = data.guru_id;

        row.dataset.mapelId = data.mapel_id;

        row.dataset.nip = data.nip || "";

        row.dataset.nama = data.nama || "";

        row.dataset.mapel = data.mapel || "";

        row.innerHTML = `
            <div
                role="cell"
                class="cell-no"
            >
                1
            </div>

            <div
                role="cell"
                class="cell-nip"
            >
                ${escapeHtml(data.nip || "-")}
            </div>

            <div
                role="cell"
                class="cell-nama"
            >
                ${escapeHtml(data.nama || "-")}
            </div>

            <div
                role="cell"
                class="cell-mapel"
            >
                ${escapeHtml(data.mapel || "-")}
            </div>

            <div
                class="guru-actions"
                role="cell"
            >

                <button
                    type="button"
                    aria-label="Edit ${escapeHtml(data.nama || "guru")}"
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
                    aria-label="Hapus ${escapeHtml(data.nama || "guru")}"
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

        return row;
    }

    /* =====================================================
       SUBMIT TAMBAH / EDIT
       ===================================================== */

    if (guruForm) {
        guruForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const guruId = nameSelect.value;

            const mapelId = mapelSelect.value;

            if (!guruId || !mapelId) {
                showAppToast("Nama guru dan mata pelajaran wajib dipilih.");

                return;
            }

            const wasEditing = Boolean(activeEditingRow);

            /*
             * Tentukan URL dan HTTP method.
             */
            let url = "/guru";

            let method = "POST";

            if (wasEditing && activeEditingRow) {
                const id = activeEditingRow.dataset.guruMapelId;

                url = `/guru/${id}`;

                method = "PUT";
            }

            guruFormSubmitBtn.disabled = true;

            try {
                const result = await sendRequest(url, {
                    method: method,

                    headers: {
                        "Content-Type": "application/json",
                    },

                    body: JSON.stringify({
                        guru_id: guruId,

                        mapel_id: mapelId,
                    }),
                });

                /*
                 * Pastikan server mengembalikan
                 * data yang valid.
                 */
                if (!result.success || !result.data) {
                    throw new Error(result.message || "Data gagal disimpan.");
                }

                /* =============================================
                       EDIT
                       ============================================= */

                if (wasEditing && activeEditingRow) {
                    updateTableRow(activeEditingRow, result.data);

                    showAppToast(
                        result.message || "Data guru berhasil diperbarui.",
                        "success",
                    );
                } else {

                /* =============================================
                       TAMBAH
                       ============================================= */
                    /*
                     * Jika sebelumnya tabel kosong,
                     * hapus pesan "Belum ada data guru."
                     */
                    const emptyRow =
                        guruTableBody.querySelector(".guru-table-empty");

                    if (emptyRow) {
                        emptyRow.remove();
                    }

                    const newRow = createTableRow(result.data);

                    guruTableBody.appendChild(newRow);

                    updateRowNumbers();

                    showAppToast(
                        result.message || "Data guru berhasil ditambahkan.",
                        "success",
                    );
                }

                closeGuruModal();
            } catch (error) {
                showAppToast(error.message || "Data guru gagal disimpan.");
            } finally {
                guruFormSubmitBtn.disabled = false;
            }
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

            const rows = guruTableBody.querySelectorAll(
                ".guru-table-row:not(.guru-table-empty)",
            );

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
