/* =========================================================
   DATA DUMMY SISWA
   ========================================================= */

function makeStudents(total, className) {
    const names = [
        "Adit",
        "Bima",
        "Citra",
        "Daffa",
        "Eka",
        "Fajar",
        "Gilang",
        "Hana",
        "Indra",
        "Jihan",
        "Kevin",
        "Lala",
        "Maya",
        "Nanda",
        "Oki",
        "Putri",
        "Raka",
        "Salsa",
        "Tio",
        "Umar",
        "Vina",
        "Wahyu",
        "Yuni",
        "Zaki",
        "Alya",
        "Bagas",
        "Caca",
        "Dion",
        "Elsa",
        "Farhan",
    ];

    return Array.from(
        {
            length: total,
        },
        function (_, index) {
            return {
                nisn: "000000" + String(index + 1).padStart(3, "0"),

                nama:
                    names[index % names.length] +
                    " " +
                    className +
                    " " +
                    (index + 1),

                jk: index % 2 === 0 ? "L" : "P",
            };
        },
    );
}

const dummyStudents = {
    1: makeStudents(28, "1A"),
    2: makeStudents(30, "1B"),
    3: makeStudents(30, "2A"),
    4: makeStudents(27, "2B"),
    5: makeStudents(29, "2C"),
    6: [],
};

/* =========================================================
   REFERENSI ELEMENT
   ========================================================= */

const kelasAddButton = document.querySelector("#kelas-add-button");

const kelasModal = document.querySelector("#kelas-modal");

const kelasForm = document.querySelector("#kelas-form");

const kelasName = document.querySelector("#kelas-name");

const kelasYear = document.querySelector("#kelas-year");

const kelasWali = document.querySelector("#kelas-wali");

const kelasModalTitle = document.querySelector("#kelas-modal-title");

const kelasModalDesc = document.querySelector("#kelas-modal-desc");

const kelasSubmit = document.querySelector("#kelas-form-submit-btn");

const kelasDeleteButton = document.querySelector("#kelas-form-delete-btn");

/* =========================================================
   DETAIL MODAL
   ========================================================= */

const detailModal = document.querySelector("#kelas-detail-modal");

const detailNama = document.querySelector("#detail-nama-kelas");

const detailTahun = document.querySelector("#detail-tahun");

const detailWali = document.querySelector("#detail-wali");

const detailJumlah = document.querySelector("#detail-jumlah-siswa");

const detailStudentCount = document.querySelector("#detail-student-count");

const detailStudentBody = document.querySelector("#detail-student-body");

const noStudent = document.querySelector("#kelas-no-student");

/* =========================================================
   DELETE MODAL
   ========================================================= */

const deleteModal = document.querySelector("#kelas-delete-modal");

const deleteText = document.querySelector("#kelas-delete-text");

const deleteWarning = document.querySelector("#kelas-delete-warning");

const deleteConfirm = document.querySelector("#kelas-delete-confirm");

/* =========================================================
   STATE
   ========================================================= */

let editingClass = null;

let pendingDeleteClass = null;

let nextClassId = 100;

/* =========================================================
   MODAL
   ========================================================= */

function openModal(modal) {
    if (!modal) {
        return;
    }

    modal.hidden = false;

    document.body.classList.add("kelas-modal-open");
}

function closeModal(modal) {
    if (!modal) {
        return;
    }

    modal.hidden = true;

    const anyOpen =
        (kelasModal && !kelasModal.hidden) ||
        (detailModal && !detailModal.hidden) ||
        (deleteModal && !deleteModal.hidden);

    if (!anyOpen) {
        document.body.classList.remove("kelas-modal-open");
    }
}

/* =========================================================
   CLOSE FORM KELAS
   ========================================================= */

function closeKelasForm() {
    closeModal(kelasModal);

    if (kelasForm) {
        kelasForm.reset();
    }

    editingClass = null;

    setWaliOptions();
}

/* =========================================================
   NORMALIZE NAMA KELAS
   ========================================================= */

function normalizeClassName(value) {
    return String(value || "")
        .trim()
        .toUpperCase();
}

/* =========================================================
   NAMA WALI KELAS
   ========================================================= */

function selectedWaliName() {
    if (!kelasWali) {
        return "";
    }

    const option = kelasWali.options[kelasWali.selectedIndex];

    return option ? option.text : "";
}

/* =========================================================
   CEK NAMA KELAS
   ========================================================= */

function existingClassName(except) {
    const current = normalizeClassName(kelasName ? kelasName.value : "");

    return Array.from(document.querySelectorAll("[data-kelas-id]"))
        .filter(function (card) {
            return card !== except;
        })
        .some(function (card) {
            return normalizeClassName(card.dataset.kelasName || "") === current;
        });
}

/* =========================================================
   CEK WALI KELAS
   ========================================================= */

function waliAlreadyUsed(waliId, except) {
    return Array.from(document.querySelectorAll("[data-kelas-id]"))
        .filter(function (card) {
            return card !== except;
        })
        .some(function (card) {
            return card.dataset.waliId === String(waliId);
        });
}

/* =========================================================
   DISABLE WALI YANG SUDAH DIGUNAKAN
   ========================================================= */

function setWaliOptions() {
    if (!kelasWali) {
        return;
    }

    Array.from(kelasWali.options).forEach(function (option, index) {
        if (index === 0) {
            return;
        }

        option.disabled = waliAlreadyUsed(option.value, editingClass);
    });
}

/* =========================================================
   UPDATE SUMMARY
   ========================================================= */

function updateSummary() {
    const cards = Array.from(document.querySelectorAll("[data-kelas-id]"));

    const totalKelas = cards.length;

    const totalSiswa = cards.reduce(function (sum, card) {
        return sum + Number(card.dataset.siswa || 0);
    }, 0);

    const average = totalKelas ? Math.round(totalSiswa / totalKelas) : 0;

    const totalKelasEl = document.querySelector(
        "#data-kelas .k-div-3 .k-div-4:nth-child(1) .k-text-wrapper-4",
    );

    const totalSiswaEl = document.querySelector(
        "#data-kelas .k-div-3 .k-div-4:nth-child(2) .k-text-wrapper-4",
    );

    const averageEl = document.querySelector(
        "#data-kelas .k-div-3 .k-div-4:nth-child(3) .k-text-wrapper-4",
    );

    if (totalKelasEl) {
        totalKelasEl.textContent = totalKelas;
    }

    if (totalSiswaEl) {
        totalSiswaEl.textContent = totalSiswa;
    }

    if (averageEl) {
        averageEl.textContent = average;
    }
}

/* =========================================================
   BUKA FORM KELAS
   ========================================================= */

function openKelasForm(edit) {
    if (!kelasModal) {
        return;
    }

    if (kelasModalTitle) {
        kelasModalTitle.textContent = edit
            ? "Edit Data Kelas"
            : "Tambah Data Kelas";
    }

    if (kelasModalDesc) {
        kelasModalDesc.textContent = edit
            ? "Ubah nama kelas, tahun ajaran, atau wali kelas."
            : "Lengkapi data kelas sesuai struktur tabel kelas.";
    }

    if (kelasSubmit) {
        kelasSubmit.textContent = edit
            ? "Simpan Perubahan"
            : "Simpan Data Kelas";
    }

    if (kelasDeleteButton) {
        kelasDeleteButton.hidden = !edit;
    }

    setWaliOptions();

    openModal(kelasModal);

    setTimeout(function () {
        if (kelasName) {
            kelasName.focus();
        }
    }, 50);
}

/* =========================================================
   ISI FORM EDIT
   ========================================================= */

function fillEditForm(card) {
    if (!card) {
        return;
    }

    editingClass = card;

    if (kelasName) {
        kelasName.value = card.dataset.kelasName || "";
    }

    if (kelasYear) {
        kelasYear.value = card.dataset.tahun || "";
    }

    if (kelasWali) {
        const option = Array.from(kelasWali.options).find(function (item) {
            return item.text === card.dataset.wali;
        });

        kelasWali.value = option ? option.value : card.dataset.waliId || "";
    }

    openKelasForm(true);
}

/* =========================================================
   TAMBAH KELAS
   ========================================================= */

if (kelasAddButton) {
    kelasAddButton.addEventListener("click", function () {
        if (kelasForm) {
            kelasForm.reset();
        }

        editingClass = null;

        openKelasForm(false);
    });
}

/* =========================================================
   EDIT KELAS
   ========================================================= */

document.addEventListener("click", function (event) {
    const editButton = event.target.closest(".kelas-edit-btn");

    if (!editButton) {
        return;
    }

    event.preventDefault();

    const card = editButton.closest("[data-kelas-id]");

    if (card) {
        fillEditForm(card);
    }
});

/* =========================================================
   SUBMIT FORM
   ========================================================= */

if (kelasForm) {
    kelasForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const name = normalizeClassName(kelasName ? kelasName.value : "");

        const year = kelasYear ? kelasYear.value.trim() : "";

        const waliId = kelasWali ? kelasWali.value : "";

        const wali = selectedWaliName();

        /* =========================
               VALIDASI NAMA KELAS
               ========================= */

        if (!/^\d[A-Z]$/.test(name)) {
            showAppToast(
                "Nama kelas harus mengikuti format seperti 1A, 2B, atau 3C.",
            );

            if (kelasName) {
                kelasName.focus();
            }

            return;
        }

        /* =========================
               VALIDASI TAHUN
               ========================= */

        if (!/^\d{4}\/\d{4}$/.test(year)) {
            showAppToast(
                "Tahun ajaran harus menggunakan format YYYY/YYYY, contoh 2026/2027.",
            );

            if (kelasYear) {
                kelasYear.focus();
            }

            return;
        }

        /* =========================
               VALIDASI WALI
               ========================= */

        if (!waliId) {
            showAppToast("Silakan pilih wali kelas.");

            if (kelasWali) {
                kelasWali.focus();
            }

            return;
        }

        /* =========================
               CEK NAMA DUPLIKAT
               ========================= */

        if (existingClassName(editingClass)) {
            showAppToast("Nama kelas sudah digunakan.");

            if (kelasName) {
                kelasName.focus();
            }

            return;
        }

        /* =========================
               CEK WALI DUPLIKAT
               ========================= */

        if (waliAlreadyUsed(waliId, editingClass)) {
            showAppToast(
                "Guru tersebut sudah menjadi wali kelas lain. Sesuai rancangan database, satu wali hanya dapat digunakan untuk satu kelas.",
            );

            if (kelasWali) {
                kelasWali.focus();
            }

            return;
        }

        /* =========================
               EDIT
               ========================= */

        const wasEditing = Boolean(editingClass);

        if (wasEditing) {
            updateKelasCard(editingClass, name, year, wali, waliId);
        } else {
            /* =========================
               TAMBAH
               ========================= */
            const id = String(nextClassId++);

            const level = (name.match(/^\d+/) || ["1"])[0];

            let section = document.querySelector("#tingkat-" + level);

            if (!section) {
                section = document.createElement("section");

                section.className = "k-div-6";

                section.setAttribute("aria-labelledby", "tingkat-" + level);

                section.innerHTML =
                    '<h2 class="k-text-wrapper-5" id="tingkat-' +
                    level +
                    '">Tingkat ' +
                    level +
                    "</h2>" +
                    '<div class="k-div-15 kelas-generated-grid"></div>';

                const dataKelas = document.querySelector("#data-kelas");

                if (dataKelas) {
                    dataKelas.appendChild(section);
                }
            }

            const grid = section.querySelector(".k-div-7, .k-div-15");

            if (!grid) {
                return;
            }

            const card = document.createElement("article");

            card.className = "k-div-16 kelas-generated-card";

            card.dataset.kelasId = id;

            card.dataset.kelasName = name;

            card.dataset.tahun = year;

            card.dataset.waliId = waliId;

            card.dataset.wali = wali;

            card.dataset.siswa = "0";

            card.setAttribute("aria-labelledby", "kelas-generated-" + id);

            card.innerHTML =
                '<div class="k-div-9">' +
                '<div class="k-div-10">' +
                '<div class="k-div-11">' +
                '<div class="k-div-12">' +
                '<h3 class="k-text-wrapper-5" id="kelas-generated-' +
                id +
                '">' +
                "Kelas " +
                name +
                "</h3>" +
                '<p class="k-text-wrapper-2">' +
                "Tahun Ajaran " +
                year +
                "</p>" +
                "</div>" +
                '<div class="k-div-13">' +
                '<svg class="icon-svg k-img-2" viewBox="0 0 24 24" aria-hidden="true">' +
                '<circle cx="12" cy="8" r="3.5" fill="none" stroke="currentColor" stroke-width="2"></circle>' +
                '<path d="M5 21c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>' +
                "</svg>" +
                '<div class="k-div-2">' +
                '<p class="k-text-wrapper-6">Wali Kelas</p>' +
                '<p class="k-text-wrapper-2">' +
                wali +
                "</p>" +
                "</div>" +
                "</div>" +
                '<div class="k-div-13">' +
                '<svg class="icon-svg k-img-2" viewBox="0 0 24 24" aria-hidden="true">' +
                '<circle cx="9" cy="8" r="3" fill="none" stroke="currentColor" stroke-width="2"></circle>' +
                '<path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2"></path>' +
                '<path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor" stroke-width="2"></path>' +
                "</svg>" +
                '<div class="k-div-2">' +
                '<p class="k-text-wrapper-6">Jumlah Siswa</p>' +
                '<p class="k-text-wrapper-2">0 Siswa</p>' +
                "</div>" +
                "</div>" +
                "</div>" +
                '<span aria-label="Tingkat ' +
                level +
                '" class="k-div-wrapper">' +
                '<span class="k-text-wrapper-7">' +
                level +
                "</span>" +
                "</span>" +
                "</div>" +
                '<span aria-hidden="true" class="k-line"></span>' +
                "</div>" +
                '<nav aria-label="Aksi Kelas ' +
                name +
                '" class="k-div-14">' +
                '<button aria-label="Lihat detail Kelas ' +
                name +
                '" class="k-text-wrapper-8 kelas-detail-btn" type="button">' +
                "Detail" +
                "</button>" +
                '<button aria-label="Edit Kelas ' +
                name +
                '" class="k-text-wrapper-9 kelas-edit-btn" type="button">' +
                "Edit" +
                "</button>" +
                "</nav>";

            grid.appendChild(card);

            dummyStudents[id] = [];
        }

        updateSummary();

        closeKelasForm();

        showAppToast(
            wasEditing
                ? "Data kelas berhasil diperbarui."
                : "Data kelas berhasil ditambahkan.",
            "success",
        );
    });
}

/* =========================================================
   UPDATE CARD KELAS
   ========================================================= */

function updateKelasCard(card, name, year, wali, waliId) {
    if (!card) {
        return;
    }

    card.dataset.kelasName = name;

    card.dataset.tahun = year;

    card.dataset.waliId = waliId;

    card.dataset.wali = wali;

    const title = card.querySelector("h3");

    const yearText = card.querySelector(".k-div-12 .k-text-wrapper-2");

    const infoTexts = card.querySelectorAll(".k-div-13 .k-text-wrapper-2");

    if (title) {
        title.textContent = "Kelas " + name;
    }

    if (yearText) {
        yearText.textContent = "Tahun Ajaran " + year;
    }

    if (infoTexts[0]) {
        infoTexts[0].textContent = wali;
    }

    const nav = card.querySelector("nav");

    const detail = card.querySelector(".kelas-detail-btn, .k-text-wrapper-8");

    const edit = card.querySelector(".kelas-edit-btn, .k-text-wrapper-9");

    if (nav) {
        nav.setAttribute("aria-label", "Aksi Kelas " + name);
    }

    if (detail) {
        detail.setAttribute("aria-label", "Lihat detail Kelas " + name);
    }

    if (edit) {
        edit.setAttribute("aria-label", "Edit Kelas " + name);
    }
}

/* =========================================================
   DETAIL KELAS
   ========================================================= */

/*
 * Perbaikan utama:
 *
 * Tombol Detail bisa menggunakan:
 *
 * .kelas-detail-btn
 *
 * maupun class lama:
 *
 * .k-text-wrapper-8
 *
 * Jadi keduanya tetap bisa diklik.
 */

document.addEventListener("click", function (event) {
    const detailButton = event.target.closest(
        ".kelas-detail-btn, .k-text-wrapper-8",
    );

    if (!detailButton) {
        return;
    }

    event.preventDefault();

    const card = detailButton.closest("[data-kelas-id]");

    if (!card) {
        return;
    }

    const id = card.dataset.kelasId;

    const students = dummyStudents[id] || [];

    /* =========================
           INFORMASI KELAS
           ========================= */

    if (detailNama) {
        detailNama.textContent = "Kelas " + (card.dataset.kelasName || "-");
    }

    if (detailTahun) {
        detailTahun.textContent = card.dataset.tahun || "-";
    }

    if (detailWali) {
        detailWali.textContent = card.dataset.wali || "-";
    }

    if (detailJumlah) {
        detailJumlah.textContent = students.length + " Siswa";
    }

    if (detailStudentCount) {
        detailStudentCount.textContent = students.length + " siswa";
    }

    /* =========================
           TABEL SISWA
           ========================= */

    if (detailStudentBody) {
        detailStudentBody.innerHTML = "";
    }

    if (noStudent) {
        noStudent.hidden = students.length !== 0;
    }

    students.forEach(function (student, index) {
        const row = document.createElement("tr");

        row.innerHTML =
            "<td>" +
            (index + 1) +
            "</td>" +
            "<td>" +
            student.nisn +
            "</td>" +
            "<td>" +
            student.nama +
            "</td>" +
            "<td>" +
            (student.jk === "L" ? "Laki-laki" : "Perempuan") +
            "</td>";

        if (detailStudentBody) {
            detailStudentBody.appendChild(row);
        }
    });

    openModal(detailModal);
});

/* =========================================================
   DELETE
   ========================================================= */

function openDeleteConfirmation(card) {
    if (!card) {
        return;
    }

    pendingDeleteClass = card;

    const name = card.dataset.kelasName || "";

    const students = Number(card.dataset.siswa || 0);

    if (deleteText) {
        deleteText.textContent = "Anda akan menghapus Kelas " + name + ".";
    }

    if (deleteConfirm) {
        deleteConfirm.disabled = students > 0;
    }

    if (deleteWarning) {
        if (students > 0) {
            deleteWarning.textContent =
                "Tidak dapat dihapus karena masih memiliki " +
                students +
                " siswa. Ini sesuai FOREIGN KEY siswa.kelas_id dengan ON DELETE NO ACTION.";
        } else {
            deleteWarning.textContent =
                "Kelas belum memiliki siswa, sehingga dapat dihapus pada demo UI.";
        }
    }

    openModal(deleteModal);
}

/* =========================================================
   TOMBOL DELETE DARI FORM EDIT
   ========================================================= */

if (kelasDeleteButton) {
    kelasDeleteButton.addEventListener("click", function () {
        if (editingClass) {
            openDeleteConfirmation(editingClass);
        }
    });
}

/* =========================================================
   KONFIRMASI DELETE
   ========================================================= */

if (deleteConfirm) {
    deleteConfirm.addEventListener("click", function () {
        if (!pendingDeleteClass || deleteConfirm.disabled) {
            return;
        }

        const name = pendingDeleteClass.dataset.kelasName;

        const id = pendingDeleteClass.dataset.kelasId;

        pendingDeleteClass.remove();

        delete dummyStudents[id];

        pendingDeleteClass = null;

        /*
         * Hapus section tingkat
         * jika sudah tidak memiliki kelas.
         */

        document
            .querySelectorAll("#data-kelas > .k-div-6")
            .forEach(function (section) {
                const grid = section.querySelector(".k-div-7, .k-div-15");

                if (grid && !grid.querySelector("[data-kelas-id]")) {
                    section.remove();
                }
            });

        closeModal(deleteModal);

        updateSummary();

        showAppToast(
            "Kelas " + name + " berhasil dihapus pada data dummy.",
            "success",
        );
    });
}

/* =========================================================
   CLOSE MODAL TAMBAH / EDIT
   ========================================================= */

const kelasModalClose = document.querySelector("#kelas-modal-close");

if (kelasModalClose) {
    kelasModalClose.addEventListener("click", closeKelasForm);
}

const kelasFormCancel = document.querySelector("#kelas-form-cancel");

if (kelasFormCancel) {
    kelasFormCancel.addEventListener("click", closeKelasForm);
}

document.querySelectorAll("[data-kelas-modal-close]").forEach(function (item) {
    item.addEventListener("click", closeKelasForm);
});

/* =========================================================
   CLOSE DETAIL
   ========================================================= */

const kelasDetailClose = document.querySelector("#kelas-detail-close");

if (kelasDetailClose) {
    kelasDetailClose.addEventListener("click", function () {
        closeModal(detailModal);
    });
}

document.querySelectorAll("[data-detail-modal-close]").forEach(function (item) {
    item.addEventListener("click", function () {
        closeModal(detailModal);
    });
});

/* =========================================================
   CLOSE DELETE
   ========================================================= */

const kelasDeleteCancel = document.querySelector("#kelas-delete-cancel");

if (kelasDeleteCancel) {
    kelasDeleteCancel.addEventListener("click", function () {
        pendingDeleteClass = null;

        closeModal(deleteModal);
    });
}

document.querySelectorAll("[data-delete-modal-close]").forEach(function (item) {
    item.addEventListener("click", function () {
        pendingDeleteClass = null;

        closeModal(deleteModal);
    });
});

/* =========================================================
   ESCAPE
   ========================================================= */

document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape") {
        return;
    }

    if (kelasModal && !kelasModal.hidden) {
        closeKelasForm();
    }

    if (detailModal && !detailModal.hidden) {
        closeModal(detailModal);
    }

    if (deleteModal && !deleteModal.hidden) {
        pendingDeleteClass = null;

        closeModal(deleteModal);
    }
});

/* =========================================================
   TAMBAH KELAS DUMMY 3A
   ========================================================= */

if (!document.querySelector('[data-kelas-name="3A"]')) {
    const section = document.createElement("section");

    section.className = "k-div-6 kelas-demo-empty-section";

    section.setAttribute("aria-labelledby", "tingkat-3");

    section.innerHTML =
        '<h2 class="k-text-wrapper-5" id="tingkat-3">' +
        "Tingkat 3" +
        "</h2>" +
        '<div class="k-div-15 kelas-generated-grid"></div>';

    const card = document.createElement("article");

    card.className = "k-div-16 kelas-generated-card";

    card.dataset.kelasId = "6";

    card.dataset.kelasName = "3A";

    card.dataset.tahun = "2025/2026";

    card.dataset.waliId = "6";

    card.dataset.wali = "Pak Dimas";

    card.dataset.siswa = "0";

    card.innerHTML =
        '<div class="k-div-9">' +
        '<div class="k-div-10">' +
        '<div class="k-div-11">' +
        '<div class="k-div-12">' +
        '<h3 class="k-text-wrapper-5">' +
        "Kelas 3A" +
        "</h3>" +
        '<p class="k-text-wrapper-2">' +
        "Tahun Ajaran 2025/2026" +
        "</p>" +
        "</div>" +
        '<div class="k-div-13">' +
        '<svg class="icon-svg k-img-2" viewBox="0 0 24 24" aria-hidden="true">' +
        '<circle cx="12" cy="8" r="3.5" fill="none" stroke="currentColor" stroke-width="2"></circle>' +
        '<path d="M5 21c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>' +
        "</svg>" +
        '<div class="k-div-2">' +
        '<p class="k-text-wrapper-6">Wali Kelas</p>' +
        '<p class="k-text-wrapper-2">' +
        "Pak Dimas" +
        "</p>" +
        "</div>" +
        "</div>" +
        '<div class="k-div-13">' +
        '<svg class="icon-svg k-img-2" viewBox="0 0 24 24" aria-hidden="true">' +
        '<circle cx="9" cy="8" r="3" fill="none" stroke="currentColor" stroke-width="2"></circle>' +
        '<path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2"></path>' +
        '<path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor" stroke-width="2"></path>' +
        "</svg>" +
        '<div class="k-div-2">' +
        '<p class="k-text-wrapper-6">Jumlah Siswa</p>' +
        '<p class="k-text-wrapper-2">' +
        "0 Siswa" +
        "</p>" +
        "</div>" +
        "</div>" +
        "</div>" +
        '<span class="k-div-wrapper">' +
        '<span class="k-text-wrapper-7">' +
        "3" +
        "</span>" +
        "</span>" +
        "</div>" +
        '<span class="k-line"></span>' +
        "</div>" +
        '<nav aria-label="Aksi Kelas 3A" class="k-div-14">' +
        '<button class="k-text-wrapper-8 kelas-detail-btn" type="button">' +
        "Detail" +
        "</button>" +
        '<button class="k-text-wrapper-9 kelas-edit-btn" type="button">' +
        "Edit" +
        "</button>" +
        "</nav>";

    const grid = section.querySelector(".k-div-15");

    if (grid) {
        grid.appendChild(card);
    }

    const dataKelas = document.querySelector("#data-kelas");

    if (dataKelas) {
        dataKelas.appendChild(section);
    }
}

/* =========================================================
   INITIAL SUMMARY
   ========================================================= */

updateSummary();
