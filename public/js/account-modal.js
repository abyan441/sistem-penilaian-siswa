document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       MODAL
       ===================================================== */

    const emailModal = document.querySelector("#change-email-modal");

    const passwordModal = document.querySelector("#change-password-modal");

    /* =====================================================
       TRIGGER MODAL
       ===================================================== */

    const modalTriggers = document.querySelectorAll(
        "[data-open-account-modal]",
    );

    /* =====================================================
       CLOSE BUTTON
       ===================================================== */

    const modalCloseButtons = document.querySelectorAll(
        "[data-close-account-modal]",
    );

    /* =====================================================
       PASSWORD TOGGLE
       ===================================================== */

    const passwordToggles = document.querySelectorAll("[data-password-toggle]");

    /* =====================================================
       ELEMENT YANG TERAKHIR FOCUS
       ===================================================== */

    let lastFocusedElement = null;

    /* =====================================================
       MENCARI MODAL
       ===================================================== */

    function getModal(type) {
        if (type === "email") {
            return emailModal;
        }

        if (type === "password") {
            return passwordModal;
        }

        return null;
    }

    /* =====================================================
       MENUTUP DROPDOWN AKUN
       ===================================================== */

    function closeAccountDropdown() {
        const accountDropdown = document.querySelector("#account-dropdown");

        const accountButton = document.querySelector(".frame-10");

        const settingsToggle = document.querySelector(
            ".account-settings-toggle",
        );

        const settingsSubmenu = document.querySelector(
            "#account-settings-submenu",
        );

        if (accountDropdown) {
            accountDropdown.hidden = true;
        }

        if (accountButton) {
            accountButton.setAttribute("aria-expanded", "false");
        }

        if (settingsToggle) {
            settingsToggle.setAttribute("aria-expanded", "false");

            settingsToggle.classList.remove("is-open");
        }

        if (settingsSubmenu) {
            settingsSubmenu.hidden = true;
        }
    }

    /* =====================================================
       MEMBUKA MODAL
       ===================================================== */

    function openModal(modal) {
        if (!modal) {
            return;
        }

        lastFocusedElement = document.activeElement;

        closeAccountDropdown();

        modal.hidden = false;

        document.body.classList.add("account-modal-open");

        const firstInput = modal.querySelector("input:not([readonly])");

        if (firstInput) {
            window.setTimeout(function () {
                firstInput.focus();
            }, 50);
        }
    }

    /* =====================================================
       MENUTUP MODAL
       ===================================================== */

    function closeModal(modal) {
        if (!modal) {
            return;
        }

        modal.hidden = true;

        document.body.classList.remove("account-modal-open");

        const activeModal = document.querySelector(
            ".account-modal:not([hidden])",
        );

        if (!activeModal) {
            if (
                lastFocusedElement &&
                typeof lastFocusedElement.focus === "function"
            ) {
                lastFocusedElement.focus();
            }

            lastFocusedElement = null;
        }
    }

    /* =====================================================
       TRIGGER PEMBUKAAN MODAL
       ===================================================== */

    modalTriggers.forEach(function (trigger) {
        trigger.addEventListener("click", function (event) {
            event.preventDefault();

            event.stopPropagation();

            const modalType = trigger.dataset.openAccountModal;

            const modal = getModal(modalType);

            openModal(modal);
        });
    });

    /* =====================================================
       TRIGGER PENUTUPAN MODAL
       ===================================================== */

    modalCloseButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            const modal = button.closest(".account-modal");

            closeModal(modal);
        });
    });

    /* =====================================================
       ESCAPE
       ===================================================== */

    document.addEventListener("keydown", function (event) {
        if (event.key !== "Escape") {
            return;
        }

        const activeModal = document.querySelector(
            ".account-modal:not([hidden])",
        );

        if (activeModal) {
            closeModal(activeModal);
        }
    });

    /* =====================================================
       SHOW / HIDE PASSWORD
       ===================================================== */

    passwordToggles.forEach(function (toggle) {
        toggle.addEventListener("click", function () {
            const inputId = toggle.dataset.passwordToggle;

            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            const isPassword = input.type === "password";

            input.type = isPassword ? "text" : "password";

            toggle.setAttribute(
                "aria-label",
                isPassword ? "Sembunyikan password" : "Tampilkan password",
            );
        });
    });

    /* =====================================================
       FORM UBAH EMAIL
       SEMENTARA DUMMY / UI
       ===================================================== */

    const emailForm = document.querySelector("#change-email-form");

    if (emailForm) {
        emailForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const newEmail = document.querySelector("#new-email");

            const confirmEmail = document.querySelector("#confirm-email");

            if (
                newEmail &&
                confirmEmail &&
                newEmail.value !== confirmEmail.value
            ) {
                showAppToast("Email baru dan konfirmasi email harus sama.");

                confirmEmail.focus();

                return;
            }

            showAppToast("Perubahan email berhasil disimpan.", "success");

            emailForm.reset();

            closeModal(emailModal);
        });
    }

    /* =====================================================
       FORM UBAH PASSWORD
       SEMENTARA DUMMY / UI
       ===================================================== */

    const passwordForm = document.querySelector("#change-password-form");

    if (passwordForm) {
        passwordForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const newPassword = document.querySelector("#new-password");

            const confirmPassword = document.querySelector("#confirm-password");

            if (
                newPassword &&
                confirmPassword &&
                newPassword.value !== confirmPassword.value
            ) {
                showAppToast(
                    "Password baru dan konfirmasi password harus sama.",
                );

                confirmPassword.focus();

                return;
            }

            showAppToast("Password berhasil diperbarui.", "success");

            passwordForm.reset();

            closeModal(passwordModal);
        });
    }
});
