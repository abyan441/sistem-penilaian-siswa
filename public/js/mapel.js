document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       KONFIGURASI
       ===================================================== */

    const config = window.mapelConfig || {};

    const storeUrl = config.storeUrl || "/mata-pelajaran";

    const updateUrl = config.updateUrl || "/mata-pelajaran";

    const csrfToken = config.csrfToken || "";

    /* =====================================================
       ELEMENT
       ===================================================== */

    const tableBody = document.querySelector(".mapel-table-body");

    const totalMapel = document.querySelector("#total-mapel");

    const averageKkm = document.querySelector("#average-kkm");

    const lowestKkm = document.querySelector("#lowest-kkm");

    const addButton = document.querySelector("#mapel-add-button");

    /* =====================================================
       HELPER
       ===================================================== */

    function getRows() {
        if (!tableBody) {
            return [];
        }

        return Array.from(
            tableBody.querySelectorAll(".mapel-table-row[data-mapel-id]"),
        );
    }

    function badgeClass(kkm) {
        return Number(kkm) <= 70 ? "kkm-low" : "kkm-high";
    }

    function refreshRowNumbers() {
        getRows().forEach(function (row, index) {
            const no = row.querySelector(".cell-no");

            if (no) {
                no.textContent = String(index + 1);
            }
        });
    }

    function updateStats() {
        const rows = getRows();

        const values = rows
            .map(function (row) {
                return Number(row.dataset.kkm);
            })
            .filter(function (value) {
                return Number.isFinite(value);
            });

        if (totalMapel) {
            totalMapel.textContent = String(values.length);
        }

        if (averageKkm) {
            const average = values.length
                ? Math.round(
                      values.reduce(function (sum, value) {
                          return sum + value;
                      }, 0) / values.length,
                  )
                : 0;

            averageKkm.textContent = String(average);
        }

        if (lowestKkm) {
            lowestKkm.textContent = String(
                values.length ? Math.min.apply(null, values) : 0,
            );
        }
    }

    function updateRowContent(row) {
        if (!row) {
            return;
        }

        const kodeCell = row.querySelector(".cell-kode");

        const namaCell = row.querySelector(".cell-nama");

        const badge = row.querySelector(".kkm-badge");

        const editButton = row.querySelector(".mapel-edit-btn");

        const deleteButton = row.querySelector(".mapel-delete-btn");

        if (kodeCell) {
            kodeCell.textContent = row.dataset.kode || "";
        }

        if (namaCell) {
            namaCell.textContent = row.dataset.nama || "";
        }

        if (badge) {
            badge.textContent = row.dataset.kkm || "0";

            badge.className = "kkm-badge " + badgeClass(row.dataset.kkm || 0);
        }

        if (editButton) {
            editButton.setAttribute(
                "aria-label",
                "Edit " + (row.dataset.nama || "mata pelajaran"),
            );
        }

        if (deleteButton) {
            deleteButton.setAttribute(
                "aria-label",
                "Hapus " + (row.dataset.nama || "mata pelajaran"),
            );
        }
    }

    function createMapelRow(data) {
        const row = document.createElement("div");

        row.className = "mapel-table-row";

        row.setAttribute("role", "row");

        row.dataset.mapelId = String(data.id);

        row.dataset.kode = data.kode_mapel || "";

        row.dataset.nama = data.nama_pelajaran || "";

        row.dataset.kkm = String(data.kkm ?? 0);

        row.innerHTML = `
            <div
                class="cell-no"
                role="cell"
            ></div>

            <div
                class="cell-kode"
                role="cell"
            ></div>

            <div
                class="cell-nama"
                role="cell"
            ></div>

            <div
                class="cell-kkm"
                role="cell"
            >
                <span class="kkm-badge"></span>
            </div>

            <div
                class="mapel-actions"
                role="cell"
            >

                <button
                    class="mapel-edit-btn"
                    type="button"
                    title="Edit"
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
                        ></path>

                        <path
                            d="M14.5 7.5l2 2"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        ></path>

                    </svg>

                </button>


                <button
                    class="mapel-delete-btn"
                    type="button"
                    title="Hapus"
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
                        ></path>

                    </svg>

                </button>

            </div>
        `;

        updateRowContent(row);

        return row;
    }

    /* =====================================================
       MODAL TAMBAH
       ===================================================== */

    const mapelModal = document.querySelector("#mapel-modal");

    const mapelForm = document.querySelector("#mapel-form");

    const mapelModalClose = document.querySelector("#mapel-modal-close");

    const mapelFormCancel = document.querySelector("#mapel-form-cancel");

    const mapelKodeInput = document.querySelector("#mapel-kode");

    const mapelNamaInput = document.querySelector("#mapel-nama");

    const mapelKkmInput = document.querySelector("#mapel-kkm");

    function openMapelModal() {
        if (!mapelModal) {
            return;
        }

        mapelModal.hidden = false;

        document.body.classList.add("mapel-modal-open");

        setTimeout(function () {
            if (mapelKodeInput) {
                mapelKodeInput.focus();
            }
        }, 50);
    }

    function closeMapelModal() {
        if (!mapelModal) {
            return;
        }

        mapelModal.hidden = true;

        document.body.classList.remove("mapel-modal-open");

        if (mapelForm) {
            mapelForm.reset();
        }

        if (mapelKkmInput) {
            mapelKkmInput.value = "75";
        }
    }

    if (addButton) {
        addButton.addEventListener("click", openMapelModal);
    }

    if (mapelModalClose) {
        mapelModalClose.addEventListener("click", closeMapelModal);
    }

    if (mapelFormCancel) {
        mapelFormCancel.addEventListener("click", closeMapelModal);
    }

    if (mapelModal) {
        mapelModal.addEventListener("click", function (event) {
            if (event.target.matches("[data-mapel-modal-close]")) {
                closeMapelModal();
            }
        });
    }

    /* =====================================================
       TAMBAH DATA
       ===================================================== */

    if (mapelForm) {
        mapelForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const kode = mapelKodeInput.value.trim().toUpperCase();

            const nama = mapelNamaInput.value.trim();

            const kkm = Number(mapelKkmInput.value);

            if (!kode) {
                showAppToast("Kode mata pelajaran wajib diisi.");

                mapelKodeInput.focus();

                return;
            }

            if (kode.length > 5) {
                showAppToast("Kode mata pelajaran maksimal 5 karakter.");

                mapelKodeInput.focus();

                return;
            }

            if (!nama) {
                showAppToast("Nama mata pelajaran wajib diisi.");

                mapelNamaInput.focus();

                return;
            }

            if (nama.length > 45) {
                showAppToast("Nama mata pelajaran maksimal 45 karakter.");

                mapelNamaInput.focus();

                return;
            }

            if (!Number.isFinite(kkm) || kkm < 0 || kkm > 100) {
                showAppToast("KKM harus berupa angka 0-100.");

                mapelKkmInput.focus();

                return;
            }

            try {
                const response = await fetch(storeUrl, {
                    method: "POST",

                    headers: {
                        "Content-Type": "application/json",

                        Accept: "application/json",

                        "X-CSRF-TOKEN": csrfToken,

                        "X-Requested-With": "XMLHttpRequest",
                    },

                    body: JSON.stringify({
                        kode_mapel: kode,
                        nama_pelajaran: nama,
                        kkm: kkm,
                    }),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(getErrorMessage(result));
                }

                const emptyRow = tableBody.querySelector(".mapel-empty-row");

                if (emptyRow) {
                    emptyRow.remove();
                }

                const row = createMapelRow(result.data);

                tableBody.appendChild(row);

                refreshRowNumbers();

                updateStats();

                closeMapelModal();

                showAppToast(
                    result.message || "Mata pelajaran berhasil ditambahkan.",
                    "success",
                );
            } catch (error) {
                showAppToast(
                    error.message || "Gagal menambahkan mata pelajaran.",
                );
            }
        });
    }

    /* =====================================================
       MODAL EDIT
       ===================================================== */

    const editModal = document.querySelector("#mapel-edit-modal");

    const editForm = document.querySelector("#mapel-edit-form");

    const editClose = document.querySelector("#mapel-edit-close");

    const editCancel = document.querySelector("#mapel-edit-cancel");

    const editKode = document.querySelector("#mapel-edit-kode");

    const editNama = document.querySelector("#mapel-edit-nama");

    const editKkm = document.querySelector("#mapel-edit-kkm");

    let editingRow = null;

    function openEditModal(row) {
        editingRow = row;

        editKode.value = row.dataset.kode || "";

        editNama.value = row.dataset.nama || "";

        editKkm.value = row.dataset.kkm || "75";

        editModal.hidden = false;

        document.body.classList.add("mapel-modal-open");

        setTimeout(function () {
            editKode.focus();
        }, 50);
    }

    function closeEditModal() {
        editModal.hidden = true;

        editingRow = null;

        editForm.reset();

        document.body.classList.remove("mapel-modal-open");
    }

    if (editClose) {
        editClose.addEventListener("click", closeEditModal);
    }

    if (editCancel) {
        editCancel.addEventListener("click", closeEditModal);
    }

    /* =====================================================
       UPDATE DATA
       ===================================================== */

    if (editForm) {
        editForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            if (!editingRow) {
                return;
            }

            const id = editingRow.dataset.mapelId;

            const kode = editKode.value.trim().toUpperCase();

            const nama = editNama.value.trim();

            const kkm = Number(editKkm.value);

            if (!kode) {
                showAppToast("Kode mata pelajaran wajib diisi.");

                editKode.focus();

                return;
            }

            if (kode.length > 5) {
                showAppToast("Kode mata pelajaran maksimal 5 karakter.");

                editKode.focus();

                return;
            }

            if (!nama) {
                showAppToast("Nama mata pelajaran wajib diisi.");

                editNama.focus();

                return;
            }

            if (nama.length > 45) {
                showAppToast("Nama mata pelajaran maksimal 45 karakter.");

                editNama.focus();

                return;
            }

            if (!Number.isFinite(kkm) || kkm < 0 || kkm > 100) {
                showAppToast("KKM harus berupa angka 0-100.");

                editKkm.focus();

                return;
            }

            try {
                const response = await fetch(
                    updateUrl + "/" + encodeURIComponent(id),
                    {
                        method: "PUT",

                        headers: {
                            "Content-Type": "application/json",

                            Accept: "application/json",

                            "X-CSRF-TOKEN": csrfToken,

                            "X-Requested-With": "XMLHttpRequest",
                        },

                        body: JSON.stringify({
                            kode_mapel: kode,
                            nama_pelajaran: nama,
                            kkm: kkm,
                        }),
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(getErrorMessage(result));
                }

                editingRow.dataset.kode = result.data.kode_mapel;

                editingRow.dataset.nama = result.data.nama_pelajaran;

                editingRow.dataset.kkm = String(result.data.kkm);

                updateRowContent(editingRow);

                updateStats();

                closeEditModal();

                showAppToast(
                    result.message || "Mata pelajaran berhasil diperbarui.",
                    "success",
                );
            } catch (error) {
                showAppToast(
                    error.message || "Gagal memperbarui mata pelajaran.",
                );
            }
        });
    }

    /* =====================================================
       MODAL HAPUS
       ===================================================== */

    const deleteModal = document.querySelector("#mapel-delete-modal");

    const deleteCancel = document.querySelector("#mapel-delete-cancel");

    const deleteConfirm = document.querySelector("#mapel-delete-confirm");

    const deleteClose = document.querySelector("#mapel-delete-close");

    const deleteName = document.querySelector("#mapel-delete-name");

    let deletingRow = null;

    function openDeleteModal(row) {
        deletingRow = row;

        const nama = row.dataset.nama || "mata pelajaran";

        deleteName.textContent = '"' + nama + '"';

        deleteModal.hidden = false;

        document.body.classList.add("mapel-modal-open");
    }

    function closeDeleteModal() {
        deleteModal.hidden = true;

        deletingRow = null;

        document.body.classList.remove("mapel-modal-open");
    }

    if (deleteClose) {
        deleteClose.addEventListener("click", closeDeleteModal);
    }

    if (deleteCancel) {
        deleteCancel.addEventListener("click", closeDeleteModal);
    }

    /* =====================================================
       KONFIRMASI HAPUS
       ===================================================== */

    if (deleteConfirm) {
        deleteConfirm.addEventListener("click", async function () {
            if (!deletingRow) {
                return;
            }

            const id = deletingRow.dataset.mapelId;

            try {
                const response = await fetch(
                    updateUrl + "/" + encodeURIComponent(id),
                    {
                        method: "DELETE",

                        headers: {
                            Accept: "application/json",

                            "X-CSRF-TOKEN": csrfToken,

                            "X-Requested-With": "XMLHttpRequest",
                        },
                    },
                );

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(getErrorMessage(result));
                }

                deletingRow.remove();

                refreshRowNumbers();

                updateStats();

                closeDeleteModal();

                showAppToast(
                    result.message || "Mata pelajaran berhasil dihapus.",
                    "success",
                );
            } catch (error) {
                showAppToast(
                    error.message || "Gagal menghapus mata pelajaran.",
                );
            }
        });
    }

    /* =====================================================
       EVENT TABEL
       ===================================================== */

    if (tableBody) {
        tableBody.addEventListener("click", function (event) {
            const target = event.target;

            if (!(target instanceof Element)) {
                return;
            }

            const editButton = target.closest(".mapel-edit-btn");

            const deleteButton = target.closest(".mapel-delete-btn");

            if (editButton) {
                const row = editButton.closest(".mapel-table-row");

                if (row) {
                    openEditModal(row);
                }

                return;
            }

            if (deleteButton) {
                const row = deleteButton.closest(".mapel-table-row");

                if (row) {
                    openDeleteModal(row);
                }
            }
        });
    }

    /* =====================================================
       OVERLAY
       ===================================================== */

    [editModal, deleteModal].forEach(function (modal) {
        if (!modal) {
            return;
        }

        modal.addEventListener("click", function (event) {
            if (event.target.matches("[data-mapel-modal-close]")) {
                if (modal === editModal) {
                    closeEditModal();
                } else {
                    closeDeleteModal();
                }
            }
        });
    });

    /* =====================================================
       ESC
       ===================================================== */

    document.addEventListener("keydown", function (event) {
        if (event.key !== "Escape") {
            return;
        }

        if (editModal && !editModal.hidden) {
            closeEditModal();
        }

        if (deleteModal && !deleteModal.hidden) {
            closeDeleteModal();
        }

        if (mapelModal && !mapelModal.hidden) {
            closeMapelModal();
        }
    });

    /* =====================================================
       ERROR HANDLER
       ===================================================== */

    function getErrorMessage(result) {
        if (result && result.message) {
            return result.message;
        }

        if (result && result.errors) {
            const firstError = Object.values(result.errors)[0];

            if (Array.isArray(firstError) && firstError.length) {
                return firstError[0];
            }
        }

        return "Terjadi kesalahan.";
    }

    /* =====================================================
       INISIALISASI
       ===================================================== */

    refreshRowNumbers();

    updateStats();
});
