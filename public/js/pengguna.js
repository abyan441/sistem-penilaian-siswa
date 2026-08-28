document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       ELEMENT
       ===================================================== */
    const addUserButton = document.querySelector("#open-user-form");
    const userFormOverlay = document.querySelector("#user-form-overlay");
    const userForm = document.querySelector("#user-form");
    const userFormClose = document.querySelector("#user-form-close");
    const userFormCancel = document.querySelector("#user-form-cancel");
    const userFormTitle = document.querySelector("#user-form-title");
    const userFormDescription = document.querySelector("#user-form-description");
    const userIdInput = document.querySelector("#user-id");
    const usernameInput = document.querySelector("#username");
    const passwordInput = document.querySelector("#password");
    const passwordRequired = document.querySelector("#password-required");
    const passwordHelp = document.querySelector("#password-help");
    const namaLengkapInput = document.querySelector("#nama_lengkap");
    const emailInput = document.querySelector("#email");
    const roleInput = document.querySelector("#role");
    const statusInput = document.querySelector("#status");
    const nipInput = document.querySelector("#nip");
    const submitButton = userForm?.querySelector(".user-form-submit");

    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ||
        document.querySelector('input[name="_token"]')?.value ||
        "";

    let editMode = false;

    /* =====================================================
       TOAST & API ERROR
       ===================================================== */
    function showToast(message, type = "success") {
        if (typeof window.showAppToast === "function") {
            window.showAppToast(message, type);
            return;
        }
        alert(message);
    }

    async function getErrorMessage(response) {
        try {
            const result = await response.json();
            if (result.message && typeof result.message === "string") {
                return result.message;
            }
            if (result.errors) {
                const firstError = Object.values(result.errors).flat()[0];
                if (firstError) return firstError;
            }
        } catch (error) {
            /* Gunakan pesan umum. */
        }
        return "Terjadi kesalahan pada server.";
    }

    function requestOptions(method = "GET", body = null) {
        const options = {
            method,
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        };

        if (csrfToken) options.headers["X-CSRF-TOKEN"] = csrfToken;

        if (body !== null) {
            options.headers["Content-Type"] = "application/json";
            options.body = JSON.stringify(body);
        }

        return options;
    }

    /* =====================================================
       MODAL KONFIRMASI
       Dibuat melalui JS agar tidak mengubah struktur Blade
       dan tidak mengganggu modal tambah/edit pengguna.
       ===================================================== */
    let confirmationOverlay = null;
    let confirmationResolve = null;

    function createConfirmationModal() {
        if (confirmationOverlay) return;

        const style = document.createElement("style");
        style.id = "user-confirmation-style";
        style.textContent = `
            .user-confirm-overlay {
                position: fixed;
                inset: 0;
                z-index: 1100;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
                background: rgba(15, 23, 42, 0.48);
                box-sizing: border-box;
                opacity: 0;
                visibility: hidden;
                transition: opacity .18s ease, visibility .18s ease;
            }
            .user-confirm-overlay.is-visible {
                opacity: 1;
                visibility: visible;
            }
            .user-confirm-modal {
                width: min(430px, 100%);
                background: var(--primarypr-00, #fff);
                border-radius: 15px;
                box-shadow: 0 18px 50px rgba(0,0,0,.18);
                padding: 28px;
                box-sizing: border-box;
                transform: translateY(8px) scale(.98);
                transition: transform .18s ease;
            }
            .user-confirm-overlay.is-visible .user-confirm-modal {
                transform: translateY(0) scale(1);
            }
            .user-confirm-icon {
                width: 48px;
                height: 48px;
                margin: 0 auto 16px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--primarypr-10, #f4f6f8);
                color: var(--secondarysec-80, #2196f3);
            }
            .user-confirm-icon.is-danger {
                background: rgba(220,53,69,.10);
                color: #dc3545;
            }
            .user-confirm-icon svg {
                width: 24px;
                height: 24px;
            }
            .user-confirm-title {
                margin: 0;
                text-align: center;
                color: var(--primarypr-50, #111);
                font-family: var(--heading-h5-medium-font-family, inherit);
                font-size: 20px;
                line-height: 26px;
                font-weight: 500;
            }
            .user-confirm-message {
                margin: 10px 0 0;
                text-align: center;
                color: var(--primarypr-50, #333);
                font-family: var(--paragraph-p16-regular-font-family, inherit);
                font-size: 15px;
                line-height: 23px;
            }
            .user-confirm-message strong {
                font-weight: 600;
            }
            .user-confirm-actions {
                display: flex;
                justify-content: center;
                gap: 12px;
                margin-top: 24px;
            }
            .user-confirm-button {
                min-width: 120px;
                min-height: 40px;
                padding: 9px 18px;
                border: 0;
                border-radius: 9px;
                font-family: var(--label-l16-regular-font-family, inherit);
                font-size: 15px;
                line-height: 20px;
                cursor: pointer;
                transition: filter .2s ease, transform .2s ease;
            }
            .user-confirm-button:hover {
                filter: brightness(.95);
                transform: translateY(-1px);
            }
            .user-confirm-button:active {
                transform: translateY(0);
            }
            .user-confirm-cancel {
                background: var(--primarypr-10, #f1f3f5);
                color: var(--primarypr-50, #333);
            }
            .user-confirm-submit {
                background: var(--primarypr-30, #2196f3);
                color: var(--primarypr-00, #fff);
            }
            .user-confirm-submit.is-danger {
                background: #dc3545;
            }
            @media (max-width: 480px) {
                .user-confirm-overlay { padding: 16px; }
                .user-confirm-modal { padding: 24px 20px; }
                .user-confirm-actions { flex-direction: column-reverse; }
                .user-confirm-button { width: 100%; }
            }
        `;
        document.head.appendChild(style);

        confirmationOverlay = document.createElement("div");
        confirmationOverlay.className = "user-confirm-overlay";
        confirmationOverlay.hidden = true;
        confirmationOverlay.innerHTML = `
            <section class="user-confirm-modal" role="dialog" aria-modal="true" aria-labelledby="user-confirm-title">
                <div class="user-confirm-icon" id="user-confirm-icon" aria-hidden="true"></div>
                <h2 class="user-confirm-title" id="user-confirm-title"></h2>
                <p class="user-confirm-message" id="user-confirm-message"></p>
                <div class="user-confirm-actions">
                    <button type="button" class="user-confirm-button user-confirm-cancel" id="user-confirm-cancel">Batal</button>
                    <button type="button" class="user-confirm-button user-confirm-submit" id="user-confirm-submit">Konfirmasi</button>
                </div>
            </section>
        `;
        document.body.appendChild(confirmationOverlay);

        confirmationOverlay.addEventListener("click", function (event) {
            if (event.target === confirmationOverlay) closeConfirmation(false);
        });

        document.querySelector("#user-confirm-cancel").addEventListener("click", function () {
            closeConfirmation(false);
        });

        document.querySelector("#user-confirm-submit").addEventListener("click", function () {
            closeConfirmation(true);
        });
    }

    function closeConfirmation(result) {
        if (!confirmationOverlay) return;

        confirmationOverlay.classList.remove("is-visible");
        document.body.classList.remove("user-confirm-open");

        setTimeout(function () {
            confirmationOverlay.hidden = true;
            if (confirmationResolve) {
                const resolve = confirmationResolve;
                confirmationResolve = null;
                resolve(result);
            }
        }, 180);
    }

    function confirmUserAction({ title, message, confirmText, danger = false }) {
        createConfirmationModal();

        return new Promise(function (resolve) {
            confirmationResolve = resolve;

            const icon = document.querySelector("#user-confirm-icon");
            const titleElement = document.querySelector("#user-confirm-title");
            const messageElement = document.querySelector("#user-confirm-message");
            const submit = document.querySelector("#user-confirm-submit");

            titleElement.textContent = title;
            messageElement.innerHTML = message;
            submit.textContent = confirmText;
            submit.classList.toggle("is-danger", danger);
            icon.classList.toggle("is-danger", danger);

            icon.innerHTML = danger
                ? `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.3 3.7 2.1 18a2 2 0 0 0 1.7 3h16.4a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`
                : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`;

            confirmationOverlay.hidden = false;
            document.body.classList.add("user-confirm-open");
            requestAnimationFrame(function () {
                confirmationOverlay.classList.add("is-visible");
            });

            setTimeout(function () {
                submit.focus();
            }, 100);
        });
    }

    /* =====================================================
       ROLE OPTION
       ===================================================== */
    function setRoleOptions(currentRole = null) {
        if (!roleInput) return;

        const roles = [{ value: "guru", label: "Guru" }];
        const existingHeadmaster = roleInput.querySelector('option[value="kepala_sekolah"]');

        if (currentRole === "kepala_sekolah" || existingHeadmaster) {
            roles.unshift({ value: "kepala_sekolah", label: "Kepala Sekolah" });
        }
        if (currentRole === "admin") {
            roles.unshift({ value: "admin", label: "Administrator" });
        }

        const placeholder = document.createElement("option");
        placeholder.value = "";
        placeholder.textContent = "Pilih role pengguna";
        placeholder.disabled = true;

        roleInput.innerHTML = "";
        roleInput.appendChild(placeholder);

        roles.forEach(function (role) {
            const option = document.createElement("option");
            option.value = role.value;
            option.textContent = role.label;
            roleInput.appendChild(option);
        });

        roleInput.value = currentRole || "";
    }

    /* =====================================================
       FORM RESET / OPEN / CLOSE
       ===================================================== */
    function resetUserForm() {
        if (!userForm) return;
        userForm.reset();
        if (userIdInput) userIdInput.value = "";
        editMode = false;

        if (userFormTitle) userFormTitle.textContent = "Tambah Pengguna";
        if (userFormDescription) userFormDescription.textContent = "Tambahkan akun pengguna baru ke sistem E-Raport";
        if (submitButton) {
            submitButton.textContent = "Simpan Pengguna";
            submitButton.disabled = false;
        }
        if (passwordInput) {
            passwordInput.value = "";
            passwordInput.required = true;
        }
        if (passwordRequired) passwordRequired.textContent = "*";
        if (passwordHelp) passwordHelp.textContent = "Minimal 8 karakter.";
        setRoleOptions();
        if (statusInput) statusInput.value = "aktif";
    }

    function openUserForm() {
        if (!userFormOverlay) return;
        userFormOverlay.hidden = false;
        document.body.classList.add("user-form-open");
        requestAnimationFrame(function () {
            userFormOverlay.classList.add("is-visible");
        });
        setTimeout(function () {
            if (usernameInput) usernameInput.focus();
        }, 120);
    }

    function closeUserForm() {
        if (!userFormOverlay) return;
        userFormOverlay.classList.remove("is-visible");
        document.body.classList.remove("user-form-open");
        setTimeout(function () {
            userFormOverlay.hidden = true;
            resetUserForm();
        }, 180);
    }

    if (addUserButton) {
        addUserButton.addEventListener("click", function () {
            resetUserForm();
            openUserForm();
        });
    }
    if (userFormClose) userFormClose.addEventListener("click", closeUserForm);
    if (userFormCancel) userFormCancel.addEventListener("click", closeUserForm);
    if (userFormOverlay) {
        userFormOverlay.addEventListener("click", function (event) {
            if (event.target === userFormOverlay) closeUserForm();
        });
    }

    /* =====================================================
       PASSWORD TOGGLE
       ===================================================== */
    document.querySelectorAll(".password-toggle").forEach(function (button) {
        button.addEventListener("click", function () {
            const target = document.getElementById(button.dataset.target);
            if (!target) return;
            const showing = target.type === "text";
            target.type = showing ? "password" : "text";
            button.setAttribute("aria-label", showing ? "Tampilkan password" : "Sembunyikan password");
        });
    });

    /* =====================================================
       EDIT
       ===================================================== */
    document.querySelectorAll(".mp-action-edit").forEach(function (button) {
        button.addEventListener("click", async function () {
            const userId = button.dataset.userId;
            if (!userId) return;

            try {
                button.disabled = true;
                const response = await fetch(`/pengguna/${userId}`, requestOptions("GET"));
                if (!response.ok) throw new Error(await getErrorMessage(response));

                const result = await response.json();
                const user = result.data;
                if (!user) throw new Error("Data pengguna tidak ditemukan.");

                editMode = true;
                if (userIdInput) userIdInput.value = user.id;
                if (usernameInput) usernameInput.value = user.username ?? "";
                if (namaLengkapInput) namaLengkapInput.value = user.nama_lengkap ?? "";
                if (emailInput) emailInput.value = user.email ?? "";
                if (nipInput) nipInput.value = user.nip ?? "";
                if (statusInput) statusInput.value = user.status ?? "aktif";

                setRoleOptions(user.role);

                if (passwordInput) {
                    passwordInput.value = "";
                    passwordInput.required = false;
                }
                if (passwordRequired) passwordRequired.textContent = "";
                if (passwordHelp) passwordHelp.textContent = "Kosongkan jika tidak ingin mengubah password.";
                if (userFormTitle) userFormTitle.textContent = "Ubah Pengguna";
                if (userFormDescription) userFormDescription.textContent = "Perbarui data akun pengguna";
                if (submitButton) {
                    submitButton.textContent = "Simpan Perubahan";
                    submitButton.disabled = false;
                }

                openUserForm();
            } catch (error) {
                console.error(error);
                showToast(error.message || "Gagal mengambil data pengguna.", "error");
            } finally {
                button.disabled = false;
            }
        });
    });

    /* =====================================================
       SUBMIT TAMBAH / EDIT
       ===================================================== */
    if (userForm) {
        userForm.addEventListener("invalid", function () {
            showToast("Lengkapi semua field wajib pada form pengguna.", "error");
        }, true);

        userForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            if (!userForm.checkValidity()) {
                userForm.reportValidity();
                return;
            }
            if (!submitButton) return;

            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = editMode ? "Menyimpan..." : "Menambahkan...";

            const payload = {
                username: usernameInput?.value.trim() || "",
                nama_lengkap: namaLengkapInput?.value.trim() || "",
                email: emailInput?.value.trim() || "",
                role: roleInput?.value || "",
                status: statusInput?.value || "aktif",
                nip: nipInput?.value.trim() || "",
            };
            if (passwordInput && passwordInput.value) payload.password = passwordInput.value;

            try {
                const userId = userIdInput?.value;
                const url = editMode && userId ? `/pengguna/${userId}` : "/pengguna";
                const method = editMode ? "PUT" : "POST";
                const response = await fetch(url, requestOptions(method, payload));
                if (!response.ok) throw new Error(await getErrorMessage(response));

                const result = await response.json();
                showToast(result.message || (editMode ? "Pengguna berhasil diperbarui." : "Pengguna berhasil ditambahkan."), "success");
                setTimeout(function () { window.location.reload(); }, 500);
            } catch (error) {
                console.error(error);
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                showToast(error.message || "Gagal menyimpan pengguna.", "error");
            }
        });
    }

    /* =====================================================
       STATUS AKTIF / TIDAK AKTIF
       ===================================================== */
    document.querySelectorAll(".mp-action-status").forEach(function (button) {
        button.addEventListener("click", async function () {
            const userId = button.dataset.userId;
            const username = button.dataset.username || "Pengguna";
            const currentStatus = button.dataset.status;
            if (!userId) return;

            const newStatus = currentStatus === "aktif" ? "tidak_aktif" : "aktif";
            const isActivating = newStatus === "aktif";
            const confirmed = await confirmUserAction({
                title: isActivating ? "Aktifkan Pengguna?" : "Nonaktifkan Pengguna?",
                message: isActivating
                    ? `Apakah Anda yakin ingin mengaktifkan kembali akun <strong>"${username}"</strong>?`
                    : `Apakah Anda yakin ingin menonaktifkan akun <strong>"${username}"</strong>? Pengguna yang dinonaktifkan tidak dapat masuk ke sistem.`,
                confirmText: isActivating ? "Aktifkan" : "Nonaktifkan",
                danger: !isActivating,
            });
            if (!confirmed) return;

            button.disabled = true;
            try {
                const response = await fetch(`/pengguna/${userId}/status`, requestOptions("PATCH", { status: newStatus }));
                if (!response.ok) throw new Error(await getErrorMessage(response));

                const result = await response.json();
                button.dataset.status = newStatus;

                const icon = button.querySelector(".mp-icon-key");
                if (icon) icon.classList.toggle("status-inactive", newStatus === "tidak_aktif");

                const isActive = newStatus === "aktif";
                button.setAttribute("aria-label", isActive ? `Nonaktifkan akun ${username}` : `Aktifkan akun ${username}`);
                button.setAttribute("title", isActive ? "Nonaktifkan akun" : "Aktifkan akun");

                const badge = document.querySelector(`[data-status-badge="${userId}"]`);
                if (badge) badge.textContent = isActive ? "Aktif" : "Tidak Aktif";

                showToast(result.message || (isActive ? "Akun berhasil diaktifkan." : "Akun berhasil dinonaktifkan."), "success");
            } catch (error) {
                console.error(error);
                showToast(error.message || "Gagal mengubah status akun.", "error");
            } finally {
                button.disabled = false;
            }
        });
    });

    /* =====================================================
       DELETE
       ===================================================== */
    document.querySelectorAll(".mp-action-delete").forEach(function (button) {
        button.addEventListener("click", async function () {
            const userId = button.dataset.userId;
            const username = button.dataset.username || "Pengguna";
            if (!userId) return;

            const confirmed = await confirmUserAction({
                title: "Hapus Pengguna?",
                message: `Apakah Anda yakin ingin menghapus akun <strong>"${username}"</strong>? Tindakan ini tidak dapat dibatalkan.`,
                confirmText: "Hapus",
                danger: true,
            });
            if (!confirmed) return;

            button.disabled = true;
            try {
                const response = await fetch(`/pengguna/${userId}`, requestOptions("DELETE"));
                if (!response.ok) throw new Error(await getErrorMessage(response));

                const result = await response.json();
                showToast(result.message || "Pengguna berhasil dihapus.", "success");
                setTimeout(function () { window.location.reload(); }, 500);
            } catch (error) {
                console.error(error);
                button.disabled = false;
                showToast(error.message || "Gagal menghapus pengguna.", "error");
            }
        });
    });

    /* =====================================================
       ESCAPE
       ===================================================== */
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            if (confirmationOverlay && !confirmationOverlay.hidden) {
                closeConfirmation(false);
                return;
            }
            if (userFormOverlay && !userFormOverlay.hidden) closeUserForm();
        }
    });
});
