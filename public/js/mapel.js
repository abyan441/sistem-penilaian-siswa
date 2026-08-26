document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
     DATA MATA PELAJARAN
     ===================================================== */
    const tableBody = document.querySelector(".mapel-table-body");
    const totalMapel = document.querySelector("#total-mapel");
    const averageKkm = document.querySelector("#average-kkm");
    const lowestKkm = document.querySelector("#lowest-kkm");
    const addButton = document.querySelector("#mapel-add-button");

    function getRows() {
        if (!tableBody) {
            return [];
        }

        return Array.from(tableBody.querySelectorAll(".mapel-table-row"));
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

    function refreshRowNumbers() {
        getRows().forEach(function (row, index) {
            const no = row.querySelector(".cell-no");

            if (no) {
                no.textContent = String(index + 1);
            }
        });
    }

    function badgeClass(kkm) {
        return Number(kkm) <= 70 ? "kkm-low" : "kkm-high";
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

    function createMapelRow(kode, nama, kkm) {
        const row = document.createElement("div");

        row.className = "mapel-table-row";
        row.setAttribute("role", "row");

        row.dataset.mapelId = String(Date.now());
        row.dataset.kode = kode.trim().toUpperCase();
        row.dataset.nama = nama.trim();
        row.dataset.kkm = String(kkm);

        row.innerHTML = `
      <div class="cell-no" role="cell"></div>

      <div class="cell-kode" role="cell"></div>

      <div class="cell-nama" role="cell"></div>

      <div class="cell-kkm" role="cell">
        <span class="kkm-badge"></span>
      </div>

      <div class="mapel-actions" role="cell">

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
     MODAL TAMBAH MATA PELAJARAN
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
        addButton.addEventListener("click", function () {
            openMapelModal();
        });
    }

    if (mapelModalClose) {
        mapelModalClose.addEventListener("click", function () {
            closeMapelModal();
        });
    }

    if (mapelFormCancel) {
        mapelFormCancel.addEventListener("click", function () {
            closeMapelModal();
        });
    }

    if (mapelModal) {
        mapelModal.addEventListener("click", function (event) {
            if (event.target.matches("[data-mapel-modal-close]")) {
                closeMapelModal();
            }
        });
    }

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && mapelModal && !mapelModal.hidden) {
            closeMapelModal();
        }
    });

    /* =====================================================
     SUBMIT TAMBAH MATA PELAJARAN
     ===================================================== */

    if (mapelForm && tableBody) {
        mapelForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const kode = mapelKodeInput
                ? mapelKodeInput.value.trim().toUpperCase()
                : "";

            const nama = mapelNamaInput ? mapelNamaInput.value.trim() : "";

            const kkm = mapelKkmInput ? Number(mapelKkmInput.value) : NaN;

            /* VALIDASI KODE */

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

            /* VALIDASI NAMA */

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

            /* VALIDASI KKM */

            if (!Number.isFinite(kkm) || kkm < 0 || kkm > 100) {
                showAppToast("KKM harus berupa angka 0-100.");

                mapelKkmInput.focus();

                return;
            }

            /* CEK DUPLIKAT */

            const existingRows = getRows();

            const duplicateKode = existingRows.some(function (row) {
                return (row.dataset.kode || "").toUpperCase() === kode;
            });

            if (duplicateKode) {
                showAppToast("Kode mata pelajaran tersebut sudah digunakan.");

                mapelKodeInput.focus();

                return;
            }

            const duplicateNama = existingRows.some(function (row) {
                return (
                    (row.dataset.nama || "").toLowerCase() ===
                    nama.toLowerCase()
                );
            });

            if (duplicateNama) {
                showAppToast("Nama mata pelajaran tersebut sudah digunakan.");

                mapelNamaInput.focus();

                return;
            }

            /* BUAT BARIS BARU */

            const row = createMapelRow(kode, nama, kkm);

            tableBody.appendChild(row);

            refreshRowNumbers();

            updateStats();

            closeMapelModal();

            showAppToast("Mata pelajaran berhasil ditambahkan.", "success");
        });
    }

    /* =====================================================
     MODAL EDIT + KONFIRMASI HAPUS
     ===================================================== */

    const editModal = document.querySelector("#mapel-edit-modal");

    const editForm = document.querySelector("#mapel-edit-form");

    const editClose = document.querySelector("#mapel-edit-close");

    const editCancel = document.querySelector("#mapel-edit-cancel");

    const editKode = document.querySelector("#mapel-edit-kode");

    const editNama = document.querySelector("#mapel-edit-nama");

    const editKkm = document.querySelector("#mapel-edit-kkm");

    const deleteModal = document.querySelector("#mapel-delete-modal");

    const deleteCancel = document.querySelector("#mapel-delete-cancel");

    const deleteConfirm = document.querySelector("#mapel-delete-confirm");

    const deleteClose = document.querySelector("#mapel-delete-close");

    const deleteName = document.querySelector("#mapel-delete-name");

    let editingRow = null;
    let deletingRow = null;

    /* =====================================================
     EDIT
     ===================================================== */

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
    }

    /* =====================================================
     HAPUS
     ===================================================== */

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
    }

    /* =====================================================
     TOMBOL MODAL EDIT
     ===================================================== */

    if (editClose) {
        editClose.addEventListener("click", closeEditModal);
    }

    if (editCancel) {
        editCancel.addEventListener("click", closeEditModal);
    }

    /* =====================================================
     TOMBOL MODAL HAPUS
     ===================================================== */

    if (deleteClose) {
        deleteClose.addEventListener("click", closeDeleteModal);
    }

    if (deleteCancel) {
        deleteCancel.addEventListener("click", closeDeleteModal);
    }

    /* =====================================================
     KLIK OVERLAY MODAL
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
     ESC UNTUK MENUTUP MODAL
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
    });

    /* =====================================================
     SUBMIT EDIT
     ===================================================== */

    if (editForm) {
        editForm.addEventListener("submit", function (event) {
            event.preventDefault();

            if (!editingRow) {
                return;
            }

            const kode = editKode.value.trim().toUpperCase();

            const nama = editNama.value.trim();

            const kkm = Number(editKkm.value);

            /* VALIDASI KODE */

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

            /* VALIDASI NAMA */

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

            /* VALIDASI KKM */

            if (!Number.isFinite(kkm) || kkm < 0 || kkm > 100) {
                showAppToast("KKM harus berupa angka 0-100.");

                editKkm.focus();

                return;
            }

            /* CEK DUPLIKAT */

            const duplicate = getRows().some(function (row) {
                if (row === editingRow) {
                    return false;
                }

                return (
                    (row.dataset.kode || "").toUpperCase() === kode ||
                    (row.dataset.nama || "").toLowerCase() ===
                        nama.toLowerCase()
                );
            });

            if (duplicate) {
                showAppToast(
                    "Kode atau nama mata pelajaran tersebut sudah digunakan.",
                );

                return;
            }

            /* UPDATE DATA */

            editingRow.dataset.kode = kode;

            editingRow.dataset.nama = nama;

            editingRow.dataset.kkm = String(kkm);

            updateRowContent(editingRow);

            updateStats();

            closeEditModal();

            document.body.classList.remove("mapel-modal-open");

            showAppToast("Mata pelajaran berhasil diperbarui.", "success");
        });
    }

    /* =====================================================
     KONFIRMASI HAPUS
     ===================================================== */

    if (deleteConfirm) {
        deleteConfirm.addEventListener("click", function () {
            if (!deletingRow) {
                return;
            }

            deletingRow.remove();

            refreshRowNumbers();

            updateStats();

            closeDeleteModal();

            document.body.classList.remove("mapel-modal-open");

            showAppToast("Mata pelajaran berhasil dihapus.", "success");
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

            /* EDIT */

            if (editButton) {
                const row = editButton.closest(".mapel-table-row");

                if (row) {
                    openEditModal(row);
                }

                return;
            }

            /* HAPUS */

            if (deleteButton) {
                const row = deleteButton.closest(".mapel-table-row");

                if (row) {
                    openDeleteModal(row);
                }
            }
        });
    }

    /* =====================================================
     INISIALISASI
     ===================================================== */

    refreshRowNumbers();

    updateStats();
});
