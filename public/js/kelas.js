document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    const dataKelas = document.getElementById("data-kelas");
    const kelasAddButton = document.getElementById("kelas-add-button");
    const kelasModal = document.getElementById("kelas-modal");
    const kelasModalClose = document.getElementById("kelas-modal-close");
    const kelasForm = document.getElementById("kelas-form");
    const kelasFormCancel = document.getElementById("kelas-form-cancel");
    const kelasName = document.getElementById("kelas-name");
    const kelasYear = document.getElementById("kelas-year");
    const kelasWali = document.getElementById("kelas-wali");
    const kelasModalTitle = document.getElementById("kelas-modal-title");
    const kelasModalDesc = document.getElementById("kelas-modal-desc");
    const kelasSubmit = document.getElementById("kelas-form-submit-btn");
    const kelasDeleteButton = document.getElementById("kelas-form-delete-btn");
    const detailModal = document.getElementById("kelas-detail-modal");
    const kelasDetailClose = document.getElementById("kelas-detail-close");
    const detailNama = document.getElementById("detail-nama-kelas");
    const detailTahun = document.getElementById("detail-tahun");
    const detailWali = document.getElementById("detail-wali");
    const detailJumlah = document.getElementById("detail-jumlah-siswa");
    const detailStudentCount = document.getElementById("detail-student-count");
    const detailStudentBody = document.getElementById("detail-student-body");
    const noStudent = document.getElementById("kelas-no-student");
    const deleteModal = document.getElementById("kelas-delete-modal");
    const deleteText = document.getElementById("kelas-delete-text");
    const deleteWarning = document.getElementById("kelas-delete-warning");
    const deleteConfirm = document.getElementById("kelas-delete-confirm");
    const kelasDeleteCancel = document.getElementById("kelas-delete-cancel");
    const kelasYearFilter = document.getElementById("kelas-year-filter");

    let editingCard = null;
    let pendingDeleteCard = null;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";

    function showMessage(message, type = "error") {
        if (typeof showAppToast === "function") {
            showAppToast(message, type);
            return;
        }
        if (type === "success") console.log(message);
        else console.error(message);
    }

    function normalizeClassName(value) {
        return String(value || "").trim().toUpperCase();
    }

    function openModal(modal) {
        if (!modal) return;
        modal.hidden = false;
        document.body.classList.add("kelas-modal-open");
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.hidden = true;
        const modalMasihTerbuka =
            (kelasModal && !kelasModal.hidden) ||
            (detailModal && !detailModal.hidden) ||
            (deleteModal && !deleteModal.hidden);
        if (!modalMasihTerbuka) document.body.classList.remove("kelas-modal-open");
    }

    function closeKelasForm() {
        closeModal(kelasModal);
        kelasForm?.reset();
        editingCard = null;
        setWaliOptions();
    }

    async function getJsonResponse(response) {
        let result = null;
        try {
            result = await response.json();
        } catch {
            throw new Error("Server tidak mengembalikan data yang dapat diproses.");
        }
        if (!response.ok) {
            let message = result?.message;
            if (response.status === 422 && result?.errors && typeof result.errors === "object") {
                const firstError = Object.values(result.errors).flat()[0];
                if (firstError) message = firstError;
            }
            throw new Error(message || "Terjadi kesalahan saat memproses data kelas.");
        }
        return result;
    }

    function validateClassData(data) {
        if (!data || typeof data !== "object") throw new Error("Data kelas dari server tidak valid.");
        const requiredFields = ["id", "nama_kelas", "tahun_ajaran", "wali_kelas_id"];
        for (const field of requiredFields) {
            if (data[field] === undefined || data[field] === null) {
                throw new Error("Data kelas dari server tidak lengkap.");
            }
        }
        return {
            id: data.id,
            nama_kelas: normalizeClassName(data.nama_kelas),
            tahun_ajaran: data.tahun_ajaran,
            wali_kelas_id: data.wali_kelas_id,
            wali_kelas: data.wali_kelas || "-",
            jumlah_siswa: Number(data.jumlah_siswa || 0),
        };
    }

    function getCards() {
        return Array.from(document.querySelectorAll("#data-kelas [data-kelas-id]"));
    }

    function getCardById(id) {
        return getCards().find(card => String(card.dataset.kelasId) === String(id));
    }

    function getCardStudentCount(card) {
        return Number(card?.dataset?.siswa || 0);
    }

    /* Nama kelas hanya dianggap duplikat jika tahun ajarannya sama. */
    function classNameAlreadyUsed(name, year, exceptCard = null) {
        const normalizedName = normalizeClassName(name);
        const normalizedYear = String(year || "").trim();
        if (!normalizedYear) return false;

        return getCards().some(card => {
            if (card === exceptCard) return false;
            return (
                normalizeClassName(card.dataset.kelasName) === normalizedName &&
                String(card.dataset.tahun || "").trim() === normalizedYear
            );
        });
    }

    /* Wali kelas hanya dianggap sudah digunakan jika tahun ajarannya sama. */
    function waliAlreadyUsed(waliId, year, exceptCard = null) {
        const normalizedYear = String(year || "").trim();
        if (!normalizedYear || !waliId) return false;

        return getCards().some(card => {
            if (card === exceptCard) return false;
            return (
                String(card.dataset.waliId || "") === String(waliId) &&
                String(card.dataset.tahun || "").trim() === normalizedYear
            );
        });
    }

    function setWaliOptions() {
        if (!kelasWali) return;

        const year = kelasYear?.value.trim() || "";

        Array.from(kelasWali.options).forEach((option, index) => {
            if (index === 0 || !option.value) return;
            option.disabled = waliAlreadyUsed(option.value, year, editingCard);
        });
    }

    function getSelectedWaliName() {
        if (!kelasWali || kelasWali.selectedIndex < 0) return "";
        const option = kelasWali.options[kelasWali.selectedIndex];
        return option ? option.textContent.trim() : "";
    }

    function refreshYearFilterOptions() {
        if (!kelasYearFilter) return;

        const selectedValue = kelasYearFilter.value || "all";
        const years = [...new Set(
            getCards()
                .map(card => String(card.dataset.tahun || "").trim())
                .filter(Boolean)
        )].sort((a, b) => b.localeCompare(a));

        kelasYearFilter.innerHTML = '<option value="all">Semua Tahun Ajaran</option>';

        years.forEach(year => {
            const option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            kelasYearFilter.appendChild(option);
        });

        kelasYearFilter.value = years.includes(selectedValue) ? selectedValue : "all";
    }

    function applyYearFilter() {
        if (!kelasYearFilter) return;

        const selectedYear = String(kelasYearFilter.value || "all").trim();
        const sections = Array.from(document.querySelectorAll("#data-kelas > .k-div-6"));
        let visibleCards = 0;

        sections.forEach(section => {
            const cards = Array.from(section.querySelectorAll("[data-kelas-id]"));
            let sectionHasVisibleCard = false;

            cards.forEach(card => {
                const cardYear = String(card.dataset.tahun || "").trim();
                const shouldShow = selectedYear === "all" || cardYear === selectedYear;

                card.hidden = !shouldShow;
                card.style.display = shouldShow ? "" : "none";

                if (shouldShow) {
                    sectionHasVisibleCard = true;
                    visibleCards++;
                }
            });

            section.hidden = !sectionHasVisibleCard;
            section.style.display = sectionHasVisibleCard ? "" : "none";
        });

        const emptyState = document.querySelector(".kelas-filter-empty");
        if (emptyState) {
            emptyState.hidden = visibleCards > 0;
            emptyState.style.display = visibleCards > 0 ? "none" : "";
        }
    }

    function syncYearFilter() {
        refreshYearFilterOptions();
        applyYearFilter();
    }

    function updateSummary() {
        const cards = getCards();
        const totalKelas = cards.length;
        const totalSiswa = cards.reduce((total, card) => total + getCardStudentCount(card), 0);
        const rataRata = totalKelas > 0 ? Math.round(totalSiswa / totalKelas) : 0;
        const summaryValues = document.querySelectorAll("#data-kelas .k-div-3 .k-div-4 .k-text-wrapper-4");
        if (summaryValues[0]) summaryValues[0].textContent = totalKelas;
        if (summaryValues[1]) summaryValues[1].textContent = totalSiswa;
        if (summaryValues[2]) summaryValues[2].textContent = rataRata;
    }

    function getClassLevel(name) {
        const match = String(name || "").match(/^\d+/);
        return match ? match[0] : "1";
    }

    function getOrCreateLevelSection(level) {
        let section = document.getElementById(`tingkat-${level}`)?.closest(".k-div-6");
        if (section) return section;

        section = document.createElement("section");
        section.className = "k-div-6";
        section.setAttribute("aria-labelledby", `tingkat-${level}`);
        section.innerHTML = `
            <h2 class="k-text-wrapper-5" id="tingkat-${level}">Tingkat ${level}</h2>
            <div class="k-div-7"></div>
        `;
        dataKelas?.appendChild(section);
        return section;
    }

    function createKelasCard(data) {
        const kelas = validateClassData(data);
        const level = getClassLevel(kelas.nama_kelas);
        const section = getOrCreateLevelSection(level);
        const grid = section.querySelector(".k-div-7");
        if (!grid) throw new Error("Tempat untuk menampilkan data kelas tidak ditemukan.");

        const card = document.createElement("article");
        card.className = "k-div-8";
        card.dataset.kelasId = kelas.id;
        card.dataset.kelasName = kelas.nama_kelas;
        card.dataset.tahun = kelas.tahun_ajaran;
        card.dataset.waliId = kelas.wali_kelas_id;
        card.dataset.wali = kelas.wali_kelas;
        card.dataset.siswa = kelas.jumlah_siswa;
        card.setAttribute("aria-labelledby", `kelas-${kelas.id}`);

        card.innerHTML = `
            <div class="k-div-9">
                <div class="k-div-10">
                    <div class="k-div-11">
                        <div class="k-div-12">
                            <h3 class="k-text-wrapper-5" id="kelas-${kelas.id}">Kelas ${kelas.nama_kelas}</h3>
                            <p class="k-text-wrapper-2">Tahun Ajaran ${kelas.tahun_ajaran}</p>
                        </div>
                        <div class="k-div-13">
                            <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="3.5" fill="none" stroke="currentColor" stroke-width="2"></circle>
                                <path d="M5 21c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>
                            </svg>
                            <div class="k-div-2">
                                <p class="k-text-wrapper-6">Wali Kelas</p>
                                <p class="k-text-wrapper-2">${kelas.wali_kelas}</p>
                            </div>
                        </div>
                        <div class="k-div-13">
                            <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewBox="0 0 24 24">
                                <circle cx="9" cy="8" r="3" fill="none" stroke="currentColor" stroke-width="2"></circle>
                                <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2"></path>
                                <path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor" stroke-width="2"></path>
                            </svg>
                            <div class="k-div-2">
                                <p class="k-text-wrapper-6">Jumlah Siswa</p>
                                <p class="k-text-wrapper-2">${kelas.jumlah_siswa} Siswa</p>
                            </div>
                        </div>
                    </div>
                    <span aria-label="Tingkat ${level}" class="k-div-wrapper">
                        <span class="k-text-wrapper-7">${level}</span>
                    </span>
                </div>
                <span aria-hidden="true" class="k-line"></span>
            </div>
            <nav aria-label="Aksi Kelas ${kelas.nama_kelas}" class="k-div-14">
                <button aria-label="Lihat detail Kelas ${kelas.nama_kelas}" class="k-text-wrapper-8 kelas-detail-btn" type="button">Detail</button>
                <button aria-label="Edit Kelas ${kelas.nama_kelas}" class="k-text-wrapper-9 kelas-edit-btn" type="button">Edit</button>
            </nav>
        `;

        grid.appendChild(card);
        return card;
    }

    function updateKelasCard(card, data) {
        const kelas = validateClassData(data);
        const oldLevel = getClassLevel(card.dataset.kelasName);
        const newLevel = getClassLevel(kelas.nama_kelas);

        card.dataset.kelasId = kelas.id;
        card.dataset.kelasName = kelas.nama_kelas;
        card.dataset.tahun = kelas.tahun_ajaran;
        card.dataset.waliId = kelas.wali_kelas_id;
        card.dataset.wali = kelas.wali_kelas;
        card.dataset.siswa = kelas.jumlah_siswa;

        const title = card.querySelector("h3");
        const yearText = card.querySelector(".k-div-12 .k-text-wrapper-2");
        const infoTexts = card.querySelectorAll(".k-div-13 .k-text-wrapper-2");
        const levelBadge = card.querySelector(".k-text-wrapper-7");
        const levelContainer = card.querySelector(".k-div-wrapper");
        const nav = card.querySelector("nav");
        const detailButton = card.querySelector(".kelas-detail-btn");
        const editButton = card.querySelector(".kelas-edit-btn");

        if (title) {
            title.textContent = `Kelas ${kelas.nama_kelas}`;
            title.id = `kelas-${kelas.id}`;
        }
        if (yearText) yearText.textContent = `Tahun Ajaran ${kelas.tahun_ajaran}`;
        if (infoTexts[0]) infoTexts[0].textContent = kelas.wali_kelas;
        if (infoTexts[1]) infoTexts[1].textContent = `${kelas.jumlah_siswa} Siswa`;
        if (levelBadge) levelBadge.textContent = newLevel;
        if (levelContainer) levelContainer.setAttribute("aria-label", `Tingkat ${newLevel}`);
        if (nav) nav.setAttribute("aria-label", `Aksi Kelas ${kelas.nama_kelas}`);
        if (detailButton) detailButton.setAttribute("aria-label", `Lihat detail Kelas ${kelas.nama_kelas}`);
        if (editButton) editButton.setAttribute("aria-label", `Edit Kelas ${kelas.nama_kelas}`);

        if (oldLevel !== newLevel) {
            const newSection = getOrCreateLevelSection(newLevel);
            const newGrid = newSection.querySelector(".k-div-7");
            if (newGrid) newGrid.appendChild(card);
        }
        return card;
    }

    function removeEmptyLevelSections() {
        document.querySelectorAll("#data-kelas > .k-div-6").forEach(section => {
            const grid = section.querySelector(".k-div-7");
            if (grid && !grid.querySelector("[data-kelas-id]")) section.remove();
        });
    }

    function openKelasForm(isEdit) {
        if (!kelasModal) return;
        if (kelasModalTitle) kelasModalTitle.textContent = isEdit ? "Edit Data Kelas" : "Tambah Data Kelas";
        if (kelasModalDesc) kelasModalDesc.textContent = isEdit
            ? "Ubah nama kelas, tahun ajaran, atau wali kelas."
            : "Lengkapi data kelas yang akan ditambahkan.";
        if (kelasSubmit) kelasSubmit.textContent = isEdit ? "Simpan Perubahan" : "Simpan Data Kelas";
        if (kelasDeleteButton) kelasDeleteButton.hidden = !isEdit;
        setWaliOptions();
        openModal(kelasModal);
        setTimeout(() => kelasName?.focus(), 50);
    }

    function fillEditForm(card) {
        if (!card) return;
        editingCard = card;
        if (kelasName) kelasName.value = card.dataset.kelasName || "";
        if (kelasYear) kelasYear.value = card.dataset.tahun || "";
        if (kelasWali) kelasWali.value = card.dataset.waliId || "";
        openKelasForm(true);
    }

    kelasAddButton?.addEventListener("click", () => {
        editingCard = null;
        kelasForm?.reset();
        setWaliOptions();
        openKelasForm(false);
    });

    kelasYear?.addEventListener("change", setWaliOptions);
    kelasYearFilter?.addEventListener("change", applyYearFilter);

    document.addEventListener("click", event => {
        const editButton = event.target.closest(".kelas-edit-btn");
        if (editButton) {
            event.preventDefault();
            const card = editButton.closest("[data-kelas-id]");
            if (card) fillEditForm(card);
            return;
        }

        const detailButton = event.target.closest(".kelas-detail-btn");
        if (detailButton) {
            event.preventDefault();
            const card = detailButton.closest("[data-kelas-id]");
            if (card) openDetail(card);
        }
    });

    kelasForm?.addEventListener("submit", async event => {
        event.preventDefault();

        const name = normalizeClassName(kelasName?.value);
        const year = kelasYear?.value.trim() || "";
        const waliId = kelasWali?.value || "";

        if (!/^\d[A-Z]$/.test(name)) {
            showMessage("Nama kelas harus menggunakan format seperti 1A, 2B, atau 3C.");
            kelasName?.focus();
            return;
        }

        if (!/^\d{4}\/\d{4}$/.test(year)) {
            showMessage("Tahun ajaran harus menggunakan format YYYY/YYYY, contoh 2026/2027.");
            kelasYear?.focus();
            return;
        }

        if (!waliId) {
            showMessage("Silakan pilih wali kelas.");
            kelasWali?.focus();
            return;
        }

        if (classNameAlreadyUsed(name, year, editingCard)) {
            showMessage("Nama kelas sudah digunakan pada tahun ajaran tersebut.");
            kelasName?.focus();
            return;
        }

        if (waliAlreadyUsed(waliId, year, editingCard)) {
            showMessage("Guru tersebut sudah menjadi wali kelas lain pada tahun ajaran tersebut.");
            kelasWali?.focus();
            return;
        }

        const payload = {
            nama_kelas: name,
            tahun_ajaran: year,
            wali_kelas_id: waliId,
        };

        const isEdit = Boolean(editingCard);
        const id = isEdit ? editingCard.dataset.kelasId : null;
        const url = isEdit ? `/kelas/${id}` : "/kelas";
        const method = isEdit ? "PUT" : "POST";

        if (kelasSubmit) kelasSubmit.disabled = true;

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify(payload),
            });

            const result = await getJsonResponse(response);
            if (!result.success) throw new Error(result.message || "Data kelas gagal diproses.");

            const kelas = validateClassData(result.data);
            if (isEdit) updateKelasCard(editingCard, kelas);
            else createKelasCard(kelas);

            updateSummary();
            syncYearFilter();
            closeKelasForm();
            setWaliOptions();

            showMessage(
                result.message || (isEdit ? "Data kelas berhasil diperbarui." : "Data kelas berhasil ditambahkan."),
                "success"
            );
        } catch (error) {
            console.error("Kesalahan data kelas:", error);
            showMessage(error.message || "Terjadi kesalahan saat memproses data kelas.");
        } finally {
            if (kelasSubmit) kelasSubmit.disabled = false;
        }
    });

    async function openDetail(card) {
        if (!card) return;
        const id = card.dataset.kelasId;
        if (!id) {
            showMessage("ID kelas tidak ditemukan.");
            return;
        }

        try {
            const response = await fetch(`/kelas/${id}/detail`, {
                method: "GET",
                headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
            });
            const result = await getJsonResponse(response);
            if (!result || !result.success || !result.data) throw new Error("Data detail kelas dari server tidak valid.");

            const data = result.data;
            if (data.id === undefined || data.nama_kelas === undefined) throw new Error("Data detail kelas dari server tidak lengkap.");
            if (detailNama) detailNama.textContent = `Kelas ${data.nama_kelas}`;
            if (detailTahun) detailTahun.textContent = data.tahun_ajaran || "-";
            if (detailWali) detailWali.textContent = data.wali_kelas || "-";
            if (detailJumlah) detailJumlah.textContent = `${data.jumlah_siswa || 0} Siswa`;
            if (detailStudentCount) detailStudentCount.textContent = `${data.jumlah_siswa || 0} siswa`;
            if (detailStudentBody) detailStudentBody.innerHTML = "";

            const students = Array.isArray(data.siswa) ? data.siswa : [];
            if (noStudent) noStudent.hidden = students.length > 0;

            students.forEach((student, index) => {
                const row = document.createElement("tr");
                const jenisKelamin = student.jenis_kelamin === "L" ? "Laki-laki" : "Perempuan";
                [index + 1, student.nisn || "-", student.nama_siswa || "-", jenisKelamin].forEach(value => {
                    const cell = document.createElement("td");
                    cell.textContent = value;
                    row.appendChild(cell);
                });
                detailStudentBody?.appendChild(row);
            });
            openModal(detailModal);
        } catch (error) {
            console.error("Kesalahan detail kelas:", error);
            showMessage(error.message || "Gagal mengambil detail kelas dari database.");
        }
    }

    function openDeleteConfirmation(card) {
        if (!card) return;
        pendingDeleteCard = card;
        const name = card.dataset.kelasName || "-";
        const jumlahSiswa = getCardStudentCount(card);
        if (deleteText) deleteText.textContent = `Anda akan menghapus Kelas ${name}.`;
        if (deleteConfirm) deleteConfirm.disabled = jumlahSiswa > 0;
        if (deleteWarning) deleteWarning.textContent = jumlahSiswa > 0
            ? `Kelas tidak dapat dihapus karena masih memiliki ${jumlahSiswa} siswa.`
            : "Kelas ini belum memiliki siswa dan dapat dihapus.";
        openModal(deleteModal);
    }

    kelasDeleteButton?.addEventListener("click", () => {
        if (!editingCard) return;
        openDeleteConfirmation(editingCard);
    });

    deleteConfirm?.addEventListener("click", async () => {
        if (!pendingDeleteCard || deleteConfirm.disabled) return;
        const card = pendingDeleteCard;
        const id = card.dataset.kelasId;
        const name = card.dataset.kelasName || "";
        if (!id) {
            showMessage("ID kelas tidak ditemukan.");
            return;
        }

        deleteConfirm.disabled = true;
        try {
            const response = await fetch(`/kelas/${id}`, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "X-Requested-With": "XMLHttpRequest",
                },
            });
            const result = await getJsonResponse(response);
            if (!result.success) throw new Error(result.message || "Data kelas gagal dihapus.");

            card.remove();
            removeEmptyLevelSections();
            updateSummary();
            syncYearFilter();
            pendingDeleteCard = null;
            closeModal(deleteModal);
            if (editingCard === card) closeKelasForm();
            setWaliOptions();
            showMessage(result.message || `Kelas ${name} berhasil dihapus.`, "success");
        } catch (error) {
            console.error("Kesalahan hapus kelas:", error);
            showMessage(error.message || "Gagal menghapus data kelas.");
        } finally {
            deleteConfirm.disabled = false;
        }
    });

    kelasModalClose?.addEventListener("click", closeKelasForm);
    kelasFormCancel?.addEventListener("click", closeKelasForm);
    document.querySelectorAll("[data-kelas-modal-close]").forEach(element => element.addEventListener("click", closeKelasForm));

    kelasDetailClose?.addEventListener("click", () => closeModal(detailModal));
    document.querySelectorAll("[data-detail-modal-close]").forEach(element => element.addEventListener("click", () => closeModal(detailModal)));

    kelasDeleteCancel?.addEventListener("click", () => {
        pendingDeleteCard = null;
        closeModal(deleteModal);
    });

    document.querySelectorAll("[data-delete-modal-close]").forEach(element => {
        element.addEventListener("click", () => {
            pendingDeleteCard = null;
            closeModal(deleteModal);
        });
    });

    document.addEventListener("keydown", event => {
        if (event.key !== "Escape") return;
        if (kelasModal && !kelasModal.hidden) {
            closeKelasForm();
            return;
        }
        if (detailModal && !detailModal.hidden) {
            closeModal(detailModal);
            return;
        }
        if (deleteModal && !deleteModal.hidden) {
            pendingDeleteCard = null;
            closeModal(deleteModal);
        }
    });

    updateSummary();
    syncYearFilter();
    setWaliOptions();
});
