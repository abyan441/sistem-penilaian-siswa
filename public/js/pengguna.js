document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       FORM TAMBAH PENGGUNA
       ===================================================== */

    const addUserButton = document.querySelector("#open-user-form");

    const userFormOverlay = document.querySelector("#user-form-overlay");

    const userForm = document.querySelector("#user-form");

    const userFormClose = document.querySelector("#user-form-close");

    const userFormCancel = document.querySelector("#user-form-cancel");

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

        const firstInput = document.querySelector("#username");

        if (firstInput) {
            setTimeout(function () {
                firstInput.focus();
            }, 120);
        }
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
        }, 180);
    }

    /* =====================================================
       BUTTON TAMBAH
       ===================================================== */

    if (addUserButton) {
        addUserButton.addEventListener("click", openUserForm);
    }

    /* =====================================================
       BUTTON CLOSE
       ===================================================== */

    if (userFormClose) {
        userFormClose.addEventListener("click", closeUserForm);
    }

    /* =====================================================
       BUTTON BATAL
       ===================================================== */

    if (userFormCancel) {
        userFormCancel.addEventListener("click", closeUserForm);
    }

    /* =====================================================
       KLIK AREA LUAR MODAL
       ===================================================== */

    if (userFormOverlay) {
        userFormOverlay.addEventListener("click", function (event) {
            if (event.target === userFormOverlay) {
                closeUserForm();
            }
        });
    }

    /* =====================================================
       TOGGLE PASSWORD
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
       SUBMIT FORM
       ===================================================== */

    if (userForm) {
        userForm.addEventListener(
            "invalid",
            function () {
                showAppToast("Lengkapi semua field wajib pada form pengguna.");
            },
            true,
        );

        userForm.addEventListener("submit", function (event) {
            event.preventDefault();

            /*
             * Sementara masih UI / demo.
             *
             * Belum terhubung dengan database
             * atau controller Laravel.
             */

            const submitButton = userForm.querySelector(".user-form-submit");

            if (!submitButton) {
                return;
            }

            const originalText = submitButton.textContent;

            submitButton.textContent = "Tersimpan (Demo)";

            submitButton.disabled = true;

            setTimeout(function () {
                submitButton.textContent = originalText;

                submitButton.disabled = false;

                userForm.reset();

                const statusField = document.querySelector("#status");

                if (statusField) {
                    statusField.value = "aktif";
                }

                closeUserForm();

                showAppToast("Data pengguna berhasil ditambahkan.", "success");
            }, 650);
        });
    }

    /* =====================================================
       ESCAPE -> CLOSE MODAL
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

    /* =====================================================
       AKSI EDIT
       ===================================================== */

    document.querySelectorAll(".mp-action-edit").forEach(function (button) {
        button.addEventListener("click", function () {
            const username = button.dataset.username || "Pengguna";

            showAppToast(
                "Data pengguna " + username + " siap diubah.",
                "success",
            );
        });
    });

    /* =====================================================
       AKSI PASSWORD
       ===================================================== */

    document.querySelectorAll(".mp-action-password").forEach(function (button) {
        button.addEventListener("click", function () {
            const username = button.dataset.username || "Pengguna";

            showAppToast(
                "Kata sandi pengguna " + username + " siap diubah.",
                "success",
            );
        });
    });

    /* =====================================================
       AKSI HAPUS
       ===================================================== */

    document.querySelectorAll(".mp-action-delete").forEach(function (button) {
        button.addEventListener("click", function () {
            const username = button.dataset.username || "Pengguna";

            const confirmed = window.confirm(
                'Apakah Anda yakin ingin menghapus pengguna "' +
                    username +
                    '"?',
            );

            if (!confirmed) {
                return;
            }

            /*
             * Sementara masih UI / demo.
             * Belum menghapus dari database.
             */

            showAppToast(
                'Pengguna "' + username + '" berhasil dihapus (Demo).',
                "success",
            );
        });
    });
});
