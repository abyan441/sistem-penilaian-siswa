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

    const userFormDescription = document.querySelector(
        "#user-form-description",
    );

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

    /* =====================================================
       CSRF
       ===================================================== */

    const csrfToken =
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content") ||
        document.querySelector('input[name="_token"]')?.value ||
        "";

    /* =====================================================
       STATE
       ===================================================== */

    let editMode = false;

    /* =====================================================
       HELPER TOAST
       ===================================================== */

    function showToast(message, type = "success") {
        if (typeof window.showAppToast === "function") {
            window.showAppToast(message, type);

            return;
        }

        alert(message);
    }

    /* =====================================================
       API ERROR
       ===================================================== */

    async function getErrorMessage(response) {
        try {
            const result = await response.json();

            if (result.message && typeof result.message === "string") {
                return result.message;
            }

            if (result.errors) {
                const firstError = Object.values(result.errors).flat()[0];

                if (firstError) {
                    return firstError;
                }
            }

            return "Terjadi kesalahan pada server.";
        } catch (error) {
            return "Terjadi kesalahan pada server.";
        }
    }

    /* =====================================================
       FETCH OPTIONS
       ===================================================== */

    function requestOptions(method = "GET", body = null) {
        const options = {
            method,
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        };

        if (csrfToken) {
            options.headers["X-CSRF-TOKEN"] = csrfToken;
        }

        if (body !== null) {
            options.headers["Content-Type"] = "application/json";

            options.body = JSON.stringify(body);
        }

        return options;
    }

    /* =====================================================
       ROLE OPTION
       ===================================================== */

    function setRoleOptions(currentRole = null) {
        if (!roleInput) {
            return;
        }

        /*
         * Simpan role saat ini.
         */
        const roles = [
            {
                value: "guru",
                label: "Guru",
            },
        ];

        /*
         * Kepala Sekolah hanya dimunculkan
         * jika sedang edit kepala sekolah.
         *
         * Saat tambah, backend/Blade akan
         * mengatur apakah role ini tersedia.
         */
        if (currentRole === "kepala_sekolah") {
            roles.unshift({
                value: "kepala_sekolah",
                label: "Kepala Sekolah",
            });
        } else {
            /*
             * Jika option kepala sekolah
             * sudah tersedia dari Blade,
             * jangan dihapus.
             */
            const existingHeadmaster = roleInput.querySelector(
                'option[value="kepala_sekolah"]',
            );

            if (existingHeadmaster) {
                roles.unshift({
                    value: "kepala_sekolah",
                    label: "Kepala Sekolah",
                });
            }
        }

        /*
         * Admin hanya dimunculkan ketika
         * sedang mengedit akun yang memang
         * sudah menjadi admin.
         */
        if (currentRole === "admin") {
            roles.unshift({
                value: "admin",
                label: "Administrator",
            });
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
       RESET FORM
       ===================================================== */

    function resetUserForm() {
        if (!userForm) {
            return;
        }

        userForm.reset();

        if (userIdInput) {
            userIdInput.value = "";
        }

        editMode = false;

        if (userFormTitle) {
            userFormTitle.textContent = "Tambah Pengguna";
        }

        if (userFormDescription) {
            userFormDescription.textContent =
                "Tambahkan akun pengguna baru ke sistem E-Raport";
        }

        if (submitButton) {
            submitButton.textContent = "Simpan Pengguna";

            submitButton.disabled = false;
        }

        if (passwordInput) {
            passwordInput.value = "";

            passwordInput.required = true;
        }

        if (passwordRequired) {
            passwordRequired.textContent = "*";
        }

        if (passwordHelp) {
            passwordHelp.textContent = "Minimal 8 karakter.";
        }

        /*
         * Kembalikan role ke mode tambah.
         */
        setRoleOptions();

        if (statusInput) {
            statusInput.value = "aktif";
        }
    }

    /* =====================================================
       OPEN MODAL
       ===================================================== */

    function openUserForm() {
        if (!userFormOverlay) {
            return;
        }

        userFormOverlay.hidden = false;

        document.body.classList.add("user-form-open");

        requestAnimationFrame(function () {
            userFormOverlay.classList.add("is-visible");
        });

        setTimeout(function () {
            if (usernameInput) {
                usernameInput.focus();
            }
        }, 120);
    }

    /* =====================================================
       CLOSE MODAL
       ===================================================== */

    function closeUserForm() {
        if (!userFormOverlay) {
            return;
        }

        userFormOverlay.classList.remove("is-visible");

        document.body.classList.remove("user-form-open");

        setTimeout(function () {
            userFormOverlay.hidden = true;

            resetUserForm();
        }, 180);
    }

    /* =====================================================
       TAMBAH PENGGUNA
       ===================================================== */

    if (addUserButton) {
        addUserButton.addEventListener("click", function () {
            resetUserForm();

            openUserForm();
        });
    }

    /* =====================================================
       CLOSE
       ===================================================== */

    if (userFormClose) {
        userFormClose.addEventListener("click", closeUserForm);
    }

    if (userFormCancel) {
        userFormCancel.addEventListener("click", closeUserForm);
    }

    /* =====================================================
       KLIK LUAR MODAL
       ===================================================== */

    if (userFormOverlay) {
        userFormOverlay.addEventListener("click", function (event) {
            if (event.target === userFormOverlay) {
                closeUserForm();
            }
        });
    }

    /* =====================================================
       PASSWORD TOGGLE
       ===================================================== */

    document.querySelectorAll(".password-toggle").forEach(function (button) {
        button.addEventListener("click", function () {
            const target = document.getElementById(button.dataset.target);

            if (!target) {
                return;
            }

            const showing = target.type === "text";

            target.type = showing ? "password" : "text";

            button.setAttribute(
                "aria-label",
                showing ? "Tampilkan password" : "Sembunyikan password",
            );
        });
    });

    /* =====================================================
       EDIT
       ===================================================== */

    document.querySelectorAll(".mp-action-edit").forEach(function (button) {
        button.addEventListener("click", async function () {
            const userId = button.dataset.userId;

            if (!userId) {
                return;
            }

            try {
                button.disabled = true;

                const response = await fetch(
                    `/pengguna/${userId}`,
                    requestOptions("GET"),
                );

                if (!response.ok) {
                    throw new Error(await getErrorMessage(response));
                }

                const result = await response.json();

                const user = result.data;

                if (!user) {
                    throw new Error("Data pengguna tidak ditemukan.");
                }

                editMode = true;

                /*
                 * ID
                 */
                if (userIdInput) {
                    userIdInput.value = user.id;
                }

                /*
                 * Username
                 */
                if (usernameInput) {
                    usernameInput.value = user.username ?? "";
                }

                /*
                 * Nama lengkap
                 */
                if (namaLengkapInput) {
                    namaLengkapInput.value = user.nama_lengkap ?? "";
                }

                /*
                 * Email
                 */
                if (emailInput) {
                    emailInput.value = user.email ?? "";
                }

                /*
                 * NIP
                 */
                if (nipInput) {
                    nipInput.value = user.nip ?? "";
                }

                /*
                 * Status
                 */
                if (statusInput) {
                    statusInput.value = user.status ?? "aktif";
                }

                /*
                 * Role
                 *
                 * Admin hanya muncul
                 * jika memang sedang
                 * mengedit akun admin.
                 *
                 * Kepala sekolah tetap
                 * muncul saat edit akun
                 * kepala sekolah.
                 */
                setRoleOptions(user.role);

                /*
                 * PASSWORD
                 *
                 * Sengaja dikosongkan.
                 * Password lama tidak pernah
                 * ditampilkan.
                 */
                if (passwordInput) {
                    passwordInput.value = "";

                    passwordInput.required = false;
                }

                if (passwordRequired) {
                    passwordRequired.textContent = "";
                }

                if (passwordHelp) {
                    passwordHelp.textContent =
                        "Kosongkan jika tidak ingin mengubah password.";
                }

                /*
                 * Judul
                 */
                if (userFormTitle) {
                    userFormTitle.textContent = "Ubah Pengguna";
                }

                if (userFormDescription) {
                    userFormDescription.textContent =
                        "Perbarui data akun pengguna";
                }

                if (submitButton) {
                    submitButton.textContent = "Simpan Perubahan";
                    submitButton.disabled = false;
                }

                openUserForm();
            } catch (error) {
                console.error(error);

                showToast(
                    error.message || "Gagal mengambil data pengguna.",
                    "error",
                );
            } finally {
                button.disabled = false;
            }
        });
    });

    /* =====================================================
       SUBMIT TAMBAH / EDIT
       ===================================================== */

    if (userForm) {
        userForm.addEventListener(
            "invalid",
            function () {
                showToast(
                    "Lengkapi semua field wajib pada form pengguna.",
                    "error",
                );
            },
            true,
        );

        userForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            if (!userForm.checkValidity()) {
                userForm.reportValidity();
                return;
            }

            if (!submitButton) {
                return;
            }

            const originalText = submitButton.textContent;

            submitButton.disabled = true;

            submitButton.textContent = editMode
                ? "Menyimpan..."
                : "Menambahkan...";

            const payload = {
                username: usernameInput?.value.trim() || "",

                nama_lengkap: namaLengkapInput?.value.trim() || "",

                email: emailInput?.value.trim() || "",

                role: roleInput?.value || "",

                status: statusInput?.value || "aktif",

                nip: nipInput?.value.trim() || "",
            };

            /*
             * Password hanya dikirim jika
             * memang diisi.
             */
            if (passwordInput && passwordInput.value) {
                payload.password = passwordInput.value;
            }

            try {
                const userId = userIdInput?.value;

                const url =
                    editMode && userId ? `/pengguna/${userId}` : "/pengguna";

                const method = editMode ? "PUT" : "POST";

                const response = await fetch(
                    url,
                    requestOptions(method, payload),
                );

                if (!response.ok) {
                    throw new Error(await getErrorMessage(response));
                }

                const result = await response.json();

                showToast(
                    result.message ||
                        (editMode
                            ? "Pengguna berhasil diperbarui."
                            : "Pengguna berhasil ditambahkan."),
                    "success",
                );

                setTimeout(function () {
                    window.location.reload();
                }, 500);
            } catch (error) {
                console.error(error);

                submitButton.disabled = false;

                submitButton.textContent = originalText;

                showToast(
                    error.message || "Gagal menyimpan pengguna.",
                    "error",
                );
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

            if (!userId) {
                return;
            }

            const newStatus =
                currentStatus === "aktif" ? "tidak_aktif" : "aktif";

            const actionText =
                newStatus === "aktif" ? "mengaktifkan" : "menonaktifkan";

            const confirmed = window.confirm(
                `Apakah Anda yakin ingin ${actionText} akun "${username}"?`,
            );

            if (!confirmed) {
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(
                    `/pengguna/${userId}/status`,
                    requestOptions("PATCH", {
                        status: newStatus,
                    }),
                );

                if (!response.ok) {
                    throw new Error(await getErrorMessage(response));
                }

                const result = await response.json();

                /*
                 * Update data tombol.
                 */
                button.dataset.status = newStatus;

                /*
                 * Update icon.
                 */
                const icon = button.querySelector(".mp-icon-key");

                if (icon) {
                    icon.classList.toggle(
                        "status-inactive",
                        newStatus === "tidak_aktif",
                    );
                }

                /*
                 * Update label tombol.
                 */
                const isActive = newStatus === "aktif";

                const ariaLabel = isActive
                    ? `Nonaktifkan akun ${username}`
                    : `Aktifkan akun ${username}`;

                const title = isActive ? "Nonaktifkan akun" : "Aktifkan akun";

                button.setAttribute("aria-label", ariaLabel);

                button.setAttribute("title", title);

                /*
                 * Update badge status.
                 */
                const badge = document.querySelector(
                    `[data-status-badge="${userId}"]`,
                );

                if (badge) {
                    badge.textContent = isActive ? "Aktif" : "Tidak Aktif";
                }

                showToast(
                    result.message ||
                        (isActive
                            ? "Akun berhasil diaktifkan."
                            : "Akun berhasil dinonaktifkan."),
                    "success",
                );
            } catch (error) {
                console.error(error);

                showToast(
                    error.message || "Gagal mengubah status akun.",
                    "error",
                );
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

            if (!userId) {
                return;
            }

            const confirmed = window.confirm(
                `Apakah Anda yakin ingin menghapus pengguna "${username}"?`,
            );

            if (!confirmed) {
                return;
            }

            button.disabled = true;

            try {
                const response = await fetch(
                    `/pengguna/${userId}`,
                    requestOptions("DELETE"),
                );

                if (!response.ok) {
                    throw new Error(await getErrorMessage(response));
                }

                const result = await response.json();

                showToast(
                    result.message || "Pengguna berhasil dihapus.",
                    "success",
                );

                setTimeout(function () {
                    window.location.reload();
                }, 500);
            } catch (error) {
                console.error(error);

                button.disabled = false;

                showToast(
                    error.message || "Gagal menghapus pengguna.",
                    "error",
                );
            }
        });
    });

    /* =====================================================
       ESCAPE
       ===================================================== */

    document.addEventListener("keydown", function (event) {
        if (
            event.key === "Escape" &&
            userFormOverlay &&
            !userFormOverlay.hidden
        ) {
            closeUserForm();
        }
    });
});
